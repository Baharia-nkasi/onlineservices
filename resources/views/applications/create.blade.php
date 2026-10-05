<x-app-layout>
    <x-slot name="header">
        <div class="flex{{ __(' items') }}-center justify-between gap-4">
            <div>
                <p class="text-xs font-extrabold uppercase tracking-wider text-blue-700">{{ __('New application') }}</p>
                <h2 class="text-2xl font-black tracking-tight text-slate-900">{{ __('Apply for a service') }}</h2>
            </div>
            <a href="{{ route('services.index') }}" class="text-sm font-bold text-slate-500 hover:text-blue-700">← {{ __('Back to Services') }}</a>
        </div>
    </x-slot>

    <div class="min-h-screen bg-slate-50 py-8">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-6 lg:grid-cols-3">
                <div class="lg:col-span-2 space-y-6">
                    <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                        <div class="bg-slate-950 p-7 text-white sm:p-8">
                            <p class="text-sm font-extrabold uppercase tracking-wider text-blue-300">Service</p>
                            <h1 class="mt-2 text-2xl font-black sm:text-3xl">{{ $service->name }}</h1>
                            @if($service->description)
                                <p class="mt-3 leading-7 text-slate-300">{{ $service->description }}</p>
                            @endif
                        </div>

                        <div class="p-6 sm:p-8">
                            <div class="flex flex-wrap gap-3 text-sm">
                                <span class="rounded-full bg-blue-50 px-4 py-2 font-bold text-blue-700">
                                    {{ __('Service fee') }}: {{ number_format($service->service_fee, 0) }}
                                </span>
                                <span class="rounded-full bg-slate-100 px-4 py-2 font-bold text-slate-700">
                                    {{ __('Government fee') }}: {{ number_format($service->government_fee, 0) }}
                                </span>
                            </div>

                            <div class="mt-8">
                                <div class="flex{{ __(' items') }}-end justify-between gap-4">
                                    <div>
                                        <h2 class="text-xl font-black text-slate-900">{{ __('Required documents') }}</h2>
                                        <p class="mt-1 text-sm text-slate-500">{{ __('Prepare these documents before uploading them after creating your application.') }}</p>
                                    </div>
                                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-600">
                                        {{ $service->documents->where('is_active', true)->count() }} {{ __('items') }}
                                    </span>
                                </div>

                                <div class="mt-5 space-y-3">
                                    @forelse($service->documents->where('is_active', true) as $document)
                                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                            <div class="flex{{ __(' items') }}-start gap-3">
                                                <span class="mt-0.5 flex h-7 w-7 shrink-0{{ __(' items') }}-center justify-center rounded-full bg-blue-100 text-sm font-black text-blue-700">{{ $loop->iteration }}</span>
                                                <div class="min-w-0">
                                                    <h3 class="font-extrabold text-slate-900">
                                                        {{ $document->name }}
                                                        @if($document->is_required)
                                                            <span class="ml-1 text-red-600">*</span>
                                                        @endif
                                                    </h3>
                                                    @if($document->description)
                                                        <p class="mt-1 text-sm leading-6 text-slate-500">{{ $document->description }}</p>
                                                    @endif
                                                    <p class="mt-2 text-xs font-bold {{ $document->is_required ? 'text-red-600' : 'text-slate-500' }}">
                                                        {{ $document->is_required ? 'Required' : 'Optional' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="rounded-2xl border border-dashed border-slate-300 p-8 text-center text-sm text-slate-500">
                                            {{ __('No document requirements have been configured for this service.') }}
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            <form method="POST" action="{{ route('applications.store', $service) }}" class="mt-8 border-t border-slate-200 pt-8">
                                @csrf
                                <label for="notes" class="block text-sm font-extrabold text-slate-800">{{ __('Additional information') }} <span class="font-normal text-slate-400">{{ __('(optional)') }}</span></label>
                                <textarea id="notes" name="notes" rows="4" maxlength="2000"
                                    class="mt-2 w-full rounded-2xl border-slate-300 bg-white text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="{{ __('Add any information that may help with your application...') }}">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
                                @enderror

                                <div class="mt-5 rounded-2xl bg-amber-50 p-4 text-sm leading-6 text-amber-800">
                                    <strong>{{ __('Next step:') }}</strong> {{ __('Click “Start Application”. Your application will be created and you will then upload the required documents.') }}
                                </div>

                                <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                                    <a href="{{ route('services.index') }}" class="rounded-xl border border-slate-300 bg-white px-5 py-3 text-center text-sm font-extrabold text-slate-700 hover:bg-slate-50">{{ __('Cancel') }}</a>
                                    <button type="submit" class="rounded-xl bg-blue-700 px-6 py-3 text-sm font-extrabold text-white shadow-lg shadow-blue-700/20 hover:bg-blue-800">
                                        {{ __('Start Application →') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <aside class="lg:col-span-1">
                    <div class="sticky top-24 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="flex h-12 w-12{{ __(' items') }}-center justify-center rounded-2xl bg-blue-50 text-xl">✓</div>
                        <h2 class="mt-5 text-lg font-black text-slate-900">{{ __('How it works') }}</h2>
                        <ol class="mt-5 space-y-5">
                            <li class="flex gap-3">
                                <span class="flex h-7 w-7 shrink-0{{ __(' items') }}-center justify-center rounded-full bg-blue-700 text-xs font-black text-white">1</span>
                                <div><p class="font-bold text-slate-800">{{ __('Start application') }}</p><p class="mt-1 text-xs leading-5 text-slate-500">{{ __('Create your application for this service.') }}</p></div>
                            </li>
                            <li class="flex gap-3">
                                <span class="flex h-7 w-7 shrink-0{{ __(' items') }}-center justify-center rounded-full bg-blue-700 text-xs font-black text-white">2</span>
                                <div><p class="font-bold text-slate-800">{{ __('Upload documents') }}</p><p class="mt-1 text-xs leading-5 text-slate-500">{{ __('Upload the required PDF or image files.') }}</p></div>
                            </li>
                            <li class="flex gap-3">
                                <span class="flex h-7 w-7 shrink-0{{ __(' items') }}-center justify-center rounded-full bg-blue-700 text-xs font-black text-white">3</span>
                                <div><p class="font-bold text-slate-800">{{ __('Track progress') }}</p><p class="mt-1 text-xs leading-5 text-slate-500">{{ __('Follow your application status from your dashboard.') }}</p></div>
                            </li>
                        </ol>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</x-app-layout>
