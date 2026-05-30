<?php require_once __DIR__ . '/../../layout/header.php'; ?>

<div class="bg-white dark:bg-darkbg min-h-screen pt-24 pb-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto bg-white dark:bg-darkcard border border-gray-200 dark:border-gray-800 rounded-3xl p-6 sm:p-10 shadow-sm">
        
        <!-- Header -->
        <div class="border-b border-gray-100 dark:border-gray-800 pb-6 mb-8">
            <h1 class="text-2xl font-black text-gray-900 dark:text-white sm:text-3xl">Syarat & Ketentuan</h1>
            <p class="text-xs text-gray-400 mt-1">Terakhir Diperbarui: 27 Mei 2026</p>
        </div>

        <!-- Content -->
        <div class="space-y-6 text-sm text-gray-600 dark:text-gray-300 leading-relaxed font-sans">
            
            <section>
                <h2 class="text-base font-extrabold text-gray-900 dark:text-white mb-2.5">1. Ketentuan Umum</h2>
                <p>
                    Selamat datang di BAKUL Enterprise. Dengan mengakses dan menggunakan situs web kami, Anda dianggap telah memahami dan menyetujui seluruh ketentuan yang tertulis di halaman ini. Jika Anda tidak menyetujui salah satu poin dari ketentuan ini, Anda dipersilakan untuk tidak melanjutkan penggunaan layanan kami.
                </p>
            </section>

            <section>
                <h2 class="text-base font-extrabold text-gray-900 dark:text-white mb-2.5">2. Akun Pengguna</h2>
                <ul class="list-disc pl-5 space-y-1.5">
                    <li>Pendaftaran akun wajib menggunakan data asli yang dapat dipertanggungjawabkan (email aktif, nomor HP aktif).</li>
                    <li>Keamanan kredensial kata sandi sepenuhnya menjadi tanggung jawab masing-masing pengguna. BAKUL tidak bertanggung jawab atas kerugian akibat kelalaian pembagian informasi akun ke pihak ketiga.</li>
                    <li>BAKUL berhak menangguhkan atau menghapus akun pengguna yang terindikasi melakukan penipuan, spam, atau manipulasi sistem transaksi.</li>
                </ul>
            </section>

            <section>
                <h2 class="text-base font-extrabold text-gray-900 dark:text-white mb-2.5">3. Transaksi & Pembayaran</h2>
                <p class="mb-2">
                    BAKUL menawarkan dua opsi utama metode transaksi:
                </p>
                <ul class="list-disc pl-5 space-y-1.5">
                    <li><strong>Transfer Online / E-Wallet</strong>: Dilakukan secara instan dan aman melalui Payment Gateway Midtrans. Pesanan baru akan diproses kirim setelah pembayaran terverifikasi lunas.</li>
                    <li><strong>Cash on Delivery (COD) / Ketemuan</strong>: Transaksi pertemuan fisik langsung antara penjual dan pembeli. Pembeli wajib mencantumkan nomor HP/WhatsApp yang valid untuk mempermudah koordinasi lokasi dan waktu pertemuan.</li>
                </ul>
            </section>

            <section>
                <h2 class="text-base font-extrabold text-gray-900 dark:text-white mb-2.5">4. Ketentuan COD (Meetup)</h2>
                <ul class="list-disc pl-5 space-y-1.5">
                    <li>Pembeli diharapkan memilih tempat pertemuan yang aman dan publik untuk menghindari hal-hal yang tidak diinginkan.</li>
                    <li>Pembeli berhak memeriksa kondisi fisik dan fungsionalitas produk terlebih dahulu saat bertemu sebelum melakukan pembayaran.</li>
                    <li>Pembayaran wajib dilakukan secara tunai langsung di tempat setelah produk disepakati dalam kondisi sesuai deskripsi.</li>
                    <li>Pembatalan transaksi COD saat sudah bertemu harus dilandasi alasan logis (misal: kondisi barang tidak sesuai deskripsi katalog). Pembatalan sepihak berulang tanpa alasan yang jelas akan berakibat pada penangguhan fitur COD akun terkait.</li>
                </ul>
            </section>

            <section>
                <h2 class="text-base font-extrabold text-gray-900 dark:text-white mb-2.5">5. Perubahan Layanan</h2>
                <p>
                    BAKUL berhak melakukan pembaruan, penyesuaian harga produk, penggantian stok, atau modifikasi syarat & ketentuan ini kapan pun tanpa pemberitahuan tertulis sebelumnya kepada pengguna. Perubahan akan langsung efektif setelah diunggah di situs resmi kami.
                </p>
            </section>

        </div>

        <!-- Back Button -->
        <div class="mt-10 pt-6 border-t border-gray-100 dark:border-gray-800 flex justify-end">
            <a href="<?= BASEURL; ?>" class="px-6 py-2.5 bg-black dark:bg-white text-white dark:text-black rounded-xl font-bold text-xs hover:bg-gray-850 dark:hover:bg-gray-100 transition shadow-sm">
                Kembali ke Beranda
            </a>
        </div>

    </div>
</div>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>
