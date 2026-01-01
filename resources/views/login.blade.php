<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Petugas - Sistem Antrian</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen">

    <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-xl border border-slate-100">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-indigo-600 text-white mb-4 shadow-lg shadow-indigo-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-slate-800">Login Petugas</h2>
            <p class="text-slate-500 text-sm mt-1">Masuk untuk mengelola antrian</p>
        </div>

        <form id="loginForm" onsubmit="handleLogin(event)" class="space-y-5">
            
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                <input type="email" id="email" required 
                    class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none"
                    placeholder="nama@kantor.com">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                <input type="password" id="password" required 
                    class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none"
                    placeholder="••••••••">
            </div>

            <button type="submit" id="btnSubmit"
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 rounded-lg transition-all transform active:scale-95 shadow-lg shadow-indigo-200">
                Masuk Dashboard
            </button>

        </form>

        <div class="mt-6 text-center text-xs text-slate-400">
            &copy; 2025 Sistem Antrian Terpadu
        </div>
    </div>

    <script>
        // 1. CEK SESI: Jika sudah login, lempar ke dashboard
        if (localStorage.getItem('admin_token')) {
            window.location.href = '/admin/dashboard';
        }

        // 2. FUNGSI LOGIN
        async function handleLogin(e) {
            e.preventDefault(); // Cegah reload halaman

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const btn = document.getElementById('btnSubmit');

            // Ubah tombol jadi loading
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
            btn.disabled = true;
            btn.classList.add('opacity-75', 'cursor-not-allowed');

            try {
                // Tembak API Login yang sudah kita tes di Postman
                const response = await fetch('/api/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ email, password })
                });

                const result = await response.json();

                if (!response.ok) {
                    throw new Error(result.message || 'Login gagal');
                }

                // SUKSES LOGIN
                // Simpan Token & User Data di LocalStorage browser
                // Agar nanti Dashboard bisa memakainya
                console.log("Respon Login:", result); 

                // Logika cerdas: Ambil token baik dia ada di dalam .data atau langsung di luar
                const token = result.data?.access_token || result.access_token || result.token;
                const user = result.data?.user || result.user;

                if (!token) {
                    throw new Error("Token tidak ditemukan dalam respon server");
                }

                localStorage.setItem('admin_token', token);
                localStorage.setItem('admin_user', JSON.stringify(user));

                // Notifikasi Sukses
                const Toast = Swal.mixin({
                    toast: true, position: 'top-end', showConfirmButton: false, timer: 1500,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });
                
                Toast.fire({ icon: 'success', title: 'Login Berhasil' });

                // Redirect ke Dashboard setelah 1.5 detik
                setTimeout(() => {
                    window.location.href = '/admin/dashboard';
                }, 1500);

            } catch (error) {
                // GAGAL LOGIN
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Masuk',
                    text: error.message,
                    confirmButtonColor: '#4f46e5'
                });
                
                // Reset tombol
                btn.innerHTML = 'Masuk Dashboard';
                btn.disabled = false;
                btn.classList.remove('opacity-75', 'cursor-not-allowed');
            }
        }
    </script>
</body>
</html>