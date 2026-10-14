<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $products = Product::with('category')
            ->where('status', true)
            ->whereHas('category', function ($query) {
                $query->where('status', true);
            })

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
                    'COALESCE(sale_price, price) >= CAST(? AS DECIMAL(15, 2))',
                    [$request->input('min_price')]
                );
            })

            ->when($request->filled('max_price'), function ($query) use ($request) {
                $query->whereRaw(
                    'COALESCE(sale_price, price) <= CAST(? AS DECIMAL(15, 2))',
                    [$request->input('max_price')]
                );
            })

            ->when(
                $request->input('sort') === 'price_asc',
                fn ($query) => $query->orderByRaw(
                    'COALESCE(sale_price, price) ASC'
                )->orderByDesc('id')
            )

            ->when(
                $request->input('sort') === 'price_desc',
                fn ($query) => $query->orderByRaw(
                    'COALESCE(sale_price, price) DESC'
                )->orderByDesc('id')
            )

            ->when($request->input('sort') === 'best_selling', function ($query) {
                $query->withSum([
                    'orderItems as sold_quantity' => function ($orderItemsQuery) {
                        $orderItemsQuery->whereHas('order', function ($orderQuery) {
                            $orderQuery->where('status', Order::STATUS_COMPLETED);
                        });
                    },
                ], 'quantity')
                    ->orderByDesc('sold_quantity')
                    ->orderByDesc('id');
            })

            ->when(
                ! \in_array(
                    $request->input('sort'),
                    ['price_asc', 'price_desc', 'best_selling'],
                    true
                ),
                fn ($query) => $query->latest()->orderByDesc('id')
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
        abort_unless((bool) $product->getAttribute('status'), 404);

        $product->load(['category' => function ($query) {
            $query->where('status', true);
        }]);

        abort_unless(
            $product->category && (bool) $product->category->getAttribute('status'),
            404
        );

        $product->load([
            'images',
            'reviews' => function ($query) {
                $query->where('status', true)
                    ->with('user')
                    ->latest();
            },
        ]);

        $categoryId = (int) $product->getAttribute('category_id');

        $relatedProducts = Product::with('category')
            ->where('status', true)
            ->whereHas('category', function ($query) {
                $query->where('status', true);
            })
            ->where('category_id', $categoryId)
            ->where('stock', '>', 0)
            ->where('id', '!=', $product->getKey())
            ->latest()
            ->take(4)
            ->get();

        return view(
            'products.show',
            compact('product', 'relatedProducts')
        );
    }
}
