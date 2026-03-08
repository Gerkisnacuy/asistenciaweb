<div x-data="{ openModal: false, fileName: '' }">
    <x-app-layout>
        <x-slot name="header">
            <div class="flex justify-between items-center">
                <h2 class="font-black text-xl text-zinc-800 dark:text-zinc-100 uppercase tracking-tight">
                    {{ __('Gestión de Personal') }}
                </h2>
                <div class="flex items-center space-x-3">
                    <button 
                        type="button"
                        @click="openModal = true" 
                        class="bg-white dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300 px-4 py-2 rounded-xl border border-zinc-200 dark:border-zinc-700 text-[10px] font-black uppercase tracking-widest hover:border-custom-primary transition-all flex items-center shadow-sm"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        Importar Excel
                    </button>

                    <a href="{{ route('employees.create') }}" class="bg-zinc-800 hover:bg-zinc-900 text-white font-black py-2 px-6 rounded-xl text-[10px] uppercase tracking-widest shadow-lg flex items-center transition-all">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Nuevo Ingreso
                    </a>
                </div>
            </div>
        </x-slot>

        <div class="py-6">
            <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow-sm rounded-2xl border border-zinc-100 dark:border-zinc-800">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-zinc-50/50 dark:bg-zinc-800/30 border-b border-zinc-100 dark:border-zinc-800">
                            {{-- Títulos con color custom-primary --}}
                            <th class="p-5 text-[10px] font-black uppercase tracking-[0.2em] text-custom-primary">Personal</th>
                            <th class="p-5 text-[10px] font-black uppercase tracking-[0.2em] text-custom-primary text-center">Identificación</th>
                            <th class="p-5 text-[10px] font-black uppercase tracking-[0.2em] text-custom-primary text-center">Contacto</th>
                            <th class="p-5 text-[10px] font-black uppercase tracking-[0.2em] text-custom-primary text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                        @forelse($employees as $employee)
                        <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-800/20 transition-colors">
                            <td class="p-5">
                                <div class="flex items-center">
                                    <div class="h-12 w-12 shrink-0 rounded-full overflow-hidden border border-zinc-200 dark:border-zinc-700 bg-zinc-100 dark:bg-zinc-800">
                                        <img src="{{ $employee->foto ? asset('storage/' . $employee->foto) : 'https://ui-avatars.com/api/?name='.urlencode($employee->nombres) }}" class="h-full w-full object-cover">
                                    </div>
                                    <div class="ml-4">
                                        <div class="font-black text-zinc-800 dark:text-zinc-100 text-sm uppercase tracking-tight">{{ $employee->nombres }} {{ $employee->apellidos }}</div>
                                        {{-- Cargo resaltado con color custom-primary --}}
                                        <div class="text-[10px] text-custom-primary font-black uppercase mt-0.5 tracking-widest">{{ $employee->cargo }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="p-5 text-center font-mono text-xs text-zinc-600 dark:text-zinc-400">
                                {{ number_format($employee->cedula, 0, ',', '.') }}
                            </td>
                            <td class="p-5 text-center">
                                <div class="text-xs font-mono text-zinc-600 dark:text-zinc-400">{{ $employee->telefono ?? 'N/A' }}</div>
                                <div class="text-[10px] text-zinc-400 truncate max-w-[150px] mx-auto uppercase font-bold">{{ $employee->email }}</div>
                            </td>
                            <td class="p-5 text-right">
                                <div class="flex items-center justify-end space-x-1">
                                    {{-- Icono QR / Ver --}}
                                    <a href="{{ route('employees.show', $employee) }}" class="inline-flex items-center justify-center p-2 text-zinc-400 hover:text-custom-primary transition-colors" title="Ver QR">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                                        </svg>
                                    </a>

                                    {{-- Icono Editar --}}
                                    <a href="{{ route('employees.edit', $employee) }}" class="inline-flex items-center justify-center p-2 text-zinc-400 hover:text-blue-500 transition-colors" title="Editar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>

                                    {{-- Icono Borrar Alineado --}}
                                    <form action="{{ route('employees.destroy', $employee) }}" method="POST" class="inline-flex m-0 p-0">
                                        @csrf @method('DELETE')
                                        <button type="submit" 
                                            class="inline-flex items-center justify-center p-2 text-zinc-400 hover:text-red-500 transition-colors focus:outline-none" 
                                            onclick="return confirm('¿Eliminar registro?')" 
                                            title="Eliminar"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="p-10 text-center text-[10px] font-black text-zinc-400 uppercase tracking-widest">No hay registros</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="p-4 border-t border-zinc-100 dark:border-zinc-800">
                    {{ $employees->links() }}
                </div>
            </div>
        </div>

        {{-- MODAL DE IMPORTACIÓN --}}
        <div x-show="openModal" x-cloak class="fixed inset-0 z-[999] flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-zinc-950/80 backdrop-blur-sm" @click="openModal = false"></div>
            <div class="relative bg-white dark:bg-zinc-900 rounded-3xl border border-zinc-200 dark:border-zinc-800 w-full max-w-md p-8 shadow-2xl"
                 x-transition:enter="ease-out duration-300 transform" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-[10px] font-black text-custom-primary uppercase tracking-[0.4em]">Importar Datos</h3>
                    <button @click="openModal = false" class="text-zinc-400 text-xl">&times;</button>
                </div>

                <form action="{{ route('employees.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="bg-zinc-50 dark:bg-zinc-950 border-2 border-dashed border-zinc-200 dark:border-zinc-800 rounded-2xl p-10 text-center mb-6">
                        <input type="file" name="file" id="xl_input" class="hidden" accept=".xlsx, .xls, .csv" required @change="fileName = $event.target.files[0].name">
                        <label for="xl_input" class="cursor-pointer">
                            <svg class="w-12 h-12 mx-auto text-zinc-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <span x-text="fileName || 'Seleccionar archivo Excel'" class="text-[10px] font-black text-zinc-400 uppercase tracking-widest"></span>
                        </label>
                    </div>
                    <button type="submit" class="bg-custom-primary w-full py-4 rounded-xl text-[10px] font-black text-white uppercase tracking-widest shadow-xl">
                        Procesar Carga
                    </button>
                </form>
            </div>
        </div>
    </x-app-layout>
</div>