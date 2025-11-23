<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[#0f172a] leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="rounded-3xl border border-[#e5e7eb] bg-white p-6 sm:p-8 shadow-sm">
                <div class="max-w-xl mx-auto">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="rounded-3xl border border-[#e5e7eb] bg-white p-6 sm:p-8 shadow-sm">
                <div class="max-w-3xl mx-auto">
                    @include('profile.partials.manage-addresses')
                </div>
            </div>

            <div class="rounded-3xl border border-[#e5e7eb] bg-white p-6 sm:p-8 shadow-sm">
                <div class="max-w-xl mx-auto">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="rounded-3xl border border-[#e5e7eb] bg-white p-6 sm:p-8 shadow-sm">
                <div class="max-w-xl mx-auto">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
