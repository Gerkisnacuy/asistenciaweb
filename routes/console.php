<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Setting;
use Carbon\Carbon;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/**
 * Programación de tareas automáticas
 */

// Ejecutamos el comando cada minuto, pero la lógica de "cuándo procesar" 
// la manejará el propio comando o una validación aquí mismo.
Schedule::command('attendance:generate-absences')->everyMinute()->when(function () {
    // 1. Buscamos la hora de salida en la configuración
    $settings = Setting::first();
    $horaSalida = $settings ? $settings->checkout_time : '20:00:00';

    // 2. Calculamos la hora de ejecución (Hora de salida + 2 horas)
    $horaEjecucion = Carbon::parse($horaSalida)->addHours(2)->format('H:i');

    // 3. Solo se ejecuta si la hora actual coincide con la programada
    return Carbon::now()->format('H:i') === $horaEjecucion;
});
