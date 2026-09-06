<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::withCount('products')
            ->latest()
            ->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.categories.create');
    }

    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);
        $data['status'] = $request->boolean('status');

        Category::create($data);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Thêm danh mục thành công.');
    }

    public function show(Category $category): RedirectResponse
    {
        return redirect()->route('admin.categories.edit', $category);
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(
        UpdateCategoryRequest $request,
        Category $category
    ): RedirectResponse {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);
        $data['status'] = $request->boolean('status');

        $category->update($data);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Cập nhật danh mục thành công.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->products()->exists()) {
            return back()->with(
                'error',
                'Không thể xóa danh mục đang chứa sản phẩm.'
            );
        }

        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Xóa danh mục thành công.');
    }
}