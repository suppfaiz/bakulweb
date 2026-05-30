<?php require_once __DIR__ . '/../../layout/admin_header.php'; ?>

<div class="flex-1 overflow-y-auto p-6 bg-white">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manajemen Refund & Penarikan Dana</h1>
            <p class="text-sm text-gray-500">Kelola dan tinjau berkas serta bukti pengajuan refund dan pencairan dana pelanggan.</p>
        </div>
    </div>

    <!-- Tabs Nav -->
    <div class="border-b border-gray-200 mb-6">
        <nav class="-mb-px flex space-x-8">
            <button onclick="switchTab('refunds')" id="tab-btn-refunds" class="border-b-2 border-primary py-4 px-1 text-sm font-semibold text-primary transition-all">
                Pengajuan Refund (<?= count($data['refunds']); ?>)
            </button>
            <button onclick="switchTab('withdrawals')" id="tab-btn-withdrawals" class="border-b-2 border-transparent py-4 px-1 text-sm font-semibold text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-all">
                Penarikan Dana (<?= count($data['withdrawals']); ?>)
            </button>
        </nav>
    </div>

    <!-- Tab Content: Refunds -->
    <div id="tab-content-refunds" class="space-y-6">
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Pesanan & Pelanggan</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nominal</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Alasan & Bukti</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status Tracking</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (empty($data['refunds'])): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-sm text-gray-500">Tidak ada data pengajuan refund.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($data['refunds'] as $refund): ?>
                            <tr class="align-top">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= date('d M Y, H:i', strtotime($refund['created_at'])); ?>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <div class="font-bold text-gray-900"><?= $refund['invoice']; ?></div>
                                    <div class="text-xs text-gray-500"><?= htmlspecialchars($refund['username']); ?> (<?= htmlspecialchars($refund['email']); ?>)</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                    Rp <?= number_format($refund['amount'], 0, ',', '.'); ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 max-w-xs">
                                    <div class="mb-2 italic text-gray-600">"<?= htmlspecialchars($refund['reason']); ?>"</div>
                                    <?php if ($refund['proof_path']): ?>
                                        <a href="<?= BASEURL; ?>/<?= $refund['proof_path']; ?>" target="_blank" class="inline-flex items-center text-xs text-primary hover:underline font-semibold">
                                            <i class="fas fa-image mr-1"></i> Lihat Bukti Gambar
                                        </a>
                                    <?php else: ?>
                                        <span class="text-xs text-gray-400">Tidak ada berkas bukti</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <?php
                                    $badge = 'bg-yellow-100 text-yellow-800';
                                    $stText = 'Pending';
                                    if ($refund['status'] == 'under_review') { $badge = 'bg-blue-100 text-blue-800'; $stText = 'Sedang Ditinjau'; }
                                    elseif ($refund['status'] == 'approved') { $badge = 'bg-green-100 text-green-800'; $stText = 'Disetujui'; }
                                    elseif ($refund['status'] == 'rejected') { $badge = 'bg-red-100 text-red-800'; $stText = 'Ditolak'; }
                                    ?>
                                    <span class="px-2 py-1 rounded-full text-xs font-bold <?= $badge; ?>"><?= $stText; ?></span>
                                    <?php if ($refund['admin_notes']): ?>
                                        <div class="text-[11px] text-gray-500 mt-2 max-w-[200px] whitespace-normal">
                                            <span class="font-semibold">Catatan:</span> <?= htmlspecialchars($refund['admin_notes']); ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <button onclick='openRefundStatusModal(<?= json_encode($refund); ?>)' class="inline-flex items-center px-3 py-1.5 bg-black hover:bg-gray-800 text-white text-xs font-bold rounded-lg transition-all">
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

    <!-- Tab Content: Withdrawals -->
    <div id="tab-content-withdrawals" class="space-y-6 hidden">
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Pelanggan</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nominal Penarikan</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Rekening Tujuan</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (empty($data['withdrawals'])): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-sm text-gray-500">Tidak ada data penarikan dana.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($data['withdrawals'] as $w): ?>
                            <tr class="align-top">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= date('d M Y, H:i', strtotime($w['created_at'])); ?>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <div class="font-bold text-gray-900"><?= htmlspecialchars($w['username']); ?></div>
                                    <div class="text-xs text-gray-500"><?= htmlspecialchars($w['email']); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                    Rp <?= number_format($w['amount'], 0, ',', '.'); ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <div class="font-semibold text-gray-800"><?= htmlspecialchars($w['bank_name']); ?></div>
                                    <div class="font-mono text-gray-700"><?= htmlspecialchars($w['account_number']); ?></div>
                                    <div class="text-xs text-gray-500">a/n <?= htmlspecialchars($w['account_holder']); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <?php
                                    $wBadge = 'bg-yellow-100 text-yellow-800';
                                    $wStText = 'Pending';
                                    if ($w['status'] == 'processing') { $wBadge = 'bg-blue-100 text-blue-800'; $wStText = 'Diproses'; }
                                    elseif ($w['status'] == 'completed') { $wBadge = 'bg-green-100 text-green-800'; $wStText = 'Selesai'; }
                                    elseif ($w['status'] == 'failed') { $wBadge = 'bg-red-100 text-red-800'; $wStText = 'Gagal (Saldo Dikembalikan)'; }
                                    ?>
                                    <span class="px-2 py-1 rounded-full text-xs font-bold <?= $wBadge; ?>"><?= $wStText; ?></span>
                                    <?php if ($w['admin_notes']): ?>
                                        <div class="text-[11px] text-gray-500 mt-2 max-w-[200px] whitespace-normal">
                                            <span class="font-semibold">Catatan:</span> <?= htmlspecialchars($w['admin_notes']); ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <button onclick='openWithdrawStatusModal(<?= json_encode($w); ?>)' class="inline-flex items-center px-3 py-1.5 bg-black hover:bg-gray-800 text-white text-xs font-bold rounded-lg transition-all">
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
</div>

