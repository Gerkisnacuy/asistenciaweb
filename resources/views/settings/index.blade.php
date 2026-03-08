<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-custom-primary leading-tight">
            {{ __('Configuración Global del Sistema') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf @method('PATCH')

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    
                    <div class="bg-white dark:bg-zinc-900 p-6 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm transition-colors duration-300">
                        <h3 class="text-zinc-800 dark:text-white font-black mb-4 border-b-2 border-custom-primary pb-2 text-sm uppercase tracking-wider">
                            Identidad Institucional
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <x-input-label value="Nombre de la Institución" class="text-zinc-500 dark:text-zinc-400 font-bold" />
                                <x-text-input name="site_name" type="text" class="w-full mt-1" :value="$settings['site_name']" />
                            </div>
                            <div>
                                <x-input-label value="RIF" class="text-zinc-500 dark:text-zinc-400 font-bold" />
                                <x-text-input name="site_rif" type="text" class="w-full mt-1" :value="$settings['site_rif']" />
                            </div>
                            <div>
                                <x-input-label value="Logo (Imagen)" class="text-zinc-500 dark:text-zinc-400 font-bold" />
                                <div class="mt-2 flex items-center space-x-3">
                                    @if(!empty($settings['site_logo']))
                                        <img src="{{ asset('storage/' . $settings['site_logo']) }}" class="h-10 w-10 rounded border border-zinc-200 p-1 bg-white">
                                    @endif
                                    <input name="site_logo" type="file" class="block w-full text-xs text-zinc-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-zinc-100 dark:file:bg-zinc-800 file:text-zinc-700 dark:file:text-zinc-300 hover:file:bg-zinc-200" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-zinc-900 p-6 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm transition-colors duration-300">
                        <h3 class="text-zinc-800 dark:text-white font-black mb-4 border-b-2 border-custom-primary pb-2 text-sm uppercase tracking-wider">
                            Datos de Contacto
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <x-input-label value="Teléfono" class="text-zinc-500 dark:text-zinc-400 font-bold" />
                                <x-text-input name="site_phone" type="text" class="w-full mt-1" :value="$settings['site_phone']" />
                            </div>
                            <div>
                                <x-input-label value="Correo Electrónico" class="text-zinc-500 dark:text-zinc-400 font-bold" />
                                <x-text-input name="site_email" type="email" class="w-full mt-1" :value="$settings['site_email']" />
                            </div>
                            <div>
                                <x-input-label value="Dirección Física" class="text-zinc-500 dark:text-zinc-400 font-bold" />
                                <textarea name="site_address" class="w-full mt-1 bg-white dark:bg-zinc-800 border-zinc-300 dark:border-zinc-700 text-zinc-900 dark:text-white rounded-md shadow-sm focus:ring-custom-primary" rows="2">{{ $settings['site_address'] }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-zinc-900 p-6 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm transition-colors duration-300">
                        <h3 class="text-zinc-800 dark:text-white font-black mb-4 border-b-2 border-custom-primary pb-2 text-sm uppercase tracking-wider">
                            Paleta de Colores
                        </h3>
                        <div class="space-y-6">
                            <div class="flex items-center justify-between p-3 bg-zinc-50 dark:bg-zinc-800/50 rounded-lg">
                                <x-input-label value="Primario (Títulos)" class="text-zinc-600 dark:text-zinc-400 font-bold" />
                                <input name="primary_color" type="color" class="h-10 w-16 bg-transparent border-0 cursor-pointer" value="{{ $settings['primary_color'] }}" />
                            </div>
                            <div class="flex items-center justify-between p-3 bg-zinc-50 dark:bg-zinc-800/50 rounded-lg">
                                <x-input-label value="Secundario (Botones)" class="text-zinc-600 dark:text-zinc-400 font-bold" />
                                <input name="secondary_color" type="color" class="h-10 w-16 bg-transparent border-0 cursor-pointer" value="{{ $settings['secondary_color'] }}" />
                            </div>
                            <div>
                                <x-input-label value="Modo de Interfaz" class="text-zinc-500 dark:text-zinc-400 font-bold mb-2" />
                                <select name="theme_mode" class="w-full bg-white dark:bg-zinc-800 border-zinc-300 dark:border-zinc-700 text-zinc-900 dark:text-white rounded-md shadow-sm focus:ring-custom-primary">
                                    <option value="dark" {{ $settings['theme_mode'] == 'dark' ? 'selected' : '' }}>🌙 Modo Oscuro</option>
                                    <option value="light" {{ $settings['theme_mode'] == 'light' ? 'selected' : '' }}>☀️ Modo Claro</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit" class="btn-custom py-4 px-10 rounded-xl font-black uppercase tracking-widest text-sm shadow-xl hover:scale-105 active:scale-95 transition-all">
                        Aplicar Cambios Globales
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>