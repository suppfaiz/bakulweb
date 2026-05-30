<?php require_once __DIR__ . '/../../layout/header.php'; ?>

<div class="bg-white dark:bg-darkbg min-h-screen pt-24 pb-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto bg-white dark:bg-darkcard border border-gray-200 dark:border-gray-800 rounded-3xl p-6 sm:p-10 shadow-sm">
        
        <!-- Header -->
        <div class="border-b border-gray-100 dark:border-gray-800 pb-6 mb-8 text-center sm:text-left">
            <h1 class="text-2xl font-black text-gray-900 dark:text-white sm:text-3xl">Kebijakan Pengembalian Dana (Refund)</h1>
            <p class="text-xs text-gray-400 mt-1">Terakhir Diperbarui: 27 Mei 2026</p>
        </div>

        <!-- Highlight Box: Garansi 1 Bulan -->
        <div class="bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/30 rounded-2xl p-6 mb-8 flex gap-4">
            <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 rounded-2xl flex items-center justify-center flex-shrink-0">
                <i class="fas fa-shield-check text-2xl"></i>
            </div>
            <div>
                <h3 class="font-extrabold text-emerald-900 dark:text-emerald-300 text-sm mb-1.5">Jaminan Garansi Perlindungan 1 Bulan (30 Hari)</h3>
                <p class="text-xs text-emerald-700 dark:text-emerald-400 leading-relaxed">
                    Kami berkomitmen menjaga kualitas produk gadget yang Anda beli. BAKUL memberikan jaminan pengembalian dana penuh (100% Refund) atau penggantian unit jika barang mengalami <strong>kegagalan fungsi teknis bawaan dan bukan disebabkan oleh kesalahan/kelalaian pengguna</strong> selama 1 bulan sejak pesanan Anda diterima.
                </p>
            </div>
        </div>

        <!-- Content Details -->
        <div class="space-y-8 text-sm text-gray-600 dark:text-gray-300 leading-relaxed font-sans">
            
            <!-- What is Covered -->
            <section class="bg-gray-50 dark:bg-gray-900/30 border border-gray-100 dark:border-gray-800 rounded-2xl p-5">
                <h2 class="text-sm font-extrabold text-emerald-650 dark:text-emerald-400 mb-3 flex items-center gap-2">
                    <i class="fas fa-check-circle"></i> Yang Berhak Mendapatkan Klaim Refund/Garansi
                </h2>
                <p class="text-xs text-gray-550 dark:text-gray-450 mb-3">
                    Klaim disetujui apabila produk mengalami kegagalan hardware/software bawaan pabrik selama pemakaian wajar, contohnya:
                </p>
                <ul class="list-disc pl-5 space-y-1.5 text-xs text-gray-600 dark:text-gray-450">
                    <li>Kerusakan layar sentuh (touchscreen) tidak merespons atau bergaris tanpa bekas benturan/jatuh.</li>
                    <li>Kerusakan baterai yang drop ekstrim atau tidak bisa diisi daya sama sekali.</li>
                    <li>Kegagalan sistem sirkuit internal (IC Power, IC Audio, IC Wifi) yang mati mendadak saat pengisian daya standar.</li>
                    <li>Kamera utama atau depan tidak dapat terbuka, buram karena cacat lensa dalam, atau sensor gagal.</li>
                    <li>Sinyal jaringan seluler terblokir bawaan sejak awal (IMEI terblokir).</li>
                </ul>
            </section>

            <!-- What is NOT Covered -->
            <section class="bg-gray-50 dark:bg-gray-900/30 border border-gray-100 dark:border-gray-800 rounded-2xl p-5">
                <h2 class="text-sm font-extrabold text-red-600 dark:text-red-400 mb-3 flex items-center gap-2">
                    <i class="fas fa-times-circle"></i> Yang TIDAK Berhak Mendapatkan Klaim (Pengecualian)
                </h2>
                <p class="text-xs text-gray-550 dark:text-gray-450 mb-3">
                    Klaim akan otomatis ditolak jika tim verifikasi kami mendeteksi kerusakan akibat kelalaian pembeli (User Error):
                </p>
                <ul class="list-disc pl-5 space-y-1.5 text-xs text-gray-600 dark:text-gray-450">
                    <li>Layar pecah, bodi retak, atau penyok karena jatuh atau terbentur benda keras.</li>
                    <li>Kerusakan komponen akibat terkena air, cairan kimia, atau kelembapan tinggi (indikator air berubah warna).</li>
                    <li>Segel garansi pabrik atau segel keaslian unit BAKUL telah rusak atau robek.</li>
                    <li>Perubahan sistem operasi bawaan seperti pembongkaran OS, modifikasi root (SuperSU/Magisk), custom ROM, atau bootloader unlocked.</li>
                    <li>Kerusakan akibat penggunaan aksesoris charger/kabel tidak standar atau tidak original (overvoltage).</li>
                </ul>
            </section>

            <!-- Refund Steps -->
            <section>
                <h2 class="text-base font-extrabold text-gray-900 dark:text-white mb-4">Langkah-Langkah Mengajukan Klaim Refund</h2>
                <div class="relative pl-6 border-l-2 border-gray-200 dark:border-gray-800 space-y-6">
                    
                    <!-- Step 1 -->
                    <div class="relative">
                        <span class="absolute -left-[31px] top-0 w-4.5 h-4.5 rounded-full bg-black dark:bg-white text-white dark:text-black flex items-center justify-center text-[10px] font-black">1</span>
                        <h4 class="font-bold text-gray-900 dark:text-white text-sm">Hubungi Customer Support</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            Hubungi kami via WhatsApp dengan menyertakan nomor Invoice dan sertakan **video penjelasan kendala** pada produk gadget Anda.
                        </p>
                    </div>

                    <!-- Step 2 -->
                    <div class="relative">
                        <span class="absolute -left-[31px] top-0 w-4.5 h-4.5 rounded-full bg-black dark:bg-white text-white dark:text-black flex items-center justify-center text-[10px] font-black">2</span>
                        <h4 class="font-bold text-gray-900 dark:text-white text-sm">Kirim Barang atau Ketemuan (COD)</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            Untuk wilayah COD, koordinasikan pertemuan dengan admin. Untuk pengiriman luar kota, kirimkan kembali perangkat lengkap beserta box aslinya ke alamat servis kami.
                        </p>
                    </div>

                    <!-- Step 3 -->
                    <div class="relative">
                        <span class="absolute -left-[31px] top-0 w-4.5 h-4.5 rounded-full bg-black dark:bg-white text-white dark:text-black flex items-center justify-center text-[10px] font-black">3</span>
                        <h4 class="font-bold text-gray-900 dark:text-white text-sm">Pengecekan dan Verifikasi Fisik</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            Teknisi kami akan memeriksa kondisi segel, kemungkinan tanda jatuh/cairan, dan memvalidasi jenis kegagalan fungsi teknis dalam waktu 1-2 hari kerja.
                        </p>
                    </div>

                    <!-- Step 4 -->
                    <div class="relative">
                        <span class="absolute -left-[31px] top-0 w-4.5 h-4.5 rounded-full bg-black dark:bg-white text-white dark:text-black flex items-center justify-center text-[10px] font-black">4</span>
                        <h4 class="font-bold text-gray-900 dark:text-white text-sm">Refund Dana atau Penggantian Unit</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            Jika kendala terbukti murni kegagalan sistem bawaan, dana pembelian Anda akan ditransfer balik 100% penuh atau diganti dengan unit baru yang normal (sesuai kesepakatan).
                        </p>
                    </div>

                </div>
            </section>

            <!-- Form Klaim Garansi -->
            <section class="bg-white dark:bg-darkcard border border-gray-250 dark:border-gray-800 rounded-3xl p-6 sm:p-8 shadow-sm">
                <h2 class="text-lg font-black text-gray-900 dark:text-white mb-2 flex items-center gap-2">
                    <i class="fas fa-file-signature text-emerald-500"></i> Formulir Klaim Garansi Online
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-6 leading-relaxed">
                    Punya kendala teknis dalam masa 1 bulan pertama? Ajukan klaim garansi murni kegagalan perangkat Anda di bawah ini secara cepat.
                </p>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <form action="<?= BASEURL; ?>/help/submit_claim" method="POST" enctype="multipart/form-data" class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">Nomor Invoice <span class="text-red-500">*</span></label>
                                <input type="text" name="invoice" required placeholder="Contoh: INV-20260527-ABCDE"
                                    class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-850 rounded-xl text-sm focus:ring-2 focus:ring-black focus:border-black outline-none transition dark:text-white">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">Nama Perangkat / Gadget <span class="text-red-500">*</span></label>
                                <input type="text" name="device_name" required placeholder="Contoh: iPhone 13 Pro Max"
                                    class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-850 rounded-xl text-sm focus:ring-2 focus:ring-black focus:border-black outline-none transition dark:text-white">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">Sebab / Kendala Kerusakan <span class="text-red-500">*</span></label>
                            <textarea name="reason" rows="3" required placeholder="Jelaskan secara rinci kendala/kerusakan teknis murni yang dialami perangkat Anda..."
                                class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-850 rounded-xl text-sm focus:ring-2 focus:ring-black focus:border-black outline-none transition resize-none dark:text-white"></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">Lampiran Bukti Foto/Video <span class="text-red-500">*</span></label>
                            <div class="relative border-2 border-dashed border-gray-300 dark:border-gray-700 hover:border-gray-400 dark:hover:border-gray-600 rounded-xl p-6 transition flex flex-col items-center justify-center bg-gray-50/50 dark:bg-gray-800/10 cursor-pointer">
                                <input type="file" name="attachment" required accept="image/*,video/*"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                    onchange="displayFileName(this)">
                                <div class="text-center pointer-events-none" id="uploadPlaceholder">
                                    <i class="fas fa-cloud-upload-alt text-gray-400 text-2xl mb-2"></i>
                                    <p class="text-xs font-bold text-gray-700 dark:text-gray-300">Klik atau seret file bukti di sini</p>
                                    <p class="text-[10px] text-gray-400 mt-1">Format gambar (JPG, PNG) atau video (MP4, MOV)</p>
                                </div>
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="w-full bg-black dark:bg-white text-white dark:text-black py-3.5 rounded-xl font-bold text-sm hover:bg-gray-850 dark:hover:bg-gray-100 transition shadow-md flex items-center justify-center gap-2">
                                <i class="fas fa-paper-plane text-xs"></i> Kirim Pengajuan Klaim
                            </button>
                        </div>
                    </form>
                <?php else: ?>
                    <div class="bg-gray-50 dark:bg-gray-900/30 rounded-2xl p-6 text-center border border-gray-150 dark:border-gray-800">
                        <i class="fas fa-lock text-gray-350 text-2xl mb-2"></i>
                        <p class="text-xs text-gray-600 dark:text-gray-400 font-medium mb-3">Anda harus masuk (login) terlebih dahulu untuk mengisi formulir klaim garansi online.</p>
                        <a href="<?= BASEURL; ?>/auth/login" class="inline-block px-5 py-2 bg-black dark:bg-white text-white dark:text-black rounded-lg font-bold text-xs hover:bg-gray-850 dark:hover:bg-gray-100 transition shadow-sm">
                            Login / Masuk Akun
                        </a>
                    </div>
                <?php endif; ?>
            </section>

        </div>

        <!-- Contact Support CTA -->
        <div class="mt-10 pt-6 border-t border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row justify-between items-center gap-4">
            <a href="https://wa.me/628123456789?text=Halo%2520BAKUL,%2520saya%2520ingin%2520mengajukan%2520klaim%2520garansi%25201%252520bulan" target="_blank" 
               class="px-5 py-3 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl font-bold text-xs transition shadow-sm flex items-center gap-2 w-full sm:w-auto justify-center">
                <i class="fab fa-whatsapp text-sm"></i> Ajukan Klaim Sekarang (WhatsApp)
            </a>
            <a href="<?= BASEURL; ?>" class="px-6 py-3 border border-gray-200 text-gray-600 dark:border-gray-800 dark:text-gray-400 rounded-xl font-bold text-xs hover:bg-gray-50 dark:hover:bg-gray-800 transition w-full sm:w-auto text-center">
                Kembali ke Beranda
            </a>
        </div>

    </div>
</div>

<script>
function displayFileName(input) {
    const placeholder = document.getElementById('uploadPlaceholder');
    if (input.files && input.files[0]) {
        const file = input.files[0];
        placeholder.innerHTML = `
            <i class="fas fa-file-alt text-emerald-500 text-2xl mb-2 animate-bounce"></i>
            <p class="text-xs font-bold text-emerald-600 dark:text-emerald-400">File terpilih: ${file.name}</p>
            <p class="text-[10px] text-gray-400 mt-1">${(file.size / (1024 * 1024)).toFixed(2)} MB</p>
        `;
    }
}
</script>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>
