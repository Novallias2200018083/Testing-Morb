@extends('layouts.admin')

@section('title', 'Kelola Staff')
@section('header_title', 'Master Data Staff')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 fade-in-up">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        
        <div>
            <h3 class="text-lg font-bold text-slate-800">Daftar Petugas</h3>
            <p class="text-sm text-slate-500">Kelola akun staff yang bertugas melayani antrian.</p>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
            <div class="relative group w-full md:w-64">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="text" id="searchInput" onkeyup="searchLocal()" 
                    class="pl-10 pr-4 py-2.5 w-full bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all shadow-sm"
                    placeholder="Cari nama / email...">
            </div>

            <button onclick="openModal('add')" 
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow-lg shadow-indigo-200 transition-all active:scale-95 flex items-center justify-center gap-2 whitespace-nowrap">
                <i class="fa-solid fa-plus"></i>
                <span>Tambah Staff</span>
            </button>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50/80 text-slate-700 font-bold border-b border-slate-200 uppercase tracking-wider text-xs">
                    <tr>
                        <th class="p-5 w-16 text-center">#</th>
                        <th class="p-5">Informasi Staff</th>
                        <th class="p-5">Status Akun</th>
                        <th class="p-5 text-right">Kontrol</th>
                    </tr>
                </thead>
                <tbody id="tableBody" class="divide-y divide-slate-100">
                    <tr><td colspan="4" class="p-8 text-center text-slate-400">Memuat data...</td></tr>
                </tbody>
            </table>
        </div>
        
        <div class="bg-slate-50 px-5 py-3 border-t border-slate-200 text-xs text-slate-500 flex justify-between items-center">
            <span id="totalData">Menampilkan 0 data</span>
        </div>
    </div>
</div>

