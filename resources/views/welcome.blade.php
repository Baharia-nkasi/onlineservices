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
            <span><span class="block text-base font-extrabold leading-none">Online Services</span><span class="mt-1 block text-xs text-slate-500">{{ __('Simple. Secure. Convenient.') }}</span></span>
        </a>
        <nav class="hidden items-center gap-7 text-sm font-semibold text-slate-600 md:flex">
            <a href="{{ url('/') }}" class="hover:text-blue-700">{{ __('Home') }}</a>
            <a href="{{ route('services.index') }}" class="hover:text-blue-700"> {{ __('Services') }} </a>
            <a href="#how-it-works" class="hover:text-blue-700"> {{ __('How it works') }} </a>
            <a href="#about" class="hover:text-blue-700"> {{ __('About') }} </a>
        </nav>
        <div class="flex items-center gap-2"><div class="flex items-center gap-1 rounded-xl border border-slate-200 bg-white/90 p-1 shadow-sm" role="group" aria-label="Language">
 <a href="{{ route('language.switch', 'en') }}" class="rounded-lg px-2.5 py-1.5 text-xs font-extrabold {{ app()->getLocale() === 'en' ? 'bg-blue-50 text-blue-700' : 'text-slate-500' }}">EN</a>
 <a href="{{ route('language.switch', 'sw') }}" class="rounded-lg px-2.5 py-1.5 text-xs font-extrabold {{ app()->getLocale() === 'sw' ? 'bg-blue-50 text-blue-700' : 'text-slate-500' }}">SW</a>
</div>
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
            <span class="inline-flex rounded-full border border-blue-400/30 bg-blue-400/10 px-4 py-2 text-sm font-bold text-blue-200"> {{ __('ONE PORTAL FOR YOUR SERVICES') }} </span>
            <h1 class="mt-6 text-4xl font-black tracking-tight text-white sm:text-6xl">{{ __('Access services.') }}<br><span class="text-blue-300">{{ __('Apply with ease.') }}</span></h1>
            <p class="mt-6 max-w-xl text-lg leading-8 text-slate-300">{{ __('Find the service you need, understand the requirements, submit your application and follow its progress from one secure online platform.') }}</p>
            <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                <a href="{{ route('services.index') }}" class="rounded-xl bg-white px-6 py-3.5 text-center font-extrabold text-slate-950 hover:bg-slate-100">{{ __('Explore services →') }}</a>
                @guest
                    <a href="{{ route('register') }}" class="rounded-xl border border-white/20 bg-white/10 px-6 py-3.5 text-center font-extrabold text-white hover:bg-white/15">{{ __('Create account') }}</a>
                @endguest
            </div>
            <div class="mt-8 grid max-w-xl grid-cols-3 gap-4 border-t border-white/10 pt-6 text-sm">
                <div><strong class="block text-xl text-white"> {{ __('Easy') }} </strong><span class="text-slate-400"> {{ __('guided applications') }} </span></div>
                <div><strong class="block text-xl text-white"> {{ __('Secure') }} </strong><span class="text-slate-400"> {{ __('document handling') }} </span></div>
                <div><strong class="block text-xl text-white"> {{ __('Trackable') }} </strong><span class="text-slate-400"> {{ __('application progress') }} </span></div>
            </div>
        </div>
        @php
            // Use the database image for the exact service. Each service has
            // its own image URL and an embedded SVG fallback, so a broken
            // remote image can never leave an empty hero slide.
            $serviceSlides = $services->values()->map(function ($service) {
                return [
                    'name' => $service->name,
                    'description' => $service->description,
                    'url' => route('applications.create', $service),
                    'image' => $service->image_url ?: \App\Support\ServiceImage::fallbackDataUri($service),
                    'fallback' => \App\Support\ServiceImage::fallbackDataUri($service),
                ];
            });
        @endphp
        <div class="relative block" data-service-carousel>
            <div class="absolute -inset-6 rounded-[3rem] bg-blue-500/10 blur-3xl"></div>
            <div class="relative overflow-hidden rounded-[2rem] border border-white/10 bg-white/5 shadow-2xl backdrop-blur">
                @forelse($serviceSlides as $index => $slide)
                    <article class="service-hero-slide {{ $index === 0 ? '' : 'hidden' }}" data-service-slide data-slide-index="{{ $index }}">
                        <img
                            src="{{ $slide['image'] }}"
                            data-service-fallback="{{ $slide['fallback'] }}"
                            alt="{{ $slide['name'] }}"
                            class="h-[430px] w-full object-cover"
                            loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                            referrerpolicy="no-referrer"
                            onerror="this.onerror=null;this.src=this.dataset.serviceFallback;"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/15 to-transparent"></div>
                        <div class="absolute inset-x-0 bottom-0 p-7">
                            <p class="text-xs font-black uppercase tracking-[0.2em] text-blue-200">{{ __('Featured service') }}</p>
                            <h2 class="mt-2 text-2xl font-black text-white">{{ $slide['name'] }}</h2>
                            <p class="mt-2 max-w-xl text-sm leading-6 text-slate-200">{{ $slide['description'] }}</p>
                            <a href="{{ $slide['url'] }}" class="mt-4 inline-flex rounded-xl bg-white px-4 py-2.5 text-sm font-black text-slate-950 hover:bg-slate-100">{{ __('Start application →') }}</a>
                        </div>
                    </article>
                @empty
                    <div class="flex h-[430px] items-center justify-center p-8 text-center text-slate-300">{{ __('Services will appear here when they are available.') }}</div>
                @endforelse
                @if($serviceSlides->count() > 1)
                    <div class="absolute bottom-5 right-5 flex items-center gap-2 rounded-full bg-slate-950/60 px-3 py-2 backdrop-blur">
                        <button type="button" data-carousel-prev class="flex h-8 w-8 items-center justify-center rounded-full bg-white/10 text-white hover:bg-white/20" aria-label="{{ __('Previous service') }}">‹</button>
                        <div class="flex items-center gap-1.5" aria-label="{{ __('Featured services') }}">
                            @foreach($serviceSlides as $index => $slide)
                                <button type="button" data-carousel-dot="{{ $index }}" class="h-2 w-2 rounded-full {{ $index === 0 ? 'bg-white' : 'bg-white/35' }}" aria-label="{{ __('Show service') }} {{ $index + 1 }}"></button>
                            @endforeach
                        </div>
                        <button type="button" data-carousel-next class="flex h-8 w-8 items-center justify-center rounded-full bg-white/10 text-white hover:bg-white/20" aria-label="{{ __('Next service') }}">›</button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div><p class="text-sm font-extrabold uppercase tracking-wider text-blue-700"> {{ __('Services') }} </p><h2 class="mt-2 text-3xl font-black tracking-tight"> {{ __('What do you need today?') }} </h2><p class="mt-2 max-w-2xl text-slate-500">{{ __('Browse available services and see the requirements before you apply.') }}</p></div>
        <a href="{{ route('services.index') }}" class="font-bold text-blue-700 hover:text-blue-900"> {{ __('View all services →') }} </a>
    </div>
    <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
        @forelse($services as $service)
            <article class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-xl">
                <div class="relative h-40 overflow-hidden">
                    <img src="{{ $serviceImage($service->slug, 900) }}" alt="{{ $service->name }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/60 to-transparent"></div>
                </div>
                <div class="p-5">
                    <h3 class="text-lg font-extrabold">{{ $service->name }}</h3>
                    <p class="mt-2 line-clamp-3 text-sm leading-6 text-slate-500">{{ $service->description }}</p>
                    <a href="{{ route('applications.create', $service) }}" class="mt-4 inline-flex text-sm font-black text-blue-700 group-hover:text-blue-900">{{ __('View requirements →') }}</a>
                </div>
            </article>
        @empty
            <div class="col-span-full rounded-2xl border border-slate-200 bg-white p-10 text-center text-slate-500">{{ __('No services are currently available.') }}</div>
        @endforelse
    </div>
