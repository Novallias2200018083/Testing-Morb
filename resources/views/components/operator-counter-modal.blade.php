<div id="counterModal" class="fixed inset-0 z-[100] bg-slate-900/90 backdrop-blur-sm flex items-center justify-center hidden transition-opacity opacity-0">
    <div class="bg-white rounded-3xl shadow-2xl p-8 max-w-md w-full mx-4 text-center transform scale-95 transition-transform duration-300" id="modalContent">
        
        <div class="w-20 h-20 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center mx-auto mb-6 text-4xl shadow-inner border border-indigo-100">
            <i class="fa-solid fa-store"></i>
        </div>
        
        <h2 class="text-2xl font-bold text-slate-800 mb-2 tracking-tight">Pilih Loket Tugas</h2>
        <p class="text-slate-500 mb-8 text-sm px-4 leading-relaxed">
            Silakan pilih loket tempat Anda bertugas hari ini untuk mulai memanggil antrian.
        </p>
        
        <div id="counterList" class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-h-60 overflow-y-auto p-1 custom-scrollbar">
            <div class="col-span-2 text-center text-slate-400 py-6 italic flex flex-col items-center gap-2">
                <i class="fa-solid fa-circle-notch fa-spin text-2xl"></i>
                <span>Memuat data loket...</span>
            </div>
        </div>
        
        <div class="mt-8 pt-6 border-t border-slate-100">
            <button onclick="confirmLogout()" class="text-slate-400 text-sm hover:text-red-500 font-medium transition-colors flex items-center justify-center gap-2 w-full group">
                <i class="fa-solid fa-power-off group-hover:scale-110 transition-transform"></i> 
                Batal & Logout
            </button>
        </div>
    </div>
</div>