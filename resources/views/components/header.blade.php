@props([
    'bgColorHeader' => theme('header', 'bgColor'),
    'menuLinkClass' => theme('header', 'menuLinkClass'),
    'profileButtonClass' => theme('global', 'profileButtonClass'),
    'profileTeamButtonClass' => theme('global', 'profileTeamButtonClass'),
])

<header class="{{ $bgColorHeader }}">
    <nav class="container mx-auto px-8 py-3">
        <div x-data="{ isOpen: false }">
        <div class="flex justify-between items-center">
            <div class="custom-logo text-lime-600">
                <x-authentication-card-logo />
            </div>
            <!-- Settings Dropdown -->
            <div class="hidden md:flex space-x-4 !ml-auto">
                <!-- Teams Dropdown -->
                {{-- @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="ms-3 relative">
                        <x-dropdown align="right" width="60">
                            <x-slot name="trigger">
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="{{ $profileTeamButtonClass }} inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md transition ease-in-out duration-150">
                                        {{ auth()->user()->currentTeam->name }}

                                        <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                    </button>
                                </span>
                            </x-slot>

                            <x-slot name="content">
                                <div class="w-60">
                                    <!-- Team Management -->
                                    <div class="block px-4 py-2 text-xs text-gray-400">
                                        {{ __('Manage Team') }}
                                    </div>

                                    <!-- Team Settings -->
                                    <x-dropdown-link href="{{ route('teams.show', auth()->user()->currentTeam->id) }}">
                                        {{ __('Team Settings') }}
                                    </x-dropdown-link>

                                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                        <x-dropdown-link href="{{ route('teams.create') }}">
                                            {{ __('Create New Team') }}
                                        </x-dropdown-link>
                                    @endcan

                                    <!-- Team Switcher -->
                                    @if (auth()->user()->allTeams()->count() > 1)
                                        <div class="border-t border-gray-200"></div>

                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            {{ __('Switch Teams') }}
                                        </div>

                                        @foreach (auth()->user()->allTeams() as $team)
                                            <x-switchable-team :team="$team" />
                                        @endforeach
                                    @endif
                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @endif --}}
                @if (Route::has('login'))
                    @auth
                        @can('access admin panel')
                            <a href="{{ url('/admin') }}" class="{{ $menuLinkClass }}">{{ __('Dashboard') }}</a>
                        @else
                            <a {{ when(config('saashovel.SPA_UX'), 'wire:navigate') }} href="{{ route('dashboard') }}" class="{{ $menuLinkClass }}">{{ __('Dashboard') }}</a>
                        @endcan
                     <!--   <a {{ when(config('saashovel.SPA_UX'), 'wire:navigate') }} href="{{ route('leads.ai-lead-scoring') }}" class="{{ $menuLinkClass }}">{{ __('AI Lead Scoring') }}</a>
                        <a {{ when(config('saashovel.SPA_UX'), 'wire:navigate') }} href="{{ route('marketplace') }}" class="{{ $menuLinkClass }}">{{ __('Workflow Marketplace') }}</a>-->
                    @else
                        <a href="{{ route('login') }}" class="{{ $menuLinkClass }}">{{ __('Log in') }}</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="{{ $menuLinkClass }}">{{ __('Register') }}</a>
                        @endif
                    @endauth
                @endif
                <!-- Blog menu code -->
                @php $menu = \LaraZeus\Sky\SkyPlugin::get()->getModel('Navigation')::fromHandle('main'); @endphp
                @if ($menu && $menu->items)
                    @foreach ($menu->items as $item)
                        {!! \App\CustomRenderNavItem::render($item, $menuLinkClass) !!}
                    @endforeach
                @endif
                <!-- /Blog menu code -->

                <!-- Site links -->
                {{-- <a href="page-url.test" class="{{ $menuLinkClass }}">Page name</a>
                <a href="page-url.test" class="{{ $menuLinkClass }}">Other page name</a> --}}
                <!-- /Site links -->
            </div>
            @if (auth()->user())
                <div class="hidden md:flex">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <button class="ml-3 flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                    <img class="h-8 w-8 rounded-full object-cover" src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" />
                                </button>
                            @else
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="{{ $profileButtonClass }} inline-flex items-center px-3 py-2 border border-transparent text-ssm leading-4 font-medium rounded-md transition ease-in-out duration-150">
                                        {{ auth()->user()->name }}

                                        <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                </span>
                            @endif
                        </x-slot>

                        <x-slot name="content">
                            <!-- Account Management -->
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Manage Account') }}
                            </div>

                            <x-dropdown-link href="{{ route('profile.show') }}">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                <x-dropdown-link href="{{ route('api-tokens.index') }}">
                                    {{ __('API Tokens') }}
                                </x-dropdown-link>
                            @endif

                            <div class="border-t border-gray-200"></div>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf

                                <x-dropdown-link href="{{ route('logout') }}"
                                        @click.prevent="$root.submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            @endif
            <div class="md:hidden">
                <button
                    @click="isOpen = !isOpen"
                    class="{{ $menuLinkClass }} focus:outline-none"
                >
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
        <div id="mobile-menu" x-show.important="isOpen" @click.away="isOpen = false" x-transition class="hidden md:hidden mt-4" :class="{ 'hidden': !isOpen }">
            @if (auth()->user())
                <div class="-ml-3{{-- hidden md:flex --}}">
                    <x-dropdown align="left" width="48">
                        <x-slot name="trigger">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <button class="ml-2 flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                    <img class="h-8 w-8 rounded-full object-cover" src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" />
                                </button>
                            @else
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="{{ $profileButtonClass }} inline-flex mx-2 items-center px-3 py-2 border border-transparent text-ssm leading-4 font-medium rounded-md transition ease-in-out duration-150">
                                        {{ auth()->user()->name }}

                                        <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                </span>
                            @endif
                        </x-slot>

                        <x-slot name="content">
                            <!-- Account Management -->
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Manage Account') }}
                            </div>

                            <x-dropdown-link href="{{ route('profile.show') }}">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                <x-dropdown-link href="{{ route('api-tokens.index') }}">
                                    {{ __('API Tokens') }}
                                </x-dropdown-link>
                            @endif

                            <div class="border-t border-gray-200"></div>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf

                                <x-dropdown-link href="{{ route('logout') }}"
                                        @click.prevent="$root.submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            @endif
            @if (Route::has('login'))
                @auth
                    @can('access admin panel')
                        <a href="{{ url('/admin') }}" class="block py-2 {{ $menuLinkClass }}">{{ __('Dashboard') }}</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="block py-2 {{ $menuLinkClass }}">{{ __('Dashboard') }}</a>
                    @endcan
                @else
                    <a href="{{ route('login') }}" class="block py-2 {{ $menuLinkClass }}">{{ __('Log in') }}</a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="block py-2 {{ $menuLinkClass }}">{{ __('Register') }}</a>
                    @endif
                @endauth
            @endif
            <!-- Blog menu code -->
            @php $menu = \LaraZeus\Sky\SkyPlugin::get()->getModel('Navigation')::fromHandle('main'); @endphp
            @if ($menu && $menu->items)
                @foreach ($menu->items as $item)
                    {!! \App\CustomRenderNavItem::render($item, 'block py-2 ' . $menuLinkClass) !!}
                @endforeach
            @endif
            <!-- /Blog menu code -->

            <!-- Site links -->
            {{-- <a href="page-url.test" class="{{ $menuLinkClass }}">Page name</a>
            <a href="page-url.test" class="{{ $menuLinkClass }}">Other page name</a> --}}
            <!-- /Site links -->
            @auth
                <a {{ when(config('saashovel.SPA_UX'), 'wire:navigate') }} href="{{ route('marketplace') }}" class="block py-2 {{ $menuLinkClass }}">
                    {{ __('Workflow Marketplace') }}
                </a>
            @endauth
        </div></div>
    </nav>
</header>
