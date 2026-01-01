<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Monitor Ultra - QueuePro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #0f172a; color: #f8fafc; overflow: hidden; }
        .font-mono-custom { font-family: 'JetBrains Mono', monospace; letter-spacing: -1px; }

       
        .smooth-all { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }

      
        .glass-card {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .card-calling {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(245, 158, 11, 0.2));
            border-color: rgba(245, 158, 11, 0.5);
            box-shadow: 0 0 20px rgba(245, 158, 11, 0.3);
            animation: pulse-glow 1.5s infinite;
        }
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 15px rgba(245, 158, 11, 0.2); border-color: rgba(245, 158, 11, 0.5); }
            50% { box-shadow: 0 0 25px rgba(245, 158, 11, 0.5); border-color: rgba(245, 158, 11, 0.8); }
        }

        .card-serving {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.05), rgba(16, 185, 129, 0.1));
            border-color: rgba(16, 185, 129, 0.4);
        }

      
        .scrollbar-hide::-webkit-scrollbar { display: none; }

       
        .marquee-container { overflow: hidden; white-space: nowrap; mask-image: linear-gradient(to right, transparent, black 5%, black 95%, transparent); }
        .marquee-content { display: inline-block; padding-left: 100%; animation: marquee 35s linear infinite; }
        @keyframes marquee { 0% { transform: translate(0, 0); } 100% { transform: translate(-100%, 0); } }

        
        .ping-dot { position: relative; }
        .ping-dot::after { content: ''; position: absolute; top: -1px; right: -1px; width: 6px; height: 6px; background: #22c55e; border-radius: 50%; animation: ping 1s cubic-bezier(0, 0, 0.2, 1) infinite; }
        @keyframes ping { 75%, 100% { transform: scale(2); opacity: 0; } }
    </style>
</head>
<body class="h-screen flex flex-col selection:bg-indigo-500 selection:text-white">

    <div id="startOverlay" onclick="startApp()" class="fixed inset-0 z-50 bg-black/90 backdrop-blur-md flex flex-col items-center justify-center cursor-pointer transition-opacity duration-500">
        <div class="relative group">
            <div class="absolute -inset-1 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-full blur opacity-75 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
            <button class="relative w-20 h-20 bg-black rounded-full flex items-center justify-center text-white text-3xl shadow-2xl">
                <i class="fa-solid fa-play ml-1 group-hover:scale-110 transition-transform"></i>
            </button>
        </div>
        <p class="mt-6 text-slate-400 font-medium tracking-wide text-sm uppercase">Tap to Launch Monitor</p>
    </div>

    <header class="h-14 px-5 flex items-center justify-between z-20 border-b border-white/5 bg-[#0f172a]/80 backdrop-blur-md">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white shadow-lg shadow-indigo-500/20">
                <i class="fa-solid fa-layer-group text-xs"></i>
            </div>
            <div>
                <h1 class="text-sm font-bold text-white tracking-tight leading-none">Queue<span class="text-indigo-400">Pro</span> <span class="text-[9px] text-slate-500 uppercase font-medium tracking-wider ml-1">Live</span></h1>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <div class="text-right">
                <div id="clock" class="text-lg font-bold text-white font-mono-custom leading-none">00:00:00</div>
                <div id="date" class="text-[10px] text-slate-400 font-medium uppercase tracking-wider leading-none mt-0.5">--</div>
            </div>
            <div id="connectionStatus" class="w-2 h-2 rounded-full bg-slate-600 transition-colors duration-300"></div>
        </div>
    </header>

    <main class="flex-grow p-4 gap-4 flex overflow-hidden relative">
        <div class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-indigo-900/20 via-[#0f172a] to-[#0f172a] -z-10"></div>

        <div class="flex-grow grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 h-full content-start overflow-y-auto scrollbar-hide pb-20">
            
            @foreach($services as $service)
            <div class="flex flex-col glass-card rounded-xl overflow-hidden h-full min-h-[220px]">
                <div class="bg-white/5 px-4 py-2.5 border-b border-white/5 flex justify-between items-center">
                    <h2 class="text-xs font-bold text-slate-300 flex items-center gap-2 uppercase tracking-wide">
                        <span class="w-1.5 h-1.5 rounded-full 
                            @if(Str::contains(Str::lower($service->name), 'cs')) bg-indigo-500 
                            @elseif(Str::contains(Str::lower($service->name), 'teller')) bg-emerald-500 
                            @else bg-slate-500 @endif">
                        </span>
                        {{ $service->name }}
                    </h2>
                    <span class="text-[10px] font-mono-custom text-slate-500">{{ $service->code }}</span>
                </div>

                <div class="p-3 flex-grow bg-black/20">
                    <div class="grid {{ $service->counters->count() > 1 ? 'grid-cols-2' : 'grid-cols-1' }} gap-2 h-full content-start">
                        
                        @forelse($service->counters as $counter)
                        <div id="card-counter-{{ $counter->id }}" class="glass-card rounded-lg p-2.5 flex flex-col items-center justify-center text-center relative smooth-all group h-full min-h-[90px]">
                            
                            <div class="w-full flex justify-between items-start mb-1">
                                <span class="text-[9px] font-bold text-slate-500 uppercase tracking-wider">{{ $counter->name }}</span>
                                <div id="status-dot-{{ $counter->id }}" class="w-1.5 h-1.5 rounded-full bg-slate-700"></div>
                            </div>
                            
                            <div id="num-counter-{{ $counter->id }}" class="text-4xl font-bold font-mono-custom text-slate-400 my-1 leading-none tracking-tight group-hover:scale-105 transition-transform duration-300">
                                --
                            </div>

                            <div id="status-text-{{ $counter->id }}" class="text-[9px] font-medium text-slate-500 uppercase tracking-widest mt-1">
                                Menunggu
                            </div>

                            <div id="reason-counter-{{ $counter->id }}" class="hidden absolute inset-0 bg-black/80 backdrop-blur-[2px] flex flex-col items-center justify-center rounded-lg z-20">
                                <i class="fa-solid fa-lock text-slate-500 text-sm mb-1"></i>
                                <span id="reason-text-{{ $counter->id }}" class="text-[9px] font-bold text-red-400 uppercase tracking-wide px-2 text-center leading-tight">--</span>
                            </div>
                        </div>
                        @empty
                        <div class="col-span-full flex flex-col items-center justify-center text-slate-600 py-6">
                            <i class="fa-solid fa-store-slash text-xl mb-1 opacity-50"></i>
                            <span class="text-[10px] font-medium uppercase tracking-wide">Offline</span>
                        </div>
                        @endforelse

                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <aside class="w-64 flex-none flex flex-col gap-4 z-10 h-full">
            
            <div class="glass-card rounded-xl overflow-hidden flex flex-col h-1/2">
                <div class="bg-white/5 px-4 py-2.5 border-b border-white/5">
                    <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest flex items-center gap-2">
                        <i class="fa-solid fa-list-ul text-indigo-500"></i> Antrian
                    </h3>
                </div>
                <div id="waitingList" class="p-2 space-y-1.5 overflow-y-auto scrollbar-hide flex-grow">
                    <div class="flex items-center justify-center h-full text-slate-600 text-[10px] italic">Memuat...</div>
                </div>
            </div>

            <div class="relative rounded-xl overflow-hidden shadow-lg flex-grow group border border-white/10">
                <img src="https://images.unsplash.com/photo-1556761175-5973dc0f32e7?auto=format&fit=crop&w=600&q=80" 
                     class="absolute inset-0 w-full h-full object-cover opacity-40 group-hover:scale-105 transition-transform duration-1000">
                <div class="absolute inset-0 bg-gradient-to-t from-black via-black/50 to-transparent flex flex-col justify-end p-4">
                    <span class="text-indigo-400 text-[9px] font-bold uppercase tracking-widest mb-0.5">Info</span>
                    <h3 class="text-white font-bold text-sm leading-tight">Pelayanan Prima Prioritas Kami</h3>
                </div>
            </div>

        </aside>
    </main>

    <footer class="h-8 bg-[#0f172a] text-white flex items-center overflow-hidden z-20 border-t border-white/5 fixed bottom-0 w-full">
        <div class="px-4 bg-indigo-600 h-full flex items-center font-bold text-[9px] uppercase tracking-widest shadow-xl z-10">
            Live Info
        </div>
        <div class="marquee-container w-full">
            <div class="marquee-content text-xs font-medium text-slate-300 flex items-center">
                <span class="mx-4">•</span> Selamat Datang di QueuePro <span class="mx-4">•</span> Mohon menunggu nomor antrian dipanggil <span class="mx-4">•</span> Terima kasih atas kesabaran Anda <span class="mx-4">•</span> Budayakan antri untuk kenyamanan bersama
            </div>
        </div>
    </footer>

    <audio id="bellSound" src="https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3"></audio>

    <script>
       
        function updateClock() {
            const now = new Date();
            document.getElementById('clock').innerText = now.toLocaleTimeString('id-ID', {hour12: false});
            document.getElementById('date').innerText = now.toLocaleDateString('id-ID', {weekday: 'short', day: 'numeric', month: 'short'});
            requestAnimationFrame(updateClock);
        }
        requestAnimationFrame(updateClock);

        let lastCalledData = {}; 

        function startApp() {
            const overlay = document.getElementById('startOverlay');
            overlay.style.opacity = '0';
            setTimeout(() => overlay.remove(), 500);
            
            const bell = document.getElementById('bellSound');
            bell.volume = 0; bell.play().then(() => { bell.pause(); bell.volume = 1; }).catch(e => console.log("Audio blocked"));
            
            fetchQueueData();
            
            setInterval(fetchQueueData, 800);
        }

        async function fetchQueueData() {
            const statusIndicator = document.getElementById('connectionStatus');
            try {
               
                statusIndicator.classList.add('bg-emerald-500', 'shadow-[0_0_8px_rgba(16,185,129,0.6)]');
                statusIndicator.classList.remove('bg-slate-600', 'bg-red-500');

                const response = await fetch('/api/monitor'); 
                const data = await response.json();
                updateDisplay(data);

                
                setTimeout(() => {
                    statusIndicator.classList.remove('bg-emerald-500', 'shadow-[0_0_8px_rgba(16,185,129,0.6)]');
                    statusIndicator.classList.add('bg-slate-600');
                }, 200);

            } catch(e) { 
                console.error("Connection lost:", e);
                statusIndicator.classList.add('bg-red-500');
                statusIndicator.classList.remove('bg-emerald-500', 'bg-slate-600');
            }
        }

        function updateDisplay(data) {
            const counters = data.counters; 
            const waitingSummary = data.waiting_summary;

            counters.forEach(c => {
                const counterId = c.id;
                const card = document.getElementById(`card-counter-${counterId}`);
                const numEl = document.getElementById(`num-counter-${counterId}`);
                const statusText = document.getElementById(`status-text-${counterId}`);
                const statusDot = document.getElementById(`status-dot-${counterId}`);
                const reasonEl = document.getElementById(`reason-counter-${counterId}`);
                const reasonText = document.getElementById(`reason-text-${counterId}`);

                if (!card) return;

                
                if (c.status === 'closed') {
                    card.className = "glass-card rounded-lg p-2.5 flex flex-col items-center justify-center text-center relative smooth-all h-full min-h-[90px] opacity-60";
                    if (c.closing_reason) {
                        reasonEl.classList.remove('hidden');
                        reasonText.innerText = c.closing_reason;
                    } else {
                        reasonEl.classList.add('hidden');
                        numEl.innerText = "--";
                        numEl.className = "text-4xl font-bold font-mono-custom text-slate-600 my-1 leading-none";
                        statusText.innerText = "OFFLINE";
                        statusText.className = "text-[9px] font-bold text-slate-600 uppercase tracking-widest mt-1";
                        statusDot.className = "w-1.5 h-1.5 rounded-full bg-slate-600";
                    }
                    return; 
                }

                reasonEl.classList.add('hidden');
                
              
                if (c.active_queue) {
                    const q = c.active_queue;
                    numEl.innerText = q.queue_code;

                    if (q.status === 'called') {
                        
                        card.className = "card-calling rounded-lg p-2.5 flex flex-col items-center justify-center text-center relative smooth-all h-full min-h-[90px] z-10 border border-amber-500/50";
                        numEl.className = "text-5xl font-bold font-mono-custom text-amber-400 my-1 leading-none drop-shadow-[0_0_8px_rgba(251,191,36,0.5)] scale-110 transition-transform";
                        statusText.innerHTML = "<i class='fa-solid fa-bullhorn mr-1'></i> MEMANGGIL";
                        statusText.className = "text-[9px] font-bold text-amber-400 uppercase tracking-widest mt-1 animate-pulse";
                        statusDot.className = "w-1.5 h-1.5 rounded-full bg-amber-500 shadow-[0_0_5px_#f59e0b]";
                        
                       
                        const lastState = lastCalledData[counterId];
                        if (!lastState || lastState.code !== q.queue_code || lastState.status !== 'called') {
                            playSound();
                            lastCalledData[counterId] = { code: q.queue_code, status: 'called' };
                        }

                    } else {
                        
                        card.className = "card-serving rounded-lg p-2.5 flex flex-col items-center justify-center text-center relative smooth-all h-full min-h-[90px]";
                        numEl.className = "text-4xl font-bold font-mono-custom text-emerald-400 my-1 leading-none";
                        statusText.innerHTML = "MELAYANI";
                        statusText.className = "text-[9px] font-bold text-emerald-500 uppercase tracking-widest mt-1";
                        statusDot.className = "w-1.5 h-1.5 rounded-full bg-emerald-500 shadow-[0_0_5px_#10b981]";
                        
                        lastCalledData[counterId] = { code: q.queue_code, status: 'serving' };
                    }

                } else if (c.last_queue) {
                    
                    card.className = "glass-card rounded-lg p-2.5 flex flex-col items-center justify-center text-center relative smooth-all h-full min-h-[90px]";
                    numEl.innerText = c.last_queue.queue_code;
                    numEl.className = "text-4xl font-bold font-mono-custom text-slate-500 my-1 leading-none opacity-50"; 
                    statusText.innerText = "SELESAI";
                    statusText.className = "text-[9px] font-medium text-slate-500 uppercase tracking-widest mt-1";
                    statusDot.className = "w-1.5 h-1.5 rounded-full bg-slate-500";

                } else {
                    
                    card.className = "glass-card rounded-lg p-2.5 flex flex-col items-center justify-center text-center relative smooth-all h-full min-h-[90px]";
                    numEl.innerText = "--";
                    numEl.className = "text-4xl font-bold font-mono-custom text-slate-700 my-1 leading-none";
                    statusText.innerText = "MENUNGGU";
                    statusText.className = "text-[9px] font-medium text-slate-600 uppercase tracking-widest mt-1";
                    statusDot.className = "w-1.5 h-1.5 rounded-full bg-slate-700";
                }
            });

           
            const waitingList = document.getElementById('waitingList');
            waitingList.innerHTML = '';
            
            if(waitingSummary.length === 0) {
                waitingList.innerHTML = `<div class="flex flex-col items-center justify-center py-8 text-slate-600 opacity-50"><i class="fa-solid fa-mug-hot text-lg mb-1"></i><span class="text-[9px] font-bold uppercase">Kosong</span></div>`;
            } else {
                waitingSummary.forEach(item => {
                    let badgeClass = "bg-slate-800 text-slate-400";
                    if(item.service.name.toLowerCase().includes('cs')) badgeClass = "bg-indigo-500/10 text-indigo-400 border border-indigo-500/20";
                    if(item.service.name.toLowerCase().includes('teller')) badgeClass = "bg-emerald-500/10 text-emerald-400 border border-emerald-500/20";

                    waitingList.innerHTML += `
                        <div class="flex justify-between items-center p-2 rounded-lg bg-white/5 border border-white/5 hover:bg-white/10 transition-colors">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded ${badgeClass} flex items-center justify-center font-bold text-[10px] shadow-sm">${item.service.code}</div>
                                <div class="text-[10px] font-bold text-slate-300 uppercase tracking-tight">${item.service.name}</div>
                            </div>
                            <div class="text-sm font-bold text-white font-mono-custom">${item.total}</div>
                        </div>`;
                });
            }
        }

        function playSound() {
            const bell = document.getElementById('bellSound');
            bell.currentTime = 0; bell.play().catch(e => console.log("Gagal memutar audio:", e));
        }
    </script>
</body>
</html>