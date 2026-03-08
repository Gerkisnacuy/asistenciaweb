<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-custom-primary leading-tight">
                {{ __('Gestión de Usuarios') }}
            </h2>
            <a href="{{ route('usuarios.create') }}" class="btn-custom font-bold py-2 px-6 rounded-lg text-sm shadow-md">
                + Nuevo Usuario
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Alertas de Éxito/Error --}}
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 dark:bg-green-900/30 border border-green-500 text-green-700 dark:text-green-200 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Contenedor de Tabla Adaptativo --}}
            <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow-sm sm:rounded-xl border border-zinc-200 dark:border-zinc-800">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-zinc-50 dark:bg-zinc-800/50 border-b border-zinc-200 dark:border-zinc-800 text-custom-primary uppercase text-xs tracking-widest">
                                <th class="p-5 font-black">Nombre</th>
                                <th class="p-5 font-black">Correo Electrónico</th>
                                <th class="p-5 font-black text-center">Nivel de Acceso</th>
                                <th class="p-5 font-black text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                            @foreach($usuarios as $user)
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/40 transition duration-150">
                                <td class="p-5">
                                    <div class="font-bold text-zinc-800 dark:text-zinc-100">{{ $user->name }}</div>
                                    @if($user->id === Auth::id())
                                        <span class="text-[10px] text-custom-primary italic font-medium">Conectado ahora</span>
                                    @endif
                                </td>
                                <td class="p-5 text-zinc-600 dark:text-zinc-400 text-sm">
                                    {{ $user->email }}
                                </td>
                                <td class="p-5 text-center">
                                    <span class="px-3 py-1 rounded-md text-[10px] font-black tracking-tighter uppercase
                                        {{ $user->role == 'super_admin' ? 'bg-custom-primary text-white' : 'bg-zinc-200 dark:bg-zinc-700 text-zinc-700 dark:text-zinc-300' }}">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td class="p-5 text-right">
                                    <div class="flex justify-end items-center space-x-4">
                                        {{-- Editar --}}
                                        <a href="{{ route('usuarios.edit', $user) }}" class="text-zinc-400 hover:text-custom-primary transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        </a>

                                        {{-- Eliminar (Solo si no es el usuario actual) --}}
                                        @if($user->id !== Auth::id())
                                        <form action="{{ route('usuarios.destroy', $user) }}" method="POST" onsubmit="return confirm('¿Confirmar eliminación de usuario?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-zinc-400 hover:text-red-500 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>