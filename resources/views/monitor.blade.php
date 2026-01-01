<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Monitor - QueuePro</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Chakra+Petch:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc; 
            color: #1e293b; 
            overflow: hidden; 
        }
        
        
        .font-digital { font-family: 'Chakra Petch', sans-serif; }

       
        
       
        .card-calling {
            border: 4px solid #f59e0b; 
            background-color: #fffbeb; 
            box-shadow: 0 0 40px rgba(245, 158, 11, 0.4);
            animation: pulse-calling 1.5s infinite;
            transform: scale(1.02);
            z-index: 20;
        }
        @keyframes pulse-calling {
            0% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.4); border-color: #f59e0b; }
            50% { box-shadow: 0 0 0 15px rgba(245, 158, 11, 0); border-color: #fbbf24; }
            100% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0); border-color: #f59e0b; }
        }

        
        .card-serving {
            border: 3px solid #10b981; 
            background-color: #ffffff;
            box-shadow: 0 10px 25px -5px rgba(16, 185, 129, 0.2);
        }

       
        .card-stop {
            border: 2px solid #e2e8f0;
            background-color: #ffffff;
        }

        
        .card-closed {
            background-color: #f1f5f9;
            border: 2px dashed #cbd5e1;
            opacity: 0.8;
            filter: grayscale(100%);
        }

        
        .smooth-transition { transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1); }
        .marquee-container { overflow: hidden; white-space: nowrap; }
        .marquee-content { display: inline-block; padding-left: 100%; animation: marquee 35s linear infinite; }
        @keyframes marquee { 0% { transform: translate(0, 0); } 100% { transform: translate(-100%, 0); } }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
    </style>