<!-- Modal: Update Refund Status -->
<div id="refundStatusModal" class="fixed inset-0 z-50 overflow-y-auto hidden flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm transition-all duration-300">
    <div id="refundStatusModalContent" class="bg-white rounded-3xl max-w-md w-full p-6 shadow-xl transform scale-95 opacity-0 transition-all duration-300 border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900">Update Status Refund</h3>
            <button onclick="closeRefundStatusModal()" class="text-gray-400 hover:text-black transition">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        
        <form action="<?= BASEURL; ?>/admin/update_refund_status" method="POST" class="space-y-4">
            <input type="hidden" id="refund_id" name="id">
            
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Invoice Pesanan</label>
                <div id="refund_invoice" class="font-bold text-gray-900 text-sm"></div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Total Dana Refund</label>
                <div id="refund_amount" class="font-bold text-primary text-base"></div>
            </div>

            <div>
                <label for="refund_status" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Pilih Status Baru</label>
                <select id="refund_status" name="status" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-1 focus:ring-black focus:border-black outline-none text-sm bg-gray-50">
                    <option value="pending">Pending</option>
                    <option value="under_review">Sedang Ditinjau (Under Review)</option>
                    <option value="approved">Disetujui (Approved & Kredit Saldo)</option>
                    <option value="rejected">Ditolak (Rejected)</option>
                </select>
            </div>

            <div>
                <label for="refund_notes" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Catatan / Keterangan Pelacakan</label>
                <textarea id="refund_notes" name="admin_notes" rows="4" placeholder="Tuliskan info pelacakan terbaru (misal: Bukti barang rusak valid. Dana disetujui)..." class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-1 focus:ring-black focus:border-black outline-none text-sm bg-gray-50 resize-none"></textarea>
            </div>

            <div class="flex justify-end space-x-3 pt-4">
                <button type="button" onclick="closeRefundStatusModal()" class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-semibold rounded-full hover:bg-gray-50 transition">Batal</button>
                <button type="submit" class="px-5 py-2 bg-primary hover:bg-sky-700 text-white text-sm font-semibold rounded-full shadow transition">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Update Withdrawal Status -->
