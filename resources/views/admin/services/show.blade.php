<x-app-layout>
<x-slot name="header">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-xs font-extrabold uppercase tracking-wider text-blue-700">{{ __('Management') }}</p>
            <h2 class="text-2xl font-black tracking-tight text-slate-900">{{ $service->name }}</h2>
            <p class="mt-1 text-sm text-slate-500">{{ __('Service management') }} · {{ $service->slug }}</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.services.index') }}" class="portal-button-secondary">← {{ __('All Services') }}</a>
            <a href="{{ route('admin.dashboard') }}" class="portal-button-secondary">{{ __('Admin Dashboard') }}</a>
        </div>
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

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="portal-card p-5"><p class="text-xs font-extrabold uppercase tracking-wide text-slate-400">{{ __('Status') }}</p><p class="mt-2 text-lg font-black {{ $service->is_active ? 'text-emerald-700' : 'text-slate-500' }}">{{ $service->is_active ? __('Active') : __('Deactivated') }}</p></div>
            <div class="portal-card p-5"><p class="text-xs font-extrabold uppercase tracking-wide text-slate-400">{{ __('Applications') }}</p><p class="mt-2 text-2xl font-black text-slate-900">{{ $service->applications_count }}</p></div>
            <div class="portal-card p-5"><p class="text-xs font-extrabold uppercase tracking-wide text-slate-400">{{ __('Active requirements') }}</p><p class="mt-2 text-2xl font-black text-slate-900">{{ $service->active_documents_count }}</p></div>
            <div class="portal-card p-5"><p class="text-xs font-extrabold uppercase tracking-wide text-slate-400">{{ __('Total requirements') }}</p><p class="mt-2 text-2xl font-black text-slate-900">{{ $service->documents->count() }}</p></div>
        </div>

        <section class="portal-card p-6">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-xl font-black text-slate-900">{{ __('Service Settings') }}</h3>
                    <p class="mt-1 text-sm text-slate-500">{{ __('Update this service without managing other services on the same screen.') }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.services.update', $service) }}" class="mt-6 grid gap-4 md:grid-cols-2">
                @csrf @method('PATCH')
                <div><label class="block text-sm font-extrabold text-slate-700">{{ __('Service name') }}</label><input name="name" value="{{ $service->name }}" required maxlength="255" class="input-modern mt-2 w-full"></div>
                <div><label class="block text-sm font-extrabold text-slate-700">{{ __('Slug') }}</label><input name="slug" value="{{ $service->slug }}" required maxlength="255" pattern="[A-Za-z0-9_-]+" class="input-modern mt-2 w-full"></div>
                <div class="md:col-span-2"><label class="block text-sm font-extrabold text-slate-700">{{ __('Description') }}</label><textarea name="description" rows="3" maxlength="5000" class="input-modern mt-2 w-full">{{ $service->description }}</textarea></div>
                <div><label class="block text-sm font-extrabold text-slate-700">{{ __('Government fee (TSh)') }}</label><input type="number" min="0" step="0.01" name="government_fee" value="{{ $service->government_fee }}" required class="input-modern mt-2 w-full"></div>
                <div><label class="block text-sm font-extrabold text-slate-700">{{ __('Service fee (TSh)') }}</label><input type="number" min="0" step="0.01" name="service_fee" value="{{ $service->service_fee }}" required class="input-modern mt-2 w-full"></div>
                <div class="md:col-span-2 flex flex-wrap items-center justify-between gap-4 border-t border-slate-100 pt-4">
                    <label class="flex items-center gap-3 text-sm font-bold text-slate-700"><input type="hidden" name="is_active" value="0"><input type="checkbox" name="is_active" value="1" @checked($service->is_active) class="rounded border-slate-300 text-blue-700 focus:ring-blue-500">{{ __('Available to customers') }}</label>
                    <button type="submit" class="portal-button">{{ __('Save Changes') }}</button>
                </div>
            </form>
        </section>

        <section class="portal-card overflow-hidden">
            <div class="border-b border-slate-200 p-6">
                <h3 class="text-xl font-black text-slate-900">{{ __('Document Requirements') }}</h3>
                <p class="mt-1 text-sm text-slate-500">{{ __('Add, edit, activate or deactivate documents required for this service.') }}</p>
            </div>
            <div class="p-6">
                <form method="POST" action="{{ route('admin.services.documents.store', $service) }}" class="grid gap-3 md:grid-cols-2 lg:grid-cols-4">
                    @csrf
                    <input name="name" required maxlength="255" class="input-modern" placeholder="{{ __('Document name') }}">
                    <input name="description" maxlength="2000" class="input-modern" placeholder="{{ __('Description') }}">
                    <select name="requirement_type" class="input-modern">
                        <option value="single">{{ __('Single requirement') }}</option>
                        <option value="choose_one">{{ __('Choose one from group') }}</option>
                        <option value="choose_many">{{ __('Choose many from group') }}</option>
                    </select>
                    <input name="requirement_group" maxlength="100" pattern="[A-Za-z0-9_-]+" class="input-modern" placeholder="{{ __('Group') }}">
                    <input type="number" name="minimum_required" value="1" min="1" max="100" class="input-modern" placeholder="{{ __('Minimum required') }}">
                    <input type="number" name="sort_order" value="0" min="0" max="10000" class="input-modern" placeholder="{{ __('Sort order') }}">
                    <label class="flex items-center gap-2 text-sm font-bold"><input type="hidden" name="is_required" value="0"><input type="checkbox" name="is_required" value="1" checked>{{ __('Required') }}</label>
                    <label class="flex items-center gap-2 text-sm font-bold"><input type="hidden" name="is_active" value="0"><input type="checkbox" name="is_active" value="1" checked>{{ __('Active') }}</label>
                    <button type="submit" class="portal-button md:col-span-2 lg:col-span-4">{{ __('Add Requirement') }}</button>
                </form>

                <div class="mt-6 space-y-4">
                    @forelse($service->documents as $document)
                        <div class="rounded-2xl border border-slate-200 bg-white p-5">
                            <form method="POST" action="{{ route('admin.service-documents.update', $document) }}" class="grid gap-3 md:grid-cols-12">
                                @csrf @method('PATCH')
                                <input name="name" value="{{ $document->name }}" required maxlength="255" class="input-modern md:col-span-3">
                                <input name="description" value="{{ $document->description }}" maxlength="2000" class="input-modern md:col-span-3">
                                <select name="requirement_type" class="input-modern md:col-span-2">
                                    <option value="single" @selected($document->requirement_type === 'single')>{{ __('Single') }}</option>
                                    <option value="choose_one" @selected($document->requirement_type === 'choose_one')>{{ __('Choose one') }}</option>
                                    <option value="choose_many" @selected($document->requirement_type === 'choose_many')>{{ __('Choose many') }}</option>
                                </select>
                                <input name="requirement_group" value="{{ $document->requirement_group }}" maxlength="100" pattern="[A-Za-z0-9_-]+" class="input-modern md:col-span-2" placeholder="{{ __('Group') }}">
                                <input type="number" name="minimum_required" value="{{ $document->minimum_required }}" min="1" max="100" class="input-modern">
                                <input type="number" name="sort_order" value="{{ $document->sort_order }}" min="0" max="10000" class="input-modern">
                                <div class="flex flex-wrap items-center gap-4 md:col-span-8">
                                    <label class="text-xs font-bold"><input type="hidden" name="is_required" value="0"><input type="checkbox" name="is_required" value="1" @checked($document->is_required)> {{ __('Required') }}</label>
                                    <label class="text-xs font-bold"><input type="hidden" name="is_active" value="0"><input type="checkbox" name="is_active" value="1" @checked($document->is_active)> {{ __('Active') }}</label>
                                    <button type="submit" class="portal-button">{{ __('Save') }}</button>
                                </div>
                            </form>
                            <div class="mt-3 flex justify-between gap-3 border-t border-slate-100 pt-3">
                                <span class="text-xs font-bold {{ $document->is_active ? 'text-emerald-700' : 'text-slate-400' }}">{{ $document->is_active ? __('Active') : __('Deactivated') }} · {{ $document->is_required ? __('Required') : __('Optional') }}</span>
                                <form method="POST" action="{{ route('admin.service-documents.destroy', $document) }}" onsubmit="return confirm('{{ __('Delete this document requirement permanently? This cannot be undone.') }}');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="rounded-xl border border-red-200 bg-red-50 px-3 py-2 text-xs font-extrabold text-red-700 hover:bg-red-100">{{ __('Delete Requirement') }}</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="rounded-2xl border border-dashed border-slate-300 p-8 text-center text-sm text-slate-500">{{ __('No document requirements configured.') }}</p>
                    @endforelse
                </div>
            </div>
        </section>

        <div class="flex flex-wrap items-center justify-between gap-3">
            <a href="{{ route('admin.services.index') }}" class="portal-button-secondary">← {{ __('Back to Services') }}</a>
            @if(!$service->is_active)
                <form method="POST" action="{{ route('admin.services.destroy', $service) }}" onsubmit="return confirm('{{ __('Delete this deactivated service permanently? This action cannot be undone.') }}');">
                    @csrf @method('DELETE')
                    <button type="submit" class="rounded-xl border border-red-200 bg-red-50 px-4 py-2.5 text-sm font-extrabold text-red-700 hover:bg-red-100">{{ __('Delete Deactivated Service') }}</button>
                </form>
            @endif
        </div>
    </div>
</div>
</x-app-layout>
