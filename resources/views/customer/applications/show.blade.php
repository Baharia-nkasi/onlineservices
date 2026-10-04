<x-app-layout>
<x-slot name="header">
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-xs font-extrabold uppercase tracking-wider text-blue-700">Application portal</p>
            <h2 class="text-2xl font-black tracking-tight text-slate-900">Application #{{ $application->id }}</h2>
        </div>
        <a href="{{ route('customer.applications.index') }}" class="text-sm font-bold text-slate-500 hover:text-blue-700">← My Applications</a>
    </div>
</x-slot>

<div class="min-h-screen bg-slate-50 page-enter">
    <div class="mx-auto max-w-5xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold text-emerald-800 fade-up">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                <p class="font-extrabold">Please check the following:</p>
                <ul class="mt-2 list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        @php
            $statusClasses = match($application->status) {
                'pending' => 'bg-amber-100 text-amber-800',
                'processing' => 'bg-blue-100 text-blue-800',
                'completed' => 'bg-emerald-100 text-emerald-800',
                'rejected' => 'bg-red-100 text-red-800',
                default => 'bg-slate-100 text-slate-700',
            };
            $requirements = $application->service->documents->where('is_active', true);
            $requiredCount = $requirements->where('is_required', true)->count();
            $uploadedRequired = $requirements->where('is_required', true)->filter(fn($r) => $application->documents->contains(fn($d) => mb_strtolower($d->document_name) === mb_strtolower($r->name)))->count();
        @endphp

        <section class="relative overflow-hidden rounded-3xl bg-slate-950 p-7 text-white shadow-xl sm:p-9">
            <div class="pointer-events-none absolute -right-16 -top-20 h-64 w-64 rounded-full bg-blue-600/20 blur-3xl"></div>
            <div class="relative flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-sm font-extrabold uppercase tracking-wider text-blue-300">Service application</p>
                    <h1 class="mt-2 text-2xl font-black sm:text-3xl">{{ $application->service->name }}</h1>
                    <p class="mt-2 text-sm text-slate-300">Submitted {{ $application->created_at->format('d M Y, H:i') }}</p>
                </div>
                <span class="status-pill {{ $statusClasses }}">{{ ucfirst($application->status) }}</span>
            </div>
        </section>

        <section class="grid gap-4 sm:grid-cols-3">
            <div class="portal-card p-5"><p class="text-xs font-bold uppercase tracking-wide text-slate-400">Required documents</p><p class="mt-2 text-2xl font-black text-slate-900">{{ $requiredCount }}</p></div>
            <div class="portal-card p-5"><p class="text-xs font-bold uppercase tracking-wide text-slate-400">Uploaded required</p><p class="mt-2 text-2xl font-black text-slate-900">{{ $uploadedRequired }}/{{ $requiredCount }}</p></div>
            <div class="portal-card p-5"><p class="text-xs font-bold uppercase tracking-wide text-slate-400">Total uploaded</p><p class="mt-2 text-2xl font-black text-slate-900">{{ $application->documents->count() }}</p></div>
        </section>

        @if($application->notes)
            <section class="portal-card p-6">
                <h2 class="font-black text-slate-900">Additional information</h2>
                <p class="mt-2 text-sm leading-6 text-slate-600">{{ $application->notes }}</p>
            </section>
        @endif

        <section class="portal-card overflow-hidden">
            <div class="border-b border-slate-200 p-6">
                <h2 class="text-xl font-black text-slate-900">Document Requirements</h2>
                <p class="mt-1 text-sm text-slate-500">Upload each required document. Accepted files: PDF, JPG or PNG, up to 5 MB.</p>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($requirements as $requirement)
                    @php $uploaded = $application->documents->first(fn($d) => mb_strtolower($d->document_name) === mb_strtolower($requirement->name)); @endphp
                    <div class="flex flex-col gap-3 p-5 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex items-start gap-3">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl {{ $uploaded ? 'bg-emerald-100 text-emerald-700' : 'bg-blue-50 text-blue-700' }} font-black">{{ $uploaded ? '✓' : $loop->iteration }}</span>
                            <div>
                                <div class="font-extrabold text-slate-900">{{ $requirement->name }}</div>
                                @if($requirement->description)<p class="mt-1 text-sm leading-6 text-slate-500">{{ $requirement->description }}</p>@endif
                                <span class="mt-1 inline-block text-xs font-bold {{ $requirement->is_required ? 'text-red-600' : 'text-slate-500' }}">{{ $requirement->is_required ? 'Required' : 'Optional' }}</span>
                            </div>
                        </div>
                        <span class="status-pill {{ $uploaded ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">{{ $uploaded ? 'Uploaded' : 'Pending' }}</span>
                    </div>
                @empty
                    <div class="p-10 text-center text-sm text-slate-500">No document requirements configured.</div>
                @endforelse
            </div>

            @if($requirements->count())
                <form method="POST" action="{{ route('application.documents.store',$application) }}" enctype="multipart/form-data" class="border-t border-slate-200 bg-slate-50 p-6" x-data="{fileName: ''}">
                    @csrf
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="block text-sm font-extrabold text-slate-800" for="document_name">Document</label>
                            <select name="document_name" id="document_name" required class="input-modern mt-2">
                                <option value="">Select a requirement</option>
                                @foreach($requirements as $requirement)
                                    @php $alreadyUploaded = $application->documents->contains(fn($d) => mb_strtolower($d->document_name) === mb_strtolower($requirement->name)); @endphp
                                    <option value="{{ $requirement->name }}" @disabled($alreadyUploaded)>{{ $requirement->name }}{{ $requirement->is_required ? ' *' : '' }}{{ $alreadyUploaded ? ' — uploaded' : '' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-extrabold text-slate-800" for="document">File</label>
                            <input @change="fileName = $event.target.files[0]?.name || ''" type="file" name="document" id="document" accept=".pdf,.jpg,.jpeg,.png" required class="input-modern mt-2 file:mr-3 file:rounded-lg file:border-0 file:bg-blue-50 file:px-3 file:py-2 file:font-bold file:text-blue-700">
                            <p class="mt-2 text-xs text-slate-500" x-text="fileName || 'Maximum file size: 5 MB'"></p>
                        </div>
                    </div>
                    <button type="submit" class="portal-button mt-5">Upload Document</button>
                </form>
            @endif
        </section>

        <section class="portal-card overflow-hidden">
            <div class="border-b border-slate-200 p-5"><h2 class="font-black text-lg text-slate-900">Uploaded Documents</h2></div>
            <div class="divide-y divide-slate-100">
                @forelse($application->documents as $document)
                    <div class="flex flex-col gap-4 p-5 md:flex-row md:items-center md:justify-between">
                        <div class="min-w-0">
                            <div class="font-extrabold text-slate-900">{{ $document->document_name }}</div>
                            <div class="mt-1 truncate text-sm text-slate-500">{{ $document->file_name }} · {{ number_format(($document->file_size ?? 0)/1024,1) }} KB</div>
                            <div class="mt-2 text-xs font-bold text-slate-600">Review: {{ ucfirst($document->status) }}</div>
                        </div>
                        <div class="flex shrink-0 gap-2">
                            <a target="_blank" rel="noopener" href="{{ route('application.documents.view',$document) }}" class="portal-button-secondary">View</a>
                            @if($document->status === 'pending')
                                <form method="POST" action="{{ route('application.documents.destroy',$document) }}" x-data @submit="confirmAction('Delete this document?').submit($event)">
                                    @csrf @method('DELETE')
                                    <button class="rounded-xl bg-red-600 px-4 py-3 text-sm font-extrabold text-white hover:bg-red-700">Delete</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-10 text-center text-slate-500">No documents uploaded yet.</div>
                @endforelse
            </div>
        </section>
    </div>
</div>
</x-app-layout>
