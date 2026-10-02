<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            Quản lý sản phẩm
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
                <a
                    href="{{ route('admin.dashboard') }}"
                    class="text-indigo-600 hover:text-indigo-800"
                >
                    ← Dashboard
                </a>

                <a
                    href="{{ route('admin.products.create') }}"
                    class="rounded-md bg-indigo-600 px-4 py-2 text-white
                           hover:bg-indigo-700"
                >
                    + Thêm sản phẩm
                </a>
            </div>

            @if (session('success'))
                <div class="mb-5 rounded-md bg-green-100 p-4 text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <form
                method="GET"
                action="{{ route('admin.products.index') }}"
                class="mb-6 grid grid-cols-1 gap-4 rounded-lg bg-white p-5
                       shadow md:grid-cols-4"
            >
                <input
                    name="keyword"
                    type="text"
                    value="{{ request('keyword') }}"
                    placeholder="Tên hoặc mã sản phẩm"
                    class="rounded-md border-gray-300"
                >

                <select name="category_id" class="rounded-md border-gray-300">
                    <option value="">Tất cả danh mục</option>

                    @foreach ($categories as $category)
                        <option
                            value="{{ $category->id }}"
                            @selected(request('category_id') == $category->id)
                        >
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>

                <select name="status" class="rounded-md border-gray-300">
                    <option value="">Tất cả trạng thái</option>
                    <option value="1" @selected(request('status') === '1')>
                        Đang hiển thị
                    </option>
                    <option value="0" @selected(request('status') === '0')>
                        Đang ẩn
                    </option>
                </select>

                <div class="flex gap-2">
                    <button
                        type="submit"
                        class="rounded-md bg-gray-800 px-4 py-2 text-white
                               hover:bg-gray-900"
                    >
                        Lọc
                    </button>

                    <a
                        href="{{ route('admin.products.index') }}"
                        class="rounded-md bg-gray-200 px-4 py-2 text-gray-700
                               hover:bg-gray-300"
                    >
                        Xóa lọc
                    </a>
                </div>
            </form>

            <div class="overflow-hidden rounded-lg bg-white shadow">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs uppercase text-gray-500">
                                    Sản phẩm
                                </th>
                                <th class="px-5 py-3 text-left text-xs uppercase text-gray-500">
                                    Danh mục
                                </th>
                                <th class="px-5 py-3 text-left text-xs uppercase text-gray-500">
                                    Giá
                                </th>
                                <th class="px-5 py-3 text-left text-xs uppercase text-gray-500">
                                    Kho
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
                            @forelse ($products as $product)
                                <tr>
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-3">
                                            @if ($product->thumbnail)
                                                <img
                                                    src="{{ asset('storage/'.$product->thumbnail) }}"
                                                    alt="{{ $product->name }}"
                                                    class="h-14 w-14 rounded-md object-cover"
                                                >
                                            @else
                                                <div class="flex h-14 w-14 items-center justify-center
                                                            rounded-md bg-gray-100 text-xs text-gray-400">
                                                    Chưa có ảnh
                                                </div>
                                            @endif

                                            <div>
                                                <p class="font-medium text-gray-900">
                                                    {{ $product->name }}
                                                </p>
                                                <p class="text-sm text-gray-500">
                                                    {{ $product->sku ?: 'Chưa có SKU' }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-5 py-4 text-gray-700">
                                        {{ $product->category->name }}
                                    </td>

                                    <td class="px-5 py-4">
                                        @if ($product->sale_price)
                                            <p class="font-semibold text-red-600">
                                                {{ number_format($product->sale_price, 0, ',', '.') }}₫
                                            </p>
                                            <p class="text-sm text-gray-400 line-through">
                                                {{ number_format($product->price, 0, ',', '.') }}₫
                                            </p>
                                        @else
                                            <p class="font-semibold text-gray-900">
                                                {{ number_format($product->price, 0, ',', '.') }}₫
                                            </p>
                                        @endif
                                    </td>

                                    <td class="px-5 py-4 text-gray-700">
                                        {{ $product->stock }}
                                    </td>

                                    <td class="px-5 py-4">
                                        @if ($product->status)
                                            <span class="rounded-full bg-green-100 px-3 py-1
                                                         text-xs text-green-700">
                                                Hiển thị
                                            </span>
                                        @else
                                            <span class="rounded-full bg-gray-100 px-3 py-1
                                                         text-xs text-gray-700">
                                                Đang ẩn
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-5 py-4">
                                        <div class="flex justify-end gap-3">
                                            <a
                                                href="{{ route('admin.products.edit', $product) }}"
                                                class="text-indigo-600 hover:text-indigo-900"
                                            >
                                                Sửa
                                            </a>

                                            <form
                                                method="POST"
                                                action="{{ route('admin.products.destroy', $product) }}"
                                                onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?')"
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
                                        colspan="6"
                                        class="px-6 py-10 text-center text-gray-500"
                                    >
                                        Không tìm thấy sản phẩm.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</x-app-layout>