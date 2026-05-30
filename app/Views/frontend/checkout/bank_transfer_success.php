<?php require_once __DIR__ . '/../../layout/header.php'; ?>

<div class="bg-white min-h-screen pt-24 pb-16 px-4 sm:px-6 lg:px-8 flex items-start justify-center">
    <div class="max-w-lg w-full space-y-4">

        <!-- Success Header Card -->
        <div class="bg-white rounded-3xl border border-gray-200 shadow-xl overflow-hidden">
            <div class="bg-gradient-to-br from-orange-500 to-amber-500 p-8 text-center text-white">
                <div class="mx-auto w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-university text-3xl text-white"></i>
                </div>
                <h1 class="text-2xl font-black mb-1">Pesanan Berhasil Dibuat!</h1>
                <p class="text-orange-100 text-sm">Selesaikan pembayaran transfer agar pesanan diproses</p>
            </div>

            <div class="px-6 py-4 bg-orange-50 border-b border-orange-100 text-center">
                <p class="text-xs text-orange-600 font-semibold uppercase tracking-wider">Nomor Invoice</p>
                <p class="text-xl font-black text-gray-900 font-mono"><?= htmlspecialchars($data['invoice']) ?></p>
            </div>

            <!-- Total Amount -->
            <div class="px-6 py-5 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500 font-semibold">Total yang harus ditransfer:</span>
                    <span class="text-2xl font-black text-orange-600">
                        Rp <?= number_format($data['order']['total_amount'], 0, ',', '.') ?>
                    </span>
                </div>
                <p class="text-xs text-gray-400 mt-1">
                    <i class="fas fa-info-circle mr-1"></i>
                    Termasuk ongkos kirim via <?= htmlspecialchars($data['order']['courier'] ?? '-') ?>
                </p>
            </div>

            <!-- BNI Account Details -->
            <div class="p-6">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">
                    <i class="fas fa-university mr-1"></i> Detail Rekening Tujuan
                </h3>
                <div class="bg-gradient-to-br from-orange-50 to-amber-50 rounded-2xl border border-orange-200 p-5 space-y-4">

                    <!-- Bank Name -->
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Bank</span>
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 bg-orange-500 rounded-md flex items-center justify-center">
                                <i class="fas fa-university text-white text-xs"></i>
                            </span>
                            <span class="font-black text-gray-900">BNI</span>
                        </div>
                    </div>

                    <!-- Account Number -->
                    <div class="flex items-center justify-between border-t border-orange-100 pt-4">
                        <span class="text-sm text-gray-500">No. Rekening</span>
                        <div class="flex items-center gap-3">
                            <span id="norek" class="text-2xl font-black text-gray-900 tracking-widest font-mono">0231090661</span>
                            <button onclick="copyNoRek()" 
                                class="flex items-center gap-1.5 bg-orange-100 hover:bg-orange-200 text-orange-700 px-3 py-1.5 rounded-xl text-xs font-bold transition">
                                <i class="fas fa-copy"></i> Salin
                            </button>
                        </div>
                    </div>

                    <!-- Account Holder -->
                    <div class="flex items-center justify-between border-t border-orange-100 pt-4">
                        <span class="text-sm text-gray-500">Atas Nama</span>
                        <span class="font-black text-gray-900">SEPTIAN FAIZ WITANA</span>
                    </div>
                </div>

                <!-- Important Note -->
                <div class="mt-4 bg-amber-50 border border-amber-200 rounded-xl p-4 flex gap-3">
                    <i class="fas fa-exclamation-triangle text-amber-500 mt-0.5 flex-shrink-0"></i>
                    <div class="text-xs text-amber-800 leading-relaxed">
                        <strong>Penting:</strong> Transfer <em>tepat</em> sebesar <strong>Rp <?= number_format($data['order']['total_amount'], 0, ',', '.') ?></strong> agar mudah diverifikasi. Sertakan nomor invoice <strong><?= htmlspecialchars($data['invoice']) ?></strong> dalam keterangan transfer.
                    </div>
                </div>
            </div>
        </div>

        <!-- Steps Card -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
            <h3 class="font-bold text-xs text-gray-400 uppercase tracking-wider mb-4">Langkah Selanjutnya</h3>
            <ul class="space-y-4">
                <li class="flex items-start gap-3">
                    <span class="w-7 h-7 bg-orange-500 text-white rounded-full flex items-center justify-center text-xs font-black flex-shrink-0 mt-0.5">1</span>
                    <div>
                        <p class="text-sm font-bold text-gray-900">Lakukan Transfer BNI</p>
                        <p class="text-xs text-gray-500 mt-0.5">Transfer sejumlah <strong>Rp <?= number_format($data['order']['total_amount'], 0, ',', '.') ?></strong> ke rekening BNI <strong>0231090661</strong> a/n Septian Faiz Witana.</p>
                    </div>
                </li>
                <li class="flex items-start gap-3">
                    <span class="w-7 h-7 bg-orange-500 text-white rounded-full flex items-center justify-center text-xs font-black flex-shrink-0 mt-0.5">2</span>
                    <div>
                        <p class="text-sm font-bold text-gray-900">Kirim Bukti Transfer</p>
                        <p class="text-xs text-gray-500 mt-0.5">Kirim screenshot/foto bukti transfer via WhatsApp dengan menyebutkan nomor invoice <strong><?= htmlspecialchars($data['invoice']) ?></strong>.</p>
                    </div>
                </li>
                <li class="flex items-start gap-3">
                    <span class="w-7 h-7 bg-orange-500 text-white rounded-full flex items-center justify-center text-xs font-black flex-shrink-0 mt-0.5">3</span>
                    <div>
                        <p class="text-sm font-bold text-gray-900">Pesanan Diproses</p>
                        <p class="text-xs text-gray-500 mt-0.5">Setelah pembayaran diverifikasi oleh penjual, pesanan Anda akan segera dikemas dan dikirim.</p>
                    </div>
                </li>
            </ul>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col gap-3">
            <?php
                $wa_msg = urlencode("Halo BAKUL, saya sudah transfer untuk pesanan invoice " . $data['invoice'] . " sebesar Rp " . number_format($data['order']['total_amount'], 0, ',', '.') . ". Mohon segera diverifikasi. Terima kasih!");
            ?>
            <a href="https://wa.me/6289631090661?text=<?= $wa_msg ?>" 
               target="_blank"
               class="w-full bg-emerald-500 text-white py-4 rounded-2xl font-black text-sm hover:bg-emerald-600 transition shadow-md flex items-center justify-center gap-2">
                <i class="fab fa-whatsapp text-lg"></i> Kirim Bukti Transfer (WhatsApp)
            </a>

            <a href="<?= BASEURL ?>/account?tab=orders" 
               class="w-full bg-black text-white py-4 rounded-2xl font-black text-sm hover:bg-gray-800 transition shadow-sm flex items-center justify-center gap-2">
                <i class="fas fa-shopping-bag text-xs"></i> Lacak Status Pesanan
            </a>

            <a href="<?= BASEURL ?>/catalog" 
               class="w-full border-2 border-gray-200 text-gray-600 py-4 rounded-2xl font-bold text-sm hover:border-gray-400 hover:text-black transition text-center">
                Kembali Belanja
            </a>
        </div>

    </div>
</div>

<script>
function copyNoRek() {
    navigator.clipboard.writeText('0231090661').then(() => {
        // Visual feedback
        const btn = event.currentTarget;
        btn.innerHTML = '<i class="fas fa-check"></i> Tersalin!';
        btn.classList.add('bg-green-100', 'text-green-700');
        btn.classList.remove('bg-orange-100', 'text-orange-700');
        setTimeout(() => {
            btn.innerHTML = '<i class="fas fa-copy"></i> Salin';
            btn.classList.remove('bg-green-100', 'text-green-700');
            btn.classList.add('bg-orange-100', 'text-orange-700');
        }, 2000);
    }).catch(() => {
        alert('Salin manual: 0231090661');
    });
}
</script>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>
