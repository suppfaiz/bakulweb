<?php require_once __DIR__ . '/../../layout/header.php'; ?>

<div class="bg-white dark:bg-darkbg min-h-screen pt-24 pb-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto bg-white dark:bg-darkcard border border-gray-200 dark:border-gray-800 rounded-3xl p-6 sm:p-10 shadow-sm">
        
        <!-- Header -->
        <div class="border-b border-gray-100 dark:border-gray-800 pb-6 mb-8">
            <h1 class="text-2xl font-black text-gray-900 dark:text-white sm:text-3xl">Kebijakan Privasi</h1>
            <p class="text-xs text-gray-400 mt-1">Terakhir Diperbarui: 27 Mei 2026</p>
        </div>

        <!-- Content -->
        <div class="space-y-6 text-sm text-gray-600 dark:text-gray-300 leading-relaxed font-sans">
            
            <p>
                BAKUL berkomitmen penuh untuk melindungi privasi setiap pengguna situs dan layanan kami. Kebijakan Privasi ini menerangkan bagaimana kami mengumpulkan, menyimpan, memproses, dan membagikan data pribadi Anda sewaktu bertransaksi di platform kami.
            </p>

            <section>
                <h2 class="text-base font-extrabold text-gray-900 dark:text-white mb-2.5">1. Informasi Yang Kami Kumpulkan</h2>
                <p class="mb-2">
                    Kami mengumpulkan informasi yang Anda berikan secara langsung saat berinteraksi di platform kami, meliputi:
                </p>
                <ul class="list-disc pl-5 space-y-1.5">
                    <li>Informasi pendaftaran akun: Nama lengkap, email, nomor HP/WhatsApp, kata sandi, dan alamat pengiriman.</li>
                    <li>Informasi transaksi: Detail produk yang dibeli, nominal belanja, serta pilihan metode pembayaran dan kurir logistik.</li>
                    <li>Informasi ulasan: Isi komentar, ulasan produk, dan rating yang Anda bagikan secara publik di halaman detail katalog.</li>
                </ul>
            </section>

            <section>
                <h2 class="text-base font-extrabold text-gray-900 dark:text-white mb-2.5">2. Penggunaan Informasi Anda</h2>
                <p class="mb-2">
                    Data pribadi yang kami kumpulkan digunakan untuk kepentingan berikut:
                </p>
                <ul class="list-disc pl-5 space-y-1.5">
                    <li>Memproses pemesanan Anda, memverifikasi pembayaran online, dan mendukung koordinasi COD.</li>
                    <li>Mengirimkan notifikasi riwayat status pesanan secara dinamis di panel notifikasi akun Anda.</li>
                    <li>Meningkatkan pengalaman berbelanja serta performa server website BAKUL.</li>
                    <li>Menyediakan layanan dukungan pelanggan (Customer Support) saat Anda membutuhkan bantuan.</li>
                </ul>
            </section>

            <section>
                <h2 class="text-base font-extrabold text-gray-900 dark:text-white mb-2.5">3. Perlindungan & Keamanan Data</h2>
                <p>
                    Kami mengimplementasikan enkripsi standar industri (SSL/TLS) untuk mengamankan komunikasi data antara perangkat Anda dengan server kami. Semua data transaksi finansial diproses melalui integrasi gateway pembayaran pihak ketiga (Midtrans) yang tersertifikasi kepatuhan PCI-DSS. BAKUL tidak menyimpan informasi kartu kredit atau detail perbankan pribadi Anda secara mentah di server kami.
                </p>
            </section>

            <section>
                <h2 class="text-base font-extrabold text-gray-900 dark:text-white mb-2.5">4. Penyebaran Informasi ke Pihak Ketiga</h2>
                <p>
                    Kami menjamin tidak akan menyewakan, menjual, atau memperdagangkan informasi data pribadi pengguna kepada pihak ketiga mana pun tanpa persetujuan Anda. Informasi pengiriman (nama, nomor HP, alamat) hanya akan kami berikan ke pihak kurir ekspedisi logistik yang Anda pilih saat checkout demi ketepatan pengantaran barang belanjaan Anda.
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
