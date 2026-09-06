<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        @yield('title', 'TechStore - Cửa hàng công nghệ')
    </title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="flex min-h-screen flex-col bg-gray-50 text-gray-900">
    <header
        x-data="{ mobileMenuOpen: false }"
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

                <nav class="hidden items-center gap-6 lg:flex">
                    <a
                        href="{{ route('home') }}"
                        class="text-sm font-medium transition
                               {{ request()->routeIs('home')
                                   ? 'text-indigo-600'
                                   : 'text-gray-700 hover:text-indigo-600' }}"
                    >
                        Trang chủ
                    </a>

                    @auth
                        @if (auth()->user()->isAdmin())
                            <a
                                href="{{ route('admin.dashboard') }}"
                                class="text-sm font-medium transition
                                       {{ request()->routeIs('admin.*')
                                           ? 'text-indigo-600'
                                           : 'text-gray-700 hover:text-indigo-600' }}"
                            >
                                Quản trị
                            </a>
                        @else
                            <a
                                href="{{ route('products.index') }}"
                                class="text-sm font-medium transition
                                       {{ request()->routeIs('products.*')
                                           ? 'text-indigo-600'
                                           : 'text-gray-700 hover:text-indigo-600' }}"
                            >
                                Sản phẩm
                            </a>

                            <a
                                href="{{ route('cart.index') }}"
                                class="relative text-sm font-medium transition
                                       {{ request()->routeIs('cart.*')
                                           ? 'text-indigo-600'
                                           : 'text-gray-700 hover:text-indigo-600' }}"
                            >
                                Giỏ hàng

                                @php
                                    $cartQuantity = auth()->user()->cart
                                        ? auth()->user()->cart
                                            ->items()
                                            ->sum('quantity')
                                        : 0;
                                @endphp

                                @if ($cartQuantity > 0)
                                    <span class="ml-1 rounded-full bg-red-600
                                                 px-2 py-0.5 text-xs text-white">
                                        {{ $cartQuantity }}
                                    </span>
                                @endif
                            </a>

                            <a
                                href="{{ route('orders.index') }}"
                                class="text-sm font-medium transition
                                       {{ request()->routeIs('orders.*')
                                           ? 'text-indigo-600'
                                           : 'text-gray-700 hover:text-indigo-600' }}"
                            >
                                Đơn hàng
                            </a>

                            <a
                                href="{{ route('dashboard') }}"
                                class="text-sm font-medium transition
                                       {{ request()->routeIs('dashboard')
                                           ? 'text-indigo-600'
                                           : 'text-gray-700 hover:text-indigo-600' }}"
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
                    @else
                        <a
                            href="{{ route('products.index') }}"
                            class="text-sm font-medium transition
                                   {{ request()->routeIs('products.*')
                                       ? 'text-indigo-600'
                                       : 'text-gray-700 hover:text-indigo-600' }}"
                        >
                            Sản phẩm
                        </a>

                        <a
                            href="{{ route('login') }}"
                            class="text-sm font-medium text-gray-700
                                   hover:text-indigo-600"
                        >
                            Đăng nhập
                        </a>

                        <a
                            href="{{ route('register') }}"
                            class="rounded-lg bg-indigo-600 px-4 py-2
                                   text-sm font-medium text-white
                                   hover:bg-indigo-700"
                        >
                            Đăng ký
                        </a>
                    @endauth
                </nav>

                <button
                    type="button"
                    @click="mobileMenuOpen = !mobileMenuOpen"
                    class="rounded-lg p-2 text-gray-600
                           hover:bg-gray-100 lg:hidden"
                    aria-label="Mở menu"
                >
                    <svg
                        x-show="!mobileMenuOpen"
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
                        x-show="mobileMenuOpen"
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
                x-show="mobileMenuOpen"
                x-transition
                class="border-t border-gray-200 py-4 lg:hidden"
            >
                <nav class="flex flex-col gap-2">
                    <a
                        href="{{ route('home') }}"
                        class="rounded-lg px-4 py-2 font-medium
                               {{ request()->routeIs('home')
                                   ? 'bg-indigo-50 text-indigo-600'
                                   : 'text-gray-700 hover:bg-gray-100' }}"
                    >
                        Trang chủ
                    </a>

                    @auth
                        @if (auth()->user()->isAdmin())
                            <a
                                href="{{ route('admin.dashboard') }}"
                                class="rounded-lg px-4 py-2 font-medium
                                       {{ request()->routeIs('admin.*')
                                           ? 'bg-indigo-50 text-indigo-600'
                                           : 'text-gray-700 hover:bg-gray-100' }}"
                            >
                                Quản trị
                            </a>
                        @else
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

                                @if (($cartQuantity ?? 0) > 0)
                                    ({{ $cartQuantity }})
                                @endif
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

                        <div class="mt-2 border-t border-gray-200
                                    px-4 pt-4">
                            <p class="text-sm text-gray-500">
                                Xin chào,
                                <strong class="text-gray-800">
                                    {{ auth()->user()->name }}
                                </strong>
                            </p>

                            <p class="mt-1 text-xs text-gray-400">
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
                    @else
                        <a
                            href="{{ route('products.index') }}"
                            class="rounded-lg px-4 py-2 text-gray-700
                                   hover:bg-gray-100"
                        >
                            Sản phẩm
                        </a>

                        <a
                            href="{{ route('login') }}"
                            class="rounded-lg px-4 py-2 text-gray-700
                                   hover:bg-gray-100"
                        >
                            Đăng nhập
                        </a>

                        <a
                            href="{{ route('register') }}"
                            class="rounded-lg bg-indigo-600 px-4 py-2
                                   text-center font-medium text-white"
                        >
                            Đăng ký
                        </a>
                    @endauth
                </nav>
            </div>
        </div>
    </header>

    <main class="flex-1">
        @yield('content')
    </main>

    <footer class="mt-16 bg-gray-900 text-gray-300">
        <div class="mx-auto grid max-w-7xl grid-cols-1 gap-8
                    px-4 py-10 sm:px-6 md:grid-cols-3 lg:px-8">
            <div>
                <h3 class="text-xl font-bold text-white">
                    TechStore
                </h3>

                <p class="mt-3 text-sm leading-6">
                    Website thương mại điện tử được xây dựng bằng
                    Laravel theo mô hình MVC.
                </p>
            </div>

            <div>
                <h3 class="font-semibold text-white">
                    Liên kết
                </h3>

                <div class="mt-3 space-y-2 text-sm">
                    <a
                        href="{{ route('home') }}"
                        class="block hover:text-white"
                    >
                        Trang chủ
                    </a>

                    @auth
                        @if (auth()->user()->isAdmin())
                            <a
                                href="{{ route('admin.dashboard') }}"
                                class="block hover:text-white"
                            >
                                Quản trị
                            </a>
                        @else
                            <a
                                href="{{ route('products.index') }}"
                                class="block hover:text-white"
                            >
                                Sản phẩm
                            </a>

                            <a
                                href="{{ route('cart.index') }}"
                                class="block hover:text-white"
                            >
                                Giỏ hàng
                            </a>

                            <a
                                href="{{ route('orders.index') }}"
                                class="block hover:text-white"
                            >
                                Đơn hàng của tôi
                            </a>
                        @endif
                    @else
                        <a
                            href="{{ route('products.index') }}"
                            class="block hover:text-white"
                        >
                            Sản phẩm
                        </a>

                        <a
                            href="{{ route('login') }}"
                            class="block hover:text-white"
                        >
                            Đăng nhập
                        </a>
                    @endauth
                </div>
            </div>

            <div>
                <h3 class="font-semibold text-white">
                    Liên hệ
                </h3>

                <div class="mt-3 space-y-2 text-sm">
                    <p>Email: support@techstore.vn</p>
                    <p>Điện thoại: 0900 000 000</p>
                    <p>Địa chỉ: Việt Nam</p>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-800 py-4 text-center text-sm">
            © {{ date('Y') }} TechStore. All rights reserved.
        </div>
    </footer>
</body>
</html>