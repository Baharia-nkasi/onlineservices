<x-app-layout>
<x-slot name="header"><h2 class="font-semibold text-xl text-slate-800">My Applications</h2></x-slot>
<div class="py-8 bg-slate-50 min-h-screen"><div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
<div class="flex items-end justify-between gap-4 mb-6"><div><h1 class="text-3xl font-extrabold text-slate-900">My Applications</h1><p class="mt-1 text-slate-500">Track your submitted applications and documents.</p></div><a href="{{ route('services.index') }}" class="px-5 py-2.5 rounded-lg bg-blue-600 text-white font-bold">New Application</a></div>
<div class="space-y-4">
@forelse($applications as $application)
@php $badge=match($application->status){'pending'=>'bg-amber-100 text-amber-800','processing'=>'bg-blue-100 text-blue-800','completed'=>'bg-emerald-100 text-emerald-800','rejected'=>'bg-red-100 text-red-800',default=>'bg-slate-100 text-slate-700'}; @endphp
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5"><div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4"><div><div class="text-xs text-slate-500">Application #{{ $application->id }}</div><h2 class="mt-1 text-lg font-bold">{{ $application->service->name }}</h2><div class="mt-1 text-sm text-slate-500">Submitted {{ $application->created_at->format('d M Y, H:i') }}</div></div><div class="flex items-center gap-3"><span class="px-3 py-1.5 rounded-full text-xs font-bold {{ $badge }}">{{ ucfirst($application->status) }}</span><a href="{{ route('customer.applications.show',$application) }}" class="px-4 py-2 rounded-lg border font-semibold">View Details</a></div></div></div>
@empty
<div class="bg-white rounded-2xl border p-12 text-center"><div class="text-4xl">📋</div><h2 class="mt-4 text-xl font-bold">No applications yet</h2><p class="mt-2 text-slate-500">Choose a service to start your first application.</p><a href="{{ route('services.index') }}" class="inline-block mt-5 px-5 py-2.5 rounded-lg bg-blue-600 text-white font-bold">Browse Services</a></div>
@endforelse
</div></div></div>
</x-app-layout>