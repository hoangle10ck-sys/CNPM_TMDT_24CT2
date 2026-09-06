<div class="space-y-6">
    <div>
        <label
            for="name"
            class="block text-sm font-medium text-gray-700"
        >
            Tên danh mục
            <span class="text-red-600">*</span>
        </label>

        <input
            id="name"
            name="name"
            type="text"
            value="{{ old(
                'name',
                isset($category) ? $category->name : ''
            ) }}"
            placeholder="Ví dụ: Laptop"
            class="mt-2 block w-full rounded-lg border-gray-300
                   shadow-sm focus:border-indigo-500
                   focus:ring-indigo-500"
            required
            autofocus
        >

        @error('name')
            <p class="mt-2 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror
    </div>

    <div>
        <label
            for="description"
            class="block text-sm font-medium text-gray-700"
        >
            Mô tả danh mục
        </label>

        <textarea
            id="description"
            name="description"
            rows="5"
            placeholder="Nhập mô tả cho danh mục"
            class="mt-2 block w-full rounded-lg border-gray-300
                   shadow-sm focus:border-indigo-500
                   focus:ring-indigo-500"
        >{{ old(
            'description',
            isset($category) ? $category->description : ''
        ) }}</textarea>

        @error('description')
            <p class="mt-2 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror
    </div>

    <div class="rounded-lg bg-gray-50 p-4">
        <input
            type="hidden"
            name="status"
            value="0"
        >

        <label class="flex cursor-pointer items-center gap-3">
            <input
                name="status"
                type="checkbox"
                value="1"
                class="rounded border-gray-300 text-indigo-600
                       shadow-sm focus:ring-indigo-500"
                @checked(old(
                    'status',
                    isset($category) ? $category->status : true
                ))
            >

            <span>
                <span class="block font-medium text-gray-800">
                    Hiển thị danh mục
                </span>

                <span class="block text-sm text-gray-500">
                    Danh mục được hiển thị trên trang cửa hàng.
                </span>
            </span>
        </label>

        @error('status')
            <p class="mt-2 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror
    </div>

    <div class="flex flex-col-reverse gap-3 border-t pt-6 sm:flex-row
                sm:justify-end">
        <a
            href="{{ route('admin.categories.index') }}"
            class="rounded-lg border border-gray-300 px-5 py-2.5
                   text-center font-medium text-gray-700
                   hover:bg-gray-50"
        >
            Hủy bỏ
        </a>

        <button
            type="submit"
            class="rounded-lg bg-indigo-600 px-6 py-2.5
                   font-semibold text-white hover:bg-indigo-700"
        >
            {{ isset($category)
                ? 'Cập nhật danh mục'
                : 'Thêm danh mục' }}
        </button>
    </div>
</div>