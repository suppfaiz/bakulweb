<?php require_once __DIR__ . '/../../layout/header.php'; ?>

<div class="bg-white min-h-screen pt-24 pb-16 px-4 sm:px-6 lg:px-8 flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-3xl border border-gray-250 shadow-xl overflow-hidden p-8 text-center">
        
        <!-- Animated Handshake Icon -->
        <div class="mx-auto w-20 h-20 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center mb-6 animate-bounce">
            <i class="fas fa-handshake text-3xl"></i>
        </div>

        <h1 class="text-2xl font-black text-gray-900 mb-2">Pesanan COD Berhasil!</h1>
        <p class="text-sm text-gray-500 mb-6 font-mono">Invoice: <?= htmlspecialchars($data['invoice']) ?></p>

        <!-- COD Info Card -->
        <div class="bg-gray-50 rounded-2xl p-5 mb-8 text-left border border-gray-200">
            <h3 class="font-bold text-xs text-gray-400 uppercase tracking-wider mb-3">Langkah Selanjutnya</h3>
            <ul class="space-y-3 text-xs text-gray-600 leading-relaxed">
                <li class="flex items-start gap-2.5">
                    <span class="w-5 h-5 bg-black text-white rounded-full flex items-center justify-center text-[10px] font-bold flex-shrink-0 mt-0.5">1</span>
                    <span>Penjual akan meninjau ketersediaan stok barang pesanan Anda.</span>
                </li>
                <li class="flex items-start gap-2.5">
                    <span class="w-5 h-5 bg-black text-white rounded-full flex items-center justify-center text-[10px] font-bold flex-shrink-0 mt-0.5">2</span>
                    <span>Penjual akan menghubungi Anda lewat nomor HP/WhatsApp yang terdaftar (<strong><?= htmlspecialchars($data['order']['recipient_phone']) ?></strong>) untuk menyepakati <strong>waktu & lokasi ketemuan</strong>.</span>
                </li>
                <li class="flex items-start gap-2.5">
                    <span class="w-5 h-5 bg-black text-white rounded-full flex items-center justify-center text-[10px] font-bold flex-shrink-0 mt-0.5">3</span>
                    <span>Anda melakukan pembayaran tunai langsung di tempat setelah barang diperiksa dan diterima.</span>
                </li>
            </ul>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col gap-3">
            <a href="https://wa.me/6289631090661?text=Halo%20BAKUL%2C%20saya%20ingin%20koordinasi%20untuk%20COD%20pesanan%20invoice%20<?= htmlspecialchars($data['invoice']) ?>" 
               target="_blank"
               class="w-full bg-emerald-500 text-white py-3.5 rounded-xl font-bold text-sm hover:bg-emerald-600 transition shadow-md flex items-center justify-center gap-2">
                <i class="fab fa-whatsapp text-base"></i> Hubungi Penjual (WhatsApp)
            </a>
            <a href="<?= BASEURL ?>/account?tab=orders" 
               class="w-full bg-black text-white py-3.5 rounded-xl font-bold text-sm hover:bg-gray-800 transition shadow-sm flex items-center justify-center gap-2">
                <i class="fas fa-shopping-bag text-xs"></i> Lacak Status Pesanan
            </a>
            <a href="<?= BASEURL ?>/catalog" 
               class="w-full border-2 border-gray-200 text-gray-600 py-3.5 rounded-xl font-bold text-sm hover:border-gray-400 hover:text-black transition">
                Kembali Belanja
            </a>
        </div>

    </div>
</div>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>
