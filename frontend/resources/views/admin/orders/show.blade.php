<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-indigo-600">Order</p>
                <h2 class="mt-1 text-2xl font-bold text-gray-900">Chi tiết đơn hàng {{ $order->order_code }}</h2>
            </div>

            <a href="{{ route('admin.orders.index') }}" class="rounded-xl border border-indigo-200 bg-indigo-50 px-4 py-2 text-sm font-semibold text-indigo-700 hover:bg-indigo-100">← Danh sách</a>
        </div>
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
            @if (session('success'))
                <div class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-700 shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 p-4 text-red-700 shadow-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 gap-7 lg:grid-cols-3">
                <div class="space-y-7 lg:col-span-2">
                    <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                        <h2 class="text-xl font-bold text-gray-900">Sản phẩm</h2>

                        <div class="mt-5 divide-y divide-gray-200">
                            @foreach ($order->items as $item)
                                <div class="flex justify-between gap-5 py-4">
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $item->product_name }}</p>
                                        <p class="text-sm text-gray-500">{{ number_format($item->price, 0, ',', '.') }}₫ × {{ $item->quantity }}</p>
                                    </div>
                                    <p class="font-semibold text-gray-900">{{ number_format($item->subtotal, 0, ',', '.') }}₫</p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                        <h2 class="text-xl font-bold text-gray-900">Thông tin khách hàng</h2>

                        <div class="mt-5 space-y-3 text-gray-700">
                            <p><strong>Họ tên:</strong> {{ $order->customer_name }}</p>
                            <p><strong>Email:</strong> {{ $order->email }}</p>
                            <p><strong>Điện thoại:</strong> {{ $order->phone }}</p>
                            <p><strong>Địa chỉ:</strong> {{ $order->shipping_address }}</p>
                            @if ($order->note)
                                <p><strong>Ghi chú:</strong> {{ $order->note }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <aside class="h-fit rounded-3xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                    <h2 class="text-xl font-bold text-gray-900">Xử lý đơn hàng</h2>

                    <div class="mt-5 space-y-3 text-sm text-gray-700">
                        <p><strong>Trạng thái:</strong> {{ $labels[$order->status] ?? $order->status }}</p>
                        <p><strong>Thanh toán:</strong> {{ $order->payment_status === 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán' }}</p>
                        <p><strong>Tổng tiền:</strong> <span class="text-lg font-bold text-red-600">{{ number_format($order->total, 0, ',', '.') }}₫</span></p>
                    </div>

                    @if (count($allowedStatuses) > 0)
                        <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="mt-6">
                            @csrf
                            @method('PUT')

                            <label class="block text-sm font-medium text-gray-700">Chuyển trạng thái</label>
                            <select name="status" class="mt-2 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">Chọn trạng thái</option>
                                @foreach ($allowedStatuses as $status)
                                    <option value="{{ $status }}">{{ $labels[$status] ?? $status }}</option>
                                @endforeach
                            </select>

                            @error('status')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            <button type="submit" class="mt-4 w-full rounded-xl bg-indigo-600 px-5 py-3 font-semibold text-white hover:bg-indigo-700">
                                Cập nhật trạng thái
                            </button>
                        </form>
                    @else
                        <p class="mt-6 rounded-xl bg-gray-100 p-4 text-sm text-gray-600">Đơn hàng đã kết thúc, không thể đổi trạng thái.</p>
                    @endif
                </aside>
            </div>
        </div>
    </div>
</x-app-layout>