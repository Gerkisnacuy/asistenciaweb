<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-red-500 leading-tight">
            {{ __('Editar Usuario: ') . $usuario->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-zinc-900 overflow-hidden shadow-sm sm:rounded-lg border border-red-900 p-6">
                <form method="POST" action="{{ route('usuarios.update', $usuario) }}" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <div>
                        <x-input-label for="name" :value="__('Nombre Completo')" class="text-zinc-300" />
                        <x-text-input id="name" name="name" type="text" class="block mt-1 w-full bg-zinc-800 border-zinc-700 text-white" :value="old('name', $usuario->name)" required />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="email" :value="__('Correo Electrónico')" class="text-zinc-300" />
                        <x-text-input id="email" name="email" type="email" class="block mt-1 w-full bg-zinc-800 border-zinc-700 text-white" :value="old('email', $usuario->email)" required />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="role" :value="__('Nivel de Acceso')" class="text-zinc-300" />
                        <select id="role" name="role" class="block mt-1 w-full bg-zinc-800 border-zinc-700 text-white rounded-md shadow-sm focus:ring-red-500">
                            <option value="assistant" {{ $usuario->role == 'assistant' ? 'selected' : '' }}>Asistente</option>
                            <option value="admin" {{ $usuario->role == 'admin' ? 'selected' : '' }}>Administrador</option>
                            <option value="super_admin" {{ $usuario->role == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                        </select>
                    </div>

                    <div class="mt-4 p-4 bg-zinc-800/50 border border-zinc-700 rounded">
                        <p class="text-sm text-zinc-400 mb-2 italic">Dejar en blanco si no desea cambiar la contraseña.</p>
                        <x-input-label for="password" :value="__('Nueva Contraseña')" class="text-zinc-300" />
                        <x-text-input id="password" name="password" type="password" class="block mt-1 w-full bg-zinc-800 border-zinc-700 text-white" />
                        
                        <x-input-label for="password_confirmation" :value="__('Confirmar Nueva Contraseña')" class="text-zinc-300 mt-2" />
                        <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="block mt-1 w-full bg-zinc-800 border-zinc-700 text-white" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <a href="{{ route('usuarios.index') }}" class="text-zinc-400 hover:text-white mr-4">Cancelar</a>
                        <x-primary-button class="bg-red-700 hover:bg-red-600">Guardar Cambios</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>