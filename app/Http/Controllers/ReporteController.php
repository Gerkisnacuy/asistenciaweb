<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Setting;
use App\Models\Absence;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReporteController extends Controller
{
    public function index(Request $request)
    {
        // 1. Filtros: Normalizamos las fechas para evitar errores de comparación
        $search = $request->get('search');
        
        // Fecha inicio al lunes de la semana y fin al último día del mes
        $fecha_inicio = $request->get('desde', Carbon::now()->startOfWeek(Carbon::MONDAY)->toDateString());
        $fecha_fin = $request->get('hasta', Carbon::now()->endOfMonth()->toDateString());

        // 2. Consulta de Asistencias (Usamos whereDate para incluir el día completo)
        $queryAsistencias = Attendance::with('employee')
            ->whereDate('date', '>=', $fecha_inicio)
            ->whereDate('date', '<=', $fecha_fin);

        // 3. Consulta de Inasistencias
        $queryInasistencias = Absence::with('employee')
            ->whereDate('date', '>=', $fecha_inicio)
            ->whereDate('date', '<=', $fecha_fin);

        // 4. Filtro de búsqueda por empleado (Optimizado)
        if ($request->filled('search')) {
            $filter = function($q) use ($search) {
                $q->whereHas('employee', function($sq) use ($search) {
                    $sq->where('nombres', 'like', "%{$search}%")
                      ->orWhere('apellidos', 'like', "%{$search}%")
                      ->orWhere('cedula', 'like', "%{$search}%");
                });
            };
            $queryAsistencias->where($filter);
            $queryInasistencias->where($filter);
        }

        $asistencias = $queryAsistencias->get();
        $inasistencias = $queryInasistencias->get();

        $settings = Setting::first();
        $horaSalidaOficial = $settings ? $settings->checkout_time : '20:00:00';

        // 5. Procesamiento de Asistencias
        $asistencias->each(function ($asistencia) use ($horaSalidaOficial) {
            $asistencia->es_inasistencia = false;
            $asistencia->minutos_extra = 0; // Inicializamos siempre en 0

            if ($asistencia->check_in && $asistencia->check_out) {
                $entrada = Carbon::parse($asistencia->check_in)->startOfMinute();
                $salida = Carbon::parse($asistencia->check_out)->startOfMinute();
                // Importante: Usar la fecha del registro para el límite de salida
                $limiteSalida = Carbon::parse($asistencia->date . ' ' . $horaSalidaOficial)->startOfMinute();

                $minutosTotales = $entrada->diffInMinutes($salida);
                $asistencia->tiempo_efectivo = sprintf('%02d:%02d', floor($minutosTotales / 60), $minutosTotales % 60);

                if ($salida->lt($limiteSalida)) {
                    $asistencia->exit_status = 'anticipada';
                } elseif ($salida->gt($limiteSalida)) {
                    $asistencia->exit_status = 'tiempo extra';
                    // Cálculo de solo los minutos después de la hora oficial
                    $asistencia->minutos_extra = $limiteSalida->diffInMinutes($salida);
                } else {
                    $asistencia->exit_status = 'a tiempo';
                }
            } else {
                $asistencia->tiempo_efectivo = '---';
                $asistencia->exit_status = $asistencia->check_in ? 'En curso' : 'Sin registro';
            }
        });

        // 6. Procesamiento de Inasistencias
        $inasistencias->each(function($i) { 
            $i->es_inasistencia = true; 
            $i->type = $i->type ?? 'Inasistencia'; 
        });

        // Unificar y ordenar cronológicamente
        $reporteCompleto = $asistencias->concat($inasistencias)->sortByDesc('date');

        return view('reportes.index', [
            'asistencias'               => $reporteCompleto,
            'fecha_inicio'              => $fecha_inicio,
            'fecha_fin'                 => $fecha_fin,
            'total_registros'           => $asistencias->count(),
            'total_retrasos'            => $asistencias->where('entry_status', 'retraso')->count(),
            'total_puntuales'           => $asistencias->where('entry_status', 'puntual')->count(),
            'total_inasistencias'       => $inasistencias->count(),
            'total_salidas_puntuales'   => $asistencias->where('exit_status', 'a tiempo')->count(),
            'total_salidas_anticipadas' => $asistencias->where('exit_status', 'anticipada')->count(),
            // CONTADOR DE REGISTROS: Cuenta cuántos registros superaron la hora de salida
            'total_tiempo_extra'        => $asistencias->where('minutos_extra', '>', 0)->count(),
            'search'                    => $search
        ]);
    }
}