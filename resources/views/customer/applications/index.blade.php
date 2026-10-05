<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-extrabold uppercase tracking-wider text-blue-700">{{ __('Customer portal') }}</p>
            <h2 class="text-2xl font-black tracking-tight text-slate-900">{{ __('My Applications') }}</h2>
        </div>
    </x-slot>

    <div class="min-h-screen bg-slate-50 py-8 page-enter">
        <div class="mx-auto max-w-6xl space-y-6 px-4 sm:px-6 lg:px-8">
            <section class="relative overflow-hidden rounded-3xl bg-slate-950 p-7 text-white shadow-xl sm:p-9">
                <div class="pointer-events-none absolute -right-20 -top-24 h-72 w-72 rounded-full bg-blue-600/20 blur-3xl"></div>
                <div class="relative flex flex-col gap-5 sm:flex-row sm:{{ __('items') }}-end sm:justify-between">
                    <div>
                        <p class="text-sm font-extrabold uppercase tracking-wider text-blue-300">{{ __('Application center') }}</p>
                        <h1 class="mt-2 text-3xl font-black tracking-tight sm:text-4xl">{{ __('Track your applications') }}</h1>
                        <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-300">{{ __('Monitor progress, upload documents, and review updates for every service request in one place.') }}</p>
                    </div>
                    <a href="{{ route('services.index') }}" class="portal-button shrink-0">{{ __('Start New Application') }}</a>
                </div>
            </section>

            @if(session('success'))
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif

            <div class="space-y-4">
                @forelse($applications as $application)
                    @php
                        $badge = match($application->status) {
                            'pending' => 'bg-amber-100 text-amber-800',
                            'processing' => 'bg-blue-100 text-blue-800',
                            'completed' => 'bg-emerald-100 text-emerald-800',
                            'rejected' => 'bg-red-100 text-red-800',
                            default => 'bg-slate-100 text-slate-700',
                        };
                        ${{ __('uploaded') }} = $application->documents->count();
                        ${{ __('approved') }} = $application->documents->where('status', '{{ __('approved') }}')->count();
                    @endphp

                    <article class="portal-card overflow-hidden transition hover:-translate-y-0.5 hover:shadow-md">
                        <div class="p-5 sm:p-6">
                            <div class="flex flex-col gap-5 lg:flex-row lg:{{ __('items') }}-center lg:justify-between">
                                <div class="min-w-0">
                                    <div class="flex flex-wrap {{ __('items') }}-center gap-2">
                                        <span class="text-xs font-extrabold uppercase tracking-wider text-slate-400">{{ __('Application') }} #{{ $application->id }}</span>
                                        <span class="status-pill {{ $badge }}">{{ __($application->status) }}</span>
                                    </div>
                                    <h2 class="mt-2 truncate text-xl font-black text-slate-900">{{ $application->service->name }}</h2>
                                    <p class="mt-1 text-sm text-slate-500">{{ __('Submitted') }} {{ $application->created_at->format('d M Y, H:i') }}</p>
                                </div>

                                <div class="flex flex-wrap {{ __('items') }}-center gap-3">
                                    <div class="rounded-xl bg-slate-50 px-4 py-3 text-sm">
                                        <span class="font-black text-slate-900">{{ ${{ __('uploaded') }} }}</span>
                                        <span class="text-slate-500"> {{ __('uploaded') }}</span>
                                        @if(${{ __('approved') }})
                                            <span class="ml-2 font-black text-emerald-700">{{ ${{ __('approved') }} }} {{ __('approved') }}</span>
                                        @endif
                                    </div>
                                    <a href="{{ route('customer.applications.show', $application) }}" class="portal-button-secondary">{{ __('View Details →') }}</a>
                                </div>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="portal-card p-12 text-center">
                        <div class="mx-auto flex h-16 w-16 {{ __('items') }}-center justify-center rounded-2xl bg-blue-50 text-3xl">📋</div>
                        <h2 class="mt-5 text-xl font-black text-slate-900">{{ __('No applications yet') }}</h2>
                        <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">{{ __('Choose one of our services to start your first application. You can return here anytime to track its progress.') }}</p>
                        <a href="{{ route('services.index') }}" class="portal-button mt-6">{{ __('Browse Services') }}</a>
                    </div>
                @endforelse
            </div>

            @if($applications->hasPages())
                <div class="portal-card p-4">
                    {{ $applications->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
