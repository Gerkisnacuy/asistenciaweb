<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      class="{{ (isset($settings['theme_mode']) && $settings['theme_mode'] === 'dark') ? 'dark' : '' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @stack('styles')

        <style>
    :root {
        /* Esto lee tus colores de la base de datos o usa los por defecto */
        --custom-primary: {{ $settings['primary_color'] ?? '#f21313' }};
        --custom-secondary: {{ $settings['secondary_color'] ?? '#1a1a1a' }};
    }

    .bg-custom-primary { background-color: var(--custom-primary); }
    .bg-custom-secondary { background-color: var(--custom-secondary); }
    .text-custom-primary { color: var(--custom-primary); }
    .text-custom-secondary { color: var(--custom-secondary); }
    
    /* Efecto hover suave para el color secundario */
    .hover-secondary:hover {
        filter: brightness(1.2);
        transition: all 0.3s;
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    </head>
    <body class="antialiased"> {{-- CORRECCIÓN 1: Quité 'overflow-hidden' de aquí para evitar conflictos de scroll con el modal --}}
        <div class="flex h-screen overflow-hidden bg-white dark:bg-zinc-950">
            @include('layouts.navigation')

            <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
                @isset($header)
                    <header class="bg-white dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-800 h-20 flex items-center px-8 shrink-0 shadow-sm z-10">
                        <div class="w-full">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                {{-- CORRECCIÓN 2: Aseguramos que el main tenga el overflow correcto para que el modal se vea siempre --}}
                <main class="flex-1 overflow-y-auto p-8 bg-zinc-50/50 dark:bg-zinc-950/50 relative">
                    <div class="max-w-7xl mx-auto">
                        {{ $slot }}
                    </div>

                    <footer class="mt-20 py-8 text-center text-xs text-zinc-500 border-t border-zinc-200 dark:border-zinc-800">
                        <p class="font-bold">
                            {{ $settings['site_name'] ?? config('app.name') }} — RIF: {{ $settings['site_rif'] ?? '' }}
                        </p>
                    </footer>
                </main>
            </div>
        </div>

        {{-- CORRECCIÓN 3: Scripts siempre al final --}}
        @stack('scripts')
    </body>
</html>