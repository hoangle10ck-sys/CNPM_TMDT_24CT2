@extends('layouts.store')

@section('title', 'TechStore - Trang chủ')

@section('content')
    <section class="bg-gradient-to-r from-indigo-700 via-indigo-700 to-purple-700 text-white">
        <div class="mx-auto grid max-w-7xl items-center gap-10 px-4 py-20 sm:px-6 md:grid-cols-2 lg:px-8">
            <div>
                <p class="mb-3 text-sm font-semibold uppercase tracking-[0.24em] text-indigo-200">
                    Cửa hàng công nghệ
                </p>

                <h1 class="text-4xl font-black leading-tight md:text-5xl">
                    Công nghệ hiện đại cho cuộc sống tiện nghi
                </h1>

                <p class="mt-6 max-w-xl text-lg text-indigo-100">
                    Khám phá laptop, điện thoại, tai nghe và phụ kiện với mức giá hợp lý, giao hàng nhanh và hỗ trợ tận tâm.
                </p>

                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="{{ route('products.index') }}" class="rounded-xl bg-white px-6 py-3 font-semibold text-indigo-700 shadow-sm transition hover:bg-indigo-50">
                        Mua sắm ngay
                    </a>

                    <a href="#featured-products" class="rounded-xl border border-white/50 px-6 py-3 font-semibold text-white transition hover:bg-white/10">
                        Sản phẩm nổi bật
                    </a>
                </div>
            </div>

            <div class="hidden justify-center md:flex">
                <div class="relative flex h-80 w-80 items-center justify-center rounded-full border border-white/20 bg-white/10 text-9xl shadow-2xl backdrop-blur-sm">
                    <div class="absolute inset-6 rounded-full border border-white/20"></div>
                    <span>💻</span>
                </div>
            </div>
        </div>
    </section>

    <div class="mx-auto max-w-7xl px-4 py-5 sm:px-6 lg:px-8">
        <div class="grid gap-4 rounded-3xl bg-white p-4 shadow-sm ring-1 ring-gray-200 md:grid-cols-3">
            <div class="flex items-center gap-3 rounded-2xl bg-indigo-50 p-4">
                <span class="text-2xl">🚚</span>
                <div>
                    <p class="font-semibold text-gray-900">Giao hàng nhanh</p>
                    <p class="text-sm text-gray-600">Trong 24–48h</p>
                </div>
            </div>

            <div class="flex items-center gap-3 rounded-2xl bg-emerald-50 p-4">
                <span class="text-2xl">🛡️</span>
                <div>
                    <p class="font-semibold text-gray-900">Bảo hành rõ ràng</p>
                    <p class="text-sm text-gray-600">Uy tín và minh bạch</p>
                </div>
            </div>

            <div class="flex items-center gap-3 rounded-2xl bg-amber-50 p-4">
                <span class="text-2xl">💬</span>
                <div>
                    <p class="font-semibold text-gray-900">Hỗ trợ 24/7</p>
                    <p class="text-sm text-gray-600">Tư vấn mọi lúc</p>
                </div>
            </div>
        </div>
    </div>

    <section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <div class="mb-8">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-indigo-600">Danh mục</p>
            <h2 class="mt-2 text-2xl font-bold text-gray-900">Danh mục sản phẩm</h2>
            <p class="mt-2 text-gray-600">Chọn danh mục phù hợp với nhu cầu của bạn.</p>
        </div>

        <div class="grid grid-cols-2 gap-5 md:grid-cols-4">
            @forelse ($categories as $category)
                <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="rounded-2xl bg-white p-6 text-center shadow-sm ring-1 ring-gray-200 transition hover:-translate-y-1 hover:shadow-md">
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

                    <h3 class="mt-4 font-semibold text-gray-900">{{ $category->name }}</h3>
                    <p class="mt-1 text-sm text-gray-500">{{ $category->products_count }} sản phẩm</p>
                </a>
            @empty
                <p class="col-span-full text-gray-500">Chưa có danh mục.</p>
            @endforelse
        </div>
    </section>

    <section class="bg-gradient-to-r from-gray-50 to-indigo-50 py-14">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-8 text-center">
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-indigo-600">Vì sao chọn chúng tôi</p>
                <h2 class="mt-2 text-3xl font-bold text-gray-900">Mua sắm an tâm, tiện lợi và đáng tin cậy</h2>
            </div>

            <div class="grid gap-6 md:grid-cols-3">
                <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                    <div class="text-3xl">🚚</div>
                    <h3 class="mt-4 text-lg font-bold text-gray-900">Giao hàng nhanh</h3>
                    <p class="mt-2 text-sm text-gray-600">Vận chuyển tiện lợi trên toàn quốc với thời gian xử lý nhanh.</p>
                </div>

                <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                    <div class="text-3xl">🔒</div>
                    <h3 class="mt-4 text-lg font-bold text-gray-900">Thanh toán an toàn</h3>
                    <p class="mt-2 text-sm text-gray-600">Hệ thống bảo mật, minh bạch và hỗ trợ thanh toán dễ dàng.</p>
                </div>

                <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                    <div class="text-3xl">💬</div>
                    <h3 class="mt-4 text-lg font-bold text-gray-900">Hỗ trợ tận tâm</h3>
                    <p class="mt-2 text-sm text-gray-600">Đội ngũ hỗ trợ sẵn sàng giải đáp mọi thắc mắc của khách hàng.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="featured-products" class="bg-white py-14">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-8 flex items-end justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-indigo-600">Nổi bật</p>
                    <h2 class="mt-2 text-2xl font-bold text-gray-900">Sản phẩm nổi bật</h2>
                    <p class="mt-2 text-gray-600">Những sản phẩm được khách hàng quan tâm.</p>
                </div>

                <a href="{{ route('products.index') }}" class="font-medium text-indigo-600 hover:text-indigo-800">Xem tất cả →</a>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @forelse ($featuredProducts as $product)
                    <x-product-card :product="$product" />
                @empty
                    <p class="col-span-full text-gray-500">Chưa có sản phẩm nổi bật.</p>
                @endforelse
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <div class="mb-8">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-indigo-600">Mới</p>
            <h2 class="mt-2 text-2xl font-bold text-gray-900">Sản phẩm mới</h2>
            <p class="mt-2 text-gray-600">Những sản phẩm mới được thêm vào cửa hàng.</p>
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @forelse ($latestProducts as $product)
                <x-product-card :product="$product" />
            @empty
                <p class="col-span-full text-gray-500">Chưa có sản phẩm.</p>
            @endforelse
        </div>
    </section>

    <section class="bg-gradient-to-r from-indigo-600 to-purple-600 py-12 text-white">
        <div class="mx-auto max-w-7xl px-4 text-center sm:px-6 lg:px-8">
            <h2 class="text-3xl font-black">Mua sắm dễ dàng cùng TechStore</h2>
            <p class="mt-3 text-indigo-100">Sản phẩm đa dạng, đặt hàng thuận tiện và hỗ trợ nhanh chóng.</p>
        </div>
    </section>
@endsection