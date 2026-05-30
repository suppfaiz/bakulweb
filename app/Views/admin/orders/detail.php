<?php require_once __DIR__ . '/../../layout/admin_header.php'; ?>

<?php
$order = $data['order'];
$items = $data['items'];

$statusMap = [
    'pending'    => ['label' => 'Menunggu',   'class' => 'bg-yellow-100 text-yellow-800'],
    'processing' => ['label' => 'Diproses',   'class' => 'bg-blue-100 text-blue-800'],
    'shipped'    => ['label' => 'Dikirim',    'class' => 'bg-purple-100 text-purple-800'],
    'completed'  => ['label' => 'Selesai',    'class' => 'bg-green-100 text-green-800'],
    'delivered'  => ['label' => 'Diterima',   'class' => 'bg-green-100 text-green-800'],
    'cancelled'  => ['label' => 'Dibatalkan', 'class' => 'bg-red-100 text-red-800'],
];
$st = $statusMap[$order['status']] ?? ['label' => ucfirst($order['status']), 'class' => 'bg-gray-100 text-gray-800'];
?>

<div class="mb-6 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <a href="<?= BASEURL ?>/admin/orders" class="w-8 h-8 bg-gray-100 hover:bg-gray-200 rounded-lg flex items-center justify-center text-gray-500 hover:text-black transition">
            <i class="fas fa-arrow-left text-sm"></i>
        </a>
        <div>
            <h1 class="text-xl font-bold text-gray-900">Detail Pesanan</h1>
            <p class="text-sm font-mono text-gray-400 mt-0.5"><?= htmlspecialchars($order['invoice']) ?></p>
        </div>
    </div>
    <div class="flex items-center gap-3">
        <span class="text-xs text-gray-400"><?= date('d M Y, H:i', strtotime($order['created_at'])) ?></span>
        <span class="px-3 py-1.5 rounded-full text-xs font-bold <?= $st['class'] ?>">
            <?= $st['label'] ?>
        </span>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Kiri: Items + Alamat -->
    <div class="lg:col-span-2 space-y-5">

        <!-- Item Pesanan -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="font-bold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-box text-gray-400 text-sm"></i> Item Pesanan
                </h2>
            </div>
            <div class="divide-y divide-gray-50">
                <?php if(!empty($items)): foreach($items as $item): ?>
                <div class="px-6 py-4 flex items-center justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-900 text-sm"><?= htmlspecialchars($item['product_name']) ?></p>
                        <p class="text-xs text-gray-500 mt-0.5"><?= htmlspecialchars($item['variant_name']) ?></p>
                        <p class="text-[10px] text-gray-400 font-mono mt-0.5">SKU: <?= htmlspecialchars($item['sku'] ?? '-') ?></p>
                    </div>
                    <div class="text-right flex-shrink-0 space-y-0.5">
                        <p class="text-xs text-gray-500">@Rp <?= number_format((float)$item['price'], 0, ',', '.') ?> × <?= $item['qty'] ?></p>
                        <p class="text-sm font-black text-gray-900">
                            Rp <?= number_format((float)($item['price'] * $item['qty']), 0, ',', '.') ?>
                        </p>
                    </div>
                </div>
                <?php endforeach; else: ?>
                <div class="px-6 py-8 text-center text-sm text-gray-400">Tidak ada item.</div>
                <?php endif; ?>
            </div>
            <!-- Totals -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 space-y-2">
                <div class="flex justify-between text-sm text-gray-500">
                    <span>Subtotal Produk</span>
                    <span>Rp <?= number_format((float)($order['total_amount'] - $order['shipping_cost']), 0, ',', '.') ?></span>
                </div>
                <div class="flex justify-between text-sm text-gray-500">
                    <span>Ongkos Kirim 
                        <?php if($order['courier']): ?>
                            <span class="text-xs bg-gray-200 text-gray-600 px-1.5 py-0.5 rounded font-bold ml-1">
                                <?= htmlspecialchars($order['courier']) ?> <?= htmlspecialchars($order['courier_service']) ?>
                            </span>
                        <?php endif; ?>
                    </span>
                    <span>Rp <?= number_format((float)$order['shipping_cost'], 0, ',', '.') ?></span>
                </div>
                <div class="flex justify-between font-black text-gray-900 text-base pt-2 border-t border-gray-200">
                    <span>Total</span>
                    <span>Rp <?= number_format((float)$order['total_amount'], 0, ',', '.') ?></span>
                </div>
            </div>
        </div>

        <!-- Alamat Pengiriman -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="font-bold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-map-marker-alt text-gray-400 text-sm"></i> Informasi Pengiriman
                </h2>
            </div>
            <div class="p-6">
                <?php if($order['shipping_address']): ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Nama Penerima</p>
                            <p class="font-semibold text-gray-900"><?= htmlspecialchars($order['recipient_name'] ?? '-') ?></p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Nomor HP</p>
                            <p class="font-semibold text-gray-900"><?= htmlspecialchars($order['recipient_phone'] ?? '-') ?></p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Alamat Pengiriman</p>
                            <p class="text-sm text-gray-700 leading-relaxed"><?= nl2br(htmlspecialchars($order['shipping_address'])) ?></p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Kurir</p>
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center gap-1.5 bg-black text-white text-xs font-bold px-3 py-1 rounded-full">
                                    <i class="fas fa-truck text-xs"></i>
                                    <?= htmlspecialchars($order['courier'] ?? 'JNE') ?> — <?= htmlspecialchars($order['courier_service'] ?? 'REG') ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <div class="text-center py-6 text-gray-400">
                    <i class="fas fa-map-marker-alt text-3xl text-gray-200 mb-3 block"></i>
                    <p class="text-sm">Belum ada data alamat pengiriman untuk pesanan ini.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Kanan: Info -->
    <div class="space-y-5">

        <!-- Customer Info -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Pelanggan</h3>
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-user text-gray-400 text-sm"></i>
                </div>
                <div>
                    <p class="font-bold text-gray-900 text-sm"><?= htmlspecialchars($order['customer_name']) ?></p>
                    <p class="text-xs text-gray-500"><?= htmlspecialchars($order['customer_email'] ?? '') ?></p>
                </div>
            </div>
        </div>

        <!-- Payment Info -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Pembayaran</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between items-center">
                    <span class="text-gray-500">Metode</span>
                    <span class="font-semibold text-gray-900 text-xs"><?= htmlspecialchars($order['payment_method'] ?? '-') ?></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-500">Status</span>
                    <?php if($order['payment_status'] == 'paid'): ?>
                        <span class="font-bold text-green-600 text-xs"><i class="fas fa-check-circle mr-1"></i>Lunas</span>
                    <?php else: ?>
                        <span class="font-bold text-red-500 text-xs"><i class="fas fa-times-circle mr-1"></i>Belum Bayar</span>
                    <?php endif; ?>
                </div>
                <?php if($order['midtrans_trx_id']): ?>
                <div class="flex justify-between items-center">
                    <span class="text-gray-500">Trx ID</span>
                    <span class="font-mono text-xs text-gray-600 bg-gray-100 px-2 py-0.5 rounded"><?= htmlspecialchars($order['midtrans_trx_id']) ?></span>
                </div>
                <?php endif; ?>
                <div class="flex justify-between items-center pt-2 border-t border-gray-100">
                    <span class="text-gray-500">Total Bayar</span>
                    <span class="font-black text-gray-900">Rp <?= number_format((float)$order['total_amount'], 0, ',', '.') ?></span>
                </div>
            </div>
        </div>

        <!-- Update Status -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Ubah Status Pesanan</h3>
            <form action="<?= BASEURL ?>/admin/update_order_status" method="POST" class="space-y-3">
                <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                <select name="status" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-black focus:border-black outline-none bg-gray-50">
                    <option value="pending"    <?= $order['status'] == 'pending'    ? 'selected' : '' ?>>⏳ Menunggu (Pending)</option>
                    <option value="processing" <?= $order['status'] == 'processing' ? 'selected' : '' ?>>⚙️ Sedang Diproses</option>
                    <option value="shipped"    <?= $order['status'] == 'shipped'    ? 'selected' : '' ?>>🚚 Dikirim</option>
                    <option value="completed"  <?= $order['status'] == 'completed'  ? 'selected' : '' ?>>✅ Selesai</option>
                    <option value="cancelled"  <?= $order['status'] == 'cancelled'  ? 'selected' : '' ?>>❌ Dibatalkan</option>
                </select>
                <button type="submit" class="w-full bg-black text-white py-2.5 rounded-xl font-bold text-sm hover:bg-gray-800 transition flex items-center justify-center gap-2">
                    <i class="fas fa-save"></i> Update Status
                </button>
            </form>
            
            <?php if($order['payment_status'] == 'unpaid'): ?>
            <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-xl text-xs text-yellow-700">
                <i class="fas fa-exclamation-triangle mr-1"></i>
                Pesanan belum dibayar. Harap konfirmasi pembayaran sebelum memproses.
            </div>
            <?php endif; ?>
        </div>

        <!-- Invoice Link -->
        <a href="<?= BASEURL ?>/account/invoice/<?= $order['id'] ?>" target="_blank"
           class="flex items-center justify-center gap-2 w-full py-2.5 border-2 border-gray-200 hover:border-black text-gray-600 hover:text-black rounded-xl text-sm font-semibold transition">
            <i class="fas fa-file-invoice"></i> Lihat Invoice Pelanggan
        </a>
    </div>
</div>

<?php require_once __DIR__ . '/../../layout/admin_footer.php'; ?>
