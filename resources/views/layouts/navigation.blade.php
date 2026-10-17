<nav x-data="{ open: false }" @keydown.escape.window="open = false" class="site-navigation sticky top-0 z-40 border-b border-slate-200 bg-white/95 backdrop-blur">
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
<div class="flex h-16 items-center justify-between">
    <div class="flex items-center gap-8">
        @auth
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5" aria-label="Online Services dashboard">
        @else
            <a href="{{ route('home') }}" class="flex items-center gap-2.5" aria-label="Online Services home">
        @endauth
            <span class="site-brand-mark flex h-9 w-9 items-center justify-center rounded-xl text-sm font-black">OS</span>
            <span class="font-extrabold tracking-tight text-slate-900">Online Services</span>
        </a>

        <button type="button"
            @click="open = !open"
            class="nav-menu-toggle inline-flex h-10 items-center gap-2 rounded-xl border border-slate-600 bg-slate-800 px-3 text-sm font-bold text-white shadow-sm transition hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-neutral-500 focus:ring-offset-2"
            aria-controls="global-navigation-menu"
            :aria-expanded="open.toString()"
            :aria-label="open ? 'Close navigation menu' : 'Open navigation menu'"
        >
            <svg x-show="!open" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
            <svg x-cloak x-show="open" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 6l12 12M18 6L6 18"/>
            </svg>
            <span x-text="open ? 'Close' : 'Menu'">Menu</span>
        </button>


    </div>

    <div class="flex items-center gap-1 rounded-xl border border-slate-200 bg-slate-50 p-1" role="group" aria-label="Language">
    <a href="{{ route('language.switch', 'en') }}" class="rounded-lg px-2.5 py-1.5 text-xs font-extrabold {{ app()->getLocale() === 'en' ? 'bg-neutral-200 text-neutral-900 shadow-sm' : 'text-neutral-600 hover:text-black dark:text-neutral-300 dark:hover:text-white' }}">EN</a>
    <a href="{{ route('language.switch', 'sw') }}" class="rounded-lg px-2.5 py-1.5 text-xs font-extrabold {{ app()->getLocale() === 'sw' ? 'bg-white text-blue-700 shadow-sm' : 'text-slate-500 hover:text-slate-800' }}">SW</a>
