<?php require_once __DIR__ . '/../../layout/header.php'; ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex flex-col md:flex-row gap-8">
        
        <!-- Sidebar Menu -->
        <div class="w-full md:w-64 flex-shrink-0">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
                <div class="flex items-center space-x-4 mb-6">
                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center text-gray-500 text-xl">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900"><?= htmlspecialchars($_SESSION['name']); ?></h3>
                        <p class="text-xs text-gray-500">Member</p>
                    </div>
                </div>
                <nav class="space-y-1">
                    <a href="<?= BASEURL; ?>/account?tab=orders" class="flex items-center px-3 py-2 <?= ($data['tab'] == 'orders') ? 'bg-gray-100 text-black' : 'text-gray-600 hover:bg-gray-50 hover:text-black'; ?> rounded-lg text-sm font-medium transition">
                        <i class="fas fa-shopping-bag w-6 text-gray-500"></i> Riwayat Pesanan
                    </a>
                    <a href="<?= BASEURL; ?>/account?tab=wishlist" class="flex items-center px-3 py-2 <?= ($data['tab'] == 'wishlist') ? 'bg-gray-100 text-black' : 'text-gray-600 hover:bg-gray-50 hover:text-black'; ?> rounded-lg text-sm font-medium transition">
                        <i class="fas fa-heart w-6 text-gray-500"></i> Wishlist
                    </a>
                    <a href="<?= BASEURL; ?>/account?tab=address" class="flex items-center px-3 py-2 <?= ($data['tab'] == 'address') ? 'bg-gray-100 text-black' : 'text-gray-600 hover:bg-gray-50 hover:text-black'; ?> rounded-lg text-sm font-medium transition">
                        <i class="fas fa-map-marker-alt w-6 text-gray-500"></i> Alamat Pengiriman
                    </a>
                    <a href="<?= BASEURL; ?>/account?tab=settings" class="flex items-center px-3 py-2 <?= ($data['tab'] == 'settings') ? 'bg-gray-100 text-black' : 'text-gray-600 hover:bg-gray-50 hover:text-black'; ?> rounded-lg text-sm font-medium transition">
                        <i class="fas fa-user-cog w-6 text-gray-500"></i> Pengaturan Akun
                    </a>
                    <a href="<?= BASEURL; ?>/account?tab=refund" class="flex items-center px-3 py-2 <?= ($data['tab'] == 'refund') ? 'bg-gray-100 text-black' : 'text-gray-600 hover:bg-gray-50 hover:text-black'; ?> rounded-lg text-sm font-medium transition">
                        <i class="fas fa-undo w-6 text-gray-500"></i> Pengembalian Dana (Refund)
                    </a>
                    <a href="<?= BASEURL; ?>/account?tab=warranty" class="flex items-center px-3 py-2 <?= ($data['tab'] == 'warranty') ? 'bg-gray-100 text-black' : 'text-gray-600 hover:bg-gray-50 hover:text-black'; ?> rounded-lg text-sm font-medium transition">
                        <i class="fas fa-shield-alt w-6 text-gray-500"></i> Klaim Garansi
                    </a>
                    <a href="<?= BASEURL; ?>/account?tab=chat" class="flex items-center justify-between px-3 py-2 <?= ($data['tab'] == 'chat') ? 'bg-gray-100 text-black' : 'text-gray-600 hover:bg-gray-50 hover:text-black'; ?> rounded-lg text-sm font-medium transition">
                        <span><i class="fas fa-comments w-6 text-gray-500"></i> Live Chat CS</span>
                        <?php if (($data['chat_unread'] ?? 0) > 0): ?>
                            <span class="bg-black text-white text-[9px] font-black px-1.5 py-0.5 rounded-full"><?= $data['chat_unread'] ?></span>
                        <?php endif; ?>
                    </a>
                </nav>
            </div>
            
            <a href="<?= BASEURL; ?>/auth/logout" class="block w-full text-center px-4 py-2 border border-red-200 text-red-600 rounded-xl hover:bg-red-50 font-medium text-sm transition">
                Logout
            </a>
        </div>

        <!-- Main Content -->
        <div class="flex-1">
            <?php if($data['tab'] == 'orders'): ?>
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Riwayat Pesanan Saya</h2>
                
                <?php if(!empty($data['orders'])): ?>
                    <div class="space-y-4">
                        <?php foreach($data['orders'] as $order): ?>
                            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 flex flex-col md:flex-row md:items-start justify-between gap-6">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <span class="font-mono font-bold text-gray-900"><?= $order['invoice']; ?></span>
                                        <?php if($order['payment_status'] == 'paid'): ?>
                                            <span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs font-bold rounded-full">Lunas</span>
                                        <?php else: ?>
                                            <span class="px-2 py-0.5 bg-red-100 text-red-700 text-xs font-bold rounded-full">Belum Bayar</span>
                                        <?php endif; ?>
                                    </div>
                                    <p class="text-sm text-gray-500 mb-4"><?= date('d M Y, H:i', strtotime($order['created_at'])); ?></p>
                                    
                                    <!-- Visual Tracking Stepper -->
                                    <div class="relative w-full mt-6 mb-2">
                                        <div class="overflow-hidden h-2 mb-4 text-xs flex rounded-full bg-gray-100">
                                            <?php 
                                                // Hitung persentase progress
                                                $progress = 0;
                                                if($order['payment_status'] == 'paid') {
                                                    $progress = 33;
                                                    if($order['status'] == 'processing') $progress = 66;
                                                    if($order['status'] == 'shipped' || $order['status'] == 'completed' || $order['status'] == 'delivered') $progress = 100;
                                                }
                                            ?>
                                            <div style="width: <?= $progress; ?>%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-black transition-all duration-500"></div>
                                        </div>
                                        <div class="flex justify-between text-xs text-gray-500 font-medium px-1">
                                            <div class="text-black">Pesanan Dibuat</div>
                                            <div class="<?= $progress >= 33 ? 'text-black' : ''; ?>">Lunas</div>
                                            <div class="<?= $progress >= 66 ? 'text-black' : ''; ?>">Diproses</div>
                                            <div class="<?= $progress >= 100 ? 'text-black' : ''; ?>">Selesai</div>
                                        </div>
                                    </div>

                                    <!-- Daftar Barang -->
                                    <div class="mt-6 border-t border-gray-100 pt-4">
                                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Daftar Barang:</h4>
                                        <ul class="space-y-2">
                                            <?php 
                                            $unreviewedCount = 0;
                                            foreach ($order['items'] as $item): 
                                                if (!$item['is_reviewed']) {
                                                    $unreviewedCount++;
                                                }
                                            ?>
                                                <li class="text-sm text-gray-700 flex items-center justify-between">
                                                    <span>
                                                        <?= htmlspecialchars($item['product_name']); ?> - <?= htmlspecialchars($item['variant_name']); ?> 
                                                        <span class="text-gray-500 font-mono">x<?= $item['qty']; ?></span>
                                                    </span>
                                                    <?php if ($item['is_reviewed']): ?>
                                                        <span class="inline-flex items-center text-xs text-green-600 font-semibold bg-green-50 px-1.5 py-0.5 rounded"><i class="fas fa-check-circle mr-1"></i> Diulas</span>
                                                    <?php endif; ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                                
                                <div class="text-left md:text-right md:w-48 flex-shrink-0 flex flex-col justify-between h-full min-h-[160px]">
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Total Belanja</p>
                                        <p class="font-bold text-gray-900 text-lg mb-2">Rp <?= number_format($order['total_amount'], 0, ',', '.'); ?></p>
                                        
                                        <a href="<?= BASEURL; ?>/account/invoice/<?= $order['id']; ?>" target="_blank" class="inline-block px-3 py-1 bg-gray-100 text-gray-600 text-xs font-bold rounded hover:bg-gray-200 transition mb-4"><i class="fas fa-file-invoice mr-1"></i> Invoice</a>
                                    </div>

                                    <div class="mt-auto pt-4 border-t border-gray-100 md:border-0 md:pt-0">
                                        <?php if($order['payment_status'] == 'unpaid'): ?>
                                            <a href="<?= BASEURL; ?>/checkout/payment/<?= $order['invoice']; ?>" class="block w-full bg-black text-white text-center py-2 rounded-lg text-sm font-medium hover:bg-gray-800 transition">Bayar Sekarang</a>
                                        <?php else: ?>
                                            <button onclick='openTrackingModal(<?= json_encode($order); ?>)' class="block w-full border border-gray-300 text-gray-700 hover:border-black hover:text-black text-center py-2 rounded-lg text-sm font-semibold transition mb-2"><i class="fas fa-truck mr-1"></i> Lacak Kurir</button>
                                            
                                            <?php if($order['status'] == 'shipped'): ?>
                                                <a href="<?= BASEURL; ?>/account/confirm_delivery/<?= $order['id']; ?>" onclick="return confirm('Apakah Anda yakin pesanan telah diterima dengan baik?')" class="block w-full bg-green-600 text-white text-center py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition mb-2">Konfirmasi Diterima</a>
                                            <?php elseif(in_array($order['status'], ['completed', 'delivered'])): ?>
                                                <?php if ($unreviewedCount > 0): ?>
                                                    <button onclick='openReviewModal(<?= json_encode($order); ?>)' class="block w-full bg-black text-white text-center py-2 rounded-lg text-sm font-medium hover:bg-gray-800 transition mb-2">Beri Ulasan</button>
                                                <?php else: ?>
                                                    <button disabled class="block w-full bg-gray-100 text-gray-400 text-center py-2 rounded-lg text-sm font-medium cursor-not-allowed mb-2">Selesai diulas</button>
                                                <?php endif; ?>
                                            <?php endif; ?>

                                            <!-- Tombol Refund / Status Refund -->
                                            <?php if(empty($order['refund'])): ?>
                                                <button onclick="openRefundModal(<?= $order['id']; ?>, '<?= $order['invoice']; ?>', <?= $order['total_amount']; ?>)" class="block w-full bg-red-50 text-red-600 border border-red-200 text-center py-2 rounded-lg text-sm font-medium hover:bg-red-100 transition"><i class="fas fa-undo mr-1"></i> Ajukan Refund</button>
                                            <?php else: ?>
                                                <div class="p-2.5 bg-gray-50 border border-gray-200 rounded-xl text-left">
                                                    <div class="flex items-center justify-between mb-1">
                                                        <span class="text-[10px] text-gray-500 font-semibold">Refund:</span>
                                                        <?php
                                                        $rStatus = $order['refund']['status'];
                                                        $rBadge = 'bg-yellow-100 text-yellow-800';
                                                        $rText = 'Pending';
                                                        if ($rStatus == 'under_review') { $rBadge = 'bg-blue-100 text-blue-800'; $rText = 'Ditinjau'; }
                                                        elseif ($rStatus == 'approved') { $rBadge = 'bg-green-100 text-green-800'; $rText = 'Disetujui'; }
                                                        elseif ($rStatus == 'rejected') { $rBadge = 'bg-red-100 text-red-800'; $rText = 'Ditolak'; }
                                                        ?>
                                                        <span class="px-1.5 py-0.5 <?= $rBadge; ?> text-[9px] font-bold rounded-full"><?= $rText; ?></span>
                                                    </div>
                                                    <button onclick='openRefundTrackingModal(<?= json_encode($order['refund']); ?>, "<?= $order['invoice']; ?>")' class="text-[10px] text-primary hover:underline font-semibold block mt-1"><i class="fas fa-search-plus mr-1"></i> Lacak Refund</button>
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 text-center">
                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-box-open text-3xl text-gray-400"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Belum ada pesanan</h3>
                        <p class="text-gray-500 text-sm mb-6">Anda belum pernah melakukan transaksi apa pun. Yuk mulai belanja sekarang!</p>
                        <a href="<?= BASEURL; ?>/catalog" class="inline-block bg-black text-white px-6 py-2.5 rounded-full text-sm font-medium hover:bg-gray-800 transition">
                            Mulai Belanja
                        </a>
                    </div>
                <?php endif; ?>
            
            <?php elseif($data['tab'] == 'wishlist'): ?>
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Wishlist Tersimpan</h2>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 text-center">
                    <i class="fas fa-heart text-5xl text-gray-200 mb-4"></i>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Wishlist Kosong</h3>
                    <p class="text-gray-500 text-sm">Anda belum menambahkan produk apapun ke dalam daftar keinginan.</p>
                </div>
            
            <?php elseif($data['tab'] == 'address'): ?>
                <?php $user = $data['user'] ?? []; ?>
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Alamat Pengiriman</h2>
                <?php if(!empty($user['address'])): ?>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <div class="border-2 border-black rounded-xl p-5 mb-4">
                        <div class="flex justify-between items-start mb-3">
                            <span class="bg-black text-white text-xs px-2.5 py-1 rounded-full font-bold">
                                <i class="fas fa-star mr-1 text-[10px]"></i>Utama
                            </span>
                            <a href="<?= BASEURL ?>/account?tab=settings" class="text-xs text-gray-500 hover:text-black font-semibold transition">
                                <i class="fas fa-pen mr-1"></i>Ubah
                            </a>
                        </div>
                        <p class="font-bold text-gray-900 text-sm"><?= htmlspecialchars($user['username']) ?></p>
                        <?php if($user['phone']): ?>
                            <p class="text-gray-500 text-sm mt-1"><i class="fas fa-phone text-xs mr-1.5 text-gray-400"></i><?= htmlspecialchars($user['phone']) ?></p>
                        <?php endif; ?>
                        <p class="text-gray-600 text-sm mt-2 leading-relaxed">
                            <i class="fas fa-map-marker-alt text-xs mr-1.5 text-gray-400"></i><?= nl2br(htmlspecialchars($user['address'])) ?>
                        </p>
                    </div>
                    <p class="text-xs text-gray-400">
                        <i class="fas fa-info-circle mr-1"></i>
                        Alamat ini akan otomatis terisi saat checkout. Anda bisa mengubahnya di halaman 
                        <a href="<?= BASEURL ?>/account?tab=settings" class="font-bold text-black hover:underline">Pengaturan Akun</a>.
                    </p>
                </div>
                <?php else: ?>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 text-center">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-map-marker-alt text-2xl text-gray-300"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Belum ada alamat tersimpan</h3>
                    <p class="text-gray-500 text-sm mb-5">Tambahkan alamat pengiriman Anda agar checkout lebih cepat.</p>
                    <a href="<?= BASEURL ?>/account?tab=settings" class="inline-block bg-black text-white px-6 py-2.5 rounded-full text-sm font-bold hover:bg-gray-800 transition">
                        <i class="fas fa-plus mr-1"></i> Tambah Alamat
                    </a>
                </div>
                <?php endif; ?>
            
            <?php elseif($data['tab'] == 'refund'): ?>
                <?php $user = $data['user'] ?? []; ?>
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Pengembalian Dana (Refund)</h2>
                
                <!-- Summary Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div>
                        <p class="text-sm text-gray-500 font-semibold mb-1">Total Saldo Refund Disetujui</p>
                        <p class="text-3xl font-extrabold text-primary">Rp <?= number_format($user['refund_balance'] ?? 0, 0, ',', '.'); ?></p>
                    </div>
                    <?php if (($user['refund_balance'] ?? 0) > 0): ?>
                        <button onclick="openWithdrawalModal(<?= $user['refund_balance']; ?>)" class="px-6 py-3 bg-black hover:bg-gray-800 text-white font-bold rounded-full shadow transition-all flex items-center">
                            <i class="fas fa-hand-holding-usd mr-2"></i> Tarik Dana Ke Rekening
                        </button>
                    <?php else: ?>
                        <button disabled class="px-6 py-3 bg-gray-100 text-gray-400 font-bold rounded-full cursor-not-allowed flex items-center">
                            <i class="fas fa-hand-holding-usd mr-2"></i> Saldo Kosong
                        </button>
                    <?php endif; ?>
                </div>

                <!-- Dual Columns / Sections -->
                <div class="space-y-6">
                    <!-- Section: Refund Requests -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4"><i class="fas fa-undo mr-2 text-gray-400"></i>Riwayat Pengajuan Refund</h3>
                        <?php if (empty($data['refund_requests'])): ?>
                            <p class="text-sm text-gray-500 text-center py-6">Belum ada pengajuan refund.</p>
                        <?php else: ?>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead>
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Tanggal</th>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Invoice</th>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Nominal</th>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Status</th>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 font-medium">
                                        <?php foreach ($data['refund_requests'] as $req): ?>
                                            <tr>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                                    <?= date('d M Y, H:i', strtotime($req['created_at'])); ?>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm font-bold text-gray-900">
                                                    <?= $req['invoice']; ?>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm font-bold text-gray-900">
                                                    Rp <?= number_format($req['amount'], 0, ',', '.'); ?>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm">
                                                    <?php
                                                    $rStatus = $req['status'];
                                                    $rBadge = 'bg-yellow-100 text-yellow-800';
                                                    $rText = 'Pending';
                                                    if ($rStatus == 'under_review') { $rBadge = 'bg-blue-100 text-blue-800'; $rText = 'Ditinjau'; }
                                                    elseif ($rStatus == 'approved') { $rBadge = 'bg-green-100 text-green-800'; $rText = 'Disetujui'; }
                                                    elseif ($rStatus == 'rejected') { $rBadge = 'bg-red-100 text-red-800'; $rText = 'Ditolak'; }
                                                    ?>
                                                    <span class="px-2 py-0.5 <?= $rBadge; ?> text-[10px] font-bold rounded-full"><?= $rText; ?></span>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm">
                                                    <button onclick='openRefundTrackingModal(<?= json_encode($req); ?>, "<?= $req['invoice']; ?>")' class="text-xs text-primary hover:underline font-bold"><i class="fas fa-search-plus mr-1"></i> Lacak</button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Section: Withdrawals -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4"><i class="fas fa-hand-holding-usd mr-2 text-gray-400"></i>Riwayat Penarikan Dana</h3>
                        <?php if (empty($data['withdrawals'])): ?>
                            <p class="text-sm text-gray-500 text-center py-6">Belum ada penarikan dana.</p>
                        <?php else: ?>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead>
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Tanggal</th>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Nominal</th>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Rekening Tujuan</th>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        <?php foreach ($data['withdrawals'] as $w): ?>
                                            <tr class="align-top font-medium">
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                                    <?= date('d M Y, H:i', strtotime($w['created_at'])); ?>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm font-bold text-gray-900">
                                                    Rp <?= number_format($w['amount'], 0, ',', '.'); ?>
                                                </td>
                                                <td class="px-4 py-3 text-sm text-gray-850">
                                                    <div class="font-bold text-gray-900"><?= htmlspecialchars($w['bank_name']); ?></div>
                                                    <div class="text-xs text-gray-500 font-mono"><?= htmlspecialchars($w['account_number']); ?> (a/n <?= htmlspecialchars($w['account_holder']); ?>)</div>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm">
                                                    <?php
                                                    $wStatus = $w['status'];
                                                    $wBadge = 'bg-yellow-100 text-yellow-800';
                                                    $wText = 'Pending';
                                                    if ($wStatus == 'processing') { $wBadge = 'bg-blue-100 text-blue-800'; $wText = 'Diproses'; }
                                                    elseif ($wStatus == 'completed') { $wBadge = 'bg-green-100 text-green-800'; $wText = 'Selesai'; }
                                                    elseif ($wStatus == 'failed') { $wBadge = 'bg-red-100 text-red-800'; $wText = 'Gagal'; }
                                                    ?>
                                                    <span class="px-2 py-0.5 <?= $wBadge; ?> text-[10px] font-bold rounded-full"><?= $wText; ?></span>
                                                    <?php if ($w['admin_notes']): ?>
                                                        <div class="text-[10px] text-gray-500 mt-1.5 max-w-[180px] whitespace-normal">
                                                            <span class="font-semibold text-gray-700">Catatan:</span> <?= htmlspecialchars($w['admin_notes']); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            <?php elseif($data['tab'] == 'settings'): ?>
                <?php $user = $data['user'] ?? []; ?>
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Pengaturan Akun</h2>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 space-y-8">
                    <form action="<?= BASEURL ?>/account/update_profile" method="POST" class="space-y-5">
                        
                        <!-- Informasi Profil -->
                        <h3 class="text-base font-bold text-gray-800 border-b border-gray-100 pb-3 flex items-center gap-2">
                            <i class="fas fa-user-circle text-gray-400"></i> Informasi Profil
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Nama / Username <span class="text-red-500">*</span></label>
                                <input type="text" name="username" value="<?= htmlspecialchars($user['username'] ?? $_SESSION['name']); ?>" required
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-black focus:border-black outline-none text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Alamat Email</label>
                                <input type="email" value="<?= htmlspecialchars($_SESSION['email'] ?? ''); ?>" disabled
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-gray-400 text-sm">
                                <p class="text-xs text-gray-400 mt-1"><i class="fas fa-lock mr-1"></i>Email tidak dapat diubah.</p>
                            </div>
                        </div>

                        <!-- Kontak & Alamat -->
                        <h3 class="text-base font-bold text-gray-800 border-b border-gray-100 pb-3 pt-2 flex items-center gap-2">
                            <i class="fas fa-map-marker-alt text-gray-400"></i> Kontak & Alamat Pengiriman
                        </h3>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Nomor HP / WhatsApp</label>
                            <input type="tel" name="phone" value="<?= htmlspecialchars($user['phone'] ?? ''); ?>"
                                placeholder="Contoh: 08123456789"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-black focus:border-black outline-none text-sm">
                            <p class="text-xs text-gray-400 mt-1"><i class="fas fa-info-circle mr-1"></i>Digunakan untuk konfirmasi pengiriman.</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Alamat Pengiriman Default</label>
                            <textarea name="address" rows="3" placeholder="Jalan, Nomor, RT/RW, Kelurahan, Kecamatan, Kota, Provinsi, Kode Pos"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-black focus:border-black outline-none text-sm resize-none"><?= htmlspecialchars($user['address'] ?? ''); ?></textarea>
                            <p class="text-xs text-gray-400 mt-1"><i class="fas fa-info-circle mr-1"></i>Alamat ini akan otomatis terisi saat checkout.</p>
                        </div>

                        <!-- Ubah Password -->
                        <h3 class="text-base font-bold text-gray-800 border-b border-gray-100 pb-3 pt-2 flex items-center gap-2">
                            <i class="fas fa-lock text-gray-400"></i> Ubah Password
                        </h3>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Password Lama</label>
                            <input type="password" name="current_password" placeholder="Masukkan password lama Anda"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-black focus:border-black outline-none text-sm">
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Password Baru</label>
                                <input type="password" name="new_password" placeholder="Min. 6 karakter"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-black focus:border-black outline-none text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Konfirmasi Password</label>
                                <input type="password" name="confirm_password" placeholder="Ulangi password baru"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-black focus:border-black outline-none text-sm">
                            </div>
                        </div>
                        <p class="text-xs text-gray-400">Biarkan kolom password kosong jika tidak ingin mengubah password.</p>
                        
                        <div class="pt-2">
                            <button type="submit" class="bg-black text-white px-7 py-2.5 rounded-full text-sm font-bold hover:bg-gray-800 transition shadow-md">
                                <i class="fas fa-save mr-1.5"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            <?php elseif($data['tab'] == 'chat'): ?>

                <!-- ── Chat History Header Card ───────────────────────────────────── -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-4">
                    <!-- Header -->
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gray-50">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-black rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-headset text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-bold text-gray-900 text-sm">Customer Service BAKUL</p>
                                <span class="inline-flex items-center gap-1 text-xs text-green-600 font-medium">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full inline-block animate-pulse"></span>
                                    Online · Siap membantu
                                </span>
                            </div>
                        </div>
                        <a href="<?= BASEURL ?>/chat" class="flex items-center gap-1.5 px-4 py-2 bg-black text-white text-xs font-semibold rounded-full hover:bg-gray-800 transition shadow-sm">
                            <i class="fas fa-paper-plane text-[10px]"></i> Kirim Pesan Baru
                        </a>
                    </div>

                    <!-- Chat bubble history -->
                    <div class="px-6 py-4 space-y-3 max-h-[520px] overflow-y-auto" id="chatHistoryBox">
                        <?php if (empty($data['chat_messages'])): ?>
                            <div class="text-center py-12">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-comments text-2xl text-gray-300"></i>
                                </div>
                                <p class="text-gray-500 text-sm font-medium">Belum ada percakapan</p>
                                <p class="text-gray-400 text-xs mt-1">Mulai chat dengan CS kami untuk bantuan pembelian.</p>
                                <a href="<?= BASEURL ?>/chat" class="inline-block mt-4 px-5 py-2 bg-black text-white text-sm font-semibold rounded-full hover:bg-gray-800 transition">
                                    Mulai Chat
                                </a>
                            </div>
                        <?php else: ?>
                            <?php
                            $prevDate = '';
                            foreach ($data['chat_messages'] as $msg):
                                $msgDate = date('d M Y', strtotime($msg['created_at']));
                                $isAdmin = $msg['is_admin'] == 1;
                            ?>
                                <!-- Date separator -->
                                <?php if ($msgDate !== $prevDate): $prevDate = $msgDate; ?>
                                <div class="flex items-center gap-3 my-4">
                                    <div class="flex-1 h-px bg-gray-100"></div>
                                    <span class="text-[10px] text-gray-400 font-semibold whitespace-nowrap bg-gray-50 px-2 py-0.5 rounded-full border border-gray-100"><?= $msgDate ?></span>
                                    <div class="flex-1 h-px bg-gray-100"></div>
                                </div>
                                <?php endif; ?>

                                <!-- Bubble -->
                                <div class="flex <?= $isAdmin ? 'justify-start' : 'justify-end' ?> items-end gap-2">
                                    <?php if ($isAdmin): ?>
                                    <div class="w-7 h-7 bg-black rounded-full flex items-center justify-center flex-shrink-0 mb-1">
                                        <i class="fas fa-headset text-white text-[10px]"></i>
                                    </div>
                                    <?php endif; ?>

                                    <div class="max-w-[72%]">
                                        <?php if ($isAdmin): ?>
                                        <p class="text-[10px] text-gray-400 font-semibold mb-1 ml-1">CS BAKUL</p>
                                        <?php endif; ?>

                                        <!-- Product tag (jika ada) -->
                                        <?php if (!empty($msg['product_name'])): ?>
                                        <a href="<?= BASEURL ?>/product/<?= htmlspecialchars($msg['product_slug']) ?>" class="flex items-center gap-1.5 text-[10px] text-gray-500 bg-gray-50 border border-gray-200 rounded-lg px-2 py-1 mb-1 hover:bg-gray-100 transition max-w-fit">
                                            <i class="fas fa-tag text-[9px]"></i> <?= htmlspecialchars($msg['product_name']) ?>
                                        </a>
                                        <?php endif; ?>

                                        <div class="<?= $isAdmin ? 'bg-gray-100 text-gray-800 rounded-tl-none' : 'bg-black text-white rounded-tr-none' ?> px-4 py-2.5 rounded-2xl text-sm leading-relaxed break-words">
                                            <?= nl2br(htmlspecialchars($msg['message'])) ?>
                                        </div>
                                        <p class="text-[10px] text-gray-400 mt-1 <?= $isAdmin ? 'text-left ml-1' : 'text-right mr-1' ?>">
                                            <?= date('H:i', strtotime($msg['created_at'])) ?>
                                            <?php if (!$isAdmin): ?>
                                            &nbsp;<i class="fas fa-check<?= $msg['is_read'] ? '-double text-blue-400' : ' text-gray-400' ?> text-[9px]"></i>
                                            <?php endif; ?>
                                        </p>
                                    </div>

                                    <?php if (!$isAdmin): ?>
                                    <div class="w-7 h-7 bg-gray-200 rounded-full flex items-center justify-center flex-shrink-0 mb-1">
                                        <i class="fas fa-user text-gray-500 text-[10px]"></i>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <!-- Quick reply box -->
                    <?php if (!empty($data['chat_messages'])): ?>
                    <div class="border-t border-gray-100 px-4 py-3 flex items-center gap-3 bg-gray-50">
                        <input type="text" id="quickChatInput" placeholder="Ketik pesan cepat..." class="flex-1 px-4 py-2 border border-gray-200 rounded-full text-sm focus:outline-none focus:border-gray-400 bg-white" onkeydown="if(event.key==='Enter') sendQuickChat()">
                        <button onclick="sendQuickChat()" class="w-9 h-9 bg-black text-white rounded-full flex items-center justify-center hover:bg-gray-800 transition flex-shrink-0">
                            <i class="fas fa-paper-plane text-xs"></i>
                        </button>
                    </div>
                    <?php endif; ?>
                </div>

            <?php elseif($data['tab'] == 'warranty'): ?>
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Riwayat Klaim Garansi Saya</h2>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <?php if (empty($data['warranty_claims'])): ?>
                        <div class="text-center py-10">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-shield-alt text-2xl text-gray-300"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Belum ada klaim garansi</h3>
                            <p class="text-gray-500 text-sm mb-4">Anda belum pernah mengajukan klaim garansi untuk produk Anda.</p>
                            <a href="<?= BASEURL; ?>/help/refund" class="inline-block bg-black text-white px-6 py-2.5 rounded-full text-sm font-bold hover:bg-gray-800 transition">Ajukan Klaim Sekarang</a>
                        </div>
                    <?php else: ?>
                        <div class="overflow-x-auto font-medium">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Tanggal</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Invoice</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Perangkat</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Kendala & Bukti</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <?php foreach ($data['warranty_claims'] as $claim): ?>
                                        <tr class="align-top">
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                                <?= date('d M Y, H:i', strtotime($claim['created_at'])); ?>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm font-bold text-gray-900">
                                                <?= htmlspecialchars($claim['invoice']); ?>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-900">
                                                <?= htmlspecialchars($claim['device_name']); ?>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-900 max-w-xs">
                                                <div class="mb-2 italic text-gray-600">"<?= htmlspecialchars($claim['reason']); ?>"</div>
                                                <?php if ($claim['attachment_path']): 
                                                    $ext = strtolower(pathinfo($claim['attachment_path'], PATHINFO_EXTENSION));
                                                    if (in_array($ext, ['mp4', 'mov', 'avi'])): ?>
                                                        <a href="<?= BASEURL; ?>/<?= $claim['attachment_path']; ?>" target="_blank" class="inline-flex items-center text-xs text-primary hover:underline font-semibold">
                                                            <i class="fas fa-video mr-1"></i> Lihat Bukti Video
                                                        </a>
                                                    <?php else: ?>
                                                        <a href="<?= BASEURL; ?>/<?= $claim['attachment_path']; ?>" target="_blank" class="inline-flex items-center text-xs text-primary hover:underline font-semibold">
                                                            <i class="fas fa-image mr-1"></i> Lihat Bukti Gambar
                                                        </a>
                                                    <?php endif; 
                                                endif; ?>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                                <?php
                                                $badge = 'bg-yellow-100 text-yellow-800';
                                                $stText = 'Pending';
                                                if ($claim['status'] == 'approved') { $badge = 'bg-green-100 text-green-800'; $stText = 'Disetujui'; }
                                                elseif ($claim['status'] == 'rejected') { $badge = 'bg-red-100 text-red-800'; $stText = 'Ditolak'; }
                                                ?>
                                                <span class="px-2 py-1 rounded-full text-xs font-bold <?= $badge; ?>"><?= $stText; ?></span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>

            <?php endif; ?>
        </div>

    </div>
