<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body x-data="{ collapsed: false }"
    class="min-h-screen flex bg-white dark:bg-zinc-800">
    <!-- Sidebar -->
    <aside
        :class="collapsed ? 'w-20' : 'w-64'"
        class="transition-all duration-300 border-r border-zinc-200 bg-[#1443e0] dark:border-zinc-700 dark:bg-[#1443e0] flex flex-col fixed h-full">
        <!-- Branding & Toggles -->
        <div class="flex items-center justify-between p-4">
            <a href="{{ route('dashboard') }}" wire:navigate>
                <div class="flex items-center h-10 w-full" x-data="{ collapsed: false }">
                    <!-- Full Logo (only visible when sidebar is expanded) -->
                    <template x-if="!collapsed">
                        <img
                            src="{{ asset('images/snapp-logo-white.PNG') }}"
                            alt="SnaPp Logo"
                            class="h-10 w-auto object-contain transition-all duration-300 ease-in-out"
                            x-cloak />
                    </template>

                    <!-- Abbreviation (SN, only visible when sidebar is collapsed) -->
                    <template x-if="collapsed">
                        <span
                            class="text-white text-xl font-semibold transition-all duration-300 ease-in-out"
                            x-cloak>
                            SN
                        </span>
                    </template>
                </div>
            </a>

            <!-- Collapse/Expand Button -->
            <button @click="collapsed = !collapsed" class="text-white focus:outline-none">
                <template x-if="!collapsed">
                    <flux:icon name="chevron-left" class="w-6 h-6" />
                </template>
                <template x-if="collapsed">
                    <flux:icon name="chevron-right" class="w-6 h-6" />
                </template>
            </button>
        </div>

        <!-- Scrollable Navigation Container -->
        <div class="flex-1 overflow-y-auto">
            <!-- Main Navigation -->
            <nav class="px-2 space-y-1">
                <a
                    href="{{ route('dashboard') }}"
                    class="group flex items-center p-2 rounded-md hover:bg-blue-500 transition-colors"
                    :class="{'justify-center': collapsed, 'space-x-2': !collapsed}">
                    <flux:icon name="home" class="w-6 h-6 text-white" />
                    <span x-show="!collapsed" class="text-white">{{ __('Dashboard') }}</span>
                </a>
                @can('can view bills')
                <a
                    href="{{ route('bills.show') }}"
                    class="group flex items-center p-2 rounded-md hover:bg-blue-500 transition-colors"
                    :class="{'justify-center': collapsed, 'space-x-2': !collapsed}">
                    <flux:icon name="wallet" class="w-6 h-6 text-white" />
                    <span x-show="!collapsed" class="text-white">{{ __('My Bills') }}</span>
                </a>
                @endcan
                @can('can view contracts')
                <a
                    href="{{ route('my-contracts') }}"
                    class="group flex items-center p-2 rounded-md hover:bg-blue-500 transition-colors"
                    :class="{'justify-center': collapsed, 'space-x-2': !collapsed}">
                    <flux:icon name="clipboard-list" class="w-6 h-6 text-white" />
                    <span x-show="!collapsed" class="text-white">{{ __('My Contracts') }}</span>
                </a>
                @endcan
                @can('can view econ')
                <a
                    href="{{ route('energy-consumption') }}"
                    class="group flex items-center p-2 rounded-md hover:bg-blue-500 transition-colors"
                    :class="{'justify-center': collapsed, 'space-x-2': !collapsed}">
                    <flux:icon name="database-zap" class="w-6 h-6 text-white" />
                    <span x-show="!collapsed" class="text-white">{{ __('Energy Consumption Report') }}</span>
                </a>
                @endcan
                @can('can view advisories')
                <a
                    href="{{ route('advisories.index') }}"
                    class="group flex items-center p-2 rounded-md hover:bg-blue-500 transition-colors"
                    :class="{'justify-center': collapsed, 'space-x-2': !collapsed}">
                    <flux:icon name="megaphone" class="w-6 h-6 text-white" />
                    <span x-show="!collapsed" class="text-white">{{ __('Advisories') }}</span>
                </a>
                @endcan

                @can('can view profile')
                <a
                    href="{{ route('profiles.index') }}"
                    class="group flex items-center p-2 rounded-md hover:bg-blue-500 transition-colors"
                    :class="{'justify-center': collapsed, 'space-x-2': !collapsed}">
                    <flux:icon name="id-card" class="w-6 h-6 text-white" />
                    <span x-show="!collapsed" class="text-white">{{ __('Profile') }}</span>
                </a>
                @endcan
            </nav>

            @role('admin')
            <!-- Admin Section -->
            <div class="pt-1 px-2">
                <nav class="mt-2 space-y-1">
                    <!-- New Dropdown Menu -->
                    <flux:dropdown position="right-start" class="w-full">
                        <button class="group flex items-center w-full p-2 rounded-md hover:bg-blue-500 transition-colors"
                            :class="{'justify-center': collapsed, 'space-x-2': !collapsed}">
                            <flux:icon name="squares-plus" class="w-6 h-6 text-white" />
                            <span x-show="!collapsed" class="text-white">{{ __('Accounts') }}</span>
                        </button>

                        <flux:menu class="w-48">
                            <flux:menu.item
                                href="{{ route('all-user-list') }}"
                                icon="user-group">{{ __('All User List') }}
                            </flux:menu.item>
                            <flux:menu.item
                                href="{{ route('users.index') }}"
                                icon="user-group">{{ __('Customer List') }}
                            </flux:menu.item>
                            <flux:menu.item
                                href="{{ route('admin-list') }}"
                                icon="user-group">{{ __('Admin List') }}
                            </flux:menu.item>
                            <flux:menu.item
                                href="{{ route('account-executive-list') }}"
                                icon="briefcase">{{ __('Account Executives') }}
                            </flux:menu.item>
                        </flux:menu>
                    </flux:dropdown>

                    <a
                        href="{{ route('role.permission.list') }}"
                        class="group flex items-center p-2 rounded-md hover:bg-blue-500 transition-colors"
                        :class="{'justify-center': collapsed, 'space-x-2': !collapsed}">
                        <flux:icon name="shield-user" class="w-6 h-6 text-white" />
                        <span x-show="!collapsed" class="text-white">{{ __('Roles & Permissions') }}</span>
                    </a>
                    <a
                        href="{{ route('advisories.list') }}"
                        class="group flex items-center p-2 rounded-md hover:bg-blue-500 transition-colors"
                        :class="{'justify-center': collapsed, 'space-x-2': !collapsed}">
                        <flux:icon name="megaphone" class="w-6 h-6 text-white" />
                        <span x-show="!collapsed" class="text-white">{{ __('Manage Advisories') }}</span>
                    </a>

                    <!-- <a
                        href="{{ route('dashboard') }}"
                        class="group flex items-center p-2 rounded-md hover:bg-blue-500 transition-colors"
                        :class="{'justify-center': collapsed, 'space-x-2': !collapsed}">
                        <flux:icon name="circle-help" class="w-6 h-6 text-white" />
                        <span x-show="!collapsed" class="text-white">{{ __('Helpdesk') }}</span>
                    </a> -->
                </nav>
            </div>
            @endrole
        </div>

        <!-- Fixed User Menu at Bottom -->
        <div class="px-2 py-4 border-t border-blue-600">
            <flux:dropdown position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    class="!text-white" />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <!-- profile info -->
                    </flux:menu.radio.group>
                    <flux:menu.separator />
                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>
                            {{ __('Settings') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>
                    <flux:menu.separator />
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </div>
    </aside>

    <!-- Global Manual Loader Overlay -->
    <div
        id="global-loader"
        class="hidden fixed inset-0 bg-white/75 z-[9999] flex items-center justify-center">
        <button type="button" class="bg-indigo-600 text-white px-4 py-2 rounded inline-flex items-center" disabled>
            <svg class="animate-spin h-5 w-5 mr-3 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
            </svg>
            Loading Customer Data…
        </button>
    </div>

    <!-- Main content -->
    <div class="flex-1 ml-20" :class="collapsed ? 'ml-20' : 'ml-64'">
        {{ $slot }}
    </div>

    <!-- Manual loader JS -->
    <script>
        const loader = document.getElementById('global-loader');

        document.addEventListener('click', e => {
            const a = e.target.closest('a');
            if (a && a.href && !a.href.startsWith('#') && !a.target) {
                loader.classList.remove('hidden');
            }
        });

        document.addEventListener('submit', () => {
            loader.classList.remove('hidden');
        });

        window.addEventListener('pageshow', () => {
            loader.classList.add('hidden');
        });
    </script>


    @livewireScripts
    @stack('scripts')
    @fluxScripts
</body>

</html>