<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-black uppercase tracking-[0.18em] text-blue-700">{{ __('Customer Portal') }}</p>
                <h2 class="text-2xl font-black tracking-tight text-slate-900">{{ __('Your Dashboard') }}</h2>
            </div>
            <a href="{{ route('services.index') }}" class="portal-button">{{ __('+ Start an Application') }}</a>
        </div>
    </x-slot>

    <div class="dashboard-page page-enter">
        <div class="dashboard-container">

            <section class="dashboard-hero">
                <div class="absolute -right-20 -top-24 h-72 w-72 rounded-full bg-blue-600/30 blur-3xl"></div>
                <div class="absolute -bottom-28 left-1/3 h-64 w-64 rounded-full bg-cyan-500/10 blur-3xl"></div>
                <div class="dashboard-hero-content">
                    <div class="max-w-2xl">
                        <span class="inline-flex rounded-full border border-blue-400/30 bg-blue-400/10 px-3 py-1.5 text-xs font-black uppercase tracking-wider text-blue-200">{{ __('Online Services') }}</span>
                        <h1 class="dashboard-hero-title">{{ __('Welcome,') }} {{ Auth::user()->name }} 👋</h1>
                        <p class="dashboard-hero-text">{{ __('Manage your applications, upload documents, and follow every step from one simple portal.') }}</p>
                        <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                            <a href="{{ route('services.index') }}" class="dashboard-hero-action">{{ __('Explore Services →') }}</a>
                            <a href="{{ route('customer.applications.index') }}" class="dashboard-hero-action-secondary">{{ __('Track Applications') }}</a>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-4 lg:grid-cols-2">
                        <div class="dashboard-stat"><div class="dashboard-stat-value">{{ $totalApplications }}</div><div class="dashboard-stat-label">{{ __('Applications') }}</div></div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur"><div class="text-2xl font-black">{{ $pendingApplications }}</div><div class="mt-1 text-xs text-slate-400">{{ __('Pending') }}</div></div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur"><div class="text-2xl font-black">{{ $processingApplications }}</div><div class="mt-1 text-xs text-slate-400">{{ __('Processing') }}</div></div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur"><div class="text-2xl font-black">{{ $completedApplications }}</div><div class="mt-1 text-xs text-slate-400">{{ __('Completed') }}</div></div>
                    </div>
                </div>
            </section>

            <section>
                <div class="mb-4 flex items-end justify-between gap-4">
                    <div><p class="dashboard-kicker">{{ __('Quick Start') }}</p><h3 class="dashboard-section-title">{{ __('Popular services') }}</h3></div>
                    <a href="{{ route('services.index') }}" class="dashboard-link">{{ __('View all →') }}</a>
                </div>
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @forelse($services as $service)
                        <article class="dashboard-service-card group">
                            <div class="flex items-center justify-between">
                                <span class="dashboard-icon">📄</span>
                                <span class="text-xs font-bold text-slate-400">{{ $service->documents->where('is_active', true)->count() }} {{ __('requirements') }}</span>
                            </div>
                            <h4 class="mt-4 font-black text-slate-900">{{ $service->name }}</h4>
                            <p class="mt-2 line-clamp-2 text-sm leading-6 text-slate-500">{{ $service->description }}</p>
                            <a href="{{ route('applications.create', $service) }}" class="mt-4 inline-flex text-sm font-black text-blue-700 group-hover:text-blue-900">{{ __('View requirements →') }}</a>
                        </article>
                    @empty
                        <div class="portal-card col-span-full p-10 text-center text-slate-500">{{ __('No services are currently available.') }}</div>
                    @endforelse
                </div>
            </section>

            <section class="dashboard-recent">
                <div class="dashboard-recent-header">
                    <div><h3 class="font-black text-slate-900">{{ __('Recent Applications') }}</h3><p class="text-sm text-slate-500">{{ __('Your latest service requests.') }}</p></div>
                    <a href="{{ route('customer.applications.index') }}" class="text-sm font-black text-blue-700">{{ __('View all →') }}</a>
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
                        <div class="dashboard-recent-row">
                            <div class="flex min-w-0 items-center gap-3">
                                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-100">📋</span>
                                <div class="min-w-0"><p class="truncate font-bold">{{ $application->service->name }}</p><p class="text-xs text-slate-400">{{ __('Application') }} #{{ $application->id }} · {{ $application->created_at->format('d M Y, H:i') }}</p></div>
                            </div>
                            <div class="flex items-center justify-between gap-4 sm:justify-end">
                                <span class="status-pill {{ $badge }}">{{ __($application->status) }}</span>
                                <a href="{{ route('customer.applications.show', $application) }}" class="text-sm font-black text-blue-700">{{ __('Details →') }}</a>
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center"><div class="text-4xl">📋</div><h4 class="mt-3 font-black">{{ __('No active applications') }}</h4><p class="mt-1 text-sm text-slate-500">{{ __('Rejected applications are removed from this list. Choose a service above to start a new application from the beginning.') }}</p></div>
                    @endforelse
                </div>
            </section>

            <section class="mt-2 overflow-hidden rounded-[28px] border border-blue-200 bg-gradient-to-br from-slate-950 via-blue-950 to-indigo-900 p-5 shadow-xl sm:p-7">
                <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <span class="inline-flex items-center gap-2 rounded-full border border-cyan-300/20 bg-cyan-300/10 px-3 py-1.5 text-xs font-black uppercase tracking-wider text-cyan-200">☎ {{ __('Help Desk') }}</span>
                        <h3 class="mt-3 text-2xl font-black text-white">{{ __('Need help? Contact our admin') }}</h3>
                        <p class="mt-1 max-w-2xl text-sm leading-6 text-slate-300">{{ __('If you face any problem with your application, documents, payment or service, call one of the support numbers below.') }}</p>
                    </div>
                    <div class="text-xs font-bold text-slate-400">{{ $helpDeskContacts->count() }} {{ __('active contact(s)') }}</div>
                </div>

                <div class="mt-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                    @forelse($helpDeskContacts as $contact)
                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $contact->phone) }}" class="group rounded-2xl border border-white/10 bg-white/[0.07] p-4 backdrop-blur transition hover:-translate-y-1 hover:border-cyan-300/40 hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-cyan-300">
                            <div class="flex items-center gap-3">
                                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-white text-xl shadow-lg">📞</span>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <h4 class="truncate font-black text-white">{{ $contact->network }}</h4>
                                        <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                                    </div>
                                    <p class="mt-1 truncate text-sm font-bold text-cyan-200">{{ $contact->phone }}</p>
                                    @if($contact->label)<p class="mt-1 truncate text-xs text-slate-400">{{ $contact->label }}</p>@endif
                                </div>
                            </div>
                            <div class="mt-4 flex items-center justify-between border-t border-white/10 pt-3 text-xs font-black">
                                <span class="text-slate-400">{{ __('Tap to call') }}</span>
                                <span class="text-cyan-300 transition group-hover:translate-x-1">Call →</span>
                            </div>
                        </a>
                    @empty
                        <div class="sm:col-span-2 lg:col-span-3 rounded-2xl border border-white/10 bg-white/[0.05] p-6 text-center">
                            <div class="text-3xl">☎️</div>
                            <p class="mt-2 font-black text-white">{{ __('Help desk contacts are being prepared.') }}</p>
                            <p class="mt-1 text-sm text-slate-400">{{ __('Please check again later or use another available support channel.') }}</p>
                        </div>
                    @endforelse
                </div>
            </section>

            <section class="grid gap-4 sm:grid-cols-3">
                <div class="dashboard-help-card p-5"><span class="text-2xl">🔎</span><h4 class="mt-3 font-black">{{ __('Clear requirements') }}</h4><p class="mt-1 text-sm leading-6 text-slate-500">{{ __('Know what documents you need before applying.') }}</p></div>
                <div class="portal-card p-5"><span class="text-2xl">🔐</span><h4 class="mt-3 font-black">{{ __('Secure account') }}</h4><p class="mt-1 text-sm leading-6 text-slate-500">{{ __('Your applications stay connected to your account.') }}</p></div>
                <div class="portal-card p-5"><span class="text-2xl">📈</span><h4 class="mt-3 font-black">{{ __('Track progress') }}</h4><p class="mt-1 text-sm leading-6 text-slate-500">{{ __('Follow pending, processing, completed, or rejected requests.') }}</p></div>
            </section>
        </div>
    </div>
</x-app-layout>
