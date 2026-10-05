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
            <x-text-input id="password" class="input-modern mt-1 block w-full" type="password" name="password" aria-describedby="password-live-error" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" /><p id="password-live-error" class="mt-1 min-h-5 text-xs font-semibold text-red-600" aria-live="polite"></p>
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="input-modern mt-1 block w-full"
                                type="password" aria-describedby="password-confirmation-live-error"
                                name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" /><p id="password-confirmation-live-error" class="mt-1 min-h-5 text-xs font-semibold text-red-600" aria-live="polite"></p>
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="portal-button">
                {{ __('Reset Password') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
