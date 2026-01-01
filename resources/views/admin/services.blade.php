@extends('layouts.admin')

@section('title', 'Kelola Layanan')
@section('header_title', 'Master Data Layanan')

@section('content')
<div class="max-w-6xl mx-auto space-y-6 fade-in-up">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold text-slate-800 tracking-tight">Daftar Layanan (Poli)</h3>
            <p class="text-sm text-slate-500 mt-1">Atur jenis antrian dan kode prefix tiket (Contoh: CS, GI, UM).</p>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
            <div class="relative group w-full md:w-64">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="text" id="searchInput" onkeyup="searchLocal()" 
                    class="pl-10 pr-4 py-2.5 w-full bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all shadow-sm"
                    placeholder="Cari layanan / kode...">
            </div>

            <button onclick="openModal('add')" 
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow-lg shadow-indigo-200 transition-all active:scale-95 flex items-center justify-center gap-2 whitespace-nowrap">
                <i class="fa-solid fa-plus"></i>
                <span>Tambah Layanan</span>
            </button>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden fade-in-up" style="animation-delay: 0.1s;">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50/80 text-slate-700 font-bold border-b border-slate-200 uppercase tracking-wider text-xs">
                    <tr>
                        <th class="p-5 w-20 text-center">Kode</th>
                        <th class="p-5">Nama Layanan</th>
                        <th class="p-5">Deskripsi</th>
                        <th class="p-5 text-right">Kontrol</th>
                    </tr>
                </thead>
                <tbody id="tableBody" class="divide-y divide-slate-100">
                    <tr><td colspan="4" class="p-10 text-center text-slate-400">Memuat data...</td></tr>
                </tbody>
            </table>
        </div>
        
        <div class="bg-slate-50 px-5 py-3 border-t border-slate-200 text-xs text-slate-500 flex justify-between items-center">
            <span id="totalData">Menampilkan 0 data</span>
        </div>
    </div>
</div>

<div id="modalForm" class="fixed inset-0 z-[100] hidden" role="dialog" aria-modal="true">
    <div id="serviceBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity opacity-0" onclick="closeModal()"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center">
            <div id="modalPanel" class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:w-full sm:max-w-lg opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                <div class="bg-white px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-slate-800" id="modalTitle">Tambah Layanan</h3>
                    <button onclick="closeModal()" class="text-slate-400 hover:text-red-500 transition-colors">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>

                <form id="serviceForm" onsubmit="saveData(event)" class="p-6 space-y-5">
                    <input type="hidden" id="serviceId">

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                        <div class="sm:col-span-1">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Kode (Prefix)</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400"><i class="fa-solid fa-tag"></i></span>
                                <input type="text" id="code" required maxlength="5" 
                                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 focus:border-indigo-500 transition-all outline-none font-bold uppercase text-indigo-700" 
                                    placeholder="CS">
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Nama Layanan</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400"><i class="fa-solid fa-signature"></i></span>
                                <input type="text" id="name" required 
                                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 focus:border-indigo-500 transition-all outline-none font-medium" 
                                    placeholder="Contoh: Poli Umum">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Deskripsi Singkat</label>
                        <div class="relative">
                            <textarea id="description" rows="3" 
                                class="w-full p-3 rounded-xl border border-slate-300 focus:border-indigo-500 transition-all outline-none text-sm" 
                                placeholder="Keterangan layanan ini (opsional)..."></textarea>
                        </div>
                    </div>

                    <div class="pt-2 flex justify-end gap-3">
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
    const API_URL = '/api/admin/services';
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
            tbody.innerHTML = `<tr><td colspan="4" class="p-10 text-center text-slate-400 italic">Belum ada layanan terdaftar.</td></tr>`;
            return;
        }

        data.forEach((item, index) => {
            tbody.innerHTML += `
                <tr class="border-b border-slate-50 hover:bg-slate-50/80 transition-colors group">
                    <td class="p-5 text-center">
                        <span class="inline-block px-3 py-1 bg-indigo-100 text-indigo-700 rounded-lg text-xs font-black font-mono tracking-wider shadow-sm border border-indigo-200">
                            ${item.code}
                        </span>
                    </td>
                    <td class="p-5 font-bold text-slate-700 group-hover:text-indigo-600 transition-colors">
                        ${item.name}
                    </td>
                    <td class="p-5 text-sm text-slate-500 truncate max-w-xs">
                        ${item.description || '<span class="italic text-slate-300">Tidak ada deskripsi</span>'}
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
            item.code.toLowerCase().includes(keyword)
        );
        renderTable(filtered);
    }

    
    async function saveData(e) {
        e.preventDefault();
        const id = document.getElementById('serviceId').value;
        const name = document.getElementById('name').value;
        const code = document.getElementById('code').value;
        const description = document.getElementById('description').value;
        const btn = document.getElementById('btnSave');

        const isEdit = !!id;
        const url = isEdit ? `${API_URL}/${id}` : API_URL;
        const method = isEdit ? 'PUT' : 'POST';

        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Proses...';

        try {
            const res = await fetch(url, {
                method: method,
                headers: { 'Authorization': `Bearer ${token}`, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ name, code, description })
            });

            if(res.ok) {
                Swal.fire({
                    icon: 'success', 
                    title: 'Berhasil', 
                    text: isEdit ? 'Layanan diperbarui' : 'Layanan ditambahkan',
                    timer: 1500, showConfirmButton: false
                });
                closeModal();
                loadData();
            } else {
                const err = await res.json();
                Swal.fire({ icon: 'error', title: 'Gagal', text: err.message || 'Kode layanan mungkin sudah ada' });
            }
        } catch(e) { console.error(e); }

        btn.disabled = false;
        btn.innerText = 'Simpan Data';
    }

    
    function deleteData(id) {
        Swal.fire({
            title: 'Hapus Layanan?',
            text: "Data tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Ya, Hapus'
        }).then(async (result) => {
            if (result.isConfirmed) {
                await fetch(`${API_URL}/${id}`, { method: 'DELETE', headers: { 'Authorization': `Bearer ${token}` } });
                loadData();
                Swal.fire('Terhapus!', '', 'success');
            }
        });
    }

    
    const serviceModal = document.getElementById('modalForm');
    const serviceBackdrop = document.getElementById('serviceBackdrop');
    const servicePanel = document.getElementById('modalPanel');

    function openModal(mode, id = null) {
        document.getElementById('serviceForm').reset();
        const title = document.getElementById('modalTitle');
        const hiddenId = document.getElementById('serviceId');

        if (mode === 'edit') {
            const data = allData.find(item => item.id === id);
            hiddenId.value = id;
            document.getElementById('name').value = data.name;
            document.getElementById('code').value = data.code;
            document.getElementById('description').value = data.description || '';
            title.innerText = 'Edit Layanan';
        } else {
            hiddenId.value = '';
            title.innerText = 'Tambah Layanan Baru';
        }

        serviceModal.classList.remove('hidden');
        setTimeout(() => {
            serviceBackdrop.classList.remove('opacity-0');
            servicePanel.classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
            servicePanel.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
        }, 10);
    }

    function closeModal() {
        serviceBackdrop.classList.add('opacity-0');
        servicePanel.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
        servicePanel.classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
        setTimeout(() => serviceModal.classList.add('hidden'), 300);
    }

    loadData();
</script>
@endpush