<div id="withdrawStatusModal" class="fixed inset-0 z-50 overflow-y-auto hidden flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm transition-all duration-300">
    <div id="withdrawStatusModalContent" class="bg-white rounded-3xl max-w-md w-full p-6 shadow-xl transform scale-95 opacity-0 transition-all duration-300 border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900">Update Status Penarikan Dana</h3>
            <button onclick="closeWithdrawStatusModal()" class="text-gray-400 hover:text-black transition">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        
        <form action="<?= BASEURL; ?>/admin/update_withdrawal_status" method="POST" class="space-y-4">
            <input type="hidden" id="withdraw_id" name="id">
            
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Pelanggan</label>
                <div id="withdraw_user" class="font-bold text-gray-900 text-sm"></div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Jumlah Penarikan</label>
                <div id="withdraw_amount" class="font-bold text-primary text-base"></div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Tujuan Rekening</label>
                <div id="withdraw_bank_details" class="text-sm text-gray-800 font-mono"></div>
            </div>

            <div>
                <label for="withdraw_status" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Pilih Status Baru</label>
                <select id="withdraw_status" name="status" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-1 focus:ring-black focus:border-black outline-none text-sm bg-gray-50">
                    <option value="pending">Pending</option>
                    <option value="processing">Sedang Diproses (Processing)</option>
                    <option value="completed">Selesai / Transfer Berhasil (Completed)</option>
                    <option value="failed">Gagal (Failed - Mengembalikan Saldo)</option>
                </select>
            </div>

            <div>
                <label for="withdraw_notes" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Catatan / Bukti Transfer</label>
                <textarea id="withdraw_notes" name="admin_notes" rows="4" placeholder="Tuliskan nomor referensi transfer bank, atau info alasan penarikan gagal..." class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-1 focus:ring-black focus:border-black outline-none text-sm bg-gray-50 resize-none"></textarea>
            </div>

            <div class="flex justify-end space-x-3 pt-4">
                <button type="button" onclick="closeWithdrawStatusModal()" class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-semibold rounded-full hover:bg-gray-50 transition">Batal</button>
                <button type="submit" class="px-5 py-2 bg-primary hover:bg-sky-700 text-white text-sm font-semibold rounded-full shadow transition">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function switchTab(tab) {
        const btnRefunds = document.getElementById('tab-btn-refunds');
        const btnWithdrawals = document.getElementById('tab-btn-withdrawals');
        const contentRefunds = document.getElementById('tab-content-refunds');
        const contentWithdrawals = document.getElementById('tab-content-withdrawals');

        if (tab === 'refunds') {
            btnRefunds.classList.add('border-primary', 'text-primary');
            btnRefunds.classList.remove('border-transparent', 'text-gray-500');
            btnWithdrawals.classList.add('border-transparent', 'text-gray-500');
            btnWithdrawals.classList.remove('border-primary', 'text-primary');

            contentRefunds.classList.remove('hidden');
            contentWithdrawals.classList.add('hidden');
        } else {
            btnWithdrawals.classList.add('border-primary', 'text-primary');
            btnWithdrawals.classList.remove('border-transparent', 'text-gray-500');
            btnRefunds.classList.add('border-transparent', 'text-gray-500');
            btnRefunds.classList.remove('border-primary', 'text-primary');

            contentWithdrawals.classList.remove('hidden');
            contentRefunds.classList.add('hidden');
        }
    }

    // Refund Modal Functions
    const refundStatusModal = document.getElementById('refundStatusModal');
    const refundStatusModalContent = document.getElementById('refundStatusModalContent');

    function openRefundStatusModal(refund) {
        document.getElementById('refund_id').value = refund.id;
        document.getElementById('refund_invoice').innerText = refund.invoice;
        document.getElementById('refund_amount').innerText = 'Rp ' + Number(refund.amount).toLocaleString('id-ID');
        document.getElementById('refund_status').value = refund.status;
        document.getElementById('refund_notes').value = refund.admin_notes || '';

        refundStatusModal.classList.remove('hidden');
        setTimeout(() => {
            refundStatusModalContent.classList.remove('scale-95', 'opacity-0');
            refundStatusModalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeRefundStatusModal() {
        refundStatusModalContent.classList.remove('scale-100', 'opacity-100');
        refundStatusModalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            refundStatusModal.classList.add('hidden');
        }, 300);
    }

    // Withdraw Modal Functions
    const withdrawStatusModal = document.getElementById('withdrawStatusModal');
    const withdrawStatusModalContent = document.getElementById('withdrawStatusModalContent');

    function openWithdrawStatusModal(w) {
        document.getElementById('withdraw_id').value = w.id;
        document.getElementById('withdraw_user').innerText = w.username + ' (' + w.email + ')';
        document.getElementById('withdraw_amount').innerText = 'Rp ' + Number(w.amount).toLocaleString('id-ID');
        document.getElementById('withdraw_bank_details').innerText = w.bank_name + ' - ' + w.account_number + ' (a/n ' + w.account_holder + ')';
        document.getElementById('withdraw_status').value = w.status;
        document.getElementById('withdraw_notes').value = w.admin_notes || '';

        withdrawStatusModal.classList.remove('hidden');
        setTimeout(() => {
            withdrawStatusModalContent.classList.remove('scale-95', 'opacity-0');
            withdrawStatusModalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeWithdrawStatusModal() {
        withdrawStatusModalContent.classList.remove('scale-100', 'opacity-100');
        withdrawStatusModalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            withdrawStatusModal.classList.add('hidden');
        }, 300);
    }
</script>

<?php require_once __DIR__ . '/../../layout/admin_footer.php'; ?>