</div>

<!-- Modal Lacak Kurir Premium -->
<div id="trackingModal" class="fixed inset-0 z-[100] hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 transition-all duration-300">
    <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full border border-gray-100 overflow-hidden transform scale-95 opacity-0 transition-all duration-300" id="trackingModalContent">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h3 class="font-bold text-gray-900"><i class="fas fa-truck text-black mr-2"></i> Lacak Pengiriman</h3>
            <button onclick="closeTrackingModal()" class="text-gray-400 hover:text-black transition">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-6 space-y-5">
            <!-- Kurir & Resi -->
            <div class="flex justify-between items-center text-sm border-b border-gray-100 pb-4">
                <div>
                    <p class="text-xs text-gray-400 uppercase font-semibold">Kurir</p>
                    <p class="font-bold text-gray-900" id="trackingCourier">-</p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-400 uppercase font-semibold">No. Resi</p>
                    <p class="font-mono font-bold text-gray-800 text-sm" id="trackingResi"></p>
                </div>
            </div>

            <!-- Alamat Penerima -->
            <div class="bg-gray-50 rounded-xl p-4 text-sm" id="trackingAddressBlock">
                <p class="text-xs text-gray-400 uppercase font-semibold mb-2"><i class="fas fa-map-marker-alt mr-1"></i>Dikirim ke</p>
                <p class="font-bold text-gray-900" id="trackingRecipient">-</p>
                <p class="text-gray-500 mt-0.5" id="trackingPhone">-</p>
                <p class="text-gray-600 mt-1 leading-relaxed" id="trackingAddress">-</p>
            </div>
            
            <!-- Tracking Timeline -->
            <div class="relative pl-6 border-l-2 border-gray-200 space-y-6" id="trackingTimeline">
                <!-- Timeline items will be injected here -->
            </div>
            
            <div class="pt-4 border-t border-gray-100 flex justify-end">
                <button type="button" onclick="closeTrackingModal()" class="px-6 py-2.5 bg-black text-white rounded-full text-sm font-semibold hover:bg-gray-800 transition shadow-md">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ulasan Premium -->
