<x-app-layout>
<x-slot name="header">
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-xs font-extrabold uppercase tracking-wider text-blue-700">Service catalogue</p>
            <h2 class="text-2xl font-black tracking-tight text-slate-900">Find a service</h2>
        </div>
        <span class="text-sm text-slate-500">{{ $services->count() }} available services</span>
    </div>
</x-slot>

<div class="min-h-screen bg-slate-50 page-enter" x-data="serviceSearch()">
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold text-emerald-800 fade-up">
                {{ session('success') }}
            </div>
        @endif

        <div class="relative overflow-hidden rounded-3xl bg-slate-950 p-7 text-white shadow-xl sm:p-10">
            <div class="pointer-events-none absolute -right-16 -top-20 h-64 w-64 rounded-full bg-blue-600/20 blur-3xl"></div>
            <div class="relative">
                <p class="text-sm font-extrabold uppercase tracking-wider text-blue-300">Online Services</p>
                <h1 class="mt-2 text-3xl font-black sm:text-4xl">What service do you need?</h1>
                <p class="mt-3 max-w-2xl leading-7 text-slate-300">Choose a service below. Review its requirements and fees before starting your application.</p>

                <div class="mt-6 flex max-w-xl items-center gap-3 rounded-2xl bg-white px-4 py-3 text-slate-400 shadow-lg">
                    <span class="text-lg">⌕</span>
                    <input x-model="query" @input="filter()" type="search" autocomplete="off"
                        placeholder="Search services..."
                        class="w-full border-0 p-0 text-sm text-slate-900 outline-none focus:ring-0">
                    <button type="button" x-show="query" x-cloak @click="query=''; filter()"
                        class="rounded-lg px-2 py-1 text-xs font-bold text-slate-500 hover:bg-slate-100">Clear</button>
                </div>
            </div>
        </div>

        <div id="service-grid" class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($services as $service)
                <article class="service-card flex flex-col rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-blue-200 hover:shadow-xl"
                    data-name="{{ strtolower($service->name) }}">
                    <div class="flex items-center justify-between">
                        <span class="service-icon flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-xl transition duration-300">📋</span>
                        <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">Available</span>
                    </div>
                    <h3 class="mt-5 text-lg font-extrabold text-slate-900">{{ $service->name }}</h3>
                    <p class="mt-2 flex-1 text-sm leading-6 text-slate-500">{{ $service->description }}</p>
                    <div class="mt-5 grid grid-cols-2 gap-3 border-t border-slate-100 pt-4 text-sm">
                        <div><span class="block text-xs text-slate-400">Requirements</span><strong>{{ $service->documents->where('is_active',true)->count() }}</strong></div>
                        <div><span class="block text-xs text-slate-400">Service fee</span><strong>TSh {{ number_format($service->service_fee,0) }}</strong></div>
                    </div>
                    <a href="{{ route('applications.create',$service) }}" class="portal-button mt-5 w-full">View requirements & Apply →</a>
                </article>
            @empty
                <div class="col-span-full rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center text-slate-500">No services are currently available.</div>
            @endforelse

            <div data-search-empty class="hidden col-span-full rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center">
                <div class="text-3xl">🔎</div>
                <h3 class="mt-3 font-black text-slate-900">No service found</h3>
                <p class="mt-1 text-sm text-slate-500">Try another search term.</p>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
