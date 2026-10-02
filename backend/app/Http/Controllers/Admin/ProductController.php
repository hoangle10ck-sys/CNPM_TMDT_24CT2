<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $products = Product::with('category')
            ->when($request->filled('keyword'), function ($query) use ($request) {
                $keyword = $request->string('keyword')->trim()->toString();

                $query->where(function ($subQuery) use ($keyword) {
                    $subQuery
                        ->where('name', 'like', "%{$keyword}%")
                        ->orWhere('sku', 'like', "%{$keyword}%");
                });
            })
            ->when($request->filled('category_id'), function ($query) use ($request) {
                $query->where('category_id', $request->integer('category_id'));
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->boolean('status'));
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view(
            'admin.products.index',
            compact('products', 'categories')
        );
    }

    public function create(): View
    {
        $categories = Category::where('status', true)
            ->orderBy('name')
            ->get();

        return view('admin.products.create', compact('categories'));
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $data['slug'] = $this->createUniqueSlug($data['name']);
        $data['status'] = $request->boolean('status');
        $data['is_featured'] = $request->boolean('is_featured');

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request
                ->file('thumbnail')
                ->store('products', 'public');
        } else {
            unset($data['thumbnail']);
        }

        Product::create($data);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Thêm sản phẩm thành công.');
    }

    public function show(Product $product): RedirectResponse
    {
        return redirect()->route('admin.products.edit', $product);
    }

    public function edit(Product $product): View
    {
        $categories = Category::orderBy('name')->get();

        return view(
            'admin.products.edit',
            compact('product', 'categories')
        );
    }

    public function update(
        UpdateProductRequest $request,
        Product $product
    ): RedirectResponse {
        $data = $request->validated();

        $data['slug'] = $this->createUniqueSlug(
            $data['name'],
            $product->id
        );

        $data['status'] = $request->boolean('status');
        $data['is_featured'] = $request->boolean('is_featured');

        if ($request->hasFile('thumbnail')) {
            if ($product->thumbnail) {
                Storage::disk('public')->delete($product->thumbnail);
            }

            $data['thumbnail'] = $request
                ->file('thumbnail')
                ->store('products', 'public');
        } else {
            unset($data['thumbnail']);
        }

        $product->update($data);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Cập nhật sản phẩm thành công.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        if ($product->thumbnail) {
            Storage::disk('public')->delete($product->thumbnail);
        }

        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Xóa sản phẩm thành công.');
    }

    private function createUniqueSlug(
        string $name,
        ?int $ignoredProductId = null
    ): string {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $number = 1;

        while (
            Product::where('slug', $slug)
                ->when(
                    $ignoredProductId,
                    fn ($query) => $query->where(
                        'id',
                        '!=',
                        $ignoredProductId
                    )
                )
                ->exists()
        ) {
            $slug = $baseSlug.'-'.$number;
            $number++;
        }

        return $slug;
    }
}