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
            <x-text-input id="password" class="neon-input mt-2 block w-full" aria-describedby="password-live-error"
                            type="password" name="password" required autocomplete="current-password" placeholder="Enter your password" />
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
</x-guest-layout>