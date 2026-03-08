<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Absence;
use App\Models\Setting;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AttendanceController extends Controller
{
    /**
     * Muestra la interfaz de la terminal de asistencia (Público).
     */
    public function index()
    {
        return view('asistencia.index');
    }

    /**
     * Genera el reporte consolidado para la vista administrativa.
     */
    public function report(Request $request)
    {
        $search = $request->input('search');

        // 1. DETERMINAR RANGO DE FECHAS (Desde el 1ero del mes hasta el último día)
        // Usamos fillter_var o chequeo manual para asegurar que si llega vacío, se asigne el mes actual
        $fecha_inicio = $request->filled('desde') 
            ? $request->desde 
            : Carbon::now()->startOfMonth()->toDateString();

        $fecha_fin = $request->filled('hasta') 
            ? $request->hasta 
            : Carbon::now()->endOfMonth()->toDateString();

        // 2. OBTENER ASISTENCIAS
        $queryAsistencias = Attendance::with('employee')
            ->whereDate('date', '>=', $fecha_inicio)
            ->whereDate('date', '<=', $fecha_fin);

        if ($search) {
            $queryAsistencias->whereHas('employee', function ($q) use ($search) {
                $q->where('nombres', 'like', "%{$search}%")
                  ->orWhere('apellidos', 'like', "%{$search}%")
                  ->orWhere('cedula', 'like', "%{$search}%");
            });
        }

        $asistenciasList = $queryAsistencias->get()->map(function ($item) {
            $item->es_inasistencia = false;
            if($item->total_work_minutes) {
                $h = floor($item->total_work_minutes / 60);
                $m = $item->total_work_minutes % 60;
                $item->tiempo_efectivo = sprintf('%02d:%02d', $h, $m);
            } else {
                $item->tiempo_efectivo = '---';
            }
            return $item;
        });

        // 3. OBTENER INASISTENCIAS
        $queryInasistencias = Absence::with('employee')
            ->whereDate('date', '>=', $fecha_inicio)
            ->whereDate('date', '<=', $fecha_fin);

        if ($search) {
            $queryInasistencias->whereHas('employee', function ($q) use ($search) {
                $q->where('nombres', 'like', "%{$search}%")
                  ->orWhere('apellidos', 'like', "%{$search}%")
                  ->orWhere('cedula', 'like', "%{$search}%");
            });
        }

        $inasistenciasList = $queryInasistencias->get()->map(function ($item) {
            $item->es_inasistencia = true;
            return $item;
        });

        // 4. CONSOLIDACIÓN Y ESTADÍSTICAS
        $todoConsolidado = $asistenciasList->concat($inasistenciasList)->sortByDesc('date');

        $minTotalExtra = $asistenciasList->sum('minutes_extra');
        $h_ex = floor($minTotalExtra / 60);
        $m_ex = $minTotalExtra % 60;

        return view('reportes.index', [
            'asistencias'               => $todoConsolidado,
            'total_registros'           => $asistenciasList->count(),
            'total_inasistencias'       => $inasistenciasList->count(),
            'total_puntuales'           => $asistenciasList->where('entry_status', 'puntual')->count(),
            'total_retrasos'            => $asistenciasList->where('entry_status', 'retraso')->count(),
            'total_salidas_puntuales'   => $asistenciasList->where('exit_status', 'a tiempo')->count(),
            'total_salidas_anticipadas' => $asistenciasList->where('exit_status', 'salida anticipada')->count(),
            'total_tiempo_extra'        => sprintf('%02d:%02d', $h_ex, $m_ex),
            'search'                    => $search,
            'fecha_inicio'              => $fecha_inicio,
            'fecha_fin'                 => $fecha_fin,
        ]);
    }

    /**
     * Procesa el marcado de entrada o salida.
     */
    public function store(Request $request)
    {
        $request->validate([
            'cedula' => 'required|exists:employees,cedula',
            'method' => 'nullable|string'
        ]);

        try {
            $employee = Employee::where('cedula', $request->cedula)->first();
            $now = Carbon::now();
            $today = $now->toDateString();

            $config = Setting::whereIn('key', ['p_hora_entrada', 'p_tolerancia', 'p_hora_salida'])
                             ->pluck('value', 'key');

            $h_entrada  = Carbon::parse($today . ' ' . ($config['p_hora_entrada'] ?? '08:00'));
            $tolerancia = (int) ($config['p_tolerancia'] ?? 0);
            $h_salida   = Carbon::parse($today . ' ' . ($config['p_hora_salida'] ?? '17:00'));

            $attendance = Attendance::where('employee_id', $employee->id)
                ->whereDate('date', $today)
                ->whereNull('check_out')
                ->latest()
                ->first();

            if (!$attendance) {
                $minutosTarde = 0;
                $estatusEntrada = 'puntual';
                $horaLimiteConTolerancia = (clone $h_entrada)->addMinutes($tolerancia);

                if ($now->gt($horaLimiteConTolerancia)) {
                    $estatusEntrada = 'retraso';
                    $minutosTarde = $h_entrada->diffInMinutes($now, false);
                }

                Attendance::create([
                    'employee_id'  => $employee->id,
                    'date'         => $today,
                    'check_in'     => $now->toDateTimeString(),
                    'entry_status' => $estatusEntrada,
                    'minutes_late' => max(0, $minutosTarde),
                    'method'       => $request->input('method', 'manual'),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => "Entrada registrada como {$estatusEntrada}.",
                    'employee' => $employee->nombres . ' ' . $employee->apellidos
                ]);

            } else {
                $checkInTime = Carbon::parse($attendance->check_in);
                $minutosDesdeEntrada = $checkInTime->diffInMinutes($now);

                if ($minutosDesdeEntrada < 5) {
                    return response()->json([
                        'success' => false,
                        'message' => "No puedes marcar la salida tan pronto. Debes esperar al menos 5 minutos.",
                        'employee' => $employee->nombres
                    ], 422);
                }

                $minutosAnticipados = 0;
                $minutosExtra = 0;
                $estatusSalida = 'a tiempo';

                if ($now->lt($h_salida)) {
                    $estatusSalida = 'salida anticipada';
                    $minutosAnticipados = $now->diffInMinutes($h_salida, false);
                } elseif ($now->gt($h_salida)) {
                    $estatusSalida = 'tiempo extra';
                    $minutosExtra = $h_salida->diffInMinutes($now, false);
                }

                $attendance->update([
                    'check_out'          => $now->toDateTimeString(),
                    'exit_status'        => $estatusSalida,
                    'minutes_early_exit' => max(0, $minutosAnticipados),
                    'minutes_extra'      => max(0, $minutosExtra),
                    'total_work_minutes' => $minutosDesdeEntrada,
                ]);

                $horas = floor($minutosDesdeEntrada / 60);
                $minutos = $minutosDesdeEntrada % 60;

                return response()->json([
                    'success' => true,
                    'message' => "Salida registrada: {$estatusSalida}. Tiempo efectivo: {$horas}h {$minutos}m.",
                    'employee' => $employee->nombres . ' ' . $employee->apellidos
                ]);
            }

        } catch (\Exception $e) {
            Log::error("Error en Marcaje: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Elimina un registro de asistencia.
     */
    public function destroy($id)
    {
        try {
            $attendance = Attendance::findOrFail($id);
            $attendance->delete();

            return redirect()->back()->with('success', 'Registro eliminado correctamente.');

        } catch (\Exception $e) {
            Log::error("Error al eliminar asistencia: " . $e->getMessage());
            return redirect()->back()->with('error', 'No se pudo eliminar el registro.');
        }
    }
}