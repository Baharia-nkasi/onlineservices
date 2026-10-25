<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Online Services') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

<style>

/* Premium public landing design: champagne canvas, espresso contrast, deliberate gold accents. */
.landing-page {
  --landing-ink:#21180b;
  --landing-muted:#5f5545;
  --landing-gold:#d4a017;
  --landing-gold-bright:#f2cb62;
  --landing-gold-deep:#684900;
  --landing-espresso:#21180b;
  --landing-espresso-2:#38270c;
  --landing-cream:#fbf7ed;
  --landing-card:#fffefa;
  --landing-border:#e4d3a0;
  background:var(--landing-cream)!important;
  color:var(--landing-ink)!important;
  font-family:Inter,ui-sans-serif,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;
}
.landing-page :is(h1,h2,h3,h4,h5,h6) { color:var(--landing-ink); letter-spacing:-.025em; }
.landing-page :is(p,li) { color:var(--landing-muted); }
.landing-page a,.landing-page button { -webkit-tap-highlight-color:transparent; }
.landing-page :is(a,button):focus-visible { outline:3px solid #8a6200!important; outline-offset:3px; box-shadow:0 0 0 5px rgb(212 160 23 / 22%); }
.landing-page header { background:rgb(255 254 250 / 96%)!important; border-color:var(--landing-border)!important; box-shadow:0 5px 24px rgb(33 24 11 / 7%); }
.landing-page header>div { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem; }
.landing-page header>div>a:first-child { flex:0 1 auto; min-width:0; }
.landing-page header>div>a:first-child>span:first-child { background:linear-gradient(145deg,#f3d77f,#c18a0a)!important; color:#21180b!important; box-shadow:0 5px 14px rgb(104 73 0 / 20%); }
.landing-page header a { color:var(--landing-ink); }
.landing-page header nav { display:flex; align-items:center; flex-wrap:wrap; gap:clamp(.75rem,1.7vw,1.5rem); }
.landing-page header nav a { text-decoration:none; transition:color .18s ease; }
.landing-page header nav a:hover { color:var(--landing-gold-deep)!important; }
.landing-page header [role=group] { background:#fff8e5!important; border-color:var(--landing-border)!important; }
.landing-page header [role=group] a { color:#5e4a20!important; }
.landing-page header [role=group] a.bg-blue-50 { background:#f0cf70!important; color:#21180b!important; }
.landing-page header a.bg-blue-700 { background:linear-gradient(135deg,#f2d276,#d4a017)!important; color:#21180b!important; border:1px solid #b8860b; box-shadow:0 5px 14px rgb(104 73 0 / 18%); }
.landing-page header a.bg-blue-700:hover { background:#b8860b!important; color:#fff!important; }
.landing-page header a.hover\:bg-slate-100:hover { background:#f5ead0!important; }

/* Hero: the overlay remains visible; gold is an accent, not a low-contrast text color. */
.landing-page .landing-hero {
  position:relative; isolation:isolate; overflow:hidden;
  background:radial-gradient(ellipse at 78% 15%,rgb(212 160 23 / 23%),transparent 34%),
             radial-gradient(ellipse at 8% 95%,rgb(164 112 14 / 19%),transparent 38%),
             linear-gradient(120deg,#171109 0%,#30220c 52%,#21180b 100%)!important;
  color:#fffefa;
}
.landing-page .landing-hero>div:first-child { opacity:1!important; pointer-events:none; background:radial-gradient(circle at 75% 18%,rgb(242 203 98 / 12%),transparent 38%),radial-gradient(circle at 8% 90%,rgb(212 160 23 / 9%),transparent 32%)!important; }
.landing-page .landing-hero>div.relative { position:relative; z-index:1; display:flex; flex-wrap:wrap; align-items:center; gap:clamp(2.25rem,5vw,4.5rem); }
.landing-page .landing-hero>div.relative>div:first-child { flex:1 1 27rem; min-width:0; }
.landing-page .landing-hero>div.relative>[data-service-carousel] { flex:1 1 27rem; min-width:0; max-width:100%; }
.landing-page .landing-hero h1 { color:#fffefa!important; line-height:1.07; text-wrap:balance; }
.landing-page .landing-hero h1 span { color:var(--landing-gold-bright)!important; background:none!important; -webkit-text-fill-color:currentColor!important; }
.landing-page .landing-hero p { color:#e9e0cf!important; }
.landing-page .landing-hero>div.relative>div:first-child>span:first-child { display:inline-flex; align-items:center; gap:.5rem; border:1px solid rgb(242 203 98 / 48%)!important; background:rgb(212 160 23 / 13%)!important; color:#f5d77e!important; letter-spacing:.12em; font-size:.72rem; }
.landing-page .landing-hero>div.relative>div:first-child>div:nth-of-type(1) { display:flex; flex-wrap:wrap; align-items:stretch; gap:.8rem; }
.landing-page .landing-hero>div.relative>div:first-child>div:nth-of-type(1)>a { flex:0 1 auto; min-height:48px; display:inline-flex; align-items:center; justify-content:center; }
.landing-page .landing-hero>div.relative>div:first-child>div:nth-of-type(2) { display:flex; flex-wrap:wrap; gap:1rem; border-color:rgb(242 203 98 / 25%)!important; }
.landing-page .landing-hero>div.relative>div:first-child>div:nth-of-type(2)>div { flex:1 1 105px; min-width:0; }
.landing-page .landing-hero>div.relative>div:first-child>div:nth-of-type(2) strong { color:#f5d77e!important; }
.landing-page .landing-hero>div.relative>div:first-child>div:nth-of-type(2) span { color:#d9cdb7!important; }
.landing-page .landing-hero a.rounded-xl.bg-white { background:linear-gradient(135deg,#f6dc8e,#d4a017)!important; color:#21180b!important; border:1px solid #f1d17b; box-shadow:0 9px 24px rgb(0 0 0 / 22%); transition:transform .18s ease,box-shadow .18s ease,background .18s ease; }
.landing-page .landing-hero a.rounded-xl.bg-white:hover { background:#e6b93f!important; transform:translateY(-2px); box-shadow:0 13px 30px rgb(0 0 0 / 28%); }
.landing-page .landing-hero a.border-white\/20 { background:rgb(255 255 255 / 7%)!important; color:#fffefa!important; border-color:rgb(242 203 98 / 55%)!important; }
.landing-page .landing-hero a.border-white\/20:hover { background:rgb(212 160 23 / 17%)!important; }
.landing-page .landing-hero [data-service-carousel]>div:first-child { background:rgb(212 160 23 / 20%)!important; }
.landing-page .landing-hero [data-service-carousel]>div:nth-child(2) { background:#fffefa!important; border:1px solid rgb(242 203 98 / 70%)!important; box-shadow:0 28px 70px rgb(0 0 0 / 32%)!important; }
.landing-page .landing-hero [data-service-carousel] article { position:relative; overflow:hidden; }
.landing-page .landing-hero [data-service-carousel] article>div:nth-last-child(2) { background:linear-gradient(to top,rgb(23 17 9 / 96%),rgb(23 17 9 / 48%) 58%,transparent)!important; }
.landing-page .landing-hero [data-service-carousel] article :is(h2,p) { color:#fffefa!important; }
.landing-page .landing-hero [data-service-carousel] article p.text-blue-200 { color:#f5d77e!important; }
.landing-page .landing-hero [data-service-carousel] article a { background:linear-gradient(135deg,#f6dc8e,#d4a017)!important; color:#21180b!important; }
.landing-page .landing-hero [data-service-carousel] article a:hover { background:#b8860b!important; color:#fff!important; }
.landing-page .landing-hero [data-carousel-prev],.landing-page .landing-hero [data-carousel-next] { background:#d4a017!important; color:#21180b!important; min-width:36px; min-height:36px; }
.landing-page .landing-hero [data-carousel-dot] { background:#e0b23d!important; }

/* Service cards use CSS Grid; flexible hero/actions use Flexbox. */
.landing-page .landing-services { background:var(--landing-cream)!important; }
.landing-page .landing-services h2,.landing-page .landing-services h3,.landing-page .landing-steps h2,.landing-page .landing-steps h3,.landing-page .landing-about h2,.landing-page .landing-about h3,.landing-page .landing-about b { color:var(--landing-ink)!important; }
.landing-page .landing-services p,.landing-page .landing-steps p,.landing-page .landing-about p { color:var(--landing-muted)!important; }
.landing-page .landing-services [class*=text-blue-700],.landing-page .landing-steps [class*=text-blue-700],.landing-page .landing-about [class*=text-blue-700] { color:var(--landing-gold-deep)!important; }
.landing-page .landing-services article,.landing-page .landing-steps>div>div:last-child>div,.landing-page .landing-about>div>div:last-child>div { background:var(--landing-card)!important; border:1px solid var(--landing-border)!important; box-shadow:0 6px 22px rgb(67 48 13 / 6%)!important; transition:transform .2s ease,box-shadow .2s ease,border-color .2s ease; }
.landing-page .landing-services article:hover,.landing-page .landing-steps>div>div:last-child>div:hover,.landing-page .landing-about>div>div:last-child>div:hover { border-color:#d4a017!important; box-shadow:0 14px 32px rgb(67 48 13 / 12%)!important; transform:translateY(-3px); }
.landing-page .landing-services article a { color:var(--landing-gold-deep)!important; }
.landing-page .landing-services article a:hover { color:#4d3500!important; }
.landing-page .landing-steps { background:linear-gradient(180deg,#f4e8c5 0%,#fbf7ed 100%)!important; border-color:var(--landing-border)!important; }
.landing-page .landing-steps>div>div:last-child>div { background:rgb(255 254 250 / 94%)!important; }
.landing-page .landing-steps>div>div:last-child>div>span { color:var(--landing-gold-deep)!important; }
.landing-page .landing-about>div>div:last-child>div { border-radius:1rem; }
.landing-page footer { background:linear-gradient(115deg,#171109,#30220c)!important; border-color:#8a6200!important; color:#fff8e6!important; }
.landing-page footer p:first-child { color:#f5d77e!important; }
.landing-page footer p { color:#e2d5bb!important; }

@media(max-width:767px) {
  .landing-page header>div { align-items:center; gap:.7rem; padding-top:.75rem; padding-bottom:.75rem; }
  /* Hide desktop navigation links on small screens so the account buttons have room. */
  .landing-page header nav { display:none!important; }
  .landing-page header>div>a:first-child { flex:1 1 100%; min-width:0; }
  .landing-page header>div>a:first-child>span:last-child span:last-child { font-size:.65rem; }
  .landing-page header>div>div:last-child {
    display:flex; flex:1 1 100%; width:100%; min-width:0; margin-left:0;
    flex-wrap:nowrap; align-items:center; justify-content:space-between; gap:.5rem;
  }
  .landing-page header>div>div:last-child [role=group] { flex:0 0 auto; }
  .landing-page header>div>div:last-child > a {
    display:inline-flex!important; align-items:center; justify-content:center;
    min-height:42px; white-space:nowrap;
  }
  .landing-page header a.bg-blue-700 { flex:0 1 auto; padding:.6rem .75rem; font-size:.8rem; }
  .landing-page .landing-hero>div.relative { gap:2rem; padding-top:3.25rem; padding-bottom:3.25rem; }
  .landing-page .landing-hero>div.relative>div:first-child,.landing-page .landing-hero>div.relative>[data-service-carousel] { flex-basis:100%; }
  .landing-page .landing-hero h1 { font-size:clamp(2.35rem,10vw,3.6rem); line-height:1.07; }
  .landing-page .landing-hero [data-service-carousel] img { height:340px; }
  .landing-page .landing-hero [data-service-carousel] article>div:nth-last-child(2) { padding:1.15rem; }
  .landing-page .landing-hero [data-service-carousel] article h2 { font-size:1.3rem; }
  .landing-page .landing-hero>div.relative>div:first-child>div:nth-of-type(2) { gap:.75rem; }
  .landing-page .landing-hero>div.relative>div:first-child>div:nth-of-type(2)>div { flex-basis:90px; }
  .landing-page .landing-hero>div.relative>div:first-child>div:first-child + div a { min-height:48px; }
  .landing-page .landing-services,.landing-page .landing-steps,.landing-page .landing-about { padding-top:3.25rem; padding-bottom:3.25rem; }
}
@media(max-width:380px) {
  .landing-page header>div>a:first-child>span:last-child span:first-child { font-size:.88rem; }
  .landing-page header>div>div:last-child [role=group] a { padding:.35rem .45rem; }
  .landing-page header a.bg-blue-700 { padding:.55rem .6rem; font-size:.76rem; }
  .landing-page .landing-hero [data-service-carousel] img { height:300px; }
}
@media(prefers-reduced-motion:reduce) {
  .landing-page *, .landing-page *::before,.landing-page *::after { scroll-behavior:auto!important; transition-duration:.01ms!important; animation-duration:.01ms!important; transform:none!important; }
}

</style>
</head>
<body class="landing-page bg-slate-50 text-slate-900 antialiased">
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
                <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-xl px-3 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-100">Sign in</a>
                <a href="{{ route('register') }}" class="rounded-xl bg-blue-700 px-4 py-2.5 text-sm font-bold text-white hover:bg-blue-800">Get started</a>
            @endauth
        </div>
    </div>
</header>

<main>
<section class="landing-hero relative overflow-hidden bg-slate-950">
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

<section class="landing-services mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div><p class="text-sm font-extrabold uppercase tracking-wider text-blue-700"> {{ __('Services') }} </p><h2 class="mt-2 text-3xl font-black tracking-tight"> {{ __('What do you need today?') }} </h2><p class="mt-2 max-w-2xl text-slate-500">{{ __('Browse available services and see the requirements before you apply.') }}</p></div>
        <a href="{{ route('services.index') }}" class="font-bold text-blue-700 hover:text-blue-900"> {{ __('View all services →') }} </a>
    </div>
    <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
        @forelse($services as $service)
            <article class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-xl">
                <div class="relative h-40 overflow-hidden">
                    <img src="{{ $service->image_url ?: \App\Support\ServiceImage::fallbackDataUri($service) }}" data-service-fallback="{{ \App\Support\ServiceImage::fallbackDataUri($service) }}" alt="{{ $service->name }}" referrerpolicy="no-referrer" onerror="this.onerror=null;this.src=this.dataset.serviceFallback;" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy">
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

<section id="how-it-works" class="landing-steps border-y border-slate-200 bg-white">
    <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="text-center"><p class="text-sm font-extrabold uppercase tracking-wider text-blue-700"> {{ __('How it works') }} </p><h2 class="mt-2 text-3xl font-black"> {{ __('A clear process from start to finish') }} </h2></div>
        <div class="mt-10 grid gap-5 md:grid-cols-4">
            @foreach([['01', __('Choose'), __('Find the service and read its requirements.')],['02', __('Apply'), __('Enter your information through a guided application.')],['03', __('Upload'), __('Submit the required documents securely.')],['04', __('Track'), __('Follow your application until completion.')]] as $step)
                <div class="relative rounded-2xl border border-slate-200 bg-slate-50 p-6"><span class="text-sm font-black text-blue-700">{{ $step[0] }}</span><h3 class="mt-3 font-extrabold">{{ $step[1] }}</h3><p class="mt-2 text-sm leading-6 text-slate-500">{{ $step[2] }}</p></div>
            @endforeach
        </div>
    </div>
</section>

<section id="about" class="landing-about mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
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