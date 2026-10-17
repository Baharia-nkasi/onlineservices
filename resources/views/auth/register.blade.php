<x-guest-layout>
    <style>
      /* Keep the registration destination visually connected to the public gold landing page. */
      body:has(form[data-live-validate="register"]) { background:#fbf7ed!important; color:#21180b!important; }
      body:has(form[data-live-validate="register"]) :is(h1,h2,h3) { color:#21180b!important; }
      body:has(form[data-live-validate="register"]) form[data-live-validate="register"] {
        color:#21180b; background:#fffefa; border:1px solid #e4d3a0; border-radius:1.25rem;
        padding:clamp(1.25rem,4vw,2rem); box-shadow:0 16px 44px rgb(67 48 13 / 10%);
      }
      body:has(form[data-live-validate="register"]) form[data-live-validate="register"] label { color:#382a13!important; font-weight:700; }
      body:has(form[data-live-validate="register"]) form[data-live-validate="register"] input:not([type="checkbox"]):not([type="radio"]) {
        color:#21180b!important; background:#fffefa!important; border:1px solid #cdbb8a!important; border-radius:.7rem;
        min-height:2.8rem; box-shadow:none;
      }
      body:has(form[data-live-validate="register"]) form[data-live-validate="register"] input::placeholder { color:#75684f!important; opacity:1; }
      body:has(form[data-live-validate="register"]) form[data-live-validate="register"] input:focus {
        border-color:#9b6d00!important; outline:3px solid rgb(212 160 23 / 22%); outline-offset:1px;
      }
      body:has(form[data-live-validate="register"]) form[data-live-validate="register"] a { color:#684900!important; }
      body:has(form[data-live-validate="register"]) form[data-live-validate="register"] button {
        background:linear-gradient(135deg,#f2d276,#d4a017)!important; color:#21180b!important;
        border:1px solid #b8860b!important; border-radius:.7rem; font-weight:800; min-height:2.8rem;
      }
      body:has(form[data-live-validate="register"]) form[data-live-validate="register"] button:hover {
        background:#b8860b!important; color:#fff!important;
      }
      @media(max-width:480px) {
        body:has(form[data-live-validate="register"]) form[data-live-validate="register"] { padding:1.1rem; border-radius:1rem; }
      }
    </style>
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
            <a class="auth-switch-button" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="portal-button ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
