<nav x-data="{ open: false }" class="sticky top-0 z-40 border-b border-slate-200 bg-white/95 backdrop-blur">
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
                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Dashboard</x-nav-link>
                <x-nav-link :href="route('services.index')" :active="request()->routeIs('services.*')">Services</x-nav-link>
                <x-nav-link :href="route('customer.applications.index')" :active="request()->routeIs('customer.applications.*')">My Applications</x-nav-link>
                @if(Auth::user()->isAdmin())
                    <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')">Admin</x-nav-link>
                @endif
            </div>
        @else
            <div class="hidden items-center gap-6 md:flex">
                <x-nav-link :href="route('home')" :active="request()->routeIs('home')">Home</x-nav-link>
                <x-nav-link :href="route('services.index')" :active="request()->routeIs('services.*')">Services</x-nav-link>
            </div>
        @endauth
    </div>

    <button type="button" x-data @click="document.documentElement.classList.toggle('dark'); localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light')" class="dark-mode-toggle hidden md:inline-flex" aria-label="Toggle dark mode" title="Toggle dark mode">
        <span aria-hidden="true">◐</span>
    </button>

    @auth
        <div class="hidden items-center gap-3 md:flex">
            <span class="max-w-48 truncate text-sm font-semibold text-slate-500">{{ Auth::user()->name }}</span>
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button type="button" class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-sm font-extrabold text-slate-700 hover:bg-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" aria-label="Open account menu">
                        {{ strtoupper(substr(Auth::user()->name,0,1)) }}
                    </button>
                </x-slot>
                <x-slot name="content">
                    <x-dropdown-link :href="route('profile.edit')">Profile</x-dropdown-link>
                    @if(Auth::user()->isAdmin())
                        <x-dropdown-link :href="route('admin.dashboard')">Admin Panel</x-dropdown-link>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">Log Out</x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>
    @else
        <div class="hidden items-center gap-2 md:flex">
            <a href="{{ route('login') }}" class="rounded-xl px-4 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Sign in</a>
            <a href="{{ route('register') }}" class="rounded-xl bg-blue-700 px-4 py-2.5 text-sm font-bold text-white hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Get started</a>
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

<div id="mobile-menu" x-cloak x-show="open" x-transition class="border-t border-slate-200 bg-white px-4 py-3 md:hidden">
    <div class="space-y-1">
        @auth
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Dashboard</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('services.index')" :active="request()->routeIs('services.*')">Services</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('customer.applications.index')" :active="request()->routeIs('customer.applications.*')">My Applications</x-responsive-nav-link>
            @if(Auth::user()->isAdmin())
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')">Admin</x-responsive-nav-link>
            @endif
            <x-responsive-nav-link :href="route('profile.edit')">Profile</x-responsive-nav-link>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">Log Out</x-responsive-nav-link>
            </form>
        @else
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">Home</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('services.index')" :active="request()->routeIs('services.*')">Services</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('login')">Sign in</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('register')">Get started</x-responsive-nav-link>
        @endauth
    </div>
</div>
</nav>