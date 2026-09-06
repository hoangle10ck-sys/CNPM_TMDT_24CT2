<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            Quản lý khách hàng
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
                action="{{ route('admin.customers.index') }}"
                class="mb-6 flex flex-col gap-4 rounded-xl bg-white
                       p-5 shadow-sm md:flex-row"
            >
                <input
                    name="keyword"
                    type="text"
                    value="{{ request('keyword') }}"
                    placeholder="Tìm theo tên, email hoặc số điện thoại"
                    class="flex-1 rounded-md border-gray-300"
                >

                <button
                    type="submit"
                    class="rounded-md bg-indigo-600 px-5 py-2.5
                           text-white hover:bg-indigo-700"
                >
                    Tìm kiếm
                </button>

                <a
                    href="{{ route('admin.customers.index') }}"
                    class="rounded-md bg-gray-200 px-5 py-2.5
                           text-center text-gray-700"
                >
                    Xóa lọc
                </a>
            </form>

            <div class="overflow-hidden rounded-xl bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs
                                           font-medium uppercase text-gray-500">
                                    Khách hàng
                                </th>

                                <th class="px-6 py-3 text-left text-xs
                                           font-medium uppercase text-gray-500">
                                    Điện thoại
                                </th>

                                <th class="px-6 py-3 text-left text-xs
                                           font-medium uppercase text-gray-500">
                                    Đơn hàng
                                </th>

                                <th class="px-6 py-3 text-left text-xs
                                           font-medium uppercase text-gray-500">
                                    Ngày đăng ký
                                </th>

                                <th class="px-6 py-3 text-right text-xs
                                           font-medium uppercase text-gray-500">
                                    Thao tác
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200">
                            @forelse ($customers as $customer)
                                <tr>
                                    <td class="px-6 py-4">
                                        <p class="font-semibold">
                                            {{ $customer->name }}
                                        </p>

                                        <p class="text-sm text-gray-500">
                                            {{ $customer->email }}
                                        </p>
                                    </td>

                                    <td class="px-6 py-4 text-gray-600">
                                        {{ $customer->phone ?: 'Chưa cập nhật' }}
                                    </td>

                                    <td class="px-6 py-4">
                                        {{ $customer->orders_count }}
                                    </td>

                                    <td class="px-6 py-4 text-gray-600">
                                        {{ $customer->created_at->format(
                                            'd/m/Y'
                                        ) }}
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        <a
                                            href="{{ route(
                                                'admin.customers.show',
                                                $customer
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
                                        class="px-6 py-10 text-center
                                               text-gray-500"
                                    >
                                        Chưa có khách hàng.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6">
                {{ $customers->links() }}
            </div>
        </div>
    </div>
</x-app-layout>