<nav x-data="{ open: false }" class="site-navigation sticky top-0 z-40 border-b border-slate-200 bg-white/95 backdrop-blur">
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
<div class="flex h-16 items-center justify-between">
    <div class="flex items-center gap-8">
        @auth
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5" aria-label="Online Services dashboard">
        @else
            <a href="{{ route('home') }}" class="flex items-center gap-2.5" aria-label="Online Services home">
        @endauth
            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-700 text-sm font-black text-white">OS</span>
            <span class="font-extrabold tracking-tight text-slate-900">Online Services</span>
        </a>

        @auth
            <div class="hidden items-center gap-6 md:flex">
                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">{{ __('Dashboard') }}</x-nav-link>
                <x-nav-link :href="route('services.index')" :active="request()->routeIs('services.*')">{{ __('Services') }}</x-nav-link>
                <x-nav-link :href="route('customer.applications.index')" :active="request()->routeIs('customer.applications.*')">{{ __('My Applications') }}</x-nav-link>
                @if(Auth::user()->isAdmin())
                    <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">{{ __('Admin') }}</x-nav-link>
                    <x-nav-link :href="route('admin.services.index')" :active="request()->routeIs('admin.services.*')">{{ __('Manage Services') }}</x-nav-link>
                    <x-nav-link :href="route('admin.help-desk.index')" :active="request()->routeIs('admin.help-desk.*')">{{ __('Help Desk') }}</x-nav-link>
                @endif
            </div>
        @else
            <div class="hidden items-center gap-6 md:flex">
                <x-nav-link :href="route('home')" :active="request()->routeIs('home')">{{ __('Home') }}</x-nav-link>
                <x-nav-link :href="route('services.index')" :active="request()->routeIs('services.*')">{{ __('Services') }}</x-nav-link>
            </div>
        @endauth
    </div>

    <div class="flex items-center gap-1 rounded-xl border border-slate-200 bg-slate-50 p-1" role="group" aria-label="Language">
    <a href="{{ route('language.switch', 'en') }}" class="rounded-lg px-2.5 py-1.5 text-xs font-extrabold {{ app()->getLocale() === 'en' ? 'bg-white text-blue-700 shadow-sm' : 'text-slate-500 hover:text-slate-800' }}">EN</a>
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

    <button type="button" @click="open=!open" class="rounded-xl p-2 text-slate-500 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 md:hidden" aria-label="Toggle navigation menu" :aria-expanded="open.toString()" aria-controls="mobile-menu">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path x-show="!open" stroke-linecap="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            <path x-show="open" stroke-linecap="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
</div>
</div>

<div id="mobile-menu" x-cloak x-show="open" x-transition class="mobile-menu border-t border-slate-200 bg-white px-4 py-3 md:hidden">
    <div class="mb-3 flex items-center gap-2 border-b border-slate-200 pb-3"><div class="flex items-center gap-1 rounded-xl border border-slate-200 bg-slate-50 p-1" role="group" aria-label="Language">
    <a href="{{ route('language.switch', 'en') }}" class="rounded-lg px-2.5 py-1.5 text-xs font-extrabold {{ app()->getLocale() === 'en' ? 'bg-white text-blue-700 shadow-sm' : 'text-slate-500 hover:text-slate-800' }}">EN</a>
    <a href="{{ route('language.switch', 'sw') }}" class="rounded-lg px-2.5 py-1.5 text-xs font-extrabold {{ app()->getLocale() === 'sw' ? 'bg-white text-blue-700 shadow-sm' : 'text-slate-500 hover:text-slate-800' }}">SW</a>
</div></div>
    <div class="space-y-1">
        @auth
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">{{ __('Dashboard') }}</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('services.index')" :active="request()->routeIs('services.*')">{{ __('Services') }}</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('customer.applications.index')" :active="request()->routeIs('customer.applications.*')">{{ __('My Applications') }}</x-responsive-nav-link>
            @if(Auth::user()->isAdmin())
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">{{ __('Admin') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.services.index')" :active="request()->routeIs('admin.services.*')">{{ __('Manage Services') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.help-desk.index')" :active="request()->routeIs('admin.help-desk.*')">{{ __('Help Desk') }}</x-responsive-nav-link>
            @endif
            <x-responsive-nav-link :href="route('profile.edit')">{{ __('Profile') }}</x-responsive-nav-link>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="block w-full rounded-lg px-3 py-2 text-left text-sm font-semibold text-slate-700 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    {{ __('Log Out') }}
                </button>
            </form>
        @else
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">{{ __('Home') }}</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('services.index')" :active="request()->routeIs('services.*')">{{ __('Services') }}</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('login')">{{ __('Sign in') }}</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('register')">{{ __('Get started') }}</x-responsive-nav-link>
        @endauth
    </div>
</div>
</nav>