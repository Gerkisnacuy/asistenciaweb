<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between h-16 print:hidden">
            <div class="flex items-center">
                <a href="{{ route('employees.index') }}" class="mr-3 p-1.5 text-zinc-400 hover:text-custom-primary hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <h2 class="font-extrabold text-xl text-zinc-900 dark:text-zinc-100 tracking-tight uppercase">
                    {{ __('Ficha Digital de Personal') }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto">
            {{-- Tarjeta Principal --}}
            <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] border border-zinc-100 dark:border-zinc-800 shadow-2xl overflow-hidden">
                
                {{-- Cabecera de la Tarjeta --}}
                <div class="bg-zinc-50 dark:bg-zinc-800/50 p-8 text-center border-b border-zinc-100 dark:border-zinc-800">
                    <div class="relative inline-block">
                        <div class="h-32 w-32 rounded-3xl overflow-hidden border-4 border-white dark:border-zinc-900 shadow-lg mx-auto">
                            <img src="{{ $employee->foto ? asset('storage/' . $employee->foto) : 'https://ui-avatars.com/api/?name='.urlencode($employee->nombres).'&size=128' }}" class="h-full w-full object-cover">
                        </div>
                        <div class="absolute -bottom-2 -right-2 bg-custom-primary text-white p-2 rounded-xl shadow-lg">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/></svg>
                        </div>
                    </div>
                    
                    <h3 class="mt-4 font-black text-xl text-zinc-800 dark:text-zinc-100 uppercase tracking-tight">
                        {{ $employee->nombres }} {{ $employee->apellidos }}
                    </h3>
                    <p class="text-[10px] font-black text-custom-primary uppercase tracking-[0.3em]">
                        {{ $employee->cargo }}
                    </p>
                </div>

                {{-- Cuerpo: Código QR --}}
                <div class="p-10 text-center bg-white dark:bg-zinc-900">
                    <div id="qr-container-to-download" class="mb-6 inline-block p-4 bg-zinc-50 dark:bg-zinc-950 rounded-[2rem] border-2 border-dashed border-zinc-200 dark:border-zinc-800">
                        <div class="qr-svg-wrapper bg-white p-2 rounded-xl">
                            {!! $qrCode !!}
                        </div>
                    </div>
                    
                    {{-- Botón de Descarga --}}
                    <div class="mb-8 print:hidden">
                        <button onclick="downloadQR()" class="inline-flex items-center px-6 py-3 bg-custom-primary text-white text-xs font-black uppercase tracking-widest rounded-xl shadow-lg hover:brightness-110 active:scale-95 transition-all">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            Descargar QR (.PNG)
                        </button>
                    </div>

                    <div class="grid grid-cols-2 gap-4 text-left">
                        <div class="p-4 bg-zinc-50 dark:bg-zinc-800/30 rounded-2xl">
                            <span class="block text-[8px] font-black text-zinc-400 uppercase">Cédula</span>
                            <span class="font-mono text-sm text-zinc-700 dark:text-zinc-200">{{ number_format($employee->cedula, 0, ',', '.') }}</span>
                        </div>
                        <div class="p-4 bg-zinc-50 dark:bg-zinc-800/30 rounded-2xl">
                            <span class="block text-[8px] font-black text-zinc-400 uppercase">Contacto</span>
                            <span class="font-mono text-sm text-zinc-700 dark:text-zinc-200">{{ $employee->telefono ?? 'S/N' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Pie de Tarjeta --}}
                <div class="p-6 bg-zinc-50 dark:bg-zinc-800/50 flex justify-center space-x-4 print:hidden">
                    <button onclick="window.print()" class="text-[10px] font-black uppercase tracking-widest text-zinc-500 hover:text-custom-primary transition-colors flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        Imprimir Gafete
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Librería y Script para descarga --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        function downloadQR() {
            const qrElement = document.getElementById('qr-container-to-download');
            const fileName = "QR_{{ $employee->cedula }}";

            html2canvas(qrElement, {
                scale: 4,
                backgroundColor: "#ffffff",
            }).then(canvas => {
                const link = document.createElement('a');
                link.download = `${fileName}.png`;
                link.href = canvas.toDataURL("image/png");
                link.click();
            });
        }
    </script>

    <style>
        .qr-svg-wrapper svg {
            width: 160px;
            height: 160px;
            margin: 0 auto;
        }
        @media print {
            .print\:hidden { display: none !important; }
        }
    </style>
</x-app-layout>