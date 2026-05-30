<?php require_once __DIR__ . '/../../layout/header.php'; ?>

<?php
// Ambil data user
$user = $data['user'];
$cart_items = $data['cart_items'];
$subtotal = $data['subtotal'];

// Data kurir simulasi: [kurir => [service => harga]]
$couriers = [
    'JNE' => [
        'OKE'  => ['label' => 'OKE (Ekonomis)', 'cost' => 12000, 'est' => '3-5 hari'],
        'REG'  => ['label' => 'REG (Reguler)',  'cost' => 15000, 'est' => '2-3 hari'],
        'YES'  => ['label' => 'YES (Kilat)',    'cost' => 28000, 'est' => '1 hari'],
    ],
    'J&T' => [
        'EZ'       => ['label' => 'EZ (Reguler)',    'cost' => 14000, 'est' => '2-3 hari'],
        'EXPRESS'  => ['label' => 'Express (Kilat)', 'cost' => 22000, 'est' => '1 hari'],
    ],
    'SiCepat' => [
        'REG'  => ['label' => 'REG (Reguler)', 'cost' => 13000, 'est' => '2-3 hari'],
        'BEST' => ['label' => 'BEST (Kilat)',  'cost' => 20000, 'est' => '1 hari'],
    ],
    'AnterAja' => [
        'STD' => ['label' => 'Standar', 'cost' => 11000, 'est' => '3-4 hari'],
    ],
];
?>

