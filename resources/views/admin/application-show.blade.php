<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-slate-800">Review Application #{{ $application->id }}</h2></x-slot>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            @if(session('success')) <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-emerald-800">{{ session('success') }}</div> @endif
            @if($errors->any())
                <div class="rounded-xl bg-red-50 border border-red-200 p-4 text-red-700"><ul class="list-disc ml-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
            @endif

            <div class="grid md:grid-cols-2 gap-6">
                <div class="bg-white rounded-2xl border p-6 shadow-sm">
                    <h3 class="font-bold text-lg mb-4">Application</h3>
                    <dl class="space-y-3 text-sm">
                        <div><dt class="text-slate-500">Customer</dt><dd class="font-semibold">{{ $application->user->name }} — {{ $application->user->email }}</dd></div>
                        <div><dt class="text-slate-500">Service</dt><dd class="font-semibold">{{ $application->service->name }}</dd></div>
                        <div><dt class="text-slate-500">Submitted</dt><dd>{{ $application->created_at->format('d M Y, H:i') }}</dd></div>
                        <div><dt class="text-slate-500">Notes</dt><dd>{{ $application->notes ?: 'No notes provided.' }}</dd></div>
                    </dl>

                    <form method="POST" action="{{ route('admin.applications.status',$application) }}" class="mt-6 flex gap-3">
                        @csrf @method('PATCH')
                        <select name="status" class="rounded-lg border-slate-300 flex-1">
                            @foreach(['pending','processing','completed','rejected'] as $status)
                                <option value="{{ $status }}" @selected($application->status === $status)>{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                        <button class="px-5 py-2 rounded-lg bg-blue-600 text-white font-bold hover:bg-blue-700">Update</button>
                    </form>
                </div>

                <div class="bg-white rounded-2xl border p-6 shadow-sm">
                    <h3 class="font-bold text-lg mb-4">Required Documents</h3>
                    <div class="space-y-2 max-h-80 overflow-y-auto">
                        @forelse($application->service->documents->where('is_active',true) as $required)
                            <div class="flex justify-between gap-3 p-3 rounded-lg bg-slate-50">
                                <span>{{ $required->name }}</span>
                                <span class="text-xs font-bold {{ $required->is_required ? 'text-red-600' : 'text-slate-500' }}">{{ $required->is_required ? 'Required' : 'Optional' }}</span>
                            </div>
                        @empty
                            <p class="text-slate-500">No document requirements configured.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border shadow-sm overflow-hidden">
                <div class="p-5 border-b"><h3 class="font-bold text-lg">Uploaded Documents</h3></div>
                <div class="divide-y">
                    @forelse($application->documents as $document)
                        <div class="p-5 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div>
                                <div class="font-bold">{{ $document->document_name }}</div>
                                <div class="text-sm text-slate-500">{{ $document->file_name }} · {{ number_format(($document->file_size ?? 0)/1024,1) }} KB</div>
                                <div class="text-xs mt-1">Status: <strong>{{ ucfirst($document->status) }}</strong></div>
                            </div>
                            <div class="flex gap-2">
                                <a target="_blank" href="{{ route('application.documents.view',$document) }}" class="px-4 py-2 rounded-lg border font-semibold">View</a>
                                <form method="POST" action="{{ route('admin.documents.status',$document) }}" class="flex gap-2">
                                    @csrf @method('PATCH')
                                    <select name="status" class="rounded-lg border-slate-300 text-sm">
                                        @foreach(['pending','approved','rejected'] as $status)<option value="{{ $status }}" @selected($document->status===$status)>{{ ucfirst($status) }}</option>@endforeach
                                    </select>
                                    <button class="px-4 py-2 rounded-lg bg-slate-900 text-white font-semibold">Save</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="p-10 text-center text-slate-500">No documents uploaded.</div>
                    @endforelse
                </div>
            </div>

            <a href="{{ route('admin.dashboard') }}" class="inline-block text-blue-600 font-bold">← Back to Admin Dashboard</a>
        </div>
    </div>
</x-app-layout>