<div id="reviewModal" class="fixed inset-0 z-[100] hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 transition-all duration-300">
    <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full border border-gray-100 overflow-hidden transform scale-95 opacity-0 transition-all duration-300" id="reviewModalContent">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h3 class="font-bold text-gray-900"><i class="fas fa-star text-amber-400 mr-2"></i> Beri Ulasan Produk</h3>
            <button onclick="closeReviewModal()" class="text-gray-400 hover:text-black transition">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form action="<?= BASEURL; ?>/account/review" method="POST" class="p-6 space-y-6 max-h-[70vh] overflow-y-auto">
            <input type="hidden" name="order_id" id="modalOrderId">
            
            <div id="modalProductsContainer" class="space-y-6">
                <!-- Products list will be injected here -->
            </div>
            
            <div class="pt-4 border-t border-gray-100 flex justify-end space-x-3">
                <button type="button" onclick="closeReviewModal()" class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-full text-sm font-semibold hover:bg-gray-50 transition">Batal</button>
                <button type="submit" class="px-7 py-2.5 bg-black text-white rounded-full text-sm font-semibold hover:bg-gray-800 transition shadow-md">Kirim Ulasan</button>
            </div>
        </form>
    </div>
</div>

<style>
    .star-rating {
        display: inline-flex;
        flex-direction: row-reverse;
    }
    .star-rating input {
        display: none;
    }
    .star-rating label {
        color: #d1d5db; /* gray-300 */
        cursor: pointer;
        font-size: 1.8rem;
        padding: 0 0.15rem;
        transition: color 0.15s;
    }
    .star-rating label:hover,
    .star-rating label:hover ~ label,
    .star-rating input:checked ~ label {
        color: #f59e0b; /* amber-500 */
    }