</div>

    @auth
        @if(Auth::user()->isCustomer())
            @php
                $navUnreadNotifications = Auth::user()->unreadNotifications()->count();
                $navNotifications = Auth::user()->notifications()->latest()->limit(6)->get();
            @endphp
        @endif
        <div class="hidden items-center gap-3 md:flex">
            @if(Auth::user()->isCustomer())
                <div x-data="{ notificationsOpen: false }" class="relative">
                    <button
                        type="button"
                        @click="notificationsOpen = !notificationsOpen"
                        @click.outside="notificationsOpen = false"
                        class="relative flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-sm transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                        aria-label="{{ __('Notifications') }}"
                        :aria-expanded="notificationsOpen.toString()"
                    >
                        <span class="text-lg leading-none">🔔</span>
                        @if($navUnreadNotifications > 0)
                            <span class="absolute -right-1 -top-1 flex min-h-5 min-w-5 items-center justify-center rounded-full border-2 border-white bg-red-600 px-1 text-[10px] font-black leading-none text-white">
                                {{ $navUnreadNotifications > 99 ? '99+' : $navUnreadNotifications }}
                            </span>
                        @endif
                    </button>

                    <div
                        x-cloak
                        x-show="notificationsOpen"
                        x-transition.origin.top.right
                        class="absolute right-0 z-50 mt-3 w-[min(92vw,380px)] overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl"
                    >
                        <div class="flex items-center justify-between border-b border-slate-200 px-4 py-3">
                            <div>
                                <p class="text-sm font-black text-slate-900">{{ __('Notifications') }}</p>
                                <p class="text-xs font-semibold text-slate-500">{{ $navUnreadNotifications }} {{ __('unread') }}</p>
                            </div>
                            @if($navUnreadNotifications > 0)
                                <form method="POST" action="{{ route('notifications.read-all') }}">
                                    @csrf
                                    <button type="submit" class="text-xs font-black text-blue-700 hover:text-blue-900">{{ __('Mark all read') }}</button>
                                </form>
                            @endif
                        </div>

                        <div class="max-h-[min(65vh,420px)] overflow-y-auto">
                            @forelse($navNotifications as $notification)
                                @php
                                    $status = data_get($notification->data, 'status');
                                    $icon = match($status) {
                                        'approved' => '✓',
                                        'rejected' => '!',
                                        'processing' => '↻',
                                        default => '•',
                                    };
                                    $iconClass = match($status) {
                                        'approved' => 'bg-emerald-100 text-emerald-700',
                                        'rejected' => 'bg-red-100 text-red-700',
                                        'processing' => 'bg-blue-100 text-blue-700',
                                        default => 'bg-slate-100 text-slate-700',
                                    };
                                @endphp
                                <a href="{{ route('notifications.open', $notification->id) }}" class="block border-b border-slate-100 px-4 py-3 transition hover:bg-slate-50 {{ is_null($notification->read_at) ? 'bg-blue-50/60' : '' }}">
                                    <div class="flex gap-3">
                                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl {{ $iconClass }} font-black">{{ $icon }}</span>
                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-start justify-between gap-2">
                                                <p class="text-sm font-black text-slate-900">{{ data_get($notification->data, 'title', __('Notification')) }}</p>
                                                @if(is_null($notification->read_at))
                                                    <span class="mt-1 h-2 w-2 shrink-0 rounded-full bg-blue-600"></span>
                                                @endif
                                            </div>
                                            <p class="mt-1 line-clamp-2 text-xs leading-5 text-slate-600">{{ data_get($notification->data, 'message', '') }}</p>
                                            <p class="mt-1 text-[10px] font-bold text-slate-400">{{ $notification->created_at->diffForHumans() }} · {{ __('Open application') }} →</p>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="px-5 py-10 text-center">
                                    <div class="text-3xl">🔔</div>
                                    <p class="mt-2 text-sm font-black text-slate-900">{{ __('No notifications yet') }}</p>
                                    <p class="mt-1 text-xs leading-5 text-slate-500">{{ __('Application updates and admin remarks will appear here.') }}</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endif
            <span class="max-w-48 truncate text-sm font-semibold text-slate-500">{{ Auth::user()->name }}</span>
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button type="button" class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-sm font-extrabold text-slate-700 hover:bg-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" aria-label="Open account menu">
                        {{ strtoupper(substr(Auth::user()->name,0,1)) }}
                    </button>
                </x-slot>
                <x-slot name="content">
                    <x-dropdown-link :href="route('profile.edit')">{{ __('Profile') }}</x-dropdown-link>
                    @if(Auth::user()->isAdmin())
                        <x-dropdown-link :href="route('admin.dashboard')">{{ __('Admin Panel') }}</x-dropdown-link>
                        <x-dropdown-link :href="route('admin.help-desk.index')">{{ __('Help Desk Contacts') }}</x-dropdown-link>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-gray-100 focus:bg-gray-100 focus:outline-none">
                            {{ __('Log Out') }}
                        </button>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>
    @else
        <div class="hidden items-center gap-2 md:flex">
            <a href="{{ route('login') }}" class="rounded-xl px-4 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">{{ __('Sign in') }}</a>
            <a href="{{ route('register') }}" class="rounded-xl bg-blue-700 px-4 py-2.5 text-sm font-bold text-white hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">{{ __('Get started') }}</a>
        </div>
    @endauth


</div>
</div>

<div id="global-navigation-menu"
    x-cloak
    x-show="open"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 -translate-y-2"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 -translate-y-2"
    @click.outside="open = false"
    class="global-navigation-menu absolute left-0 right-0 top-full z-50 border-t border-slate-700 shadow-2xl"
