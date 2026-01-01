@extends('layouts.admin')

@section('title', 'Kelola Loket')
@section('header_title', 'Master Data Loket')

@section('content')
<div class="max-w-6xl mx-auto space-y-6 fade-in-up">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold text-slate-800 tracking-tight">Manajemen Loket & Layanan</h3>
            <p class="text-sm text-slate-500 mt-1">Atur loket, petugas jaga, dan jenis layanan yang diterima.</p>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
            <div class="relative group w-full md:w-64">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 group-focus-within:text-indigo-500 transition-colors"><i class="fa-solid fa-magnifying-glass"></i></span>
                <input type="text" id="searchInput" onkeyup="searchLocal()" class="pl-10 pr-4 py-2.5 w-full bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-indigo-500 transition-all shadow-sm" placeholder="Cari loket...">
            </div>
            <button onclick="openModal('add')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow-lg shadow-indigo-200 transition-all active:scale-95 flex items-center justify-center gap-2 whitespace-nowrap">
                <i class="fa-solid fa-plus"></i> <span>Tambah Loket</span>
            </button>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden fade-in-up" style="animation-delay: 0.1s;">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50/80 text-slate-700 font-bold border-b border-slate-200 uppercase tracking-wider text-xs">
                    <tr>
                        <th class="p-5 w-16 text-center">#</th>
                        <th class="p-5">Nama Loket</th>
                        <th class="p-5 w-1/3">Layanan (Service)</th>
                        <th class="p-5">Status & Petugas</th>
                        <th class="p-5 text-right">Kontrol</th>
                    </tr>
                </thead>
                <tbody id="tableBody" class="divide-y divide-slate-100">
                    <tr><td colspan="5" class="p-10 text-center text-slate-400">Memuat data...</td></tr>
                </tbody>
            </table>
        </div>
        <div class="bg-slate-50 px-5 py-3 border-t border-slate-200 text-xs text-slate-500 flex justify-between items-center">
            <span id="totalData">Menampilkan 0 data</span>
        </div>
    </div>
</div>

