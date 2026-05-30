<?php require_once __DIR__ . '/../layout/admin_header.php'; ?>

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
    <p class="text-sm text-gray-500 mt-1">Ringkasan aktivitas dan metrik sistem hari ini.</p>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
    <!-- Stat 1: Pendapatan -->
    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Pendapatan (Lunas)</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">Rp <?= number_format($data['stats']['revenue'], 0, ',', '.'); ?></p>
            </div>
            <div class="w-12 h-12 bg-black rounded-full flex items-center justify-center text-white">
                <i class="fas fa-wallet"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-xs text-gray-400">
            <span class="text-green-500 font-medium flex items-center mr-1"><i class="fas fa-check-circle mr-1"></i> Aktif</span>
            <span>Real-time dari penjualan lunas</span>
        </div>
    </div>

    <!-- Stat 2: Pesanan Baru -->
    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Pesanan Baru (Antrean)</p>
                <p class="text-2xl font-bold text-gray-900 mt-1"><?= $data['stats']['new_orders']; ?></p>
            </div>
            <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center text-gray-600">
                <i class="fas fa-shopping-bag"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-xs text-gray-400">
            <span class="text-blue-500 font-medium flex items-center mr-1"><i class="fas fa-sync mr-1"></i> Perlu diproses</span>
            <span>Status Pending & Processing</span>
        </div>
    </div>

    <!-- Stat 3: Stok Menipis -->
    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Stok Menipis (<= 5)</p>
                <p class="text-2xl font-bold text-gray-900 mt-1"><?= $data['low_stock_count']; ?></p>
            </div>
            <div class="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center text-red-500">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-sm">
            <a href="<?= BASEURL; ?>/admin/products" class="text-black font-medium hover:underline text-xs">Periksa Stok &rarr;</a>
        </div>
    </div>

    <!-- Stat 4: Total Customer -->
    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Customer</p>
                <p class="text-2xl font-bold text-gray-900 mt-1"><?= $data['customer_count']; ?></p>
            </div>
            <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center text-gray-600">
                <i class="fas fa-users"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-xs text-gray-400">
            <span>Customer terdaftar</span>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Chart Placeholder -->
    <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
        <h3 class="text-base font-bold text-gray-900 mb-4">Grafik Penjualan</h3>
        <div class="h-64 bg-gray-50 border border-dashed border-gray-200 rounded-xl flex items-center justify-center text-gray-400">
            [ Area Chart Penjualan Harian ]
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
        <h3 class="text-base font-bold text-gray-900 mb-4">Aktivitas Pesanan Terbaru</h3>
        <div class="space-y-4">
            <?php if (!empty($data['recent_activities'])): foreach ($data['recent_activities'] as $act): ?>
                <div class="flex items-start">
                    <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 mt-0.5">
                        <i class="fas fa-shopping-cart text-xs"></i>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm text-gray-900 font-bold"><?= $act['invoice']; ?></p>
                        <p class="text-xs text-gray-500">Pelanggan: <?= htmlspecialchars($act['customer_name']); ?></p>
                        <div class="flex justify-between items-center mt-1">
                            <span class="text-xs text-gray-400"><?= date('d M, H:i', strtotime($act['created_at'])); ?></span>
                            <span class="text-xs font-bold text-gray-800">Rp <?= number_format($act['total_amount'], 0, ',', '.'); ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; else: ?>
                <p class="text-sm text-gray-400 text-center py-6">Belum ada aktivitas pesanan baru.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/admin_footer.php'; ?>
