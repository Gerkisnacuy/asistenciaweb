<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-custom-primary leading-tight">
                {{ __('Configuración Global del Sistema') }}
            </h2>
            
            {{-- Botón ajustado a Color Secundario y nuevo texto --}}
            <button type="submit" form="settingsForm" class="bg-custom-secondary hover:brightness-110 text-white px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all shadow-lg border border-black/5">
                {{ __('Guardar Cambios') }}
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form id="settingsForm" method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf @method('PATCH')

                {{-- Fila 1: Identidad, Contacto y Colores --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    
                    <div class="bg-white dark:bg-zinc-900 p-5 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm transition-colors duration-300">
                        <h3 class="text-custom-primary font-black mb-4 border-b border-custom-primary pb-2 text-sm uppercase tracking-wider">
                            Identidad Institucional
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <x-input-label value="Nombre de la Institución" class="text-[10px] text-zinc-500 dark:text-zinc-400 font-bold uppercase" />
                                <x-text-input name="site_name" type="text" class="w-full mt-1 text-sm" :value="$settings['site_name'] ?? ''" />
                            </div>
                            <div>
                                <x-input-label value="RIF" class="text-[10px] text-zinc-500 dark:text-zinc-400 font-bold uppercase" />
                                <x-text-input name="site_rif" type="text" class="w-full mt-1 text-sm" :value="$settings['site_rif'] ?? ''" />
                            </div>
                            <div>
                                <x-input-label value="Logo (Imagen)" class="text-[10px] text-zinc-500 dark:text-zinc-400 font-bold uppercase" />
                                <div class="mt-2 flex items-center space-x-3">
                                    @if(!empty($settings['site_logo']))
                                        <img src="{{ asset('storage/' . $settings['site_logo']) }}" class="h-10 w-10 rounded border border-zinc-200 p-1 bg-white">
                                    @endif
                                    <input name="site_logo" type="file" class="block w-full text-[10px] text-zinc-500 file:mr-4 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-[10px] file:font-semibold file:bg-zinc-100 dark:file:bg-zinc-800 file:text-zinc-700 dark:file:text-zinc-300 hover:file:bg-zinc-200" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-zinc-900 p-5 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm transition-colors duration-300">
                        <h3 class="text-custom-primary font-black mb-4 border-b border-custom-primary pb-2 text-sm uppercase tracking-wider">
                            Datos de Contacto
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <x-input-label value="Teléfono" class="text-[10px] text-zinc-500 dark:text-zinc-400 font-bold uppercase" />
                                <x-text-input name="site_phone" type="text" class="w-full mt-1 text-sm" :value="$settings['site_phone'] ?? ''" />
                            </div>
                            <div>
                                <x-input-label value="Correo Electrónico" class="text-[10px] text-zinc-500 dark:text-zinc-400 font-bold uppercase" />
                                <x-text-input name="site_email" type="email" class="w-full mt-1 text-sm" :value="$settings['site_email'] ?? ''" />
                            </div>
                            <div>
                                <x-input-label value="Dirección Física" class="text-[10px] text-zinc-500 dark:text-zinc-400 font-bold uppercase" />
                                <textarea name="site_address" class="w-full mt-1 bg-white dark:bg-zinc-800 border-zinc-300 dark:border-zinc-700 text-sm text-zinc-900 dark:text-white rounded-md shadow-sm focus:ring-custom-primary" rows="2">{{ $settings['site_address'] ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-zinc-900 p-5 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm transition-colors duration-300">
                        <h3 class="text-custom-primary font-black mb-4 border-b border-custom-primary pb-2 text-sm uppercase tracking-wider">
                            Paleta de Colores
                        </h3>
                        <div class="space-y-6">
                            <div class="flex items-center justify-between p-3 bg-zinc-50 dark:bg-zinc-800/50 rounded-lg">
                                <x-input-label value="Primario" class="text-[10px] text-zinc-600 dark:text-zinc-400 font-bold uppercase" />
                                <input name="primary_color" type="color" class="h-8 w-12 bg-transparent border-0 cursor-pointer" value="{{ $settings['primary_color'] ?? '#f21313' }}" />
                            </div>
                            <div class="flex items-center justify-between p-3 bg-zinc-50 dark:bg-zinc-800/50 rounded-lg">
                                <x-input-label value="Secundario" class="text-[10px] text-zinc-600 dark:text-zinc-400 font-bold uppercase" />
                                <input name="secondary_color" type="color" class="h-8 w-12 bg-transparent border-0 cursor-pointer" value="{{ $settings['secondary_color'] ?? '#1a1a1a' }}" />
                            </div>
                            <div>
                                <x-input-label value="Modo de Interfaz" class="text-[10px] text-zinc-500 dark:text-zinc-400 font-bold uppercase mb-2" />
                                <select name="theme_mode" class="w-full bg-white dark:bg-zinc-800 border-zinc-300 dark:border-zinc-700 text-sm text-zinc-900 dark:text-white rounded-md shadow-sm focus:ring-custom-primary">
                                    <option value="dark" {{ ($settings['theme_mode'] ?? '') == 'dark' ? 'selected' : '' }}>🌙 Modo Oscuro</option>
                                    <option value="light" {{ ($settings['theme_mode'] ?? '') == 'light' ? 'selected' : '' }}>☀️ Modo Claro</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Fila 2: Parámetros de Asistencia y Horarios --}}
                <div class="bg-white dark:bg-zinc-900 p-6 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm">
                    <h3 class="text-custom-primary font-black mb-6 border-b border-custom-primary pb-2 text-sm uppercase tracking-wider">
                        Configuración de Horarios Laborales
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div>
                            <x-input-label value="Hora de Entrada" class="text-[10px] text-zinc-500 dark:text-zinc-400 font-bold uppercase mb-2" />
                            <x-text-input name="p_hora_entrada" type="time" class="w-full text-sm" :value="$settings['p_hora_entrada'] ?? '07:00'" />
                            <p class="mt-2 text-[9px] text-zinc-400 italic">Cálculo base de retrasos.</p>
                        </div>

                        <div>
                            <x-input-label value="Tolerancia (Min)" class="text-[10px] text-zinc-500 dark:text-zinc-400 font-bold uppercase mb-2" />
                            <x-text-input name="p_tolerancia" type="number" class="w-full text-sm" :value="$settings['p_tolerancia'] ?? '0'" min="0" />
                            <p class="mt-2 text-[9px] text-zinc-400 italic">Minutos de gracia.</p>
                        </div>

                        <div>
                            <x-input-label value="Hora de Salida" class="text-[10px] text-zinc-500 dark:text-zinc-400 font-bold uppercase mb-2" />
                            <x-text-input name="p_hora_salida" type="time" class="w-full text-sm" :value="$settings['p_hora_salida'] ?? '15:00'" />
                            <p class="mt-2 text-[9px] text-zinc-400 italic">Cálculo base de salida.</p>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>