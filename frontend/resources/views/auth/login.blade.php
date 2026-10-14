<x-guest-layout>
    <div class="mb-6 text-center">
        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-100 text-2xl shadow-sm">
            🔐
        </div>

        <h1 class="text-2xl font-semibold text-gray-900">Đăng nhập</h1>
        <p class="mt-2 text-sm text-gray-600">Đăng nhập để tiếp tục mua sắm tại TechStore.</p>
    </div>

    <div class="mb-5 rounded-2xl bg-slate-50 p-4 text-sm text-gray-600">
        <p class="font-medium text-gray-800">Chào mừng quay lại</p>
        <p class="mt-1">Theo dõi đơn hàng và quản lý tài khoản của bạn nhanh hơn.</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Mật khẩu')" />

            <x-text-input id="password" class="mt-1 block w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4 block">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Ghi nhớ đăng nhập') }}</span>
            </label>
        </div>

        <div class="mt-4 flex flex-col gap-4">
            @if (Route::has('password.request'))
                <a class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2" href="{{ route('password.request') }}">
                    {{ __('Quên mật khẩu?') }}
                </a>
            @endif

            <x-primary-button class="w-full justify-center py-3">
                {{ __('Đăng nhập') }}
            </x-primary-button>
        </div>
    </form>

    @if (Route::has('register'))
        <p class="mt-6 border-t border-gray-200 pt-5 text-center text-sm text-gray-600">
            Chưa có tài khoản?
            <a href="{{ route('register') }}" class="rounded-md font-semibold text-indigo-600 hover:text-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Đăng ký tài khoản
            </a>
        </p>
    @endif
</x-guest-layout>