<div id="modalForm" class="fixed inset-0 z-[100] hidden" role="dialog" aria-modal="true">
    
    <div id="modalBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity opacity-0" onclick="closeModal()"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center">
            
            <div id="modalPanel" class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:w-full sm:max-w-lg opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                <div class="bg-white px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-slate-800" id="modalTitle">Tambah Staff Baru</h3>
                    <button onclick="closeModal()" class="text-slate-400 hover:text-red-500 transition-colors">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>

                <form id="staffForm" onsubmit="saveData(event)" class="p-6 space-y-5">
                    <input type="hidden" id="staffId"> <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Nama Lengkap</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400"><i class="fa-regular fa-user"></i></span>
                            <input type="text" id="name" required class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all outline-none font-medium" placeholder="Nama Staff">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Email Login</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400"><i class="fa-regular fa-envelope"></i></span>
                            <input type="email" id="email" required class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all outline-none font-medium" placeholder="email@kantor.com">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">
                            Password <span id="passHint" class="hidden text-[10px] text-slate-400 font-normal normal-case">(Biarkan kosong jika tidak ingin mengubah)</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400"><i class="fa-solid fa-lock"></i></span>
                            <input type="password" id="password" class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all outline-none font-medium" placeholder="••••••••">
                        </div>
                    </div>

                    <div class="pt-4 flex justify-end gap-3">
                        <button type="button" onclick="closeModal()" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-500 hover:bg-slate-100 transition-colors">Batal</button>
                        <button type="submit" id="btnSave" class="px-5 py-2.5 rounded-xl text-sm font-semibold bg-indigo-600 text-white hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-all active:scale-95">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const API_URL = '/api/admin/staff';
    const token = localStorage.getItem('admin_token');
    let allData = []; 

    
    async function loadData() {
        const tbody = document.getElementById('tableBody');
        try {
            const res = await fetch(API_URL, { headers: { 'Authorization': `Bearer ${token}` } });
            allData = await res.json();
            renderTable(allData); 
        } catch (e) {
            console.error(e);
            tbody.innerHTML = `<tr><td colspan="4" class="p-8 text-center text-red-500">Gagal memuat data.</td></tr>`;
        }
    }

    
    function renderTable(data) {
        const tbody = document.getElementById('tableBody');
        const totalEl = document.getElementById('totalData');
        tbody.innerHTML = '';
        totalEl.innerText = `Menampilkan ${data.length} data`;

        if (data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="4" class="p-10 text-center text-slate-400 italic">Tidak ada data ditemukan.</td></tr>`;
            return;
        }

        data.forEach((item, index) => {
           
            const statusBadge = item.is_active 
                ? `<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-100"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif</span>`
                : `<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-500 border border-slate-200"><span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Non-Aktif</span>`;
            
           
            const initials = item.name.substring(0, 2).toUpperCase();

            tbody.innerHTML += `
                <tr class="border-b border-slate-50 hover:bg-slate-50/80 transition-colors group">
                    <td class="p-5 text-center text-slate-400 text-xs font-mono">${index + 1}</td>
                    <td class="p-5">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-sm shadow-sm group-hover:scale-110 transition-transform">
                                ${initials}
                            </div>
                            <div>
                                <div class="font-bold text-slate-700 text-sm group-hover:text-indigo-600 transition-colors">${item.name}</div>
                                <div class="text-xs text-slate-400 font-mono mt-0.5">${item.email}</div>
                            </div>
                        </div>
                    </td>
                    <td class="p-5">
                        <button onclick="toggleStatus(${item.id})" class="hover:opacity-80 transition-opacity" title="Klik untuk ubah status">
                            ${statusBadge}
                        </button>
                    </td>
                    <td class="p-5 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <button onclick="openModal('edit', ${item.id})" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all" title="Edit">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </button>
                            <button onclick="deleteData(${item.id})" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Hapus">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });
    }

   
    function searchLocal() {
        const keyword = document.getElementById('searchInput').value.toLowerCase();
        const filtered = allData.filter(item => 
            item.name.toLowerCase().includes(keyword) || 
            item.email.toLowerCase().includes(keyword)
        );
        renderTable(filtered);
    }

    
    async function saveData(e) {
        e.preventDefault();
        
        const id = document.getElementById('staffId').value;
        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const btn = document.getElementById('btnSave');

        
        const isEdit = !!id;
        const url = isEdit ? `${API_URL}/${id}` : API_URL;
        const method = isEdit ? 'PUT' : 'POST';

        
        if (!isEdit && !password) {
            Swal.fire('Error', 'Password wajib diisi untuk staff baru', 'error');
            return;
        }

     
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Proses...';

        try {
            const res = await fetch(url, {
                method: method,
                headers: { 'Authorization': `Bearer ${token}`, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ name, email, password })
            });

            if(res.ok) {
                Swal.fire({
                    icon: 'success', 
                    title: 'Berhasil', 
                    text: isEdit ? 'Data diperbarui' : 'Staff baru ditambahkan',
                    timer: 1500, showConfirmButton: false
                });
                closeModal();
                loadData();
            } else {
                const err = await res.json();
                Swal.fire({ icon: 'error', title: 'Gagal', text: err.message || 'Periksa inputan (Email mungkin duplikat)' });
            }
        } catch(e) { console.error(e); }
        
        btn.disabled = false;
        btn.innerText = 'Simpan Data';
    }

    
    function deleteData(id) {
        Swal.fire({
            title: 'Hapus Staff?',
            text: "Data tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then(async (result) => {
            if (result.isConfirmed) {
                await fetch(`${API_URL}/${id}`, { method: 'DELETE', headers: { 'Authorization': `Bearer ${token}` } });
                Swal.fire('Terhapus!', 'Data telah dihapus.', 'success');
                loadData();
            }
        });
    }

    
    async function toggleStatus(id) {
        await fetch(`${API_URL}/${id}/toggle`, { method: 'POST', headers: { 'Authorization': `Bearer ${token}` } });
        loadData();
        const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2000 });
        Toast.fire({ icon: 'success', title: 'Status diperbarui' });
    }

    
    const modal = document.getElementById('modalForm');
    const modalBackdropEl = document.getElementById('modalBackdrop'); 
    const panel = document.getElementById('modalPanel');

    function openModal(mode, id = null) {
        document.getElementById('staffForm').reset();
        const title = document.getElementById('modalTitle');
        const passHint = document.getElementById('passHint');
        const hiddenId = document.getElementById('staffId');

        if (mode === 'edit') {
            const data = allData.find(item => item.id === id);
            hiddenId.value = id;
            document.getElementById('name').value = data.name;
            document.getElementById('email').value = data.email;
            
            title.innerText = 'Edit Data Staff';
            passHint.classList.remove('hidden'); 
        } else {
            hiddenId.value = '';
            title.innerText = 'Tambah Staff Baru';
            passHint.classList.add('hidden');
        }

       
        modal.classList.remove('hidden');
        setTimeout(() => {
            modalBackdropEl.classList.remove('opacity-0');
            panel.classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
            panel.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
        }, 10);
    }

    function closeModal() {
        modalBackdropEl.classList.add('opacity-0');
        panel.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
        panel.classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }

    loadData();
</script>
@endpush