@extends('layouts.store')

@section('title', 'Giỏ hàng - TechStore')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold">Giỏ hàng</h1>
                <p class="mt-2 text-gray-600">
                    Kiểm tra sản phẩm trước khi đặt hàng.
                </p>
            </div>

            <a
                href="{{ route('products.index') }}"
                class="font-medium text-indigo-600 hover:text-indigo-800"
            >
                ← Tiếp tục mua sắm
            </a>
        </div>

        @if (session('success'))
            <div class="mb-6 rounded-lg bg-green-100 p-4 text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded-lg bg-red-100 p-4 text-red-700">
                <ul class="list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if ($cart->items->isEmpty())
            <div class="rounded-2xl bg-white p-14 text-center shadow-sm">
                <div class="text-7xl">🛒</div>

                <h2 class="mt-5 text-2xl font-semibold">
                    Giỏ hàng đang trống
                </h2>

                <p class="mt-2 text-gray-500">
                    Hãy thêm sản phẩm yêu thích vào giỏ hàng.
                </p>

                <a
                    href="{{ route('products.index') }}"
                    class="mt-6 inline-block rounded-lg bg-indigo-600
                           px-6 py-3 font-semibold text-white
                           hover:bg-indigo-700"
                >
                    Xem sản phẩm
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                <div class="space-y-4 lg:col-span-2">
                    @foreach ($cart->items as $item)
                        <div class="rounded-xl bg-white p-5 shadow-sm">
                            <div class="flex flex-col gap-5 sm:flex-row">
                                @if ($item->product->thumbnail)
                                    <img
                                        src="{{ asset(
                                            'storage/'.$item->product->thumbnail
                                        ) }}"
                                        alt="{{ $item->product->name }}"
                                        class="h-32 w-full rounded-lg object-cover sm:w-40"
                                    >
                                @else
                                    <div class="flex h-32 w-full items-center
                                                justify-center rounded-lg bg-gray-100
                                                text-5xl sm:w-40">
                                        🛍️
                                    </div>
                                @endif

                                <div class="flex-1">
                                    <p class="text-sm text-indigo-600">
                                        {{ $item->product->category->name }}
                                    </p>

                                    <a
                                        href="{{ route('products.show', [
                                            'product' => $item->product->slug
                                        ]) }}"
                                        class="mt-1 block text-lg font-semibold
                                               hover:text-indigo-600"
                                    >
                                        {{ $item->product->name }}
                                    </a>

                                    <p class="mt-2 font-bold text-red-600">
                                        {{ number_format(
                                            $item->product->current_price,
                                            0,
                                            ',',
                                            '.'
                                        ) }}₫
                                    </p>

                                    <p class="mt-2 text-sm text-gray-500">
                                        Còn {{ $item->product->stock }} sản phẩm
                                    </p>

                                    <div class="mt-4 flex flex-wrap items-end gap-4">
                                        <form
                                            method="POST"
                                            action="{{ route('cart.update', $item) }}"
                                            class="flex items-end gap-2"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <div>
                                                <label
                                                    for="quantity-{{ $item->id }}"
                                                    class="block text-xs text-gray-500"
                                                >
                                                    Số lượng
                                                </label>

                                                <input
                                                    id="quantity-{{ $item->id }}"
                                                    name="quantity"
                                                    type="number"
                                                    min="1"
                                                    max="{{ $item->product->stock }}"
                                                    value="{{ $item->quantity }}"
                                                    class="mt-1 w-20 rounded-md border-gray-300"
                                                >
                                            </div>

                                            <button
                                                type="submit"
                                                class="rounded-md bg-gray-800 px-3
                                                       py-2 text-sm text-white"
                                            >
                                                Cập nhật
                                            </button>
                                        </form>

                                        <form
                                            method="POST"
                                            action="{{ route('cart.destroy', $item) }}"
                                            onsubmit="return confirm(
                                                'Xóa sản phẩm khỏi giỏ hàng?'
                                            )"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="py-2 text-sm font-medium text-red-600"
                                            >
                                                Xóa
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <div class="text-right">
                                    <p class="text-sm text-gray-500">
                                        Thành tiền
                                    </p>

                                    <p class="mt-1 text-lg font-bold">
                                        {{ number_format(
                                            $item->subtotal,
                                            0,
                                            ',',
                                            '.'
                                        ) }}₫
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <form
                        method="POST"
                        action="{{ route('cart.clear') }}"
                        onsubmit="return confirm(
                            'Bạn có chắc muốn xóa toàn bộ giỏ hàng?'
                        )"
                    >
                        @csrf
                        @method('DELETE')

                        <button
                            type="submit"
                            class="text-sm font-medium text-red-600"
                        >
                            Xóa toàn bộ giỏ hàng
                        </button>
                    </form>
                </div>

                <aside class="h-fit rounded-xl bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-bold">
                        Tóm tắt đơn hàng
                    </h2>

                    <div class="mt-6 space-y-4">
                        <div class="flex justify-between text-gray-600">
                            <span>Số lượng</span>
                            <span>
                                {{ $cart->items->sum('quantity') }} sản phẩm
                            </span>
                        </div>

                        <div class="flex justify-between text-gray-600">
                            <span>Tạm tính</span>
                            <span>
                                {{ number_format(
                                    $cart->total,
                                    0,
                                    ',',
                                    '.'
                                ) }}₫
                            </span>
                        </div>

                        <div class="flex justify-between text-gray-600">
                            <span>Phí vận chuyển</span>
                            <span>Miễn phí</span>
                        </div>

                        <div class="border-t pt-4">
                            <div class="flex justify-between">
                                <span class="font-semibold">Tổng cộng</span>

                                <span class="text-xl font-bold text-red-600">
                                    {{ number_format(
                                        $cart->total,
                                        0,
                                        ',',
                                        '.'
                                    ) }}₫
                                </span>
                            </div>
                        </div>
                    </div>

                  <a
    href="{{ route('checkout.index') }}"
    class="mt-6 block w-full rounded-lg bg-indigo-600
           px-6 py-3 text-center font-semibold text-white
           hover:bg-indigo-700"
>
    Tiến hành đặt hàng
</a>
                </aside>
            </div>
        @endif
    </div>
@endsection