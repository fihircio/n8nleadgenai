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

            <x-section-border />
            <div class="mt-10 sm:mt-0">
                <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Coin Balance') }}</h3>
                    <div class="flex items-center space-x-4">
                        <span class="text-2xl font-bold text-green-600 dark:text-green-400" id="coin-balance">{{ auth()->user()->getCoinBalance() }}</span>
                        <button id="refresh-coin-balance" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">{{ __('Refresh') }}</button>
                    </div>
                </div>
            </div>

            @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                <x-section-border />

                <div class="mt-10 sm:mt-0">
                    @livewire('profile.delete-user-form')
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
