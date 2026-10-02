@extends('layouts.store')

@section('title', 'Sản phẩm - TechStore')

@section('content')
    <section class="bg-gray-900 py-12 text-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold">Tất cả sản phẩm</h1>

            <p class="mt-2 text-gray-300">
                Tìm sản phẩm phù hợp với nhu cầu của bạn.
            </p>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <form
            method="GET"
            action="{{ route('products.index') }}"
            class="mb-10 grid grid-cols-1 gap-4 rounded-xl bg-white p-6
                   shadow-sm md:grid-cols-5"
        >
            <div class="md:col-span-2">
                <label class="mb-1 block text-sm font-medium text-gray-700">
                    Tìm kiếm
                </label>

                <input
                    name="keyword"
                    type="text"
                    value="{{ request('keyword') }}"
                    placeholder="Nhập tên hoặc mã sản phẩm"
                    class="block w-full rounded-md border-gray-300
                           focus:border-indigo-500 focus:ring-indigo-500"
                >
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">
                    Danh mục
                </label>

                <select
                    name="category"
                    class="block w-full rounded-md border-gray-300
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
                <label class="mb-1 block text-sm font-medium text-gray-700">
                    Sắp xếp
                </label>

                <select
                    name="sort"
                    class="block w-full rounded-md border-gray-300
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
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button
                    type="submit"
                    class="rounded-md bg-indigo-600 px-4 py-2.5 text-white
                           hover:bg-indigo-700"
                >
                    Tìm kiếm
                </button>

                <a
                    href="{{ route('products.index') }}"
                    class="rounded-md bg-gray-200 px-4 py-2.5 text-gray-700
                           hover:bg-gray-300"
                >
                    Xóa lọc
                </a>
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">
                    Giá thấp nhất
                </label>

                <input
                    name="min_price"
                    type="number"
                    min="0"
                    step="1000"
                    value="{{ request('min_price') }}"
                    placeholder="0"
                    class="block w-full rounded-md border-gray-300
                           focus:border-indigo-500 focus:ring-indigo-500"
                >
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">
                    Giá cao nhất
                </label>

                <input
                    name="max_price"
                    type="number"
                    min="0"
                    step="1000"
                    value="{{ request('max_price') }}"
                    placeholder="20.000.000"
                    class="block w-full rounded-md border-gray-300
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
                <div class="col-span-full rounded-xl bg-white p-12 text-center shadow-sm">
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