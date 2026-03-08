<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-4">
                <h2 class="font-semibold text-xl text-custom-primary leading-tight">
                    {{ __('Reportes de Asistencia e Inasistencias') }}
                </h2>
                
                {{-- BOTÓN: CERRAR JORNADA (Procesar Inasistencias) --}}
                <form action="{{ route('inasistencias.procesar') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-custom-primary text-white px-4 py-2 rounded-2xl text-[10px] font-bold uppercase tracking-widest hover:brightness-110 transition-all shadow-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                        Cerrar Jornada Hoy
                    </button>
                </form>
            </div>

            {{-- Buscador y Filtros --}}
            <form method="GET" action="{{ route('reportes.index') }}" class="w-full md:w-auto inline-flex flex-wrap md:flex-nowrap items-center gap-2 bg-white dark:bg-zinc-800 p-1.5 rounded-2xl border border-zinc-200 dark:border-zinc-700 shadow-sm">
                <div class="relative w-full md:w-32"> 
                    <span class="absolute inset-y-0 left-0 flex items-center pl-2.5">
                        <svg class="w-3.5 h-3.5 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Buscar..." class="w-full pl-7 pr-2 py-1 border-none bg-zinc-50 dark:bg-zinc-900/50 rounded-xl text-[11px] font-medium text-zinc-700 dark:text-zinc-300 focus:ring-1 focus:ring-custom-secondary">
                </div>

                <div class="hidden md:block h-5 w-[1px] bg-zinc-200 dark:bg-zinc-700 mx-0.5"></div>

                <div class="flex items-center gap-2 px-1">
                    <div class="flex items-center gap-1.5">
                        <label class="text-[8px] uppercase font-bold text-zinc-400">Desde</label>
                        <input type="date" name="desde" value="{{ $fecha_inicio }}" class="border-none bg-transparent text-[10px] font-bold text-zinc-700 dark:text-zinc-300 focus:ring-0 p-0 w-24">
                    </div>
                    <div class="flex items-center gap-1.5">
                        <label class="text-[8px] uppercase font-bold text-zinc-400">Hasta</label>
                        <input type="date" name="hasta" value="{{ $fecha_fin }}" class="border-none bg-transparent text-[10px] font-bold text-zinc-700 dark:text-zinc-300 focus:ring-0 p-0 w-24">
                    </div>
                </div>

                <div class="flex items-center gap-1 ml-auto">
                    <button type="submit" class="bg-custom-secondary hover:brightness-105 text-white px-3 py-1 rounded-xl text-[9px] font-bold uppercase tracking-widest transition-all">Filtrar</button>
                    <a href="{{ route('reportes.index') }}" class="bg-zinc-100 dark:bg-zinc-700 hover:bg-zinc-200 text-zinc-500 p-1 rounded-xl transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </a>
                </div>
            </form>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- SECCIÓN DE INDICADORES (Mantenida igual) --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-7 gap-3 mb-8">
                <div class="bg-white dark:bg-zinc-900 p-3 rounded-3xl border border-zinc-200 dark:border-zinc-800 shadow-sm flex items-center">
                    <div class="p-2 bg-zinc-100 dark:bg-zinc-800 rounded-2xl mr-3 text-zinc-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    </div>
                    <div>
                        <p class="text-[8px] uppercase font-bold text-zinc-400 mb-1">Asistencias</p>
                        <h3 class="text-md font-bold text-zinc-800 dark:text-zinc-100 leading-none">{{ $total_registros }}</h3>
                    </div>
                </div>

                <div class="bg-white dark:bg-zinc-900 p-3 rounded-3xl border border-zinc-200 dark:border-zinc-800 shadow-sm flex items-center">
                    <div class="p-2 bg-orange-50 rounded-2xl mr-3 text-orange-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </div>
                    <div>
                        <p class="text-[8px] uppercase font-bold text-zinc-400 mb-1">Inasistencias</p>
                        <h3 class="text-md font-bold text-orange-600 leading-none">{{ $total_inasistencias }}</h3>
                    </div>
                </div>

                <div class="bg-white dark:bg-zinc-900 p-3 rounded-3xl border border-zinc-200 dark:border-zinc-800 shadow-sm flex items-center">
                    <div class="p-2 bg-green-50 rounded-2xl mr-3 text-green-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div>
                        <p class="text-[8px] uppercase font-bold text-zinc-400 mb-1">Puntuales</p>
                        <h3 class="text-md font-bold text-green-600 leading-none">{{ $total_puntuales }}</h3>
                    </div>
                </div>

                <div class="bg-white dark:bg-zinc-900 p-3 rounded-3xl border border-zinc-200 dark:border-zinc-800 shadow-sm flex items-center">
                    <div class="p-2 bg-red-50 rounded-2xl mr-3 text-custom-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div>
                        <p class="text-[8px] uppercase font-bold text-zinc-400 mb-1">Retrasos</p>
                        <h3 class="text-md font-bold text-custom-primary leading-none">{{ $total_retrasos }}</h3>
                    </div>
                </div>

                <div class="bg-white dark:bg-zinc-900 p-3 rounded-3xl border border-zinc-200 dark:border-zinc-800 shadow-sm flex items-center">
                    <div class="p-2 bg-blue-50 rounded-2xl mr-3 text-blue-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" /></svg>
                    </div>
                    <div>
                        <p class="text-[8px] uppercase font-bold text-zinc-400 mb-1">Sal. Puntual</p>
                        <h3 class="text-md font-bold text-blue-600 leading-none">{{ $total_salidas_puntuales }}</h3>
                    </div>
                </div>

                <div class="bg-white dark:bg-zinc-900 p-3 rounded-3xl border border-zinc-200 dark:border-zinc-800 shadow-sm flex items-center">
                    <div class="p-2 bg-amber-50 rounded-2xl mr-3 text-amber-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    </div>
                    <div>
                        <p class="text-[8px] uppercase font-bold text-zinc-400 mb-1">Anticipadas</p>
                        <h3 class="text-md font-bold text-amber-600 leading-none">{{ $total_salidas_anticipadas }}</h3>
                    </div>
                </div>

                <div class="bg-white dark:bg-zinc-900 p-3 rounded-3xl border border-zinc-200 dark:border-zinc-800 shadow-sm flex items-center">
                    <div class="p-2 bg-purple-50 rounded-2xl mr-3 text-purple-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3" /></svg>
                    </div>
                    <div>
                        <p class="text-[8px] uppercase font-bold text-zinc-400 mb-1">Min. Extras</p>
                        <h3 class="text-md font-bold text-purple-600 leading-none">{{ $total_tiempo_extra }}</h3>
                    </div>
                </div>
            </div>

            {{-- TABLA PRINCIPAL --}}
            <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow-sm sm:rounded-3xl border border-zinc-200 dark:border-zinc-800">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-zinc-50 dark:bg-zinc-800/50">
                                <th class="px-6 py-4 text-xs font-bold text-custom-primary uppercase border-b border-zinc-100 dark:border-zinc-800">Empleado</th>
                                <th class="px-6 py-4 text-xs font-bold text-custom-primary uppercase border-b border-zinc-100 dark:border-zinc-800 text-center">Fecha y Hora</th>
                                <th class="px-6 py-4 text-xs font-bold text-custom-primary uppercase border-b border-zinc-100 dark:border-zinc-800 text-center">Tipo</th>
                                <th class="px-6 py-4 text-xs font-bold text-custom-primary uppercase border-b border-zinc-100 dark:border-zinc-800 text-center">Estatus</th>
                                <th class="px-6 py-4 text-xs font-bold text-custom-primary uppercase border-b border-zinc-100 dark:border-zinc-800 text-center">T. Efectivo</th>
                                <th class="px-6 py-4 text-xs font-bold text-custom-primary uppercase border-b border-zinc-100 dark:border-zinc-800 text-center">T. Extra</th>
                                <th class="px-6 py-4 text-xs font-bold text-custom-primary uppercase border-b border-zinc-100 dark:border-zinc-800 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                            @forelse($asistencias as $asistencia)
                                @if($asistencia->es_inasistencia)
                                    <tr class="bg-zinc-50/50">
                                        <td class="px-6 py-4 text-sm">
                                            <div class="flex items-center opacity-70">
                                                <div class="h-8 w-8 rounded-lg bg-zinc-400 flex items-center justify-center text-white text-[10px] font-bold mr-3 uppercase">
                                                    {{ substr($asistencia->employee->nombres ?? 'E', 0, 1) }}{{ substr($asistencia->employee->apellidos ?? '', 0, 1) }}
                                                </div>
                                                <div>
                                                    <p class="font-semibold text-zinc-800 dark:text-zinc-200 leading-none mb-1">{{ $asistencia->employee->nombres }} {{ $asistencia->employee->apellidos }}</p>
                                                    <p class="text-[10px] text-zinc-500">C.I: {{ $asistencia->employee->cedula }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <p class="text-sm font-bold text-zinc-600">{{ \Carbon\Carbon::parse($asistencia->date)->format('d/m/Y') }}</p>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="px-2 py-0.5 rounded-full bg-zinc-200 text-zinc-600 font-bold text-[9px] uppercase">Inasistencia</span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="text-[10px] font-bold text-zinc-500 uppercase">{{ $asistencia->type }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-center text-zinc-300 font-bold text-[10px]">---</td>
                                        <td class="px-6 py-4 text-center text-zinc-300 font-bold text-[10px]">---</td>
                                        <td class="px-6 py-4 text-right flex justify-end gap-2">
                                            {{-- BOTÓN JUSTIFICAR --}}
                                            <button onclick="abrirModalJustificar({{ $asistencia->id }}, '{{ $asistencia->type }}', '{{ $asistencia->observation }}')" class="p-2 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-xl transition-all" title="Justificar Inasistencia">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                                            </button>
                                            <button onclick="confirmarEliminacion({{ $asistencia->id }}, 'inasistencia')" class="p-2 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-xl transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </td>
                                    </tr>
                                @else
                                    {{-- FILA: ENTRADA (Mantenida igual) --}}
                                    <tr class="hover:bg-zinc-50/50 transition-colors">
                                        <td class="px-6 py-4 text-sm">
                                            <div class="flex items-center">
                                                <div class="h-8 w-8 rounded-lg bg-custom-secondary flex items-center justify-center text-white text-[10px] font-bold mr-3 uppercase">
                                                    {{ substr($asistencia->employee->nombres ?? 'E', 0, 1) }}{{ substr($asistencia->employee->apellidos ?? '', 0, 1) }}
                                                </div>
                                                <div>
                                                    <p class="font-semibold text-zinc-800 dark:text-zinc-200 leading-none mb-1">{{ $asistencia->employee->nombres }} {{ $asistencia->employee->apellidos }}</p>
                                                    <p class="text-[10px] text-zinc-500">C.I: {{ $asistencia->employee->cedula }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <p class="text-sm font-medium text-zinc-600">{{ \Carbon\Carbon::parse($asistencia->date)->format('d/m/Y') }}</p>
                                            <p class="text-[10px] font-bold text-zinc-400 uppercase">{{ \Carbon\Carbon::parse($asistencia->check_in)->format('h:i A') }}</p>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="px-2 py-0.5 rounded-full bg-green-100 text-green-700 font-bold text-[9px] uppercase">Entrada</span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="{{ $asistencia->entry_status == 'retraso' ? 'text-custom-primary' : 'text-green-600' }} font-bold text-[10px] uppercase">
                                                {{ $asistencia->entry_status == 'retraso' ? 'Retraso' : 'A Tiempo' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center text-zinc-300 font-bold text-[10px]">---</td>
                                        <td class="px-6 py-4 text-center text-zinc-300 font-bold text-[10px]">---</td>
                                        <td class="px-6 py-4 text-right">
                                            <button onclick="confirmarEliminacion({{ $asistencia->id }}, 'asistencia')" class="p-2 bg-custom-primary/5 text-custom-primary hover:bg-custom-primary hover:text-white rounded-xl transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </td>
                                    </tr>

                                    {{-- FILA: SALIDA (Mantenida igual) --}}
                                    @if($asistencia->check_out)
                                    <tr class="bg-zinc-50/30">
                                        <td class="px-6 py-4 text-sm border-t border-zinc-100 dark:border-zinc-800">
                                            <div class="flex items-center">
                                                <div class="h-8 w-8 rounded-lg bg-zinc-200 flex items-center justify-center text-zinc-500 text-[10px] font-bold mr-3 uppercase">
                                                    {{ substr($asistencia->employee->nombres ?? 'E', 0, 1) }}{{ substr($asistencia->employee->apellidos ?? '', 0, 1) }}
                                                </div>
                                                <div class="opacity-60">
                                                    <p class="font-semibold text-zinc-800 dark:text-zinc-200 leading-none mb-1">{{ $asistencia->employee->nombres }} {{ $asistencia->employee->apellidos }}</p>
                                                    <p class="text-[10px] text-zinc-500">C.I: {{ $asistencia->employee->cedula }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center border-t border-zinc-100 dark:border-zinc-800">
                                            <p class="text-sm font-medium text-zinc-600">{{ \Carbon\Carbon::parse($asistencia->date)->format('d/m/Y') }}</p>
                                            <p class="text-[10px] font-bold text-red-500 uppercase">{{ \Carbon\Carbon::parse($asistencia->check_out)->format('h:i A') }}</p>
                                        </td>
                                        <td class="px-6 py-4 text-center border-t border-zinc-100 dark:border-zinc-800">
                                            <span class="px-2 py-0.5 rounded-full bg-red-100 text-red-700 font-bold text-[9px] uppercase">Salida</span>
                                        </td>
                                        <td class="px-6 py-4 text-center border-t border-zinc-100 dark:border-zinc-800">
                                            <span class="text-[10px] font-bold uppercase {{ $asistencia->exit_status == 'tiempo extra' ? 'text-purple-600' : ($asistencia->exit_status == 'anticipada' ? 'text-amber-600' : 'text-blue-600') }}">
                                                {{ $asistencia->exit_status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center border-t border-zinc-100 dark:border-zinc-800">
                                            <span class="text-sm font-bold text-zinc-700">{{ $asistencia->tiempo_efectivo }}</span>
                                        </td>
                                        
                                        <td class="px-6 py-4 text-center border-t border-zinc-100 dark:border-zinc-800">
                                            @if($asistencia->exit_status == 'tiempo extra' && $asistencia->check_out)
                                                @php
                                                    $fechaBase = \Carbon\Carbon::parse($asistencia->date)->format('Y-m-d');
                                                    $horaReal = \Carbon\Carbon::parse($asistencia->check_out);
                                                    $horaOficial = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $fechaBase . ' 20:30:00');
                                                    
                                                    $minutosExtra = $horaOficial->diffInMinutes($horaReal, false);
                                                    $minutosFinales = $minutosExtra > 0 ? $minutosExtra : 0;
                                                    
                                                    $h = floor($minutosFinales / 60);
                                                    $m = $minutosFinales % 60;
                                                @endphp
                                                
                                                <span class="text-sm font-bold text-purple-600">
                                                    {{ sprintf('%02d:%02d', $h, $m) }}
                                                </span>
                                            @else
                                                <span class="text-zinc-300">--:--</span>
                                            @endif
                                        </td>

                                        <td class="px-6 py-4 text-right border-t border-zinc-100 dark:border-zinc-800">
                                            <button onclick="confirmarEliminacion({{ $asistencia->id }}, 'asistencia')" class="p-2 bg-custom-primary/5 text-custom-primary hover:bg-custom-primary hover:text-white rounded-xl transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </td>
                                    </tr>
                                    @endif
                                @endif
                            @empty
                                <tr><td colspan="7" class="px-6 py-12 text-center text-zinc-400 text-xs uppercase tracking-widest">No se encontraron registros</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL PARA JUSTIFICAR INASISTENCIA --}}
    <div id="modalJustificar" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-zinc-500 bg-opacity-75 dark:bg-zinc-950 dark:bg-opacity-80" onclick="cerrarModalJustificar()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white dark:bg-zinc-900 rounded-3xl shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-zinc-200 dark:border-zinc-800">
                <form id="formJustificar" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="px-6 py-6 bg-white dark:bg-zinc-900">
                        <h3 class="text-lg font-bold text-custom-primary mb-4 uppercase tracking-wider">Justificar Inasistencia</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-[10px] font-bold text-zinc-400 uppercase mb-1">Motivo / Tipo</label>
                                <select name="type" id="justificar_type" class="w-full bg-zinc-50 dark:bg-zinc-800 border-none rounded-xl text-sm focus:ring-1 focus:ring-custom-secondary">
                                    <option value="Injustificada">Injustificada</option>
                                    <option value="Enfermedad">Enfermedad / Reposo</option>
                                    <option value="Permiso de Dirección">Permiso de Dirección</option>
                                    <option value="Vacaciones">Vacaciones</option>
                                    <option value="Comisión de Servicio">Comisión de Servicio</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-zinc-400 uppercase mb-1">Observaciones</label>
                                <textarea name="observation" id="justificar_observation" rows="3" class="w-full bg-zinc-50 dark:bg-zinc-800 border-none rounded-xl text-sm focus:ring-1 focus:ring-custom-secondary" placeholder="Escriba los detalles aquí..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-zinc-50 dark:bg-zinc-800/50 flex justify-end gap-3">
                        <button type="button" onclick="cerrarModalJustificar()" class="text-[10px] font-bold uppercase text-zinc-500 hover:text-zinc-700">Cancelar</button>
                        <button type="submit" class="bg-custom-secondary text-white px-6 py-2 rounded-xl text-[10px] font-bold uppercase tracking-widest">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function abrirModalJustificar(id, tipoActual, observacionActual) {
            const modal = document.getElementById('modalJustificar');
            const form = document.getElementById('formJustificar');
            
            form.action = `/inasistencias/${id}`;
            document.getElementById('justificar_type').value = tipoActual;
            document.getElementById('justificar_observation').value = observacionActual || '';
            
            modal.classList.remove('hidden');
        }

        function cerrarModalJustificar() {
            document.getElementById('modalJustificar').classList.add('hidden');
        }

        function confirmarEliminacion(id, tipo) {
            if (confirm(`¿Estás seguro de que deseas eliminar este registro?`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = tipo === 'asistencia' ? `/asistencias/${id}` : `/inasistencias/${id}`;
                form.innerHTML = `@csrf @method('DELETE')`;
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</x-app-layout>