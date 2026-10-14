<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-indigo-600">Orders</p>
                <h2 class="mt-1 text-2xl font-bold text-gray-900">Quản lý đơn hàng</h2>
            </div>

            <a href="{{ route('admin.dashboard') }}" class="rounded-xl border border-indigo-200 bg-indigo-50 px-4 py-2 text-sm font-semibold text-indigo-700 hover:bg-indigo-100">
                ← Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <form method="GET" action="{{ route('admin.orders.index') }}" class="mb-6 grid grid-cols-1 gap-4 rounded-3xl bg-white p-5 shadow-sm ring-1 ring-gray-200 md:grid-cols-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Tìm kiếm</label>
                    <input name="keyword" type="text" value="{{ request('keyword') }}" placeholder="Mã đơn, tên, email hoặc số điện thoại" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Trạng thái</label>
                    <select name="status" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Tất cả</option>
                        <option value="pending" @selected(request('status') === 'pending')>Chờ xác nhận</option>
                        <option value="confirmed" @selected(request('status') === 'confirmed')>Đã xác nhận</option>
                        <option value="shipping" @selected(request('status') === 'shipping')>Đang giao</option>
                        <option value="completed" @selected(request('status') === 'completed')>Hoàn thành</option>
                        <option value="cancelled" @selected(request('status') === 'cancelled')>Đã hủy</option>
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2.5 text-white hover:bg-indigo-700">Lọc</button>
                    <a href="{{ route('admin.orders.index') }}" class="rounded-xl bg-gray-200 px-4 py-2.5 text-gray-700 hover:bg-gray-300">Xóa lọc</a>
                </div>
            </form>

            @if (session('success'))
                <div class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-700 shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-gray-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs uppercase tracking-wider text-gray-500">Đơn hàng</th>
                                <th class="px-5 py-3 text-left text-xs uppercase tracking-wider text-gray-500">Khách hàng</th>
                                <th class="px-5 py-3 text-left text-xs uppercase tracking-wider text-gray-500">Tổng tiền</th>
                                <th class="px-5 py-3 text-left text-xs uppercase tracking-wider text-gray-500">Trạng thái</th>
                                <th class="px-5 py-3 text-right text-xs uppercase tracking-wider text-gray-500">Thao tác</th>
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

                                <tr class="hover:bg-gray-50">
                                    <td class="px-5 py-4">
                                        <p class="font-semibold text-gray-900">{{ $order->order_code }}</p>
                                        <p class="text-sm text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                                    </td>
                                    <td class="px-5 py-4">
                                        <p class="text-gray-900">{{ $order->customer_name }}</p>
                                        <p class="text-sm text-gray-500">{{ $order->phone }}</p>
                                    </td>
                                    <td class="px-5 py-4 font-semibold text-red-600">{{ number_format($order->total, 0, ',', '.') }}₫</td>
                                    <td class="px-5 py-4"><span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700">{{ $labels[$order->status] ?? $order->status }}</span></td>
                                    <td class="px-5 py-4 text-right"><a href="{{ route('admin.orders.show', $order) }}" class="font-medium text-indigo-600 hover:text-indigo-800">Xem chi tiết</a></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-10 text-center text-gray-500">Chưa có đơn hàng.</td>
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