@props([
    'subHeaderClass' => theme('header', 'subHeaderClass'),
    'profileBgColor' => theme('header', 'profileBgColor'),
])

<x-app-layout>
    <x-slot name="header">
        <h2 class="{{ $subHeaderClass }}">
            {{ __('Create Team') }}
        </h2>
    </x-slot>

    <div>
        <div class="{{ $profileBgColor }} max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            @livewire('teams.create-team-form')
        </div>
    </div>
</x-app-layout>