</head>
<body class="h-screen flex flex-col">

    <div id="startOverlay" onclick="startApp()" class="fixed inset-0 z-50 bg-slate-900/80 backdrop-blur-md flex items-center justify-center cursor-pointer transition-opacity duration-700">
        <div class="bg-white p-10 rounded-3xl shadow-2xl text-center max-w-sm mx-4 transform hover:scale-105 transition-transform">
            <div class="w-20 h-20 bg-indigo-600 text-white rounded-full flex items-center justify-center mx-auto mb-6 text-3xl shadow-lg shadow-indigo-500/30">
                <i class="fa-solid fa-play ml-1"></i>
            </div>
            <h3 class="text-2xl font-bold text-slate-800 mb-2">Hubungkan Layar</h3>
            <p class="text-slate-500 font-medium">Klik dimanapun untuk memulai monitor & suara.</p>
        </div>
    </div>

    <header class="h-24 bg-white border-b border-slate-200 flex items-center justify-between px-10 shadow-sm z-20 relative">
        <div class="flex items-center gap-5">
            <div class="bg-indigo-600 text-white w-12 h-12 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-200">
                <i class="fa-solid fa-layer-group text-2xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight leading-none">Queue<span class="text-indigo-600">Pro</span></h1>
                <p class="text-sm text-slate-500 font-bold uppercase tracking-[0.2em] mt-1">Sistem Antrian Terpadu</p>
            </div>
        </div>
        <div class="text-right">
            <div id="clock" class="text-4xl font-black text-slate-700 tracking-tight leading-none font-digital">00:00</div>
            <div id="date" class="text-sm text-slate-400 font-bold uppercase tracking-widest mt-1">--</div>
        </div>
        <div class="absolute bottom-0 left-0 w-full h-[3px] bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500"></div>
    </header>

    <main class="flex-grow flex p-8 gap-8 overflow-hidden relative">
        
        <div class="absolute inset-0 opacity-[0.03] pointer-events-none" style="background-image: radial-gradient(#6366f1 1px, transparent 1px); background-size: 24px 24px;"></div>

        <div class="flex-grow grid grid-cols-1 {{ count($services) > 1 ? 'lg:grid-cols-'.min(count($services), 2) : '' }} gap-8 h-full z-10">
            
            @foreach($services as $service)
            <div class="flex flex-col h-full bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden relative">
                
                <div class="bg-gradient-to-r from-slate-50 to-white px-8 py-5 border-b border-slate-100 flex justify-between items-center">
                    <h2 class="text-xl font-extrabold text-slate-700 flex items-center gap-3 uppercase">
                        @if(Str::contains(Str::lower($service->name), 'cs')) <i class="fa-solid fa-headset text-indigo-500"></i>
                        @elseif(Str::contains(Str::lower($service->name), 'teller')) <i class="fa-solid fa-money-bill-wave text-emerald-500"></i>
                        @else <i class="fa-solid fa-users text-slate-500"></i>
                        @endif
                        {{ $service->name }}
                    </h2>
                    <span class="text-xs font-black text-slate-400 bg-white border border-slate-200 px-3 py-1.5 rounded-lg shadow-sm">
                        {{ $service->code }}
                    </span>
                </div>

                <div class="flex-grow p-8 overflow-y-auto bg-slate-50/50">
                    <div class="grid grid-cols-1 gap-6">
                        @forelse($service->counters as $counter)
                            <div id="card-counter-{{ $counter->id }}" class="card-stop rounded-2xl p-6 text-center smooth-transition relative group">
                                
                                <div class="text-sm font-bold text-slate-400 uppercase tracking-[0.2em] mb-2 border-b border-slate-100 pb-2">
                                    {{ $counter->name }}
                                </div>

                                <div id="num-counter-{{ $counter->id }}" class="text-8xl font-black font-digital text-slate-300 tracking-wider my-4 leading-none smooth-transition scale-100">
                                    --
                                </div>

                                <div id="status-counter-{{ $counter->id }}" class="min-h-[40px] flex items-center justify-center">
                                    <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-slate-100 text-slate-400 text-xs font-bold uppercase tracking-wider border border-slate-200">
                                        Menunggu
                                    </span>
                                </div>

                                <div id="reason-counter-{{ $counter->id }}" class="hidden absolute inset-0 bg-slate-100/90 backdrop-blur-sm flex items-center justify-center rounded-2xl z-30">
                                    <div class="bg-white border-2 border-red-100 px-6 py-3 rounded-xl shadow-lg text-center transform rotate-0">
                                        <div class="text-xs text-slate-400 font-bold uppercase mb-1">MAAF, LOKET TUTUP</div>
                                        <div class="text-xl font-black text-red-500 uppercase tracking-widest" id="reason-text-{{ $counter->id }}">--</div>
                                    </div>
                                </div>

                            </div>
                        @empty
                            <div class="flex flex-col items-center justify-center h-full text-slate-400 opacity-60">
                                <i class="fa-solid fa-store-slash text-4xl mb-3"></i>
                                <span class="text-sm font-bold uppercase">Belum ada loket</span>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
            @endforeach

        </div>

        <aside class="w-96 flex-none flex flex-col gap-6 z-10">
            
            <div class="bg-white rounded-3xl border border-slate-200 shadow-lg overflow-hidden flex flex-col flex-grow max-h-[50%]">
                <div class="bg-gradient-to-r from-indigo-50 to-white px-6 py-4 border-b border-slate-100">
                    <h3 class="text-sm font-extrabold text-slate-700 flex items-center gap-2 uppercase tracking-wide">
                        <i class="fa-solid fa-list-ul text-indigo-500"></i> Sisa Antrian
                    </h3>
                </div>
                <div id="waitingList" class="p-4 space-y-3 overflow-y-auto scrollbar-hide bg-slate-50/30">
                    <div class="flex items-center justify-center h-24 text-slate-400 text-sm italic">
                        <i class="fa-solid fa-circle-notch fa-spin mr-2"></i> Memuat...
                    </div>
                </div>
            </div>

            <div class="bg-slate-900 rounded-3xl overflow-hidden shadow-xl flex-grow relative group border border-slate-800">
                <img src="https://images.unsplash.com/photo-1556761175-5973dc0f32e7?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" 
                     class="w-full h-full object-cover opacity-40 group-hover:opacity-50 transition-opacity duration-1000">
                <div class="absolute inset-0 flex flex-col items-center justify-center p-8 text-center bg-gradient-to-t from-slate-900 via-transparent to-transparent">
                    <span class="text-white/60 text-xs font-bold uppercase tracking-[0.3em] border-b border-white/20 pb-2 mb-2">Informasi</span>
                    <h3 class="text-white font-bold text-xl leading-tight">Melayani Dengan Sepenuh Hati</h3>
                </div>
            </div>

        </aside>

    </main>

    <footer class="h-16 bg-indigo-900 text-white flex items-center overflow-hidden z-20 shadow-[0_-5px_20px_rgba(0,0,0,0.1)] relative border-t-4 border-indigo-500">
        <div class="px-8 bg-indigo-800 h-full flex items-center font-black text-xs uppercase tracking-[0.2em] shadow-lg z-10 relative">
            <span class="absolute right-0 top-0 bottom-0 w-4 bg-gradient-to-r from-indigo-800 to-transparent"></span>
            INFO TERKINI:
        </div>
        <div class="marquee-container w-full bg-indigo-900">
            <div class="marquee-content text-lg font-medium py-2 text-indigo-100 tracking-wide flex items-center">
                Selamat Datang di Sistem Pelayanan Terpadu. Mohon menunggu nomor antrian Anda dipanggil. Budayakan antri untuk kenyamanan bersama. Terima kasih atas kunjungan Anda.
            </div>
        </div>
    </footer>

    <audio id="bellSound" src="https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3"></audio>

    <script>
       
        setInterval(() => {
            const now = new Date();
            document.getElementById('clock').innerText = now.toLocaleTimeString('id-ID', {hour12: false, hour:'2-digit', minute:'2-digit'});
            document.getElementById('date').innerText = now.toLocaleDateString('id-ID', {weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'});
        }, 1000);

        
        let lastCalledData = {}; 

        function startApp() {
            const overlay = document.getElementById('startOverlay');
            overlay.style.opacity = '0';
            setTimeout(() => overlay.remove(), 700);

            
            const bell = document.getElementById('bellSound');
            bell.volume = 0;
            bell.play().then(() => { bell.pause(); bell.volume = 1; }).catch(e => console.log("Audio blocked"));
            
            fetchQueueData();
            setInterval(fetchQueueData, 1000); 
        }

        async function fetchQueueData() {
            try {
                const response = await fetch('/api/monitor'); 
                const data = await response.json();
                updateDisplay(data);
            } catch(e) { console.error("Connection lost:", e); }
        }

        function updateDisplay(data) {
            const counters = data.counters; 
            const waitingSummary = data.waiting_summary;

            
            counters.forEach(c => {
                const counterId = c.id;
                const card = document.getElementById(`card-counter-${counterId}`);
                const numEl = document.getElementById(`num-counter-${counterId}`);
                const statusEl = document.getElementById(`status-counter-${counterId}`);
                const reasonEl = document.getElementById(`reason-counter-${counterId}`);
                const reasonText = document.getElementById(`reason-text-${counterId}`);

                if (!card) return;

               
                if (c.status === 'closed') {
                    card.className = "card-closed rounded-2xl p-6 text-center smooth-transition relative";
                    
                    
                    if (c.closing_reason) {
                        reasonEl.classList.remove('hidden');
                        reasonText.innerText = c.closing_reason;
                    } else {
                        
                        reasonEl.classList.add('hidden');
                        numEl.innerText = "--";
                        statusEl.innerHTML = `<span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-200 text-slate-500 text-[10px] font-bold uppercase tracking-wider">OFFLINE</span>`;
                    }
                    return; 
                }

               
                reasonEl.classList.add('hidden');

                
                if (c.active_queue) {
                    const q = c.active_queue;
                    numEl.innerText = q.queue_code;

                    if (q.status === 'called') {
                        
                        card.className = "card-calling rounded-2xl p-6 text-center smooth-transition relative";
                        numEl.className = "text-9xl font-black text-amber-500 tracking-tighter my-4 leading-none smooth-transition scale-110 drop-shadow-md";
                        statusEl.innerHTML = `<span class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-amber-500 text-white text-sm font-extrabold uppercase tracking-widest shadow-lg animate-bounce"><i class="fa-solid fa-bullhorn"></i> Memanggil...</span>`;
                        
                       
                        const lastState = lastCalledData[counterId];
                        if (!lastState || lastState.code !== q.queue_code || lastState.status !== 'called') {
                            playSound();
                            lastCalledData[counterId] = { code: q.queue_code, status: 'called' };
                        }

                    } else {
                        
                        card.className = "card-serving rounded-2xl p-6 text-center smooth-transition relative";
                        numEl.className = "text-8xl font-black text-emerald-600 tracking-tighter my-4 leading-none smooth-transition scale-100";
                        statusEl.innerHTML = `<span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold uppercase tracking-wider border border-emerald-200"><i class="fa-solid fa-user-check"></i> Sedang Melayani</span>`;
                        
                        lastCalledData[counterId] = { code: q.queue_code, status: 'serving' };
                    }

                } else if (c.last_queue) {
                  
                    card.className = "card-stop rounded-2xl p-6 text-center smooth-transition relative";
                    
                    numEl.innerText = c.last_queue.queue_code;
                    numEl.className = "text-8xl font-black text-slate-700 tracking-tighter my-4 leading-none smooth-transition opacity-80"; // Warna Abu Gelap
                    
                    statusEl.innerHTML = `<span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white border border-slate-300 text-slate-500 text-xs font-bold uppercase tracking-wider shadow-sm">Antrian Terakhir</span>`;

                } else {
                   
                    card.className = "card-stop rounded-2xl p-6 text-center smooth-transition relative";
                    numEl.innerText = "--";
                    numEl.className = "text-8xl font-black text-slate-200 tracking-tighter my-4 leading-none";
                    statusEl.innerHTML = `<span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-slate-100 text-slate-400 text-xs font-bold uppercase tracking-wider">Menunggu</span>`;
                }
            });

            
            const waitingList = document.getElementById('waitingList');
            waitingList.innerHTML = '';
            
            if(waitingSummary.length === 0) {
                waitingList.innerHTML = `<div class="flex flex-col items-center justify-center py-10 text-slate-400 opacity-60"><i class="fa-solid fa-mug-hot text-3xl mb-3 text-slate-300"></i><span class="text-xs font-bold uppercase tracking-widest">Antrian Kosong</span></div>`;
            } else {
                waitingSummary.forEach(item => {
                   
                    let badgeColor = "bg-slate-100 text-slate-500 border-slate-200";
                    let icon = "fa-users";
                    if(item.service.name.toLowerCase().includes('cs')) { badgeColor = "bg-indigo-50 text-indigo-600 border-indigo-100"; icon = "fa-headset"; }
                    if(item.service.name.toLowerCase().includes('teller')) { badgeColor = "bg-emerald-50 text-emerald-600 border-emerald-100"; icon = "fa-money-bill-wave"; }

                    waitingList.innerHTML += `
                        <div class="flex justify-between items-center p-3.5 rounded-2xl border border-slate-100 bg-white hover:shadow-md hover:border-slate-200 transition-all duration-300 group">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl ${badgeColor} border flex items-center justify-center font-bold text-lg shadow-sm group-hover:scale-110 transition-transform"><i class="fa-solid ${icon}"></i></div>
                                <div><div class="text-xs font-extrabold text-slate-700 uppercase tracking-tight">${item.service.name}</div><div class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider mt-0.5">Menunggu</div></div>
                            </div>
                            <div class="text-2xl font-black text-slate-800 tracking-tighter group-hover:text-indigo-600 transition-colors">${item.total}</div>
                        </div>`;
                });
            }
        }

        function playSound() {
            const bell = document.getElementById('bellSound');
            bell.currentTime = 0;
            bell.play().catch(e => console.log("Gagal memutar audio:", e));
        }
    </script>
</body>
</html>