@extends('layouts.store')

@section('title', 'Thanh toán - TechStore')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="mb-8">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-indigo-600">Checkout</p>
            <h1 class="mt-2 text-3xl font-bold text-gray-900">Thông tin đặt hàng</h1>
            <p class="mt-2 text-gray-600">Kiểm tra lại thông tin và xác nhận đơn hàng của bạn.</p>
        </div>

        @if ($errors->any())
            <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-red-700 shadow-sm">
                <ul class="list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('checkout.store') }}" class="grid grid-cols-1 gap-8 lg:grid-cols-[1.4fr_0.8fr]">
            @csrf

            <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-gray-200 lg:p-8">
                <div class="flex items-center justify-between border-b border-gray-200 pb-4">
                    <h2 class="text-xl font-bold text-gray-900">Thông tin nhận hàng</h2>
                    <span class="rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold text-indigo-700">Bước 1</span>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Họ và tên</label>
                        <input name="customer_name" type="text" value="{{ old('customer_name', auth()->user()->name) }}" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input name="email" type="email" value="{{ old('email', auth()->user()->email) }}" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Số điện thoại</label>
                        <input name="phone" type="text" value="{{ old('phone', auth()->user()->phone) }}" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Địa chỉ nhận hàng</label>
                        <textarea name="shipping_address" rows="3" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>{{ old('shipping_address', auth()->user()->address) }}</textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Ghi chú</label>
                        <textarea name="note" rows="3" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Ghi chú cho cửa hàng">{{ old('note') }}</textarea>
                    </div>
                </div>

                <div class="mt-8 rounded-2xl border border-indigo-100 bg-indigo-50 p-5">
                    <h2 class="text-xl font-bold text-gray-900">Phương thức thanh toán</h2>

                    <label class="mt-4 flex cursor-pointer items-center gap-3 rounded-2xl border border-indigo-200 bg-white p-4 shadow-sm">
                        <input name="payment_method" type="radio" value="cod" checked class="h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                        <span>
                            <strong class="block text-gray-900">Thanh toán khi nhận hàng</strong>
                            <span class="block text-sm text-gray-500">Thanh toán tiền mặt khi nhận sản phẩm.</span>
                        </span>
                    </label>

                    <div class="mt-5 rounded-xl border border-indigo-200 bg-white p-4 text-sm text-indigo-700">
                        <p class="font-semibold">Lưu ý quan trọng</p>
                        <p class="mt-1 text-indigo-600">Đơn hàng sẽ được xác nhận nhanh nhất sau khi bạn gửi thông tin. TechStore cam kết bảo mật dữ liệu cá nhân và địa chỉ giao hàng.</p>
                    </div>
                </div>
            </div>

            <aside class="h-fit rounded-3xl bg-white p-6 shadow-sm ring-1 ring-gray-200 lg:p-7">
                <div class="flex items-center justify-between border-b border-gray-200 pb-4">
                    <h2 class="text-xl font-bold text-gray-900">Đơn hàng</h2>
                    <span class="text-sm text-gray-500">{{ $cart->items->count() }} sản phẩm</span>
                </div>

                <div class="mt-5 space-y-4">
                    @foreach ($cart->items as $item)
                        <div class="flex justify-between gap-4 rounded-2xl bg-gray-50 p-3">
                            <div>
                                <p class="font-medium text-gray-900">{{ $item->product->name }}</p>
                                <p class="mt-1 text-sm text-gray-500">Số lượng: {{ $item->quantity }}</p>
                            </div>
                            <p class="whitespace-nowrap font-semibold text-gray-900">{{ number_format($item->subtotal, 0, ',', '.') }}₫</p>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 space-y-3 border-t border-gray-200 pt-5">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Tạm tính</span>
                        <span class="font-medium text-gray-900">{{ number_format($subtotal, 0, ',', '.') }}₫</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Phí vận chuyển</span>
                        <span class="font-medium text-emerald-600">Miễn phí</span>
                    </div>
                    <div class="flex justify-between border-t border-gray-200 pt-4">
                        <span class="text-lg font-bold text-gray-900">Tổng cộng</span>
                        <span class="text-2xl font-bold text-red-600">{{ number_format($total, 0, ',', '.') }}₫</span>
                    </div>
                </div>

                <button type="submit" class="mt-6 w-full rounded-xl bg-indigo-600 px-6 py-3 text-base font-semibold text-white transition hover:bg-indigo-700">
                    Xác nhận đặt hàng
                </button>

                <a href="{{ route('cart.index') }}" class="mt-3 block text-center text-sm font-medium text-gray-500 hover:text-indigo-600">
                    ← Quay lại giỏ hàng
                </a>
            </aside>
        </form>
    </div>
@endsection