<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terminal de Asistencia - Fe y Alegría</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-zinc-100 h-screen w-full overflow-hidden font-sans antialiased text-zinc-900">

    <div class="fixed top-6 left-6 z-50">
        <a href="{{ route('dashboard') }}" 
           class="group flex items-center gap-3 bg-white/80 backdrop-blur-md px-5 py-3 rounded-2xl border border-zinc-200 shadow-xl hover:bg-zinc-900 transition-all duration-300">
            <div class="p-2 rounded-xl bg-zinc-100 group-hover:bg-zinc-800 transition-colors">
                <svg class="w-5 h-5 text-zinc-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </div>
            <span class="font-black text-[10px] uppercase tracking-[0.2em] text-zinc-500 group-hover:text-white">Volver al Panel</span>
        </a>
    </div>

    <div class="h-full w-full flex items-center justify-center p-4 md:p-6 lg:p-8">
        
        <div class="w-full h-full max-w-6xl max-h-[90vh] bg-white rounded-[3rem] shadow-2xl border border-zinc-200 flex flex-col md:flex-row overflow-hidden">
            
            <div class="flex-1 min-h-0 flex flex-col items-center justify-center p-6 bg-zinc-50/50 border-b md:border-b-0 md:border-r border-zinc-100">
                <div class="text-center mb-4 flex-shrink-0">
                    <h2 class="text-2xl font-black uppercase tracking-tighter text-zinc-800">Escáner QR</h2>
                    <div class="h-1.5 w-16 bg-red-600 mx-auto mt-1 rounded-full"></div>
                </div>
                
                <div class="relative w-full max-w-[320px] aspect-square bg-black rounded-[2.5rem] overflow-hidden border-[8px] border-white shadow-lg">
                    <div id="reader" class="w-full h-full"></div>
                </div>

                <div class="mt-6 flex items-center gap-2 bg-white px-5 py-2 rounded-full shadow-sm border border-zinc-100">
                    <span class="relative flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-red-600"></span>
                    </span>
                    <span class="text-[10px] font-black uppercase text-zinc-500 tracking-widest">Lector Activo</span>
                </div>
            </div>

            <div class="flex-1 min-h-0 flex flex-col p-6 md:p-8 bg-white">
                
                <div class="text-center mb-4 flex-shrink-0">
                    <h2 class="text-2xl font-black uppercase tracking-tighter text-zinc-800">Marcado Manual</h2>
                </div>

                <div class="flex-1 flex flex-col justify-center max-w-[320px] mx-auto w-full min-h-0">
                    <input type="number" id="cedulaInput" readonly
                        class="w-full py-3 px-2 bg-zinc-50 border-2 border-zinc-100 rounded-2xl text-center text-4xl font-mono font-black text-red-600 focus:ring-0 shadow-inner mb-4"
                        placeholder="CÉDULA">
                    
                    <div class="grid grid-cols-3 gap-2">
                        @for ($i = 1; $i <= 9; $i++)
                            <button type="button" onclick="addNum({{ $i }})" class="py-3 bg-zinc-100 text-2xl font-black rounded-xl text-zinc-700 hover:bg-red-600 hover:text-white transition-all active:scale-90 shadow-sm">{{ $i }}</button>
                        @endfor
                        <button type="button" onclick="clearInput()" class="py-3 bg-red-50 text-red-600 font-black rounded-xl uppercase text-[10px] hover:bg-red-100 active:scale-90">Borrar</button>
                        <button type="button" onclick="addNum(0)" class="py-3 bg-zinc-100 text-2xl font-black rounded-xl text-zinc-700 hover:bg-red-600 hover:text-white active:scale-90 shadow-sm">0</button>
                        <button type="button" onclick="enviarManual()" class="py-3 bg-red-600 text-white font-black rounded-xl uppercase text-[10px] shadow-lg shadow-red-100 hover:bg-red-700 active:scale-95">OK</button>
                    </div>
                </div>

                <div class="text-center pt-4 border-t border-zinc-100 mt-4 flex-shrink-0">
                    <div id="clock" class="text-4xl font-black text-zinc-800 tabular-nums tracking-tighter leading-none">00:00:00</div>
                    <div class="text-sm font-bold text-red-600 uppercase tracking-[0.2em] mt-2">{{ now()->translatedFormat('d F, Y') }}</div>
                </div>
            </div>

        </div>
    </div>

    <script>
        function updateClock() {
            const now = new Date();
            document.getElementById('clock').innerText = now.toLocaleTimeString('es-VE', { hour12: false });
        }
        setInterval(updateClock, 1000);
        updateClock();

        const input = document.getElementById('cedulaInput');
        function addNum(n) { if(input.value.length < 9) input.value += n; }
        function clearInput() { input.value = ''; }

        const html5QrCode = new Html5Qrcode("reader");
        html5QrCode.start({ facingMode: "environment" }, { fps: 20, qrbox: 200 }, (text) => {
            enviarAsistencia(text.split('/').pop(), 'qr');
        }).catch(err => console.error(err));

        function enviarManual() {
            if(input.value) enviarAsistencia(input.value, 'manual');
            else Swal.fire({ icon: 'warning', title: 'Atención', text: 'Ingrese una cédula' });
        }

        function enviarAsistencia(cedula, metodo) {
            Swal.fire({ title: 'Procesando...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); }});
            
            fetch("{{ route('asistencia.store') }}", {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json', 
                    'X-CSRF-TOKEN': '{{ csrf_token() }}', 
                    'Accept': 'application/json' 
                },
                body: JSON.stringify({ cedula: cedula, method: metodo })
            })
            .then(res => res.json())
            .then(data => {
                Swal.fire({ 
                    title: data.success ? '¡ÉXITO!' : 'ERROR', 
                    text: data.message, 
                    icon: data.success ? 'success' : 'error', 
                    timer: 2000, 
                    showConfirmButton: false 
                });
                clearInput();
            }).catch(() => Swal.fire({ icon: 'error', title: 'Error de red' }));
        }
    </script>

    <style>
        #reader video { object-fit: cover !important; width: 100% !important; height: 100% !important; border-radius: 2rem; }
        #reader__dashboard_section_csr { display: none !important; }
        input::-webkit-outer-spin-button, input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    </style>
</body>
</html>