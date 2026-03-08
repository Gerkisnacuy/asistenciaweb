<x-guest-layout>
    <style>
        :root {
            --primary-color: {{ $settings['primary_color'] ?? '#ef4444' }};
        }
        
        /* Ajuste del color de acento de Tailwind (radio, checkbox, focus) */
        .form-input-custom:focus, .form-checkbox-custom:focus {
            border-color: var(--primary-color) !important;
            --tw-ring-color: var(--primary-color) !important;
        }
        .form-checkbox-custom {
            color: var(--primary-color) !important;
        }
    </style>

    <div class="flex justify-center mb-6">
        <a href="/">
            @if(!empty($settings['site_logo']))
                <img src="{{ asset('storage/' . $settings['site_logo']) }}" alt="Logo Institución" class="h-24 w-auto object-contain">
            @else
                <div class="p-3 rounded-2xl bg-gray-100">
                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                </div>
            @endif
        </a>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Correo Electrónico')" class="font-bold text-gray-700" />
            <x-text-input id="email" class="block mt-1 w-full form-input-custom" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Contraseña')" class="font-bold text-gray-700" />
            <x-text-input id="password" class="block mt-1 w-full form-input-custom" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 shadow-sm form-checkbox-custom" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Recordarme') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-custom-primary hover:text-red-600 font-medium" href="{{ route('password.request') }}">
                    {{ __('¿Olvidaste tu contraseña?') }}
                </a>
            @endif
        </div>

        <div class="mt-6">
            <button type="submit" class="w-full btn-custom py-3 rounded-lg font-bold text-lg shadow-lg">
                {{ __('Iniciar Sesión') }}
            </button>
        </div>
    </form>
</x-guest-layout>