<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-extrabold uppercase tracking-wider text-blue-700">Admin review</p>
                <h2 class="text-2xl font-black tracking-tight text-slate-900">Application #{{ $application->id }}</h2>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="text-sm font-bold text-slate-500 hover:text-blue-700">← Admin Dashboard</a>
        </div>
    </x-slot>

    <div class="min-h-screen bg-slate-50 page-enter">
        <div class="mx-auto max-w-6xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold text-emerald-800">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                    <p class="font-extrabold">Action could not be completed.</p>
                    <ul class="mt-2 list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            @php
                $requirements = $application->effectiveServiceRequirements()->sortBy('sort_order')->values();
                $standaloneRequired = $requirements->where('is_required', true)->whereNull('requirement_group');
                $requirementGroups = $requirements->where('is_required', true)->whereNotNull('requirement_group')->groupBy('requirement_group');
                $required = $standaloneRequired;
                $approvedRequired = $standaloneRequired->filter(fn($r) => $application->documents->contains(fn($d) => mb_strtolower($d->document_name) === mb_strtolower($r->name) && $d->status === 'approved' && $d->hasAvailableFile()))->count();
                $groupRequired = $requirementGroups->count();
                $groupSatisfied = $requirementGroups->filter(function ($groupRequirements) use ($application) {
                    $minimum = max(1, (int) $groupRequirements->max('minimum_required'));
                    $approved = $application->documents->where('status', 'approved')->filter(fn($d) => $d->hasAvailableFile() && $groupRequirements->contains(fn($r) => mb_strtolower($r->name) === mb_strtolower($d->document_name)))->count();
                    return $approved >= $minimum;
                })->count();
                $completionUnits = $standaloneRequired->count() + $groupRequired;
                $completedUnits = $approvedRequired + $groupSatisfied;
                $totalUploaded = $application->documents->count();
                $statusClasses = match($application->status) {
                    'pending' => 'bg-amber-100 text-amber-800',
                    'processing' => 'bg-blue-100 text-blue-800',
                    'approved' => 'bg-emerald-100 text-emerald-800',
                    'completed' => 'bg-emerald-100 text-emerald-800',
                    'rejected' => 'bg-red-100 text-red-800',
                    default => 'bg-slate-100 text-slate-700',
                };
                // Keep the four main review states visible in the status selector.
                // Backend transition rules still protect invalid state changes.
                $statusOptions = ['pending', 'processing', 'approved', 'rejected'];
            @endphp

            <section class="relative overflow-hidden rounded-3xl bg-slate-950 p-7 text-white shadow-xl sm:p-9">
                <div class="pointer-events-none absolute -right-20 -top-24 h-72 w-72 rounded-full bg-blue-600/20 blur-3xl"></div>
                <div class="relative flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="text-sm font-extrabold uppercase tracking-wider text-blue-300">{{ $application->service->name }}</p>
                        <h1 class="mt-2 text-3xl font-black">Review customer application</h1>
                        <p class="mt-2 text-sm text-slate-300">{{ $application->user->name }} · {{ $application->user->email }}</p>
                    </div>
                    <span class="status-pill {{ $statusClasses }}">{{ ucfirst($application->status) }}</span>
                </div>
            </section>

            <section class="portal-card flex flex-col gap-4 border border-red-200 bg-red-50 p-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-black uppercase tracking-wider text-red-700">Danger Zone</p>
                    <h2 class="mt-1 text-lg font-black text-red-950">Permanently delete this customer</h2>
                    <p class="mt-1 text-sm leading-6 text-red-800">
                        This removes <strong>{{ $application->user->name }}</strong>, all applications, uploaded files, notifications and related history.
                        The customer can register again as a completely new account.
                    </p>
                </div>
                <form method="POST" action="{{ route('admin.customers.destroy', $application->user) }}"
                      onsubmit="return confirm({{ Js::from('PERMANENT DELETE: This customer account and ALL of its applications, uploaded documents, notifications, sessions and history will be permanently removed. This cannot be undone. Continue?') }});"
                      class="shrink-0">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full rounded-xl bg-red-600 px-5 py-3 text-sm font-black text-white shadow-sm transition hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 sm:w-auto">
                        🗑 Delete Customer Permanently
                    </button>
                </form>
            </section>

            <section class="grid gap-4 sm:grid-cols-3">
                <div class="portal-card p-5"><p class="text-xs font-bold uppercase tracking-wide text-slate-400">Required</p><p class="mt-2 text-2xl font-black text-slate-900">{{ $required->count() }}</p></div>
                <div class="portal-card p-5"><p class="text-xs font-bold uppercase tracking-wide text-slate-400">Approved progress</p><p class="mt-2 text-2xl font-black text-slate-900">{{ $completedUnits }}/{{ $completionUnits }}</p><div class="mt-3 h-2 overflow-hidden rounded-full bg-slate-200"><div class="h-full rounded-full bg-blue-600" style="width: {{ $completionUnits ? min(100, round(($completedUnits / $completionUnits) * 100)) : 0 }}%"></div></div></div>
                @if($application->status === 'approved')
                    <div class="portal-card border-emerald-200 bg-emerald-50 p-5"><p class="text-xs font-bold uppercase tracking-wide text-emerald-700">Document review</p><p class="mt-2 text-lg font-black text-emerald-800">Cleared after approval</p></div>
                @else
                    <div class="portal-card p-5"><p class="text-xs font-bold uppercase tracking-wide text-slate-400">Uploaded files</p><p class="mt-2 text-2xl font-black text-slate-900">{{ $totalUploaded }}</p></div>
                @endif
            </section>

            <div class="grid gap-6 lg:grid-cols-3">
                <section class="portal-card p-6 lg:col-span-2">
                    <div>
                        <h2 class="text-xl font-black text-slate-900">Application details</h2>
                        <p class="mt-1 text-sm text-slate-500">Review the customer information before changing the application status.</p>
                    </div>

                    <dl class="mt-6 grid gap-5 sm:grid-cols-2">
                        <div><dt class="text-xs font-bold uppercase tracking-wide text-slate-400">Customer</dt><dd class="mt-1 font-extrabold text-slate-900">{{ $application->user->name }}</dd></div>
                        <div><dt class="text-xs font-bold uppercase tracking-wide text-slate-400">Email</dt><dd class="mt-1 font-extrabold text-slate-900 break-all">{{ $application->user->email }}</dd></div>
                        <div><dt class="text-xs font-bold uppercase tracking-wide text-slate-400">Submitted</dt><dd class="mt-1 font-semibold text-slate-700">{{ $application->created_at->format('d M Y, H:i') }}</dd></div>
                        <div><dt class="text-xs font-bold uppercase tracking-wide text-slate-400">Service</dt><dd class="mt-1 font-extrabold text-slate-900">{{ $application->service->name }}</dd></div>
                    </dl>

                    @if($application->notes)
                        <div class="mt-6 rounded-2xl bg-slate-50 p-4">
                            <p class="text-xs font-bold uppercase tracking-wide text-slate-400">Customer notes</p>
                            <p class="mt-2 text-sm leading-6 text-slate-700">{{ $application->notes }}</p>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.applications.status',$application) }}" class="mt-6 border-t border-slate-200 pt-6" x-data="{ status: @js($application->status) }">
                        @csrf @method('PATCH')
                        <label for="status" class="block text-sm font-extrabold text-slate-800">Application status</label>
                        <div class="mt-2 flex flex-col gap-3 sm:flex-row">
                            <select id="status" name="status" x-model="status" class="input-modern flex-1" {{ $application->status === 'completed' ? 'disabled' : '' }}>
                                @foreach($statusOptions as $status)
                                    <option value="{{ $status }}" @selected($application->status === $status)>{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                            @if($application->status === 'completed')
                                <input type="hidden" name="status" value="completed">
                            @endif
                            <button class="portal-button" {{ $application->status === 'completed' ? 'disabled' : '' }}>Update Status</button>
                        </div>

                        <div class="mt-4">
                            <label for="approval_remark" class="block text-sm font-extrabold text-slate-800">
                                <span x-show="status === 'rejected'">Rejection remark to customer</span>
                                <span x-show="status !== 'rejected'">Remark to customer</span>
                            </label>
                            <textarea
                                id="approval_remark"
                                name="approval_remark"
                                rows="3"
                                maxlength="2000"
                                class="input-modern mt-2"
                                :required="status === 'rejected'"
                                :placeholder="status === 'rejected'
                                    ? 'Explain briefly why this application was rejected and what the customer should correct before re-applying.'
                                    : 'Write a short remark for the customer. It will appear on their application dashboard when the application is approved.'"
                                {{ $application->status === 'completed' ? 'disabled' : '' }}
                            >{{ old('approval_remark', $application->approval_remark) }}</textarea>
                            <p class="mt-2 text-xs font-semibold text-slate-500" x-show="status === 'rejected'">A rejection remark is required. After rejection, this application leaves the active admin queue and the customer receives a notification with a direct link to start a new application.</p>
                            <p class="mt-2 text-xs font-semibold text-slate-500" x-show="status !== 'rejected'">This remark is saved with the application and shown to the customer.</p>
                        </div>

                        @if($application->status === 'completed')
                            <p class="mt-2 text-xs font-semibold text-slate-500">Completed applications are locked and cannot be reopened.</p>
                        @endif
                    </form>
                </section>

                <aside class="portal-card p-6">
                    <h2 class="text-lg font-black text-slate-900">Completion checklist</h2>
                    <p class="mt-1 text-sm text-slate-500">An application can only be completed when required documents and special groups are approved.</p>
                    <div class="mt-5 space-y-3">
                        <div class="flex items-center justify-between rounded-xl bg-slate-50 p-3">
                            <span class="text-sm font-bold text-slate-700">Required documents</span>
                            <span class="text-sm font-black {{ $approvedRequired === $required->count() ? 'text-emerald-700' : 'text-amber-700' }}">{{ $approvedRequired }}/{{ $required->count() }}</span>
                        </div>
                        @foreach($requirementGroups as $group => $groupRequirements)
                            @php
                                $minimum = max(1, (int) $groupRequirements->max('minimum_required'));
                                $approved = $application->documents->where('status','approved')->filter(fn($d) => $d->hasAvailableFile() && $groupRequirements->contains(fn($r) => mb_strtolower($r->name) === mb_strtolower($d->document_name)))->count();
                            @endphp
                            <div class="flex items-center justify-between rounded-xl bg-slate-50 p-3">
                                <span class="text-sm font-bold text-slate-700">{{ ucfirst(str_replace('_',' ',$group)) }}</span>
                                <span class="text-sm font-black {{ $approved >= $minimum ? 'text-emerald-700' : 'text-amber-700' }}">{{ $approved }}/{{ $minimum }}</span>
                            </div>
                        @endforeach
                    </div>
                </aside>
            </div>

            @if($application->status === 'approved')
                <section class="overflow-hidden rounded-2xl border border-emerald-200 bg-emerald-50 shadow-sm">
                    <div class="flex items-start gap-3 p-5">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-600 text-lg font-black text-white">✓</span>
                        <div>
                            <h2 class="font-black text-emerald-950">Uploaded Documents Cleared</h2>
                            <p class="mt-1 text-sm leading-6 text-emerald-800">All customer-uploaded documents were permanently removed when this application was approved. The application can now be kept as a clean approval record.</p>
                        </div>
                    </div>
                </section>
            @else
            <section class="portal-card overflow-hidden">
                <div class="border-b border-slate-200 p-6">
                    <h2 class="text-xl font-black text-slate-900">Uploaded Documents</h2>
                    <p class="mt-1 text-sm text-slate-500">Open each file, review it, then approve or reject it.</p>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse($application->documents as $document)
                        <div class="flex flex-col gap-4 p-5 xl:flex-row xl:items-center xl:justify-between">
                            <div class="min-w-0">
                                <div class="font-extrabold text-slate-900">{{ $document->document_name }}</div>
                                <div class="mt-1 truncate text-sm text-slate-500">{{ $document->file_name }} · {{ number_format(($document->file_size ?? 0)/1024,1) }} KB</div>
                                <span class="mt-2 inline-block rounded-full px-3 py-1 text-xs font-bold {{ $document->status === 'approved' ? 'bg-emerald-50 text-emerald-700' : ($document->status === 'rejected' ? 'bg-red-50 text-red-700' : 'bg-amber-50 text-amber-700') }}">{{ ucfirst($document->status) }}</span>
                            </div>

                            <div class="flex flex-col gap-2 sm:flex-row">
                                @if($document->hasAvailableFile())
                                    <a target="_blank" rel="noopener" href="{{ route('application.documents.view',$document) }}" class="portal-button-secondary text-center">{{ __('View File') }}</a>
                                    <a href="{{ route('application.documents.download',$document) }}" class="portal-button-secondary text-center">{{ __('Download') }}</a>
                                @else
                                    <span class="inline-flex items-center rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-extrabold text-amber-700 text-center">
                                        File unavailable — ask customer to re-upload
                                    </span>
                                @endif
                                <form method="POST" action="{{ route('admin.documents.destroy',$document) }}" class="shrink-0" onsubmit="return confirm('Delete this file? The customer will need to upload it again.');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-extrabold text-red-700 hover:bg-red-100" {{ $application->status === 'completed' ? 'disabled' : '' }}>Delete File</button>
                                </form>
                                <form method="POST" action="{{ route('admin.documents.status',$document) }}" class="flex flex-col gap-2 sm:flex-row">
                                    @csrf @method('PATCH')
                                    <select name="status" class="rounded-xl border-slate-300 text-sm" {{ $application->status === 'completed' ? 'disabled' : '' }}>
                                        @foreach(['pending','processing','approved','rejected'] as $status)
                                            <option value="{{ $status }}" @selected($document->status === $status)>{{ ucfirst($status) }}</option>
                                        @endforeach
                                    </select>
                                    <input type="text" name="notes" maxlength="1000" value="{{ old('notes', $document->notes) }}" placeholder="Review note (optional)" class="rounded-xl border-slate-300 text-sm" {{ $application->status === 'completed' ? 'disabled' : '' }}>
                                    <button class="rounded-xl bg-slate-900 px-4 py-3 text-sm font-extrabold text-white hover:bg-slate-800" {{ $application->status === 'completed' ? 'disabled' : '' }}>Save Review</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center text-slate-500">No documents have been uploaded yet.</div>
                    @endforelse
                </div>
            </section>
            @endif
        </div>
    </div>
</x-app-layout>