</style>

<script>
    const reviewModal = document.getElementById('reviewModal');
    const reviewModalContent = document.getElementById('reviewModalContent');
    const modalOrderId = document.getElementById('modalOrderId');
    const modalProductsContainer = document.getElementById('modalProductsContainer');

    function openReviewModal(order) {
        modalOrderId.value = order.id;
        
        let html = '';
        order.items.forEach(item => {
            if (item.is_reviewed) return; // Skip already reviewed items
            
            html += `
                <div class="pb-6 border-b border-gray-100 last:border-0 last:pb-0 space-y-3">
                    <div>
                        <p class="font-bold text-sm text-gray-900">${escapeHtml(item.product_name)}</p>
                        <p class="text-xs text-gray-500">${escapeHtml(item.variant_name)}</p>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Rating Bintang</label>
                        <div class="star-rating">
                            <input type="radio" id="star-${item.product_id}-5" name="rating[${item.product_id}]" value="5" required>
                            <label for="star-${item.product_id}-5" title="Sangat Baik"><i class="fas fa-star"></i></label>
                            
                            <input type="radio" id="star-${item.product_id}-4" name="rating[${item.product_id}]" value="4">
                            <label for="star-${item.product_id}-4" title="Baik"><i class="fas fa-star"></i></label>
                            
                            <input type="radio" id="star-${item.product_id}-3" name="rating[${item.product_id}]" value="3">
                            <label for="star-${item.product_id}-3" title="Cukup"><i class="fas fa-star"></i></label>
                            
                            <input type="radio" id="star-${item.product_id}-2" name="rating[${item.product_id}]" value="2">
                            <label for="star-${item.product_id}-2" title="Buruk"><i class="fas fa-star"></i></label>
                            
                            <input type="radio" id="star-${item.product_id}-1" name="rating[${item.product_id}]" value="1">
                            <label for="star-${item.product_id}-1" title="Sangat Buruk"><i class="fas fa-star"></i></label>
                        </div>
                    </div>
                    
                    <div>
                        <label for="comment-${item.product_id}" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Ulasan Anda</label>
                        <textarea id="comment-${item.product_id}" name="comment[${item.product_id}]" rows="3" placeholder="Bagikan pendapat Anda tentang produk ini..." class="w-full px-4 py-2.5 border border-gray-300 rounded-2xl focus:ring-1 focus:ring-black focus:border-black outline-none text-sm bg-gray-50 resize-none" required></textarea>
                    </div>
                </div>
            `;
        });
        
        modalProductsContainer.innerHTML = html;
        
        // Show modal with animation
        reviewModal.classList.remove('hidden');
        setTimeout(() => {
            reviewModalContent.classList.remove('scale-95', 'opacity-0');
            reviewModalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeReviewModal() {
        reviewModalContent.classList.remove('scale-100', 'opacity-100');
        reviewModalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            reviewModal.classList.add('hidden');
        }, 300);
    }

    function escapeHtml(text) {
        if (!text) return '';
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }

    // Tracking Modal JS
    const trackingModal = document.getElementById('trackingModal');
    const trackingModalContent = document.getElementById('trackingModalContent');
    const trackingResi = document.getElementById('trackingResi');
    const trackingTimeline = document.getElementById('trackingTimeline');

    function openTrackingModal(order) {
        const resi = 'BK-EX' + order.invoice.replace(/[^0-9]/g, '') + order.id;
        trackingResi.innerText = resi;

        // Populate courier & address info
        const courierName = (order.courier || 'JNE') + ' — ' + (order.courier_service || 'REG');
        document.getElementById('trackingCourier').innerText = courierName;
        document.getElementById('trackingRecipient').innerText = order.recipient_name || order.customer_name || '-';
        document.getElementById('trackingPhone').innerText = order.recipient_phone || '-';
        document.getElementById('trackingAddress').innerText = order.shipping_address || 'Alamat belum tersedia';
        document.getElementById('trackingAddressBlock').style.display = order.shipping_address ? '' : 'none';

        
        let timelineEvents = [];
        const now = new Date(order.created_at);
        const formatTime = (d, offsetDays, hour, min) => {
            const temp = new Date(d);
            temp.setDate(temp.getDate() + offsetDays);
            return temp.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }) + `, ${hour}:${min}`;
        };

        if (order.status === 'pending') {
            timelineEvents = [
                { title: 'Menunggu Pembayaran', desc: 'Menunggu konfirmasi pembayaran oleh pelanggan.', time: formatTime(now, 0, '10', '00') }
            ];
        } else if (order.status === 'processing') {
            timelineEvents = [
                { title: 'Diproses Toko', desc: 'Pesanan sedang dipersiapkan dan dikemas oleh toko.', time: formatTime(now, 0, '14', '30') },
                { title: 'Pembayaran Diterima', desc: 'Pembayaran diverifikasi secara otomatis.', time: formatTime(now, 0, '10', '15') },
                { title: 'Pesanan Dibuat', desc: 'Pesanan berhasil dibuat di sistem.', time: formatTime(now, 0, '10', '00') }
            ];
        } else if (order.status === 'shipped') {
            timelineEvents = [
                { title: 'Dalam Perjalanan', desc: 'Paket sedang dibawa oleh kurir ke lokasi tujuan.', time: formatTime(now, 1, '09', '15') },
                { title: 'Diserahkan ke Kurir', desc: 'Paket diserahkan ke jasa kurir logistik BAKUL Express.', time: formatTime(now, 0, '18', '00') },
                { title: 'Pesanan Dikemas', desc: 'Pesanan telah diproses dan dikemas rapi.', time: formatTime(now, 0, '14', '30') },
                { title: 'Pembayaran Diterima', desc: 'Pembayaran diverifikasi secara otomatis.', time: formatTime(now, 0, '10', '15') }
            ];
        } else if (order.status === 'completed' || order.status === 'delivered') {
            timelineEvents = [
                { title: 'Paket Diterima', desc: 'Pesanan telah sampai dan diterima dengan baik.', time: formatTime(now, 2, '13', '45') },
                { title: 'Sedang Dikirim', desc: 'Kurir sedang menuju alamat pengiriman Anda.', time: formatTime(now, 2, '08', '30') },
                { title: 'Dalam Perjalanan', desc: 'Paket sedang dalam transit logistik.', time: formatTime(now, 1, '09', '15') },
                { title: 'Diserahkan ke Kurir', desc: 'Paket diserahkan ke jasa kurir logistik BAKUL Express.', time: formatTime(now, 0, '18', '00') },
                { title: 'Pesanan Dikemas', desc: 'Pesanan telah diproses dan dikemas rapi.', time: formatTime(now, 0, '14', '30') }
            ];
        } else if (order.status === 'cancelled') {
            timelineEvents = [
                { title: 'Pesanan Dibatalkan', desc: 'Pesanan dibatalkan secara sistem.', time: formatTime(now, 0, '11', '00') }
            ];
        } else {
            timelineEvents = [
                { title: 'Status Terkini: ' + order.status, desc: 'Silakan hubungi bantuan CS.', time: formatTime(now, 0, '10', '00') }
            ];
        }

        let html = '';
        timelineEvents.forEach((ev, idx) => {
            const isLatest = idx === 0;
            const dotColor = isLatest ? 'bg-black' : 'bg-gray-300';
            const textColor = isLatest ? 'text-gray-900 font-bold' : 'text-gray-500';
            
            html += `
                <div class="relative">
                    <span class="absolute -left-[31px] top-1.5 flex h-4 w-4 items-center justify-center rounded-full ${dotColor} border-4 border-white"></span>
                    <div>
                        <p class="text-sm ${textColor}">${escapeHtml(ev.title)}</p>
                        <p class="text-xs text-gray-500 mt-0.5">${escapeHtml(ev.desc)}</p>
                        <p class="text-[10px] text-gray-400 mt-1">${escapeHtml(ev.time)}</p>
                    </div>
                </div>
            `;
        });
        
        trackingTimeline.innerHTML = html;
        
        trackingModal.classList.remove('hidden');
        setTimeout(() => {
            trackingModalContent.classList.remove('scale-95', 'opacity-0');
            trackingModalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeTrackingModal() {
        trackingModalContent.classList.remove('scale-100', 'opacity-100');
        trackingModalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            trackingModal.classList.add('hidden');
        }, 300);
    }

    // Refund Submission Modal
    const refundModal = document.getElementById('refundModal');
    const refundModalContent = document.getElementById('refundModalContent');

    function openRefundModal(orderId, invoice, amount) {
        document.getElementById('refund_order_id').value = orderId;
        document.getElementById('refund_invoice_label').innerText = invoice;
        document.getElementById('refund_amount_label').innerText = 'Rp ' + Number(amount).toLocaleString('id-ID');
        
        refundModal.classList.remove('hidden');
        setTimeout(() => {
            refundModalContent.classList.remove('scale-95', 'opacity-0');
            refundModalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeRefundModal() {
        refundModalContent.classList.remove('scale-100', 'opacity-100');
        refundModalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            refundModal.classList.add('hidden');
        }, 300);
    }

    // Refund Tracking Modal
    const refundTrackingModal = document.getElementById('refundTrackingModal');
    const refundTrackingModalContent = document.getElementById('refundTrackingModalContent');

    function openRefundTrackingModal(refund, invoice) {
        document.getElementById('refundTrackInvoice').innerText = invoice;
        document.getElementById('refundTrackAmount').innerText = 'Rp ' + Number(refund.amount).toLocaleString('id-ID');
        document.getElementById('refundTrackReason').innerText = refund.reason;
        
        const trackingList = document.getElementById('refundTrackingList');
        let steps = [];
        const formatTime = (d) => {
            return new Date(d).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }) + `, ` + new Date(d).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        };
        
        steps.push({ title: 'Refund Diajukan', desc: 'Permintaan refund berhasil dibuat oleh pelanggan.', time: formatTime(refund.created_at) });
        
        if (refund.status === 'under_review') {
            steps.push({ title: 'Sedang Ditinjau', desc: refund.admin_notes || 'Admin sedang memeriksa kelengkapan berkas dan bukti refund.', time: formatTime(refund.updated_at) });
        } else if (refund.status === 'approved') {
            steps.push({ title: 'Sedang Ditinjau', desc: 'Berkas selesai diperiksa.', time: formatTime(refund.updated_at) });
            steps.push({ title: 'Refund Disetujui', desc: refund.admin_notes || 'Dana refund telah dikreditkan ke saldo akun Anda.', time: formatTime(refund.updated_at) });
        } else if (refund.status === 'rejected') {
            steps.push({ title: 'Sedang Ditinjau', desc: 'Berkas selesai diperiksa.', time: formatTime(refund.updated_at) });
            steps.push({ title: 'Refund Ditolak', desc: refund.admin_notes || 'Pengajuan refund ditolak oleh admin.', time: formatTime(refund.updated_at), error: true });
        }
        
        steps.reverse();
        
        let html = '';
        steps.forEach((st, idx) => {
            const isLatest = idx === 0;
            let dotColor = isLatest ? 'bg-black' : 'bg-gray-300';
            if (st.error) dotColor = 'bg-red-500';
            const textColor = isLatest ? 'text-gray-900 font-bold' : 'text-gray-500';
            
            html += `
                <div class="relative">
                    <span class="absolute -left-[31px] top-1.5 flex h-4 w-4 items-center justify-center rounded-full ${dotColor} border-4 border-white"></span>
                    <div>
                        <p class="text-sm ${textColor}">${escapeHtml(st.title)}</p>
                        <p class="text-xs text-gray-500 mt-0.5">${escapeHtml(st.desc)}</p>
                        <p class="text-[10px] text-gray-400 mt-1">${escapeHtml(st.time)}</p>
                    </div>
                </div>
            `;
        });
        
        trackingList.innerHTML = html;
        
        refundTrackingModal.classList.remove('hidden');
        setTimeout(() => {
            refundTrackingModalContent.classList.remove('scale-95', 'opacity-0');
            refundTrackingModalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeRefundTrackingModal() {
        refundTrackingModalContent.classList.remove('scale-100', 'opacity-100');
        refundTrackingModalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            refundTrackingModal.classList.add('hidden');
        }, 300);
    }

    // Withdrawal Modal
    const withdrawalModal = document.getElementById('withdrawalModal');
    const withdrawalModalContent = document.getElementById('withdrawalModalContent');

    function openWithdrawalModal(balance) {
        document.getElementById('withdraw_amount_input').max = balance;
        document.getElementById('withdraw_balance_label').innerText = 'Rp ' + Number(balance).toLocaleString('id-ID');
        
        withdrawalModal.classList.remove('hidden');
        setTimeout(() => {
            withdrawalModalContent.classList.remove('scale-95', 'opacity-0');
            withdrawalModalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeWithdrawalModal() {
        withdrawalModalContent.classList.remove('scale-100', 'opacity-100');
        withdrawalModalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            withdrawalModal.classList.add('hidden');
        }, 300);
    }

    function sendQuickChat() {
        const input = document.getElementById('quickChatInput');
        if (!input) return;
        const msg = input.value.trim();
        if (!msg) return;

        input.disabled = true;

        fetch('<?= BASEURL; ?>/chat/send', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ message: msg })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                input.value = '';
                window.location.href = '<?= BASEURL; ?>/account?tab=chat';
            } else {
                alert(data.message || 'Gagal mengirim pesan');
            }
        })
        .catch(err => {
            console.error(err);
            alert('Terjadi kesalahan jaringan');
        })
        .finally(() => {
            input.disabled = false;
        });
    }
