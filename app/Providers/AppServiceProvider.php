<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /**
         * Compartir configuraciones globales con todas las vistas
         * Verificamos que no estemos en consola y que la tabla exista para evitar errores.
         */
        if (!app()->runningInConsole()) {
            if (Schema::hasTable('settings')) {
                // Obtenemos un array clave => valor de la tabla settings
                $settings = Setting::pluck('value', 'key')->all();
                
                // Lo inyectamos en todas las vistas Blade como la variable $settings
                View::share('settings', $settings);
            }
        }
    }
}