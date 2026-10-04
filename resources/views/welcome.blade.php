<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Services</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'], absolute: false)
</head>
<body class="min-h-screen bg-slate-50 text-slate-800">
    <main class="min-h-screen flex items-center justify-center px-6 py-12">
        <section class="w-full max-w-5xl">
            <div class="bg-white rounded-3xl shadow-xl border border-slate-200 overflow-hidden">
                <div class="p-8 sm:p-12 text-center">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-50 text-blue-700 text-sm font-semibold">
                        <span>✓</span> Secure Online Services
                    </div>

                    <h1 class="mt-6 text-4xl sm:text-6xl font-extrabold tracking-tight text-slate-900">
                        Online Services
                    </h1>

                    <p class="mt-5 max-w-2xl mx-auto text-lg text-slate-600 leading-8">
                        Apply for services online, upload required documents, and track your application progress from one simple platform.
                    </p>

                    <div class="mt-8 flex flex-col sm:flex-row justify-center gap-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="px-7 py-3 rounded-xl bg-blue-600 text-white font-bold hover:bg-blue-700 transition">
                                Go to Dashboard
                            </a>
                            <a href="{{ route('services.index') }}" class="px-7 py-3 rounded-xl border border-slate-300 bg-white text-slate-700 font-bold hover:bg-slate-50 transition">
                                Browse Services
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="px-7 py-3 rounded-xl bg-blue-600 text-white font-bold hover:bg-blue-700 transition">
                                Create Account
                            </a>
                            <a href="{{ route('login') }}" class="px-7 py-3 rounded-xl border border-slate-300 bg-white text-slate-700 font-bold hover:bg-slate-50 transition">
                                Sign In
                            </a>
                        @endauth
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 border-t border-slate-200">
                    <div class="p-6 text-center">
                        <div class="text-2xl">📋</div>
                        <h2 class="mt-3 font-bold">Apply Online</h2>
                        <p class="mt-1 text-sm text-slate-500">Start applications without unnecessary paperwork.</p>
                    </div>
                    <div class="p-6 text-center border-t md:border-t-0 md:border-l md:border-r border-slate-200">
                        <div class="text-2xl">📎</div>
                        <h2 class="mt-3 font-bold">Upload Documents</h2>
                        <p class="mt-1 text-sm text-slate-500">Submit clear PDF or image documents securely.</p>
                    </div>
                    <div class="p-6 text-center border-t md:border-t-0 border-slate-200">
                        <div class="text-2xl">🔎</div>
                        <h2 class="mt-3 font-bold">Track Progress</h2>
                        <p class="mt-1 text-sm text-slate-500">Follow pending, processing, completed or rejected applications.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
