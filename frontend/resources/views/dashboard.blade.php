@extends('layouts.store')

@section('title', 'Tài khoản của tôi - TechStore')

@section('content')
    <section class="bg-gradient-to-r from-indigo-700 to-purple-700 py-12
                    text-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <p class="text-indigo-100">Xin chào,</p>

            <h1 class="mt-1 text-3xl font-bold">
                {{ $user->name }}
            </h1>

            <p class="mt-2 text-indigo-100">
                Quản lý tài khoản, giỏ hàng và đơn hàng của bạn.
            </p>
        </div>
    </section>

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
            <a
                href="{{ route('orders.index') }}"
                class="rounded-xl bg-white p-6 shadow-sm transition
                       hover:-translate-y-1 hover:shadow-md"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">
                            Tổng đơn hàng
                        </p>

                        <p class="mt-2 text-3xl font-bold text-blue-600">
                            {{ $statistics['total_orders'] }}
                        </p>
                    </div>

                    <span class="text-4xl">📦</span>
                </div>
            </a>

            <a
                href="{{ route('orders.index') }}"
                class="rounded-xl bg-white p-6 shadow-sm transition
                       hover:-translate-y-1 hover:shadow-md"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">
                            Đơn đang xử lý
                        </p>

                        <p class="mt-2 text-3xl font-bold text-orange-600">
                            {{ $statistics['pending_orders'] }}
                        </p>
                    </div>

                    <span class="text-4xl">🚚</span>
                </div>
            </a>

            <a
                href="{{ route('cart.index') }}"
                class="rounded-xl bg-white p-6 shadow-sm transition
                       hover:-translate-y-1 hover:shadow-md"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">
                            Trong giỏ hàng
                        </p>

                        <p class="mt-2 text-3xl font-bold text-purple-600">
                            {{ $statistics['cart_items'] }}
                        </p>
                    </div>

                    <span class="text-4xl">🛒</span>
                </div>
            </a>

            <div class="rounded-xl bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">
                            Tổng chi tiêu
                        </p>

                        <p class="mt-2 text-2xl font-bold text-green-600">
                            {{ number_format(
                                $statistics['total_spent'],
                                0,
                                ',',
                                '.'
                            ) }}₫
                        </p>
                    </div>

                    <span class="text-4xl">💰</span>
                </div>
            </div>
        </div>

        <div class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-3">
            <section class="overflow-hidden rounded-xl bg-white
                            shadow-sm lg:col-span-2">
                <div class="flex items-center justify-between border-b p-6">
                    <div>
                        <h2 class="text-xl font-bold">
                            Đơn hàng gần đây
                        </h2>

                        <p class="mt-1 text-sm text-gray-500">
                            Theo dõi các đơn hàng mới nhất.
                        </p>
                    </div>

                    <a
                        href="{{ route('orders.index') }}"
                        class="text-sm font-medium text-indigo-600
                               hover:text-indigo-800"
                    >
                        Xem tất cả →
                    </a>
                </div>

                @if ($recentOrders->isEmpty())
                    <div class="p-12 text-center">
                        <div class="text-6xl">📭</div>

                        <h3 class="mt-4 text-lg font-semibold">
                            Bạn chưa có đơn hàng
                        </h3>

                        <p class="mt-2 text-gray-500">
                            Hãy khám phá các sản phẩm tại TechStore.
                        </p>

                        <a
                            href="{{ route('products.index') }}"
                            class="mt-5 inline-block rounded-lg bg-indigo-600
                                   px-5 py-2.5 font-semibold text-white
                                   hover:bg-indigo-700"
                        >
                            Mua sắm ngay
                        </a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs
                                               uppercase text-gray-500">
                                        Mã đơn
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs
                                               uppercase text-gray-500">
                                        Ngày đặt
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs
                                               uppercase text-gray-500">
                                        Tổng tiền
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs
                                               uppercase text-gray-500">
                                        Trạng thái
                                    </th>

                                    <th class="px-6 py-3 text-right text-xs
                                               uppercase text-gray-500">
                                        Chi tiết
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200">
                                @foreach ($recentOrders as $order)
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
                                                'd/m/Y'
                                            ) }}
                                        </td>

                                        <td class="px-6 py-4 font-semibold
                                                   text-red-600">
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
                                                class="font-medium
                                                       text-indigo-600"
                                            >
                                                Xem
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </section>

            <aside class="space-y-6">
                <div class="rounded-xl bg-white p-6 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="flex h-16 w-16 items-center justify-center
                                    rounded-full bg-indigo-100 text-2xl
                                    font-bold text-indigo-600">
                            {{ mb_strtoupper(
                                mb_substr($user->name, 0, 1)
                            ) }}
                        </div>

                        <div>
                            <h2 class="font-bold">
                                {{ $user->name }}
                            </h2>

                            <p class="text-sm text-gray-500">
                                Khách hàng
                            </p>
                        </div>
                    </div>

                    <div class="mt-6 space-y-4 text-sm">
                        <div>
                            <p class="text-gray-500">Email</p>

                            <p class="break-all font-medium">
                                {{ $user->email }}
                            </p>
                        </div>

                        <div>
                            <p class="text-gray-500">Điện thoại</p>

                            <p class="font-medium">
                                {{ $user->phone ?: 'Chưa cập nhật' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-gray-500">Địa chỉ</p>

                            <p class="font-medium">
                                {{ $user->address ?: 'Chưa cập nhật' }}
                            </p>
                        </div>
                    </div>

                    <a
                        href="{{ route('profile.edit') }}"
                        class="mt-6 block rounded-lg border
                               border-indigo-600 px-5 py-2.5 text-center
                               font-medium text-indigo-600
                               hover:bg-indigo-50"
                    >
                        Chỉnh sửa hồ sơ
                    </a>
                </div>

                <div class="rounded-xl bg-indigo-50 p-6">
                    <h2 class="font-bold text-indigo-900">
                        Mua sắm ngay
                    </h2>

                    <p class="mt-2 text-sm text-indigo-700">
                        Khám phá các sản phẩm công nghệ mới nhất.
                    </p>

                    <a
                        href="{{ route('products.index') }}"
                        class="mt-4 inline-block font-semibold
                               text-indigo-700 hover:text-indigo-900"
                    >
                        Xem sản phẩm →
                    </a>
                </div>
            </aside>
        </div>
    </div>
@endsection