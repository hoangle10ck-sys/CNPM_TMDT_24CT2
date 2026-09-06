<nav
    x-data="{ open: false }"
    class="sticky top-0 z-50 border-b border-gray-200
           bg-white shadow-sm"
>
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-20 items-center justify-between">
            <a
                href="{{ route('home') }}"
                class="shrink-0 text-2xl font-bold text-indigo-600"
            >
                TechStore
            </a>

            <div class="hidden items-center gap-7 lg:flex">
                @if (auth()->user()->isAdmin())
                    <a
                        href="{{ route('admin.dashboard') }}"
                        class="text-sm font-medium transition
                               {{ request()->routeIs('admin.dashboard')
                                   ? 'text-indigo-600'
                                   : 'text-gray-700 hover:text-indigo-600' }}"
                    >
                        Quản trị
                    </a>

                    <a
                        href="{{ route('admin.categories.index') }}"
                        class="text-sm font-medium transition
                               {{ request()->routeIs('admin.categories.*')
                                   ? 'text-indigo-600'
                                   : 'text-gray-700 hover:text-indigo-600' }}"
                    >
                        Danh mục
                    </a>

                    <a
                        href="{{ route('admin.products.index') }}"
                        class="text-sm font-medium transition
                               {{ request()->routeIs('admin.products.*')
                                   ? 'text-indigo-600'
                                   : 'text-gray-700 hover:text-indigo-600' }}"
                    >
                        Sản phẩm
                    </a>

                    <a
                        href="{{ route('admin.orders.index') }}"
                        class="text-sm font-medium transition
                               {{ request()->routeIs('admin.orders.*')
                                   ? 'text-indigo-600'
                                   : 'text-gray-700 hover:text-indigo-600' }}"
                    >
                        Đơn hàng
                    </a>

                    <a
                        href="{{ route('admin.customers.index') }}"
                        class="text-sm font-medium transition
                               {{ request()->routeIs('admin.customers.*')
                                   ? 'text-indigo-600'
                                   : 'text-gray-700 hover:text-indigo-600' }}"
                    >
                        Khách hàng
                    </a>

                    <a
                        href="{{ route('home') }}"
                        class="text-sm font-medium text-gray-700
                               transition hover:text-indigo-600"
                    >
                        Xem cửa hàng
                    </a>
                @else
                    <a
                        href="{{ route('home') }}"
                        class="text-sm font-medium text-gray-700
                               hover:text-indigo-600"
                    >
                        Trang chủ
                    </a>

                    <a
                        href="{{ route('products.index') }}"
                        class="text-sm font-medium text-gray-700
                               hover:text-indigo-600"
                    >
                        Sản phẩm
                    </a>

                    <a
                        href="{{ route('cart.index') }}"
                        class="text-sm font-medium text-gray-700
                               hover:text-indigo-600"
                    >
                        Giỏ hàng
                    </a>

                    <a
                        href="{{ route('orders.index') }}"
                        class="text-sm font-medium text-gray-700
                               hover:text-indigo-600"
                    >
                        Đơn hàng
                    </a>

                    <a
                        href="{{ route('dashboard') }}"
                        class="text-sm font-medium text-indigo-600"
                    >
                        Tài khoản
                    </a>
                @endif

                <span class="text-sm text-gray-500">
                    Xin chào,
                    <strong class="text-gray-800">
                        {{ auth()->user()->name }}
                    </strong>
                </span>

                <form
                    method="POST"
                    action="{{ route('logout') }}"
                >
                    @csrf

                    <button
                        type="submit"
                        class="text-sm font-medium text-red-600
                               hover:text-red-800"
                    >
                        Đăng xuất
                    </button>
                </form>
            </div>

            <button
                type="button"
                @click="open = !open"
                class="rounded-lg p-2 text-gray-600
                       hover:bg-gray-100 lg:hidden"
            >
                <svg
                    x-show="!open"
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16"
                    />
                </svg>

                <svg
                    x-show="open"
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"
                    />
                </svg>
            </button>
        </div>

        <div
            x-show="open"
            x-transition
            class="border-t py-4 lg:hidden"
        >
            <div class="flex flex-col gap-2">
                @if (auth()->user()->isAdmin())
                    <a
                        href="{{ route('admin.dashboard') }}"
                        class="rounded-lg px-4 py-2 font-medium
                               {{ request()->routeIs('admin.dashboard')
                                   ? 'bg-indigo-50 text-indigo-600'
                                   : 'text-gray-700 hover:bg-gray-100' }}"
                    >
                        Quản trị
                    </a>

                    <a
                        href="{{ route('admin.categories.index') }}"
                        class="rounded-lg px-4 py-2 text-gray-700
                               hover:bg-gray-100"
                    >
                        Danh mục
                    </a>

                    <a
                        href="{{ route('admin.products.index') }}"
                        class="rounded-lg px-4 py-2 text-gray-700
                               hover:bg-gray-100"
                    >
                        Sản phẩm
                    </a>

                    <a
                        href="{{ route('admin.orders.index') }}"
                        class="rounded-lg px-4 py-2 text-gray-700
                               hover:bg-gray-100"
                    >
                        Đơn hàng
                    </a>

                    <a
                        href="{{ route('admin.customers.index') }}"
                        class="rounded-lg px-4 py-2 text-gray-700
                               hover:bg-gray-100"
                    >
                        Khách hàng
                    </a>

                    <a
                        href="{{ route('home') }}"
                        class="rounded-lg px-4 py-2 text-gray-700
                               hover:bg-gray-100"
                    >
                        Xem cửa hàng
                    </a>
                @else
                    <a
                        href="{{ route('home') }}"
                        class="rounded-lg px-4 py-2 text-gray-700
                               hover:bg-gray-100"
                    >
                        Trang chủ
                    </a>

                    <a
                        href="{{ route('products.index') }}"
                        class="rounded-lg px-4 py-2 text-gray-700
                               hover:bg-gray-100"
                    >
                        Sản phẩm
                    </a>

                    <a
                        href="{{ route('cart.index') }}"
                        class="rounded-lg px-4 py-2 text-gray-700
                               hover:bg-gray-100"
                    >
                        Giỏ hàng
                    </a>

                    <a
                        href="{{ route('orders.index') }}"
                        class="rounded-lg px-4 py-2 text-gray-700
                               hover:bg-gray-100"
                    >
                        Đơn hàng
                    </a>

                    <a
                        href="{{ route('dashboard') }}"
                        class="rounded-lg px-4 py-2 text-gray-700
                               hover:bg-gray-100"
                    >
                        Tài khoản
                    </a>
                @endif

                <div class="mt-2 border-t px-4 pt-4">
                    <p class="text-sm text-gray-500">
                        {{ auth()->user()->name }}
                    </p>

                    <p class="text-xs text-gray-400">
                        {{ auth()->user()->email }}
                    </p>
                </div>

                <form
                    method="POST"
                    action="{{ route('logout') }}"
                    class="px-4"
                >
                    @csrf

                    <button
                        type="submit"
                        class="py-2 font-medium text-red-600"
                    >
                        Đăng xuất
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>