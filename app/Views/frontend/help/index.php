<?php require_once __DIR__ . '/../../layout/header.php'; ?>

<div class="bg-white dark:bg-darkbg min-h-screen pt-24 pb-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        
        <!-- Header Section -->
        <div class="text-center mb-12">
            <h1 class="text-3xl font-black text-gray-900 dark:text-white sm:text-4xl">Pusat Bantuan</h1>
            <p class="mt-3 text-sm text-gray-500 dark:text-gray-400">Punya pertanyaan seputar layanan BAKUL? Temukan jawabannya di bawah ini.</p>
        </div>

        <!-- Help Category Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-12">
            <div class="bg-white dark:bg-darkcard border border-gray-200 dark:border-gray-800 rounded-3xl p-6 shadow-sm hover:shadow-md transition">
                <div class="w-10 h-10 bg-black dark:bg-white text-white dark:text-black rounded-2xl flex items-center justify-center mb-4">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <h3 class="font-bold text-gray-900 dark:text-white text-base mb-2">Pemesanan & COD</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">Panduan lengkap berbelanja online dan tata cara COD / ketemuan langsung.</p>
            </div>
            <a href="<?= BASEURL; ?>/help/refund" class="bg-white dark:bg-darkcard border border-gray-200 dark:border-gray-800 rounded-3xl p-6 shadow-sm hover:shadow-md transition block">
                <div class="w-10 h-10 bg-black dark:bg-white text-white dark:text-black rounded-2xl flex items-center justify-center mb-4">
                    <i class="fas fa-shield-halved"></i>
                </div>
                <h3 class="font-bold text-gray-900 dark:text-white text-base mb-2">Garansi & Refund</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">Ketentuan garansi 1 bulan untuk kegagalan perangkat bawaan.</p>
            </a>
            <div class="bg-white dark:bg-darkcard border border-gray-200 dark:border-gray-800 rounded-3xl p-6 shadow-sm hover:shadow-md transition">
                <div class="w-10 h-10 bg-black dark:bg-white text-white dark:text-black rounded-2xl flex items-center justify-center mb-4">
                    <i class="fas fa-lock"></i>
                </div>
                <h3 class="font-bold text-gray-900 dark:text-white text-base mb-2">Akun & Keamanan</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">Mengelola profil, mengubah kata sandi, dan proteksi privasi data Anda.</p>
            </div>
        </div>

        <!-- FAQ Accordion -->
        <div class="bg-white dark:bg-darkcard border border-gray-200 dark:border-gray-800 rounded-3xl p-6 sm:p-8 shadow-sm">
            <h2 class="text-xl font-extrabold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                <i class="far fa-question-circle text-lg"></i> Pertanyaan Populer (FAQ)
            </h2>
            
            <div class="space-y-4 divide-y divide-gray-150 dark:divide-gray-800">
                
                <!-- FAQ 1 -->
                <div class="pt-4 first:pt-0">
                    <details class="group">
                        <summary class="flex justify-between items-center font-bold text-sm text-gray-900 dark:text-white cursor-pointer list-none">
                            <span>Bagaimana cara melakukan transaksi COD (Cash on Delivery)?</span>
                            <span class="transition group-open:rotate-180">
                                <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                            </span>
                        </summary>
                        <p class="mt-3 text-xs text-gray-600 dark:text-gray-400 leading-relaxed">
                            Di halaman checkout, pilih metode pembayaran <strong>COD (Bayar di Tempat)</strong>. Ongkos kirim otomatis diset menjadi Rp 0. Setelah Anda membuat pesanan, Penjual akan menghubungi Anda via WhatsApp untuk menyepakati lokasi dan waktu pertemuan. Anda dapat memeriksa kondisi fisik barang terlebih dahulu saat ketemuan sebelum membayar secara tunai.
                        </p>
                    </details>
                </div>

                <!-- FAQ 2 -->
                <div class="pt-4">
                    <details class="group">
                        <summary class="flex justify-between items-center font-bold text-sm text-gray-900 dark:text-white cursor-pointer list-none">
                            <span>Berapa lama masa garansi produk yang dibeli di BAKUL?</span>
                            <span class="transition group-open:rotate-180">
                                <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                            </span>
                        </summary>
                        <p class="mt-3 text-xs text-gray-600 dark:text-gray-400 leading-relaxed">
                            Kami memberikan garansi penukaran unit atau pengembalian dana penuh selama <strong>1 bulan (30 hari)</strong> sejak barang diterima. Garansi ini khusus berlaku untuk kegagalan fungsi internal perangkat yang bukan disebabkan oleh kelalaian pengguna. Untuk info selengkapnya, silakan baca halaman <a href="<?= BASEURL; ?>/help/refund" class="font-bold underline text-black dark:text-white">Kebijakan Pengembalian Dana</a>.
                        </p>
                    </details>
                </div>

                <!-- FAQ 3 -->
                <div class="pt-4">
                    <details class="group">
                        <summary class="flex justify-between items-center font-bold text-sm text-gray-900 dark:text-white cursor-pointer list-none">
                            <span>Apakah saya bisa membayar menggunakan e-wallet atau kartu kredit?</span>
                            <span class="transition group-open:rotate-180">
                                <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                            </span>
                        </summary>
                        <p class="mt-3 text-xs text-gray-600 dark:text-gray-400 leading-relaxed">
                            Ya, jika memilih metode <strong>Transfer Online / E-Wallet</strong> di checkout, Anda akan diarahkan ke Payment Gateway (Midtrans) kami yang mendukung berbagai jenis pembayaran instan, seperti QRIS, e-wallet (GoPay, ShopeePay), transfer bank virtual account, dan kartu kredit secara aman.
                        </p>
                    </details>
                </div>

                <!-- FAQ 4 -->
                <div class="pt-4">
                    <details class="group">
                        <summary class="flex justify-between items-center font-bold text-sm text-gray-900 dark:text-white cursor-pointer list-none">
                            <span>Bagaimana jika barang yang saya terima cacat fisik saat pengiriman kurir?</span>
                            <span class="transition group-open:rotate-180">
                                <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                            </span>
                        </summary>
                        <p class="mt-3 text-xs text-gray-600 dark:text-gray-400 leading-relaxed">
                            Harap lakukan video unboxing tanpa terputus saat pertama kali membuka paket kurir. Jika terdapat cacat fisik akibat ekspedisi, segera laporkan ke layanan pelanggan kami dalam waktu 2x24 jam untuk mendapatkan penggantian unit baru secara gratis.
                        </p>
                    </details>
                </div>

            </div>
        </div>

        <!-- Contact Section -->
        <div class="mt-8 text-center bg-black dark:bg-white text-white dark:text-black rounded-3xl p-8">
            <h3 class="text-lg font-bold mb-2">Masih butuh bantuan lain?</h3>
            <p class="text-xs text-gray-400 dark:text-gray-600 mb-6">Hubungi Customer Service kami yang siap membantu Anda 24/7.</p>
            <a href="https://wa.me/628123456789?text=Halo%20BAKUL,%20saya%20butuh%20bantuan%20mengenai%25" target="_blank" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-500 hover:bg-emerald-600 text-white rounded-full font-bold text-sm transition shadow-md">
                <i class="fab fa-whatsapp"></i> Chat WhatsApp Dukungan
            </a>
        </div>

    </div>
</div>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>
