<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            {{-- Se cambió font-black por font-semibold --}}
            <h2 class="font-semibold text-xl text-custom-primary leading-tight tracking-tight">
                {{ __('Gestión de Usuarios') }}
            </h2>
            <a href="{{ route('usuarios.create') }}" class="bg-custom-secondary hover:opacity-90 text-white font-black py-2 px-6 rounded-lg text-[10px] uppercase tracking-widest shadow-sm transition-all flex items-center">
                <svg class="w-3.5 h-3.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                {{ __('Nuevo Usuario') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 dark:bg-green-900/30 border border-green-500 text-green-700 dark:text-green-200 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow-sm rounded-2xl border border-zinc-100 dark:border-zinc-800">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-zinc-50/50 dark:bg-zinc-800/30 border-b border-zinc-100 dark:border-zinc-800">
                                <th class="p-5 text-xs font-black uppercase tracking-widest text-custom-primary">Nombre</th>
                                <th class="p-5 text-xs font-black uppercase tracking-widest text-custom-primary">Correo Electrónico</th>
                                <th class="p-5 text-xs font-black uppercase tracking-widest text-custom-primary text-center">Nivel de Acceso</th>
                                <th class="p-5 text-xs font-black uppercase tracking-widest text-custom-primary text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                            @foreach($usuarios as $user)
                            <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-800/20 transition-colors">
                                <td class="p-5">
                                    <div class="font-black text-zinc-800 dark:text-zinc-100 text-sm uppercase tracking-tight">{{ $user->name }}</div>
                                    @if($user->id === Auth::id())
                                        <div class="text-[10px] text-custom-primary font-black uppercase mt-0.5 tracking-widest italic italic">Conectado ahora</div>
                                    @endif
                                </td>
                                <td class="p-5 text-zinc-600 dark:text-zinc-400 font-mono text-xs">
                                    {{ $user->email }}
                                </td>
                                <td class="p-5 text-center">
                                    <span class="px-3 py-1 rounded-md text-[10px] font-black tracking-widest uppercase
                                        {{ $user->role == 'super_admin' ? 'bg-custom-primary text-white' : 'bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 border border-zinc-200 dark:border-zinc-700' }}">
                                        {{ match($user->role) {
                                            'super_admin' => 'Super Administrador',
                                            'admin' => 'Administrador',
                                            'assistant' => 'Asistente',
                                            default => $user->role,
                                        } }}
                                    </span>
                                </td>
                                <td class="p-5 text-right">
                                    <div class="flex items-center justify-end space-x-1">
                                        {{-- Botón Editar (Estilo Personal) --}}
                                        <a href="{{ route('usuarios.edit', $user) }}" class="inline-flex items-center justify-center p-2 text-zinc-400 hover:text-blue-500 transition-colors" title="Editar">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>

                                        {{-- Botón Eliminar (Estilo Personal) --}}
                                        @if($user->id !== Auth::id())
                                        <form action="{{ route('usuarios.destroy', $user) }}" method="POST" class="inline-flex m-0 p-0">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="inline-flex items-center justify-center p-2 text-zinc-400 hover:text-red-500 transition-colors focus:outline-none" onclick="return confirm('¿Eliminar usuario?')" title="Eliminar">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
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