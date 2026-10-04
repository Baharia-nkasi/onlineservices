<x-app-layout>
<x-slot name="header">
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div><p class="text-xs font-extrabold uppercase tracking-wider text-blue-700">Service catalogue</p><h2 class="text-2xl font-black tracking-tight text-slate-900">Find a service</h2></div>
        <span class="text-sm text-slate-500">{{ $services->count() }} available services</span>
    </div>
</x-slot>
<div class="min-h-screen bg-slate-50">
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
@if(session('success'))<div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold text-emerald-800">{{ session('success') }}</div>@endif
<div class="rounded-3xl bg-slate-950 p-7 text-white shadow-xl sm:p-10">
    <p class="text-sm font-extrabold uppercase tracking-wider text-blue-300">Online Services</p>
    <h1 class="mt-2 text-3xl font-black sm:text-4xl">What service do you need?</h1>
    <p class="mt-3 max-w-2xl leading-7 text-slate-300">Choose a service below. You can review its description, requirements and fee before starting your application.</p>
    <div class="mt-6 flex max-w-xl items-center gap-3 rounded-2xl bg-white px-4 py-3 text-slate-400"><span>⌕</span><input type="text" placeholder="Search services..." class="w-full border-0 p-0 text-sm text-slate-900 outline-none focus:ring-0" oninput="filterServices(this.value)"></div>
</div>
<div id="service-grid" class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
@forelse($services as $service)
<article class="service-card flex flex-col rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-xl" data-name="{{ strtolower($service->name) }}">
    <div class="flex items-center justify-between"><span class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-xl">📋</span><span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">Available</span></div>
    <h3 class="mt-5 text-lg font-extrabold">{{ $service->name }}</h3>
    <p class="mt-2 flex-1 text-sm leading-6 text-slate-500">{{ $service->description }}</p>
    <div class="mt-5 grid grid-cols-2 gap-3 border-t border-slate-100 pt-4 text-sm"><div><span class="block text-xs text-slate-400">Requirements</span><strong>{{ $service->documents->where('is_active',true)->count() }}</strong></div><div><span class="block text-xs text-slate-400">Service fee</span><strong>TSh {{ number_format($service->service_fee,0) }}</strong></div></div>
    <a href="{{ route('applications.create',$service) }}" class="mt-5 rounded-xl bg-blue-700 px-4 py-3 text-center text-sm font-extrabold text-white hover:bg-blue-800">View requirements & Apply →</a>
</article>
@empty
<div class="col-span-full rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center text-slate-500">No services are currently available.</div>
@endforelse
</div>
</div></div>
<script>
function filterServices(query){const q=query.toLowerCase().trim();document.querySelectorAll('.service-card').forEach(c=>c.classList.toggle('hidden',!c.dataset.name.includes(q)));}
</script>
</x-app-layout>