<div id="modalForm" class="fixed inset-0 z-[100] hidden" role="dialog" aria-modal="true">
    <div id="counterBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity opacity-0" onclick="closeModal()"></div>
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center">
            <div id="modalPanel" class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:w-full sm:max-w-lg opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                <div class="bg-white px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-slate-800" id="modalTitle">Tambah Loket</h3>
                    <button onclick="closeModal()" class="text-slate-400 hover:text-red-500 transition-colors"><i class="fa-solid fa-xmark text-xl"></i></button>
                </div>

                <form id="counterForm" onsubmit="saveData(event)" class="p-6 space-y-5">
                    <input type="hidden" id="counterId">

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Nama Loket</label>
                        <input type="text" id="name" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:border-indigo-500 transition-all outline-none font-medium" placeholder="Contoh: Loket 1">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Layanan yang diterima</label>
                        <div id="servicesContainer" class="grid grid-cols-2 gap-3 max-h-32 overflow-y-auto p-1">
                            <div class="text-sm text-slate-400 italic">Memuat layanan...</div>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1">*Bisa pilih lebih dari satu</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 border-t border-slate-100 pt-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Status</label>
                            <select id="status" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:border-indigo-500 bg-white">
                                <option value="open">BUKA (Open)</option>
                                <option value="closed">TUTUP (Closed)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Petugas</label>
                            <select id="active_user_id" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:border-indigo-500 bg-white">
                                <option value="">-- Kosongkan --</option>
                            </select>
                        </div>
                    </div>

                    <div class="pt-2 flex justify-end gap-3">
                        <button type="button" onclick="closeModal()" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-500 hover:bg-slate-100">Batal</button>
                        <button type="submit" id="btnSave" class="px-5 py-2.5 rounded-xl text-sm font-semibold bg-indigo-600 text-white hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-all active:scale-95">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const API_URL = '/api/admin/counters';
    const STAFF_URL = '/api/admin/staff';
    const SERVICES_URL = '/api/admin/services'; 
    const token = localStorage.getItem('admin_token');
    
    let allData = [];
    let staffList = [];
    let serviceList = [];

   
    async function loadData() {
        try {
            const [resCounter, resStaff, resServices] = await Promise.all([
                fetch(API_URL, { headers: { 'Authorization': `Bearer ${token}` } }),
                fetch(STAFF_URL, { headers: { 'Authorization': `Bearer ${token}` } }),
                fetch(SERVICES_URL, { headers: { 'Authorization': `Bearer ${token}` } })
            ]);

            allData = await resCounter.json();
            staffList = await resStaff.json();
            serviceList = await resServices.json();
            
            renderTable(allData);
            populateFormOptions(); 

        } catch (e) { console.error(e); }
    }

    
    function populateFormOptions() {
        
        const selectStaff = document.getElementById('active_user_id');
        selectStaff.innerHTML = '<option value="">-- Kosongkan --</option>';
        staffList.forEach(staff => {
            if(staff.is_active) selectStaff.innerHTML += `<option value="${staff.id}">${staff.name}</option>`;
        });

        
        const container = document.getElementById('servicesContainer');
        container.innerHTML = '';
        serviceList.forEach(srv => {
            container.innerHTML += `
                <label class="flex items-center gap-3 p-2 border border-slate-200 rounded-lg cursor-pointer hover:bg-slate-50 transition-colors">
                    <input type="checkbox" name="services" value="${srv.id}" class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                    <div class="text-xs font-semibold text-slate-700">
                        ${srv.name} <span class="text-slate-400 font-normal">(${srv.code})</span>
                    </div>
                </label>
            `;
        });
    }

    function renderTable(data) {
        const tbody = document.getElementById('tableBody');
        const totalEl = document.getElementById('totalData');
        tbody.innerHTML = '';
        totalEl.innerText = `Menampilkan ${data.length} data`;

        if (data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="5" class="p-10 text-center text-slate-400 italic">Belum ada data loket.</td></tr>`;
            return;
        }

        data.forEach((item, index) => {
           
            let statusHtml = item.status === 'open' 
                ? `<span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-700 border border-emerald-200"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> BUKA</span>`
                : `<span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-500 border border-slate-200">TUTUP</span>`;
            
            
            let officerHtml = item.active_user 
                ? `<div class="flex items-center gap-2 mt-1"><div class="w-5 h-5 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-[9px] font-bold">${item.active_user.name.charAt(0)}</div><span class="text-xs font-semibold text-slate-700">${item.active_user.name}</span></div>`
                : `<span class="text-[10px] text-slate-400 italic block mt-1">Tanpa Petugas</span>`;

           
            let servicesHtml = '';
            if(item.services && item.services.length > 0) {
                item.services.forEach(srv => {
                    servicesHtml += `<span class="inline-block bg-white border border-slate-200 text-slate-600 text-[10px] px-2 py-1 rounded mr-1 mb-1 shadow-sm font-medium">${srv.name}</span>`;
                });
            } else {
                servicesHtml = `<span class="text-[10px] text-red-400 italic">Belum disetting</span>`;
            }

            tbody.innerHTML += `
                <tr class="border-b border-slate-50 hover:bg-slate-50/80 transition-colors">
                    <td class="p-5 text-center text-slate-400 text-xs font-mono">${index + 1}</td>
                    <td class="p-5">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center font-bold shadow-sm"><i class="fa-solid fa-store text-xs"></i></div>
                            <span class="font-bold text-slate-700 text-sm">${item.name}</span>
                        </div>
                    </td>
                    <td class="p-5">${servicesHtml}</td>
                    <td class="p-5">
                        <div class="flex flex-col items-start gap-0.5">${statusHtml}${officerHtml}</div>
                    </td>
                    <td class="p-5 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <button onclick="openModal('edit', ${item.id})" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all" title="Konfigurasi"><i class="fa-solid fa-sliders"></i></button>
                            <button onclick="deleteData(${item.id})" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Hapus"><i class="fa-regular fa-trash-can"></i></button>
                        </div>
                    </td>
                </tr>
            `;
        });
    }

    
    function searchLocal() {
        const keyword = document.getElementById('searchInput').value.toLowerCase();
        const filtered = allData.filter(item => item.name.toLowerCase().includes(keyword));
        renderTable(filtered);
    }

    
    async function saveData(e) {
        e.preventDefault();
        const id = document.getElementById('counterId').value;
        const name = document.getElementById('name').value;
        const status = document.getElementById('status').value;
        const active_user_id = document.getElementById('active_user_id').value || null;
        
        
        const checkedServices = Array.from(document.querySelectorAll('input[name="services"]:checked')).map(cb => cb.value);

        const btn = document.getElementById('btnSave');
        const url = id ? `${API_URL}/${id}` : API_URL;
        const method = id ? 'PUT' : 'POST';

        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Proses...';

        try {
            const res = await fetch(url, {
                method: method,
                headers: { 'Authorization': `Bearer ${token}`, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ name, status, active_user_id, services: checkedServices })
            });

            if(res.ok) {
                Swal.fire({ icon: 'success', title: 'Berhasil', text: 'Konfigurasi tersimpan', timer: 1500, showConfirmButton: false });
                closeModal();
                loadData();
            } else {
                Swal.fire({ icon: 'error', title: 'Gagal', text: 'Terjadi kesalahan input' });
            }
        } catch(e) { console.error(e); }

        btn.disabled = false;
        btn.innerText = 'Simpan';
    }

    
    function deleteData(id) {
        Swal.fire({ title: 'Hapus Loket?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', confirmButtonText: 'Ya, Hapus' })
            .then(async (r) => { if(r.isConfirmed) { await fetch(`${API_URL}/${id}`, { method: 'DELETE', headers: {'Authorization': `Bearer ${token}`} }); loadData(); } });
    }

    const counterModal = document.getElementById('modalForm');
    const counterBackdrop = document.getElementById('counterBackdrop');
    const counterPanel = document.getElementById('modalPanel');

    function openModal(mode, id = null) {
        document.getElementById('counterForm').reset();
        
        
        document.querySelectorAll('input[name="services"]').forEach(cb => cb.checked = false);

        if (mode === 'edit') {
            const data = allData.find(item => item.id === id);
            document.getElementById('counterId').value = id;
            document.getElementById('name').value = data.name;
            document.getElementById('status').value = data.status;
            document.getElementById('active_user_id').value = data.active_user_id || "";
            document.getElementById('modalTitle').innerText = 'Konfigurasi Loket';

            
            if(data.services) {
                data.services.forEach(srv => {
                    const cb = document.querySelector(`input[name="services"][value="${srv.id}"]`);
                    if(cb) cb.checked = true;
                });
            }
        } else {
            document.getElementById('counterId').value = '';
            document.getElementById('status').value = 'closed';
            document.getElementById('modalTitle').innerText = 'Tambah Loket';
        }

        counterModal.classList.remove('hidden');
        setTimeout(() => {
            counterBackdrop.classList.remove('opacity-0');
            counterPanel.classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
            counterPanel.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
        }, 10);
    }

    function closeModal() {
        counterBackdrop.classList.add('opacity-0');
        counterPanel.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
        counterPanel.classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
        setTimeout(() => counterModal.classList.add('hidden'), 300);
    }

    loadData();
</script>
@endpush