<div class="bg-white min-h-screen pt-24 pb-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto">
        
        <!-- Breadcrumb -->
        <div class="flex items-center gap-2 text-sm text-gray-400 mb-8">
            <a href="<?= BASEURL ?>/cart" class="hover:text-black transition flex items-center gap-1">
                <i class="fas fa-shopping-cart text-xs"></i> Keranjang
            </a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="font-semibold text-black">Konfirmasi Pesanan</span>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="text-gray-300">Pembayaran</span>
        </div>

        <h1 class="text-2xl font-extrabold text-gray-900 mb-8">Konfirmasi Pesanan</h1>

        <form action="<?= BASEURL ?>/checkout/process" method="POST" id="checkoutForm">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Left Column: Address + Courier -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Alamat Pengiriman -->
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 bg-black rounded-lg flex items-center justify-center">
                                    <i class="fas fa-map-marker-alt text-white text-xs"></i>
                                </div>
                                <h2 class="font-bold text-gray-900">Alamat Pengiriman</h2>
                            </div>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Nama Penerima <span class="text-red-500">*</span></label>
                                    <input type="text" name="recipient_name" id="recipient_name"
                                        value="<?= htmlspecialchars($user['username'] ?? '') ?>"
                                        required placeholder="Nama lengkap penerima"
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-black focus:border-black outline-none transition">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Nomor HP <span class="text-red-500">*</span></label>
                                    <input type="tel" name="recipient_phone" id="recipient_phone"
                                        value="<?= htmlspecialchars($user['phone'] ?? '') ?>"
                                        required placeholder="Contoh: 08123456789"
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-black focus:border-black outline-none transition">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Alamat Lengkap <span class="text-red-500">*</span></label>
                                <textarea name="shipping_address" id="shipping_address" rows="3" required
                                    placeholder="Jalan, Nomor, RT/RW, Kelurahan, Kecamatan, Kota, Provinsi, Kode Pos"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-black focus:border-black outline-none transition resize-none"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                                <p class="text-xs text-gray-400 mt-1.5">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Tuliskan alamat lengkap termasuk kota dan kode pos.
                                </p>
                            </div>
                            
                            <!-- Simpan ke profil -->
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="save_address" value="1" checked class="w-4 h-4 rounded border-gray-300 text-black focus:ring-black">
                                <span class="text-sm text-gray-600">Simpan alamat & nomor HP ke profil saya</span>
                            </label>
                        </div>
                    </div>

                    <!-- Metode Pembayaran -->
                    <div class="bg-white dark:bg-darkcard rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex items-center gap-2">
                            <div class="w-7 h-7 bg-black dark:bg-white rounded-lg flex items-center justify-center">
                                <i class="fas fa-credit-card text-white dark:text-black text-xs"></i>
                            </div>
                            <h2 class="font-bold text-gray-900 dark:text-white">Metode Pembayaran</h2>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <!-- COD Payment -->
                                <label class="flex items-center justify-between border-2 border-gray-100 dark:border-gray-800 rounded-xl p-4 cursor-pointer hover:border-gray-300 transition has-[:checked]:border-black dark:has-[:checked]:border-white has-[:checked]:bg-gray-50 dark:has-[:checked]:bg-gray-805/30">
                                    <div class="flex items-center gap-3">
                                        <input type="radio" name="payment_method" value="cod" checked
                                            class="w-4 h-4 accent-black dark:accent-white"
                                            onchange="selectPaymentMethod('cod')">
                                        <div>
                                            <p class="font-bold text-gray-900 dark:text-white text-sm">COD (Bayar di Tempat)</p>
                                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Ketemuan & Bayar Tunai</p>
                                        </div>
                                    </div>
                                    <i class="fas fa-handshake text-gray-400 dark:text-gray-500 text-lg"></i>
                                </label>

                                <!-- Transfer Bank BNI -->
                                <label class="flex items-center justify-between border-2 border-gray-100 dark:border-gray-800 rounded-xl p-4 cursor-pointer hover:border-gray-300 transition has-[:checked]:border-black dark:has-[:checked]:border-white has-[:checked]:bg-gray-50 dark:has-[:checked]:bg-gray-805/30">
                                    <div class="flex items-center gap-3">
                                        <input type="radio" name="payment_method" value="bank_transfer"
                                            class="w-4 h-4 accent-black dark:accent-white"
                                            onchange="selectPaymentMethod('bank_transfer')">
                                        <div>
                                            <p class="font-bold text-gray-900 dark:text-white text-sm">Transfer Bank BNI</p>
                                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Transfer Manual & Konfirmasi</p>
                                        </div>
                                    </div>
                                    <i class="fas fa-university text-gray-400 dark:text-gray-500 text-lg"></i>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- BNI Transfer Info Box (hidden by default) -->
                    <div id="bniInfoBox" class="hidden bg-gradient-to-br from-orange-50 to-amber-50 dark:from-gray-800 dark:to-gray-750 border-2 border-orange-200 dark:border-orange-700 rounded-2xl p-5">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-8 h-8 bg-orange-500 rounded-xl flex items-center justify-center">
                                <i class="fas fa-university text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-black text-gray-900 dark:text-white text-sm">Info Rekening Transfer</p>
                                <p class="text-xs text-orange-600 dark:text-orange-400">Selesaikan transfer setelah order dibuat</p>
                            </div>
                        </div>
                        <div class="bg-white dark:bg-gray-700 rounded-xl p-4 space-y-3 border border-orange-100 dark:border-gray-600">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">Bank</span>
                                <span class="font-black text-gray-900 dark:text-white text-sm">🏦 BNI (Bank Negara Indonesia)</span>
                            </div>
                            <div class="border-t border-gray-100 dark:border-gray-600 pt-3 flex items-center justify-between">
                                <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">No. Rekening</span>
                                <div class="flex items-center gap-2">
                                    <span class="font-black text-gray-900 dark:text-white text-lg tracking-wider font-mono">0231090661</span>
                                    <button type="button" onclick="copyNoRek()" class="text-xs bg-orange-100 hover:bg-orange-200 text-orange-700 px-2 py-1 rounded-lg transition font-bold">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="border-t border-gray-100 dark:border-gray-600 pt-3 flex items-center justify-between">
                                <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">Atas Nama</span>
                                <span class="font-bold text-gray-900 dark:text-white text-sm">SEPTIAN FAIZ WITANA</span>
                            </div>
                        </div>
                        <div class="mt-3 flex items-start gap-2 text-xs text-orange-700 dark:text-orange-400">
                            <i class="fas fa-info-circle mt-0.5 flex-shrink-0"></i>
                            <p>Setelah order dibuat, Anda akan mendapat nomor invoice. Harap kirim bukti transfer via WhatsApp ke penjual agar pesanan segera diproses.</p>
                        </div>
                    </div>

                    <!-- Pilihan Kurir -->
                    <div id="courierSection" class="bg-white dark:bg-darkcard rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex items-center gap-2">
                            <div class="w-7 h-7 bg-black dark:bg-white rounded-lg flex items-center justify-center">
                                <i class="fas fa-truck text-white dark:text-black text-xs"></i>
                            </div>
                            <h2 class="font-bold text-gray-900 dark:text-white">Pilihan Kurir & Layanan</h2>
                        </div>
                        <div class="p-6">
                            <!-- Kurir Tabs -->
                            <div class="flex gap-2 flex-wrap mb-5">
                                <?php foreach(array_keys($couriers) as $i => $courier_name): ?>
                                <button type="button" onclick="selectCourier('<?= $courier_name ?>')"
                                    id="tab-<?= $courier_name ?>"
                                    class="courier-tab px-4 py-2 rounded-full text-sm font-semibold border-2 transition <?= $i === 0 ? 'bg-black text-white border-black' : 'bg-white text-gray-600 border-gray-200 hover:border-gray-400' ?>">
                                    <?= $courier_name ?>
                                </button>
                                <?php endforeach; ?>
                            </div>
                            
                            <!-- Layanan per Kurir -->
                            <?php foreach($couriers as $courier_name => $services): ?>
                            <div id="services-<?= $courier_name ?>" class="courier-services space-y-3 <?= $courier_name !== 'JNE' ? 'hidden' : '' ?>">
                                <?php foreach($services as $service_code => $service): ?>
                                <label class="service-option flex items-center justify-between border-2 border-gray-100 rounded-xl p-4 cursor-pointer hover:border-gray-300 transition has-[:checked]:border-black has-[:checked]:bg-gray-50">
                                    <div class="flex items-center gap-3">
                                        <input type="radio" name="courier_service_key" 
                                            value="<?= $courier_name ?>|<?= $service_code ?>"
                                            class="w-4 h-4 accent-black"
                                            <?= ($courier_name === 'JNE' && $service_code === 'REG') ? 'checked' : '' ?>
                                            onchange="updateShipping(<?= $service['cost'] ?>, '<?= $courier_name ?>', '<?= $service_code ?>')">
                                        <div>
                                            <p class="font-semibold text-gray-900 text-sm"><?= $courier_name ?> <?= $service['label'] ?></p>
                                            <p class="text-xs text-gray-400 mt-0.5"><i class="fas fa-clock mr-1"></i>Estimasi <?= $service['est'] ?></p>
                                        </div>
                                    </div>
                                    <span class="font-bold text-gray-900 text-sm whitespace-nowrap">
                                        Rp <?= number_format($service['cost'], 0, ',', '.') ?>
                                    </span>
                                </label>
                                <?php endforeach; ?>
                            </div>
                            <?php endforeach; ?>
                            
                            <!-- Hidden inputs -->
                            <input type="hidden" name="courier" id="input_courier" value="JNE">
                            <input type="hidden" name="courier_service" id="input_service" value="REG">
                            <input type="hidden" name="shipping_cost" id="input_shipping_cost" value="15000">
                        </div>
                    </div>

                    <!-- Info Box COD (Ketemuan) -->
                    <div id="codInfoBox" class="bg-emerald-50 dark:bg-emerald-950/20 rounded-2xl border border-emerald-100 dark:border-emerald-900/30 p-6 hidden">
                        <div class="flex gap-3">
                            <div class="w-8 h-8 bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-450 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-handshake"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-emerald-900 dark:text-emerald-250 text-sm mb-1">Ketentuan Cash on Delivery (COD)</h3>
                                <p class="text-xs text-emerald-700 dark:text-emerald-300 leading-relaxed">
                                    Anda memilih untuk bertransaksi secara COD. Setelah pesanan dibuat, Anda dan Penjual akan berkoordinasi langsung untuk menyepakati <strong>waktu & lokasi ketemuan</strong>. Pembayaran dilakukan secara tunai langsung di tempat setelah barang diperiksa dan diterima. Ongkos kirim otomatis menjadi <strong>Rp 0 (Gratis)</strong>.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Catatan Pesanan -->
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                            <i class="fas fa-comment-alt mr-1"></i>Catatan Pesanan (Opsional)
                        </label>
                        <textarea name="order_note" rows="2" placeholder="Contoh: Jangan diketuk pintu, tinggalkan di depan..."
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-black focus:border-black outline-none resize-none bg-gray-50"></textarea>
                    </div>
                </div>

                <!-- Right Column: Order Summary -->
                <div class="space-y-4">
                    <!-- Item Pesanan -->
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden sticky top-24">
                        <div class="px-5 py-4 border-b border-gray-100">
                            <h2 class="font-bold text-gray-900">Ringkasan Pesanan</h2>
                            <p class="text-xs text-gray-400 mt-0.5"><?= count($cart_items) ?> produk</p>
                        </div>
                        
                        <!-- Items List -->
                        <div class="divide-y divide-gray-50 max-h-64 overflow-y-auto">
                            <?php foreach($cart_items as $item): ?>
                            <div class="px-5 py-3 flex items-center gap-3">
                                <div class="w-12 h-12 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0 border border-gray-100">
                                    <?php if($item['image']): ?>
                                        <img src="<?= BASEURL ?>/<?= $item['image'] ?>" alt="" class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <div class="w-full h-full flex items-center justify-center text-gray-300 text-xs"><i class="fas fa-mobile-alt"></i></div>
                                    <?php endif; ?>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-semibold text-gray-900 truncate"><?= htmlspecialchars($item['name']) ?></p>
                                    <p class="text-xs text-gray-400"><?= htmlspecialchars($item['color']) ?> · <?= htmlspecialchars($item['storage']) ?></p>
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <p class="text-xs font-bold text-gray-900">Rp <?= number_format($item['total'], 0, ',', '.') ?></p>
                                    <p class="text-xs text-gray-400">x<?= $item['qty'] ?></p>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Price Breakdown -->
                        <div class="px-5 py-4 border-t border-gray-100 space-y-2.5">
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>Subtotal Produk</span>
                                <span class="font-semibold">Rp <?= number_format($subtotal, 0, ',', '.') ?></span>
                            </div>
                            <!-- Applied Discount Row (hidden by default) -->
                            <div id="discountRow" class="flex justify-between text-sm text-red-600 hidden">
                                <span>Diskon Voucher (<span id="voucherCodeLabel"></span>)</span>
                                <span class="font-bold">-<span id="displayDiscount">Rp 0</span></span>
                            </div>
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>Ongkos Kirim</span>
                                <span class="font-semibold" id="displayShipping">Rp 15.000</span>
                            </div>
                            <div class="pt-2 border-t border-gray-100 flex justify-between font-black text-base text-gray-900">
                                <span>Total Bayar</span>
                                <span id="displayTotal">Rp <?= number_format($subtotal + 15000, 0, ',', '.') ?></span>
                            </div>
                        </div>

                        <!-- Voucher Input Block -->
                        <div class="px-5 py-3.5 border-t border-gray-100 bg-gray-50/50">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">
                                <i class="fas fa-ticket-alt mr-1 text-primary"></i>Punya Voucher Promo?
                            </label>
                            <div class="flex gap-2">
                                <input type="text" id="voucherCodeInput" placeholder="Masukkan kode voucher" class="flex-1 px-3 py-2 border border-gray-300 rounded-xl text-xs font-bold font-mono focus:ring-2 focus:ring-black focus:border-black outline-none bg-white uppercase">
                                <button type="button" onclick="applyVoucher()" class="bg-black hover:bg-gray-800 text-white px-4 py-2 rounded-xl text-xs font-bold transition">
                                    Pakai
                                </button>
                            </div>
                            <div id="voucherStatus" class="mt-2 text-[11px] font-semibold hidden"></div>
                        </div>

                        <!-- Checkout Button -->
                        <div class="px-5 pb-5">
                            <input type="hidden" name="voucher_code" id="appliedVoucherCode" value="">
                            <button type="submit" id="submitBtn"
                                class="w-full bg-black text-white py-3.5 rounded-xl font-bold text-sm hover:bg-gray-800 transition shadow-md hover:shadow-lg hover:-translate-y-0.5 flex items-center justify-center gap-2">
                                <i class="fas fa-lock text-xs"></i>
                                Lanjut ke Pembayaran
                            </button>
                            <p class="text-center text-xs text-gray-400 mt-3">
                                <i class="fas fa-shield-halved mr-1"></i>Transaksi aman & terenkripsi
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
const subtotal = <?= $subtotal ?>;

