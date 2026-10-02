@extends('layouts.store')

@section('title', $product->name.' - TechStore')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <nav class="mb-8 text-sm text-gray-500">
            <a
                href="{{ route('home') }}"
                class="hover:text-indigo-600"
            >
                Trang chủ
            </a>

            <span class="mx-2">/</span>

            <a
                href="{{ route('products.index', [
                    'category' => $product->category->slug
                ]) }}"
                class="hover:text-indigo-600"
            >
                {{ $product->category->name }}
            </a>

            <span class="mx-2">/</span>

            <span class="text-gray-900">
                {{ $product->name }}
            </span>
        </nav>

        <div class="grid grid-cols-1 gap-10 rounded-2xl bg-white
                    p-6 shadow-sm lg:grid-cols-2 lg:p-10">
            <div>
                @if ($product->thumbnail)
                    <img
                        src="{{ asset('storage/'.$product->thumbnail) }}"
                        alt="{{ $product->name }}"
                        class="h-[450px] w-full rounded-xl object-contain"
                    >
                @else
                    <div class="flex h-[450px] items-center justify-center
                                rounded-xl bg-gray-100">
                        <div class="text-center text-gray-400">
                            <div class="text-8xl">🛍️</div>

                            <p class="mt-4">
                                Sản phẩm chưa có ảnh
                            </p>
                        </div>
                    </div>
                @endif

                @if ($product->images->isNotEmpty())
                    <div class="mt-4 grid grid-cols-4 gap-3">
                        @foreach ($product->images as $image)
                            <img
                                src="{{ asset(
                                    'storage/'.$image->image_path
                                ) }}"
                                alt="{{ $product->name }}"
                                class="h-24 w-full rounded-lg object-cover"
                            >
                        @endforeach
                    </div>
                @endif
            </div>

            <div>
                <p class="font-medium uppercase tracking-wide
                          text-indigo-600">
                    {{ $product->category->name }}
                </p>

                <h1 class="mt-2 text-3xl font-bold text-gray-900">
                    {{ $product->name }}
                </h1>

                <p class="mt-3 text-sm text-gray-500">
                    Mã sản phẩm:
                    {{ $product->sku ?: 'Đang cập nhật' }}
                </p>

                <div class="mt-5 flex items-center gap-3">
                    @php
                        $averageRating = $product->reviews->isNotEmpty()
                            ? $product->reviews->avg('rating')
                            : 0;
                    @endphp

                    <span class="text-yellow-500">
                        {{ str_repeat(
                            '★',
                            (int) round($averageRating)
                        ) }}

                        <span class="text-gray-300">
                            {{ str_repeat(
                                '★',
                                5 - (int) round($averageRating)
                            ) }}
                        </span>
                    </span>

                    <span class="text-sm text-gray-500">
                        {{ $product->reviews->count() }} đánh giá
                    </span>
                </div>

                <div class="mt-6">
                    @if (
                        $product->sale_price
                        && $product->sale_price < $product->price
                    )
                        <span class="text-3xl font-bold text-red-600">
                            {{ number_format(
                                $product->sale_price,
                                0,
                                ',',
                                '.'
                            ) }}₫
                        </span>

                        <span class="ml-3 text-lg text-gray-400 line-through">
                            {{ number_format(
                                $product->price,
                                0,
                                ',',
                                '.'
                            ) }}₫
                        </span>

                        <span class="ml-3 rounded-md bg-red-100
                                     px-2 py-1 text-sm font-medium
                                     text-red-600">
                            Giảm
                            {{ round(
                                (($product->price - $product->sale_price)
                                / $product->price) * 100
                            ) }}%
                        </span>
                    @else
                        <span class="text-3xl font-bold text-gray-900">
                            {{ number_format(
                                $product->price,
                                0,
                                ',',
                                '.'
                            ) }}₫
                        </span>
                    @endif
                </div>

                <div class="mt-6 rounded-lg bg-gray-50 p-4">
                    @if ($product->stock > 0)
                        <p class="font-medium text-green-600">
                            ✓ Còn hàng
                        </p>

                        <p class="mt-1 text-sm text-gray-500">
                            Hiện còn {{ $product->stock }} sản phẩm.
                        </p>
                    @else
                        <p class="font-medium text-red-600">
                            Sản phẩm đã hết hàng
                        </p>
                    @endif
                </div>

                <div class="mt-8">
                    @if (session('success'))
                        <div
                            x-data="{ show: true }"
                            x-init="setTimeout(() => show = false, 3000)"
                            x-show="show"
                            x-transition
                            class="fixed right-5 top-24 z-50
                                   flex items-center gap-3 rounded-xl
                                   bg-green-600 px-6 py-4 text-white
                                   shadow-xl"
                        >
                            <span class="flex h-8 w-8 items-center
                                         justify-center rounded-full
                                         bg-white text-green-600">
                                ✓
                            </span>

                            <div>
                                <p class="font-semibold">
                                    {{ session('success') }}
                                </p>

                                @auth
                                    @if (!auth()->user()->isAdmin())
                                        <a
                                            href="{{ route('cart.index') }}"
                                            class="text-sm text-green-100
                                                   underline hover:text-white"
                                        >
                                            Xem giỏ hàng
                                        </a>
                                    @endif
                                @endauth
                            </div>

                            <button
                                type="button"
                                @click="show = false"
                                class="ml-3 text-xl text-green-100
                                       hover:text-white"
                            >
                                ×
                            </button>
                        </div>
                    @endif

                    @if ($errors->has('quantity'))
                        <div class="mb-4 rounded-lg bg-red-100
                                    p-4 text-red-700">
                            {{ $errors->first('quantity') }}
                        </div>
                    @endif

                    @auth
                        @if (auth()->user()->isAdmin())
                            <div class="rounded-xl border
                                        border-indigo-200 bg-indigo-50 p-5">
                                <div class="flex flex-col gap-4
                                            sm:flex-row sm:items-center
                                            sm:justify-between">
                                    <div>
                                        <p class="font-semibold
                                                  text-indigo-800">
                                            Chế độ quản trị
                                        </p>

                                        <p class="mt-1 text-sm
                                                  text-indigo-600">
                                            Bạn đang ở chế độ quản trị
                                        </p>
                                    </div>

                                    <a
                                        href="{{ route(
                                            'admin.products.edit',
                                            $product
                                        ) }}"
                                        class="shrink-0 rounded-lg
                                               bg-indigo-600 px-5 py-2.5
                                               text-center font-semibold
                                               text-white
                                               hover:bg-indigo-700"
                                    >
                                        Chỉnh sửa sản phẩm
                                    </a>
                                </div>
                            </div>
                        @elseif ($product->stock > 0)
                            <form
                                method="POST"
                                action="{{ route('cart.store') }}"
                                class="flex items-end gap-4"
                            >
                                @csrf

                                <input
                                    type="hidden"
                                    name="product_id"
                                    value="{{ $product->id }}"
                                >

                                <div>
                                    <label
                                        for="quantity"
                                        class="mb-1 block text-sm
                                               font-medium"
                                    >
                                        Số lượng
                                    </label>

                                    <input
                                        id="quantity"
                                        name="quantity"
                                        type="number"
                                        min="1"
                                        max="{{ $product->stock }}"
                                        value="{{ old('quantity', 1) }}"
                                        class="w-24 rounded-md
                                               border-gray-300
                                               focus:border-indigo-500
                                               focus:ring-indigo-500"
                                        required
                                    >
                                </div>

                                <button
                                    type="submit"
                                    class="flex-1 rounded-lg
                                           bg-indigo-600 px-6 py-3
                                           font-semibold text-white
                                           hover:bg-indigo-700"
                                >
                                    Thêm vào giỏ hàng
                                </button>
                            </form>
                        @else
                            <div class="rounded-lg bg-red-100 p-4
                                        text-center text-red-700">
                                Sản phẩm hiện đã hết hàng.
                            </div>
                        @endif
                    @else
                        <a
                            href="{{ route('login') }}"
                            class="block rounded-lg bg-indigo-600
                                   px-6 py-3 text-center font-semibold
                                   text-white hover:bg-indigo-700"
                        >
                            Đăng nhập để mua hàng
                        </a>
                    @endauth
                </div>

                <div class="mt-8 border-t pt-6">
                    <h2 class="font-semibold text-gray-900">
                        Chính sách mua hàng
                    </h2>

                    <ul class="mt-3 space-y-2 text-sm text-gray-600">
                        <li>
                            ✓ Kiểm tra sản phẩm trước khi nhận hàng.
                        </li>

                        <li>
                            ✓ Hỗ trợ đổi trả theo chính sách cửa hàng.
                        </li>

                        <li>
                            ✓ Thanh toán khi nhận hàng.
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <section class="mt-10 rounded-2xl bg-white p-8 shadow-sm">
            <h2 class="text-2xl font-bold">
                Mô tả sản phẩm
            </h2>

            <div class="mt-5 leading-8 text-gray-700">
                @if ($product->description)
                    {!! nl2br(e($product->description)) !!}
                @else
                    Thông tin sản phẩm đang được cập nhật.
                @endif
            </div>
        </section>

        <section class="mt-10 rounded-2xl bg-white p-8 shadow-sm">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold">
                    Đánh giá sản phẩm
                </h2>

                @if ($product->reviews->isNotEmpty())
                    <span class="text-sm text-gray-500">
                        {{ number_format(
                            $product->reviews->avg('rating'),
                            1
                        ) }}/5
                    </span>
                @endif
            </div>

            <div class="mt-6 space-y-5">
                @forelse ($product->reviews as $review)
                    <div class="border-b pb-5 last:border-0">
                        <div class="flex items-center justify-between">
                            <p class="font-semibold">
                                {{ $review->user->name }}
                            </p>

                            <p class="text-sm text-gray-500">
                                {{ $review->created_at->format('d/m/Y') }}
                            </p>
                        </div>

                        <p class="mt-1 text-yellow-500">
                            {{ str_repeat('★', $review->rating) }}

                            <span class="text-gray-300">
                                {{ str_repeat(
                                    '★',
                                    5 - $review->rating
                                ) }}
                            </span>
                        </p>

                        <p class="mt-2 text-gray-600">
                            {{ $review->comment
                                ?: 'Người dùng không để lại nhận xét.' }}
                        </p>
                    </div>
                @empty
                    <p class="text-gray-500">
                        Sản phẩm chưa có đánh giá.
                    </p>
                @endforelse
            </div>
        </section>

        @if ($relatedProducts->isNotEmpty())
            <section class="mt-14">
                <h2 class="mb-7 text-2xl font-bold">
                    Sản phẩm liên quan
                </h2>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2
                            lg:grid-cols-4">
                    @foreach ($relatedProducts as $relatedProduct)
                        <x-product-card :product="$relatedProduct" />
                    @endforeach
                </div>
            </section>
        @endif
    </div>
@endsection