<?php

namespace App\Http\Controllers;

use App\Models\Absence;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Setting;
use App\Models\Holiday; // Asegúrate de tener este modelo
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AbsenceController extends Controller
{
    /**
     * Procesa las inasistencias del día manualmente.
     * Cruza empleados activos vs asistencias, validando fines de semana y feriados.
     */
    public function procesarManual()
    {
        $ahora = Carbon::now();
        $hoy = $ahora->toDateString();

        // 1. VALIDACIÓN DE DÍAS NO LABORABLES
        
        // A. Validar Fin de Semana
        if ($ahora->isWeekend()) {
            return redirect()->back()->with('error', 'Hoy es fin de semana. No se procesan inasistencias en días no laborables.');
        }

        // B. Validar Feriados (Busca la fecha de hoy en la tabla de feriados)
        // Se asume que tienes una tabla 'holidays' con una columna 'date'
        $esFeriado = Holiday::whereDate('date', $hoy)->exists();
        if ($esFeriado) {
            return redirect()->back()->with('error', 'Hoy está marcado como feriado/asueto en el calendario. No se generarán faltas.');
        }

        // 2. OBTENER CONFIGURACIÓN DE SALIDA
        $settings = Setting::first();
        $horaConfigurada = $settings->checkout_time ?? '20:30:00';

        try {
            // parse() es tolerante a fallos de formato (evita el error 'Not enough data')
            $horaLimite = Carbon::parse($horaConfigurada)->subMinutes(10);
        } catch (\Exception $e) {
            Log::error("Error en formato de hora_salida: " . $e->getMessage());
            $horaLimite = Carbon::today()->setTime(20, 20);
        }

        // 3. VALIDAR VENTANA DE TIEMPO
        if ($ahora->lessThan($horaLimite)) {
            return redirect()->back()->with('error', 
                'Operación bloqueada. El cierre de jornada se habilita a las ' . $horaLimite->format('h:i A')
            );
        }

        // 4. LÓGICA DE CRUCE (DIFERENCIA DE CONJUNTOS)
        
        // Obtener IDs de quienes SÍ vinieron hoy
        $asistieronIds = Attendance::whereDate('date', $hoy)
            ->pluck('employee_id')
            ->toArray();

        // Obtener Empleados activos que NO están en la lista anterior
        $inasistentes = Employee::where('active', true)
            ->whereNotIn('id', $asistieronIds)
            ->get();

        // 5. REGISTRO DE INASISTENCIAS
        $count = 0;
        foreach ($inasistentes as $empleado) {
            // firstOrCreate evita duplicados si se pulsa el botón varias veces
            Absence::firstOrCreate(
                [
                    'employee_id' => $empleado->id,
                    'date'        => $hoy,
                ],
                [
                    'type'        => 'Injustificada',
                    'observation' => 'Sistema: Inasistencia detectada al cierre de jornada (Cruce automático).'
                ]
            );
            $count++;
        }

        return redirect()->back()->with('success', "Proceso completado. Se han registrado {$count} inasistencias.");
    }

    /**
     * Actualiza una inasistencia (Justificación).
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'type'        => 'required|string',
            'observation' => 'nullable|string'
        ]);

        try {
            $absence = Absence::findOrFail($id);
            $absence->update($validated);

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Actualizado correctamente.']);
            }

            return redirect()->back()->with('success', 'Inasistencia actualizada.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al actualizar registro.');
        }
    }

    /**
     * Elimina un registro de inasistencia.
     */
    public function destroy($id)
    {
        try {
            Absence::findOrFail($id)->delete();
            return redirect()->back()->with('success', 'Registro eliminado correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'No se pudo eliminar el registro.');
        }
    }
}