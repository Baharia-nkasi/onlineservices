<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Online Services') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-900 antialiased">
<header class="sticky top-0 z-50 border-b border-slate-200/80 bg-white/95 backdrop-blur">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
        <a href="{{ url('/') }}" class="flex items-center gap-3">
            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-700 text-lg font-black text-white shadow-sm">OS</span>
            <span><span class="block text-base font-extrabold leading-none">Online Services</span><span class="mt-1 block text-xs text-slate-500">Simple. Secure. Convenient.</span></span>
        </a>
        <nav class="hidden items-center gap-7 text-sm font-semibold text-slate-600 md:flex">
            <a href="{{ url('/') }}" class="hover:text-blue-700">Home</a>
            <a href="{{ route('services.index') }}" class="hover:text-blue-700">Services</a>
            <a href="#how-it-works" class="hover:text-blue-700">How it works</a>
            <a href="#about" class="hover:text-blue-700">About</a>
        </nav>
        <div class="flex items-center gap-2">
            @auth
                <a href="{{ route('dashboard') }}" class="rounded-xl bg-blue-700 px-4 py-2.5 text-sm font-bold text-white hover:bg-blue-800">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="hidden rounded-xl px-4 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-100 sm:inline-flex">Sign in</a>
                <a href="{{ route('register') }}" class="rounded-xl bg-blue-700 px-4 py-2.5 text-sm font-bold text-white hover:bg-blue-800">Get started</a>
            @endauth
        </div>
    </div>
</header>

<main>
<section class="relative overflow-hidden bg-slate-950">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_75%_20%,rgba(37,99,235,.35),transparent_35%),radial-gradient(circle_at_10%_90%,rgba(14,165,233,.16),transparent_30%)]"></div>
    <div class="relative mx-auto grid max-w-7xl items-center gap-12 px-4 py-20 sm:px-6 lg:grid-cols-2 lg:px-8 lg:py-28">
        <div>
            <span class="inline-flex rounded-full border border-blue-400/30 bg-blue-400/10 px-4 py-2 text-sm font-bold text-blue-200">ONE PORTAL FOR YOUR SERVICES</span>
            <h1 class="mt-6 text-4xl font-black tracking-tight text-white sm:text-6xl">Access services.<br><span class="text-blue-300">Apply with ease.</span></h1>
            <p class="mt-6 max-w-xl text-lg leading-8 text-slate-300">Find the service you need, understand the requirements, submit your application and follow its progress from one secure online platform.</p>
            <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                <a href="{{ route('services.index') }}" class="rounded-xl bg-white px-6 py-3.5 text-center font-extrabold text-slate-950 hover:bg-slate-100">Explore services →</a>
                @guest
                    <a href="{{ route('register') }}" class="rounded-xl border border-white/20 bg-white/10 px-6 py-3.5 text-center font-extrabold text-white hover:bg-white/15">Create account</a>
                @endguest
            </div>
            <div class="mt-8 grid max-w-xl grid-cols-3 gap-4 border-t border-white/10 pt-6 text-sm">
                <div><strong class="block text-xl text-white">Easy</strong><span class="text-slate-400">guided applications</span></div>
                <div><strong class="block text-xl text-white">Secure</strong><span class="text-slate-400">document handling</span></div>
                <div><strong class="block text-xl text-white">Trackable</strong><span class="text-slate-400">application progress</span></div>
            </div>
        </div>
        <div class="relative hidden lg:block">
            <div class="absolute -inset-6 rounded-[3rem] bg-blue-500/10 blur-3xl"></div>
            <div class="relative overflow-hidden rounded-[2rem] border border-white/10 bg-white/5 p-3 shadow-2xl backdrop-blur">
                <img src="{{ asset('images/online-services-hero.svg') }}"
                     alt="Online Services application and document tracking"
                     class="h-auto w-full rounded-[1.5rem]">
            </div>
        </div>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div><p class="text-sm font-extrabold uppercase tracking-wider text-blue-700">Services</p><h2 class="mt-2 text-3xl font-black tracking-tight">What do you need today?</h2><p class="mt-2 max-w-2xl text-slate-500">Browse available services and see the requirements before you apply.</p></div>
        <a href="{{ route('services.index') }}" class="font-bold text-blue-700 hover:text-blue-900">View all services →</a>
    </div>
    <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
        @forelse($services as $service)
            <article class="group flex flex-col rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-xl">
                <div class="flex items-center justify-between"><span class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-lg">📄</span><span class="text-xs font-bold text-slate-400">{{ $service->documents->where('is_active', true)->count() }} requirements</span></div>
                <h3 class="mt-5 text-lg font-extrabold">{{ $service->name }}</h3>
                <p class="mt-2 line-clamp-3 flex-1 text-sm leading-6 text-slate-500">{{ $service->description }}</p>
                <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-4"><span class="text-sm text-slate-500">Service fee</span><span class="text-sm font-black text-slate-900">TSh {{ number_format($service->service_fee, 0) }}</span></div>
            </article>
        @empty
            <div class="sm:col-span-2 lg:col-span-4 rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center text-slate-500">Services will appear here when they are available.</div>
        @endforelse
    </div>
