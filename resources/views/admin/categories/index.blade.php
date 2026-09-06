<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">
                Quản lý danh mục
            </h2>

            <a
                href="{{ route('admin.dashboard') }}"
                class="text-indigo-600 hover:text-indigo-800"
            >
                Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex justify-end">
                <a
                    href="{{ route('admin.categories.create') }}"
                    class="rounded-md bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700"
                >
                    + Thêm danh mục
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

            <div class="overflow-hidden rounded-lg bg-white shadow">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">
                                    Tên danh mục
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">
                                    Số sản phẩm
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">
                                    Trạng thái
                                </th>

                                <th class="px-6 py-3 text-right text-xs font-medium uppercase text-gray-500">
                                    Thao tác
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse ($categories as $category)
                                <tr>
                                    <td class="px-6 py-4">
                                        <p class="font-medium text-gray-900">
                                            {{ $category->name }}
                                        </p>

                                        <p class="text-sm text-gray-500">
                                            {{ $category->slug }}
                                        </p>
                                    </td>

                                    <td class="px-6 py-4 text-gray-700">
                                        {{ $category->products_count }}
                                    </td>

                                    <td class="px-6 py-4">
                                        @if ($category->status)
                                            <span class="rounded-full bg-green-100 px-3 py-1 text-xs text-green-700">
                                                Đang hiển thị
                                            </span>
                                        @else
                                            <span class="rounded-full bg-gray-100 px-3 py-1 text-xs text-gray-700">
                                                Đang ẩn
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="flex justify-end gap-3">
                                            <a
                                                href="{{ route('admin.categories.edit', $category) }}"
                                                class="text-indigo-600 hover:text-indigo-900"
                                            >
                                                Sửa
                                            </a>

                                            <form
                                                method="POST"
                                                action="{{ route('admin.categories.destroy', $category) }}"
                                                onsubmit="return confirm('Bạn có chắc muốn xóa danh mục này?')"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="text-red-600 hover:text-red-900"
                                                >
                                                    Xóa
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td
                                        colspan="4"
                                        class="px-6 py-10 text-center text-gray-500"
                                    >
                                        Chưa có danh mục.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6">
                {{ $categories->links() }}
            </div>
        </div>
    </div>
</x-app-layout>