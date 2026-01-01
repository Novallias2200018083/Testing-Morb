<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - Sistem Antrian</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f1f5f9; 
        }
        
       
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

       
        .sidebar-transition { transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        
        
        .nav-active {
            background-color: #eef2ff; 
            color: #4f46e5;
            font-weight: 700;
            border-right: 3px solid #4f46e5;
        }
        .nav-item {
            color: #64748b;
            font-weight: 500;
        }
        .nav-item:hover:not(.nav-active) {
            background-color: #f8fafc; 
            color: #1e293b; 
        }

      
        .hidden-menu { display: none !important; }
    </style>
</head>
<body class="text-slate-800 antialiased overflow-hidden">

    <div class="flex h-screen w-full">

        <div id="mobileBackdrop" onclick="toggleSidebar()" 
             class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40 hidden transition-opacity opacity-0 lg:hidden">
        </div>

        <aside id="sidebar" 
               class="fixed lg:static inset-y-0 left-0 z-50 w-72 bg-white border-r border-slate-200 transform -translate-x-full lg:translate-x-0 sidebar-transition flex flex-col h-full shadow-2xl lg:shadow-none">
            
            <div class="h-20 flex items-center px-8 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="bg-indigo-600 text-white w-10 h-10 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-200">
                        <i class="fa-solid fa-layer-group text-lg"></i>
                    </div>
                    <div>
                        <h1 class="font-bold text-xl tracking-tight text-slate-800 leading-none">Queue<span class="text-indigo-600">Pro</span></h1>
                        <p class="text-[10px] text-slate-400 font-bold tracking-widest uppercase mt-1">Sistem Antrian</p>
                    </div>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto py-6 space-y-1">
                
                <div class="px-6 mb-3 text-[10px] font-extrabold uppercase tracking-widest text-slate-400">
                    Operasional
                </div>

                <a href="/admin/dashboard" 
                   class="flex items-center gap-3 px-6 py-3.5 text-sm transition-all {{ request()->is('admin/dashboard') ? 'nav-active' : 'nav-item' }}">
                    <i class="fa-solid fa-chart-pie w-5 text-center"></i>
                    <span>Dashboard</span>
                </a>

                {{-- <a href="/admin/queues" 
                   class="flex items-center gap-3 px-6 py-3.5 text-sm transition-all {{ request()->is('admin/queues*') ? 'nav-active' : 'nav-item' }}">
                    <i class="fa-solid fa-list-check w-5 text-center"></i>
                    <span>Daftar Antrian</span>
                </a> --}}

                <div id="menu-admin" class="hidden-menu"> <div class="my-6 border-t border-slate-100 mx-6"></div>

                    <div class="px-6 mb-3 text-[10px] font-extrabold uppercase tracking-widest text-slate-400">
                        Master Data
                    </div>

                    <a href="/admin/services" 
                       class="flex items-center gap-3 px-6 py-3.5 text-sm transition-all {{ request()->is('admin/services*') ? 'nav-active' : 'nav-item' }}">
                        <i class="fa-solid fa-bell-concierge w-5 text-center"></i>
                        <span>Layanan (Poli)</span>
                    </a>

                    <a href="/admin/counters" 
                       class="flex items-center gap-3 px-6 py-3.5 text-sm transition-all {{ request()->is('admin/counters*') ? 'nav-active' : 'nav-item' }}">
                        <i class="fa-solid fa-store w-5 text-center"></i>
                        <span>Loket Fisik</span>
                    </a>

                    <a href="/admin/staff" 
                       class="flex items-center gap-3 px-6 py-3.5 text-sm transition-all {{ request()->is('admin/staff*') ? 'nav-active' : 'nav-item' }}">
                        <i class="fa-solid fa-users-gear w-5 text-center"></i>
                        <span>Staff & User</span>
                    </a>
                </div>

            </div>

            <div class="p-4 border-t border-slate-100 bg-slate-50">
                <div class="flex items-center gap-3 p-3 rounded-xl bg-white border border-slate-200 shadow-sm">
                    <div class="h-10 w-10 rounded-full bg-slate-800 text-white flex items-center justify-center font-bold text-lg" id="userAvatar">
                        A
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-slate-800 truncate" id="layoutUserName">Loading...</p>
                        <p class="text-[10px] font-bold text-indigo-500 uppercase tracking-wide" id="layoutUserRole">Staff</p>
                    </div>
                    <button onclick="confirmLogout()" class="text-slate-400 hover:text-red-500 transition-colors" title="Keluar">
                        <i class="fa-solid fa-power-off text-lg"></i>
                    </button>
                </div>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-slate-50/50">
            
            <header class="h-20 flex items-center justify-between px-6 lg:px-10 z-30 sticky top-0 bg-white/80 backdrop-blur-md border-b border-slate-200">
                <div class="flex items-center gap-4">
                    <button onclick="toggleSidebar()" class="lg:hidden p-2 text-slate-500 hover:bg-slate-100 rounded-lg transition-colors">
                        <i class="fa-solid fa-bars-staggered text-xl"></i>
                    </button>
                    <div>
                        <h2 class="text-lg font-bold text-slate-800 tracking-tight">
                            @yield('header_title', 'Dashboard')
                        </h2>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <a href="/kiosk" target="_blank" class="hidden md:flex items-center gap-2 px-4 py-2 text-xs font-bold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 border border-indigo-100 rounded-lg transition-all">
                        <i class="fa-solid fa-tablet-screen-button"></i> Kiosk
                    </a>
                    <a href="/monitor" target="_blank" class="hidden md:flex items-center gap-2 px-4 py-2 text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-100 rounded-lg transition-all">
                        <i class="fa-solid fa-tv"></i> Monitor
                    </a>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto p-6 lg:p-10 scroll-smooth">
                @yield('content')
                
                <div class="mt-10 pt-6 border-t border-slate-200 text-center text-xs text-slate-400 font-medium">
                    &copy; {{ date('Y') }} Sistem Antrian Terpadu. All rights reserved.
                </div>
            </main>
        </div>
    </div>

    <script>
        
        document.addEventListener("DOMContentLoaded", function() {
            const adminToken = localStorage.getItem('admin_token');
            const adminUserString = localStorage.getItem('admin_user');

            if (!adminToken || !adminUserString) {
                window.location.href = '/admin/login';
                return;
            }

            const adminUser = JSON.parse(adminUserString);

            
            const name = adminUser.name || 'Admin';
            const role = (adminUser.role || 'staff').toLowerCase(); 

            document.getElementById('layoutUserName').innerText = name;
            document.getElementById('layoutUserRole').innerText = role.toUpperCase();
            document.getElementById('userAvatar').innerText = name.charAt(0).toUpperCase();

            
            const menuAdmin = document.getElementById('menu-admin');
            
            if (role === 'admin') {
                
                menuAdmin.classList.remove('hidden-menu');
            } else {
                
                menuAdmin.classList.add('hidden-menu');
                
                
                const currentPath = window.location.pathname;
                if (currentPath.includes('/admin/staff') || currentPath.includes('/admin/services') || currentPath.includes('/admin/counters')) {
                    
                    window.location.href = '/admin/dashboard';
                }
            }
        });

        
        function confirmLogout() {
            Swal.fire({
                title: 'Akhiri Sesi?',
                text: "Anda harus login ulang untuk masuk kembali.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#4f46e5',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Keluar',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    performLogout();
                }
            })
        }

        function performLogout() {
            
            localStorage.removeItem('admin_token');
            localStorage.removeItem('admin_user');
            
            
            window.location.href = '/admin/login';
        }

        
        const sidebar = document.getElementById('sidebar');
        const backdrop = document.getElementById('mobileBackdrop');

        function toggleSidebar() {
            const isClosed = sidebar.classList.contains('-translate-x-full');
            if (isClosed) {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
                setTimeout(() => backdrop.classList.remove('opacity-0'), 10);
            } else {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('opacity-0');
                setTimeout(() => backdrop.classList.add('hidden'), 300);
            }
        }
    </script>
    
    @stack('scripts')
</body>
</html>