const courierData = <?= json_encode($couriers) ?>;

function selectCourier(name) {
    // Update tab styling
    document.querySelectorAll('.courier-tab').forEach(btn => {
        btn.className = 'courier-tab px-4 py-2 rounded-full text-sm font-semibold border-2 transition bg-white text-gray-600 border-gray-200 hover:border-gray-400';
    });
    document.getElementById('tab-' + name).className = 'courier-tab px-4 py-2 rounded-full text-sm font-semibold border-2 transition bg-black text-white border-black';

    // Show/hide services
    document.querySelectorAll('.courier-services').forEach(el => el.classList.add('hidden'));
    document.getElementById('services-' + name).classList.remove('hidden');

    // Auto-select first service of this courier
    const firstServiceEl = document.querySelector(`#services-${name} input[type="radio"]`);
    if (firstServiceEl) {
        firstServiceEl.checked = true;
        firstServiceEl.dispatchEvent(new Event('change'));
    }
}

let savedShippingCost = 15000;
let currentPaymentMethod = 'midtrans';
let discountAmount = 0;
let appliedVoucher = "";

function recalculateTotal() {
    let shippingCost = 0;
    if (currentPaymentMethod !== 'cod') {
        shippingCost = parseInt(document.getElementById('input_shipping_cost').value) || 0;
    }
    const total = Math.max(0, (subtotal - discountAmount) + shippingCost);
    document.getElementById('displayTotal').textContent = 'Rp ' + total.toLocaleString('id-ID');
}

