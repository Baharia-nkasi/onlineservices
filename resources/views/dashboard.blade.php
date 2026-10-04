<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-black uppercase tracking-[0.18em] text-blue-700">Customer Portal</p>
                <h2 class="text-2xl font-black tracking-tight text-slate-900">Your Dashboard</h2>
            </div>
            <a href="{{ route('services.index') }}" class="portal-button">+ Start an Application</a>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-b from-slate-50 to-slate-100 py-8">
        <div class="mx-auto max-w-7xl space-y-7 px-4 sm:px-6 lg:px-8">

            <section class="relative overflow-hidden rounded-3xl bg-slate-950 p-7 text-white shadow-2xl sm:p-10">
                <div class="absolute -right-20 -top-24 h-72 w-72 rounded-full bg-blue-600/30 blur-3xl"></div>
                <div class="absolute -bottom-28 left-1/3 h-64 w-64 rounded-full bg-cyan-500/10 blur-3xl"></div>
                <div class="relative flex flex-col justify-between gap-8 lg:flex-row lg:items-center">
                    <div class="max-w-2xl">
                        <span class="inline-flex rounded-full border border-blue-400/30 bg-blue-400/10 px-3 py-1.5 text-xs font-black uppercase tracking-wider text-blue-200">Online Services</span>
                        <h1 class="mt-4 text-3xl font-black tracking-tight sm:text-4xl">Welcome, {{ Auth::user()->name }} 👋</h1>
                        <p class="mt-3 text-base leading-7 text-slate-300">Manage your applications, upload documents, and follow every step from one simple portal.</p>
                        <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                            <a href="{{ route('services.index') }}" class="rounded-xl bg-white px-5 py-3 text-center text-sm font-black text-slate-950 hover:bg-slate-100">Explore Services →</a>
                            <a href="{{ route('customer.applications.index') }}" class="rounded-xl border border-white/20 bg-white/10 px-5 py-3 text-center text-sm font-black text-white hover:bg-white/15">Track Applications</a>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-4 lg:grid-cols-2">
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur"><div class="text-2xl font-black">{{ $totalApplications }}</div><div class="mt-1 text-xs text-slate-400">Applications</div></div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur"><div class="text-2xl font-black">{{ $pendingApplications }}</div><div class="mt-1 text-xs text-slate-400">Pending</div></div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur"><div class="text-2xl font-black">{{ $processingApplications }}</div><div class="mt-1 text-xs text-slate-400">Processing</div></div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur"><div class="text-2xl font-black">{{ $completedApplications }}</div><div class="mt-1 text-xs text-slate-400">Completed</div></div>
                    </div>
                </div>
            </section>

            <section>
                <div class="mb-4 flex items-end justify-between gap-4">
                    <div><p class="text-xs font-black uppercase tracking-[0.18em] text-blue-700">Quick Start</p><h3 class="mt-1 text-xl font-black">Popular services</h3></div>
                    <a href="{{ route('services.index') }}" class="text-sm font-black text-blue-700 hover:text-blue-900">View all →</a>
                </div>
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @forelse($services as $service)
                        <article class="group portal-card p-5 hover:-translate-y-1 hover:shadow-lg">
                            <div class="flex items-center justify-between">
                                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-lg">📄</span>
                                <span class="text-xs font-bold text-slate-400">{{ $service->documents->where('is_active', true)->count() }} requirements</span>
                            </div>
                            <h4 class="mt-4 font-black text-slate-900">{{ $service->name }}</h4>
                            <p class="mt-2 line-clamp-2 text-sm leading-6 text-slate-500">{{ $service->description }}</p>
                            <a href="{{ route('applications.create', $service) }}" class="mt-4 inline-flex text-sm font-black text-blue-700 group-hover:text-blue-900">View requirements →</a>
                        </article>
                    @empty
                        <div class="portal-card col-span-full p-10 text-center text-slate-500">No services are currently available.</div>
                    @endforelse
                </div>
            </section>

            <section class="portal-card overflow-hidden">
                <div class="flex flex-col gap-2 border-b border-slate-200 p-5 sm:flex-row sm:items-center sm:justify-between">
                    <div><h3 class="font-black text-slate-900">Recent Applications</h3><p class="text-sm text-slate-500">Your latest service requests.</p></div>
                    <a href="{{ route('customer.applications.index') }}" class="text-sm font-black text-blue-700">View all →</a>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse($recentApplications as $application)
                        @php
                            $badge = match($application->status) {
                                'pending' => 'bg-amber-50 text-amber-700',
                                'processing' => 'bg-blue-50 text-blue-700',
                                'completed' => 'bg-emerald-50 text-emerald-700',
                                'rejected' => 'bg-red-50 text-red-700',
                                default => 'bg-slate-100 text-slate-700',
                            };
                        @endphp
                        <div class="flex flex-col gap-3 p-5 sm:flex-row sm:items-center sm:justify-between">
                            <div class="flex min-w-0 items-center gap-3">
                                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-100">📋</span>
                                <div class="min-w-0"><p class="truncate font-bold">{{ $application->service->name }}</p><p class="text-xs text-slate-400">Application #{{ $application->id }} · {{ $application->created_at->format('d M Y, H:i') }}</p></div>
                            </div>
                            <div class="flex items-center justify-between gap-4 sm:justify-end">
                                <span class="status-pill {{ $badge }}">{{ ucfirst($application->status) }}</span>
                                <a href="{{ route('customer.applications.show', $application) }}" class="text-sm font-black text-blue-700">Details →</a>
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center"><div class="text-4xl">📋</div><h4 class="mt-3 font-black">No applications yet</h4><p class="mt-1 text-sm text-slate-500">Choose a service above to start your first application.</p></div>
                    @endforelse
                </div>
            </section>

            <section class="grid gap-4 sm:grid-cols-3">
                <div class="portal-card p-5"><span class="text-2xl">🔎</span><h4 class="mt-3 font-black">Clear requirements</h4><p class="mt-1 text-sm leading-6 text-slate-500">Know what documents you need before applying.</p></div>
                <div class="portal-card p-5"><span class="text-2xl">🔐</span><h4 class="mt-3 font-black">Secure account</h4><p class="mt-1 text-sm leading-6 text-slate-500">Your applications stay connected to your account.</p></div>
                <div class="portal-card p-5"><span class="text-2xl">📈</span><h4 class="mt-3 font-black">Track progress</h4><p class="mt-1 text-sm leading-6 text-slate-500">Follow pending, processing, completed, or rejected requests.</p></div>
            </section>
        </div>
    </div>
</x-app-layout>
