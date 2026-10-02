@extends('layouts.store')

@section('title', 'Thanh toán - TechStore')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <h1 class="mb-8 text-3xl font-bold">Thông tin đặt hàng</h1>

        @if ($errors->any())
            <div class="mb-6 rounded-lg bg-red-100 p-4 text-red-700">
                <ul class="list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            method="POST"
            action="{{ route('checkout.store') }}"
            class="grid grid-cols-1 gap-8 lg:grid-cols-3"
        >
            @csrf

            <div class="rounded-xl bg-white p-6 shadow-sm lg:col-span-2">
                <h2 class="text-xl font-bold">Thông tin nhận hàng</h2>

                <div class="mt-6 grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium">
                            Họ và tên
                        </label>

                        <input
                            name="customer_name"
                            type="text"
                            value="{{ old(
                                'customer_name',
                                auth()->user()->name
                            ) }}"
                            class="mt-1 block w-full rounded-md border-gray-300"
                            required
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium">
                            Email
                        </label>

                        <input
                            name="email"
                            type="email"
                            value="{{ old(
                                'email',
                                auth()->user()->email
                            ) }}"
                            class="mt-1 block w-full rounded-md border-gray-300"
                            required
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium">
                            Số điện thoại
                        </label>

                        <input
                            name="phone"
                            type="text"
                            value="{{ old(
                                'phone',
                                auth()->user()->phone
                            ) }}"
                            class="mt-1 block w-full rounded-md border-gray-300"
                            required
                        >
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium">
                            Địa chỉ nhận hàng
                        </label>

                        <textarea
                            name="shipping_address"
                            rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300"
                            required
                        >{{ old(
                            'shipping_address',
                            auth()->user()->address
                        ) }}</textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium">
                            Ghi chú
                        </label>

                        <textarea
                            name="note"
                            rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300"
                            placeholder="Ghi chú cho cửa hàng"
                        >{{ old('note') }}</textarea>
                    </div>
                </div>

                <div class="mt-8">
                    <h2 class="text-xl font-bold">
                        Phương thức thanh toán
                    </h2>

                    <label class="mt-4 flex cursor-pointer items-center gap-3
                                  rounded-lg border border-indigo-500 p-4">
                        <input
                            name="payment_method"
                            type="radio"
                            value="cod"
                            checked
                            class="text-indigo-600"
                        >

                        <span>
                            <strong>Thanh toán khi nhận hàng</strong>
                            <span class="block text-sm text-gray-500">
                                Thanh toán tiền mặt khi nhận sản phẩm.
                            </span>
                        </span>
                    </label>
                </div>
            </div>

            <aside class="h-fit rounded-xl bg-white p-6 shadow-sm">
                <h2 class="text-xl font-bold">Đơn hàng</h2>

                <div class="mt-5 divide-y">
                    @foreach ($cart->items as $item)
                        <div class="flex justify-between gap-4 py-4">
                            <div>
                                <p class="font-medium">
                                    {{ $item->product->name }}
                                </p>

                                <p class="text-sm text-gray-500">
                                    Số lượng: {{ $item->quantity }}
                                </p>
                            </div>

                            <p class="whitespace-nowrap font-medium">
                                {{ number_format(
                                    $item->subtotal,
                                    0,
                                    ',',
                                    '.'
                                ) }}₫
                            </p>
                        </div>
                    @endforeach
                </div>

                <div class="mt-5 space-y-3 border-t pt-5">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tạm tính</span>
                        <span>
                            {{ number_format($subtotal, 0, ',', '.') }}₫
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-600">Phí vận chuyển</span>
                        <span>Miễn phí</span>
                    </div>

                    <div class="flex justify-between border-t pt-4">
                        <span class="font-bold">Tổng cộng</span>

                        <span class="text-xl font-bold text-red-600">
                            {{ number_format($total, 0, ',', '.') }}₫
                        </span>
                    </div>
                </div>

                <button
                    type="submit"
                    class="mt-6 w-full rounded-lg bg-indigo-600 px-6 py-3
                           font-semibold text-white hover:bg-indigo-700"
                >
                    Xác nhận đặt hàng
                </button>

                <a
                    href="{{ route('cart.index') }}"
                    class="mt-3 block text-center text-sm text-gray-500
                           hover:text-indigo-600"
                >
                    Quay lại giỏ hàng
                </a>
            </aside>
        </form>
    </div>
@endsection