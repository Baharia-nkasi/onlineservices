@php
    $user = Auth::user();
    $isAdmin = $user?->isAdmin();
    $isCustomer = $user?->isCustomer();

    $adminItems = [
        ['label' => __('Dashboard'), 'route' => 'admin.dashboard', 'active' => request()->routeIs('admin.dashboard'), 'icon' => 'grid'],
        ['label' => __('Client Matrix'), 'route' => 'admin.dashboard', 'active' => request()->routeIs('admin.applications.*'), 'icon' => 'users', 'anchor' => 'client-matrix'],
        ['label' => __('M-Pesa Logs'), 'route' => 'admin.dashboard', 'active' => false, 'icon' => 'activity', 'anchor' => 'activity-stream'],
        ['label' => __('Revenue'), 'route' => 'admin.dashboard', 'active' => false, 'icon' => 'chart', 'anchor' => 'metrics'],
        ['label' => __('Settings'), 'route' => 'profile.edit', 'active' => request()->routeIs('profile.*'), 'icon' => 'settings'],
        ['label' => __('User Management'), 'route' => 'admin.customers.index', 'active' => request()->routeIs('admin.customers.*'), 'icon' => 'users'],
        ['label' => __('Manage Services'), 'route' => 'admin.services.index', 'active' => request()->routeIs('admin.services.*'), 'icon' => 'server'],
        ['label' => __('Help Desk'), 'route' => 'admin.help-desk.index', 'active' => request()->routeIs('admin.help-desk.*'), 'icon' => 'headset'],
    ];

    $customerItems = [
        ['label' => __('My Packages'), 'route' => 'dashboard', 'active' => request()->routeIs('dashboard'), 'icon' => 'package'],
        ['label' => __('My Usage'), 'route' => 'customer.applications.index', 'active' => request()->routeIs('customer.applications.*'), 'icon' => 'activity'],
        ['label' => __('Buy Bundle'), 'route' => 'services.index', 'active' => request()->routeIs('services.*') || request()->routeIs('applications.*'), 'icon' => 'bolt'],
        ['label' => __('Support'), 'route' => 'dashboard', 'active' => false, 'icon' => 'headset'],
        ['label' => __('Profile'), 'route' => 'profile.edit', 'active' => request()->routeIs('profile.*'), 'icon' => 'user'],
    ];
@endphp

<nav
    x-data="{ open: false, sidebarCollapsed: localStorage.getItem('sidebar-collapsed') === '1' }"
    x-init="$watch('sidebarCollapsed', value => localStorage.setItem('sidebar-collapsed', value ? '1' : '0'))"
    class="site-navigation"
