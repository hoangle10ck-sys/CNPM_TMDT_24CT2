<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            Chi tiết đơn hàng {{ $order->order_code }}
        </h2>
    </x-slot>

    @php
        $labels = [
            'pending' => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'shipping' => 'Đang giao',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy',
        ];
    @endphp

    <div class="py-10">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <a
                    href="{{ route('admin.orders.index') }}"
                    class="text-indigo-600"
                >
                    ← Danh sách đơn hàng
                </a>
            </div>

            @if (session('success'))
                <div class="mb-5 rounded-md bg-green-100 p-4 text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-5 rounded-md bg-red-100 p-4 text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 gap-7 lg:grid-cols-3">
                <div class="space-y-7 lg:col-span-2">
                    <div class="rounded-xl bg-white p-6 shadow-sm">
                        <h2 class="text-xl font-bold">Sản phẩm</h2>

                        <div class="mt-5 divide-y">
                            @foreach ($order->items as $item)
                                <div class="flex justify-between gap-5 py-4">
                                    <div>
                                        <p class="font-semibold">
                                            {{ $item->product_name }}
                                        </p>

                                        <p class="text-sm text-gray-500">
                                            {{ number_format(
                                                $item->price,
                                                0,
                                                ',',
                                                '.'
                                            ) }}₫ × {{ $item->quantity }}
                                        </p>
                                    </div>

                                    <p class="font-semibold">
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

                    <div class="rounded-xl bg-white p-6 shadow-sm">
                        <h2 class="text-xl font-bold">
                            Thông tin khách hàng
                        </h2>

                        <div class="mt-5 space-y-3">
                            <p>
                                <strong>Họ tên:</strong>
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
                </div>

                <aside class="h-fit rounded-xl bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-bold">Xử lý đơn hàng</h2>

                    <div class="mt-5 space-y-3">
                        <p>
                            <strong>Trạng thái:</strong>
                            {{ $labels[$order->status] ?? $order->status }}
                        </p>

                        <p>
                            <strong>Thanh toán:</strong>
                            {{ $order->payment_status === 'paid'
                                ? 'Đã thanh toán'
                                : 'Chưa thanh toán' }}
                        </p>

                        <p>
                            <strong>Tổng tiền:</strong>
                            <span class="text-lg text-red-600">
                                {{ number_format(
                                    $order->total,
                                    0,
                                    ',',
                                    '.'
                                ) }}₫
                            </span>
                        </p>
                    </div>

                    @if (count($allowedStatuses) > 0)
                        <form
                            method="POST"
                            action="{{ route(
                                'admin.orders.update',
                                $order
                            ) }}"
                            class="mt-6"
                        >
                            @csrf
                            @method('PUT')

                            <label class="block text-sm font-medium">
                                Chuyển trạng thái
                            </label>

                            <select
                                name="status"
                                class="mt-2 block w-full rounded-md
                                       border-gray-300"
                                required
                            >
                                <option value="">Chọn trạng thái</option>

                                @foreach ($allowedStatuses as $status)
                                    <option value="{{ $status }}">
                                        {{ $labels[$status] ?? $status }}
                                    </option>
                                @endforeach
                            </select>

                            @error('status')
                                <p class="mt-2 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                            <button
                                type="submit"
                                class="mt-4 w-full rounded-md bg-indigo-600
                                       px-5 py-3 font-semibold text-white"
                            >
                                Cập nhật trạng thái
                            </button>
                        </form>
                    @else
                        <p class="mt-6 rounded-lg bg-gray-100 p-4 text-sm text-gray-600">
                            Đơn hàng đã kết thúc, không thể đổi trạng thái.
                        </p>
                    @endif
                </aside>
            </div>
        </div>
    </div>
</x-app-layout>