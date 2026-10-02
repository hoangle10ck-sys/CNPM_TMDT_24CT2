<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            Quản lý đơn hàng
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <a
                    href="{{ route('admin.dashboard') }}"
                    class="text-indigo-600 hover:text-indigo-800"
                >
                    ← Dashboard
                </a>
            </div>

            <form
                method="GET"
                action="{{ route('admin.orders.index') }}"
                class="mb-6 grid grid-cols-1 gap-4 rounded-xl bg-white
                       p-5 shadow-sm md:grid-cols-4"
            >
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium">
                        Tìm kiếm
                    </label>

                    <input
                        name="keyword"
                        type="text"
                        value="{{ request('keyword') }}"
                        placeholder="Mã đơn, tên, email hoặc số điện thoại"
                        class="mt-1 block w-full rounded-md border-gray-300"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium">
                        Trạng thái
                    </label>

                    <select
                        name="status"
                        class="mt-1 block w-full rounded-md border-gray-300"
                    >
                        <option value="">Tất cả</option>
                        <option value="pending" @selected(request('status') === 'pending')>
                            Chờ xác nhận
                        </option>
                        <option value="confirmed" @selected(request('status') === 'confirmed')>
                            Đã xác nhận
                        </option>
                        <option value="shipping" @selected(request('status') === 'shipping')>
                            Đang giao
                        </option>
                        <option value="completed" @selected(request('status') === 'completed')>
                            Hoàn thành
                        </option>
                        <option value="cancelled" @selected(request('status') === 'cancelled')>
                            Đã hủy
                        </option>
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button
                        type="submit"
                        class="rounded-md bg-indigo-600 px-4 py-2.5
                               text-white hover:bg-indigo-700"
                    >
                        Lọc
                    </button>

                    <a
                        href="{{ route('admin.orders.index') }}"
                        class="rounded-md bg-gray-200 px-4 py-2.5"
                    >
                        Xóa lọc
                    </a>
                </div>
            </form>

            @if (session('success'))
                <div class="mb-5 rounded-md bg-green-100 p-4 text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="overflow-hidden rounded-xl bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs uppercase text-gray-500">
                                    Đơn hàng
                                </th>
                                <th class="px-5 py-3 text-left text-xs uppercase text-gray-500">
                                    Khách hàng
                                </th>
                                <th class="px-5 py-3 text-left text-xs uppercase text-gray-500">
                                    Tổng tiền
                                </th>
                                <th class="px-5 py-3 text-left text-xs uppercase text-gray-500">
                                    Trạng thái
                                </th>
                                <th class="px-5 py-3 text-right text-xs uppercase text-gray-500">
                                    Thao tác
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200">
                            @forelse ($orders as $order)
                                @php
                                    $labels = [
                                        'pending' => 'Chờ xác nhận',
                                        'confirmed' => 'Đã xác nhận',
                                        'shipping' => 'Đang giao',
                                        'completed' => 'Hoàn thành',
                                        'cancelled' => 'Đã hủy',
                                    ];
                                @endphp

                                <tr>
                                    <td class="px-5 py-4">
                                        <p class="font-semibold">
                                            {{ $order->order_code }}
                                        </p>

                                        <p class="text-sm text-gray-500">
                                            {{ $order->created_at->format(
                                                'd/m/Y H:i'
                                            ) }}
                                        </p>
                                    </td>

                                    <td class="px-5 py-4">
                                        <p>{{ $order->customer_name }}</p>
                                        <p class="text-sm text-gray-500">
                                            {{ $order->phone }}
                                        </p>
                                    </td>

                                    <td class="px-5 py-4 font-semibold text-red-600">
                                        {{ number_format(
                                            $order->total,
                                            0,
                                            ',',
                                            '.'
                                        ) }}₫
                                    </td>

                                    <td class="px-5 py-4">
                                        {{ $labels[$order->status]
                                            ?? $order->status }}
                                    </td>

                                    <td class="px-5 py-4 text-right">
                                        <a
                                            href="{{ route(
                                                'admin.orders.show',
                                                $order
                                            ) }}"
                                            class="font-medium text-indigo-600"
                                        >
                                            Xem chi tiết
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td
                                        colspan="5"
                                        class="px-5 py-10 text-center text-gray-500"
                                    >
                                        Chưa có đơn hàng.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</x-app-layout>