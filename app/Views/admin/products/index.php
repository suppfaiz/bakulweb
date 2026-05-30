<?php require_once __DIR__ . '/../../layout/admin_header.php'; ?>
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Manajemen Produk</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola data produk, stok, dan harga.</p>
    </div>
    <a href="<?= BASEURL; ?>/admin/product_create" class="bg-black hover:bg-gray-800 text-white px-4 py-2 rounded-md text-sm font-medium transition shadow-sm">
        <i class="fas fa-plus mr-2"></i> Tambah Produk
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori / Merek</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga Mulai</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php if(!empty($data['products'])): foreach($data['products'] as $p): ?>
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 bg-gray-100 rounded-md border border-gray-200 flex items-center justify-center overflow-hidden">
                            <?php if($p['image']): ?>
                                <img src="<?= BASEURL; ?>/<?= $p['image']; ?>" class="h-10 w-10 object-cover">
                            <?php else: ?>
                                <i class="fas fa-image text-gray-300"></i>
                            <?php endif; ?>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($p['name']); ?></div>
                            <div class="text-xs text-gray-500">Slug: <?= $p['slug']; ?></div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 border border-gray-200">
                        <?= htmlspecialchars($p['category_name']); ?>
                    </span>
                    <div class="text-xs text-gray-500 mt-1.5"><?= htmlspecialchars($p['brand_name']); ?></div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                    Rp <?= number_format((float)$p['starting_price'], 0, ',', '.'); ?>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <a href="<?= BASEURL; ?>/admin/product_edit/<?= $p['id']; ?>" class="text-blue-600 hover:text-blue-900 mr-3"><i class="fas fa-edit"></i></a>
                    <a href="<?= BASEURL; ?>/admin/product_delete/<?= $p['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini? Semua data varian dan gambar juga akan dihapus secara permanen.')" class="text-red-600 hover:text-red-900"><i class="fas fa-trash-alt"></i></a>
                </td>
            </tr>
            <?php endforeach; else: ?>
            <tr>
                <td colspan="4" class="px-6 py-10 text-center text-sm text-gray-500">
                    Belum ada produk. <a href="<?= BASEURL; ?>/admin/product_create" class="text-black font-medium hover:underline">Tambahkan sekarang.</a>
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php require_once __DIR__ . '/../../layout/admin_footer.php'; ?>
