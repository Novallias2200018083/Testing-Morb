@extends('layouts.admin')

@section('title', 'Panel Petugas')
@section('header_title', 'Operator Dashboard')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 fade-in-up">

    <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm flex flex-col sm:flex-row justify-between items-center gap-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl border border-indigo-100 shadow-sm">
                <i class="fa-solid fa-store"></i>
            </div>
            <div>
                <h2 class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-0.5">Status Login</h2>
                <div id="currentCounterName" class="text-lg font-bold text-slate-800">Memuat...</div>
            </div>
        </div>
        
        <div class="flex gap-2" id="counterActions">
            {{-- <button onclick="changeCounter()" class="px-4 py-2 text-xs font-bold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-lg border border-indigo-200 transition-colors">
                <i class="fa-solid fa-exchange-alt mr-1"></i> Ganti Loket
            </button>
            <button onclick="closeCounterPrompt()" class="px-4 py-2 text-xs font-bold text-white bg-red-500 hover:bg-red-600 rounded-lg shadow-md shadow-red-200 transition-colors">
                <i class="fa-solid fa-power-off mr-1"></i> Tutup Loket
            </button> --}}
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <div class="lg:col-span-8">
            <div id="mainPanel" class="bg-white rounded-3xl border border-slate-200 shadow-lg overflow-hidden relative min-h-[500px] flex flex-col transition-all duration-500">
                
                <div id="statusHeader" class="bg-slate-800 text-white p-4 text-center transition-colors duration-500 flex justify-between px-8">
                    <span id="statusText" class="font-bold uppercase tracking-[0.2em] text-sm">MENUNGGU</span>
                    <span id="realtimeClock" class="font-mono text-sm opacity-80">00:00:00</span>
                </div>

                <div class="flex-grow flex flex-col items-center justify-center p-8 text-center relative z-10">
                    <div class="mb-4 text-slate-400 font-bold text-xs uppercase tracking-widest border-b-2 border-slate-100 pb-1">Nomor Antrian Saat Ini</div>
                    
                    <div id="currentNumber" class="text-[8rem] leading-none font-black text-slate-800 tracking-tighter transition-all">
                        --
                    </div>

                    <div id="serviceInfo" class="mt-8 opacity-0 transition-all duration-500 transform translate-y-4">
                        <span class="px-6 py-2 rounded-full bg-indigo-50 text-indigo-700 text-sm font-bold border border-indigo-100 flex items-center gap-2 shadow-sm">
                            <i class="fa-solid fa-ticket"></i> <span id="serviceName">-</span>
                        </span>
                    </div>
                </div>

                <div class="p-6 bg-slate-50 border-t border-slate-100" id="controlBar">
                    
                    <div id="idleControls" class="flex justify-center">
                        <button onclick="callNext()" id="btnNext" class="w-full py-5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl shadow-lg shadow-indigo-200 font-bold text-xl transition-all active:scale-95 flex items-center justify-center gap-3">
                            <span>PANGGIL ANTRIAN BERIKUTNYA</span> <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </div>

                    <div id="activeControls" class="hidden grid-cols-2 gap-4">
                        
                        <button onclick="updateStatus('serving')" id="btnServe" class="col-span-1 py-4 bg-emerald-500 hover:bg-emerald-600 text-white rounded-2xl font-bold shadow-md shadow-emerald-200 active:scale-95 flex items-center justify-center gap-2">
                            <i class="fa-solid fa-user-check text-xl"></i> Mulai Layani
                        </button>
                        
                        <div class="col-span-1 grid grid-cols-2 gap-2" id="preServeTools">
                            <button onclick="recall()" id="btnRecall" class="bg-amber-500 hover:bg-amber-600 text-white rounded-2xl font-bold shadow-md active:scale-95 flex flex-col items-center justify-center">
                                <i class="fa-solid fa-bullhorn mb-1"></i> <span class="text-[10px] uppercase">Panggil Ulang</span>
                            </button>
                            <button onclick="skipQueue()" class="bg-white border-2 border-slate-200 text-slate-500 hover:bg-slate-100 rounded-2xl font-bold active:scale-95 flex flex-col items-center justify-center">
                                <i class="fa-solid fa-forward mb-1"></i> <span class="text-[10px] uppercase">Lewati</span>
                            </button>
                        </div>

                        <div id="finishTools" class="hidden col-span-2 grid grid-cols-3 gap-4">
                             <button onclick="completeQueue('stop')" class="col-span-1 py-4 bg-slate-700 hover:bg-slate-800 text-white rounded-2xl font-bold shadow-lg active:scale-95 flex flex-col items-center justify-center leading-tight">
                                <span class="text-sm"><i class="fa-solid fa-stop mr-1"></i> SELESAI</span>
                                <span class="text-[10px] opacity-70 font-normal">& Stop</span>
                            </button>

                            <button onclick="completeQueue('next')" class="col-span-2 py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-bold text-lg shadow-lg shadow-indigo-200 active:scale-95 flex items-center justify-center gap-2">
                                <i class="fa-solid fa-check-circle"></i> SELESAI & NEXT
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-4 space-y-4 flex flex-col h-[600px]">
            
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm flex flex-col h-1/2 overflow-hidden">
                <div class="bg-slate-50 px-4 py-3 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="text-xs font-extrabold text-slate-600 uppercase tracking-widest flex items-center gap-2">
                        <i class="fa-regular fa-clock text-indigo-500"></i> Menunggu
                    </h3>
                    <span id="pendingCount" class="bg-indigo-100 text-indigo-600 text-[10px] font-bold px-2 py-0.5 rounded-full">0</span>
                </div>
                <div id="waitingListContainer" class="p-2 space-y-2 overflow-y-auto flex-grow custom-scrollbar">
                    <div class="text-center text-slate-400 text-xs py-4 italic">Memuat data...</div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-red-100 shadow-sm flex flex-col h-1/2 overflow-hidden relative">
                <div class="absolute top-0 right-0 w-16 h-16 bg-red-50 rounded-bl-full -mr-8 -mt-8 z-0"></div>
                <div class="bg-red-50/50 px-4 py-3 border-b border-red-100 flex justify-between items-center relative z-10">
                    <h3 class="text-xs font-extrabold text-red-600 uppercase tracking-widest flex items-center gap-2">
                        <i class="fa-solid fa-forward text-red-500"></i> Dilewati
                    </h3>
                    <span id="skippedCount" class="bg-red-100 text-red-600 text-[10px] font-bold px-2 py-0.5 rounded-full">0</span>
                </div>
                <div id="skippedListContainer" class="p-2 space-y-2 overflow-y-auto flex-grow custom-scrollbar bg-red-50/10">
                    <div class="text-center text-red-300 text-xs py-4 italic">Kosong</div>
                </div>
            </div>

        </div>
    </div>
