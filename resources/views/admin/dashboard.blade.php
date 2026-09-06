<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">
                    Dashboard quản trị
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Tổng quan hoạt động của TechStore
                </p>
            </div>

            <a
                href="{{ route('home') }}"
                class="rounded-md bg-indigo-600 px-4 py-2 text-sm
                       font-medium text-white hover:bg-indigo-700"
            >
                Xem cửa hàng
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900">
                    Xin chào, {{ auth()->user()->name }}!
                </h1>

                <p class="mt-1 text-gray-600">
                    Đây là tình hình hoạt động hiện tại của cửa hàng.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2
                        lg:grid-cols-4">
                <a
                    href="{{ route('admin.customers.index') }}"
                    class="rounded-xl bg-gradient-to-br from-blue-500
                           to-blue-600 p-6 text-white shadow-sm
                           transition hover:-translate-y-1 hover:shadow-lg"
                >
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-blue-100">
                                Khách hàng
                            </p>

                            <p class="mt-2 text-3xl font-bold">
                                {{ $statistics['customers'] }}
                            </p>
                        </div>

                        <span class="text-4xl">👥</span>
                    </div>
                </a>

                <a
                    href="{{ route('admin.categories.index') }}"
                    class="rounded-xl bg-gradient-to-br from-green-500
                           to-green-600 p-6 text-white shadow-sm
                           transition hover:-translate-y-1 hover:shadow-lg"
                >
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-green-100">
                                Danh mục
                            </p>

                            <p class="mt-2 text-3xl font-bold">
                                {{ $statistics['categories'] }}
                            </p>
                        </div>

                        <span class="text-4xl">📂</span>
                    </div>
                </a>

                <a
                    href="{{ route('admin.products.index') }}"
                    class="rounded-xl bg-gradient-to-to-br from-purple-500
                           to-purple-600 p-6 text-white shadow-sm
                           transition hover:-translate-y-1 hover:shadow-lg"
                >
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-purple-100">
                                Sản phẩm
                            </p>

                            <p class="mt-2 text-3xl font-bold">
                                {{ $statistics['products'] }}
                            </p>
                        </div>

                        <span class="text-4xl">📦</span>
                    </div>
                </a>

                <a
                    href="{{ route('admin.orders.index') }}"
                    class="rounded-xl bg-gradient-to-br from-orange-500
                           to-orange-600 p-6 text-white shadow-sm
                           transition hover:-translate-y-1 hover:shadow-lg"
                >
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-orange-100">
                                Đơn hàng
                            </p>

                            <p class="mt-2 text-3xl font-bold">
                                {{ $statistics['orders'] }}
                            </p>
                        </div>

                        <span class="text-4xl">🧾</span>
                    </div>
                </a>
            </div>

            <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
                <div class="rounded-xl bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">
                                Doanh thu hoàn thành
                            </p>

                            <p class="mt-2 text-2xl font-bold text-green-600">
                                {{ number_format(
                                    $statistics['revenue'],
                                    0,
                                    ',',
                                    '.'
                                ) }}₫
                            </p>
                        </div>

                        <span class="text-4xl">💰</span>
                    </div>
                </div>

                <a
                    href="{{ route('admin.orders.index', [
                        'status' => 'pending'
                    ]) }}"
                    class="rounded-xl bg-white p-6 shadow-sm
                           transition hover:shadow-md"
                >
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">
                                Đơn chờ xác nhận
                            </p>

                            <p class="mt-2 text-2xl font-bold text-yellow-600">
                                {{ $statistics['pending_orders'] }}
                            </p>
                        </div>

                        <span class="text-4xl">⏳</span>
                    </div>
                </a>

                <a
                    href="{{ route('admin.products.index') }}"
                    class="rounded-xl bg-white p-6 shadow-sm
                           transition hover:shadow-md"
                >
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">
                                Sản phẩm sắp hết
                            </p>

                            <p class="mt-2 text-2xl font-bold text-red-600">
                                {{ $statistics['low_stock_products'] }}
                            </p>
                        </div>

                        <span class="text-4xl">⚠️</span>
                    </div>
                </a>
            </div>

            <section class="mt-8">
                <h2 class="mb-4 text-xl font-bold">
                    Truy cập nhanh
                </h2>

                <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                    <a
                        href="{{ route('admin.products.create') }}"
                        class="rounded-xl bg-white p-5 text-center
                               font-medium shadow-sm hover:text-indigo-600
                               hover:shadow-md"
                    >
                        <span class="block text-3xl">➕</span>
                        <span class="mt-2 block">Thêm sản phẩm</span>
                    </a>

                    <a
                        href="{{ route('admin.categories.create') }}"
                        class="rounded-xl bg-white p-5 text-center
                               font-medium shadow-sm hover:text-indigo-600
                               hover:shadow-md"
                    >
                        <span class="block text-3xl">📁</span>
                        <span class="mt-2 block">Thêm danh mục</span>
                    </a>

                    <a
                        href="{{ route('admin.orders.index') }}"
                        class="rounded-xl bg-white p-5 text-center
                               font-medium shadow-sm hover:text-indigo-600
                               hover:shadow-md"
                    >
                        <span class="block text-3xl">🚚</span>
                        <span class="mt-2 block">Xử lý đơn hàng</span>
                    </a>

                    <a
                        href="{{ route('admin.customers.index') }}"
                        class="rounded-xl bg-white p-5 text-center
                               font-medium shadow-sm hover:text-indigo-600
                               hover:shadow-md"
                    >
                        <span class="block text-3xl">👤</span>
                        <span class="mt-2 block">Xem khách hàng</span>
                    </a>
                </div>
            </section>

            <div class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-3">
                <section class="overflow-hidden rounded-xl bg-white
                                shadow-sm lg:col-span-2">
                    <div class="flex items-center justify-between border-b p-6">
                        <h2 class="text-xl font-bold">
                            Đơn hàng mới nhất
                        </h2>

                        <a
                            href="{{ route('admin.orders.index') }}"
                            class="text-sm font-medium text-indigo-600"
                        >
                            Xem tất cả →
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs
                                               uppercase text-gray-500">
                                        Đơn hàng
                                    </th>

                                    <th class="px-5 py-3 text-left text-xs
                                               uppercase text-gray-500">
                                        Khách hàng
                                    </th>

                                    <th class="px-5 py-3 text-left text-xs
                                               uppercase text-gray-500">
                                        Tổng tiền
                                    </th>

                                    <th class="px-5 py-3 text-right text-xs
                                               uppercase text-gray-500">
                                        Chi tiết
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200">
                                @forelse ($recentOrders as $order)
                                    <tr>
                                        <td class="px-5 py-4">
                                            <p class="font-medium">
                                                {{ $order->order_code }}
                                            </p>

                                            <p class="text-sm text-gray-500">
                                                {{ $order->created_at->format(
                                                    'd/m/Y H:i'
                                                ) }}
                                            </p>
                                        </td>

                                        <td class="px-5 py-4">
                                            {{ $order->customer_name }}
                                        </td>

                                        <td class="px-5 py-4 font-semibold
                                                   text-red-600">
                                            {{ number_format(
                                                $order->total,
                                                0,
                                                ',',
                                                '.'
                                            ) }}₫
                                        </td>

                                        <td class="px-5 py-4 text-right">
                                            <a
                                                href="{{ route(
                                                    'admin.orders.show',
                                                    $order
                                                ) }}"
                                                class="text-indigo-600"
                                            >
                                                Xem
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td
                                            colspan="4"
                                            class="px-5 py-10 text-center
                                                   text-gray-500"
                                        >
                                            Chưa có đơn hàng.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>

                <section class="rounded-xl bg-white shadow-sm">
                    <div class="border-b p-6">
                        <h2 class="text-xl font-bold">
                            Sản phẩm sắp hết
                        </h2>
                    </div>

                    <div class="divide-y">
                        @forelse ($lowStockProducts as $product)
                            <div class="flex items-center justify-between p-5">
                                <div>
                                    <a
                                        href="{{ route(
                                            'admin.products.edit',
                                            $product
                                        ) }}"
                                        class="font-medium hover:text-indigo-600"
                                    >
                                        {{ $product->name }}
                                    </a>

                                    <p class="mt-1 text-sm text-gray-500">
                                        {{ $product->category->name }}
                                    </p>
                                </div>

                                <span class="rounded-full bg-red-100 px-3
                                             py-1 text-sm font-bold text-red-600">
                                    {{ $product->stock }}
                                </span>
                            </div>
                        @empty
                            <div class="p-8 text-center text-gray-500">
                                Không có sản phẩm sắp hết.
                            </div>
                        @endforelse
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>