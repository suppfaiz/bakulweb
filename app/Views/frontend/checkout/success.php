<?php require_once __DIR__ . '/../../layout/header.php'; ?>

<div class="min-h-screen bg-white flex flex-col justify-center items-center px-4 py-16">
    <div class="max-w-lg w-full">
        <!-- Success Card -->
        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
            <!-- Top Accent -->
            <div class="h-2 bg-gradient-to-r from-gray-900 to-gray-600"></div>
            
            <div class="p-8 sm:p-10 text-center">
                <!-- Animated Checkmark -->
                <div class="relative w-24 h-24 mx-auto mb-6">
                    <div class="w-24 h-24 bg-green-50 rounded-full flex items-center justify-center">
                        <i class="fas fa-check-circle text-5xl text-green-500 animate-bounce-once"></i>
                    </div>
                    <div class="absolute inset-0 rounded-full bg-green-200 animate-ping opacity-20"></div>
                </div>
                
                <h1 class="text-3xl font-extrabold text-gray-900 mb-2">Pembayaran Berhasil!</h1>
                <p class="text-gray-500 mb-1">Pesanan Anda telah diterima dan sedang diproses.</p>
                <p class="text-sm text-gray-400">Invoice: <span class="font-mono font-bold text-black"><?= htmlspecialchars($data['invoice']); ?></span></p>
            </div>

            <!-- Status Timeline -->
            <div class="px-8 pb-6 space-y-0">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-5">Status Pesanan</h3>
                <div class="relative pl-5 border-l-2 border-gray-200 space-y-5">
                    <div class="relative">
                        <span class="absolute -left-[25px] top-1 flex h-4 w-4 items-center justify-center rounded-full bg-black border-4 border-white"></span>
                        <p class="text-sm font-bold text-gray-900">Pembayaran Diterima</p>
                        <p class="text-xs text-gray-500 mt-0.5">Pembayaran terverifikasi secara otomatis.</p>
                    </div>
                    <div class="relative">
                        <span class="absolute -left-[25px] top-1 flex h-4 w-4 items-center justify-center rounded-full bg-gray-200 border-4 border-white"></span>
                        <p class="text-sm text-gray-400">Sedang Diproses</p>
                        <p class="text-xs text-gray-300 mt-0.5">Tim toko sedang menyiapkan pesanan.</p>
                    </div>
                    <div class="relative">
                        <span class="absolute -left-[25px] top-1 flex h-4 w-4 items-center justify-center rounded-full bg-gray-200 border-4 border-white"></span>
                        <p class="text-sm text-gray-400">Dikirim</p>
                        <p class="text-xs text-gray-300 mt-0.5">Estimasi tiba 1–3 hari kerja.</p>
                    </div>
                    <div class="relative">
                        <span class="absolute -left-[25px] top-1 flex h-4 w-4 items-center justify-center rounded-full bg-gray-200 border-4 border-white"></span>
                        <p class="text-sm text-gray-400">Selesai</p>
                    </div>
                </div>
            </div>

            <div class="p-6 pt-4 border-t border-gray-100 flex flex-col sm:flex-row gap-3">
                <a href="<?= BASEURL; ?>/account?tab=orders" 
                   class="flex-1 bg-black text-white px-6 py-3 rounded-full font-bold text-sm hover:bg-gray-800 transition text-center flex items-center justify-center gap-2">
                    <i class="fas fa-list-check"></i> Lacak Pesanan
                </a>
                <a href="<?= BASEURL; ?>/catalog" 
                   class="flex-1 bg-white text-black border-2 border-gray-200 px-6 py-3 rounded-full font-bold text-sm hover:border-black transition text-center flex items-center justify-center gap-2">
                    <i class="fas fa-shopping-bag"></i> Belanja Lagi
                </a>
            </div>
        </div>

        <!-- Info Card -->
        <div class="mt-4 bg-blue-50 border border-blue-100 rounded-2xl p-5 text-sm text-blue-700 flex items-start gap-3">
            <i class="fas fa-bell mt-0.5 flex-shrink-0"></i>
            <p>Anda akan mendapatkan notifikasi otomatis saat status pesanan berubah. Pantau pesanan di halaman <a href="<?= BASEURL ?>/account?tab=orders" class="font-bold underline">Akun Saya</a>.</p>
        </div>
    </div>
</div>

<style>
@keyframes bounce-once {
    0%, 100% { transform: translateY(0); }
    40% { transform: translateY(-10px); }
    60% { transform: translateY(-5px); }
}
.animate-bounce-once { animation: bounce-once 1s ease-in-out; }
</style>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>
