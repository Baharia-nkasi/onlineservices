<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Online Services') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
                        <span class="neon-status">Authentication</span>
                        <h2 class="mt-4 text-2xl font-black tracking-tight text-white sm:text-3xl">Welcome back</h2>
                        <p class="mt-2 text-sm leading-6 text-[#8fa79a]">Sign in to continue to your secure service dashboard.</p>
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