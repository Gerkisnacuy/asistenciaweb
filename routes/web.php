<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\AbsenceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Sistema de Asistencia Fe y Alegría
|--------------------------------------------------------------------------
*/

// Pantalla de bienvenida
Route::get('/', function () {
    return view('welcome');
});

/**
 * MÓDULO DE MARCADO DE ASISTENCIA (Acceso Público)
 */
Route::get('/asistencia', [AttendanceController::class, 'index'])->name('asistencia.index');
Route::post('/asistencia/marcar', [AttendanceController::class, 'store'])->name('asistencia.store');

/**
 * DASHBOARD PRINCIPAL (Requiere Login)
 */
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/**
 * RUTAS PROTEGIDAS POR AUTENTICACIÓN
 */
Route::middleware('auth')->group(function () {

    // Gestión de Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /**
     * MÓDULO DE PERSONAL
     */
    Route::post('/employees/import', [EmployeeController::class, 'import'])->name('employees.import');
    Route::resource('employees', EmployeeController::class);

    /**
     * ZONA ADMINISTRATIVA (Solo Super Administrador)
     */
    Route::middleware('role:super_admin')->group(function () {
        
        // Gestión de Usuarios del Sistema
        Route::resource('usuarios', UserController::class)->names('usuarios');

        // Configuración Global
        Route::get('/configuracion', [SettingController::class, 'index'])->name('settings.index');
        Route::patch('/configuracion', [SettingController::class, 'update'])->name('settings.update');
        
        // Reportes y Consultas
        Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
        Route::get('/reportes/asistencia/detallado', [AttendanceController::class, 'report'])->name('asistencia.report');

        /**
         * GESTIÓN DE INASISTENCIAS (Absences)
         */
        // NUEVA RUTA: Procesar inasistencias manualmente
        Route::post('/inasistencias/procesar', [AbsenceController::class, 'procesarManual'])->name('inasistencias.procesar');
        
        // Justificar y Eliminar
        Route::put('/inasistencias/{id}', [AbsenceController::class, 'update'])->name('inasistencia.update');
        Route::delete('/inasistencias/{id}', [AbsenceController::class, 'destroy'])->name('inasistencia.destroy');

        /**
         * GESTIÓN DE ASISTENCIAS
         */
        Route::delete('/asistencias/{id}', [AttendanceController::class, 'destroy'])->name('asistencia.destroy');
    });
});

require __DIR__.'/auth.php';