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
        class="transition-all duration-300 border-r border-zinc-200 bg-[#1443e0] dark:border-zinc-700 dark:bg-[#1443e0] flex flex-col"
        >
       <!-- Branding & Toggles -->
<div class="flex items-center justify-between p-4">
<a href="{{ route('dashboard') }}" wire:navigate>
    <div class="flex items-center h-10 w-full" x-data="{ collapsed: false }">
        <!-- Full Logo (only visible when sidebar is expanded) -->
        <template x-if="!collapsed">
            <img 
                src="{{ asset('images/snap_logo_white.png') }}" 
                alt="SnaPp Logo" 
                class="h-10 w-auto object-contain transition-all duration-300 ease-in-out"
                x-cloak
            />
        </template>

        <!-- Abbreviation (SN, only visible when sidebar is collapsed) -->
        <template x-if="collapsed">
            <span 
                class="text-white text-xl font-semibold transition-all duration-300 ease-in-out"
                x-cloak
            >
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


        <!-- Navigation -->
        <nav class="flex-1 px-2 space-y-1">
            <a
                href="{{ route('dashboard') }}"
                class="group flex items-center p-2 rounded-md hover:bg-blue-500 transition-colors"
                :class="{'justify-center': collapsed, 'space-x-2': !collapsed}"
            >
                <flux:icon name="home" class="w-6 h-6 text-white" />
                <span x-show="!collapsed" class="text-white">{{ __('Dashboard') }}</span>
            </a>
            <a
                href="{{ route('bills.show') }}"
                class="group flex items-center p-2 rounded-md hover:bg-blue-500 transition-colors"
                :class="{'justify-center': collapsed, 'space-x-2': !collapsed}"
            >
                <flux:icon name="wallet" class="w-6 h-6 text-white" />
                <span x-show="!collapsed" class="text-white">{{ __('My Bills') }}</span>
            </a>
            <a
                href="{{ route('my-contracts') }}"
                class="group flex items-center p-2 rounded-md hover:bg-blue-500 transition-colors"
                :class="{'justify-center': collapsed, 'space-x-2': !collapsed}"
            >
                <flux:icon name="clipboard-list" class="w-6 h-6 text-white" />
                <span x-show="!collapsed" class="text-white">{{ __('My Contracts') }}</span>
            </a>
            <a
                href="{{ route('energy-consumption') }}"
                class="group flex items-center p-2 rounded-md hover:bg-blue-500 transition-colors"
                :class="{'justify-center': collapsed, 'space-x-2': !collapsed}"
            >
            <flux:icon name="database-zap" class="w-6 h-6 text-white" />
            <span x-show="!collapsed" class="text-white">{{ __('Energy Consumption Report') }}</span>
            </a>
            <a
                href="{{ route('advisories') }}"
                class="group flex items-center p-2 rounded-md hover:bg-blue-500 transition-colors"
                :class="{'justify-center': collapsed, 'space-x-2': !collapsed}"
            >
                <flux:icon name="megaphone" class="w-6 h-6 text-white" />
                <span x-show="!collapsed" class="text-white">{{ __('Advisories') }}</span>
            </a>

            <a
                href="{{ route('profiles.index') }}"
                class="group flex items-center p-2 rounded-md hover:bg-blue-500 transition-colors"
                :class="{'justify-center': collapsed, 'space-x-2': !collapsed}"
            >
                <flux:icon name="id-card" class="w-6 h-6 text-white" />
                <span x-show="!collapsed" class="text-white">{{ __('Profile') }}</span>
            </a>
            
        </nav>

        <!-- Admin Section -->
        <div class="pt-4 px-2">
            <h3 
                class="px-2 text-xs font-semibold text-white uppercase">{{ __('Admin') }}</h3>
            <nav class="mt-2 space-y-1">
                <a
                    href="{{ route('users.index') }}"
                    class="group flex items-center p-2 rounded-md hover:bg-blue-500 transition-colors"
                    :class="{'justify-center': collapsed, 'space-x-2': !collapsed}"
                >
                    <flux:icon name="users" class="w-6 h-6 text-white" />
                    <span x-show="!collapsed" class="text-white">{{ __('Customer Accounts') }}</span>
                </a>

                <a
                    href="{{ route('role.permission.list') }}"
                    class="group flex items-center p-2 rounded-md hover:bg-blue-500 transition-colors"
                    :class="{'justify-center': collapsed, 'space-x-2': !collapsed}"
                >
                    <flux:icon name="shield-user" class="w-6 h-6 text-white" />
                    <span x-show="!collapsed" class="text-white">{{ __('Roles & Permissions') }}</span>
                </a>

                <a
                    href="{{ route('dashboard') }}"
                    class="group flex items-center p-2 rounded-md hover:bg-blue-500 transition-colors"
                    :class="{'justify-center': collapsed, 'space-x-2': !collapsed}"
                >
                    <flux:icon name="circle-help" class="w-6 h-6 text-white" />
                    <span x-show="!collapsed" class="text-white">{{ __('Helpdesk') }}</span>
                </a>
            </nav>
        </div>

        <!-- Spacer -->
        <div class="px-2 py-4"></div>

        <!-- User Menu -->
        <div class="px-2 py-4">
            <flux:dropdown position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    class="text-white"
                />
                <flux:menu class="w-[220px]">
                    <!-- Menu contents unchanged -->
                    <flux:menu.radio.group>
                        <!-- profile info -->
                    </flux:menu.radio.group>
                    <flux:menu.separator />
                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
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

    <!-- Main content -->
    <div class="flex-1 overflow-auto">
        {{ $slot }}
    </div>

    @livewireScripts
    @stack('scripts')
    @fluxScripts
</body>
</html>