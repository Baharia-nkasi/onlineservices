<x-app-layout>
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
                        <h3 class="text-xl font-black text-slate-900">{{ __('All Services') }}</h3>
                        <p class="mt-1 text-sm text-slate-500">{{ __('Activate, deactivate, edit or safely delete services.') }}</p>
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
                            {{ $services->total() }} {{ __('matching service(s)') }}
                        </span>
                        <span class="text-slate-500">
                            {{ __('Search results for') }} “{{ $search }}”
                        </span>
                    </div>
                @endif
            </div>
            <div id="service-management-carousel" class="admin-services-stage p-3 sm:p-5 lg:p-6">
                @forelse($services as $service)
                    <article data-service-step class="admin-management-step admin-service-card hidden w-full p-4 sm:p-5 lg:p-6">
                        <form method="POST" action="{{ route('admin.services.update', $service) }}" class="admin-service-edit-form">
                            @csrf @method('PATCH')
                            <div class="admin-field admin-field-service"><label class="text-xs font-extrabold uppercase tracking-wide text-slate-400">{{ __('Service') }}</label><input name="name" value="{{ $service->name }}" required maxlength="255" class="admin-management-input mt-2 w-full"></div>
                            <div class="admin-field admin-field-slug"><label class="text-xs font-extrabold uppercase tracking-wide text-slate-400">{{ __('Slug') }}</label><input name="slug" value="{{ $service->slug }}" required maxlength="255" pattern="[A-Za-z0-9_-]+" class="admin-management-input mt-2 w-full"></div>
                            <div class="admin-field admin-field-description"><label class="text-xs font-extrabold uppercase tracking-wide text-slate-400">{{ __('Description') }}</label><input name="description" value="{{ $service->description }}" maxlength="5000" class="admin-management-input mt-2 w-full"></div>
                            <div class="admin-field admin-field-fee"><label class="text-xs font-extrabold uppercase tracking-wide text-slate-400">{{ __('Government fee (TSh)') }}</label><input type="number" min="0" step="0.01" name="government_fee" value="{{ $service->government_fee }}" required class="admin-management-input mt-2 w-full"></div>
                            <div class="admin-field admin-field-fee"><label class="text-xs font-extrabold uppercase tracking-wide text-slate-400">{{ __('Service fee (TSh)') }}</label><input type="number" min="0" step="0.01" name="service_fee" value="{{ $service->service_fee }}" required class="admin-management-input mt-2 w-full"></div>
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
                            @if(!$service->is_active)
                                <form method="POST" action="{{ route('admin.services.destroy', $service) }}" onsubmit="return confirm('{{ __('Delete this deactivated service permanently? This action cannot be undone.') }}');">@csrf @method('DELETE')<button type="submit" class="rounded-xl border border-red-200 bg-red-50 px-4 py-2.5 text-sm font-extrabold text-red-700 hover:bg-red-100">{{ __('Delete Deactivated Service') }}</button></form>
                            @else
                                <span class="text-xs font-bold text-slate-400">{{ __('Deactivate the service before deletion.') }}</span>
                            @endif
                        </div>
                    </article>
                @empty
                    <div class="p-12 text-center text-slate-500">{{ __('No services have been created yet.') }}</div>
                @endforelse
            </div>
            @if($services->count() > 0)
                <div class="admin-services-pagination flex flex-col gap-4 border-t border-slate-200 bg-white p-4 sm:flex-row sm:items-center sm:justify-between sm:p-5">
                    <div class="flex items-center gap-2">
                        <button type="button" id="service-prev" class="portal-button-secondary !w-auto" aria-label="{{ __('Previous service') }}">← {{ __('Previous') }}</button>
                        <div id="service-dots" class="flex flex-wrap items-center justify-center gap-1.5" aria-label="{{ __('Service navigation') }}"></div>
                        <button type="button" id="service-next" class="portal-button !w-auto" aria-label="{{ __('Next service') }}">{{ __('Next') }} →</button>
                    </div>
                    <p id="service-step-label" class="text-center text-sm font-bold text-slate-500"></p>
                </div>
            @endif
        </section>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const steps = Array.from(document.querySelectorAll('[data-service-step]'));
    const previous = document.getElementById('service-prev');
    const next = document.getElementById('service-next');
    const dots = document.getElementById('service-dots');
    const label = document.getElementById('service-step-label');
    const search = document.getElementById('service-search');

    if (!steps.length || !previous || !next || !dots || !label) return;

    let current = 0;

    function render(index, shouldScroll = false) {
        current = (index + steps.length) % steps.length;

        steps.forEach((step, stepIndex) => {
            const active = stepIndex === current;
            step.classList.toggle('hidden', !active);
            step.setAttribute('aria-hidden', active ? 'false' : 'true');
        });

        dots.innerHTML = '';
        steps.forEach((step, stepIndex) => {
            const button = document.createElement('button');
            button.type = 'button';
            button.textContent = String(stepIndex + 1);
            button.setAttribute('aria-label', 'Service ' + (stepIndex + 1));
            button.setAttribute('aria-current', stepIndex === current ? 'true' : 'false');
            button.className = stepIndex === current
                ? 'admin-service-dot is-active'
                : 'admin-service-dot';
            button.addEventListener('click', () => render(stepIndex, true));
            dots.appendChild(button);
        });

        label.textContent = (current + 1) + ' / ' + steps.length;
        previous.disabled = steps.length <= 1;
        next.disabled = steps.length <= 1;

        if (shouldScroll && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            document.getElementById('service-management-carousel')?.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    }

    previous.addEventListener('click', () => render(current - 1, true));
    next.addEventListener('click', () => render(current + 1, true));

    document.addEventListener('keydown', (event) => {
        if (event.key !== '/' || event.ctrlKey || event.metaKey || event.altKey) return;
        if (['INPUT', 'TEXTAREA', 'SELECT'].includes(document.activeElement?.tagName)) return;
        event.preventDefault();
        search?.focus();
    });

    search?.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            search.value = '';
            search.form?.submit();
        }
    });

    render(0);
});
</script>>

</x-app-layout>
