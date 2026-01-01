@extends('layouts.admin')

@section('title', 'Daftar Antrian')
@section('header_title', 'Data Antrian')

@section('content')
<div class="fade-in-up">
    
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 mb-6">
        <form method="GET" action="{{ url('/admin/queues') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Tanggal</label>
                <input type="date" name="date" value="{{ request('date', date('Y-m-d')) }}" 
                       class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Status</label>
                <select name="status" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                    <option value="called" {{ request('status') == 'called' ? 'selected' : '' }}>Dipanggil</option>
                    <option value="serving" {{ request('status') == 'serving' ? 'selected' : '' }}>Dilayani</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="skipped" {{ request('status') == 'skipped' ? 'selected' : '' }}>Dilewati</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Cari Nomor</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Contoh: CS-001" 
                           class="w-full pl-10 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    <i class="fa-solid fa-search absolute left-3 top-3 text-slate-400"></i>
                </div>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white py-2.5 rounded-xl text-sm font-bold transition-colors">
                    <i class="fa-solid fa-filter mr-1"></i> Filter
                </button>
                <a href="{{ url('/admin/queues') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-sm font-bold transition-colors" title="Reset">
                    <i class="fa-solid fa-rotate-right"></i>
                </a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-xs uppercase tracking-wider text-slate-500">
                        <th class="px-6 py-4 font-extrabold">Nomor Tiket</th>
                        <th class="px-6 py-4 font-extrabold">Layanan</th>
                        <th class="px-6 py-4 font-extrabold">Waktu & Tanggal</th>
                        <th class="px-6 py-4 font-extrabold text-center">Loket & Petugas</th>
                        <th class="px-6 py-4 font-extrabold text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-600">
                    @forelse($queues as $q)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-black text-slate-800 text-lg">{{ $q->queue_code }}</div>
                        </td>
                        
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-2 px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600 text-xs font-bold border border-slate-200">
                                {{ $q->service->name }}
                            </span>
                        </td>

                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-700">{{ $q->created_at->format('H:i') }} WIB</div>
                            <div class="text-xs text-slate-400">{{ $q->created_at->format('d M Y') }}</div>
                        </td>

                        <td class="px-6 py-4 text-center">
                            @if($q->counter)
                                <div class="font-bold text-indigo-600">{{ $q->counter->name }}</div>
                                <div class="text-xs text-slate-400">{{ $q->user->name ?? '-' }}</div>
                            @else
                                <span class="text-slate-300 italic">- Belum Ada -</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-center">
                            @php
                                $statusClass = match($q->status) {
                                    'pending' => 'bg-slate-100 text-slate-500 border-slate-200',
                                    'called' => 'bg-amber-100 text-amber-600 border-amber-200 animate-pulse',
                                    'serving' => 'bg-blue-100 text-blue-600 border-blue-200',
                                    'completed' => 'bg-emerald-100 text-emerald-600 border-emerald-200',
                                    'skipped' => 'bg-red-50 text-red-500 border-red-100',
                                    default => 'bg-slate-100 text-slate-500'
                                };
                                $statusLabel = match($q->status) {
                                    'pending' => 'Menunggu',
                                    'called' => 'Dipanggil',
                                    'serving' => 'Dilayani',
                                    'completed' => 'Selesai',
                                    'skipped' => 'Dilewati',
                                    default => $q->status
                                };
                            @endphp
                            <span class="inline-block px-3 py-1 rounded-full text-[10px] font-bold uppercase border {{ $statusClass }}">
                                {{ $statusLabel }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                            <i class="fa-regular fa-folder-open text-3xl mb-3 opacity-50"></i>
                            <p class="text-sm">Tidak ada data antrian ditemukan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($queues->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
            {{ $queues->links() }} 
        </div>
        @endif
    </div>
</div>
@endsection