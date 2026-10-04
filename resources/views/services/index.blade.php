<x-app-layout>
<x-slot name="header"><h2 class="font-semibold text-xl text-slate-800">Available Services</h2></x-slot>
<div class="py-8 bg-slate-50 min-h-screen"><div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
@if(session('success'))<div class="mb-6 rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-emerald-800">{{ session('success') }}</div>@endif
<div class="mb-7"><h1 class="text-3xl font-extrabold text-slate-900">Available Services</h1><p class="mt-1 text-slate-500">Choose a service, submit your application and upload the required documents.</p></div>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
@forelse($services as $service)
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 flex flex-col">
<div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">📋</div>
<h3 class="mt-4 text-lg font-bold text-slate-900">{{ $service->name }}</h3>
<p class="mt-2 text-sm text-slate-500 flex-1">{{ $service->description }}</p>
<div class="mt-4 flex items-center justify-between text-sm"><span class="text-slate-500">Requirements</span><strong>{{ $service->documents->where('is_active',true)->count() }}</strong></div>
<div class="mt-2 text-sm"><span class="font-semibold">Service Fee:</span> TSh {{ number_format($service->service_fee,2) }}</div>
<a href="{{ route('applications.create',$service) }}" class="mt-5 text-center px-5 py-2.5 rounded-lg bg-blue-600 text-white font-bold hover:bg-blue-700">Apply Now</a>
</div>
@empty
<div class="col-span-full bg-white rounded-2xl border p-12 text-center text-slate-500">No services are currently available.</div>
@endforelse
</div></div></div>
</x-app-layout>