</div>

@include('components.operator-counter-modal')

@endsection

@push('scripts')
<script>
    const API_BASE = '/api/admin';
    const token = localStorage.getItem('admin_token');
    
    
    let currentQueueId = null;
    let currentStatus = 'idle';
    let userRole = 'staff'; 

   
    setInterval(() => {
        document.getElementById('realtimeClock').innerText = new Date().toLocaleTimeString('id-ID');
    }, 1000);

    
    document.addEventListener('DOMContentLoaded', () => {
        if(!token) window.location.href = '/admin/login';
        checkOperatorState();
    });

    async function checkOperatorState() {
        try {
            
            const user = JSON.parse(localStorage.getItem('admin_user') || '{}');
            userRole = user.role || 'staff';

            
            if (userRole === 'admin') {
                document.getElementById('currentCounterName').innerHTML = '<span class="text-indigo-600">Administrator Mode</span>';
                document.getElementById('counterActions').style.display = 'none'; 
                
                
                const res = await fetch(`${API_BASE}/operator-state`, { headers: { 'Authorization': `Bearer ${token}` } });
                const data = await res.json();
                
                if (data.counter) {
                   
                    updateCounterUI(data.counter.name + " (Admin)");
                    document.getElementById('counterActions').style.display = 'flex'; 
                    if (data.active_queue) restoreSession(data.active_queue);
                    else setIdleState();
                } else {
                   
                    document.getElementById('controlBar').classList.add('opacity-50', 'pointer-events-none');
                    document.getElementById('statusText').innerText = "MODE MONITORING";
                    
                    
                }
                
                startPollingDashboard(); 
                return;
            }

            
            const res = await fetch(`${API_BASE}/operator-state`, { headers: { 'Authorization': `Bearer ${token}` } });
            if (res.status === 401) { location.href = '/admin/login'; return; }
            const data = await res.json();

            if (data.counter) {
                updateCounterUI(data.counter.name);
                if (data.active_queue) restoreSession(data.active_queue);
                else setIdleState();
                startPollingDashboard(); 
            } else {
                loadCountersForModal(); 
            }
        } catch (e) { console.error(e); }
    }

    
    async function callNext() {
        setBtnLoading(true);
        try {
            const res = await fetch(`${API_BASE}/next`, { method: 'POST', headers: { 'Authorization': `Bearer ${token}` } });
            const result = await res.json();

            if (res.ok) updateUI(result.data, 'called');
            else {
                Swal.fire({ toast: true, icon: 'info', title: 'Antrian Kosong', position: 'top', showConfirmButton: false, timer: 1500 });
                setIdleState();
            }
        } catch (e) { console.error(e); }
        setBtnLoading(false);
    }

    async function updateStatus(status) {
        if(!currentQueueId) return;
        await fetch(`${API_BASE}/queue/${currentQueueId}/status`, { 
            method: 'POST', 
            headers: { 'Authorization': `Bearer ${token}`, 'Content-Type': 'application/json' },
            body: JSON.stringify({ status })
        });
        if(status === 'serving') updateUI(null, 'serving');
    }

    async function recall() {
        if(!currentQueueId) return;
        const btn = document.getElementById('btnRecall');
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
        await fetch(`${API_BASE}/queue/${currentQueueId}/recall`, { method: 'POST', headers: { 'Authorization': `Bearer ${token}` } });
        setTimeout(() => btn.innerHTML = '<i class="fa-solid fa-bullhorn mb-1"></i><span class="text-[10px] uppercase">Panggil Ulang</span>', 500);
    }

    async function skipQueue() {
        if(!currentQueueId) return;
        await fetch(`${API_BASE}/queue/${currentQueueId}/skip`, { method: 'POST', headers: { 'Authorization': `Bearer ${token}` } });
        setIdleState();
        fetchDashboardStats();
    }

    async function recallFromSkipped(id) {
       
        if (document.getElementById('controlBar').classList.contains('pointer-events-none')) {
            Swal.fire('Info', 'Anda dalam mode monitoring. Pilih loket terlebih dahulu untuk memanggil.', 'info');
            return;
        }

        Swal.fire({
            title: 'Panggil Ulang?',
            text: "Antrian akan menjadi aktif kembali.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Panggil',
            confirmButtonColor: '#f59e0b'
        }).then(async (r) => {
            if(r.isConfirmed) {
                const res = await fetch(`${API_BASE}/queue/${id}/recall`, { method: 'POST', headers: { 'Authorization': `Bearer ${token}` } });
                if(res.ok) checkOperatorState(); 
            }
        });
    }

    async function completeQueue(mode) {
        if(!currentQueueId) return;
        await fetch(`${API_BASE}/queue/${currentQueueId}/status`, { 
            method: 'POST', 
            headers: { 'Authorization': `Bearer ${token}`, 'Content-Type': 'application/json' },
            body: JSON.stringify({ status: 'completed' })
        });
        if (mode === 'next') callNext();
        else setIdleState();
    }

    async function closeCounterPrompt() {
        const { value: reason } = await Swal.fire({
            title: 'Tutup Loket?',
            input: 'textarea',
            inputLabel: 'Alasan Penutupan',
            inputPlaceholder: 'Contoh: Istirahat Makan Siang',
            showCancelButton: true,
            confirmButtonText: 'Tutup Loket',
            confirmButtonColor: '#ef4444',
            inputValidator: (value) => { if (!value) return 'Wajib isi alasan!'; }
        });

        if (reason) {
            await fetch(`${API_BASE}/counter/close`, { 
                method: 'POST', 
                headers: { 'Authorization': `Bearer ${token}`, 'Content-Type': 'application/json' },
                body: JSON.stringify({ reason }) 
            });
            location.reload();
        }
    }

    
    function updateUI(queueData, status) {
        currentStatus = status;
        const els = {
            header: document.getElementById('statusHeader'),
            text: document.getElementById('statusText'),
            num: document.getElementById('currentNumber'),
            info: document.getElementById('serviceInfo'),
            idle: document.getElementById('idleControls'),
            active: document.getElementById('activeControls'),
            preServe: document.getElementById('preServeTools'),
            finish: document.getElementById('finishTools')
        };

        if(queueData) {
            currentQueueId = queueData.id;
            els.num.innerText = queueData.queue_code;
            document.getElementById('serviceName').innerText = queueData.service ? queueData.service.name : '-';
            els.info.classList.remove('opacity-0', 'translate-y-4');
        }

        els.idle.classList.add('hidden');
        els.active.classList.remove('hidden'); els.active.classList.add('grid');

        if (status === 'called') {
            els.header.className = "bg-amber-500 text-white p-4 text-center transition-colors duration-500 flex justify-between px-8";
            els.text.innerHTML = '<i class="fa-solid fa-bullhorn animate-bounce"></i> MEMANGGIL...';
            els.num.className = "text-[8rem] leading-none font-black text-amber-500 tracking-tighter transition-all scale-110";
            document.getElementById('btnServe').classList.remove('hidden');
            els.preServe.classList.remove('hidden');
            els.finish.classList.add('hidden');
        } else if (status === 'serving') {
            els.header.className = "bg-emerald-600 text-white p-4 text-center transition-colors duration-500 flex justify-between px-8";
            els.text.innerHTML = '<i class="fa-solid fa-user-check"></i> SEDANG MELAYANI';
            els.num.className = "text-[8rem] leading-none font-black text-emerald-600 tracking-tighter transition-all scale-100";
            document.getElementById('btnServe').classList.add('hidden');
            els.preServe.classList.add('hidden');
            els.finish.classList.remove('hidden'); els.finish.classList.add('grid');
        }
    }

    function setIdleState() {
        currentStatus = 'idle';
        currentQueueId = null;
        document.getElementById('statusHeader').className = "bg-slate-800 text-white p-4 text-center transition-colors duration-500 flex justify-between px-8";
        document.getElementById('statusText').innerText = "MENUNGGU";
        
        const num = document.getElementById('currentNumber');
        num.innerText = "--";
        num.className = "text-[8rem] leading-none font-black text-slate-300 tracking-tighter transition-all scale-100";
        document.getElementById('serviceInfo').classList.add('opacity-0', 'translate-y-4');

        document.getElementById('idleControls').classList.remove('hidden');
        document.getElementById('activeControls').classList.add('hidden');
        document.getElementById('activeControls').classList.remove('grid');
    }
    
    function setBtnLoading(isLoading) {
        const btn = document.getElementById('btnNext');
        if(isLoading) {
            btn.disabled = true; btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i>';
        } else {
            btn.disabled = false; btn.innerHTML = '<span>PANGGIL ANTRIAN BERIKUTNYA</span> <i class="fa-solid fa-arrow-right"></i>';
        }
    }

    function restoreSession(queue) { updateUI(queue, queue.status); }
    function updateCounterUI(name) { document.getElementById('currentCounterName').innerText = name; }
    
    
    async function changeCounter() {
        const r = await Swal.fire({ title: 'Ganti Loket?', icon: 'warning', showCancelButton: true, confirmButtonText: 'Ya' });
        if(r.isConfirmed) { 
            await fetch(`${API_BASE}/checkout`, { method: 'POST', headers: { 'Authorization': `Bearer ${token}` } });
            location.reload(); 
        }
    }

    
    function startPollingDashboard() {
        fetchDashboardStats();
        setInterval(fetchDashboardStats, 3000);
    }

    async function fetchDashboardStats() {
        try {
            const res = await fetch(`${API_BASE}/dashboard-stats`, { headers: { 'Authorization': `Bearer ${token}` } });
            const data = await res.json();
            
            
            const waitList = document.getElementById('waitingListContainer');
            waitList.innerHTML = '';
            document.getElementById('pendingCount').innerText = data.waiting.length;
            
            if(data.waiting.length === 0) waitList.innerHTML = '<div class="text-center text-slate-400 text-xs py-4 italic">Kosong</div>';
            else {
                data.waiting.forEach(q => {
                    waitList.innerHTML += `
                        <div class="bg-slate-50 p-2 rounded-lg border border-slate-100 flex justify-between items-center group hover:bg-white hover:shadow-sm transition-all">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-white border border-slate-200 rounded-md flex items-center justify-center font-bold text-slate-700 text-sm shadow-sm">${q.code}</div>
                                <div>
                                    <div class="text-[9px] font-bold text-slate-400 uppercase tracking-wide">Masuk ${q.time}</div>
                                    <div class="text-[10px] font-semibold text-indigo-600">${q.service_name}</div>
                                </div>
                            </div>
                        </div>`;
                });
            }

            
            const skipList = document.getElementById('skippedListContainer');
            skipList.innerHTML = '';
            document.getElementById('skippedCount').innerText = data.skipped.length;

            if(data.skipped.length === 0) skipList.innerHTML = '<div class="text-center text-red-300 text-xs py-4 italic opacity-70">Kosong</div>';
            else {
                data.skipped.forEach(q => {
                    skipList.innerHTML += `
                        <div class="bg-white p-2 rounded-lg border border-red-100 flex justify-between items-center group hover:shadow-sm transition-all">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-red-50 border border-red-100 rounded-md flex items-center justify-center font-bold text-red-500 text-sm">${q.code}</div>
                                <div>
                                    <div class="text-[9px] font-bold text-red-300 uppercase">Dilewati</div>
                                    <div class="text-[10px] font-semibold text-slate-600">${q.service_name} • ${q.skipped_at}</div>
                                </div>
                            </div>
                            <button onclick="recallFromSkipped(${q.id})" class="bg-amber-100 hover:bg-amber-200 text-amber-700 p-1.5 rounded-md transition-colors" title="Panggil Kembali">
                                <i class="fa-solid fa-undo text-xs"></i>
                            </button>
                        </div>`;
                });
            }

        } catch(e) {}
    }
</script>
@endpush