<x-app-layout>
    @php @endphp
<x-slot name="header">
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-xs font-extrabold uppercase tracking-wider text-blue-700">{{ __('Service catalogue') }}</p>
            <h2 class="text-2xl font-black tracking-tight text-slate-900">{{ __('Find a service') }}</h2>
        </div>
        <span class="text-sm text-slate-500">{{ $services->count() }} {{ __('available services') }}</span>
    </div>
</x-slot>

<div class="min-h-screen bg-slate-50 page-enter" x-data="serviceSearch()">
    <div class="mx-auto w-full max-w-7xl px-3 py-6 sm:px-6 sm:py-8 lg:px-8">
        @if(session('success'))
            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold text-emerald-800 fade-up">
                {{ session('success') }}
            </div>
        @endif

        <div class="relative overflow-hidden rounded-[12px] bg-slate-950 p-5 text-white shadow-[0_12px_35px_rgba(15,23,42,0.12)] sm:p-8 lg:p-10">
            <div class="pointer-events-none absolute -right-16 -top-20 h-64 w-64 rounded-full bg-blue-600/20 blur-3xl"></div>
            <div class="relative">
                <p class="text-sm font-extrabold uppercase tracking-wider text-blue-300">Online Services</p>
                <h1 class="mt-2 text-2xl font-black tracking-tight sm:text-4xl">{{ __('What service do you need?') }}</h1>
                <p class="mt-3 max-w-2xl leading-7 text-slate-300">{{ __('Choose a service below. Review its requirements and fees before starting your application.') }}</p>

                <div class="mt-6 flex max-w-xl items-center gap-3 rounded-[12px] bg-white px-4 py-3 text-slate-400 shadow-[0_8px_24px_rgba(15,23,42,0.12)]">
                    <span class="text-lg">⌕</span>
                    <input x-model="query" @input="filter()" type="search" autocomplete="off"
                        placeholder="{{ __('Search services...') }}"
                        class="w-full border-0 p-0 text-sm text-slate-900 outline-none focus:ring-0">
                    <button type="button" x-show="query" x-cloak @click="query=''; filter()"
                        class="rounded-lg px-2 py-1 text-xs font-bold text-slate-500 hover:bg-slate-100">{{ __('Clear') }}</button>
                </div>
            </div>
        </div>

        <div id="service-grid" class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($services as $service)
                <article class="service-card portal-card flex flex-col overflow-hidden p-0"
                    data-name="{{ strtolower($service->name) }}">
                    <a href="{{ route('applications.create',$service) }}" class="group block">
                        <div class="service-card-image-wrap">
                            <img
                                src="{{ $service->image_url ?: \App\Support\ServiceImage::fallbackDataUri($service) }}"
                                data-service-fallback="{{ \App\Support\ServiceImage::fallbackDataUri($service) }}"
                                alt="{{ $service->name }}"
                                class="service-card-image"
                                loading="lazy"
                                referrerpolicy="no-referrer"
                                onerror="this.onerror=null;this.src=this.dataset.serviceFallback;"
                            >
                            <span class="service-card-availability">{{ __('Available') }}</span>
                        </div>
                        <div class="flex flex-col p-5">
                            <div class="flex items-start justify-between gap-3">
                                <h3 class="min-w-0 flex-1 text-lg font-extrabold text-slate-900 group-hover:text-blue-700">{{ $service->name }}</h3>
                                <span class="shrink-0 text-xs font-bold text-slate-400">{{ $service->active_documents_count }} {{ __('requirements') }}</span>
                            </div>
                            <p class="mt-2 min-h-[48px] flex-1 text-sm leading-6 text-slate-500">{{ $service->description }}</p>
                            <div class="mt-5 grid grid-cols-2 gap-3 border-t border-slate-100 pt-4 text-sm">
                                <div><span class="block text-xs text-slate-400">{{ __('Requirements') }}</span><strong>{{ $service->active_documents_count }}</strong></div>
                                <div><span class="block text-xs text-slate-400">{{ __('Service fee') }}</span><strong>TSh {{ number_format($service->service_fee,0) }}</strong></div>
                            </div>
                            <span class="portal-button mt-5 w-full text-center">{{ __('View requirements & Apply →') }}</span>
                        </div>
                    </a>
                </article>
            @empty
                <div class="col-span-full rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center text-slate-500">No services are currently available.</div>
            @endforelse

            <div data-search-empty class="hidden col-span-full rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center">
                <div class="text-3xl">🔎</div>
                <h3 class="mt-3 font-black text-slate-900">{{ __('No service found') }}</h3>
                <p class="mt-1 text-sm text-slate-500">{{ __('Try another search term.') }}</p>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
