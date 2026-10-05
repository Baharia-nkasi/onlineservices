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
                        <div class="p-12 text-center"><div class="text-4xl">📋</div><h4 class="mt-3 font-black">{{ __('No applications yet') }}</h4><p class="mt-1 text-sm text-slate-500">{{ __('Choose a service above to start your first application.') }}</p></div>
                    @endforelse
                </div>
            </section>

            <section class="grid gap-4 lg:grid-cols-2">
                <div class="portal-card overflow-hidden">
                    <div class="border-b border-slate-200 p-5">
                        <h3 class="font-black text-slate-900">{{ __('Service Updates') }}</h3>
                        <p class="mt-1 text-sm text-slate-500">{{ __('Important updates about your application and documents.') }}</p>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @forelse($recentCompletedApplications as $application)
                            <div class="p-5">
                                <div class="flex items-start gap-3">
                                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700">✓</span>
                                    <div class="min-w-0">
                                        <p class="font-black text-emerald-800">{{ __('Your application has been completed') }}</p>
                                        <p class="mt-1 text-sm text-slate-600">{{ $application->service->name }} — {{ __('The service work has been completed successfully.') }}</p>
                                        <p class="mt-2 text-xs font-semibold text-slate-500">{{ __('Keep your original identification and any documents related to this service for collection or future verification.') }}</p>
                                        <a href="{{ route('customer.applications.show', $application) }}" class="mt-3 inline-flex text-sm font-black text-blue-700">{{ __('View application and documents →') }}</a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-5 text-sm text-slate-500">{{ __('No completed service updates yet.') }}</div>
                        @endforelse

                        @foreach($recentRejectedApplications as $application)
                            <div class="p-5">
                                <div class="flex items-start gap-3">
                                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-red-50 text-red-700">!</span>
                                    <div class="min-w-0">
                                        <p class="font-black text-red-800">{{ __('Application requires your attention') }}</p>
                                        <p class="mt-1 text-sm text-slate-600">{{ $application->service->name }} — {{ __('Please open the application to read the review and provide any required changes.') }}</p>
                                        <a href="{{ route('customer.applications.show', $application) }}" class="mt-3 inline-flex text-sm font-black text-blue-700">{{ __('Review application →') }}</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="portal-card overflow-hidden">
                    <div class="border-b border-slate-200 p-5">
                        <h3 class="font-black text-slate-900">{{ __('Document Updates') }}</h3>
                        <p class="mt-1 text-sm text-slate-500">{{ __('Approved or rejected documents from admin review.') }}</p>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @forelse($recentDocumentUpdates as $document)
                            @php $approved = $document->status === 'approved'; @endphp
                            <div class="p-5">
                                <div class="flex items-start gap-3">
                                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $approved ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">{{ $approved ? '✓' : '!' }}</span>
                                    <div class="min-w-0">
                                        <p class="font-black {{ $approved ? 'text-emerald-800' : 'text-red-800' }}">{{ $approved ? __('Document approved') : __('Document rejected') }}</p>
                                        <p class="mt-1 text-sm text-slate-600">{{ $document->document_name }} — {{ $document->application->service->name }}</p>
                                        @if($document->notes)
                                            <p class="mt-2 rounded-xl bg-slate-50 px-3 py-2 text-xs leading-5 text-slate-600"><strong>{{ __('Admin note:') }}</strong> {{ $document->notes }}</p>
                                        @endif
                                        <a href="{{ route('customer.applications.show', $document->application) }}" class="mt-3 inline-flex text-sm font-black text-blue-700">{{ __('Open documents →') }}</a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-5 text-sm text-slate-500">{{ __('No document review updates yet.') }}</div>
                        @endforelse
                    </div>
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
