<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">
                Chỉnh sửa danh mục
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Cập nhật thông tin danh mục {{ $category->name }}.
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
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <h1 class="text-xl font-bold text-gray-900">
                                {{ $category->name }}
                            </h1>

                            <p class="mt-1 text-sm text-gray-500">
                                Đường dẫn: {{ $category->slug }}
                            </p>
                        </div>

                        @if ($category->status)
                            <span class="rounded-full bg-green-100
                                         px-3 py-1 text-sm font-medium
                                         text-green-700">
                                Đang hiển thị
                            </span>
                        @else
                            <span class="rounded-full bg-gray-100
                                         px-3 py-1 text-sm font-medium
                                         text-gray-700">
                                Đang ẩn
                            </span>
                        @endif
                    </div>
                </div>

                <form
                    method="POST"
                    action="{{ route(
                        'admin.categories.update',
                        $category
                    ) }}"
                    class="p-6"
                >
                    @csrf
                    @method('PUT')

                    @include('admin.categories._form')
                </form>
            </div>
        </div>
    </div>
</x-app-layout>