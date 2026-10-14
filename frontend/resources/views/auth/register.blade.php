<x-guest-layout>
    <div class="w-full max-w-xl">
        <div class="mb-8 text-center">
            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-100 text-2xl shadow-sm ring-1 ring-indigo-200">
                🛒
            </div>

            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Tạo tài khoản TechStore</h1>
            <p class="mt-2 text-sm text-gray-600">Đăng ký để mua sắm nhanh hơn, theo dõi đơn hàng và nhận ưu đãi thành viên.</p>
        </div>

        <div class="mb-6 rounded-2xl border border-indigo-200 bg-gradient-to-r from-indigo-50 to-purple-50 p-4 text-sm text-indigo-700 shadow-sm">
            <p class="font-semibold">Ưu đãi thành viên</p>
            <p class="mt-1 text-indigo-600">Mua hàng nhanh hơn, theo dõi đơn hàng và hưởng quyền lợi độc quyền.</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm sm:p-7">
            @csrf

            <div class="grid gap-4 md:grid-cols-2">
                <div class="md:col-span-2">
                    <x-input-label for="name" :value="__('Họ và tên')" />
                    <x-text-input id="name" class="mt-1 block w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div class="md:col-span-2">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="phone" :value="__('Số điện thoại')" />
                    <x-text-input id="phone" class="mt-1 block w-full" type="tel" name="phone" :value="old('phone')" autocomplete="tel" />
                    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="password" :value="__('Mật khẩu')" />
                    <x-text-input id="password" class="mt-1 block w-full" type="password" name="password" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="md:col-span-2">
                    <x-input-label for="address" :value="__('Địa chỉ nhận hàng')" />
                    <textarea id="address" name="address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Nhập địa chỉ giao hàng của bạn">{{ old('address') }}</textarea>
                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
                </div>

                <div class="md:col-span-2">
                    <x-input-label for="password_confirmation" :value="__('Xác nhận mật khẩu')" />
                    <x-text-input id="password_confirmation" class="mt-1 block w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>
            </div>

            <div class="mt-6">
                <x-primary-button class="w-full justify-center py-3 text-base font-semibold">
                    {{ __('Đăng ký') }}
                </x-primary-button>
            </div>
        </form>

        <p class="mt-6 border-t border-gray-200 pt-5 text-center text-sm text-gray-600">
            Đã có tài khoản?
            <a class="rounded-md font-semibold text-indigo-600 hover:text-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                Đăng nhập
            </a>
        </p>
    </div>
</x-guest-layout>