function applyVoucher() {
    const input = document.getElementById('voucherCodeInput');
    const code = input.value.trim().toUpperCase();
    const statusBox = document.getElementById('voucherStatus');
    
    if (code === "") {
        showToast('Masukkan kode voucher terlebih dahulu!', 'warning');
        return;
    }

    // Call API via POST
    fetch('<?= BASEURL; ?>/checkout/apply_voucher', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'code=' + encodeURIComponent(code)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            // Apply discount
            discountAmount = data.discount_amount;
            appliedVoucher = data.code;

            // Update display
            document.getElementById('displayDiscount').textContent = data.discount_formatted;
            document.getElementById('voucherCodeLabel').textContent = data.code;
            document.getElementById('discountRow').classList.remove('hidden');
            
            // Set hidden form field
            document.getElementById('appliedVoucherCode').value = data.code;

            // Show status message
            statusBox.textContent = data.message;
            statusBox.className = "mt-2 text-[11px] font-semibold text-green-600";
            statusBox.classList.remove('hidden');

            recalculateTotal();
            showToast(data.message, 'success');
        } else {
            // Reset discount
            discountAmount = 0;
            appliedVoucher = "";

            // Hide display
            document.getElementById('discountRow').classList.add('hidden');
            document.getElementById('appliedVoucherCode').value = "";

            // Show status message
            statusBox.textContent = data.message;
            statusBox.className = "mt-2 text-[11px] font-semibold text-red-500";
            statusBox.classList.remove('hidden');

            recalculateTotal();
            showToast(data.message, 'error');
        }
    })
    .catch(err => {
        console.error(err);
        showToast('Terjadi kesalahan koneksi.', 'error');
    });
}