</section>

<section id="how-it-works" class="border-y border-slate-200 bg-white">
    <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="text-center"><p class="text-sm font-extrabold uppercase tracking-wider text-blue-700">How it works</p><h2 class="mt-2 text-3xl font-black">A clear process from start to finish</h2></div>
        <div class="mt-10 grid gap-5 md:grid-cols-4">
            @foreach([['01','Choose','Find the service and read its requirements.'],['02','Apply','Enter your information through a guided application.'],['03','Upload','Submit the required documents securely.'],['04','Track','Follow your application until completion.']] as $step)
                <div class="relative rounded-2xl border border-slate-200 bg-slate-50 p-6"><span class="text-sm font-black text-blue-700">{{ $step[0] }}</span><h3 class="mt-3 font-extrabold">{{ $step[1] }}</h3><p class="mt-2 text-sm leading-6 text-slate-500">{{ $step[2] }}</p></div>
            @endforeach
        </div>
    </div>
</section>

<section id="about" class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
    <div class="grid gap-8 lg:grid-cols-2">
        <div><p class="text-sm font-extrabold uppercase tracking-wider text-blue-700">Why Online Services?</p><h2 class="mt-2 text-3xl font-black">Everything starts with clarity.</h2><p class="mt-4 leading-7 text-slate-600">The portal is designed to make service requests easier to understand: what the service is, what you need, what you have submitted and what happens next.</p></div>
        <div class="grid gap-4 sm:grid-cols-2"><div class="rounded-2xl border bg-white p-5"><b>Clear requirements</b><p class="mt-2 text-sm text-slate-500">Know required documents before starting.</p></div><div class="rounded-2xl border bg-white p-5"><b>Application tracking</b><p class="mt-2 text-sm text-slate-500">See your current application status.</p></div><div class="rounded-2xl border bg-white p-5"><b>Secure access</b><p class="mt-2 text-sm text-slate-500">Your account controls your applications.</p></div><div class="rounded-2xl border bg-white p-5"><b>One account</b><p class="mt-2 text-sm text-slate-500">Manage your services in one place.</p></div></div>
    </div>
</section>
</main>

<footer class="border-t border-slate-200 bg-slate-950 text-slate-300">
    <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-10 sm:px-6 md:flex-row md:items-center md:justify-between lg:px-8">
        <div><p class="font-extrabold text-white">Online Services</p><p class="mt-1 text-sm text-slate-400">A simple portal for service applications and tracking.</p></div>
        <p class="text-sm text-slate-500">© {{ date('Y') }} Online Services. All rights reserved.</p>
    </div>
</footer>
</body>
</html>