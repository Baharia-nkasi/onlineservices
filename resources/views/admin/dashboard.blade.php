<x-app-layout>
<x-slot name="header">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-xs font-black uppercase tracking-[.24em] text-[#39ff14]">{{ __('Management') }}</p>
            <h2 class="mt-1 text-2xl font-black tracking-tight text-white sm:text-3xl">{{ __('Admin Dashboard') }}</h2>
            <p class="mt-1 text-sm text-[#8fa79a]">Central application operations and service infrastructure.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.services.index') }}" class="neon-button-outline">{{ __('Manage Services') }}</a>
            <a href="{{ route('admin.customers.index') }}" class="neon-button-outline">👤 {{ __('Manage Customers') }}</a>
            <a href="{{ route('admin.help-desk.index') }}" class="neon-button-outline">☎ {{ __('Help Desk') }}</a>
            <a href="{{ route('services.index') }}" class="neon-button-outline">{{ __('View Services') }}</a>
        </div>
    </div>
</x-slot>

<div class="neon-shell page-enter">
    <div class="relative z-10 mx-auto max-w-7xl space-y-6 px-4 py-7 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="neon-card border-[#00ff66]/30 p-4 text-sm font-bold text-[#9dffbb]">{{ session('success') }}</div>
        @endif

        <section class="neon-card neon-glow-border overflow-hidden p-5 sm:p-7">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <span class="neon-status">System online</span>
                    <h1 class="mt-4 text-3xl font-black tracking-tight text-white sm:text-4xl">Central Operations Matrix</h1>
                    <p class="mt-2 max-w-2xl text-sm leading-7 text-[#8fa79a]">Monitor customer applications, active services and document workflow from one responsive control surface.</p>
                </div>
                <button type="button" onclick="window.location.reload()" class="neon-button-outline neon-waka self-start lg:self-auto">↻ {{ __('Refresh') }}</button>
            </div>
        </section>

        <section class="grid gap-4 md:grid-cols-3">
            <div class="neon-card p-5">
                <p class="text-xs font-black uppercase tracking-[.16em] text-[#7f9589]">{{ __('Total Applications') }}</p>
                <p class="neon-value mt-3 text-4xl font-black">{{ $stats['applications'] }}</p>
                <p class="mt-2 text-xs font-semibold text-[#6f8378]">{{ __('All application records') }}</p>
            </div>
            <div class="neon-card p-5">
                <p class="text-xs font-black uppercase tracking-[.16em] text-[#7f9589]">{{ __('Active Services') }}</p>
                <p class="neon-value mt-3 text-4xl font-black">{{ $stats['services'] }}</p>
                <p class="mt-2 text-xs font-semibold text-[#6f8378]">{{ __('Services currently available') }}</p>
            </div>
            <div class="neon-card p-5">
                <p class="text-xs font-black uppercase tracking-[.16em] text-[#7f9589]">{{ __('Documents') }}</p>
                <p class="neon-value mt-3 text-4xl font-black">{{ $stats['documents'] }}</p>
                <p class="mt-2 text-xs font-semibold text-[#6f8378]">{{ __('Uploaded application records') }}</p>
            </div>
        </section>

        <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_340px]">
            <section class="neon-card overflow-hidden">
                <div class="flex flex-col gap-3 border-b border-[#163124] p-5 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-xs font-black uppercase tracking-[.18em] text-[#39ff14]">Client Matrix</p>
                        <h3 class="mt-1 text-xl font-black text-white">{{ __('Recent Applications') }}</h3>
                        <p class="mt-1 text-sm text-[#789085]">{{ __('Search, filter, review and process active customer requests.') }}</p>
                    </div>
                    <span id="admin-search-count" class="neon-status">{{ $applications->count() }} {{ __('results') }}</span>
                </div>

                <form method="GET" data-admin-filter-form class="grid gap-3 border-b border-[#102219] bg-[#07100b] p-4 md:grid-cols-[1fr_auto_auto]">
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="{{ __('Search customer, email, service or application ID...') }}" class="neon-input" data-table-search="#admin-applications-table" data-count-target="#admin-search-count" autocomplete="off">
                    <select name="status" class="neon-input md:w-48">
                        <option value="">{{ __('All statuses') }}</option>
                        @foreach(['pending','processing'] as $status)
                            <option value="{{ $status }}" @selected(request('status') === $status)>{{ __($status) }}</option>
                        @endforeach
                    </select>
                    <div class="flex gap-2">
                        <button type="submit" class="neon-button">{{ __('Filter') }}</button>
                        @if(request()->hasAny(['q','status']))
                            <a href="{{ route('admin.dashboard') }}" class="neon-button-outline">{{ __('Clear') }}</a>
                        @endif
                    </div>
                </form>

                <div class="overflow-x-auto p-2 sm:p-3">
                    <table id="admin-applications-table" class="w-full min-w-[760px] border-separate border-spacing-y-1 text-left text-sm">
                        <thead class="text-[10px] uppercase tracking-[.16em] text-[#6f8378]">
                            <tr>
                                <th class="px-4 py-3">{{ __('ID') }}</th>
                                <th class="px-4 py-3">{{ __('Customer') }}</th>
                                <th class="px-4 py-3">{{ __('Service') }}</th>
                                <th class="px-4 py-3">{{ __('Status') }}</th>
                                <th class="px-4 py-3">{{ __('Date') }}</th>
                                <th class="px-4 py-3"></th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($applications as $application)
                            @php
                                $badge = match($application->status) {
                                    'pending'=>'bg-amber-500/10 text-amber-300 border-amber-500/30',
                                    'processing'=>'bg-cyan-500/10 text-cyan-300 border-cyan-500/30',
                                    default=>'bg-[#00ff66]/10 text-[#9dffbb] border-[#00ff66]/30'
                                };
                            @endphp
                            <tr data-search-row data-application-id="{{ $application->id }}" data-search-text="{{ strtolower($application->id . ' ' . $application->user->name . ' ' . $application->user->email . ' ' . $application->service->name . ' ' . $application->status) }}" class="neon-row">
                                <td class="px-4 py-4 font-black text-[#39ff14]">#{{ $application->id }}</td>
                                <td class="px-4 py-4"><div class="font-bold text-white">{{ $application->user->name }}</div><div class="text-xs text-[#71867b]">{{ $application->user->email }}</div></td>
                                <td class="px-4 py-4 font-semibold text-[#c4d5cc]">{{ $application->service->name }}</td>
                                <td class="px-4 py-4"><span class="status-pill border {{ $badge }}">{{ __($application->status) }}</span></td>
                                <td class="whitespace-nowrap px-4 py-4 text-[#71867b]">{{ $application->created_at->format('d M Y H:i') }}</td>
                                <td class="px-4 py-4 text-right"><a class="font-black text-[#39ff14] hover:text-white" href="{{ route('admin.applications.show',$application) }}">{{ __('Review') }} →</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="p-12 text-center text-[#71867b]"><div class="text-3xl">⌁</div><p class="mt-3 font-bold">{{ __('No applications yet.') }}</p></td></tr>
                        @endforelse
                        </tbody>
                    </table>
                    <div data-table-search-empty class="hidden p-10 text-center text-sm text-[#71867b]">{{ __('No matching applications found on this page.') }}</div>
                </div>
                <div class="border-t border-[#163124] p-4">{{ $applications->links() }}</div>
            </section>

            <aside class="neon-card h-fit overflow-hidden">
                <div class="border-b border-[#163124] p-5">
                    <p class="text-xs font-black uppercase tracking-[.18em] text-[#39ff14]">Activity Stream</p>
                    <h3 class="mt-1 text-lg font-black text-white">Application Signals</h3>
                    <p class="mt-1 text-xs leading-5 text-[#71867b]">Live workflow signals from the current admin queue.</p>
                </div>
                <div class="space-y-2 p-3">
                    <div class="neon-row flex gap-3 p-3">
                        <span class="neon-indicator"></span>
                        <div><p class="text-sm font-bold text-white">{{ $stats['pending'] }} pending</p><p class="mt-1 text-xs text-[#71867b]">Waiting for admin action</p></div>
                    </div>
                    <div class="neon-row flex gap-3 p-3">
                        <span class="neon-indicator"></span>
                        <div><p class="text-sm font-bold text-white">{{ $stats['processing'] }} processing</p><p class="mt-1 text-xs text-[#71867b]">Currently being handled</p></div>
                    </div>
                    <div class="neon-row flex gap-3 p-3">
                        <span class="neon-indicator"></span>
                        <div><p class="text-sm font-bold text-white">{{ $stats['completed'] }} completed</p><p class="mt-1 text-xs text-[#71867b]">Completed workflow records</p></div>
                    </div>
                    <div class="neon-row flex gap-3 p-3">
                        <span class="neon-indicator"></span>
                        <div><p class="text-sm font-bold text-white">{{ $stats['rejected'] }} rejected</p><p class="mt-1 text-xs text-[#71867b]">Kept in application history</p></div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>
</x-app-layout>