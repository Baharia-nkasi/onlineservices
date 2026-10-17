<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Online Services') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
      /* Authentication pages share the public landing page's espresso-and-gold identity. */
      :root { color-scheme: light; }
      body:has(.neon-login-shell) { background:#21180b!important; color:#21180b; }
      .neon-login-shell {
        min-height:100vh; isolation:isolate; position:relative; overflow:hidden;
        background:
          radial-gradient(ellipse at 78% 14%,rgb(212 160 23 / 22%),transparent 35%),
          radial-gradient(ellipse at 10% 92%,rgb(164 112 14 / 18%),transparent 38%),
          linear-gradient(120deg,#171109 0%,#30220c 52%,#21180b 100%)!important;
        color:#fffefa!important;
      }
      .neon-login-shell::before {
        content:""; position:absolute; inset:0; pointer-events:none; z-index:0;
        background:radial-gradient(circle at 50% 50%,transparent 20%,rgb(15 10 3 / 22%) 100%);
      }
      .neon-login-shell .neon-particle { display:none!important; }
      .neon-login-shell .neon-login-card {
        background:#fffefa!important; color:#21180b!important; border:1px solid #e4d3a0!important;
        border-radius:1.35rem!important; box-shadow:0 28px 75px rgb(0 0 0 / 28%),0 0 0 1px rgb(242 203 98 / 10%)!important;
      }
      .neon-login-shell .neon-login-card :is(h1,h2,h3) { color:#21180b!important; text-shadow:none!important; }
      .neon-login-shell .neon-login-card p { color:#5f5545!important; }
      .neon-login-shell .neon-login-card label,
      .neon-login-shell .neon-login-card .neon-label { color:#382a13!important; font-weight:750!important; }
      .neon-login-shell .neon-login-card input:not([type="checkbox"]):not([type="radio"]),
      .neon-login-shell .neon-login-card .neon-input,
      .neon-login-shell .neon-login-card .input-modern {
        color:#21180b!important; background:#fffefa!important; border:1px solid #cdbb8a!important;
        border-radius:.7rem!important; min-height:2.85rem; box-shadow:none!important;
      }
      .neon-login-shell .neon-login-card input::placeholder { color:#75684f!important; opacity:1!important; }
      .neon-login-shell .neon-login-card input:focus {
        border-color:#9b6d00!important; outline:3px solid rgb(212 160 23 / 22%)!important; outline-offset:1px;
      }
      .neon-login-shell .neon-login-card input[type="checkbox"] {
        accent-color:#b8860b; width:1rem; height:1rem; border-color:#a38a4e!important; background:#fffefa!important;
      }
      .neon-login-shell .neon-login-card label span,
      .neon-login-shell .neon-login-card [class*="text-gray-"] { color:#5f5545!important; }
      .neon-login-shell .neon-login-card a:not(.auth-switch-button) { color:#684900!important; text-decoration:underline; text-underline-offset:3px; }
      .neon-login-shell .neon-login-card a:not(.auth-switch-button):hover { color:#21180b!important; }
      .neon-login-shell .neon-login-card button,
      .neon-login-shell .neon-login-card .neon-button,
      .neon-login-shell .neon-login-card .portal-button {
        display:inline-flex; align-items:center; justify-content:center; gap:.5rem;
        background:linear-gradient(135deg,#f6dc8e,#d4a017)!important; color:#21180b!important;
        border:1px solid #b8860b!important; border-radius:.75rem!important; font-weight:850!important;
        min-height:2.9rem; box-shadow:0 7px 18px rgb(104 73 0 / 15%)!important; text-decoration:none!important;
        transition:transform .18s ease,box-shadow .18s ease,background .18s ease;
      }
      .neon-login-shell .neon-login-card button:hover,
      .neon-login-shell .neon-login-card .neon-button:hover,
      .neon-login-shell .neon-login-card .portal-button:hover { background:#b8860b!important; color:#fffefa!important; transform:translateY(-1px); }
      .neon-login-shell .neon-login-card button:focus-visible,
      .neon-login-shell .neon-login-card a:focus-visible { outline:3px solid #8a6200!important; outline-offset:3px; }
      .neon-login-shell .neon-login-card .text-red-400,
      .neon-login-shell .neon-login-card .text-red-600 { color:#b42318!important; }
      .neon-login-shell .neon-login-card .text-green-400,
      .neon-login-shell .neon-login-card .text-emerald-600 { color:#176b3a!important; }
      .neon-login-shell .neon-status {
        display:inline-flex; align-items:center; border:1px solid rgb(242 203 98 / 45%)!important;
        border-radius:999px; background:rgb(212 160 23 / 12%)!important; color:#f5d77e!important;
        padding:.4rem .75rem; font-size:.7rem; font-weight:850; letter-spacing:.09em; text-transform:uppercase;
      }
      .neon-login-shell .neon-login-card .auth-switch-button {
        display:inline-flex; align-items:center; justify-content:center; min-height:2.8rem; padding:.65rem 1rem;
        border:1px solid #b8860b!important; border-radius:.75rem!important; background:#fff8e5!important;
        color:#684900!important; font-size:.875rem; font-weight:800; text-decoration:none!important;
        transition:background .18s ease,transform .18s ease;
      }
      .neon-login-shell .neon-login-card .auth-switch-button:hover { background:#f2d276!important; color:#21180b!important; transform:translateY(-1px); }
      .neon-login-shell a[href="{{ url('/') }}"] { transition:color .18s ease; }
      .neon-login-shell>div>div:first-child a>span:first-child,
      .neon-login-shell>div>div:nth-child(2) a>span:first-child {
        border-color:rgb(242 203 98 / 60%)!important; background:rgb(212 160 23 / 12%)!important;
        color:#f5d77e!important; box-shadow:0 0 22px rgb(212 160 23 / 12%)!important;
      }
      .neon-login-shell>div>div:first-child h1 { color:#fffefa!important; }
      .neon-login-shell>div>div:first-child h1 span { color:#f5d77e!important; text-shadow:none!important; }
      .neon-login-shell>div>div:first-child p:not(.neon-status) { color:#e2d5bb!important; }
      .neon-login-shell>div>div:first-child>p:last-child { color:#b8a98e!important; }
      .neon-login-shell>div>div:nth-child(2)>div:last-child>a { color:#e2d5bb!important; }
      .neon-login-shell>div>div:nth-child(2)>div:last-child>a:hover { color:#f5d77e!important; }
      @media(max-width:640px) {
        .neon-login-shell .neon-login-card { border-radius:1rem!important; padding:1.2rem!important; }
        .neon-login-shell .relative.flex.items-center.justify-center { padding-top:5.25rem!important; }
        .neon-login-shell .neon-login-card form>div:last-child { flex-wrap:wrap; gap:.75rem; }
        .neon-login-shell .neon-login-card form>div:last-child button,
        .neon-login-shell .neon-login-card form>div:last-child a { flex:1 1 100%; margin-left:0!important; }
      }
      @media(prefers-reduced-motion:reduce) {
        .neon-login-shell *, .neon-login-shell *::before, .neon-login-shell *::after { transition-duration:.01ms!important; animation-duration:.01ms!important; }
      }
    </style>
</head>
<body class="min-h-screen font-sans antialiased">
<div class="neon-login-shell">
    <span class="neon-particle" aria-hidden="true"></span><span class="neon-particle" aria-hidden="true"></span>
    <span class="neon-particle" aria-hidden="true"></span><span class="neon-particle" aria-hidden="true"></span>
    <span class="neon-particle" aria-hidden="true"></span><span class="neon-particle" aria-hidden="true"></span>

    <div class="relative z-10 grid min-h-screen lg:grid-cols-2">
        <div class="hidden flex-col justify-between p-12 text-white lg:flex">
            <a href="{{ url('/') }}" class="flex items-center gap-3">
                <span class="flex h-11 w-11 items-center justify-center rounded-2xl border border-[#00ff66]/60 bg-[#00ff66]/10 font-black text-[#39ff14] shadow-[0_0_22px_rgba(0,255,102,.18)]">OS</span>
                <span class="text-lg font-extrabold tracking-tight">Online Services</span>
            </a>
            <div class="max-w-xl">
                <p class="text-xs font-black uppercase tracking-[.24em] text-[#39ff14]">Secure digital service portal</p>
                <h1 class="mt-5 text-5xl font-black leading-tight tracking-tight">Your services.<br><span class="text-[#39ff14] [text-shadow:0_0_18px_rgba(0,255,102,.35)]">Connected securely.</span></h1>
                <p class="mt-6 max-w-lg text-base leading-8 text-[#9eb2a6]">Apply for services, upload requirements and track progress through one secure digital workspace.</p>
                <div class="mt-8 flex flex-wrap gap-2">
                    <span class="neon-status">Secure access</span>
                    <span class="neon-status">Live portal</span>
                </div>
            </div>
            <p class="text-sm text-[#64776d]">© {{ date('Y') }} Online Services</p>
        </div>

        <div class="relative flex items-center justify-center px-5 py-12 sm:px-8">
            <div class="absolute right-5 top-5 z-20">
                <div class="flex items-center gap-1 rounded-2xl border border-[#00ff66]/25 bg-[#07100b]/80 p-1 shadow-[0_0_18px_rgba(0,255,102,.08)] backdrop-blur">
                    <a href="{{ route('language.switch', 'en') }}" class="rounded-xl px-3 py-2 text-xs font-black {{ app()->getLocale() === 'en' ? 'bg-[#00ff66]/15 text-[#39ff14]' : 'text-[#70877b] hover:text-white' }}">EN</a>
                    <a href="{{ route('language.switch', 'sw') }}" class="rounded-xl px-3 py-2 text-xs font-black {{ app()->getLocale() === 'sw' ? 'bg-[#00ff66]/15 text-[#39ff14]' : 'text-[#70877b] hover:text-white' }}">SW</a>
                </div>
            </div>

            <div class="w-full max-w-md">
                <a href="{{ url('/') }}" class="mb-7 flex items-center gap-3 text-white lg:hidden">
                    <span class="flex h-10 w-10 items-center justify-center rounded-2xl border border-[#00ff66]/60 bg-[#00ff66]/10 font-black text-[#39ff14]">OS</span>
                    <span class="font-extrabold">Online Services</span>
                </a>
                <div class="neon-login-card p-6 sm:p-8">
                    <div class="mb-7">
                        <span class="neon-status">{{ request()->routeIs('register') ? __('Create your account') : __('Secure sign in') }}</span>
                        <h2 class="mt-4 text-2xl font-black tracking-tight text-white sm:text-3xl">{{ request()->routeIs('register') ? __('Get started with Online Services') : __('Welcome back') }}</h2>
                        <p class="mt-2 text-sm leading-6 text-[#8fa79a]">{{ request()->routeIs('register') ? __('Create your account to apply for services and track your applications in one place.') : __('Sign in to continue to your secure service dashboard.') }}</p>
                    </div>
                    {{ $slot }}
                </div>
                <a href="{{ url('/') }}" class="mt-5 block text-center text-sm font-bold text-[#71867b] transition hover:text-[#39ff14]">← Back to Online Services</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>