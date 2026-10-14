@extends('layouts.store')

@section('title', 'Sản phẩm - TechStore')

@section('content')
    <section class="bg-gradient-to-br from-slate-950 via-indigo-950 to-indigo-800 py-12 text-white sm:py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-indigo-200">Khám phá TechStore</p>
            <h1 class="mt-2 text-3xl font-black tracking-tight sm:text-4xl">Tất cả sản phẩm</h1>

            <p class="mt-2 text-gray-300">
                Tìm sản phẩm phù hợp với nhu cầu của bạn.
            </p>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <form
            method="GET"
            action="{{ route('products.index') }}"
            class="mb-10 grid grid-cols-1 gap-4 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:grid-cols-2 lg:grid-cols-5 lg:p-6"
        >
            <div class="md:col-span-2">
                <label for="product-keyword" class="mb-1 block text-sm font-semibold text-gray-700">
                    Tìm kiếm
                </label>

                <input
                    id="product-keyword"
                    name="keyword"
                    type="text"
                    value="{{ request('keyword') }}"
                    placeholder="Nhập tên hoặc mã sản phẩm"
                    class="block w-full rounded-xl border-gray-300 bg-gray-50/70
                           focus:border-indigo-500 focus:bg-white focus:ring-indigo-500"
                >
            </div>

            <div>
                <label for="product-category" class="mb-1 block text-sm font-semibold text-gray-700">
                    Danh mục
                </label>

                <select
                    id="product-category"
                    name="category"
                    class="block w-full rounded-xl border-gray-300 bg-gray-50/70
                           focus:border-indigo-500 focus:ring-indigo-500"
                >
                    <option value="">Tất cả danh mục</option>

                    @foreach ($categories as $category)
                        <option
                            value="{{ $category->slug }}"
                            @selected(request('category') === $category->slug)
                        >
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="product-sort" class="mb-1 block text-sm font-semibold text-gray-700">
                    Sắp xếp
                </label>

                <select
                    id="product-sort"
                    name="sort"
                    class="block w-full rounded-xl border-gray-300 bg-gray-50/70
                           focus:border-indigo-500 focus:ring-indigo-500"
                >
                    <option value="">Mới nhất</option>

                    <option
                        value="price_asc"
                        @selected(request('sort') === 'price_asc')
                    >
                        Giá thấp đến cao
                    </option>

                    <option
                        value="price_desc"
                        @selected(request('sort') === 'price_desc')
                    >
                        Giá cao đến thấp
                    </option>

                    <option
                        value="best_selling"
                        @selected(request('sort') === 'best_selling')
                    >
                        Bán chạy
                    </option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button
                    type="submit"
                    class="rounded-xl bg-indigo-600 px-4 py-2.5 font-semibold text-white shadow-sm
                           transition hover:bg-indigo-700"
                >
                    Tìm kiếm
                </button>

                <a
                    href="{{ route('products.index') }}"
                    class="rounded-xl bg-gray-100 px-4 py-2.5 font-medium text-gray-700
                           hover:bg-gray-300"
                >
                    Xóa lọc
                </a>
            </div>

            <div>
                <label for="min-price" class="mb-1 block text-sm font-semibold text-gray-700">
                    Giá thấp nhất
                </label>

                <input
                    id="min-price"
                    name="min_price"
                    type="number"
                    min="0"
                    step="1000"
                    value="{{ request('min_price') }}"
                    placeholder="0"
                    class="block w-full rounded-xl border-gray-300 bg-gray-50/70
                           focus:border-indigo-500 focus:ring-indigo-500"
                >
            </div>

            <div>
                <label for="max-price" class="mb-1 block text-sm font-semibold text-gray-700">
                    Giá cao nhất
                </label>

                <input
                    id="max-price"
                    name="max_price"
                    type="number"
                    min="0"
                    step="1000"
                    value="{{ request('max_price') }}"
                    placeholder="20.000.000"
                    class="block w-full rounded-xl border-gray-300 bg-gray-50/70
                           focus:border-indigo-500 focus:ring-indigo-500"
                >
            </div>
        </form>

        <div class="mb-6 flex items-center justify-between">
            <p class="text-gray-600">
                Tìm thấy
                <span class="font-semibold text-gray-900">
                    {{ $products->total() }}
                </span>
                sản phẩm
            </p>
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @forelse ($products as $product)
                <x-product-card :product="$product" />
            @empty
                <div class="col-span-full rounded-2xl border border-gray-200 bg-white p-12 text-center shadow-sm">
                    <div class="text-6xl">🔍</div>

                    <h2 class="mt-4 text-xl font-semibold">
                        Không tìm thấy sản phẩm
                    </h2>

                    <p class="mt-2 text-gray-500">
                        Hãy thử thay đổi từ khóa hoặc bộ lọc.
                    </p>

                    <a
                        href="{{ route('products.index') }}"
                        class="mt-5 inline-block rounded-md bg-indigo-600
                               px-5 py-2.5 text-white hover:bg-indigo-700"
                    >
                        Xem tất cả sản phẩm
                    </a>
                </div>
            @endforelse
        </div>

        <div class="mt-10">
            {{ $products->links() }}
        </div>
    </section>
@endsection
