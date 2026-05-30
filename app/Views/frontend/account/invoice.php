<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['judul']; ?></title>
    <link href="<?= BASEURL; ?>/css/style.css" rel="stylesheet">
    <style>
        @media print {
            body { background: white; margin: 0; padding: 0; }
            .no-print { display: none !important; }
            .print-only { display: block !important; }
            .container { max-width: 100% !important; padding: 0 !important; }
            .border-gray-200 { border-color: #000 !important; }
            .shadow-sm { box-shadow: none !important; }
            @page { margin: 1cm; }
        }
    </style>
</head>
<body class="bg-gray-100 py-10 print:py-0">

<div class="container mx-auto max-w-4xl bg-white p-8 md:p-12 rounded-xl shadow-sm border border-gray-200">
    
    <!-- Action Bar -->
    <div class="flex justify-between items-center mb-8 pb-6 border-b border-gray-200 no-print">
        <a href="<?= BASEURL; ?>/account" class="text-sm font-medium text-gray-500 hover:text-black">&larr; Kembali ke Riwayat</a>
        <button onclick="window.print()" class="bg-black text-white px-5 py-2 rounded-lg font-medium shadow-md hover:bg-gray-800 transition">
            <i class="fas fa-print mr-2"></i> Cetak / Simpan PDF
        </button>
    </div>

    <!-- Header Invoice -->
    <div class="flex flex-col md:flex-row justify-between items-start mb-10 gap-6">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-gray-900 mb-2">BAKUL<span class="text-orange-500">.</span></h1>
            <p class="text-sm text-gray-500 max-w-xs">Jalan Jendral Sudirman No. 123<br>Kebayoran Baru, Jakarta Selatan<br>DKI Jakarta 12190</p>
        </div>
        <div class="text-left md:text-right">
            <h2 class="text-4xl font-bold text-gray-200 uppercase tracking-widest mb-2">Invoice</h2>
            <p class="font-bold text-gray-900 text-lg"><?= $data['order']['invoice']; ?></p>
            <p class="text-sm text-gray-500">Tanggal: <?= date('d M Y, H:i', strtotime($data['order']['created_at'])); ?></p>
        </div>
    </div>

    <!-- Info Pelanggan & Pembayaran -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
        <div>
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Ditagihkan Kepada:</h3>
            <p class="font-bold text-gray-900"><?= htmlspecialchars($data['order']['customer_name'] ?? 'Pelanggan POS'); ?></p>
            <?php if(!empty($data['order']['customer_email'])): ?>
                <p class="text-sm text-gray-600"><?= htmlspecialchars($data['order']['customer_email']); ?></p>
            <?php endif; ?>
        </div>
        <div>
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Metode Pembayaran:</h3>
            <p class="font-bold text-gray-900"><?= htmlspecialchars($data['order']['payment_method']); ?></p>
            <p class="text-sm text-gray-600">
                Status: 
                <?php if($data['order']['payment_status'] == 'paid'): ?>
                    <span class="text-green-600 font-bold">LUNAS</span>
                <?php else: ?>
                    <span class="text-red-500 font-bold">BELUM BAYAR</span>
                <?php endif; ?>
            </p>
        </div>
    </div>

    <!-- Tabel Produk -->
    <div class="mb-10 overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b-2 border-gray-900 text-gray-900">
                    <th class="py-3 font-bold text-sm">Produk</th>
                    <th class="py-3 font-bold text-sm text-center">Qty</th>
                    <th class="py-3 font-bold text-sm text-right">Harga Satuan</th>
                    <th class="py-3 font-bold text-sm text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data['items'] as $item): ?>
                <tr class="border-b border-gray-200">
                    <td class="py-4">
                        <p class="font-bold text-gray-900 text-sm"><?= htmlspecialchars($item['product_name']); ?></p>
                        <p class="text-xs text-gray-500"><?= htmlspecialchars($item['variant_name']); ?> (SKU: <?= htmlspecialchars($item['sku']); ?>)</p>
                    </td>
                    <td class="py-4 text-center text-sm"><?= $item['qty']; ?></td>
                    <td class="py-4 text-right text-sm">Rp <?= number_format($item['price'], 0, ',', '.'); ?></td>
                    <td class="py-4 text-right font-bold text-sm">Rp <?= number_format($item['price'] * $item['qty'], 0, ',', '.'); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Total -->
    <div class="flex justify-end">
        <div class="w-full md:w-1/3 space-y-3">
            <div class="flex justify-between text-sm text-gray-600">
                <span>Subtotal Barang</span>
                <span>Rp <?= number_format($data['order']['total_amount'], 0, ',', '.'); ?></span>
            </div>
            <div class="flex justify-between text-sm text-gray-600">
                <span>Ongkos Kirim</span>
                <span>Rp <?= number_format($data['order']['shipping_cost'], 0, ',', '.'); ?></span>
            </div>
            <div class="flex justify-between text-lg font-black text-gray-900 pt-3 border-t-2 border-gray-900">
                <span>TOTAL</span>
                <span>Rp <?= number_format($data['order']['total_amount'] + $data['order']['shipping_cost'], 0, ',', '.'); ?></span>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="mt-16 pt-8 border-t border-gray-200 text-center text-xs text-gray-400">
        <p>Terima kasih telah berbelanja di BAKUL. Invoice ini sah dan diterbitkan secara otomatis oleh sistem.</p>
        <p class="mt-1">Untuk bantuan, hubungi support@bakul.com atau WhatsApp 0812-3456-7890</p>
    </div>

</div>

<!-- FontAwesome (only loaded for the button icon) -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" class="no-print">
</body>
</html>
