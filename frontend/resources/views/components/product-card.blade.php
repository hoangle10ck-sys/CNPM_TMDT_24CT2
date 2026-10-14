@props(['product'])

<article class="group overflow-hidden rounded-2xl border border-gray-200/80 bg-white shadow-sm
                transition duration-300 hover:-translate-y-1 hover:border-indigo-200 hover:shadow-xl">
    <a href="{{ route('products.show', ['product' => $product->slug]) }}">
        @if ($product->thumbnail)
            <img
                src="{{ asset('storage/'.$product->thumbnail) }}"
                alt="{{ $product->name }}"
                width="600"
                height="420"
                loading="lazy"
                decoding="async"
                sizes="(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 25vw"
                class="h-52 w-full bg-gray-50 object-contain p-4 transition duration-300 group-hover:scale-[1.03]"
            >
        @else
            <div class="flex h-52 items-center justify-center bg-gradient-to-br from-gray-50 to-indigo-50">
                <div class="text-center text-gray-400">
                    <div class="text-5xl">🛍️</div>
                    <p class="mt-2 text-sm">Chưa có ảnh</p>
                </div>
            </div>
        @endif
    </a>

    <div class="p-5">
        <p class="text-xs font-semibold uppercase tracking-wider text-indigo-600">
            {{ $product->category->name }}
        </p>

        <a
            href="{{route('products.show', ['product' => $product->slug]) }}"
            class="mt-2 block min-h-12 font-semibold leading-6 text-gray-900
                   transition group-hover:text-indigo-600"
        >
            {{ $product->name }}
        </a>

        <div class="mt-3">
            @if ($product->sale_price)
                <span class="text-lg font-bold text-indigo-700">
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
            @if ($product->stock > 0)
                <span class="text-sm text-gray-500">
                    Còn {{ $product->stock }} sản phẩm
                </span>
            @else
                <span class="text-sm font-medium text-red-600">
                    Hết hàng
                </span>
            @endif

            @if ($product->is_featured)
                <span class="rounded-full bg-yellow-100 px-2 py-1
                             text-xs font-medium text-yellow-700">
                    Nổi bật
                </span>
            @endif
        </div>
    </div>
</article>
