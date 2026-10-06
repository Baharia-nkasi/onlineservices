<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-black uppercase tracking-[0.18em] text-blue-700">{{ __('Management') }}</p>
                <h2 class="text-2xl font-black tracking-tight text-slate-900">{{ __('Help Desk Contacts') }}</h2>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="portal-button-secondary">{{ __('← Back to Dashboard') }}</a>
        </div>
    </x-slot>

    <div class="min-h-screen bg-slate-50 page-enter">
        <div class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-bold text-emerald-800">{{ session('success') }}</div>
            @endif

            <section class="overflow-hidden rounded-[26px] border border-blue-200 bg-gradient-to-br from-blue-700 via-indigo-700 to-violet-700 p-6 text-white shadow-xl sm:p-8">
                <div class="max-w-3xl">
                    <span class="inline-flex rounded-full border border-white/20 bg-white/10 px-3 py-1 text-xs font-black uppercase tracking-wider text-blue-100">☎ Help Desk</span>
                    <h1 class="mt-3 text-2xl font-black sm:text-3xl">{{ __('Customer support phone numbers') }}</h1>
                    <p class="mt-2 text-sm leading-6 text-blue-100">{{ __('Add and manage Tigo, TTCL, Vodacom, Airtel, or any other support number. Only active contacts appear to customers.') }}</p>
                </div>
            </section>

            <section class="grid gap-6 lg:grid-cols-[380px_1fr]">
                <div class="portal-card p-5 sm:p-6">
                    <div class="mb-5">
                        <p class="text-xs font-black uppercase tracking-wider text-blue-700">{{ __('Add new') }}</p>
                        <h3 class="mt-1 text-lg font-black text-slate-900">{{ __('New help desk number') }}</h3>
                    </div>
                    <form method="POST" action="{{ route('admin.help-desk.store') }}" class="space-y-4">
                        @csrf
                        <div><label class="text-sm font-extrabold text-slate-700">{{ __('Network / Provider') }}</label><input name="network" required maxlength="80" class="input-modern mt-2" placeholder="e.g. Tigo"></div>
                        <div><label class="text-sm font-extrabold text-slate-700">{{ __('Phone number') }}</label><input name="phone" required maxlength="40" class="input-modern mt-2" placeholder="+255 7XX XXX XXX"></div>
                        <div><label class="text-sm font-extrabold text-slate-700">{{ __('Label') }}</label><input name="label" maxlength="120" class="input-modern mt-2" placeholder="Customer Support"></div>
                        <div><label class="text-sm font-extrabold text-slate-700">{{ __('Display order') }}</label><input type="number" name="sort_order" min="0" max="999" value="0" class="input-modern mt-2"></div>
                        <label class="flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 p-3 text-sm font-bold text-slate-700"><input type="checkbox" name="is_active" value="1" checked class="rounded border-slate-300 text-blue-700 focus:ring-blue-500"> {{ __('Show on customer dashboard') }}</label>
                        <button type="submit" class="portal-button w-full justify-center">＋ {{ __('Add Contact') }}</button>
                    </form>
                </div>

                <div class="portal-card overflow-hidden">
                    <div class="border-b border-slate-200 p-5 sm:p-6">
                        <h3 class="text-lg font-black text-slate-900">{{ __('Managed numbers') }}</h3>
                        <p class="mt-1 text-sm text-slate-500">{{ __('Edit, activate, deactivate, or remove support contacts.') }}</p>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @forelse($contacts as $contact)
                            <form method="POST" action="{{ route('admin.help-desk.update', $contact) }}" class="grid gap-3 p-4 sm:grid-cols-[1fr_1fr_1fr_90px_auto] sm:items-end">
                                @csrf @method('PATCH')
                                <div><label class="text-[11px] font-black uppercase tracking-wide text-slate-400">{{ __('Network') }}</label><input name="network" value="{{ $contact->network }}" required class="input-modern mt-1"></div>
                                <div><label class="text-[11px] font-black uppercase tracking-wide text-slate-400">{{ __('Phone') }}</label><input name="phone" value="{{ $contact->phone }}" required class="input-modern mt-1"></div>
                                <div><label class="text-[11px] font-black uppercase tracking-wide text-slate-400">{{ __('Label') }}</label><input name="label" value="{{ $contact->label }}" class="input-modern mt-1"></div>
                                <div><label class="text-[11px] font-black uppercase tracking-wide text-slate-400">{{ __('Order') }}</label><input type="number" name="sort_order" min="0" value="{{ $contact->sort_order }}" class="input-modern mt-1"></div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <label class="flex items-center gap-2 text-xs font-bold text-slate-600"><input type="checkbox" name="is_active" value="1" @checked($contact->is_active) class="rounded border-slate-300 text-blue-700 focus:ring-blue-500"> {{ __('Active') }}</label>
                                    <button class="portal-button px-3 py-2 text-xs">{{ __('Save') }}</button>
                                    <button type="button" onclick="this.closest('form').nextElementSibling.submit()" class="portal-button-secondary px-3 py-2 text-xs">{{ __('Delete') }}</button>
                                </div>
                            </form>
                            <form method="POST" action="{{ route('admin.help-desk.destroy', $contact) }}" class="hidden">@csrf @method('DELETE')</form>
                        @empty
                            <div class="p-12 text-center"><div class="text-4xl">☎️</div><h4 class="mt-3 font-black text-slate-900">{{ __('No help desk numbers yet') }}</h4><p class="mt-1 text-sm text-slate-500">{{ __('Add the first support number using the form.') }}</p></div>
                        @endforelse
                    </div>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
