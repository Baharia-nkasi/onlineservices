<x-app-layout>
<x-slot name="header">
    <div class="flex items-center justify-between">
        <div><p class="text-xs font-extrabold uppercase tracking-wider text-blue-700">Management</p><h2 class="text-2xl font-black tracking-tight text-slate-900">Admin Dashboard</h2></div>
        <a href="{{ route('services.index') }}" class="portal-button-secondary hidden sm:inline-flex">View Services</a>
    </div>
</x-slot>

<div class="min-h-screen bg-slate-50 page-enter">
    <div class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold text-emerald-800">{{ session('success') }}</div>
        @endif

        <div class="grid grid-cols-2 gap-4 md:grid-cols-4 lg:grid-cols-7">
            @foreach([
                ['Applications',$stats['applications'],'bg-blue-50 text-blue-700'],
                ['Pending',$stats['pending'],'bg-amber-50 text-amber-700'],
                ['Processing',$stats['processing'],'bg-indigo-50 text-indigo-700'],
                ['Completed',$stats['completed'],'bg-emerald-50 text-emerald-700'],
                ['Rejected',$stats['rejected'],'bg-red-50 text-red-700'],
                ['Documents',$stats['documents'],'bg-violet-50 text-violet-700'],
                ['Active Services',$stats['services'],'bg-slate-100 text-slate-700']
            ] as [$label,$value,$style])
                <div class="portal-card p-4 transition hover:-translate-y-0.5 hover:shadow-md">
                    <div class="inline-flex rounded-lg px-2 py-1 text-xs font-extrabold {{ $style }}">{{ $label }}</div>
                    <div class="mt-3 text-2xl font-black text-slate-900">{{ $value }}</div>
                </div>
            @endforeach
        </div>

        <div class="portal-card overflow-hidden">
            <div class="flex flex-col gap-3 border-b border-slate-200 p-5 sm:flex-row sm:items-center sm:justify-between">
                <div><h3 class="text-lg font-black text-slate-900">Recent Applications</h3><p class="mt-1 text-sm text-slate-500">Search, filter, review and process customer requests.</p></div>
                <span id="admin-search-count" class="text-sm font-bold text-slate-400">{{ $applications->count() }} results on this page</span>
            </div>
            <form method="GET" data-admin-filter-form class="grid gap-3 border-b border-slate-100 bg-slate-50 p-4 md:grid-cols-[1fr_auto_auto]">
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Search customer, email, service or application ID..." class="input-modern" data-table-search="#admin-applications-table" data-count-target="#admin-search-count" autocomplete="off">
                <select name="status" class="input-modern md:w-48">
                    <option value="">All statuses</option>
                    @foreach(['pending','processing','completed','rejected'] as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
                <div class="flex gap-2">
                    <button type="submit" class="portal-button">Filter</button>
                    @if(request()->hasAny(['q','status']))
                        <a href="{{ route('admin.dashboard') }}" class="portal-button-secondary">Clear</a>
                    @endif
                </div>
            </form>
            <div class="overflow-x-auto">
                <table id="admin-applications-table" class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                        <tr><th class="p-4">ID</th><th class="p-4">Customer</th><th class="p-4">Service</th><th class="p-4">Status</th><th class="p-4">Date</th><th class="p-4"></th></tr>
                    </thead>
                    <tbody>
                    @forelse($applications as $application)
                        @php
                            $badge = match($application->status) {
                                'pending'=>'bg-amber-100 text-amber-800',
                                'processing'=>'bg-blue-100 text-blue-800',
                                'completed'=>'bg-emerald-100 text-emerald-800',
                                'rejected'=>'bg-red-100 text-red-800',
                                default=>'bg-slate-100 text-slate-700'
                            };
                        @endphp
                        <tr data-search-row data-search-text="{{ strtolower($application->id . ' ' . $application->user->name . ' ' . $application->user->email . ' ' . $application->service->name . ' ' . $application->status) }}" class="border-t border-slate-100 transition hover:bg-slate-50">
                            <td class="p-4 font-black">#{{ $application->id }}</td>
                            <td class="p-4"><div class="font-bold text-slate-900">{{ $application->user->name }}</div><div class="text-xs text-slate-500">{{ $application->user->email }}</div></td>
                            <td class="p-4 font-medium">{{ $application->service->name }}</td>
                            <td class="p-4"><span class="status-pill {{ $badge }}">{{ ucfirst($application->status) }}</span></td>
                            <td class="p-4 whitespace-nowrap text-slate-500">{{ $application->created_at->format('d M Y H:i') }}</td>
                            <td class="p-4 text-right"><a class="font-extrabold text-blue-700 hover:text-blue-900" href="{{ route('admin.applications.show',$application) }}">Review →</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="p-12 text-center text-slate-500"><div class="text-3xl">📋</div><p class="mt-3 font-bold">No applications yet.</p></td></tr>
                    @endforelse
                    </tbody>
                </table>
                <div data-table-search-empty class="hidden p-10 text-center text-sm text-slate-500">No matching applications found on this page.</div>
            </div>
            <div class="border-t border-slate-200 p-4">{{ $applications->links() }}</div>
        </div>
    </div>
</div>
</x-app-layout>
