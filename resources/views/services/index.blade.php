<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-extrabold uppercase tracking-wider text-blue-700">{{ __('Service catalogue') }}</p>
                <h2 class="text-2xl font-black tracking-tight text-slate-900">{{ __('Find a service') }}</h2>
            </div>
            <span class="text-sm text-slate-500">
                {{ $services->count() }} {{ __('available services') }}
            </span>
        </div>
    </x-slot>

    <div class="service-catalogue-page page-enter" x-data="serviceSearch()">
        <div class="service-catalogue-container">
            @if (session('success'))
                <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold text-emerald-800 fade-up" role="status">
                    {{ session('success') }}
                </div>
            @endif

            <section class="service-catalogue-hero" aria-labelledby="service-catalogue-heading">
                <div class="service-catalogue-hero-glow" aria-hidden="true"></div>
                <div class="service-catalogue-hero-content">
                    <p class="service-catalogue-eyebrow">Online Services</p>
                    <h1 id="service-catalogue-heading">{{ __('What service do you need?') }}</h1>
                    <p class="service-catalogue-intro">
                        {{ __('Choose a service below. Review its requirements and fees before starting your application.') }}
                    </p>

                    <div class="service-catalogue-search" role="search">
                        <label class="sr-only" for="service-search">{{ __('Search services...') }}</label>
                        <span class="service-catalogue-search-icon" aria-hidden="true">⌕</span>
                        <input
                            id="service-search"
                            x-model="query"
                            @input="filter()"
                            type="search"
                            autocomplete="off"
                            placeholder="{{ __('Search services...') }}"
                            aria-controls="service-grid"
                        >
                        <button
                            type="button"
                            x-show="query"
                            x-cloak
                            @click="query = ''; filter()"
                            class="service-catalogue-clear"
                        >{{ __('Clear') }}</button>
                    </div>
                </div>
            </section>

            <section class="service-catalogue-results" aria-label="{{ __('Available services') }}">
                <div id="service-grid" class="service-catalogue-grid">
                    @forelse ($services as $service)
                        <article class="service-card portal-card" data-name="{{ strtolower($service->name) }}">
                            <div class="service-card-image-wrap">
                                <img
                                    src="{{ $service->image_url ?: AppSupportServiceImage::fallbackDataUri($service) }}"
                                    data-service-fallback="{{ AppSupportServiceImage::fallbackDataUri($service) }}"
                                    alt="{{ $service->name }}"
                                    class="service-card-image"
                                    loading="lazy"
                                    referrerpolicy="no-referrer"
                                    onerror="this.onerror=null;this.src=this.dataset.serviceFallback;"
                                >
                                <span class="service-card-availability">{{ __('Available') }}</span>
                            </div>

                            <div class="service-card-content">
                                <div class="service-card-heading">
                                    <h3>{{ $service->name }}</h3>
                                    <span class="service-card-requirement-count">
                                        {{ $service->active_documents_count }} {{ __('requirements') }}
                                    </span>
                                </div>

                                <p class="service-card-description">{{ $service->description }}</p>

                                <dl class="service-card-meta">
                                    <div>
                                        <dt>{{ __('Requirements') }}</dt>
                                        <dd>{{ $service->active_documents_count }}</dd>
                                    </div>
                                    <div>
                                        <dt>{{ __('Service fee') }}</dt>
                                        <dd>TSh {{ number_format($service->service_fee, 0) }}</dd>
                                    </div>
                                </dl>

                                <a href="{{ route('applications.create', $service) }}" class="service-card-action">
                                    {{ __('View requirements & Apply') }} <span aria-hidden="true">→</span>
                                </a>
                            </div>
                        </article>
                    @empty
                        <div class="service-catalogue-empty">
                            <h3>{{ __('No services are currently available.') }}</h3>
                        </div>
                    @endforelse

                    <div data-search-empty class="service-catalogue-empty hidden" role="status" aria-live="polite">
                        <div class="text-3xl" aria-hidden="true">🔎</div>
                        <h3>{{ __('No service found') }}</h3>
                        <p>{{ __('Try another search term.') }}</p>
                    </div>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
