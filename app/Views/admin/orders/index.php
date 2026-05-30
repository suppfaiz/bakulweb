<?php require_once __DIR__ . '/../../layout/admin_header.php'; ?>

<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Manajemen Pesanan</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola transaksi dan status pengiriman pesanan pelanggan.</p>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex space-x-2">
            <a href="<?= BASEURL; ?>/admin/orders" class="px-3 py-1 <?= empty($data['filters']['status']) ? 'bg-black text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50'; ?> text-xs font-medium rounded-full">Semua</a>
            <a href="<?= BASEURL; ?>/admin/orders?status=perlu_dikirim" class="px-3 py-1 <?= ($data['filters']['status'] ?? '') == 'perlu_dikirim' ? 'bg-black text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50'; ?> text-xs font-medium rounded-full">Perlu Dikirim</a>
            <a href="<?= BASEURL; ?>/admin/orders?status=selesai" class="px-3 py-1 <?= ($data['filters']['status'] ?? '') == 'selesai' ? 'bg-black text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50'; ?> text-xs font-medium rounded-full">Selesai</a>
        </div>
        <form action="<?= BASEURL; ?>/admin/orders" method="GET" class="relative">
            <?php if(!empty($data['filters']['status'])): ?>
                <input type="hidden" name="status" value="<?= $data['filters']['status']; ?>">
            <?php endif; ?>
            <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 text-xs"></i>
            <input type="text" name="search" value="<?= $data['filters']['search'] ?? ''; ?>" placeholder="Cari Invoice..." class="pl-8 pr-4 py-1.5 border border-gray-300 rounded-lg text-sm focus:ring-black focus:border-black outline-none w-full md:w-64">
        </form>
    </div>
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice & Tanggal</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php if(!empty($data['orders'])): ?>
                <?php foreach($data['orders'] as $order): ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-bold text-gray-900"><?= $order['invoice']; ?></div>
                        <div class="text-xs text-gray-500"><?= date('d M Y, H:i', strtotime($order['created_at'])); ?></div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900"><?= $order['customer_name']; ?></div>
                        <div class="text-xs text-gray-500"><?= $order['customer_email']; ?></div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-bold text-gray-900">Rp <?= number_format($order['total_amount'], 0, ',', '.'); ?></div>
                        <div class="text-xs text-gray-500">
                            <?php if($order['payment_status'] == 'paid'): ?>
                                <span class="text-green-600 font-medium"><i class="fas fa-check-circle"></i> Lunas</span>
                            <?php else: ?>
                                <span class="text-red-500"><i class="fas fa-times-circle"></i> Belum Bayar</span>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <?php if($order['status'] == 'pending'): ?>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Menunggu</span>
                        <?php elseif($order['status'] == 'processing'): ?>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Diproses</span>
                        <?php elseif($order['status'] == 'shipped'): ?>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">Dikirim</span>
                        <?php else: ?>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800"><?= ucfirst($order['status']); ?></span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex items-center justify-end gap-2">
                            <a href="<?= BASEURL ?>/admin/order_detail/<?= $order['id'] ?>" 
                               class="text-gray-500 hover:text-black text-xs font-semibold bg-gray-100 hover:bg-gray-200 px-2.5 py-1.5 rounded-lg transition">
                                <i class="fas fa-eye mr-1"></i>Detail
                            </a>
                            <form action="<?= BASEURL; ?>/admin/update_order_status" method="POST" class="inline-block">
                                <input type="hidden" name="order_id" value="<?= $order['id']; ?>">
                                <select name="status" onchange="this.form.submit()" class="text-xs border border-gray-300 rounded-md p-1 focus:ring-black focus:border-black outline-none">
                                    <option value="pending" <?= $order['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="processing" <?= $order['status'] == 'processing' ? 'selected' : ''; ?>>Diproses</option>
                                    <option value="shipped" <?= $order['status'] == 'shipped' ? 'selected' : ''; ?>>Dikirim</option>
                                    <option value="completed" <?= $order['status'] == 'completed' ? 'selected' : ''; ?>>Selesai</option>
                                </select>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-sm text-gray-500">
                        Belum ada data transaksi pesanan.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../../layout/admin_footer.php'; ?>
