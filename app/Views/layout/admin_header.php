<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($data['judul']) ? $data['judul'] : 'Admin Dashboard'; ?></title>
    <link rel="icon" type="image/png" href="<?= BASEURL; ?>/favicon.png">
    <link rel="apple-touch-icon" href="<?= BASEURL; ?>/favicon.png">
    <link href="<?= BASEURL; ?>/css/style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-white text-gray-900 antialiased">
    <!-- Mobile Sidebar Backdrop Overlay -->
    <div id="sidebarBackdrop" class="fixed inset-0 z-40 bg-black/45 backdrop-blur-sm hidden md:hidden transition-opacity duration-300"></div>

    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar Drawer -->
        <aside id="adminSidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 transform -translate-x-full md:translate-x-0 md:static md:flex flex-col flex-shrink-0 transition-transform duration-300 ease-in-out">
            <div class="h-16 flex items-center justify-between px-6 border-b border-gray-200">
                <a href="<?= BASEURL; ?>" class="flex items-center gap-2">
                    <img src="<?= BASEURL; ?>/images/logo.jpg" alt="BAKUL Logo" class="h-14 w-auto object-contain">
                    <span class="text-lg font-bold tracking-tight text-black">ERP</span>
                </a>
                <button id="mobileSidebarClose" class="md:hidden text-gray-500 hover:text-gray-700 focus:outline-none">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div class="flex-1 overflow-y-auto py-4">
                <nav class="px-4 space-y-1">
                    <a href="<?= BASEURL; ?>/admin" class="bg-gray-100 text-gray-900 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-home text-gray-500 mr-3 text-lg"></i> Dashboard
                    </a>
                    
                    <p class="px-3 pt-4 pb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Katalog</p>
                    <a href="<?= BASEURL; ?>/admin/products" class="text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-box text-gray-400 mr-3 text-lg"></i> Produk
                    </a>
                    <a href="<?= BASEURL; ?>/admin/categories" class="text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-tags text-gray-400 mr-3 text-lg"></i> Kategori & Merek
                    </a>
                    
                    <p class="px-3 pt-4 pb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Transaksi</p>
                    <a href="<?= BASEURL; ?>/admin/orders" class="text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-shopping-cart text-gray-400 mr-3 text-lg"></i> Pesanan
                    </a>
                    <a href="<?= BASEURL; ?>/admin/pos" class="text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-cash-register text-gray-400 mr-3 text-lg"></i> Point of Sale
                    </a>
                    <a href="<?= BASEURL; ?>/chat/admin" class="text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-comments text-gray-400 mr-3 text-lg"></i> Live Chat
                    </a>
 
                    <p class="px-3 pt-4 pb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">ERP Modul</p>
                    <a href="<?= BASEURL; ?>/admin/inventory" class="text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-warehouse text-gray-400 mr-3 text-lg"></i> Inventory / Opname
                    </a>
                    <a href="<?= BASEURL; ?>/admin/finance" class="<?= (strpos($_SERVER['REQUEST_URI'], '/admin/finance') !== false) ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'; ?> group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-chart-line text-gray-400 mr-3 text-lg"></i> Finance & Laporan
                    </a>
                    <a href="<?= BASEURL; ?>/admin/analysis" class="<?= (strpos($_SERVER['REQUEST_URI'], '/admin/analysis') !== false) ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'; ?> group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-chart-pie text-gray-400 mr-3 text-lg"></i> Analisa & Auto Promosi
                    </a>
                    <a href="<?= BASEURL; ?>/admin/promos" class="text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-bullhorn text-gray-400 mr-3 text-lg"></i> Banner Promo
                    </a>
                    <a href="<?= BASEURL; ?>/admin/vouchers" class="text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-ticket-alt text-gray-400 mr-3 text-lg"></i> Voucher Diskon
                    </a>
                    <a href="<?= BASEURL; ?>/admin/refunds" class="<?= (strpos($_SERVER['REQUEST_URI'], '/admin/refunds') !== false) ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'; ?> group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-undo-alt text-gray-400 mr-3 text-lg"></i> Refund & Penarikan
                    </a>
                    <a href="<?= BASEURL; ?>/admin/claims" class="<?= (strpos($_SERVER['REQUEST_URI'], '/admin/claims') !== false) ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'; ?> group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-shield-alt text-gray-400 mr-3 text-lg"></i> Klaim Garansi
                    </a>
                </nav>
            </div>
            
            <div class="p-4 border-t border-gray-200">
                <a href="<?= BASEURL; ?>" target="_blank" class="flex items-center text-sm text-gray-600 hover:text-black">
                    <i class="fas fa-external-link-alt mr-2"></i> Kunjungi Toko
                </a>
            </div>
        </aside>
 
        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Topbar -->
            <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 shrink-0">
                <button id="mobileSidebarToggle" class="md:hidden text-gray-500 hover:text-gray-700 focus:outline-none">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <div class="flex-1"></div>
                <div class="flex items-center space-x-4">
                    <a href="<?= BASEURL ?>" target="_blank" class="hidden md:flex items-center text-xs text-gray-400 hover:text-gray-700 border border-gray-200 rounded-lg px-3 py-1.5 hover:bg-gray-50 transition-all">
                        <i class="fas fa-store mr-1.5"></i> Lihat Toko
                    </a>
                    <button class="text-gray-400 hover:text-gray-500"><i class="fas fa-bell"></i></button>
                    <div class="relative border-l border-gray-200 pl-4 ml-2 flex items-center gap-2">
                        <i class="fas fa-user-circle text-2xl text-indigo-400"></i>
                        <div class="hidden sm:block">
                            <p class="text-xs font-semibold text-gray-800"><?= htmlspecialchars($_SESSION['name'] ?? 'Admin') ?></p>
                            <p class="text-xs text-gray-400 capitalize"><?= htmlspecialchars($_SESSION['role'] ?? '') ?></p>
                        </div>
                        <a href="<?= BASEURL ?>/admin/logout" class="ml-2 flex items-center text-xs text-red-400 hover:text-red-600 border border-red-100 rounded-lg px-2.5 py-1.5 hover:bg-red-50 transition-all">
                            <i class="fas fa-sign-out-alt mr-1"></i> Keluar
                        </a>
                    </div>
                </div>
            </header>

 
            <!-- Main Scrollable Area -->
            <main class="flex-1 overflow-y-auto bg-white p-6">
            
            <!-- Mobile Sidebar Script -->
            <script>
            document.addEventListener("DOMContentLoaded", function() {
                const toggleBtn = document.getElementById('mobileSidebarToggle');
                const closeBtn = document.getElementById('mobileSidebarClose');
                const sidebar = document.getElementById('adminSidebar');
                const backdrop = document.getElementById('sidebarBackdrop');
 
                if (toggleBtn && sidebar && backdrop) {
                    toggleBtn.addEventListener('click', function() {
                        sidebar.classList.remove('-translate-x-full');
                        sidebar.classList.add('translate-x-0');
                        backdrop.classList.remove('hidden');
                    });
                }
 
                if (closeBtn && sidebar && backdrop) {
                    closeBtn.addEventListener('click', function() {
                        sidebar.classList.remove('translate-x-0');
                        sidebar.classList.add('-translate-x-full');
                        backdrop.classList.add('hidden');
                    });
                }
 
                if (backdrop && sidebar) {
                    backdrop.addEventListener('click', function() {
                        sidebar.classList.remove('translate-x-0');
                        sidebar.classList.add('-translate-x-full');
                        backdrop.classList.add('hidden');
                    });
                }
            });
            </script>
