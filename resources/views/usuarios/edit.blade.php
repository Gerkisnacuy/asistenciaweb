<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-xl text-custom-primary leading-tight tracking-tight">
                {{ __('Editar Usuario: ') . $usuario->name }}
            </h2>

            <div class="flex items-center space-x-3">
                <a href="{{ route('usuarios.index') }}" 
                   class="px-4 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest text-zinc-500 hover:text-custom-secondary transition-all">
                    {{ __('Cancelar') }}
                </a>

                {{-- El atributo form="editUserForm" asegura la conexión con el formulario de abajo --}}
                <button type="submit" form="editUserForm"
                        class="bg-custom-secondary hover:opacity-90 text-white px-6 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all shadow-sm">
                    {{ __('Guardar Cambios') }}
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            {{-- Añadimos un ID al formulario para vincularlo con el botón del header --}}
            <form id="editUserForm" method="POST" action="{{ route('usuarios.update', $usuario) }}">
                @csrf
                @method('PATCH')

                <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow-sm rounded-2xl border border-zinc-100 dark:border-zinc-800 p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Nombre --}}
                        <div class="md:col-span-2">
                            <x-input-label for="name" :value="__('Nombre Completo')" class="text-[10px] font-black uppercase tracking-widest text-custom-primary" />
                            <x-text-input id="name" name="name" type="text" class="block mt-1 w-full text-sm" :value="old('name', $usuario->name)" required />
                        </div>

                        {{-- Email --}}
                        <div>
                            <x-input-label for="email" :value="__('Correo Electrónico')" class="text-[10px] font-black uppercase tracking-widest text-custom-primary" />
                            <x-text-input id="email" name="email" type="email" class="block mt-1 w-full text-sm" :value="old('email', $usuario->email)" required />
                        </div>

                        {{-- Nivel de Acceso --}}
                        <div>
                            <x-input-label for="role" :value="__('Nivel de Acceso')" class="text-[10px] font-black uppercase tracking-widest text-custom-primary" />
                            <select id="role" name="role" class="block mt-1 w-full bg-white dark:bg-zinc-800 border-zinc-300 dark:border-zinc-700 text-sm text-zinc-900 dark:text-white rounded-md shadow-sm focus:ring-custom-primary focus:border-custom-primary">
                                <option value="assistant" {{ $usuario->role == 'assistant' ? 'selected' : '' }}>Asistente</option>
                                <option value="admin" {{ $usuario->role == 'admin' ? 'selected' : '' }}>Administrador</option>
                                <option value="super_admin" {{ $usuario->role == 'super_admin' ? 'selected' : '' }}>Super Administrador</option>
                            </select>
                        </div>

                        {{-- Sección de Contraseña --}}
                        <div class="md:col-span-2 mt-4 p-6 bg-zinc-50 dark:bg-zinc-800/50 rounded-xl border border-zinc-100 dark:border-zinc-800">
                            <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-zinc-400 mb-4 italic">
                                {{ __('Seguridad (Opcional)') }}
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="password" :value="__('Nueva Contraseña')" class="text-[10px] font-black uppercase tracking-widest text-zinc-500" />
                                    <x-text-input id="password" name="password" type="password" class="block mt-1 w-full text-sm" placeholder="••••••••" />
                                </div>
                                <div>
                                    <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" class="text-[10px] font-black uppercase tracking-widest text-zinc-500" />
                                    <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="block mt-1 w-full text-sm" placeholder="••••••••" />
                                </div>
                            </div>
                            <p class="text-[9px] text-zinc-400 mt-3 uppercase font-bold tracking-tight italic">
                                * Dejar en blanco para mantener la contraseña actual.
                            </p>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>