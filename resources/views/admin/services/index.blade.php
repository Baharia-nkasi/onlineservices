<x-app-layout>
    @php @endphp
<x-slot name="header">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-xs font-extrabold uppercase tracking-wider text-blue-700">{{ __('Management') }}</p>
            <h2 class="text-2xl font-black tracking-tight text-slate-900">{{ __('Manage Services') }}</h2>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="portal-button-secondary">← {{ __('Admin Dashboard') }}</a>
    </div>
</x-slot>

<div class="min-h-screen bg-slate-50 page-enter">
    <div class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold text-emerald-800">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                <ul class="list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <section class="admin-management-card admin-add-service-card p-5 sm:p-6 lg:p-7">
            <div class="admin-management-heading">
                <div class="admin-management-icon" aria-hidden="true">+</div>
                <div class="min-w-0">
                    <p class="admin-management-kicker">{{ __('Service catalogue') }}</p>
                    <h3 class="text-xl font-black tracking-tight text-slate-900 sm:text-2xl">{{ __('Add New Service') }}</h3>
                    <p class="mt-1 max-w-2xl text-sm leading-6 text-slate-500">{{ __('Create a service that customers can request from the Services catalogue.') }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.services.store') }}" class="admin-service-form mt-6">
                @csrf
                <div><label for="name" class="block text-sm font-extrabold text-slate-700">{{ __('Service name') }}</label><input id="name" name="name" value="{{ old('name') }}" required maxlength="255" class="admin-management-input mt-2 w-full" placeholder="{{ __('e.g. Passport Application') }}"></div>
                <div><label for="slug" class="block text-sm font-extrabold text-slate-700">{{ __('Slug') }}</label><input id="slug" name="slug" value="{{ old('slug') }}" required maxlength="255" pattern="[A-Za-z0-9_-]+" class="admin-management-input mt-2 w-full" placeholder="passport-application"></div>
                <div class="md:col-span-2"><label for="description" class="block text-sm font-extrabold text-slate-700">{{ __('Description') }}</label><textarea id="description" name="description" rows="3" maxlength="5000" class="admin-management-input mt-2 w-full">{{ old('description') }}</textarea></div>
                <div class="md:col-span-2"><label for="image_url" class="block text-sm font-extrabold text-slate-700">{{ __('Service image URL') }}</label><input id="image_url" type="url" name="image_url" value="{{ old('image_url') }}" maxlength="2048" class="admin-management-input mt-2 w-full" placeholder="https://example.com/service-image.jpg"><p class="mt-1 text-xs text-slate-500">{{ __('Use a direct HTTPS image URL. The customer dashboard will show this image on the service card.') }}</p></div>
                <div><label for="government_fee" class="block text-sm font-extrabold text-slate-700">{{ __('Government fee (TSh)') }}</label><input id="government_fee" type="number" min="0" step="0.01" name="government_fee" value="{{ old('government_fee', 0) }}" required class="admin-management-input mt-2 w-full"></div>
                <div><label for="service_fee" class="block text-sm font-extrabold text-slate-700">{{ __('Service fee (TSh)') }}</label><input id="service_fee" type="number" min="0" step="0.01" name="service_fee" value="{{ old('service_fee', 0) }}" required class="admin-management-input mt-2 w-full"></div>
                <div class="admin-service-form-footer md:col-span-2">
                    <label class="admin-check-row"><input type="hidden" name="is_active" value="0"><input type="checkbox" name="is_active" value="1" checked class="rounded border-slate-300 text-blue-700 focus:ring-blue-500">{{ __('Make service available to customers now') }}</label>
                    <button type="submit" class="portal-button admin-add-service-button !w-auto">
                        <span class="admin-button-plus" aria-hidden="true">+</span>
                        <span>{{ __('Add Service') }}</span>
                    </button>
                </div>
            </form>
        </section>

        <section class="portal-card admin-services-panel overflow-hidden">
            <div class="admin-services-toolbar border-b border-slate-200 p-5 sm:p-6">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <h3 class="text-xl font-black text-slate-900">{{ __('Active Services') }}</h3>
                        <p class="mt-1 text-sm text-slate-500">{{ __('Only active services appear here and in document requirements management. Deactivated services with customer history are kept safely in the database.') }}</p>
                        <form method="POST" action="{{ route('admin.services.purge-deactivated') }}" class="mt-3" onsubmit="return confirm('{{ __('Permanently delete all deactivated services that have no customer applications? Services with application history will be kept. This cannot be undone.') }}');">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-2 rounded-xl border border-red-200 bg-red-50 px-4 py-2 text-sm font-extrabold text-red-700 transition hover:bg-red-100">
                                <span aria-hidden="true">🗑</span>
                                {{ __('Delete unused deactivated services') }}
                            </button>
                        </form>
                    </div>

                    <form method="GET" action="{{ route('admin.services.index') }}" class="w-full lg:max-w-xl" role="search">
                        <label for="service-search" class="sr-only">{{ __('Search services') }}</label>
                        <div class="flex flex-col gap-2 sm:flex-row">
                            <div class="relative flex-1">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400" aria-hidden="true">⌕</span>
                                <input
                                    id="service-search"
                                    type="search"
                                    name="q"
                                    value="{{ $search ?? '' }}"
                                    maxlength="100"
                                    autocomplete="off"
                                    class="admin-management-input w-full pl-11 pr-4"
                                    placeholder="{{ __('Search service name, slug or description...') }}"
                                >
                            </div>
                            <button type="submit" class="portal-button !w-auto whitespace-nowrap">
                                {{ __('Search') }}
                            </button>
                            @if(!empty($search))
                                <a href="{{ route('admin.services.index') }}" class="portal-button-secondary !w-auto whitespace-nowrap">
                                    {{ __('Clear') }}
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                @if(!empty($search))
                    <div class="mt-4 flex flex-wrap items-center gap-2 text-sm">
                        <span class="rounded-full bg-blue-50 px-3 py-1.5 font-bold text-blue-800">
                            {{ $services->count() }} {{ __('matching service(s)') }}
                        </span>
                        <span class="text-slate-500">
                            {{ __('Search results for') }} “{{ $search }}”
                        </span>
                    </div>
                @endif
            </div>
            <div id="service-management-carousel" class="admin-services-stage p-3 sm:p-5 lg:p-6">
                @forelse($services as $service)
                    <article data-service-step class="admin-management-step admin-service-card w-full p-4 sm:p-5 lg:p-6">
                        <div class="mb-5 overflow-hidden rounded-[20px] border border-slate-200 bg-slate-100">
                            <img
                                src="{{ $service->image_url ?: \App\Support\ServiceImage::fallbackDataUri($service) }}"
                                data-service-fallback="{{ \App\Support\ServiceImage::fallbackDataUri($service) }}"
                                alt="{{ $service->name }}"
                                class="h-48 w-full object-cover sm:h-56"
                                loading="lazy"
                                referrerpolicy="no-referrer"
                                onerror="this.onerror=null;this.src=this.dataset.serviceFallback;"
                            >
                            <div class="flex items-center justify-between gap-3 border-t border-slate-200 bg-white px-4 py-3">
                                <span class="text-xs font-extrabold uppercase tracking-wider text-slate-500">{{ __('Service image preview') }}</span>
                                <span class="text-xs font-bold text-slate-400">{{ __('External image + embedded fallback') }}</span>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('admin.services.update', $service) }}" class="admin-service-edit-form">
                            @csrf @method('PATCH')
                            <div class="admin-field admin-field-service"><label class="text-xs font-extrabold uppercase tracking-wide text-slate-400">{{ __('Service') }}</label><input name="name" value="{{ $service->name }}" required maxlength="255" class="admin-management-input mt-2 w-full"></div>
                            <div class="admin-field admin-field-slug"><label class="text-xs font-extrabold uppercase tracking-wide text-slate-400">{{ __('Slug') }}</label><input name="slug" value="{{ $service->slug }}" required maxlength="255" pattern="[A-Za-z0-9_-]+" class="admin-management-input mt-2 w-full"></div>
                            <div class="admin-field admin-field-description"><label class="text-xs font-extrabold uppercase tracking-wide text-slate-400">{{ __('Description') }}</label><input name="description" value="{{ $service->description }}" maxlength="5000" class="admin-management-input mt-2 w-full"></div>
                            <div class="admin-service-pricing-grid">
                                <div class="admin-field admin-field-image">
                                    <label class="text-xs font-extrabold uppercase tracking-wide text-slate-400">{{ __('Service image URL') }}</label>
                                    <input type="url" name="image_url" value="{{ $service->image_url }}" maxlength="2048" class="admin-management-input mt-2 w-full" placeholder="https://example.com/service-image.jpg">
                                    <p class="mt-1 text-xs text-slate-500">{{ __('If the remote image fails, the system automatically uses an embedded service-specific image.') }}</p>
                                </div>
                                <div class="admin-field admin-field-fee">
                                    <label class="text-xs font-extrabold uppercase tracking-wide text-slate-400">{{ __('Government fee (TSh)') }}</label>
                                    <input type="number" min="0" step="0.01" name="government_fee" value="{{ $service->government_fee }}" required class="admin-management-input mt-2 w-full">
                                </div>
                                <div class="admin-field admin-field-fee">
                                    <label class="text-xs font-extrabold uppercase tracking-wide text-slate-400">{{ __('Service fee (TSh)') }}</label>
                                    <input type="number" min="0" step="0.01" name="service_fee" value="{{ $service->service_fee }}" required class="admin-management-input mt-2 w-full">
                                </div>
                            </div>
                            <div class="admin-service-status"><label class="flex items-center gap-2 pb-3 text-sm font-bold {{ $service->is_active ? 'text-emerald-700' : 'text-slate-500' }}"><input type="hidden" name="is_active" value="0"><input type="checkbox" name="is_active" value="1" @checked($service->is_active) class="rounded border-slate-300 text-blue-700 focus:ring-blue-500">{{ $service->is_active ? __('Active') : __('Deactivated') }}</label></div>
                            <div class="admin-service-actions"><span class="rounded-xl bg-slate-100 px-3 py-2 text-xs font-bold text-slate-600">{{ $service->applications_count }} {{ __('applications') }}</span><span class="rounded-xl bg-slate-100 px-3 py-2 text-xs font-bold text-slate-600">{{ $service->active_documents_count }} {{ __('active requirements') }}</span><button type="submit" class="portal-button">{{ __('Save Changes') }}</button><a href="{{ route('admin.services.show', $service) }}" class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 text-sm font-extrabold text-white shadow-lg shadow-slate-900/20 transition-all duration-200 hover:-translate-y-0.5 hover:bg-slate-800 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-slate-700 focus:ring-offset-2">{{ __('Manage Service') }} →</a></div>
                        </form>
                        <details class="admin-document-panel mt-5">
                            <summary class="admin-document-summary">
                                <span class="admin-document-summary-icon" aria-hidden="true"><span class="admin-document-plus">+</span></span>
                                <span class="admin-document-summary-copy"><strong>{{ __('Manage document requirements') }}</strong><span class="admin-document-count"><span class="admin-document-count-dot" aria-hidden="true"></span>{{ $service->active_documents_count }} {{ __('active requirement(s)') }}</span></span>
                                <span class="admin-document-chevron" aria-hidden="true">⌄</span>
                            </summary>
                            <div class="mt-4 space-y-4">
                                <form method="POST" action="{{ route('admin.services.documents.store', $service) }}" class="admin-document-form">
                                    @csrf
                                    <input name="name" required maxlength="255" class="admin-management-input" placeholder="{{ __('Document name') }}">
                                    <input name="description" maxlength="2000" class="admin-management-input" placeholder="{{ __('Description') }}">
                                    <select name="requirement_type" class="admin-management-input">
                                        <option value="single">{{ __('Single requirement') }}</option>
                                        <option value="choose_one">{{ __('Choose one from group') }}</option>
                                        <option value="choose_many">{{ __('Choose many from group') }}</option>
                                    </select>
                                    <input name="requirement_group" maxlength="100" pattern="[A-Za-z0-9_-]+" class="admin-management-input" placeholder="{{ __('Group (optional, e.g. identity)') }}">
                                    <input type="number" name="minimum_required" value="1" min="1" max="100" class="admin-management-input" placeholder="{{ __('Minimum required') }}">
                                    <input type="number" name="sort_order" value="0" min="0" max="10000" class="admin-management-input" placeholder="{{ __('Sort order') }}">
                                    <label class="flex items-center gap-2 text-sm font-bold"><input type="hidden" name="is_required" value="0"><input type="checkbox" name="is_required" value="1" checked>{{ __('Required') }}</label>
                                    <label class="flex items-center gap-2 text-sm font-bold"><input type="hidden" name="is_active" value="0"><input type="checkbox" name="is_active" value="1" checked>{{ __('Active') }}</label>
                                    <button type="submit" class="portal-button admin-document-add-button !w-auto"><span class="admin-button-plus" aria-hidden="true">+</span><span>{{ __('Add Requirement') }}</span></button>
                                </form>
                                <div class="space-y-3">
                                    @forelse($service->documents as $document)
                                        <form method="POST" action="{{ route('admin.service-documents.update', $document) }}" class="admin-document-row">
                                            @csrf @method('PATCH')
                                            <input name="name" value="{{ $document->name }}" required maxlength="255" class="input-modern md:col-span-3">
                                            <input name="description" value="{{ $document->description }}" maxlength="2000" class="input-modern md:col-span-3">
                                            <select name="requirement_type" class="input-modern md:col-span-2">
                                                <option value="single" @selected($document->requirement_type === 'single')>{{ __('Single') }}</option>
                                                <option value="choose_one" @selected($document->requirement_type === 'choose_one')>{{ __('Choose one') }}</option>
                                                <option value="choose_many" @selected($document->requirement_type === 'choose_many')>{{ __('Choose many') }}</option>
                                            </select>
                                            <input name="requirement_group" value="{{ $document->requirement_group }}" maxlength="100" pattern="[A-Za-z0-9_-]+" class="input-modern md:col-span-2" placeholder="{{ __('Group') }}">
                                            <input type="number" name="minimum_required" value="{{ $document->minimum_required }}" min="1" max="100" class="admin-management-input">
                                            <input type="number" name="sort_order" value="{{ $document->sort_order }}" min="0" max="10000" class="admin-management-input">
                                            <div class="flex items-center gap-3 md:col-span-8">
                                                <label class="text-xs font-bold"><input type="hidden" name="is_required" value="0"><input type="checkbox" name="is_required" value="1" @checked($document->is_required)> {{ __('Required') }}</label>
                                                <label class="text-xs font-bold"><input type="hidden" name="is_active" value="0"><input type="checkbox" name="is_active" value="1" @checked($document->is_active)> {{ __('Active') }}</label>
                                                <button type="submit" class="portal-button">{{ __('Save') }}</button>
                                            </div>
                                        </form>
                                        <div class="admin-document-delete-row mt-2 flex justify-end">
                                            <form method="POST" action="{{ route('admin.service-documents.destroy', $document) }}" onsubmit="return confirm('{{ __('Delete this document requirement permanently? This cannot be undone.') }}');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="rounded-xl border border-red-200 bg-red-50 px-3 py-2 text-xs font-extrabold text-red-700 hover:bg-red-100">
                                                    {{ __('Delete Requirement') }}
                                                </button>
                                            </form>
                                        </div>
                                    @empty
                                        <p class="text-sm text-slate-500">{{ __('No document requirements configured.') }}</p>
                                    @endforelse
                                </div>
                            </div>
                        </details>

                        <div class="admin-service-footer mt-4">
                            <p class="text-xs text-slate-500">@if($service->is_active){{ __('Active services are visible to customers and can receive new requests.') }}@else{{ __('Deactivated services are hidden from new customer requests. Existing applications are preserved.') }}@endif</p>
                            <div class="flex flex-wrap items-center justify-end gap-2">
                                <form method="POST" action="{{ route('admin.services.destroy', $service) }}" onsubmit="return confirm('{{ __('PERMANENTLY DELETE this service, all document requirements, all customer applications and related history? This cannot be undone.') }}');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl border border-red-300 bg-red-50 px-4 py-2.5 text-sm font-extrabold text-red-700 shadow-sm transition hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                        <span aria-hidden="true">🗑</span>
                                        {{ __('Delete Service Permanently') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="p-12 text-center" role="status" aria-live="polite">
                        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-50 text-3xl font-black text-emerald-700" aria-hidden="true">⌕</div>
                        @if(!empty($search))
                            <h4 class="text-base font-extrabold text-slate-800">{{ __('No matching services found') }}</h4>
                            <p class="mx-auto mt-2 max-w-lg text-sm text-slate-500">{{ __('Try another service name, slug or description, or clear the search to see all active services.') }}</p>
                            <a href="{{ route('admin.services.index') }}" class="portal-button-secondary mt-4 !w-auto">{{ __('Clear search') }}</a>
                        @else
                            <h4 class="text-base font-extrabold text-slate-800">{{ __('No active services found') }}</h4>
                            <p class="mx-auto mt-2 max-w-lg text-sm text-slate-500">{{ __('Deactivated services are hidden from this list. Add a new service above or activate an existing service to make it available here.') }}</p>
                        @endif
                    </div>
                @endforelse
            </div>
            @if($services->count() > 0)
                <nav id="admin-services-pagination" class="admin-services-pagination" aria-label="{{ __('Service navigation') }}">
                    <button type="button" id="admin-service-previous" class="admin-pagination-button" disabled>← {{ __('Previous') }}</button>
                    <span id="admin-service-page-indicator" class="admin-service-page-indicator" aria-live="polite">{{ __('Page') }} 1 {{ __('of') }} {{ $services->count() }}</span>
                    <button type="button" id="admin-service-next" class="admin-pagination-button">{{ __('Next') }} →</button>
                </nav>
            @endif
        </section>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const search = document.getElementById('service-search');
    search?.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && search.value) {
            search.value = '';
            search.form?.submit();
        }
    });

    const stage = document.getElementById('service-management-carousel');
    const pagination = document.getElementById('admin-services-pagination');
    if (!stage || !pagination) return;

    const cards = Array.from(stage.querySelectorAll('[data-service-step]'));
    if (!cards.length) {
        pagination.hidden = true;
        return;
    }

    // Build the client-side data list from actual Laravel records, not demo placeholders.
    // Each entry retains its original edit form, CSRF token, route, and document forms.
    const servicesData = cards.map((element) => {
        const form = element.querySelector('.admin-service-edit-form');
        const field = (name) => form?.elements.namedItem(name);
        const value = (name) => field(name)?.value ?? '';
        const image = element.querySelector('[data-service-fallback]');
        const badges = Array.from(element.querySelectorAll('.admin-service-actions > span'));
        return {
            serviceName: value('name'),
            slug: value('slug'),
            description: value('description'),
            imageUrl: value('image_url') || image?.getAttribute('src') || '',
            governmentFee: value('government_fee'),
            serviceFee: value('service_fee'),
            applicationsCount: badges[0]?.textContent.trim() ?? '0 applications',
            activeRequirements: badges[1]?.textContent.trim() ?? '0 active requirements',
            isActive: Boolean(field('is_active')?.checked),
            element
        };
    });

    let currentPage = 0;
    const previousButton = document.getElementById('admin-service-previous');
    const nextButton = document.getElementById('admin-service-next');
    const pageIndicator = document.getElementById('admin-service-page-indicator');

    function renderService(pageNumber) {
        currentPage = Math.max(0, Math.min(pageNumber, servicesData.length - 1));
        servicesData.forEach((service, index) => {
            const isCurrent = index === currentPage;
            service.element.hidden = !isCurrent;
            service.element.setAttribute('aria-hidden', String(!isCurrent));
        });
        previousButton.disabled = currentPage === 0;
        nextButton.disabled = currentPage === servicesData.length - 1;
        pageIndicator.textContent = '{{ __('Page') }} ' + (currentPage + 1) + ' {{ __('of') }} ' + servicesData.length;
        pagination.setAttribute('aria-label', '{{ __('Service navigation') }}: ' + (currentPage + 1) + ' {{ __('of') }} ' + servicesData.length);
    }

    previousButton.addEventListener('click', () => renderService(currentPage - 1));
    nextButton.addEventListener('click', () => renderService(currentPage + 1));
    renderService(0);
});
</script>

</x-app-layout>
