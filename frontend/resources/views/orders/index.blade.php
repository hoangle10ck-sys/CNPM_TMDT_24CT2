@extends('layouts.store')

@section('title', 'Đơn hàng của tôi - TechStore')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold">Đơn hàng của tôi</h1>

            <p class="mt-2 text-gray-600">
                Theo dõi lịch sử và trạng thái đơn hàng.
            </p>
        </div>

        @if (session('success'))
            <div class="mb-6 rounded-lg bg-green-100 p-4 text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 rounded-lg bg-red-100 p-4 text-red-700">
                {{ session('error') }}
            </div>
        @endif

        @if ($orders->isEmpty())
            <div class="rounded-2xl bg-white p-14 text-center shadow-sm">
                <div class="text-7xl">📦</div>

                <h2 class="mt-5 text-2xl font-semibold">
                    Bạn chưa có đơn hàng
                </h2>

                <a
                    href="{{ route('products.index') }}"
                    class="mt-6 inline-block rounded-lg bg-indigo-600
                           px-6 py-3 font-semibold text-white"
                >
                    Bắt đầu mua sắm
                </a>
            </div>
        @else
            <div class="overflow-hidden rounded-xl bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">
                                    Mã đơn
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">
                                    Ngày đặt
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">
                                    Sản phẩm
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">
                                    Tổng tiền
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">
                                    Trạng thái
                                </th>

                                <th class="px-6 py-3 text-right text-xs font-medium uppercase text-gray-500">
                                    Thao tác
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200">
                            @foreach ($orders as $order)
                                @php
                                    $status = match ($order->status) {
                                        'pending' => [
                                            'Chờ xác nhận',
                                            'bg-yellow-100 text-yellow-700'
                                        ],
                                        'confirmed' => [
                                            'Đã xác nhận',
                                            'bg-blue-100 text-blue-700'
                                        ],
                                        'shipping' => [
                                            'Đang giao',
                                            'bg-purple-100 text-purple-700'
                                        ],
                                        'completed' => [
                                            'Hoàn thành',
                                            'bg-green-100 text-green-700'
                                        ],
                                        'cancelled' => [
                                            'Đã hủy',
                                            'bg-red-100 text-red-700'
                                        ],
                                        default => [
                                            $order->status,
                                            'bg-gray-100 text-gray-700'
                                        ],
                                    };
                                @endphp

                                <tr>
                                    <td class="px-6 py-4 font-medium">
                                        {{ $order->order_code }}
                                    </td>

                                    <td class="px-6 py-4 text-gray-600">
                                        {{ $order->created_at->format(
                                            'd/m/Y H:i'
                                        ) }}
                                    </td>

                                    <td class="px-6 py-4">
                                        {{ $order->items_count }}
                                    </td>

                                    <td class="px-6 py-4 font-semibold text-red-600">
                                        {{ number_format(
                                            $order->total,
                                            0,
                                            ',',
                                            '.'
                                        ) }}₫
                                    </td>

                                    <td class="px-6 py-4">
                                        <span class="rounded-full px-3 py-1
                                                     text-xs font-medium
                                                     {{ $status[1] }}">
                                            {{ $status[0] }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        <a
                                            href="{{ route(
                                                'orders.show',
                                                $order
                                            ) }}"
                                            class="font-medium text-indigo-600"
                                        >
                                            Chi tiết
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
@endsection