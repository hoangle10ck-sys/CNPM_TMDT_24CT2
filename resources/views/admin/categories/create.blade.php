<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">
                Thêm danh mục
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Tạo danh mục sản phẩm mới cho cửa hàng.
            </p>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <a
                    href="{{ route('admin.categories.index') }}"
                    class="font-medium text-indigo-600
                           hover:text-indigo-800"
                >
                    ← Quay lại danh sách danh mục
                </a>
            </div>

            <div class="overflow-hidden rounded-2xl bg-white shadow-sm">
                <div class="border-b border-gray-200 px-6 py-5">
                    <h1 class="text-xl font-bold text-gray-900">
                        Thông tin danh mục
                    </h1>
                </div>

                <form
                    method="POST"
                    action="{{ route('admin.categories.store') }}"
                    class="p-6"
                >
                    @csrf

                    @include('admin.categories._form')
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
