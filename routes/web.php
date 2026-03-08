<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Sistema de Asistencia Fe y Alegría
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/**
 * DASHBOARD PRINCIPAL
 */
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/**
 * RUTAS PROTEGIDAS POR AUTENTICACIÓN
 */
Route::middleware('auth')->group(function () {

    // --- Gestión de Perfil de Usuario (Breeze) ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /**
     * MÓDULO DE PERSONAL
     */
    Route::get('/personal', [EmployeeController::class, 'index'])->name('employees.index');
    Route::get('/personal/crear', [EmployeeController::class, 'create'])->name('employees.create');
    Route::post('/personal', [EmployeeController::class, 'store'])->name('employees.store');
    
    // CORRECCIÓN DE ORDEN: La ruta estática 'importar' debe ir ANTES de las rutas con parámetros {employee}
    Route::post('/personal/importar', [EmployeeController::class, 'import'])->name('employees.import');

    // Rutas con parámetros
    Route::get('/personal/{employee}', [EmployeeController::class, 'show'])->name('employees.show');
    Route::get('/personal/{employee}/editar', [EmployeeController::class, 'edit'])->name('employees.edit');
    Route::patch('/personal/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
    Route::delete('/personal/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');

    /**
     * ZONA ADMINISTRATIVA (Solo Super Admin)
     */
    Route::middleware('role:super_admin')->group(function () {
        
        // Gestión de Usuarios (CRUD completo)
        Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios.index');
        Route::get('/usuarios/crear', [UserController::class, 'create'])->name('usuarios.create');
        Route::post('/usuarios', [UserController::class, 'store'])->name('usuarios.store');
        Route::get('/usuarios/{usuario}/editar', [UserController::class, 'edit'])->name('usuarios.edit');
        Route::patch('/usuarios/{usuario}', [UserController::class, 'update'])->name('usuarios.update');
        Route::delete('/usuarios/{usuario}', [UserController::class, 'destroy'])->name('usuarios.destroy');

        // Configuración Institucional
        Route::get('/configuracion', [SettingController::class, 'index'])->name('settings.index');
        Route::patch('/configuracion', [SettingController::class, 'update'])->name('settings.update');
    });
});

require __DIR__.'/auth.php';