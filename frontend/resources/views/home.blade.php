@extends('layouts.store')

@section('title', 'TechStore - Trang chủ')

@section('content')
    <section class="bg-gradient-to-r from-indigo-700 to-purple-700 text-white">
        <div class="mx-auto grid max-w-7xl items-center gap-10 px-4 py-20
                    md:grid-cols-2 sm:px-6 lg:px-8">
            <div>
                <p class="mb-3 font-semibold text-indigo-200">
                    CỬA HÀNG CÔNG NGHỆ
                </p>

                <h1 class="text-4xl font-bold leading-tight md:text-5xl">
                    Công nghệ hiện đại cho cuộc sống tiện nghi
                </h1>

                <p class="mt-6 max-w-xl text-lg text-indigo-100">
                    Khám phá laptop, điện thoại, tai nghe và phụ kiện
                    với mức giá phù hợp.
                </p>

                <div class="mt-8 flex flex-wrap gap-4">
                    <a
                        href="{{ route('products.index') }}"
                        class="rounded-lg bg-white px-6 py-3 font-semibold
                               text-indigo-700 hover:bg-indigo-50"
                    >
                        Mua sắm ngay
                    </a>

                    <a
                        href="#featured-products"
                        class="rounded-lg border border-white px-6 py-3
                               font-semibold text-white hover:bg-white/10"
                    >
                        Sản phẩm nổi bật
                    </a>
                </div>
            </div>

            <div class="hidden justify-center md:flex">
                <div class="flex h-72 w-72 items-center justify-center
                            rounded-full bg-white/10 text-9xl">
                    💻
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h2 class="text-2xl font-bold">Danh mục sản phẩm</h2>
            <p class="mt-2 text-gray-600">
                Chọn danh mục phù hợp với nhu cầu của bạn.
            </p>
        </div>

        <div class="grid grid-cols-2 gap-5 md:grid-cols-4">
            @forelse ($categories as $category)
                <a
                    href="{{ route('products.index', [
                        'category' => $category->slug
                    ]) }}"
                    class="rounded-xl bg-white p-6 text-center shadow-sm
                           transition hover:-translate-y-1 hover:shadow-md"
                >
                    <div class="text-4xl">
                        @switch($category->slug)
                            @case('laptop')
                                💻
                                @break

                            @case('dien-thoai')
                                📱
                                @break

                            @case('tai-nghe')
                                🎧
                                @break

                            @default
                                ⌨️
                        @endswitch
                    </div>

                    <h3 class="mt-4 font-semibold text-gray-900">
                        {{ $category->name }}
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        {{ $category->products_count }} sản phẩm
                    </p>
                </a>
            @empty
                <p class="col-span-full text-gray-500">
                    Chưa có danh mục.
                </p>
            @endforelse
        </div>
    </section>

    <section id="featured-products" class="bg-white py-14">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-8 flex items-end justify-between">
                <div>
                    <h2 class="text-2xl font-bold">Sản phẩm nổi bật</h2>
                    <p class="mt-2 text-gray-600">
                        Những sản phẩm được khách hàng quan tâm.
                    </p>
                </div>

                <a
                    href="{{ route('products.index') }}"
                    class="font-medium text-indigo-600 hover:text-indigo-800"
                >
                    Xem tất cả →
                </a>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @forelse ($featuredProducts as $product)
                    <x-product-card :product="$product" />
                @empty
                    <p class="col-span-full text-gray-500">
                        Chưa có sản phẩm nổi bật.
                    </p>
                @endforelse
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h2 class="text-2xl font-bold">Sản phẩm mới</h2>
            <p class="mt-2 text-gray-600">
                Những sản phẩm mới được thêm vào cửa hàng.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @forelse ($latestProducts as $product)
                <x-product-card :product="$product" />
            @empty
                <p class="col-span-full text-gray-500">
                    Chưa có sản phẩm.
                </p>
            @endforelse
        </div>
    </section>

    <section class="bg-indigo-600 py-12 text-white">
        <div class="mx-auto max-w-7xl px-4 text-center sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold">Mua sắm dễ dàng cùng TechStore</h2>

            <p class="mt-3 text-indigo-100">
                Sản phẩm đa dạng, đặt hàng thuận tiện và hỗ trợ nhanh chóng.
            </p>
        </div>
    </section>
@endsection