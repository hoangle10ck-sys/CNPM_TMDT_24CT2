<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            Chi tiết khách hàng
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <a
                    href="{{ route('admin.customers.index') }}"
                    class="text-indigo-600"
                >
                    ← Danh sách khách hàng
                </a>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                <div class="rounded-xl bg-white p-6 shadow-sm">
                    <p class="text-sm text-gray-500">Tổng đơn hàng</p>

                    <p class="mt-2 text-3xl font-bold text-indigo-600">
                        {{ $statistics['total_orders'] }}
                    </p>
                </div>

                <div class="rounded-xl bg-white p-6 shadow-sm">
                    <p class="text-sm text-gray-500">
                        Đơn đã hoàn thành
                    </p>

                    <p class="mt-2 text-3xl font-bold text-green-600">
                        {{ $statistics['completed_orders'] }}
                    </p>
                </div>

                <div class="rounded-xl bg-white p-6 shadow-sm">
                    <p class="text-sm text-gray-500">Tổng chi tiêu</p>

                    <p class="mt-2 text-2xl font-bold text-red-600">
                        {{ number_format(
                            $statistics['total_spent'],
                            0,
                            ',',
                            '.'
                        ) }}₫
                    </p>
                </div>
            </div>

            <div class="mt-7 rounded-xl bg-white p-6 shadow-sm">
                <h2 class="text-xl font-bold">Thông tin cá nhân</h2>

                <div class="mt-5 grid grid-cols-1 gap-4 md:grid-cols-2">
                    <p>
                        <strong>Họ tên:</strong>
                        {{ $user->name }}
                    </p>

                    <p>
                        <strong>Email:</strong>
                        {{ $user->email }}
                    </p>

                    <p>
                        <strong>Điện thoại:</strong>
                        {{ $user->phone ?: 'Chưa cập nhật' }}
                    </p>

                    <p>
                        <strong>Ngày đăng ký:</strong>
                        {{ $user->created_at->format('d/m/Y H:i') }}
                    </p>

                    <p class="md:col-span-2">
                        <strong>Địa chỉ:</strong>
                        {{ $user->address ?: 'Chưa cập nhật' }}
                    </p>
                </div>
            </div>

            <div class="mt-7 overflow-hidden rounded-xl bg-white shadow-sm">
                <div class="border-b p-6">
                    <h2 class="text-xl font-bold">
                        Lịch sử đơn hàng
                    </h2>
                </div>

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
                                    Thao tác
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200">
                            @forelse ($orders as $order)
                                <tr>
                                    <td class="px-6 py-4 font-medium">
                                        {{ $order->order_code }}
                                    </td>

                                    <td class="px-6 py-4">
                                        {{ $order->created_at->format(
                                            'd/m/Y H:i'
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
                                        {{ $order->status }}
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        <a
                                            href="{{ route(
                                                'admin.orders.show',
                                                $order
                                            ) }}"
                                            class="font-medium text-indigo-600"
                                        >
                                            Chi tiết
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td
                                        colspan="5"
                                        class="px-6 py-10 text-center
                                               text-gray-500"
                                    >
                                        Khách hàng chưa có đơn hàng.
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