function selectPaymentMethod(method) {
    currentPaymentMethod = method;
    const courierSection = document.getElementById('courierSection');
    const codInfoBox = document.getElementById('codInfoBox');
    const bniInfoBox = document.getElementById('bniInfoBox');
    
    if (method === 'cod') {
        // Save current shipping cost
        savedShippingCost = parseInt(document.getElementById('input_shipping_cost').value) || 0;
        
        // Hide courier section, show COD info, hide BNI
        courierSection.classList.add('hidden');
        codInfoBox.classList.remove('hidden');
        bniInfoBox.classList.add('hidden');
        
        // Set inputs for COD
        document.getElementById('input_shipping_cost').value = 0;
        
        // Update display
        document.getElementById('displayShipping').textContent = 'Rp 0 (COD)';
        document.getElementById('displayShipping').className = 'font-bold text-emerald-600 dark:text-emerald-400';
        
        recalculateTotal();
        document.getElementById('submitBtn').innerHTML = '<i class="fas fa-handshake text-xs"></i> Buat Pesanan COD';
    } else if (method === 'bank_transfer') {
        // Show courier section, hide COD info, show BNI
        courierSection.classList.remove('hidden');
        codInfoBox.classList.add('hidden');
        bniInfoBox.classList.remove('hidden');
        
        // Restore shipping cost
        const activeRadio = document.querySelector('input[name="courier_service_key"]:checked');
        if (activeRadio) {
            activeRadio.dispatchEvent(new Event('change'));
        } else {
            updateShipping(savedShippingCost, document.getElementById('input_courier').value, document.getElementById('input_service').value);
        }
        
        document.getElementById('displayShipping').className = 'font-semibold';
        document.getElementById('submitBtn').innerHTML = '<i class="fas fa-university text-xs"></i> Buat Pesanan & Transfer BNI';
    } else {
        // Show courier section, hide COD info and BNI info (midtrans)
        courierSection.classList.remove('hidden');
        codInfoBox.classList.add('hidden');
        bniInfoBox.classList.add('hidden');
        
        // Restore shipping cost
        const activeRadio = document.querySelector('input[name="courier_service_key"]:checked');
        if (activeRadio) {
            activeRadio.dispatchEvent(new Event('change'));
        } else {
            updateShipping(savedShippingCost, document.getElementById('input_courier').value, document.getElementById('input_service').value);
        }
        
        document.getElementById('displayShipping').className = 'font-semibold';
        document.getElementById('submitBtn').innerHTML = '<i class="fas fa-lock text-xs"></i> Lanjut ke Pembayaran';
    }
}

