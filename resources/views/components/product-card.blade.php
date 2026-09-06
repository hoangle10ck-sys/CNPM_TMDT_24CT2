@props(['product'])

<div class="group overflow-hidden rounded-xl bg-white shadow-sm
            transition duration-300 hover:-translate-y-1 hover:shadow-lg">
    <a href="{{ route('products.show', ['product' => $product->slug]) }}">
        @if ($product->thumbnail)
            <img
                src="{{ asset('storage/'.$product->thumbnail) }}"
                alt="{{ $product->name }}"
                class="h-52 w-full object-cover"
            >
        @else
            <div class="flex h-52 items-center justify-center bg-gray-100">
                <div class="text-center text-gray-400">
                    <div class="text-5xl">🛍️</div>
                    <p class="mt-2 text-sm">Chưa có ảnh</p>
                </div>
            </div>
        @endif
    </a>

    <div class="p-5">
        <p class="text-xs font-medium uppercase text-indigo-600">
            {{ $product->category->name }}
        </p>

        <a
            href="{{route('products.show', ['product' => $product->slug]) }}"
            class="mt-2 block min-h-12 font-semibold text-gray-900
                   group-hover:text-indigo-600"
        >
            {{ $product->name }}
        </a>

        <div class="mt-3">
            @if ($product->sale_price)
                <span class="text-lg font-bold text-red-600">
                    {{ number_format($product->sale_price, 0, ',', '.') }}₫
                </span>

                <span class="ml-2 text-sm text-gray-400 line-through">
                    {{ number_format($product->price, 0, ',', '.') }}₫
                </span>
            @else
                <span class="text-lg font-bold text-gray-900">
                    {{ number_format($product->price, 0, ',', '.') }}₫
                </span>
            @endif
        </div>

        <div class="mt-3 flex items-center justify-between">
            <span class="text-sm text-gray-500">
                Còn {{ $product->stock }} sản phẩm
            </span>

            @if ($product->is_featured)
                <span class="rounded-full bg-yellow-100 px-2 py-1
                             text-xs font-medium text-yellow-700">
                    Nổi bật
                </span>
            @endif
        </div>
    </div>
</div>