<x-guest-layout>
    <form method="POST" action="{{ route('password.store') }}" data-live-validate="reset" novalidate>
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="input-modern mt-1 block w-full" type="email" aria-describedby="email-live-error" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" /><p id="email-live-error" class="mt-1 min-h-5 text-xs font-semibold text-red-600" aria-live="polite"></p>
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <div class="password-visibility-field relative mt-1">
                <x-text-input id="password" class="input-modern block w-full pr-12" type="password" name="password" aria-describedby="password-live-error" required autocomplete="new-password" />
                <button type="button" class="password-visibility-toggle absolute inset-y-0 right-0 z-10 inline-flex items-center justify-center rounded-r-xl border-0 px-3 text-stone-700 shadow-none transition hover:bg-amber-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-amber-700" data-password-visibility-toggle aria-controls="password" aria-label="Show password" aria-pressed="false">
                <svg data-eye-open aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/></svg>
                <svg data-eye-closed aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="hidden h-5 w-5"><path d="m3 3 18 18"/><path d="M10.6 10.6a2 2 0 0 0 2.8 2.8"/><path d="M9.9 5.2A10.9 10.9 0 0 1 12 5c6.4 0 10 7 10 7a16.8 16.8 0 0 1-3.1 3.9"/><path d="M6.6 6.6C3.7 8.5 2 12 2 12s3.6 7 10 7a10.7 10.7 0 0 0 4.2-.9"/></svg>
                <span class="sr-only">Show password</span>
            </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" /><p id="password-live-error" class="mt-1 min-h-5 text-xs font-semibold text-red-600" aria-live="polite"></p>
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <div class="password-visibility-field relative mt-1">
                <x-text-input id="password_confirmation" class="input-modern block w-full pr-12" type="password" aria-describedby="password-confirmation-live-error" name="password_confirmation" required autocomplete="new-password" />
                <button type="button" class="password-visibility-toggle absolute inset-y-0 right-0 z-10 inline-flex items-center justify-center rounded-r-xl border-0 px-3 text-stone-700 shadow-none transition hover:bg-amber-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-amber-700" data-password-visibility-toggle aria-controls="password_confirmation" aria-label="Show password" aria-pressed="false">
                <svg data-eye-open aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/></svg>
                <svg data-eye-closed aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="hidden h-5 w-5"><path d="m3 3 18 18"/><path d="M10.6 10.6a2 2 0 0 0 2.8 2.8"/><path d="M9.9 5.2A10.9 10.9 0 0 1 12 5c6.4 0 10 7 10 7a16.8 16.8 0 0 1-3.1 3.9"/><path d="M6.6 6.6C3.7 8.5 2 12 2 12s3.6 7 10 7a10.7 10.7 0 0 0 4.2-.9"/></svg>
                <span class="sr-only">Show password</span>
            </button>
            </div>

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" /><p id="password-confirmation-live-error" class="mt-1 min-h-5 text-xs font-semibold text-red-600" aria-live="polite"></p>
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="portal-button">
                {{ __('Reset Password') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
