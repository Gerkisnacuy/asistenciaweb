<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-red-500 leading-tight">
            {{ __('Crear Nuevo Usuario') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-zinc-900 overflow-hidden shadow-sm sm:rounded-lg border border-red-900 p-6">
                <form method="POST" action="{{ route('usuarios.store') }}" class="space-y-6">
                    @csrf

                    <div>
                        <x-input-label for="name" :value="__('Nombre Completo')" class="text-zinc-300" />
                        <x-text-input id="name" class="block mt-1 w-full bg-zinc-800 border-zinc-700 text-white focus:border-red-500 focus:ring-red-500" type="text" name="name" :value="old('name')" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="email" :value="__('Correo Electrónico')" class="text-zinc-300" />
                        <x-text-input id="email" class="block mt-1 w-full bg-zinc-800 border-zinc-700 text-white focus:border-red-500 focus:ring-red-500" type="email" name="email" :value="old('email')" required />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="role" :value="__('Nivel de Acceso')" class="text-zinc-300" />
                        <select id="role" name="role" class="block mt-1 w-full bg-zinc-800 border-zinc-700 text-white focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm">
                            <option value="assistant">{{ __('Asistente (Lectura/Registro)') }}</option>
                            <option value="admin">{{ __('Administrador (Gestión)') }}</option>
                            <option value="super_admin">{{ __('Super Admin (Control Total)') }}</option>
                        </select>
                        <x-input-error :messages="$errors->get('role')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="password" :value="__('Contraseña')" class="text-zinc-300" />
                        <x-text-input id="password" class="block mt-1 w-full bg-zinc-800 border-zinc-700 text-white focus:border-red-500 focus:ring-red-500" type="password" name="password" required />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" class="text-zinc-300" />
                        <x-text-input id="password_confirmation" class="block mt-1 w-full bg-zinc-800 border-zinc-700 text-white focus:border-red-500 focus:ring-red-500" type="password" name="password_confirmation" required />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <a href="{{ route('usuarios.index') }}" class="text-zinc-400 hover:text-white mr-4 transition">
                            {{ __('Cancelar') }}
                        </a>
                        <x-primary-button class="bg-red-700 hover:bg-red-600 focus:bg-red-800">
                            {{ __('Registrar Usuario') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>