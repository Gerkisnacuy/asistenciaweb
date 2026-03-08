<style>
    @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap');
    
    .sidebar-nav {
        font-family: 'Roboto', sans-serif !important;
    }
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(0,0,0,0.1);
        border-radius: 10px;
    }
</style>

<nav x-data="{ open: false }" 
     class="sidebar-nav w-72 bg-white dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-800 flex flex-col h-screen shrink-0 transition-all duration-300">
    
    <div class="p-6 border-b border-zinc-100 dark:border-zinc-800/50 flex flex-col items-center">
        <a href="{{ route('dashboard') }}" class="group flex flex-col items-center">
            @if(!empty($settings['site_logo']))
                <img src="{{ asset('storage/' . $settings['site_logo']) }}" 
                     alt="Logo" 
                     class="h-20 w-auto object-contain mb-4 transition-transform group-hover:scale-105">
            @else
                <div class="p-3 rounded-2xl bg-custom-primary shadow-lg mb-4">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A10.003 10.003 0 0020 10c0-5.523-4.477-10-10-10S0 4.477 0 10c0 1.911.537 3.697 1.466 5.216l.054.09m1.312 3.391a10.003 10.003 0 01-2.82-5.07m5.625 9.2a4.459 4.459 0 01-1.587-1.066m-3.322-3.23a4.459 4.459 0 01-1.066-1.587l.001.001z" />
                    </svg>
                </div>
            @endif
            <span class="font-black text-sm tracking-widest text-zinc-900 dark:text-zinc-100 text-center uppercase leading-tight">
                {{ $settings['site_name'] ?? 'SISTEMA ASISTENCIA' }}
            </span>
        </a>
    </div>

    <div class="flex-1 overflow-y-auto custom-scrollbar px-4 py-6 space-y-8">
        
        <div>
            <p class="px-4 text-[10px] font-black text-zinc-400 dark:text-zinc-500 uppercase tracking-[0.2em] mb-4">Principal</p>
            <div class="space-y-1">
                <x-nav-link-sidebar :href="route('dashboard')" :active="request()->routeIs('dashboard')" icon="home">
                    {{ __('Inicio') }}
                </x-nav-link-sidebar>
            </div>
        </div>

        @if(Auth::user()->role === 'super_admin')
        <div>
            <p class="px-4 text-[10px] font-black text-custom-primary uppercase tracking-[0.2em] mb-4">Administración</p>
            <div class="space-y-1">
                
                <x-nav-link-sidebar :href="route('settings.index')" :active="request()->routeIs('settings.index')" icon="cog">
                    {{ __('Configuración Global') }}
                </x-nav-link-sidebar>

                <x-nav-link-sidebar :href="route('employees.index')" :active="request()->routeIs('employees.*')" icon="users">
                    {{ __('Gestión de Personal') }}
                </x-nav-link-sidebar>

                <x-nav-link-sidebar :href="route('usuarios.index')" :active="request()->routeIs('usuarios.*')" icon="user-group">
                    {{ __('Gestión de Usuarios') }}
                </x-nav-link-sidebar>

                <x-nav-link-sidebar :href="route('asistencia.index')" :active="request()->routeIs('asistencia.index')" icon="clock">
                    {{ __('Registro de Asistencia') }}
                </x-nav-link-sidebar>

                <x-nav-link-sidebar :href="route('asistencia.report')" :active="request()->routeIs('asistencia.report')" icon="chart-bar">
                    {{ __('Reportes de Asistencia') }}
                </x-nav-link-sidebar>

            </div>
        </div>
        @endif
    </div>

    <div class="p-4 border-t border-zinc-100 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-900/50">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" 
                    class="w-full bg-custom-secondary group p-4 rounded-2xl flex items-center justify-between shadow-lg hover:brightness-110 transition-all duration-300 border border-black/5">
                
                <div class="flex items-center overflow-hidden">
                    <div class="h-10 w-10 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center shrink-0 font-black text-white text-lg border border-white/20">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    
                    <div class="ml-3 text-left overflow-hidden text-white">
                        <p class="text-xs font-black truncate uppercase leading-tight">
                            {{ Auth::user()->name }}
                        </p>
                        <p class="text-[9px] font-bold uppercase tracking-widest opacity-80 mt-1">
                            {{ Auth::user()->role === 'super_admin' ? 'Administrador' : 'Personal' }}
                        </p>
                    </div>
                </div>
                
                <div class="p-2 bg-white/10 rounded-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </div>
            </button>
        </form>
    </div>
</nav>