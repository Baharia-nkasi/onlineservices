<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" data-live-validate="register" novalidate>
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="input-modern mt-1 block w-full" aria-describedby="name-live-error" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" /><p id="name-live-error" class="mt-1 min-h-5 text-xs font-semibold text-red-600" aria-live="polite"></p>
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="input-modern mt-1 block w-full" aria-describedby="email-live-error" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" /><p id="email-live-error" class="mt-1 min-h-5 text-xs font-semibold text-red-600" aria-live="polite"></p>
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="input-modern mt-1 block w-full" aria-describedby="password-live-error"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" /><p id="password-live-error" class="mt-1 min-h-5 text-xs font-semibold text-red-600" aria-live="polite"></p>
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="input-modern mt-1 block w-full" aria-describedby="password-confirmation-live-error"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" /><p id="password-confirmation-live-error" class="mt-1 min-h-5 text-xs font-semibold text-red-600" aria-live="polite"></p>
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="portal-button ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