</script>

<!-- Modal: Form Pengajuan Refund -->
<div id="refundModal" class="fixed inset-0 z-50 overflow-y-auto hidden flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm transition-all duration-300">
    <div id="refundModalContent" class="bg-white rounded-3xl max-w-md w-full p-6 shadow-xl transform scale-95 opacity-0 transition-all duration-300 border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900">Form Pengajuan Refund</h3>
            <button onclick="closeRefundModal()" class="text-gray-400 hover:text-black transition">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        
        <form action="<?= BASEURL; ?>/account/submit_refund" method="POST" enctype="multipart/form-files" class="space-y-4" onsubmit="this.action='<?= BASEURL; ?>/account/submit_refund';">
            <!-- wait, correct enctype is multipart/form-data for file uploads -->
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const f = document.querySelector('#refundModal form');
                    if(f) f.setAttribute('enctype', 'multipart/form-data');
                });
            </script>
            <input type="hidden" id="refund_order_id" name="order_id">
            
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Invoice Pesanan</label>
                <div id="refund_invoice_label" class="font-mono font-bold text-gray-900 text-sm"></div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Total Dana Refund</label>
                <div id="refund_amount_label" class="font-bold text-primary text-base"></div>
            </div>

            <div>
                <label for="refund_reason" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Alasan Pengembalian Dana</label>
                <textarea id="refund_reason" name="reason" rows="4" placeholder="Jelaskan alasan pengajuan refund Anda secara detail..." class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-1 focus:ring-black focus:border-black outline-none text-sm bg-gray-50 resize-none" required></textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Unggah Bukti Gambar (Maks. 2MB)</label>
                <div class="relative flex items-center justify-center border-2 border-dashed border-gray-300 rounded-xl py-6 hover:bg-gray-50 transition cursor-pointer" onclick="document.getElementById('refund_proof_file').click()">
                    <div class="text-center space-y-1">
                        <i class="fas fa-cloud-upload-alt text-2xl text-gray-400"></i>
                        <p class="text-xs text-gray-500 font-medium">Klik untuk pilih berkas</p>
                        <p class="text-[10px] text-gray-400" id="file_selected_name">Mendukung JPG, JPEG, PNG</p>
                    </div>
                    <input type="file" id="refund_proof_file" name="proof" class="hidden" accept="image/*" required onchange="document.getElementById('file_selected_name').innerText = this.files[0].name; document.getElementById('file_selected_name').className = 'text-[10px] text-green-600 font-bold';">
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-4">
                <button type="button" onclick="closeRefundModal()" class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-semibold rounded-full hover:bg-gray-50 transition">Batal</button>
                <button type="submit" class="px-5 py-2 bg-primary hover:bg-sky-700 text-white text-sm font-semibold rounded-full shadow transition">Kirim Pengajuan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Lacak Status Tracking Refund -->
