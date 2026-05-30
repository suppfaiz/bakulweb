<?php require_once __DIR__ . '/../../layout/admin_header.php'; ?>

<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Inventory & Stock Opname</h1>
        <p class="text-sm text-gray-500 mt-1">Lacak dan sesuaikan stok produk gudang Anda.</p>
    </div>
    <div class="flex items-center gap-3">
        <div class="relative">
            <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 text-xs"></i>
            <input type="text" id="inventorySearch" placeholder="Cari produk atau SKU..." class="pl-9 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-black focus:border-black outline-none w-64">
        </div>
        <div class="flex gap-2 text-xs font-medium">
            <button onclick="filterStock('all')" id="filter-all" class="px-3 py-1.5 bg-black text-white rounded-full">Semua</button>
            <button onclick="filterStock('low')" id="filter-low" class="px-3 py-1.5 bg-white border border-gray-200 text-gray-600 rounded-full hover:bg-gray-50">Stok Rendah</button>
            <button onclick="filterStock('ok')" id="filter-ok" class="px-3 py-1.5 bg-white border border-gray-200 text-gray-600 rounded-full hover:bg-gray-50">Aman</button>
        </div>
    </div>
</div>

<!-- Summary Cards -->
<?php
$total = count($data['variants'] ?? []);
$lowStock = count(array_filter($data['variants'] ?? [], fn($v) => $v['stock'] <= 5));
$outStock = count(array_filter($data['variants'] ?? [], fn($v) => $v['stock'] == 0));
$totalStock = array_sum(array_column($data['variants'] ?? [], 'stock'));
?>
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-1">Total Varian</p>
        <p class="text-2xl font-black text-gray-900"><?= $total ?></p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-1">Total Unit</p>
        <p class="text-2xl font-black text-gray-900"><?= number_format($totalStock) ?></p>
    </div>
    <div class="bg-white rounded-xl border border-red-100 bg-red-50 p-4">
        <p class="text-xs text-red-500 uppercase tracking-wider font-semibold mb-1">Stok Rendah ≤5</p>
        <p class="text-2xl font-black text-red-600"><?= $lowStock ?></p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-1">Habis</p>
        <p class="text-2xl font-black text-gray-900"><?= $outStock ?></p>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200" id="inventoryTable">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">SKU & Produk</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Varian</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Harga Jual</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Stok Gudang</th>
                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200" id="inventoryBody">
            <?php if(!empty($data['variants'])): foreach($data['variants'] as $v): ?>
            <tr class="hover:bg-gray-50 transition-colors inventory-row" 
                data-product="<?= strtolower(htmlspecialchars($v['product_name'])) ?>" 
                data-sku="<?= strtolower($v['sku']) ?>"
                data-stock="<?= (int)$v['stock'] ?>">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-semibold text-gray-900"><?= htmlspecialchars($v['product_name']); ?></div>
                    <div class="text-xs text-gray-400 mt-0.5 font-mono">SKU: <?= htmlspecialchars($v['sku']); ?></div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    <?= htmlspecialchars($v['storage']); ?> &bull; <?= htmlspecialchars($v['color']); ?>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">
                    Rp <?= number_format((float)$v['price'], 0, ',', '.'); ?>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <?php if($v['stock'] == 0): ?>
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-red-100 text-red-700">
                            <i class="fas fa-times-circle mr-1 mt-0.5"></i> Habis
                        </span>
                    <?php elseif($v['stock'] <= 5): ?>
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-orange-100 text-orange-700">
                            <i class="fas fa-exclamation-triangle mr-1 mt-0.5"></i> <?= $v['stock']; ?> Unit
                        </span>
                    <?php else: ?>
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-green-100 text-green-700">
                            <i class="fas fa-check-circle mr-1 mt-0.5"></i> <?= $v['stock']; ?> Unit
                        </span>
                    <?php endif; ?>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <button onclick="openOpname(<?= $v['id'] ?>, '<?= addslashes($v['product_name']) ?>', '<?= addslashes($v['storage']) ?>', '<?= addslashes($v['color']) ?>', <?= $v['stock'] ?>)"
                        class="text-gray-600 hover:text-black bg-gray-100 hover:bg-gray-200 px-3 py-1.5 rounded-lg border border-gray-200 text-xs font-semibold transition">
                        <i class="fas fa-edit mr-1"></i>Opname
                    </button>
                </td>
            </tr>
            <?php endforeach; else: ?>
            <tr>
                <td colspan="5" class="px-6 py-16 text-center text-sm text-gray-500">
                    <i class="fas fa-warehouse text-4xl text-gray-200 mb-3 block"></i>
                    Belum ada data varian produk di inventory.
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal Opname -->
<div id="opnameModal" class="fixed inset-0 z-[100] hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
            <div>
                <h3 class="font-bold text-gray-900"><i class="fas fa-clipboard-check mr-2 text-black"></i>Stock Opname</h3>
                <p class="text-xs text-gray-500 mt-0.5" id="opnameProductLabel">Memuat...</p>
            </div>
            <button onclick="closeOpname()" class="text-gray-400 hover:text-black transition text-lg">&times;</button>
        </div>
        <form action="<?= BASEURL ?>/admin/inventory_update_stock" method="POST" class="p-6 space-y-5">
            <input type="hidden" name="variant_id" id="opnameVariantId">
            
            <div class="bg-gray-50 rounded-xl p-4 flex items-center justify-between">
                <span class="text-sm text-gray-600">Stok Saat Ini:</span>
                <span class="text-xl font-black text-gray-900" id="opnameCurrentStock">0</span>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Stok Baru <span class="text-red-500">*</span></label>
                <input type="number" name="new_stock" id="opnameNewStock" min="0" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-black focus:border-black outline-none text-lg font-bold"
                    placeholder="Masukkan jumlah stok baru">
                <p class="text-xs text-gray-400 mt-1.5">Masukkan jumlah stok aktual di gudang (bukan penambahan).</p>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan / Catatan</label>
                <textarea name="note" rows="2" placeholder="Contoh: Stok fisik hasil hitung ulang..."
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-black focus:border-black outline-none text-sm resize-none"></textarea>
            </div>
            
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeOpname()" class="flex-1 px-4 py-2.5 border border-gray-300 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-50 transition">
                    Batal
                </button>
                <button type="submit" class="flex-1 px-4 py-2.5 bg-black text-white rounded-xl text-sm font-semibold hover:bg-gray-800 transition shadow-md">
                    <i class="fas fa-save mr-1"></i>Simpan Stok
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openOpname(id, name, storage, color, currentStock) {
    document.getElementById('opnameVariantId').value = id;
    document.getElementById('opnameProductLabel').textContent = name + ' — ' + storage + ' / ' + color;
    document.getElementById('opnameCurrentStock').textContent = currentStock + ' Unit';
    document.getElementById('opnameNewStock').value = currentStock;
    document.getElementById('opnameNewStock').focus();
    document.getElementById('opnameModal').classList.remove('hidden');
}

