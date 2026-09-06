@if ($errors->any())
    <div class="mb-6 rounded-md bg-red-100 p-4 text-red-700">
        <p class="font-semibold">Vui lòng kiểm tra lại dữ liệu:</p>

        <ul class="mt-2 list-inside list-disc text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid grid-cols-1 gap-6 md:grid-cols-2">
    <div class="md:col-span-2">
        <label for="name" class="block text-sm font-medium text-gray-700">
            Tên sản phẩm
        </label>

        <input
            id="name"
            name="name"
            type="text"
            value="{{ old('name', $product->name ?? '') }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                   focus:border-indigo-500 focus:ring-indigo-500"
            required
        >
    </div>

    <div>
        <label for="category_id" class="block text-sm font-medium text-gray-700">
            Danh mục
        </label>

        <select
            id="category_id"
            name="category_id"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                   focus:border-indigo-500 focus:ring-indigo-500"
            required
        >
            <option value="">-- Chọn danh mục --</option>

            @foreach ($categories as $category)
                <option
                    value="{{ $category->id }}"
                    @selected(
                        old('category_id', $product->category_id ?? '') == $category->id
                    )
                >
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="sku" class="block text-sm font-medium text-gray-700">
            Mã sản phẩm
        </label>

        <input
            id="sku"
            name="sku"
            type="text"
            value="{{ old('sku', $product->sku ?? '') }}"
            placeholder="Ví dụ: LAPTOP-001"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                   focus:border-indigo-500 focus:ring-indigo-500"
        >
    </div>

    <div>
        <label for="price" class="block text-sm font-medium text-gray-700">
            Giá gốc
        </label>

        <input
            id="price"
            name="price"
            type="number"
            min="0"
            step="1000"
            value="{{ old('price', $product->price ?? '') }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                   focus:border-indigo-500 focus:ring-indigo-500"
            required
        >
    </div>

    <div>
        <label for="sale_price" class="block text-sm font-medium text-gray-700">
            Giá khuyến mãi
        </label>

        <input
            id="sale_price"
            name="sale_price"
            type="number"
            min="0"
            step="1000"
            value="{{ old('sale_price', $product->sale_price ?? '') }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                   focus:border-indigo-500 focus:ring-indigo-500"
        >
    </div>

    <div>
        <label for="stock" class="block text-sm font-medium text-gray-700">
            Số lượng tồn kho
        </label>

        <input
            id="stock"
            name="stock"
            type="number"
            min="0"
            value="{{ old('stock', $product->stock ?? 0) }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                   focus:border-indigo-500 focus:ring-indigo-500"
            required
        >
    </div>

    <div>
        <label for="thumbnail" class="block text-sm font-medium text-gray-700">
            Ảnh đại diện
        </label>

        <input
            id="thumbnail"
            name="thumbnail"
            type="file"
            accept=".jpg,.jpeg,.png,.webp"
            class="mt-1 block w-full rounded-md border border-gray-300
                   bg-white p-2 text-sm"
        >

        <p class="mt-1 text-xs text-gray-500">
            Chấp nhận JPG, PNG, WEBP; tối đa 2 MB.
        </p>
    </div>

    @if (!empty($product?->thumbnail))
        <div class="md:col-span-2">
            <p class="mb-2 text-sm font-medium text-gray-700">
                Ảnh hiện tại
            </p>

            <img
                src="{{ asset('storage/'.$product->thumbnail) }}"
                alt="{{ $product->name }}"
                class="h-40 w-40 rounded-lg object-cover"
            >
        </div>
    @endif

    <div class="md:col-span-2">
        <label for="description" class="block text-sm font-medium text-gray-700">
            Mô tả sản phẩm
        </label>

        <textarea
            id="description"
            name="description"
            rows="7"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                   focus:border-indigo-500 focus:ring-indigo-500"
        >{{ old('description', $product->description ?? '') }}</textarea>
    </div>

    <div>
        <input type="hidden" name="status" value="0">

        <label class="flex items-center gap-3">
            <input
                name="status"
                type="checkbox"
                value="1"
                class="rounded border-gray-300 text-indigo-600 shadow-sm"
                @checked(
                    old('status', isset($product) ? $product->status : true)
                )
            >

            <span class="text-sm text-gray-700">
                Hiển thị sản phẩm
            </span>
        </label>
    </div>

    <div>
        <input type="hidden" name="is_featured" value="0">

        <label class="flex items-center gap-3">
            <input
                name="is_featured"
                type="checkbox"
                value="1"
                class="rounded border-gray-300 text-indigo-600 shadow-sm"
                @checked(
                    old(
                        'is_featured',
                        isset($product) ? $product->is_featured : false
                    )
                )
            >

            <span class="text-sm text-gray-700">
                Sản phẩm nổi bật
            </span>
        </label>
    </div>

    <div class="flex gap-3 md:col-span-2">
        <button
            type="submit"
            class="rounded-md bg-indigo-600 px-5 py-2.5 font-medium
                   text-white hover:bg-indigo-700"
        >
            Lưu sản phẩm
        </button>

        <a
            href="{{ route('admin.products.index') }}"
            class="rounded-md bg-gray-200 px-5 py-2.5 text-gray-700
                   hover:bg-gray-300"
        >
            Quay lại
        </a>
    </div>
</div>