>
    <div class="neon-nav-mobile-top md:hidden">
        <button type="button" class="neon-nav-icon-button neon-waka" @click="open = !open" :aria-expanded="open.toString()" aria-controls="mobile-nav-panel" aria-label="{{ __('Toggle navigation') }}">
            <svg x-show="!open" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            <svg x-show="open" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <a href="{{ $isAdmin ? route('admin.dashboard') : route('dashboard') }}" class="neon-nav-brand">
            <span class="neon-nav-logo">OS</span>
            <span>ONLINE<span class="text-[#39ff14]">SERVICES</span></span>
        </a>
        @auth
            <div class="flex items-center gap-2">
                <a href="{{ route('profile.edit') }}" class="neon-nav-avatar" aria-label="{{ __('Profile') }}">{{ strtoupper(substr($user->name, 0, 1)) }}</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="neon-logout" aria-label="{{ __('Log Out') }}">↪</button>
                </form>
            </div>
        @endauth
    </div>

    @auth
    <aside class="neon-sidebar" :class="sidebarCollapsed ? 'neon-sidebar-collapsed' : ''">
        <div class="neon-sidebar-top">
            <a href="{{ $isAdmin ? route('admin.dashboard') : route('dashboard') }}" class="neon-nav-brand">
                <span class="neon-nav-logo">OS</span>
                <span class="neon-brand-text">ONLINE<span class="text-[#39ff14]">SERVICES</span></span>
            </a>
            <button type="button" @click="sidebarCollapsed = !sidebarCollapsed" class="neon-nav-icon-button neon-collapse-button" :aria-label="sidebarCollapsed ? '{{ __('Expand sidebar') }}' : '{{ __('Collapse sidebar') }}'">
                <svg class="h-5 w-5 transition-transform duration-300" :class="sidebarCollapsed ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
        </div>

        <div class="neon-nav-profile">
            <a href="{{ route('profile.edit') }}" class="neon-nav-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</a>
            <div class="min-w-0 neon-brand-text">
                <p class="truncate text-sm font-black text-white">{{ $user->name }}</p>
                <p class="truncate text-[10px] font-bold uppercase tracking-widest text-[#6f897b]">{{ $isAdmin ? __('Administrator') : __('Customer') }}</p>
            </div>
        </div>

        <div class="neon-nav-scroll">
            <p class="neon-nav-section">{{ $isAdmin ? __('Admin Infrastructure') : __('Customer Workspace') }}</p>
            <div class="space-y-1.5">
                @foreach(($isAdmin ? $adminItems : $customerItems) as $item)
                    <a
                        href="{{ route($item['route']) }}{{ isset($item['anchor']) ? '#'.$item['anchor'] : '' }}"
                        class="neon-nav-item {{ $item['active'] ? 'is-active' : '' }}"
                        :title="sidebarCollapsed ? '{{ $item['label'] }}' : null"
                    >
                        <span class="neon-nav-item-icon">
                            @include('layouts.partials.nav-icon', ['name' => $item['icon']])
                        </span>
                        <span class="neon-brand-text flex-1 truncate">{{ $item['label'] }}</span>
                        @if($item['active'])
                            <span class="neon-nav-active-dot"></span>
                        @endif
                    </a>
                @endforeach
            </div>

            @if($isCustomer)
                <a href="{{ route('services.index') }}" class="neon-buy-bundle neon-waka">
                    <span class="neon-nav-item-icon">@include('layouts.partials.nav-icon', ['name' => 'bolt'])</span>
                    <span class="neon-brand-text">{{ __('Buy Bundle') }}</span>
                </a>
            @endif
        </div>

        <div class="neon-sidebar-bottom">
            <div class="flex items-center gap-1 rounded-xl border border-[#00ff66]/20 bg-[#07100b]/70 p-1" role="group" aria-label="Language">
                <a href="{{ route('language.switch', 'en') }}" class="neon-lang {{ app()->getLocale() === 'en' ? 'is-active' : '' }}">EN</a>
                <a href="{{ route('language.switch', 'sw') }}" class="neon-lang {{ app()->getLocale() === 'sw' ? 'is-active' : '' }}">SW</a>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button type="submit" class="neon-logout neon-waka w-full">
                    <span>↪</span><span class="neon-brand-text">{{ __('Log Out') }}</span>
                </button>
            </form>
        </div>
    </aside>

    <header class="neon-topbar hidden md:flex">
        <div class="flex items-center gap-3">
            <span class="neon-live-dot"></span>
            <span class="text-xs font-black uppercase tracking-[.18em] text-[#8fa79a]">{{ $isAdmin ? __('Admin Control') : __('Customer Portal') }}</span>
        </div>
        <div class="flex items-center gap-3">
            @if($isCustomer)
                @php
                    $navUnreadNotifications = $user->unreadNotifications()->count();
                    $navNotifications = $user->notifications()->latest()->limit(6)->get();
                @endphp
                <div x-data="{ notificationsOpen: false }" class="relative">
                    <button type="button" @click="notificationsOpen=!notificationsOpen" @click.outside="notificationsOpen=false" class="neon-nav-icon-button relative" aria-label="{{ __('Notifications') }}">
                        <span>⌁</span>
                        @if($navUnreadNotifications > 0)<span class="absolute -right-1 -top-1 flex h-4 min-w-4 items-center justify-center rounded-full bg-[#39ff14] px-1 text-[9px] font-black text-[#031107]">{{ $navUnreadNotifications > 99 ? '99+' : $navUnreadNotifications }}</span>@endif
                    </button>
                    <div x-cloak x-show="notificationsOpen" x-transition class="neon-notification-panel">
                        <div class="border-b border-[#163124] px-4 py-3">
                            <p class="text-sm font-black text-white">{{ __('Notifications') }}</p>
                            <p class="text-xs text-[#71867b]">{{ $navUnreadNotifications }} {{ __('unread') }}</p>
                        </div>
                        <div class="max-h-[380px] overflow-y-auto">
                            @forelse($navNotifications as $notification)
                                <a href="{{ route('notifications.open', $notification->id) }}" class="block border-b border-[#102219] px-4 py-3 hover:bg-[#00ff66]/5">
                                    <p class="text-sm font-bold text-white">{{ data_get($notification->data, 'title', __('Notification')) }}</p>
                                    <p class="mt-1 text-xs leading-5 text-[#71867b]">{{ data_get($notification->data, 'message', '') }}</p>
                                </a>
                            @empty
                                <p class="p-6 text-center text-xs text-[#71867b]">{{ __('No notifications yet') }}</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endif
            <div class="hidden text-right lg:block">
                <p class="text-xs font-black text-white">{{ $user->name }}</p>
                <p class="text-[10px] uppercase tracking-widest text-[#6f897b]">{{ $isAdmin ? __('Administrator') : __('Customer') }}</p>
            </div>
            <a href="{{ route('profile.edit') }}" class="neon-nav-avatar" aria-label="{{ __('Profile') }}">{{ strtoupper(substr($user->name,0,1)) }}</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="neon-logout neon-waka">{{ __('Log Out') }}</button>
            </form>
        </div>
    </header>

    <div id="mobile-nav-panel" x-cloak x-show="open" x-transition class="neon-mobile-panel md:hidden">
        <div class="space-y-1.5">
            @foreach(($isAdmin ? $adminItems : $customerItems) as $item)
                <a href="{{ route($item['route']) }}{{ isset($item['anchor']) ? '#'.$item['anchor'] : '' }}" @click="open=false" class="neon-nav-item {{ $item['active'] ? 'is-active' : '' }}">
                    <span class="neon-nav-item-icon">@include('layouts.partials.nav-icon', ['name' => $item['icon']])</span>
                    <span class="flex-1">{{ $item['label'] }}</span>
                    @if($item['active'])<span class="neon-nav-active-dot"></span>@endif
                </a>
            @endforeach
        </div>
    </div>

    @if($isCustomer)
        <div class="neon-mobile-bottom-nav md:hidden">
            @foreach(array_slice($customerItems, 0, 4) as $item)
                <a href="{{ route($item['route']) }}{{ isset($item['anchor']) ? '#'.$item['anchor'] : '' }}" class="neon-mobile-nav-item {{ $item['active'] ? 'is-active' : '' }}">
                    <span class="neon-mobile-nav-icon">@include('layouts.partials.nav-icon', ['name' => $item['icon']])</span>
                    <span>{{ $item['label'] }}</span>
                </a>
            @endforeach
        </div>
    @endif
    @endauth
</nav>