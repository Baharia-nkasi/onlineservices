<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" data-live-validate="login" novalidate class="space-y-1">
        @csrf
        <div>
            <x-input-label for="email" class="neon-label" :value="__('Email')" />
            <x-text-input id="email" class="neon-input mt-2 block w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="you@example.com" aria-describedby="email-live-error" />
            <p id="email-live-error" class="mt-1 min-h-5 text-xs font-semibold text-red-400" aria-live="polite"></p>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-400" />
        </div>

        <div class="mt-5">
            <x-input-label for="password" class="neon-label" :value="__('Password')" />
            <div class="password-visibility-field relative mt-2">
                <x-text-input id="password" class="neon-input block w-full pr-12" aria-describedby="password-live-error"
                            type="password" name="password" required autocomplete="current-password" placeholder="Enter your password" />
                <button type="button" class="password-visibility-toggle absolute inset-y-0 right-0 z-10 inline-flex items-center justify-center rounded-r-xl border-0 px-3 text-stone-700 shadow-none transition hover:bg-amber-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-amber-700" data-password-visibility-toggle aria-controls="password" aria-label="Show password" aria-pressed="false">
                <svg data-eye-open aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/></svg>
                <svg data-eye-closed aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="hidden h-5 w-5"><path d="m3 3 18 18"/><path d="M10.6 10.6a2 2 0 0 0 2.8 2.8"/><path d="M9.9 5.2A10.9 10.9 0 0 1 12 5c6.4 0 10 7 10 7a16.8 16.8 0 0 1-3.1 3.9"/><path d="M6.6 6.6C3.7 8.5 2 12 2 12s3.6 7 10 7a10.7 10.7 0 0 0 4.2-.9"/></svg>
                <span class="sr-only">Show password</span>
            </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-400" />
            <p id="password-live-error" class="mt-1 min-h-5 text-xs font-semibold text-red-400" aria-live="polite"></p>
        </div>

        <div class="mt-5 flex items-center justify-between gap-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="h-4 w-4 rounded border-[#31503e] bg-[#07100b] text-[#00ff66] shadow-none focus:ring-[#00ff66]" name="remember">
                <span class="ms-2 text-sm font-semibold text-[#8fa79a]">{{ __('Remember me') }}</span>
            </label>
            @if (Route::has('password.request'))
                <a class="text-sm font-bold text-[#39ff14] transition hover:text-white focus:outline-none focus:ring-2 focus:ring-[#00ff66] focus:ring-offset-2 focus:ring-offset-[#08100b]" href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <button type="submit" class="neon-button neon-waka mt-7 w-full py-3.5 text-sm">
            <span>↳</span> {{ __('Log in') }}
        </button>
    </form>

    <div class="mt-6 flex flex-wrap items-center justify-between gap-3 border-t border-[#e4d3a0] pt-5">
        <span class="text-sm font-medium text-[#5f5545]">{{ __('New to Online Services?') }}</span>
        <a href="{{ route('register') }}" class="auth-switch-button">{{ __('Create an account') }}</a>
    </div>
</x-guest-layout>