<div id="refundTrackingModal" class="fixed inset-0 z-50 overflow-y-auto hidden flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm transition-all duration-300">
    <div id="refundTrackingModalContent" class="bg-white rounded-3xl max-w-md w-full p-6 shadow-xl transform scale-95 opacity-0 transition-all duration-300 border border-gray-100">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-900">Pelacakan Status Refund</h3>
            <button onclick="closeRefundTrackingModal()" class="text-gray-400 hover:text-black transition">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <div class="space-y-4 mb-6">
            <div class="grid grid-cols-2 gap-2 text-xs">
                <div>
                    <span class="text-gray-400">Invoice:</span>
                    <p class="font-mono font-bold text-gray-900 mt-0.5" id="refundTrackInvoice"></p>
                </div>
                <div>
                    <span class="text-gray-400">Jumlah Dana:</span>
                    <p class="font-bold text-primary mt-0.5" id="refundTrackAmount"></p>
                </div>
            </div>
            <div>
                <span class="text-xs text-gray-400">Alasan:</span>
                <p class="text-xs text-gray-700 mt-1 italic" id="refundTrackReason"></p>
            </div>
        </div>

        <div class="relative pl-6 border-l border-gray-200 ml-4 space-y-6" id="refundTrackingList">
            <!-- Timeline elements dynamically injected here -->
        </div>

        <div class="flex justify-end pt-6 border-t border-gray-100 mt-6">
            <button type="button" onclick="closeRefundTrackingModal()" class="px-5 py-2 bg-black text-white text-sm font-semibold rounded-full hover:bg-gray-800 transition">Tutup</button>
        </div>
    </div>
