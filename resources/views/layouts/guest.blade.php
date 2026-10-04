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
<body class="min-h-screen bg-slate-950 font-sans antialiased text-slate-900">
<div class="min-h-screen grid lg:grid-cols-2">
    <div class="hidden lg:flex flex-col justify-between p-12 text-white bg-slate-950">
        <a href="{{ url('/') }}" class="flex items-center gap-3"><span class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-700 font-black">OS</span><span class="text-lg font-extrabold">Online Services</span></a>
        <div class="max-w-lg"><p class="text-sm font-extrabold uppercase tracking-wider text-blue-300">Secure service portal</p><h1 class="mt-4 text-5xl font-black leading-tight">Apply, upload and track your services in one place.</h1><p class="mt-6 text-lg leading-8 text-slate-300">A simple way to start service applications, submit requirements and follow progress online.</p></div>
        <p class="text-sm text-slate-500">© {{ date('Y') }} Online Services</p>
    </div>
    <div class="flex items-center justify-center bg-slate-50 px-5 py-10">
        <div class="w-full max-w-md">
            <a href="{{ url('/') }}" class="mb-8 flex items-center gap-3 lg:hidden"><span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-700 font-black text-white">OS</span><span class="font-extrabold">Online Services</span></a>
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xl sm:p-8">{{ $slot }}</div>
            <a href="{{ url('/') }}" class="mt-5 block text-center text-sm font-semibold text-slate-500 hover:text-blue-700">← Back to Online Services</a>
        </div>
    </div>
</div>
</body>
</html>