<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-sm font-medium uppercase tracking-[0.18em] text-indigo-600">Tài khoản</p>
                <h2 class="text-2xl font-bold text-gray-900 leading-tight">
                    {{ __('Profile') }}
                </h2>
            </div>

            <a href="{{ route('dashboard') }}" class="inline-flex items-center rounded-full border border-indigo-200 bg-indigo-50 px-4 py-2 text-sm font-semibold text-indigo-700 hover:bg-indigo-100">
                ← Quay lại dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-6xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="rounded-3xl border border-indigo-100 bg-gradient-to-r from-indigo-50 to-white p-5 shadow-sm">
                <p class="text-sm font-medium text-indigo-700">Thông tin cá nhân</p>
                <p class="mt-1 text-gray-600">Cập nhật thông tin liên hệ để đặt hàng nhanh hơn và nhận hàng đúng địa chỉ.</p>
            </div>

            <div class="grid gap-6 xl:grid-cols-[1.3fr_0.7fr]">
                <div class="space-y-6">
                    <div class="rounded-3xl bg-white p-4 shadow-sm ring-1 ring-gray-200 sm:p-8">
                        <div class="max-w-xl">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>

                    <div class="rounded-3xl bg-white p-4 shadow-sm ring-1 ring-gray-200 sm:p-8">
                        <div class="max-w-xl">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl bg-white p-4 shadow-sm ring-1 ring-gray-200 sm:p-6">
                    <div class="flex items-center gap-4 border-b border-gray-200 pb-5">
                        <div class="flex h-14 w-14 items-center justify-center rounded-full bg-indigo-100 text-lg font-bold text-indigo-600">
                            {{ mb_strtoupper(mb_substr(auth()->user()->name ?? 'U', 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-lg font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                            <p class="text-sm text-gray-500">Khách hàng TechStore</p>
                        </div>
                    </div>

                    <div class="mt-5 space-y-4 text-sm">
                        <div>
                            <p class="text-gray-500">Email</p>
                            <p class="mt-1 font-medium text-gray-900 break-all">{{ auth()->user()->email }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Số điện thoại</p>
                            <p class="mt-1 font-medium text-gray-900">{{ auth()->user()->phone ?: 'Chưa cập nhật' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Địa chỉ</p>
                            <p class="mt-1 font-medium text-gray-900">{{ auth()->user()->address ?: 'Chưa cập nhật' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl bg-white p-4 shadow-sm ring-1 ring-gray-200 sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
