<?php require_once __DIR__ . '/../../layout/admin_header.php'; ?>

<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Kategori & Merek</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola master data pengelompokan produk.</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    
    <!-- Kategori -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-lg font-bold text-gray-900 mb-2">Kategori</h2>
            <form action="<?= BASEURL; ?>/admin/category_store" method="POST" class="flex gap-2">
                <input type="text" name="name" placeholder="Nama Kategori Baru..." required class="flex-1 px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:ring-black focus:border-black outline-none">
                <button type="submit" class="px-4 py-1.5 bg-black text-white text-sm font-medium rounded-lg hover:bg-gray-800"><i class="fas fa-plus mr-1"></i> Tambah</button>
            </form>
        </div>
        <ul class="divide-y divide-gray-200">
            <?php if(!empty($data['categories'])): foreach($data['categories'] as $cat): ?>
            <li class="px-6 py-4 hover:bg-gray-50 flex justify-between items-center transition-colors">
                <div>
                    <p class="text-sm font-medium text-gray-900"><?= htmlspecialchars($cat['name']); ?></p>
                    <p class="text-xs text-gray-500">Slug: <?= $cat['slug']; ?></p>
                </div>
                <div class="text-gray-400 hover:text-black cursor-pointer"><i class="fas fa-ellipsis-v"></i></div>
            </li>
            <?php endforeach; else: ?>
            <li class="px-6 py-4 text-sm text-gray-500 text-center">Belum ada kategori</li>
            <?php endif; ?>
        </ul>
    </div>

    <!-- Merek -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-lg font-bold text-gray-900 mb-2">Merek (Brand)</h2>
            <form action="<?= BASEURL; ?>/admin/brand_store" method="POST" class="flex gap-2">
                <input type="text" name="name" placeholder="Nama Merek Baru..." required class="flex-1 px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:ring-black focus:border-black outline-none">
                <button type="submit" class="px-4 py-1.5 bg-black text-white text-sm font-medium rounded-lg hover:bg-gray-800"><i class="fas fa-plus mr-1"></i> Tambah</button>
            </form>
        </div>
        <ul class="divide-y divide-gray-200">
            <?php if(!empty($data['brands'])): foreach($data['brands'] as $brand): ?>
            <li class="px-6 py-4 hover:bg-gray-50 flex justify-between items-center transition-colors">
                <div>
                    <p class="text-sm font-medium text-gray-900"><?= htmlspecialchars($brand['name']); ?></p>
                    <p class="text-xs text-gray-500">Slug: <?= $brand['slug']; ?></p>
                </div>
                <div class="text-gray-400 hover:text-black cursor-pointer"><i class="fas fa-ellipsis-v"></i></div>
            </li>
            <?php endforeach; else: ?>
            <li class="px-6 py-4 text-sm text-gray-500 text-center">Belum ada merek</li>
            <?php endif; ?>
        </ul>
    </div>

</div>

<?php require_once __DIR__ . '/../../layout/admin_footer.php'; ?>
