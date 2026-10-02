<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $products = Product::with('category')
            ->where('status', true)

            ->when($request->filled('keyword'), function ($query) use ($request) {
                $keyword = $request->string('keyword')->trim()->toString();

                $query->where(function ($subQuery) use ($keyword) {
                    $subQuery
                        ->where('name', 'like', "%{$keyword}%")
                        ->orWhere('description', 'like', "%{$keyword}%")
                        ->orWhere('sku', 'like', "%{$keyword}%");
                });
            })

            ->when($request->filled('category'), function ($query) use ($request) {
                $query->whereHas('category', function ($categoryQuery) use ($request) {
                    $categoryQuery->where(
                        'slug',
                        $request->string('category')->trim()->toString()
                    );
                });
            })

            ->when($request->filled('min_price'), function ($query) use ($request) {
                $query->whereRaw(
                    'COALESCE(sale_price, price) >= ?',
                    [$request->input('min_price')]
                );
            })

            ->when($request->filled('max_price'), function ($query) use ($request) {
                $query->whereRaw(
                    'COALESCE(sale_price, price) <= ?',
                    [$request->input('max_price')]
                );
            })

            ->when(
                $request->input('sort') === 'price_asc',
                fn ($query) => $query->orderByRaw(
                    'COALESCE(sale_price, price) ASC'
                )
            )

            ->when(
                $request->input('sort') === 'price_desc',
                fn ($query) => $query->orderByRaw(
                    'COALESCE(sale_price, price) DESC'
                )
            )

            ->when(
                !in_array($request->input('sort'), ['price_asc', 'price_desc']),
                fn ($query) => $query->latest()
            )

            ->paginate(12)
            ->withQueryString();

        $categories = Category::where('status', true)
            ->orderBy('name')
            ->get();

        return view(
            'products.index',
            compact('products', 'categories')
        );
    }

    public function show(Product $product): View
    {
        abort_unless($product->status, 404);

        $product->load([
            'category',
            'images',
            'reviews' => function ($query) {
                $query->where('status', true)
                    ->with('user')
                    ->latest();
            },
        ]);

        $relatedProducts = Product::with('category')
            ->where('status', true)
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->latest()
            ->take(4)
            ->get();

        return view(
            'products.show',
            compact('product', 'relatedProducts')
        );
    }
}