@extends('layouts.admin')

@section('title', 'Dashboard Operasional')
@section('header_title', 'Control Center')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 fade-in-up pb-20">

    
    <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-4 border border-slate-200 shadow-sm flex flex-col sm:flex-row justify-between items-center gap-4 sticky top-0 z-30">
        <div class="flex items-center gap-4">
            <div id="roleIcon" class="w-12 h-12 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center text-xl border border-slate-200 shadow-inner">
                <i class="fa-solid fa-circle-notch fa-spin"></i>
            </div>
            <div>
                <h2 class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-0.5">Mode Operasional</h2>
                <div id="dashboardTitle" class="text-lg font-bold text-slate-800 leading-tight">Memuat Sistem...</div>
            </div>
        </div>
        
        <div class="flex items-center gap-4">
            <div class="text-right hidden sm:block px-4 border-r border-slate-200">
                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Waktu Server</div>
                <div id="realtimeClock" class="font-mono text-lg font-bold text-indigo-600">00:00:00</div>
            </div>
            <div id="staffActions" class="hidden">
                
            </div>
        </div>
    </div>

   
    <div id="adminPanel" class="hidden space-y-8">
        
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm group hover:border-indigo-300 transition-all">
                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">Menunggu</div>
                <div id="admStatPending" class="text-3xl font-black text-slate-700 mt-2">-</div>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-indigo-100 shadow-sm group hover:border-indigo-300 transition-all">
                <div class="text-xs font-bold text-indigo-400 uppercase tracking-widest">Sedang Dilayani</div>
                <div id="admStatServing" class="text-3xl font-black text-indigo-600 mt-2">-</div>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-emerald-100 shadow-sm group hover:border-emerald-300 transition-all">
                <div class="text-xs font-bold text-emerald-500 uppercase tracking-widest">Selesai</div>
                <div id="admStatCompleted" class="text-3xl font-black text-emerald-600 mt-2">-</div>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-red-100 shadow-sm group hover:border-red-300 transition-all">
                <div class="text-xs font-bold text-red-400 uppercase tracking-widest">Dilewati</div>
                <div id="admStatSkipped" class="text-3xl font-black text-red-500 mt-2">-</div>
            </div>
        </div>

       
        <div>
            <h3 class="text-sm font-bold text-slate-700 uppercase tracking-widest mb-4 flex items-center gap-2">
                <i class="fa-solid fa-satellite-dish text-red-500 animate-pulse"></i> Live Monitoring Layanan
            </h3>
            <div id="liveMonitorGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
               
            </div>
        </div>

       
        <div class="bg-slate-800 text-white rounded-2xl shadow-lg p-6">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4 border-b border-slate-700 pb-2">Status Petugas & Loket</h3>
            <div id="admCountersGrid" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4"></div>
        </div>
    </div>

    
    <div id="staffPanel" class="hidden grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        
        <div class="lg:col-span-8">
            <div id="mainPanel" class="bg-white rounded-[2rem] border border-slate-200 shadow-xl overflow-hidden relative min-h-[550px] flex flex-col transition-all duration-500">
                
               
                <div id="statusHeader" class="bg-slate-800 text-white p-5 text-center transition-colors duration-500 flex justify-between items-center px-8 shadow-md relative z-20">
                    <span id="statusText" class="font-black uppercase tracking-[0.25em] text-sm animate-pulse">MENUNGGU</span>
                    <div class="w-2 h-2 rounded-full bg-white animate-ping"></div>
                </div>

                
                <div class="flex-grow flex flex-col items-center justify-center p-8 text-center relative z-10 bg-slate-50/50">
                    <div class="absolute inset-0 opacity-5" style="background-image: radial-gradient(#6366f1 1px, transparent 1px); background-size: 20px 20px;"></div>
                    
                    <div class="mb-2 text-slate-400 font-bold text-[10px] uppercase tracking-[0.3em] relative z-10">Nomor Antrian</div>
                    
                    <div id="currentNumber" class="text-[7rem] sm:text-[9rem] leading-none font-black text-slate-800 tracking-tighter transition-all duration-300 filter drop-shadow-sm relative z-10">
                        --
                    </div>

                    <div id="serviceInfo" class="mt-8 opacity-0 transition-all duration-500 transform translate-y-4 relative z-10">
                        <span class="px-6 py-2 rounded-full bg-white text-indigo-700 text-xs font-bold border border-indigo-100 shadow-md flex items-center gap-2">
                             <span id="staffServiceName">-</span>
                        </span>
                    </div>
                </div>

               
                <div class="p-6 bg-white border-t border-slate-100 relative z-20 shadow-[0_-5px_15px_rgba(0,0,0,0.02)]" id="controlBar">
                    
                    
                    <div id="idleControls" class="flex justify-center">
                        <button onclick="callNext()" id="btnNext" class="group w-full py-5 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 text-white rounded-2xl shadow-lg shadow-indigo-200 font-bold text-xl transition-all active:scale-[0.98] flex items-center justify-center gap-3">
                            <span>PANGGIL BERIKUTNYA</span> 
                            <div class="bg-white/20 rounded-full w-8 h-8 flex items-center justify-center group-hover:translate-x-1 transition-transform">
                                <i class="fa-solid fa-arrow-right text-sm"></i>
                            </div>
                        </button>
                    </div>

                   
                    <div id="activeControls" class="hidden grid-cols-2 gap-4">
                        
                        
                        <button onclick="updateStatus('serving')" id="btnServe" class="col-span-1 py-4 bg-emerald-500 hover:bg-emerald-600 text-white rounded-2xl font-bold shadow-md shadow-emerald-200 active:scale-95 flex items-center justify-center gap-2 transition-all">
                            <i class="fa-solid fa-user-check text-xl"></i> Mulai Layani
                        </button>
                        
                        
                        <div class="col-span-1 grid grid-cols-2 gap-3" id="preServeTools">
                            <button onclick="recall()" id="btnRecall" class="bg-amber-100 hover:bg-amber-200 text-amber-700 border border-amber-200 rounded-2xl font-bold active:scale-95 flex flex-col items-center justify-center transition-all">
                                <i class="fa-solid fa-bullhorn mb-1 text-lg"></i> <span class="text-[10px] uppercase">Panggil Ulang</span>
                            </button>
                            <button onclick="skipQueue()" class="bg-slate-100 hover:bg-slate-200 text-slate-600 border border-slate-200 rounded-2xl font-bold active:scale-95 flex flex-col items-center justify-center transition-all">
                                <i class="fa-solid fa-forward mb-1 text-lg"></i> <span class="text-[10px] uppercase">Lewati</span>
                            </button>
                        </div>

                        
                        <div id="finishTools" class="hidden col-span-2 grid grid-cols-3 gap-4">
                             <button onclick="completeQueue('stop')" class="col-span-1 py-4 bg-slate-700 hover:bg-slate-800 text-white rounded-2xl font-bold shadow-lg active:scale-95 flex flex-col items-center justify-center leading-tight">
                                <span class="text-sm"><i class="fa-solid fa-stop mr-1"></i> SELESAI</span>
                                <span class="text-[10px] opacity-70 font-normal">& Istirahat</span>
                            </button>

                            <button onclick="completeQueue('next')" class="col-span-2 py-4 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white rounded-2xl font-bold text-lg shadow-lg shadow-indigo-200 active:scale-95 flex items-center justify-center gap-2">
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
                    <span id="pendingCount" class="bg-indigo-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm">0</span>
                </div>
                <div id="waitingListContainer" class="p-3 space-y-2 overflow-y-auto flex-grow custom-scrollbar bg-slate-50/30">
                   
                </div>
            </div>

            
            <div class="bg-white rounded-2xl border border-red-100 shadow-sm flex flex-col h-1/2 overflow-hidden relative group">
                <div class="absolute top-0 right-0 w-20 h-20 bg-red-50 rounded-bl-full -mr-10 -mt-10 z-0 transition-transform group-hover:scale-110"></div>
                <div class="bg-red-50/50 px-4 py-3 border-b border-red-100 flex justify-between items-center relative z-10">
                    <h3 class="text-xs font-extrabold text-red-600 uppercase tracking-widest flex items-center gap-2">
                        <i class="fa-solid fa-forward text-red-500"></i> Dilewati
                    </h3>
                    <span id="skippedCount" class="bg-red-100 text-red-600 text-[10px] font-bold px-2 py-0.5 rounded-full border border-red-200">0</span>
                </div>
                <div id="skippedListContainer" class="p-3 space-y-2 overflow-y-auto flex-grow custom-scrollbar bg-red-50/10">
                    
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
    let userRole = null; 

    setInterval(() => { document.getElementById('realtimeClock').innerText = new Date().toLocaleTimeString('id-ID'); }, 1000);

    document.addEventListener('DOMContentLoaded', () => {
        if(!token) window.location.href = '/admin/login';
        checkOperatorState();
        startPollingDashboard();
    });

    
    async function checkOperatorState() {
        try {
            const res = await fetch(`${API_BASE}/operator-state`, { headers: { 'Authorization': `Bearer ${token}` } });
            if (res.status === 401) { location.href = '/admin/login'; return; }
            const data = await res.json();
            
            const localUser = JSON.parse(localStorage.getItem('admin_user') || '{}');
            userRole = localUser.role || 'staff'; 

           
            const roleIcon = document.getElementById('roleIcon');
            const dashTitle = document.getElementById('dashboardTitle');

            if(userRole === 'admin') {
                dashTitle.innerHTML = 'Administrator <span class="font-normal text-slate-500">Monitoring</span>';
                roleIcon.innerHTML = '<i class="fa-solid fa-chart-pie"></i>';
                roleIcon.className = "w-12 h-12 rounded-xl bg-indigo-600 text-white flex items-center justify-center text-xl shadow-lg shadow-indigo-200";
                
                document.getElementById('staffActions').innerHTML = '';
                return; 
            }

            
            if (data.counter) {
                dashTitle.innerHTML = `Loket: <span class="text-indigo-600">${data.counter.name}</span>`;
                roleIcon.innerHTML = '<i class="fa-solid fa-headset"></i>';
                roleIcon.className = "w-12 h-12 rounded-xl bg-white text-indigo-600 border border-indigo-100 flex items-center justify-center text-xl shadow-sm";

                document.getElementById('staffActions').className = 'block';
                document.getElementById('staffActions').innerHTML = `
                 <button onclick="closeCounterPrompt()" class="px-4 py-2 text-xs font-bold text-red-600 bg-red-50 hover:bg-red-100 rounded-lg border border-red-100 transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-power-off"></i> Tutup Loket
                 </button>`;

                if (data.active_queue) restoreSession(data.active_queue);
                else setIdleState();
            } else {
                loadCountersForModal(); 
            }
        } catch (e) { console.error(e); }
    }

   
    function startPollingDashboard() {
        fetchDashboardStats();
        setInterval(fetchDashboardStats, 3000);
    }

    async function fetchDashboardStats() {
        try {
            const res = await fetch(`${API_BASE}/dashboard-stats`, { headers: { 'Authorization': `Bearer ${token}` } });
            const data = await res.json();
            
            if (data.role === 'admin') {
                renderAdminDashboard(data);
            } else {
                renderStaffDashboard(data);
            }
        } catch(e) {}
    }

   
    function renderAdminDashboard(data) {
        document.getElementById('adminPanel').classList.remove('hidden');
        document.getElementById('staffPanel').classList.add('hidden');

        // Stats Cards
        document.getElementById('admStatPending').innerText = data.stats.pending;
        document.getElementById('admStatServing').innerText = data.stats.serving;
        document.getElementById('admStatCompleted').innerText = data.stats.completed;
        document.getElementById('admStatSkipped').innerText = data.stats.skipped;

        
        const monitorGrid = document.getElementById('liveMonitorGrid');
        monitorGrid.innerHTML = '';
        
        data.monitoring.forEach(m => {
            const isServing = m.current_code !== '--';
            const statusClass = isServing 
                ? 'bg-gradient-to-br from-indigo-500 to-indigo-600 text-white shadow-lg shadow-indigo-200' 
                : 'bg-white border border-slate-200';
            const textClass = isServing ? 'text-white' : 'text-slate-800';
            const subTextClass = isServing ? 'text-indigo-100' : 'text-slate-400';
            
            let nextQueueHtml = '';
            if(m.next_queue.length > 0) {
                m.next_queue.forEach(code => {
                    nextQueueHtml += `<span class="inline-block px-2 py-1 rounded bg-white/20 text-xs font-bold mr-1 mb-1 border border-white/10">${code}</span>`;
                });
            } else {
                nextQueueHtml = '<span class="text-[10px] opacity-50 italic">Tidak ada antrian</span>';
            }

            monitorGrid.innerHTML += `
            <div class="${statusClass} rounded-2xl p-6 relative overflow-hidden transition-all duration-500">
                <div class="flex justify-between items-start mb-4 relative z-10">
                    <div>
                        <h4 class="text-xs font-extrabold uppercase tracking-widest ${subTextClass}">${m.service_name}</h4>
                        ${isServing ? `<div class="text-[10px] mt-1 font-bold bg-white/20 inline-block px-2 rounded text-white"><i class="fa-solid fa-location-dot"></i> ${m.current_counter}</div>` : ''}
                    </div>
                    ${isServing ? '<i class="fa-solid fa-bolt text-yellow-300 animate-pulse"></i>' : '<i class="fa-solid fa-moon text-slate-300"></i>'}
                </div>
                <div class="text-center py-2 relative z-10">
                    <div class="text-5xl font-black ${textClass} tracking-tighter">${m.current_code}</div>
                </div>
                <div class="mt-6 pt-4 border-t ${isServing ? 'border-white/20' : 'border-slate-100'} relative z-10">
                    <div class="text-[9px] font-bold uppercase tracking-widest mb-2 opacity-60">Berikutnya</div>
                    <div class="flex flex-wrap">
                        ${isServing 
                            ? nextQueueHtml 
                            : m.next_queue.map(c => `<span class="inline-block px-2 py-1 rounded bg-slate-100 text-slate-600 text-xs font-bold mr-1 mb-1 border border-slate-200">${c}</span>`).join('') || '<span class="text-[10px] text-slate-400 italic">Kosong</span>'
                        }
                    </div>
                </div>
            </div>`;
        });

        
        const counterGrid = document.getElementById('admCountersGrid');
        counterGrid.innerHTML = '';
        data.counters.forEach(c => {
            const isOpen = c.status === 'open';
            counterGrid.innerHTML += `
                <div class="bg-slate-700/50 rounded-lg p-3 border border-slate-600 flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full ${isOpen ? 'bg-emerald-400 shadow-[0_0_10px_#34d399]' : 'bg-red-500'}"></div>
                    <div class="truncate">
                        <div class="text-xs font-bold text-slate-200">${c.name}</div>
                        <div class="text-[10px] text-slate-400 truncate">${isOpen ? c.operator : 'Tutup'}</div>
                    </div>
                </div>`;
        });
    }

    
    function renderStaffDashboard(data) {
        document.getElementById('staffPanel').classList.remove('hidden');
        document.getElementById('adminPanel').classList.add('hidden');

       
        const waitList = document.getElementById('waitingListContainer');
        waitList.innerHTML = '';
        document.getElementById('pendingCount').innerText = data.waiting.length;
        
        if(data.waiting.length === 0) {
            waitList.innerHTML = '<div class="flex flex-col items-center justify-center h-40 text-slate-300 gap-2"><i class="fa-solid fa-mug-hot text-2xl"></i><span class="text-xs italic">Tidak ada antrian</span></div>';
        } else {
            data.waiting.forEach(q => {
                waitList.innerHTML += `
                    <div class="bg-white p-0 rounded-xl border border-slate-200 flex overflow-hidden shadow-sm hover:shadow-md transition-all group">
                        <div class="w-14 bg-slate-50 flex items-center justify-center border-r border-dashed border-slate-300 relative">
                             <span class="font-black text-slate-600 text-sm">${q.code}</span>
                             <div class="absolute -top-1.5 -right-1.5 w-3 h-3 bg-slate-50/30 rounded-full border border-slate-200 z-10"></div>
                             <div class="absolute -bottom-1.5 -right-1.5 w-3 h-3 bg-slate-50/30 rounded-full border border-slate-200 z-10"></div>
                        </div>
                        <div class="p-2 flex-grow flex justify-between items-center">
                            <div>
                                <div class="text-[9px] font-bold text-slate-400 uppercase tracking-wide">Masuk ${q.time}</div>
                                <div class="text-[10px] font-bold text-indigo-600">${q.service_name}</div>
                            </div>
                            <div class="text-[10px] font-medium text-slate-400 bg-slate-100 px-2 py-1 rounded">
                                ${q.waited_for}
                            </div>
                        </div>
                    </div>`;
            });
        }

        
        const skipList = document.getElementById('skippedListContainer');
        skipList.innerHTML = '';
        document.getElementById('skippedCount').innerText = data.skipped.length;

        if(data.skipped.length === 0) {
            skipList.innerHTML = '<div class="text-center text-red-200 text-xs py-10 italic">Kosong</div>';
        } else {
            data.skipped.forEach(q => {
                skipList.innerHTML += `
                    <div class="bg-white p-2 rounded-xl border border-red-100 flex justify-between items-center hover:shadow-sm mb-2">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-red-50 border border-red-100 rounded-lg text-red-500 font-bold flex items-center justify-center text-xs">${q.code}</div>
                            <div>
                                <div class="text-[10px] font-bold text-slate-600">${q.service_name}</div>
                            </div>
                        </div>
                        <button onclick="recallFromSkipped(${q.id})" class="text-amber-600 hover:text-amber-700 bg-amber-50 hover:bg-amber-100 p-2 rounded-lg transition-colors shadow-sm border border-amber-100">
                            <i class="fa-solid fa-undo text-xs"></i>
                        </button>
                    </div>`;
            });
        }
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
            method: 'POST', headers: { 'Authorization': `Bearer ${token}`, 'Content-Type': 'application/json' }, body: JSON.stringify({ status })
        });
        if(status === 'serving') updateUI(null, 'serving');
    }

    async function recall() {
        if(!currentQueueId) return;
        const btn = document.getElementById('btnRecall');
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
        await fetch(`${API_BASE}/queue/${currentQueueId}/recall`, { method: 'POST', headers: { 'Authorization': `Bearer ${token}` } });
        setTimeout(() => btn.innerHTML = '<i class="fa-solid fa-bullhorn mb-1 text-lg"></i><span class="text-[10px] uppercase">Panggil Ulang</span>', 500);
    }

    async function skipQueue() {
        if(!currentQueueId) return;
        await fetch(`${API_BASE}/queue/${currentQueueId}/skip`, { method: 'POST', headers: { 'Authorization': `Bearer ${token}` } });
        setIdleState(); fetchDashboardStats();
    }

    async function recallFromSkipped(id) {
        if (document.getElementById('controlBar').classList.contains('pointer-events-none')) {
            Swal.fire('Info', 'Anda dalam mode monitoring.', 'info');
            return;
        }
        Swal.fire({
            title: 'Panggil Ulang?', text: "Antrian akan menjadi aktif kembali.", icon: 'question', showCancelButton: true, confirmButtonText: 'Ya, Panggil', confirmButtonColor: '#f59e0b'
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
            method: 'POST', headers: { 'Authorization': `Bearer ${token}`, 'Content-Type': 'application/json' }, body: JSON.stringify({ status: 'completed' })
        });
        if (mode === 'next') callNext(); else setIdleState();
    }

    async function closeCounterPrompt() {
        const { value: reason } = await Swal.fire({
            title: 'Tutup Loket?', input: 'textarea', inputLabel: 'Alasan Penutupan', inputPlaceholder: 'Contoh: Istirahat Makan Siang', showCancelButton: true, confirmButtonText: 'Tutup Loket', confirmButtonColor: '#ef4444',
            inputValidator: (value) => { if (!value) return 'Wajib isi alasan!'; }
        });
        if (reason) {
            await fetch(`${API_BASE}/counter/close`, { 
                method: 'POST', headers: { 'Authorization': `Bearer ${token}`, 'Content-Type': 'application/json' }, body: JSON.stringify({ reason }) 
            });
            location.reload();
        }
    }

   
    function updateUI(queueData, status) {
        currentStatus = status;
        const els = {
            header: document.getElementById('statusHeader'), text: document.getElementById('statusText'), num: document.getElementById('currentNumber'), info: document.getElementById('serviceInfo'), idle: document.getElementById('idleControls'), active: document.getElementById('activeControls'), preServe: document.getElementById('preServeTools'), finish: document.getElementById('finishTools'), sName: document.getElementById('staffServiceName')
        };
        
        if(queueData) {
            currentQueueId = queueData.id; els.num.innerText = queueData.queue_code; els.sName.innerText = queueData.service ? queueData.service.name : '-'; els.info.classList.remove('opacity-0', 'translate-y-4');
        }

        els.idle.classList.add('hidden'); els.active.classList.remove('hidden'); els.active.classList.add('grid');

        // State Styling
        if (status === 'called') {
            els.header.className = "bg-amber-500 text-white p-5 text-center flex justify-between items-center px-8 shadow-md relative z-20";
            els.text.innerHTML = '<span class="flex items-center gap-2"><i class="fa-solid fa-bullhorn animate-bounce"></i> MEMANGGIL...</span>';
            els.num.className = "text-[7rem] sm:text-[9rem] leading-none font-black text-amber-500 tracking-tighter transition-all scale-110 relative z-10";
            document.getElementById('btnServe').classList.remove('hidden'); els.preServe.classList.remove('hidden'); els.finish.classList.add('hidden');
        } else if (status === 'serving') {
            els.header.className = "bg-emerald-600 text-white p-5 text-center flex justify-between items-center px-8 shadow-md relative z-20";
            els.text.innerHTML = '<span class="flex items-center gap-2"><i class="fa-solid fa-user-check"></i> SEDANG MELAYANI</span>';
            els.num.className = "text-[7rem] sm:text-[9rem] leading-none font-black text-emerald-600 tracking-tighter transition-all scale-100 relative z-10";
            document.getElementById('btnServe').classList.add('hidden'); els.preServe.classList.add('hidden'); els.finish.classList.remove('hidden'); els.finish.classList.add('grid');
        }
    }

    function setIdleState() {
        currentStatus = 'idle'; currentQueueId = null;
        document.getElementById('statusHeader').className = "bg-slate-800 text-white p-5 text-center flex justify-between items-center px-8 shadow-md relative z-20";
        document.getElementById('statusText').innerText = "MENUNGGU";
        const num = document.getElementById('currentNumber'); num.innerText = "--"; num.className = "text-[7rem] sm:text-[9rem] leading-none font-black text-slate-300 tracking-tighter transition-all scale-100 relative z-10";
        document.getElementById('serviceInfo').classList.add('opacity-0', 'translate-y-4');
        document.getElementById('idleControls').classList.remove('hidden'); document.getElementById('activeControls').classList.add('hidden'); document.getElementById('activeControls').classList.remove('grid');
    }
    
    function setBtnLoading(isLoading) {
        const btn = document.getElementById('btnNext'); 
        if(isLoading) { btn.disabled = true; btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i>'; } 
        else { btn.disabled = false; btn.innerHTML = '<span>PANGGIL BERIKUTNYA</span> <div class="bg-white/20 rounded-full w-8 h-8 flex items-center justify-center group-hover:translate-x-1 transition-transform"><i class="fa-solid fa-arrow-right text-sm"></i></div>'; }
    }
    
    function restoreSession(queue) { updateUI(queue, queue.status); }
    function updateCounterUI(name) { document.getElementById('dashboardTitle').innerHTML = `Loket: <span class="text-indigo-600">${name}</span>`; }
</script>
@endpush