@extends('layouts.store')

@section('title', 'Đặt hàng thành công - TechStore')

@section('content')
    @php
        $statusLabels = [
            'pending' => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'shipping' => 'Đang giao hàng',
            'completed' => 'Đã hoàn thành',
            'cancelled' => 'Đã hủy',
        ];
    @endphp

    <div class="mx-auto max-w-4xl px-4 py-14 sm:px-6 lg:px-8">
        <section class="overflow-hidden rounded-2xl bg-white shadow-lg">
            <div class="bg-gradient-to-r from-green-500 to-emerald-600
                        px-6 py-10 text-center text-white">
                <div class="mx-auto flex h-20 w-20 items-center
                            justify-center rounded-full bg-white text-5xl
                            text-green-600 shadow">
                    ✓
                </div>

                <h1 class="mt-5 text-3xl font-bold">
                    Đặt hàng thành công!
                </h1>

                <p class="mt-2 text-green-100">
                    Cảm ơn bạn đã mua hàng tại TechStore.
                </p>
            </div>

            <div class="p-6 md:p-8">
                <div class="grid grid-cols-1 gap-4 rounded-xl
                            bg-gray-50 p-5 md:grid-cols-2">
                    <div>
                        <p class="text-sm text-gray-500">
                            Mã đơn hàng
                        </p>

                        <p class="mt-1 font-bold text-indigo-600">
                            {{ $order->order_code }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">
                            Ngày đặt
                        </p>

                        <p class="mt-1 font-medium">
                            {{ $order->created_at->format('d/m/Y H:i') }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">
                            Trạng thái
                        </p>

                        <p class="mt-1 font-medium text-orange-600">
                            {{ $statusLabels[$order->status]
                                ?? $order->status }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">
                            Phương thức thanh toán
                        </p>

                        <p class="mt-1 font-medium">
                            Thanh toán khi nhận hàng
                        </p>
                    </div>
                </div>

                <div class="mt-8">
                    <h2 class="text-xl font-bold">
                        Sản phẩm đã đặt
                    </h2>

                    <div class="mt-4 divide-y rounded-xl border">
                        @foreach ($order->items as $item)
                            <div class="flex items-start justify-between
                                        gap-5 p-5">
                                <div>
                                    <p class="font-semibold">
                                        {{ $item->product_name }}
                                    </p>

                                    <p class="mt-1 text-sm text-gray-500">
                                        Mã sản phẩm:
                                        {{ $item->product_sku ?: 'N/A' }}
                                    </p>

                                    <p class="mt-1 text-sm text-gray-500">
                                        {{ number_format(
                                            $item->price,
                                            0,
                                            ',',
                                            '.'
                                        ) }}₫
                                        × {{ $item->quantity }}
                                    </p>
                                </div>

                                <p class="whitespace-nowrap font-bold">
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
                </div>

                <div class="mt-8 grid grid-cols-1 gap-8 md:grid-cols-2">
                    <div>
                        <h2 class="text-xl font-bold">
                            Thông tin nhận hàng
                        </h2>

                        <div class="mt-4 space-y-2 text-gray-700">
                            <p>
                                <strong>Người nhận:</strong>
                                {{ $order->customer_name }}
                            </p>

                            <p>
                                <strong>Email:</strong>
                                {{ $order->email }}
                            </p>

                            <p>
                                <strong>Điện thoại:</strong>
                                {{ $order->phone }}
                            </p>

                            <p>
                                <strong>Địa chỉ:</strong>
                                {{ $order->shipping_address }}
                            </p>

                            @if ($order->note)
                                <p>
                                    <strong>Ghi chú:</strong>
                                    {{ $order->note }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="rounded-xl bg-gray-50 p-5">
                        <h2 class="text-xl font-bold">
                            Thanh toán
                        </h2>

                        <div class="mt-4 space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tạm tính</span>

                                <span>
                                    {{ number_format(
                                        $order->subtotal,
                                        0,
                                        ',',
                                        '.'
                                    ) }}₫
                                </span>
                            </div>

                            <div class="flex justify-between">
                                <span class="text-gray-600">
                                    Phí vận chuyển
                                </span>

                                <span>
                                    {{ $order->shipping_fee > 0
                                        ? number_format(
                                            $order->shipping_fee,
                                            0,
                                            ',',
                                            '.'
                                        ).'₫'
                                        : 'Miễn phí' }}
                                </span>
                            </div>

                            <div class="flex justify-between border-t pt-3">
                                <span class="font-bold">
                                    Tổng cộng
                                </span>

                                <span class="text-xl font-bold text-red-600">
                                    {{ number_format(
                                        $order->total,
                                        0,
                                        ',',
                                        '.'
                                    ) }}₫
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-9 flex flex-col justify-center gap-3 sm:flex-row">
                    <a
                        href="{{ route('orders.show', $order) }}"
                        class="rounded-lg bg-indigo-600 px-6 py-3
                               text-center font-semibold text-white
                               hover:bg-indigo-700"
                    >
                        Xem chi tiết đơn hàng
                    </a>

                    <a
                        href="{{ route('products.index') }}"
                        class="rounded-lg border border-gray-300 px-6 py-3
                               text-center font-semibold text-gray-700
                               hover:bg-gray-50"
                    >
                        Tiếp tục mua sắm
                    </a>
                </div>

                <p class="mt-7 text-center text-sm text-gray-500">
                    Cửa hàng sẽ liên hệ để xác nhận và giao hàng
                    trong thời gian sớm nhất.
                </p>
            </div>
        </section>
    </div>
@endsection