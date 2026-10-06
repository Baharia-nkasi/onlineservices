<x-app-layout>
<x-slot name="header">
    <div class="flex items-center justify-between gap-4">
        <div><p class="text-xs font-extrabold uppercase tracking-wider text-red-700">{{ __('Management') }}</p><h2 class="text-2xl font-black tracking-tight text-slate-900">{{ __('Manage Customers') }}</h2></div>
        <a href="{{ route('admin.dashboard') }}" class="portal-button-secondary hidden sm:inline-flex">{{ __('Back to Dashboard') }}</a>
    </div>
</x-slot>
<div class="min-h-screen bg-slate-50 page-enter">
<div class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
@if(session('success'))<div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold text-emerald-800">{{ session('success') }}</div>@endif
@if($errors->any())<div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-sm font-semibold text-red-800">{{ $errors->first() }}</div>@endif
<div class="portal-card overflow-hidden">
<div class="border-b border-slate-200 p-5"><div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between"><div><h3 class="text-lg font-black text-slate-900">{{ __('Customer Accounts') }}</h3><p class="mt-1 text-sm text-slate-500">{{ __('Permanently remove a customer and all information belonging to that account.') }}</p></div><span class="text-sm font-bold text-slate-400">{{ $customers->total() }} {{ __('customers') }}</span></div></div>
<form method="GET" class="flex flex-col gap-3 border-b border-slate-100 bg-slate-50 p-4 sm:flex-row"><input type="search" name="q" value="{{ $search }}" placeholder="{{ __('Search customer name or email...') }}" class="input-modern flex-1" autocomplete="off"><button type="submit" class="portal-button">{{ __('Search') }}</button>@if($search !== '')<a href="{{ route('admin.customers.index') }}" class="portal-button-secondary">{{ __('Clear') }}</a>@endif</form>
<div class="overflow-x-auto"><table class="w-full min-w-[760px] text-left text-sm"><thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500"><tr><th class="p-4">{{ __('Customer') }}</th><th class="p-4">{{ __('Email') }}</th><th class="p-4">{{ __('Applications') }}</th><th class="p-4">{{ __('Registered') }}</th><th class="p-4 text-right">{{ __('Action') }}</th></tr></thead><tbody>
@forelse($customers as $customer)
<tr class="border-t border-slate-100 hover:bg-slate-50"><td class="p-4"><div class="font-black text-slate-900">{{ $customer->name }}</div><div class="mt-1 text-xs text-slate-500">#{{ $customer->id }}</div></td><td class="p-4 font-medium text-slate-700">{{ $customer->email }}</td><td class="p-4"><span class="status-pill bg-blue-100 text-blue-800">{{ $customer->applications_count }}</span></td><td class="p-4 whitespace-nowrap text-slate-500">{{ $customer->created_at->format('d M Y H:i') }}</td><td class="p-4 text-right"><form method="POST" action="{{ route('admin.customers.destroy', $customer) }}" onsubmit="return confirm({{ Js::from(__('PERMANENT DELETE WARNING: This will remove the customer account, all applications, uploaded documents, notifications, sessions and related history. This cannot be undone. The customer will need to register again as a new user. Continue?')) }});">@csrf @method('DELETE')<button type="submit" class="inline-flex items-center rounded-xl bg-red-600 px-4 py-2 text-sm font-extrabold text-white shadow-sm transition hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">{{ __('Delete Permanently') }}</button></form></td></tr>
@empty<tr><td colspan="5" class="p-12 text-center text-slate-500"><div class="text-3xl">👤</div><p class="mt-3 font-bold">{{ $search !== '' ? __('No customers found.') : __('No customer accounts yet.') }}</p></td></tr>@endforelse
</tbody></table></div>
<div class="border-t border-slate-200 p-4">{{ $customers->links() }}</div>
</div></div></div>
</x-app-layout>
