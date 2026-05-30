<?php require_once __DIR__ . '/../../layout/admin_header.php'; ?>

<div class="flex-1 overflow-y-auto p-6 bg-white">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manajemen Klaim Garansi</h1>
            <p class="text-sm text-gray-500">Kelola dan tinjau berkas serta bukti klaim garansi yang diajukan oleh pelanggan.</p>
        </div>
    </div>

    <!-- Main Content Table -->
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Pelanggan & Invoice</th>
                    <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Perangkat</th>
                    <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kendala & Bukti</th>
                    <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (empty($data['claims'])): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-sm text-gray-500">Tidak ada data klaim garansi yang diajukan.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($data['claims'] as $claim): ?>
                        <tr class="align-top">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= date('d M Y, H:i', strtotime($claim['created_at'])); ?>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div class="font-bold text-gray-900"><?= htmlspecialchars($claim['invoice']); ?></div>
                                <div class="text-xs text-gray-500"><?= htmlspecialchars($claim['username']); ?> (<?= htmlspecialchars($claim['email']); ?>)</div>
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                                <?= htmlspecialchars($claim['device_name']); ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 max-w-xs">
                                <div class="mb-2 italic text-gray-600">"<?= htmlspecialchars($claim['reason']); ?>"</div>
                                <?php if ($claim['attachment_path']): 
                                    $ext = strtolower(pathinfo($claim['attachment_path'], PATHINFO_EXTENSION));
                                    if (in_array($ext, ['mp4', 'mov', 'avi'])): ?>
                                        <a href="<?= BASEURL; ?>/<?= $claim['attachment_path']; ?>" target="_blank" class="inline-flex items-center text-xs text-primary hover:underline font-semibold">
                                            <i class="fas fa-video mr-1"></i> Lihat Bukti Video
                                        </a>
                                    <?php else: ?>
                                        <a href="<?= BASEURL; ?>/<?= $claim['attachment_path']; ?>" target="_blank" class="inline-flex items-center text-xs text-primary hover:underline font-semibold">
                                            <i class="fas fa-image mr-1"></i> Lihat Bukti Gambar
                                        </a>
                                    <?php endif; 
                                else: ?>
                                    <span class="text-xs text-gray-400">Tidak ada berkas bukti</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <?php
                                $badge = 'bg-yellow-100 text-yellow-800';
                                $stText = 'Pending';
                                if ($claim['status'] == 'approved') { $badge = 'bg-green-100 text-green-800'; $stText = 'Disetujui'; }
                                elseif ($claim['status'] == 'rejected') { $badge = 'bg-red-100 text-red-800'; $stText = 'Ditolak'; }
                                ?>
                                <span class="px-2 py-1 rounded-full text-xs font-bold <?= $badge; ?>"><?= $stText; ?></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <button onclick='openClaimStatusModal(<?= json_encode($claim); ?>)' class="inline-flex items-center px-3 py-1.5 bg-black hover:bg-gray-800 text-white text-xs font-bold rounded-lg transition-all">
                                    <i class="fas fa-edit mr-1"></i> Update Status
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal: Update Claim Status -->
<div id="claimStatusModal" class="fixed inset-0 z-50 overflow-y-auto hidden flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm transition-all duration-300">
    <div id="claimStatusModalContent" class="bg-white rounded-3xl max-w-md w-full p-6 shadow-xl transform scale-95 opacity-0 transition-all duration-300 border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900">Update Status Klaim Garansi</h3>
            <button onclick="closeClaimStatusModal()" class="text-gray-400 hover:text-black transition">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        
        <form action="<?= BASEURL; ?>/admin/update_claim_status" method="POST" class="space-y-4">
            <input type="hidden" id="claim_id" name="id">
            
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Invoice & Perangkat</label>
                <div id="claim_device_info" class="font-bold text-gray-900 text-sm"></div>
            </div>

            <div>
                <label for="claim_status" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Pilih Status Baru</label>
                <select id="claim_status" name="status" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-1 focus:ring-black focus:border-black outline-none text-sm bg-gray-50">
                    <option value="pending">Pending</option>
                    <option value="approved">Disetujui (Approved)</option>
                    <option value="rejected">Ditolak (Rejected)</option>
                </select>
            </div>

            <div class="flex justify-end space-x-3 pt-4">
                <button type="button" onclick="closeClaimStatusModal()" class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-semibold rounded-full hover:bg-gray-50 transition">Batal</button>
                <button type="submit" class="px-5 py-2 bg-primary hover:bg-sky-700 text-white text-sm font-semibold rounded-full shadow transition">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    const claimStatusModal = document.getElementById('claimStatusModal');
    const claimStatusModalContent = document.getElementById('claimStatusModalContent');

    function openClaimStatusModal(claim) {
        document.getElementById('claim_id').value = claim.id;
        document.getElementById('claim_device_info').innerText = claim.invoice + ' - ' + claim.device_name;
        document.getElementById('claim_status').value = claim.status;

        claimStatusModal.classList.remove('hidden');
        setTimeout(() => {
            claimStatusModalContent.classList.remove('scale-95', 'opacity-0');
            claimStatusModalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeClaimStatusModal() {
        claimStatusModalContent.classList.remove('scale-100', 'opacity-100');
        claimStatusModalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            claimStatusModal.classList.add('hidden');
        }, 300);
    }
</script>

<?php require_once __DIR__ . '/../../layout/admin_footer.php'; ?>
