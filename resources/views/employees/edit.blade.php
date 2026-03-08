<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center">
                <a href="{{ route('employees.index') }}" class="mr-3 p-1.5 text-zinc-400 hover:text-custom-primary hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <h2 class="font-extrabold text-xl text-zinc-900 dark:text-zinc-100 tracking-tight uppercase">
                    {{ __('Editar Registro de Personal') }}
                </h2>
            </div>
            
            <div class="flex items-center space-x-2">
                <a href="{{ route('employees.index') }}" class="px-4 py-2 text-sm font-bold text-zinc-600 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-lg transition-colors">
                    {{ __('Cancelar') }}
                </a>
                <button form="employeeForm" type="submit" class="btn-custom py-2 px-5 rounded-lg text-sm font-bold shadow-md hover:brightness-110 active:scale-95 transition-all flex items-center">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4h16v16H4V4z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v8m-4-4h8"/></svg>
                    {{ __('Actualizar Ficha') }}
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto">
            {{-- CORRECCIÓN: Se añade explícitamente el parámetro 'personal' para coincidir con el resource de la ruta --}}
            <form id="employeeForm" action="{{ route('employees.update', $employee) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    
                    <div class="lg:col-span-1 space-y-6">
                        <div class="bg-white dark:bg-zinc-900 p-6 rounded-2xl border border-zinc-100 dark:border-zinc-800/50 shadow-inner flex flex-col items-center">
                            <label class="block text-[10px] font-black text-custom-primary uppercase tracking-[0.2em] mb-4">Área de Fotografía</label>
                            
                            <div class="relative group">
                                <div id="preview-container" class="w-36 h-36 rounded-2xl bg-zinc-50 dark:bg-zinc-950 border border-dashed border-zinc-200 dark:border-zinc-700/50 overflow-hidden flex items-center justify-center mx-auto shadow-inner transition-colors group-hover:border-custom-primary">
                                    @if($employee->foto)
                                        <img id="preview-image" src="{{ asset('storage/' . $employee->foto) }}" class="w-full h-full object-cover">
                                        <svg id="placeholder-icon" class="hidden w-10 h-10 text-zinc-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    @else
                                        <svg id="placeholder-icon" class="w-10 h-10 text-zinc-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <img id="preview-image" class="hidden w-full h-full object-cover">
                                    @endif
                                </div>
                                <label for="foto" class="absolute -bottom-1 -right-1 bg-white dark:bg-zinc-800 p-1.5 rounded-xl border border-zinc-100 dark:border-zinc-700 shadow-md cursor-pointer hover:scale-110 hover:border-custom-primary transition-all text-zinc-400 hover:text-custom-primary">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </label>
                            </div>
                            <input type="file" name="foto" id="foto" class="hidden" accept="image/*" onchange="previewFile()">
                            
                            <div class="mt-4 p-3 rounded-lg bg-zinc-50 dark:bg-zinc-950 border border-zinc-100 dark:border-zinc-800 text-center w-full">
                                <p class="text-[9px] font-bold text-zinc-500 uppercase tracking-widest leading-relaxed">Editar Fotografía</p>
                                <p class="text-[9px] font-mono text-zinc-400">Dejar vacío para mantener actual</p>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-2">
                        <div class="bg-white dark:bg-zinc-900 p-8 rounded-2xl border border-zinc-100 dark:border-zinc-800/50 shadow-inner">
                            <h3 class="text-xs font-black text-custom-primary uppercase tracking-[0.3em] mb-6 border-b border-zinc-100 dark:border-zinc-800 pb-2">Datos de Identidad</h3>
                            
                            <div class="grid grid-cols-6 gap-x-5 gap-y-4">
                                <div class="col-span-3">
                                    <x-input-label for="cedula" :value="__('Cédula de Identidad (DNI)')" class="font-bold text-zinc-500 uppercase text-[10px] tracking-wider mb-1" />
                                    <x-text-input id="cedula" name="cedula" type="number" class="block w-full py-2 px-3 text-sm font-mono tracking-tight bg-zinc-50 dark:bg-zinc-950/50 border border-zinc-100 dark:border-zinc-800 focus:ring-1 focus:ring-custom-primary" :value="old('cedula', $employee->cedula)" required />
                                    <x-input-error :messages="$errors->get('cedula')" class="mt-1 text-[10px]" />
                                </div>

                                <div class="col-span-3">
                                    <x-input-label for="nombres" :value="__('Nombres Completos')" class="font-bold text-zinc-500 uppercase text-[10px] tracking-wider mb-1" />
                                    <x-text-input id="nombres" name="nombres" type="text" class="block w-full py-2 px-3 text-sm bg-zinc-50 dark:bg-zinc-950/50 border border-zinc-100 dark:border-zinc-800" :value="old('nombres', $employee->nombres)" required />
                                </div>

                                <div class="col-span-3">
                                    <x-input-label for="apellidos" :value="__('Apellidos Completos')" class="font-bold text-zinc-500 uppercase text-[10px] tracking-wider mb-1" />
                                    <x-text-input id="apellidos" name="apellidos" type="text" class="block w-full py-2 px-3 text-sm bg-zinc-50 dark:bg-zinc-950/50 border border-zinc-100 dark:border-zinc-800" :value="old('apellidos', $employee->apellidos)" required />
                                </div>

                                <div class="col-span-3">
                                    <x-input-label for="cargo" :value="__('Cargo / Función')" class="font-bold text-zinc-500 uppercase text-[10px] tracking-wider mb-1" />
                                    <select name="cargo" id="cargo" class="block w-full py-2 px-3 text-sm rounded-lg border border-zinc-100 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-950/50 focus:ring-1 focus:ring-custom-primary text-zinc-700 dark:text-zinc-300">
                                        @foreach(['Docente', 'Administrativo', 'Obrero', 'Directivo'] as $cargo)
                                            <option value="{{ $cargo }}" {{ old('cargo', $employee->cargo) == $cargo ? 'selected' : '' }}>{{ $cargo }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <h3 class="col-span-6 text-xs font-black text-custom-primary uppercase tracking-[0.3em] mt-6 mb-2 border-b border-zinc-100 dark:border-zinc-800 pb-2">Información de Contacto</h3>

                                <div class="col-span-3">
                                    <x-input-label for="telefono" :value="__('Teléfono Móvil')" class="font-bold text-zinc-500 uppercase text-[10px] tracking-wider mb-1" />
                                    <x-text-input id="telefono" name="telefono" type="text" class="block w-full py-2 px-3 text-sm bg-zinc-50 dark:bg-zinc-950/50 border border-zinc-100 dark:border-zinc-800" :value="old('telefono', $employee->telefono)" placeholder="0412-0000000" />
                                </div>

                                <div class="col-span-3">
                                    <x-input-label for="email" :value="__('Correo Electrónico')" class="font-bold text-zinc-500 uppercase text-[10px] tracking-wider mb-1" />
                                    <x-text-input id="email" name="email" type="email" class="block w-full py-2 px-3 text-sm bg-zinc-50 dark:bg-zinc-950/50 border border-zinc-100 dark:border-zinc-800" :value="old('email', $employee->email)" placeholder="ejemplo@correo.com" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewFile() {
            const preview = document.querySelector('#preview-image');
            const placeholder = document.querySelector('#placeholder-icon');
            const file = document.querySelector('#foto').files[0];
            const reader = new FileReader();

            reader.onloadend = function () {
                preview.src = reader.result;
                preview.classList.remove('hidden');
                if(placeholder) placeholder.classList.add('hidden');
            }

            if (file) {
                reader.readAsDataURL(file);
            }
        }
    </script>
</x-app-layout>