</section>

<section id="how-it-works" class="border-y border-slate-200 bg-white">
    <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="text-center"><p class="text-sm font-extrabold uppercase tracking-wider text-blue-700"> {{ __('How it works') }} </p><h2 class="mt-2 text-3xl font-black"> {{ __('A clear process from start to finish') }} </h2></div>
        <div class="mt-10 grid gap-5 md:grid-cols-4">
            @foreach([['01', __('Choose'), __('Find the service and read its requirements.')],['02', __('Apply'), __('Enter your information through a guided application.')],['03', __('Upload'), __('Submit the required documents securely.')],['04', __('Track'), __('Follow your application until completion.')]] as $step)
                <div class="relative rounded-2xl border border-slate-200 bg-slate-50 p-6"><span class="text-sm font-black text-blue-700">{{ $step[0] }}</span><h3 class="mt-3 font-extrabold">{{ $step[1] }}</h3><p class="mt-2 text-sm leading-6 text-slate-500">{{ $step[2] }}</p></div>
            @endforeach
        </div>
    </div>
</section>

<section id="about" class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
    <div class="grid gap-8 lg:grid-cols-2">
        <div><p class="text-sm font-extrabold uppercase tracking-wider text-blue-700"> {{ __('Why Online Services?') }} </p><h2 class="mt-2 text-3xl font-black"> {{ __('Everything starts with clarity.') }} </h2><p class="mt-4 leading-7 text-slate-600">{{ __('The portal is designed to make service requests easier to understand: what the service is, what you need, what you have submitted and what happens next.') }}</p></div>
        <div class="grid gap-4 sm:grid-cols-2"><div class="rounded-2xl border bg-white p-5"><b> {{ __('Clear requirements') }} </b><p class="mt-2 text-sm text-slate-500">{{ __('Know required documents before starting.') }}</p></div><div class="rounded-2xl border bg-white p-5"><b> {{ __('Application tracking') }} </b><p class="mt-2 text-sm text-slate-500">{{ __('See your current application status.') }}</p></div><div class="rounded-2xl border bg-white p-5"><b> {{ __('Secure access') }} </b><p class="mt-2 text-sm text-slate-500">{{ __('Your account controls your applications.') }}</p></div><div class="rounded-2xl border bg-white p-5"><b> {{ __('One account') }} </b><p class="mt-2 text-sm text-slate-500">{{ __('Manage your services in one place.') }}</p></div></div>
    </div>
</section>
</main>

<footer class="border-t border-slate-200 bg-slate-950 text-slate-300">
    <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-10 sm:px-6 md:flex-row md:items-center md:justify-between lg:px-8">
        <div><p class="font-extrabold text-white">Online Services</p><p class="mt-1 text-sm text-slate-400">{{ __('A simple portal for service applications and tracking.') }}</p></div>
        <p class="text-sm text-slate-500">© {{ date('Y') }} {{ __('Online Services') }}. {{ __('All rights reserved.') }}</p>
    </div>
</footer>
</body>
</html>