function copyNoRek() {
    navigator.clipboard.writeText('0231090661').then(() => {
        showToast('Nomor rekening berhasil disalin!', 'success');
    }).catch(() => {
        showToast('Gagal menyalin. Salin manual: 0231090661', 'info');
    });
}

function updateShipping(cost, courier, service) {
    if (currentPaymentMethod === 'cod') return;

    document.getElementById('input_courier').value = courier;
    document.getElementById('input_service').value = service;
    document.getElementById('input_shipping_cost').value = cost;

    // Update display
    document.getElementById('displayShipping').textContent = 'Rp ' + cost.toLocaleString('id-ID');
    recalculateTotal();
}

// Init with default JNE REG
updateShipping(15000, 'JNE', 'REG');

// Init payment method (COD is default)
selectPaymentMethod('cod');

// Form validation
document.getElementById('checkoutForm').addEventListener('submit', function(e) {
    const name = document.getElementById('recipient_name').value.trim();
    const phone = document.getElementById('recipient_phone').value.trim();
    const addr = document.getElementById('shipping_address').value.trim();

    if (!name || !phone || !addr) {
        e.preventDefault();
        showToast('Harap lengkapi nama penerima, nomor HP, dan alamat pengiriman!', 'warning');
        return;
    }
    
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
});
</script>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>