function closeOpname() {
    document.getElementById('opnameModal').classList.add('hidden');
}

// Search
document.getElementById('inventorySearch').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.inventory-row').forEach(row => {
        const match = row.dataset.product.includes(q) || row.dataset.sku.includes(q);
        row.style.display = match ? '' : 'none';
    });
});

// Filter
let currentFilter = 'all';
function filterStock(type) {
    currentFilter = type;
    ['all','low','ok'].forEach(f => {
        const btn = document.getElementById('filter-' + f);
        btn.className = f === type 
            ? 'px-3 py-1.5 bg-black text-white rounded-full text-xs font-medium'
            : 'px-3 py-1.5 bg-white border border-gray-200 text-gray-600 rounded-full hover:bg-gray-50 text-xs font-medium';
    });
    document.querySelectorAll('.inventory-row').forEach(row => {
        const stock = parseInt(row.dataset.stock);
        let show = true;
        if (type === 'low') show = stock <= 5;
        if (type === 'ok') show = stock > 5;
        row.style.display = show ? '' : 'none';
    });
}

// Close modal on backdrop click
document.getElementById('opnameModal').addEventListener('click', function(e) {
    if (e.target === this) closeOpname();
});
</script>

<?php require_once __DIR__ . '/../../layout/admin_footer.php'; ?>