>
    <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
        <div class="mb-3 flex items-center justify-between gap-3 border-b border-slate-700 pb-3">
            <p class="text-xs font-extrabold uppercase tracking-[.18em] text-slate-400">{{ __('Navigation menu') }}</p>
            <div class="flex items-center gap-1 rounded-xl border border-slate-600 bg-slate-800 p-1" role="group" aria-label="Language">
                <a href="{{ route('language.switch', 'en') }}" class="rounded-lg px-3 py-1.5 text-xs font-extrabold transition {{ app()->getLocale() === 'en' ? 'bg-emerald-500 text-slate-950 shadow-sm' : 'text-slate-200 hover:bg-slate-700' }}">EN</a>
                <a href="{{ route('language.switch', 'sw') }}" class="rounded-lg px-3 py-1.5 text-xs font-extrabold transition {{ app()->getLocale() === 'sw' ? 'bg-emerald-500 text-slate-950 shadow-sm' : 'text-slate-200 hover:bg-slate-700' }}">SW</a>
            </div>
        </div>
        <div class="grid grid-cols-1 gap-1 sm:grid-cols-2 lg:grid-cols-3">
            @auth
                <a href="{{ route('dashboard') }}" @click="open = false" @class(['global-nav-link', 'global-nav-link-active' => request()->routeIs('dashboard')]) aria-current="{{ request()->routeIs('dashboard') ? 'page' : 'false' }}">
                    <span class="global-nav-link-icon">⌂</span>{{ __('Dashboard') }}
                </a>
                <a href="{{ route('services.index') }}" @click="open = false" @class(['global-nav-link', 'global-nav-link-active' => request()->routeIs('services.*')]) aria-current="{{ request()->routeIs('services.*') ? 'page' : 'false' }}">
                    <span class="global-nav-link-icon">▦</span>{{ __('Services') }}
                </a>
                <a href="{{ route('customer.applications.index') }}" @click="open = false" @class(['global-nav-link', 'global-nav-link-active' => request()->routeIs('customer.applications.*')]) aria-current="{{ request()->routeIs('customer.applications.*') ? 'page' : 'false' }}">
                    <span class="global-nav-link-icon">▤</span>{{ __('My Applications') }}
                </a>
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" @click="open = false" @class(['global-nav-link', 'global-nav-link-active' => request()->routeIs('admin.dashboard')]) aria-current="{{ request()->routeIs('admin.dashboard') ? 'page' : 'false' }}">
                        <span class="global-nav-link-icon">⌘</span>{{ __('Admin') }}
                    </a>
                    <a href="{{ route('admin.services.index') }}" @click="open = false" @class(['global-nav-link', 'global-nav-link-active' => request()->routeIs('admin.services.*')]) aria-current="{{ request()->routeIs('admin.services.*') ? 'page' : 'false' }}">
                        <span class="global-nav-link-icon">＋</span>{{ __('Manage Services') }}
                        <span class="global-nav-active-dot" aria-hidden="true"></span>
                    </a>
                    <a href="{{ route('admin.help-desk.index') }}" @click="open = false" @class(['global-nav-link', 'global-nav-link-active' => request()->routeIs('admin.help-desk.*')]) aria-current="{{ request()->routeIs('admin.help-desk.*') ? 'page' : 'false' }}">
                        <span class="global-nav-link-icon">?</span>{{ __('Help Desk') }}
                    </a>
                @endif
                <a href="{{ route('profile.edit') }}" @click="open = false" @class(['global-nav-link', 'global-nav-link-active' => request()->routeIs('profile.edit')])>
                    <span class="global-nav-link-icon">◉</span>{{ __('Profile') }}
                </a>
                <form method="POST" action="{{ route('logout') }}" class="contents">
                    @csrf
                    <button type="submit" class="global-nav-link w-full text-left">
                        <span class="global-nav-link-icon">↪</span>{{ __('Log Out') }}
                    </button>
                </form>
            @else
                <a href="{{ route('home') }}" @click="open = false" @class(['global-nav-link', 'global-nav-link-active' => request()->routeIs('home')])>{{ __('Home') }}</a>
                <a href="{{ route('services.index') }}" @click="open = false" @class(['global-nav-link', 'global-nav-link-active' => request()->routeIs('services.*')])>{{ __('Services') }}</a>
                <a href="{{ route('login') }}" @click="open = false" class="global-nav-link">{{ __('Sign in') }}</a>
                <a href="{{ route('register') }}" @click="open = false" class="global-nav-link">{{ __('Get started') }}</a>
            @endauth
        </div>
    </div>
</div>
</nav>