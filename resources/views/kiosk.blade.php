<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ambil Antrian - Kiosk</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: white;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        
        .bg-pattern {
            position: absolute; top: 0; left: 0; right: 0; bottom: 0;
            opacity: 0.1;
            background-image: radial-gradient(#6366f1 1px, transparent 1px);
            background-size: 30px 30px;
            z-index: -1;
        }

       
        .service-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(12px);
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .service-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.08);
            border-color: #818cf8;
            box-shadow: 0 20px 40px -5px rgba(99, 102, 241, 0.2);
        }

        .service-card:active { transform: scale(0.98); }

       
        .live-dot {
            height: 8px; width: 8px;
            background-color: #22c55e;
            border-radius: 50%;
            display: inline-block;
            box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7);
            animation: pulse-green 2s infinite;
        }

        @keyframes pulse-green {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(34, 197, 94, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
        }
    </style>
</head>
<body class="antialiased">

    <div class="bg-pattern"></div>

    <header class="pt-10 pb-6 text-center px-4">
        <div class="inline-flex items-center justify-center p-3 rounded-2xl bg-indigo-500/10 mb-4 shadow-inner border border-indigo-500/20">
            <i class="fa-solid fa-ticket-simple text-3xl text-indigo-400"></i>
        </div>
        <h1 class="text-3xl md:text-5xl font-extrabold tracking-tight mb-2 text-transparent bg-clip-text bg-gradient-to-b from-white to-slate-400">
            Ambil Tiket Antrian
        </h1>
        <p class="text-slate-400 text-sm md:text-base font-medium">
            Pilih layanan di bawah untuk mendapatkan nomor antrian Anda
        </p>
    </header>

    <main class="flex-grow flex items-center justify-center p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-7xl w-full">
            
            @forelse($services as $service)
                <button onclick="takeQueue({{ $service->id }}, '{{ $service->name }}')" 
                        class="service-card group relative overflow-hidden rounded-3xl p-6 h-auto min-h-[320px] flex flex-col items-center justify-between text-center w-full">
                    
                    <div class="absolute inset-0 bg-gradient-to-b from-transparent via-transparent to-indigo-900/20 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                    <div class="relative z-10 w-full flex flex-col items-center mt-2">
                        <div class="h-16 w-16 rounded-2xl bg-slate-800/80 flex items-center justify-center mb-4 group-hover:scale-110 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300 border border-slate-700 group-hover:border-indigo-500 shadow-xl">
                             @if(Str::contains(Str::lower($service->name), ['cs', 'customer']))
                                <i class="fa-solid fa-headset text-2xl text-indigo-400 group-hover:text-white"></i>
                            @elseif(Str::contains(Str::lower($service->name), ['teller', 'kasir', 'uang']))
                                <i class="fa-solid fa-money-bill-transfer text-2xl text-emerald-400 group-hover:text-white"></i>
                            @else
                                <i class="fa-solid fa-star text-2xl text-amber-400 group-hover:text-white"></i>
                            @endif
                        </div>
                        
                        <h3 class="text-xl font-bold text-white mb-1 tracking-wide group-hover:text-indigo-300 transition-colors">
                            {{ $service->name }}
                        </h3>
                        <p class="text-xs text-slate-500 line-clamp-2 px-4">{{ $service->description ?? 'Layanan umum' }}</p>
                    </div>

                    <div class="relative z-10 w-full my-4">
                        <div class="bg-slate-900/60 rounded-xl py-2 px-4 border border-slate-700/50 inline-flex flex-col items-center min-w-[140px]">
                            <span class="text-[10px] uppercase tracking-widest text-slate-400 mb-0.5">Sedang Dipanggil</span>
                            
                            <div id="status-text-{{ $service->id }}" class="text-2xl font-bold text-white tracking-tight">--</div>
                            
                            <div id="live-indicator-{{ $service->id }}" class="hidden items-center gap-1.5 mt-0.5">
                                <span class="live-dot"></span>
                                <span class="text-[9px] text-green-400 font-bold tracking-wider">LIVE</span>
                            </div>
                        </div>
                    </div>

                    <div class="relative z-10 w-full mt-auto">
                        <div class="mb-3">
                            <span class="inline-block py-1 px-3 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-300 text-xs font-semibold">
                                Tiket Anda Berikutnya: 
                                <span id="next-number-{{ $service->id }}" class="text-white font-bold ml-1 text-sm">Loading...</span>
                            </span>
                        </div>

                        <div class="w-full py-3 rounded-xl bg-slate-800 group-hover:bg-indigo-600 transition-colors text-slate-300 group-hover:text-white font-bold text-sm flex items-center justify-center gap-2">
                            Ambil Antrian <i class="fa-solid fa-print"></i>
                        </div>
                    </div>

                </button>
            @empty
                <div class="col-span-full flex flex-col items-center justify-center text-slate-500 py-20">
                    <i class="fa-solid fa-ban text-4xl mb-4"></i>
                    <p>Tidak ada layanan tersedia.</p>
                </div>
            @endforelse

        </div>
    </main>

    <footer class="text-center py-6 text-slate-600 text-[10px] uppercase tracking-widest">
        &copy; {{ date('Y') }} Sistem Antrian Terpadu
    </footer>

    <script>
        
        async function takeQueue(serviceId, serviceName) {
            Swal.fire({
                title: 'Mencetak Tiket...',
                text: 'Mohon tunggu sebentar',
                background: '#1e293b',
                color: '#fff',
                showConfirmButton: false,
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            try {
                const response = await fetch('/api/ticket', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ service_id: serviceId })
                });

                const result = await response.json();
                if (!response.ok) throw new Error(result.message || 'Error');

               
                Swal.fire({
                    background: '#ffffff',
                    color: '#1e293b',
                    html: `
                        <div class="flex flex-col items-center pt-2">
                            <div class="text-xs text-gray-400 uppercase tracking-widest font-semibold mb-1">Nomor Antrian Anda</div>
                            <div class="text-8xl font-black text-indigo-600 tracking-tighter leading-none mb-2">
                                ${result.data.queue_code}
                            </div>
                            <div class="bg-indigo-50 text-indigo-700 px-4 py-1.5 rounded-full font-bold text-sm mb-6 shadow-sm">
                                ${serviceName}
                            </div>
                            <div class="text-xs text-gray-400 border-t w-full pt-4">
                                Silakan duduk, nomor Anda akan segera dipanggil.
                            </div>
                        </div>
                    `,
                    showConfirmButton: true,
                    confirmButtonText: 'Selesai',
                    confirmButtonColor: '#4f46e5',
                    timer: 5000,
                    timerProgressBar: true
                });

                
                fetchStatusData();

            } catch (error) {
                Swal.fire({ icon: 'error', title: 'Gagal', text: error.message, background: '#1e293b', color: '#fff' });
            }
        }

       
        async function fetchStatusData() {
            try {
                const response = await fetch('/api/monitor');
                const data = await response.json();
                
                

                @foreach($services as $s)
                    updateCardData({{ $s->id }}, data);
                @endforeach

            } catch (error) {
                console.error("Gagal update status:", error);
            }
        }

        function updateCardData(serviceId, data) {
            
            const statusText = document.getElementById(`status-text-${serviceId}`);
            const liveIndicator = document.getElementById(`live-indicator-${serviceId}`);
            
            const active = data.active_queues.find(q => q.service_id === serviceId);

            if (active) {
                statusText.textContent = active.queue_code;
                statusText.classList.add('text-green-400');
                statusText.classList.remove('text-white');
                liveIndicator.classList.remove('hidden');
                liveIndicator.classList.add('flex');
            } else {
                statusText.textContent = "--";
                statusText.classList.add('text-white');
                statusText.classList.remove('text-green-400');
                liveIndicator.classList.add('hidden');
                liveIndicator.classList.remove('flex');
            }

           
            const nextNumText = document.getElementById(`next-number-${serviceId}`);
            
            
            if (data.next_queues) {
                const nextData = data.next_queues.find(q => q.service_id === serviceId);
                if (nextData) {
                    nextNumText.textContent = nextData.next_code;
                }
            }
        }


        fetchStatusData();
        setInterval(fetchStatusData, 3000);

    </script>
</body>
</html>