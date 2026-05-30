<?php require_once __DIR__ . '/../../layout/admin_header.php'; ?>

<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Manajemen Banner Promo</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola gambar banner slideshow yang tampil di halaman depan website.</p>
    </div>
    <a href="<?= BASEURL; ?>/admin/promo_create" class="bg-black hover:bg-gray-800 text-white px-4 py-2 rounded-xl text-sm font-semibold transition shadow-sm flex items-center gap-2">
        <i class="fas fa-plus"></i> Tambah Banner
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 font-semibold text-gray-900">Urutan</th>
                    <th class="px-6 py-4 font-semibold text-gray-900">Gambar</th>
                    <th class="px-6 py-4 font-semibold text-gray-900">Info Banner</th>
                    <th class="px-6 py-4 font-semibold text-gray-900">Tautan (URL)</th>
                    <th class="px-6 py-4 font-semibold text-gray-900">Status</th>
                    <th class="px-6 py-4 font-semibold text-gray-900 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                <?php if(!empty($data['promos'])): foreach($data['promos'] as $promo): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-950">
                            #<?= $promo['sort_order']; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="w-24 h-16 rounded-lg border border-gray-200 overflow-hidden bg-gray-100 flex items-center justify-center">
                                <img src="<?= BASEURL; ?>/<?= htmlspecialchars($promo['image_path']); ?>" alt="Banner" class="w-full h-full object-cover">
                            </div>
                        </td>
                        <td class="px-6 py-4 max-w-xs">
                            <p class="font-bold text-gray-900 leading-tight"><?= htmlspecialchars($promo['title'] ?? 'COMING SOON'); ?></p>
                            <p class="text-xs text-gray-500 mt-1 line-clamp-2"><?= htmlspecialchars($promo['subtitle'] ?? ''); ?></p>
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-500 font-mono truncate max-w-xs">
                            <?= $promo['link_url'] ? htmlspecialchars($promo['link_url']) : '<span class="text-gray-300">-</span>'; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if($promo['is_active'] == 1): ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-150">
                                    Aktif
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-150 text-gray-600">
                                    Nonaktif
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium space-x-2">
                            <a href="<?= BASEURL; ?>/admin/promo_edit/<?= $promo['id']; ?>" class="inline-block px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-lg transition">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="<?= BASEURL; ?>/admin/promo_delete/<?= $promo['id']; ?>" 
                               onclick="return confirm('Apakah Anda yakin ingin menghapus banner promo ini?')"
                               class="inline-block px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 font-bold rounded-lg transition">
                                <i class="fas fa-trash-alt"></i> Hapus
                            </a>
                        </td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr>
                        <td colspan="6" class="text-center py-10 text-gray-400">Belum ada data banner promosi.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../../layout/admin_footer.php'; ?>
