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

        <section class="admin-management-card p-6">
            <h3 class="text-xl font-black text-slate-900">{{ __('Add New Service') }}</h3>
            <p class="mt-1 text-sm text-slate-500">{{ __('Create a service that customers can request from the Services catalogue.') }}</p>
            <form method="POST" action="{{ route('admin.services.store') }}" class="mt-6 grid gap-4 md:grid-cols-2">
                @csrf
                <div><label for="name" class="block text-sm font-extrabold text-slate-700">{{ __('Service name') }}</label><input id="name" name="name" value="{{ old('name') }}" required maxlength="255" class="admin-management-input mt-2 w-full" placeholder="{{ __('e.g. Passport Application') }}"></div>
                <div><label for="slug" class="block text-sm font-extrabold text-slate-700">{{ __('Slug') }}</label><input id="slug" name="slug" value="{{ old('slug') }}" required maxlength="255" pattern="[A-Za-z0-9_-]+" class="admin-management-input mt-2 w-full" placeholder="passport-application"></div>
                <div class="md:col-span-2"><label for="description" class="block text-sm font-extrabold text-slate-700">{{ __('Description') }}</label><textarea id="description" name="description" rows="3" maxlength="5000" class="admin-management-input mt-2 w-full">{{ old('description') }}</textarea></div>
                <div><label for="government_fee" class="block text-sm font-extrabold text-slate-700">{{ __('Government fee (TSh)') }}</label><input id="government_fee" type="number" min="0" step="0.01" name="government_fee" value="{{ old('government_fee', 0) }}" required class="admin-management-input mt-2 w-full"></div>
                <div><label for="service_fee" class="block text-sm font-extrabold text-slate-700">{{ __('Service fee (TSh)') }}</label><input id="service_fee" type="number" min="0" step="0.01" name="service_fee" value="{{ old('service_fee', 0) }}" required class="admin-management-input mt-2 w-full"></div>
                <div class="md:col-span-2 flex items-center justify-between gap-4 border-t border-slate-100 pt-4">
                    <label class="flex items-center gap-3 text-sm font-bold text-slate-700"><input type="hidden" name="is_active" value="0"><input type="checkbox" name="is_active" value="1" checked class="rounded border-slate-300 text-blue-700 focus:ring-blue-500">{{ __('Make service available to customers now') }}</label>
                    <button type="submit" class="portal-button shadow-lg shadow-blue-800/20">{{ __('Add Service') }}</button>
                </div>
            </form>
        </section>

        <section class="portal-card overflow-hidden">
            <div class="border-b border-slate-200 p-6">
                <h3 class="text-xl font-black text-slate-900">{{ __('All Services') }}</h3>
                <p class="mt-1 text-sm text-slate-500">{{ __('Activate, deactivate, edit or safely delete services.') }}</p>
            </div>
            <div id="service-management-carousel" class="bg-gradient-to-br from-blue-50 via-white to-cyan-50 p-4 sm:p-6">
                @forelse($services as $service)
                    <article data-service-step class="admin-management-step hidden w-full rounded-[2rem] border border-slate-200 bg-white p-5 shadow-xl shadow-slate-200/50 sm:p-7">
                        <form method="POST" action="{{ route('admin.services.update', $service) }}" class="grid gap-4 lg:grid-cols-12">
                            @csrf @method('PATCH')
                            <div class="lg:col-span-4"><label class="text-xs font-extrabold uppercase tracking-wide text-slate-400">{{ __('Service') }}</label><input name="name" value="{{ $service->name }}" required maxlength="255" class="admin-management-input mt-2 w-full"></div>
                            <div class="lg:col-span-3"><label class="text-xs font-extrabold uppercase tracking-wide text-slate-400">{{ __('Slug') }}</label><input name="slug" value="{{ $service->slug }}" required maxlength="255" pattern="[A-Za-z0-9_-]+" class="admin-management-input mt-2 w-full"></div>
                            <div class="lg:col-span-5"><label class="text-xs font-extrabold uppercase tracking-wide text-slate-400">{{ __('Description') }}</label><input name="description" value="{{ $service->description }}" maxlength="5000" class="admin-management-input mt-2 w-full"></div>
                            <div class="lg:col-span-3"><label class="text-xs font-extrabold uppercase tracking-wide text-slate-400">{{ __('Government fee (TSh)') }}</label><input type="number" min="0" step="0.01" name="government_fee" value="{{ $service->government_fee }}" required class="admin-management-input mt-2 w-full"></div>
                            <div class="lg:col-span-3"><label class="text-xs font-extrabold uppercase tracking-wide text-slate-400">{{ __('Service fee (TSh)') }}</label><input type="number" min="0" step="0.01" name="service_fee" value="{{ $service->service_fee }}" required class="admin-management-input mt-2 w-full"></div>
                            <div class="lg:col-span-2 flex items-end"><label class="flex items-center gap-2 pb-3 text-sm font-bold {{ $service->is_active ? 'text-emerald-700' : 'text-slate-500' }}"><input type="hidden" name="is_active" value="0"><input type="checkbox" name="is_active" value="1" @checked($service->is_active) class="rounded border-slate-300 text-blue-700 focus:ring-blue-500">{{ $service->is_active ? __('Active') : __('Deactivated') }}</label></div>
                            <div class="lg:col-span-4 flex flex-wrap items-end gap-2"><span class="rounded-xl bg-slate-100 px-3 py-2 text-xs font-bold text-slate-600">{{ $service->applications_count }} {{ __('applications') }}</span><span class="rounded-xl bg-slate-100 px-3 py-2 text-xs font-bold text-slate-600">{{ $service->active_documents_count }} {{ __('active requirements') }}</span><button type="submit" class="portal-button">{{ __('Save Changes') }}</button><a href="{{ route('admin.services.show', $service) }}" class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 text-sm font-extrabold text-white shadow-lg shadow-slate-900/20 transition-all duration-200 hover:-translate-y-0.5 hover:bg-slate-800 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-slate-700 focus:ring-offset-2">{{ __('Manage Service') }} →</a></div>
                        </form>
                        <details class="mt-5 rounded-3xl border border-sky-200 bg-gradient-to-br from-sky-50 to-cyan-50 p-4 shadow-inner">
                            <summary class="cursor-pointer text-sm font-extrabold text-slate-800">{{ __('Manage document requirements') }} ({{ $service->active_documents_count }})</summary>
                            <div class="mt-4 space-y-4">
                                <form method="POST" action="{{ route('admin.services.documents.store', $service) }}" class="grid gap-3 md:grid-cols-2">
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
                                    <button type="submit" class="portal-button md:col-span-2">{{ __('Add Requirement') }}</button>
                                </form>
                                <div class="space-y-3">
                                    @forelse($service->documents as $document)
                                        <form method="POST" action="{{ route('admin.service-documents.update', $document) }}" class="grid gap-3 rounded-xl border border-slate-200 bg-white p-4 md:grid-cols-12">
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
                                        <div class="mt-2 flex justify-end">
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

                        <div class="mt-4 flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-4">
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
                <div class="flex flex-col gap-4 border-t border-slate-200 bg-white p-4 sm:flex-row sm:items-center sm:justify-between sm:p-5">
                    <div class="flex items-center gap-2">
                        <button type="button" id="service-prev" class="portal-button-secondary !w-auto" aria-label="{{ __('Previous service') }}">← {{ __('Previous') }}</button>
                        <div id="service-dots" class="flex flex-wrap items-center justify-center gap-1.5" aria-label="{{ __('Service navigation') }}"></div>
                        <button type="button" id="service-next" class="portal-button !w-auto" aria-label="{{ __('Next service') }}">{{ __('Next') }} →</button>
                    </div>
                    <p id="service-step-label" class="text-center text-sm font-bold text-slate-500"></p>
                </div>
            @endif
            <div class="border-t border-slate-200 bg-white p-4">{{ $services->links() }}</div>
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

    if (!steps.length || !previous || !next || !dots || !label) return;

    let current = 0;

    function render(index, direction) {
        current = (index + steps.length) % steps.length;

        steps.forEach((step, stepIndex) => {
            step.classList.toggle('hidden', stepIndex !== current);
            step.setAttribute('aria-hidden', stepIndex === current ? 'false' : 'true');
        });

        dots.innerHTML = '';
        steps.forEach((step, stepIndex) => {
            const button = document.createElement('button');
            button.type = 'button';
            button.textContent = String(stepIndex + 1);
            button.setAttribute('aria-label', 'Service ' + (stepIndex + 1));
            button.className = stepIndex === current
                ? 'flex h-9 min-w-9 items-center justify-center rounded-xl bg-blue-700 px-2 text-xs font-black text-white shadow-md'
                : 'flex h-9 min-w-9 items-center justify-center rounded-xl border border-slate-200 bg-slate-50 px-2 text-xs font-black text-slate-600 hover:border-blue-300 hover:bg-blue-50 hover:text-blue-700';
            button.addEventListener('click', () => render(stepIndex, stepIndex > current ? 1 : -1));
            dots.appendChild(button);
        });

        label.textContent = (current + 1) + ' / ' + steps.length;
        previous.disabled = steps.length <= 1;
        next.disabled = steps.length <= 1;

        const active = steps[current];
        active.classList.remove('admin-management-step');
        void active.offsetWidth;
        active.classList.add('admin-management-step');

        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
        active.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    previous.addEventListener('click', () => render(current - 1, -1));
    next.addEventListener('click', () => render(current + 1, 1));
    render(0, 1);
});
</script>

</x-app-layout>
