<x-app-layout>
    <x-slot name="header">
        <h2 class="h5 fw-semibold text-dark mb-0">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="container" style="max-width: 56rem;">
            <div class="bg-white border rounded-4 p-4 p-md-5 mb-4">
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="bg-white border rounded-4 p-4 p-md-5 mb-4">
                @include('profile.partials.manage-addresses')
            </div>

            <div class="bg-white border rounded-4 p-4 p-md-5 mb-4">
                @include('profile.partials.update-password-form')
            </div>

            <div class="bg-white border rounded-4 p-4 p-md-5 mb-4">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