</div>

<!-- Modal: Form Penarikan Dana (Withdrawal) -->
<div id="withdrawalModal" class="fixed inset-0 z-50 overflow-y-auto hidden flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm transition-all duration-300">
    <div id="withdrawalModalContent" class="bg-white rounded-3xl max-w-md w-full p-6 shadow-xl transform scale-95 opacity-0 transition-all duration-300 border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900">Tarik Dana Refund</h3>
            <button onclick="closeWithdrawalModal()" class="text-gray-400 hover:text-black transition">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        
        <form action="<?= BASEURL; ?>/account/submit_withdrawal" method="POST" class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Saldo Tersedia</label>
                <div id="withdraw_balance_label" class="font-bold text-primary text-xl"></div>
            </div>

            <div>
                <label for="withdraw_bank_name" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Nama Bank <span class="text-red-500">*</span></label>
                <input type="text" id="withdraw_bank_name" name="bank_name" placeholder="BCA / Mandiri / BRI / GoPay / OVO..." required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-1 focus:ring-black focus:border-black outline-none text-sm bg-gray-50">
            </div>

            <div>
                <label for="withdraw_account_number" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Nomor Rekening / HP <span class="text-red-500">*</span></label>
                <input type="text" id="withdraw_account_number" name="account_number" placeholder="Contoh: 8010485934..." required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-1 focus:ring-black focus:border-black outline-none text-sm bg-gray-50">
            </div>

            <div>
                <label for="withdraw_account_holder" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Nama Pemilik Rekening <span class="text-red-500">*</span></label>
                <input type="text" id="withdraw_account_holder" name="account_holder" placeholder="Nama lengkap Anda..." required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-1 focus:ring-black focus:border-black outline-none text-sm bg-gray-50">
            </div>

            <div>
                <label for="withdraw_amount_input" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Nominal Penarikan <span class="text-red-500">*</span></label>
                <div class="relative">
                    <span class="absolute left-4 top-2.5 text-sm font-semibold text-gray-500">Rp</span>
                    <input type="number" id="withdraw_amount_input" name="amount" min="10000" placeholder="Min. 10.000" required class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-1 focus:ring-black focus:border-black outline-none text-sm bg-gray-50">
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-4">
                <button type="button" onclick="closeWithdrawalModal()" class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-semibold rounded-full hover:bg-gray-50 transition">Batal</button>
                <button type="submit" class="px-5 py-2 bg-black hover:bg-gray-800 text-white text-sm font-semibold rounded-full shadow transition">Ajukan Penarikan</button>
            </div>
        </form>
    </div>
</div>
<?php require_once __DIR__ . '/../../layout/footer.php'; ?>
