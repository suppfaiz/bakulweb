<?php require_once __DIR__ . '/../../layout/admin_header.php'; ?>

<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Manajemen Voucher Diskon</h1>
        <p class="text-sm text-gray-500 mt-1">Buat dan kelola kode voucher diskon belanja untuk transaksi online pelanggan.</p>
    </div>
    <a href="<?= BASEURL; ?>/admin/voucher_create" class="bg-black hover:bg-gray-800 text-white px-4 py-2 rounded-xl text-sm font-semibold transition shadow-sm flex items-center gap-2">
        <i class="fas fa-plus"></i> Buat Voucher
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 font-semibold text-gray-900">Kode</th>
                    <th class="px-6 py-4 font-semibold text-gray-900">Tipe Diskon</th>
                    <th class="px-6 py-4 font-semibold text-gray-900">Nilai Potongan</th>
                    <th class="px-6 py-4 font-semibold text-gray-900">Min. Belanja</th>
                    <th class="px-6 py-4 font-semibold text-gray-900">Kedaluwarsa</th>
                    <th class="px-6 py-4 font-semibold text-gray-900">Kuota Terpakai</th>
                    <th class="px-6 py-4 font-semibold text-gray-900">Status</th>
                    <th class="px-6 py-4 font-semibold text-gray-900 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                <?php if(!empty($data['vouchers'])): foreach($data['vouchers'] as $voucher): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 bg-primary/10 text-primary text-xs font-bold rounded-lg font-mono border border-primary/20">
                                <?= htmlspecialchars($voucher['code']); ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap capitalize text-gray-700">
                            <?= $voucher['discount_type'] === 'percentage' ? 'Persentase (%)' : 'Potongan Tunai (Rupiah)'; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-950">
                            <?= $voucher['discount_type'] === 'percentage' ? $voucher['discount_amount'] . '%' : 'Rp ' . number_format($voucher['discount_amount'], 0, ',', '.'); ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                            Rp <?= number_format($voucher['min_spend'], 0, ',', '.'); ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                            <?php 
                            if ($voucher['expiry_date']) {
                                $expired = strtotime($voucher['expiry_date'] . ' 23:59:59') < time();
                                $class = $expired ? 'text-red-500 font-semibold' : 'text-gray-600';
                                echo '<span class="' . $class . '">' . date('d M Y', strtotime($voucher['expiry_date'])) . ($expired ? ' (Expired)' : '') . '</span>';
                            } else {
                                echo '<span class="text-gray-400">Tidak ada batas</span>';
                            }
                            ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-600 font-medium">
                            <?= $voucher['usage_count']; ?> / <?= $voucher['usage_limit'] !== null ? $voucher['usage_limit'] : '&infin;'; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php 
                            $isExpired = $voucher['expiry_date'] && strtotime($voucher['expiry_date'] . ' 23:59:59') < time();
                            $isFull = $voucher['usage_limit'] !== null && $voucher['usage_count'] >= $voucher['usage_limit'];
                            if($voucher['is_active'] == 1 && !$isExpired && !$isFull): 
                            ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-150">
                                    Aktif
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-50 text-red-600 border border-red-150">
                                    Tidak Aktif
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium space-x-2">
                            <a href="<?= BASEURL; ?>/admin/voucher_edit/<?= $voucher['id']; ?>" class="inline-block px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-lg transition">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="<?= BASEURL; ?>/admin/voucher_delete/<?= $voucher['id']; ?>" 
                               onclick="return confirm('Apakah Anda yakin ingin menghapus voucher ini?')"
                               class="inline-block px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 font-bold rounded-lg transition">
                                <i class="fas fa-trash-alt"></i> Hapus
                            </a>
                        </td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr>
                        <td colspan="8" class="text-center py-10 text-gray-400">Belum ada data voucher diskon.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../../layout/admin_footer.php'; ?>
