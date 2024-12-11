@props([
    'subHeaderClass' => theme('header', 'subHeaderClass'),
    'profileBgColor' => theme('header', 'profileBgColor'),
])

<x-app-layout>
    <x-slot name="header">
        <h2 class="{{ $subHeaderClass }}">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8 {{ $profileBgColor }}">
            @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                @livewire('profile.update-profile-information-form')

                @if (!auth()->user()->isSocialite()) <x-section-border /> @endif
            @endif

            @if (!auth()->user()->isSocialite())
                @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                    <div class="mt-10 sm:mt-0">
                        @livewire('profile.update-password-form')
                    </div>

                    <x-section-border />
                @endif
            @endif

            @if ((Laravel\Fortify\Features::canManageTwoFactorAuthentication() && !auth()->user()->isSocialite()))
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.two-factor-authentication-form')
                </div>

                <x-section-border />
            @endif

            @if (!auth()->user()->isSocialite())
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.logout-other-browser-sessions-form')
                </div>
            @endif

            @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                <x-section-border />

                <div class="mt-10 sm:mt-0">
                    @livewire('profile.delete-user-form')
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
