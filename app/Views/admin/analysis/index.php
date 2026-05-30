<?php require_once __DIR__ . '/../../layout/admin_header.php'; ?>

<!-- Header -->
<div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Analisa Bisnis & Auto Promosi</h1>
        <p class="text-sm text-gray-500 mt-1">Menganalisis kinerja barang terlaris, barang lambat terjual, serta membuat promosi otomatis secara instan.</p>
    </div>
    <!-- Set Target Form -->
    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-col sm:flex-row items-start sm:items-center gap-3">
        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Target Bulanan:</span>
        <form action="<?= BASEURL; ?>/admin/analysis" method="POST" class="flex items-center space-x-2">
            <div class="relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="text-gray-500 text-sm">Rp</span>
                </div>
                <input type="number" name="sales_target" value="<?= $data['sales_target']; ?>" 
                       class="pl-9 pr-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent w-40 font-medium text-gray-900" required>
            </div>
            <button type="submit" class="px-4 py-1.5 bg-[#111e2e] text-white rounded-lg text-sm hover:bg-black font-semibold transition-colors duration-200">
                Sesuaikan
            </button>
        </form>
    </div>
</div>

<!-- Target Sales Progress Section -->
<?php 
$percentage = ($data['sales_target'] > 0) ? min(100, round(($data['current_sales'] / $data['sales_target']) * 100)) : 0;
$remaining = max(0, $data['sales_target'] - $data['current_sales']);
?>
<div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm mb-8">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div class="flex-1">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-semibold text-gray-500">Pencapaian Target Penjualan Bulan Ini</span>
                <span class="text-lg font-bold text-blue-600"><?= $percentage; ?>%</span>
            </div>
            <!-- Progress Bar -->
            <div class="w-full bg-gray-100 rounded-full h-4 overflow-hidden mb-3">
                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-4 rounded-full transition-all duration-500" style="width: <?= $percentage; ?>%"></div>
            </div>
            <div class="flex flex-wrap justify-between text-xs text-gray-500 gap-2">
                <span>Tercapai: <strong>Rp <?= number_format($data['current_sales'], 0, ',', '.'); ?></strong></span>
                <span>Sisa Target: <strong>Rp <?= number_format($remaining, 0, ',', '.'); ?></strong></span>
                <span>Target: <strong>Rp <?= number_format($data['sales_target'], 0, ',', '.'); ?></strong></span>
            </div>
        </div>
        <div class="border-t lg:border-t-0 lg:border-l border-gray-200 pt-4 lg:pt-0 lg:pl-8 flex flex-col justify-center min-w-[200px]">
            <p class="text-xs text-gray-400 font-medium uppercase tracking-wider mb-1">Status Target</p>
            <?php if ($percentage >= 100): ?>
                <div class="flex items-center text-green-600 font-bold text-sm">
                    <i class="fas fa-check-circle text-xl mr-2"></i> Target Tercapai! 🎉
                </div>
                <p class="text-xs text-gray-500 mt-1">Performa luar biasa! Terus pertahankan penjualan.</p>
            <?php else: ?>
                <div class="flex items-center text-amber-500 font-bold text-sm">
                    <i class="fas fa-chart-line text-xl mr-2"></i> Sedang Berjalan
                </div>
                <p class="text-xs text-gray-500 mt-1">Buat promosi otomatis di bawah untuk mempercepat pencapaian target.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Main Grid: Fast Moving & Slow Moving -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    
    <!-- Fast-Moving Section -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-blue-50/50 to-transparent">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center text-blue-600">
                        <i class="fas fa-fire-alt text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 text-base">Produk Terlaris (Fast-Moving)</h3>
                        <p class="text-xs text-gray-500">Barang dengan penjualan tertinggi dan diminati konsumen.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            <?php if (!empty($data['fast_moving'])): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100 text-sm">
                        <thead>
                            <tr class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                <th class="pb-3 font-medium">Produk</th>
                                <th class="pb-3 text-center font-medium">Terjual</th>
                                <th class="pb-3 text-right font-medium">Omset</th>
                                <th class="pb-3 text-center font-medium">Stok</th>
                                <th class="pb-3 text-center font-medium">Aksi Promosi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php foreach ($data['fast_moving'] as $p): ?>
                                <tr>
                                    <td class="py-3">
                                        <div class="flex items-center">
                                            <?php if (!empty($p['primary_image'])): ?>
                                                <img src="<?= BASEURL; ?>/<?= $p['primary_image']; ?>" class="w-10 h-10 object-cover rounded-lg border mr-3">
                                            <?php else: ?>
                                                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center mr-3 border text-gray-400">
                                                    <i class="fas fa-image"></i>
                                                </div>
                                            <?php endif; ?>
                                            <div>
                                                <p class="font-bold text-gray-900 leading-tight"><?= htmlspecialchars($p['product_name']); ?></p>
                                                <p class="text-xs text-gray-400 mt-0.5"><?= htmlspecialchars($p['category_name'] ?? 'Tanpa Kategori'); ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 text-center font-bold text-gray-900"><?= $p['total_qty_sold']; ?> unit</td>
                                    <td class="py-3 text-right font-semibold text-gray-900">Rp <?= number_format($p['total_revenue'], 0, ',', '.'); ?></td>
                                    <td class="py-3 text-center">
                                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold <?= $p['total_stock'] <= 5 ? 'bg-red-50 text-red-600' : 'bg-green-50 text-green-700'; ?>">
                                            <?= $p['total_stock']; ?>
                                        </span>
                                    </td>
                                    <td class="py-3 text-center">
                                        <a href="<?= BASEURL; ?>/admin/auto_create_promo?product_id=<?= $p['product_id']; ?>" 
                                           onclick="return confirm('Sistem akan membuat banner promo di beranda untuk produk terlaris ini. Lanjutkan?')"
                                           class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white rounded-lg text-xs font-bold hover:bg-blue-700 transition-colors duration-150">
                                            <i class="fas fa-bullhorn mr-1.5"></i> Auto Promo Banner
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-8 text-gray-400">
                    <i class="fas fa-info-circle text-2xl mb-2 text-gray-300"></i>
                    <p class="text-sm">Belum ada data penjualan tercatat untuk dianalisa.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Slow-Moving Section -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-amber-50/50 to-transparent">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center text-amber-600">
                        <i class="fas fa-snowflake text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 text-base">Produk Lambat Terjual (Slow-Moving)</h3>
                        <p class="text-xs text-gray-500">Barang dengan stok menumpuk tapi penjualan sangat sedikit.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            <?php if (!empty($data['slow_moving'])): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100 text-sm">
                        <thead>
                            <tr class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                <th class="pb-3 font-medium">Produk</th>
                                <th class="pb-3 text-center font-medium">Terjual</th>
                                <th class="pb-3 text-center font-medium">Stok Mengendap</th>
                                <th class="pb-3 text-center font-medium">Rekomendasi Promosi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php foreach ($data['slow_moving'] as $p): ?>
                                <tr>
                                    <td class="py-3">
                                        <div class="flex items-center">
                                            <?php if (!empty($p['primary_image'])): ?>
                                                <img src="<?= BASEURL; ?>/<?= $p['primary_image']; ?>" class="w-10 h-10 object-cover rounded-lg border mr-3">
                                            <?php else: ?>
                                                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center mr-3 border text-gray-400">
                                                    <i class="fas fa-image"></i>
                                                </div>
                                            <?php endif; ?>
                                            <div>
                                                <p class="font-bold text-gray-900 leading-tight"><?= htmlspecialchars($p['product_name']); ?></p>
                                                <p class="text-xs text-gray-400 mt-0.5"><?= htmlspecialchars($p['category_name'] ?? 'Tanpa Kategori'); ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 text-center text-gray-600"><?= $p['total_qty_sold']; ?> unit</td>
                                    <td class="py-3 text-center font-semibold text-amber-600"><?= $p['total_stock']; ?> unit</td>
                                    <td class="py-3 text-center">
                                        <a href="<?= BASEURL; ?>/admin/auto_create_voucher?product_id=<?= $p['product_id']; ?>" 
                                           onclick="return confirm('Sistem akan membuat voucher diskon 15% untuk produk lambat terjual ini agar mempercepat pengosongan stok. Lanjutkan?')"
                                           class="inline-flex items-center px-3 py-1.5 bg-amber-500 text-white rounded-lg text-xs font-bold hover:bg-amber-600 transition-colors duration-150">
                                            <i class="fas fa-ticket-alt mr-1.5"></i> Auto Voucher 15%
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-8 text-gray-400">
                    <i class="fas fa-info-circle text-2xl mb-2 text-gray-300"></i>
                    <p class="text-sm">Semua stok produk terjual dengan sangat baik!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Suggestions & Out of Stock Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Bundling Suggestions -->
    <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex flex-col justify-between">
        <div>
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center text-purple-600">
                    <i class="fas fa-boxes text-lg"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 text-base">Saran Strategi Bundling Cerdas</h3>
                    <p class="text-xs text-gray-500">Mengkombinasikan produk terlaris dengan produk lambat untuk menaikkan penjualan optimal.</p>
                </div>
            </div>
            
            <div class="space-y-4">
                <?php if (!empty($data['bundling_suggestions'])): foreach ($data['bundling_suggestions'] as $bundle): ?>
                    <div class="p-4 rounded-xl border border-purple-100 bg-purple-50/20">
                        <div class="flex flex-wrap items-center justify-between gap-3 mb-2">
                            <span class="px-2 py-0.5 bg-purple-100 text-purple-700 font-semibold text-xs rounded">Kombinasi Kategori: <?= htmlspecialchars($bundle['category']); ?></span>
                            <span class="text-xs font-bold text-purple-800"><i class="fas fa-percent mr-1"></i> Rekomendasi Diskon Bundling: <?= $bundle['discount_pct']; ?>%</span>
                        </div>
                        <div class="flex items-center justify-between gap-4 mt-2">
                            <div class="flex items-center space-x-2 text-sm text-gray-700">
                                <span class="font-bold text-blue-600 bg-white px-2 py-1 rounded border shadow-sm leading-tight"><?= htmlspecialchars($bundle['fast_product']['product_name']); ?></span>
                                <span class="font-bold text-gray-400">+</span>
                                <span class="font-bold text-amber-600 bg-white px-2 py-1 rounded border shadow-sm leading-tight"><?= htmlspecialchars($bundle['slow_product']['product_name']); ?></span>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-3 flex items-start">
                            <i class="fas fa-lightbulb text-yellow-500 mr-1.5 mt-0.5"></i>
                            <?= htmlspecialchars($bundle['reason']); ?>
                        </p>
                    </div>
                <?php endforeach; else: ?>
                    <p class="text-sm text-gray-400 text-center py-6">Belum ada saran bundling yang dapat dianalisa saat ini.</p>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between text-xs text-gray-400">
            <span>Saran diperbarui secara real-time berdasarkan matriks pergerakan stok.</span>
        </div>
    </div>
    
    <!-- Restock Alert -->
    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex flex-col justify-between">
        <div>
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center text-red-500">
                    <i class="fas fa-exclamation-triangle text-lg"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 text-base">Alert Restock Segera!</h3>
                    <p class="text-xs text-gray-500">Barang laku keras yang stoknya sudah kritis/habis.</p>
                </div>
            </div>
            
            <div class="space-y-3">
                <?php if (!empty($data['out_of_stock'])): foreach ($data['out_of_stock'] as $item): ?>
                    <div class="flex items-center justify-between p-3 rounded-lg border border-red-100 bg-red-50/20 text-sm">
                        <div class="flex-1 pr-2">
                            <p class="font-bold text-gray-900 truncate"><?= htmlspecialchars($item['product_name']); ?></p>
                            <p class="text-xs text-gray-500">Total terjual sebelumnya: <?= $item['total_qty_sold']; ?> unit</p>
                        </div>
                        <div class="text-right">
                            <span class="block text-xs font-bold text-red-600 bg-red-100 px-2 py-0.5 rounded">
                                Stok: <?= $item['total_stock']; ?>
                            </span>
                            <a href="<?= BASEURL; ?>/admin/inventory" class="inline-block text-xs text-blue-600 font-bold hover:underline mt-1.5">
                                Isi Stok &rarr;
                            </a>
                        </div>
                    </div>
                <?php endforeach; else: ?>
                    <div class="text-center py-6 text-gray-400">
                        <i class="fas fa-check text-green-500 text-xl mb-1"></i>
                        <p class="text-xs">Stok produk terlaris masih aman.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="mt-6 pt-4 border-t border-gray-100 text-center">
            <a href="<?= BASEURL; ?>/admin/inventory" class="text-sm font-bold text-blue-600 hover:text-blue-700 inline-flex items-center">
                <i class="fas fa-warehouse mr-2"></i> Buka Manajemen Inventory &rarr;
            </a>
        </div>
    </div>
    
</div>

<?php require_once __DIR__ . '/../../layout/admin_footer.php'; ?>
