<?php require_once __DIR__ . '/../../layout/admin_header.php'; ?>

<?php
$period     = $data['period'] ?? '30';
$report     = $data['report'] ?? [];
$report_all = $data['report_all'] ?? [];
$transactions = $data['transactions'] ?? [];
$monthly    = $data['monthly'] ?? [];
$activeTab  = $data['tab'] ?? 'report';

// --- Periode aktif ---
$gross_revenue    = (float)($report['gross_revenue'] ?? $report['pemasukan'] ?? 0);
$shipping_revenue = (float)($report['shipping_revenue'] ?? 0);
$total_discount   = (float)($report['total_discount'] ?? 0);
$nett_revenue     = (float)($report['nett_revenue'] ?? ($gross_revenue - $shipping_revenue - $total_discount));
$total_transaksi  = (int)($report['total_transaksi'] ?? 0);
$jumlah_lunas     = (int)($report['jumlah_lunas'] ?? 0);
$total_cogs       = (float)($report['total_cogs'] ?? 0);
$gross_profit     = (float)($report['gross_profit'] ?? 0);
$total_expenses   = (float)($report['total_expenses'] ?? 0);
$nett_profit      = (float)($report['nett_profit'] ?? 0);
// backward-compat alias
$pemasukan = $gross_revenue;

// --- Akumulasi semua waktu ---
$gross_revenue_all    = (float)($report_all['gross_revenue'] ?? $report_all['total_pemasukan'] ?? 0);
$shipping_revenue_all = (float)($report_all['shipping_revenue'] ?? 0);
$total_discount_all   = (float)($report_all['total_discount'] ?? 0);
$nett_revenue_all     = (float)($report_all['nett_revenue'] ?? ($gross_revenue_all - $shipping_revenue_all - $total_discount_all));
$total_cogs_all       = (float)($report_all['total_cogs'] ?? 0);
$gross_profit_all     = (float)($report_all['gross_profit'] ?? 0);
$total_expenses_all   = (float)($report_all['total_expenses'] ?? 0);
$nett_profit_all      = (float)($report_all['nett_profit'] ?? 0);
$total_pemasukan_all  = $gross_revenue_all; // backward-compat

$period_label = match($period) {
    '7'   => '7 Hari Terakhir',
    '30'  => '30 Hari Terakhir',
    '90'  => '90 Hari Terakhir',
    '365' => 'Tahun Ini',
    default => '30 Hari Terakhir'
};
?>

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900 font-sans tracking-tight">Finance & Laporan</h1>
    <p class="text-sm text-gray-500 mt-1">Laporan arus kas, harga pokok penjualan (COGS), laba bersih, beban operasional, dan rekonsiliasi bank.</p>
</div>

<!-- Tabs Nav -->
<div class="border-b border-gray-200 mb-6">
    <nav class="-mb-px flex space-x-8">
        <a href="<?= BASEURL ?>/admin/finance?tab=report" class="border-b-2 <?= $activeTab == 'report' ? 'border-primary text-primary font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' ?> pb-4 px-1 text-sm font-semibold transition-all">
            Laporan & Ringkasan
        </a>
        <a href="<?= BASEURL ?>/admin/finance?tab=expenses" class="border-b-2 <?= $activeTab == 'expenses' ? 'border-primary text-primary font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' ?> pb-4 px-1 text-sm font-semibold transition-all flex items-center gap-2">
            Beban & Pengeluaran
        </a>
        <a href="<?= BASEURL ?>/admin/finance?tab=reconciliation" class="border-b-2 <?= $activeTab == 'reconciliation' ? 'border-primary text-primary font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' ?> pb-4 px-1 text-sm font-semibold transition-all flex items-center gap-2">
            Rekonsiliasi Rekening
            <span class="inline-flex items-center justify-center px-2 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-800">
                <?= count($data['unreconciled_statements']); ?>
            </span>
        </a>
    </nav>
</div>

<?php if ($activeTab == 'report'): ?>
    <!-- TAB 1: REPORTS & SUMMARY -->
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-lg font-bold text-gray-900">Ringkasan Arus Kas & Profitabilitas</h2>
            <p class="text-xs text-gray-500">Analisa pendapatan kotor, beban pokok, operasional hingga laba bersih.</p>
        </div>
        <div class="flex items-center gap-3">
            <!-- Filter Period -->
            <form method="GET" action="<?= BASEURL ?>/admin/finance">
                <input type="hidden" name="tab" value="report">
                <select name="period" onchange="this.form.submit()" 
                    class="text-sm border-gray-300 rounded-lg bg-white border py-2 pl-3 pr-8 outline-none focus:ring-black focus:border-black shadow-sm">
                    <option value="7" <?= $period == '7' ? 'selected' : '' ?>>7 Hari Terakhir</option>
                    <option value="30" <?= $period == '30' ? 'selected' : '' ?>>30 Hari Terakhir</option>
                    <option value="90" <?= $period == '90' ? 'selected' : '' ?>>90 Hari Terakhir</option>
                    <option value="365" <?= $period == '365' ? 'selected' : '' ?>>Tahun Ini</option>
                </select>
            </form>
            <button onclick="window.print()" class="bg-black hover:bg-gray-800 text-white px-4 py-2 rounded-lg text-sm font-medium transition shadow-sm flex items-center gap-2">
                <i class="fas fa-print"></i> Cetak Laporan
            </button>
        </div>
    </div>

    <!-- Stat Cards Row — 6 Kartu P&L Flow -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
        <!-- 1. Gross Revenue -->
        <div class="col-span-1 bg-white p-4 rounded-2xl border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Gross Revenue</p>
                <div class="w-8 h-8 bg-blue-50 rounded-xl flex items-center justify-center text-blue-500">
                    <i class="fas fa-wallet text-xs"></i>
                </div>
            </div>
            <p class="text-lg font-black text-gray-900 leading-tight">Rp <?= number_format($gross_revenue, 0, ',', '.') ?></p>
            <p class="text-[10px] text-gray-400 mt-1"><?= $jumlah_lunas ?> pesanan lunas</p>
        </div>

        <!-- 2. Ongkos Kirim (pass-through) -->
        <div class="col-span-1 bg-white p-4 rounded-2xl border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Ongkos Kirim</p>
                <div class="w-8 h-8 bg-sky-50 rounded-xl flex items-center justify-center text-sky-500">
                    <i class="fas fa-truck text-xs"></i>
                </div>
            </div>
            <p class="text-lg font-black text-sky-700 leading-tight">Rp <?= number_format($shipping_revenue, 0, ',', '.') ?></p>
            <p class="text-[10px] text-gray-400 mt-1">Pass-through ke kurir</p>
        </div>

        <!-- 3. Diskon Voucher -->
        <div class="col-span-1 bg-white p-4 rounded-2xl border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Diskon Voucher</p>
                <div class="w-8 h-8 bg-pink-50 rounded-xl flex items-center justify-center text-pink-500">
                    <i class="fas fa-tag text-xs"></i>
                </div>
            </div>
            <p class="text-lg font-black text-pink-600 leading-tight">Rp <?= number_format($total_discount, 0, ',', '.') ?></p>
            <p class="text-[10px] text-gray-400 mt-1">Total diskon diberikan</p>
        </div>

        <!-- 4. COGS (Harga Beli) -->
        <div class="col-span-1 bg-white p-4 rounded-2xl border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">COGS Harga Beli</p>
                <div class="w-8 h-8 bg-orange-50 rounded-xl flex items-center justify-center text-orange-500">
                    <i class="fas fa-box-open text-xs"></i>
                </div>
            </div>
            <p class="text-lg font-black text-orange-700 leading-tight">Rp <?= number_format($total_cogs, 0, ',', '.') ?></p>
            <p class="text-[10px] text-gray-400 mt-1">Biaya pokok barang</p>
        </div>

        <!-- 5. Beban Operasional -->
        <div class="col-span-1 bg-white p-4 rounded-2xl border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Beban Operasional</p>
                <div class="w-8 h-8 bg-red-50 rounded-xl flex items-center justify-center text-red-500">
                    <i class="fas fa-arrow-trend-down text-xs"></i>
                </div>
            </div>
            <p class="text-lg font-black text-red-600 leading-tight">Rp <?= number_format($total_expenses, 0, ',', '.') ?></p>
            <p class="text-[10px] text-gray-400 mt-1">Gaji, sewa, marketing</p>
        </div>

        <!-- 6. Nett Profit -->
        <div class="col-span-1 p-4 rounded-2xl border shadow-sm <?= $nett_profit >= 0 ? 'bg-emerald-50 border-emerald-200' : 'bg-red-50 border-red-200' ?>">
            <div class="flex items-center justify-between mb-2">
                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Nett Profit</p>
                <div class="w-8 h-8 rounded-xl flex items-center justify-center <?= $nett_profit >= 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' ?>">
                    <i class="fas fa-chart-line text-xs"></i>
                </div>
            </div>
            <p class="text-lg font-black leading-tight <?= $nett_profit >= 0 ? 'text-emerald-800' : 'text-red-800' ?>">Rp <?= number_format($nett_profit, 0, ',', '.') ?></p>
            <p class="text-[10px] text-gray-400 mt-1">Margin: <?= $nett_revenue > 0 ? round(($nett_profit / $nett_revenue) * 100, 1) : 0 ?>%</p>
        </div>
    </div>

    <!-- Income Statement & Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-8">
        <!-- Laporan Laba Rugi Akurat -->
        <div class="lg:col-span-6 bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
            <h3 class="font-bold text-gray-900 mb-5 flex items-center gap-2">
                <i class="fas fa-file-invoice-dollar text-gray-400"></i> Laporan Laba Rugi (<?= $period_label ?>)
            </h3>
            <div class="space-y-2 text-sm">
                <!-- Gross Revenue -->
                <div class="flex justify-between items-center py-2.5 border-b border-gray-100">
                    <span class="text-gray-700 font-semibold">Gross Revenue (Total Bayar Pelanggan)</span>
                    <span class="font-bold text-gray-900">Rp <?= number_format($gross_revenue, 0, ',', '.') ?></span>
                </div>
                <!-- Kurang Ongkir -->
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-sky-600 font-medium flex items-center gap-1.5"><i class="fas fa-minus text-[9px]"></i> Ongkos Kirim (pass-through)</span>
                    <span class="font-semibold text-sky-700">(Rp <?= number_format($shipping_revenue, 0, ',', '.') ?>)</span>
                </div>
                <!-- Kurang Diskon -->
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-pink-500 font-medium flex items-center gap-1.5"><i class="fas fa-minus text-[9px]"></i> Diskon Voucher</span>
                    <span class="font-semibold text-pink-600">(Rp <?= number_format($total_discount, 0, ',', '.') ?>)</span>
                </div>
                <!-- Nett Revenue -->
                <div class="flex justify-between items-center py-2.5 px-3 rounded-xl bg-blue-50 border border-blue-100">
                    <span class="text-blue-900 font-bold">= Nett Revenue (Pendapatan Bersih)</span>
                    <span class="font-extrabold text-blue-900">Rp <?= number_format($nett_revenue, 0, ',', '.') ?></span>
                </div>
                <!-- Kurang COGS -->
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-orange-500 font-medium flex items-center gap-1.5"><i class="fas fa-minus text-[9px]"></i> Harga Pokok Penjualan (COGS)</span>
                    <span class="font-semibold text-orange-600">(Rp <?= number_format($total_cogs, 0, ',', '.') ?>)</span>
                </div>
                <!-- Gross Profit -->
                <div class="flex justify-between items-center py-2.5 px-3 rounded-xl bg-gray-50 border border-gray-200">
                    <span class="text-gray-800 font-bold">= Laba Kotor (Gross Profit)</span>
                    <span class="font-extrabold text-gray-900">Rp <?= number_format($gross_profit, 0, ',', '.') ?></span>
                </div>
                <!-- Kurang Beban -->
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-red-500 font-medium flex items-center gap-1.5"><i class="fas fa-minus text-[9px]"></i> Total Beban Operasional</span>
                    <span class="font-semibold text-red-600">(Rp <?= number_format($total_expenses, 0, ',', '.') ?>)</span>
                </div>
                <!-- Nett Profit -->
                <div class="flex justify-between items-center p-3 rounded-2xl <?= $nett_profit >= 0 ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-red-50 text-red-800 border border-red-200' ?>">
                    <span class="font-black text-base">= Laba Bersih (Nett Profit)</span>
                    <span class="font-black text-lg">Rp <?= number_format($nett_profit, 0, ',', '.') ?></span>
                </div>
            </div>

            <!-- All Time Financial Summary Info -->
            <div class="mt-6 pt-5 border-t border-gray-200">
                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Akumulasi Semua Waktu</h4>
                <div class="grid grid-cols-3 gap-2 text-center">
                    <div class="bg-gray-50 rounded-xl p-2.5">
                        <p class="text-[9px] text-gray-500 uppercase font-bold">Total Omset</p>
                        <p class="text-xs font-extrabold text-gray-800 mt-0.5">Rp <?= number_format($total_pemasukan_all/1000000, 1) ?>jt</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-2.5">
                        <p class="text-[9px] text-gray-500 uppercase font-bold">Total Beban</p>
                        <p class="text-xs font-extrabold text-gray-800 mt-0.5">Rp <?= number_format($total_expenses_all/1000000, 1) ?>jt</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-2.5">
                        <p class="text-[9px] text-gray-500 uppercase font-bold">Laba Bersih</p>
                        <p class="text-xs font-extrabold text-emerald-700 mt-0.5">Rp <?= number_format($nett_profit_all/1000000, 1) ?>jt</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tren Bulanan Chart (Revenue vs COGS) -->
        <div class="lg:col-span-6 flex flex-col">
            <?php if (!empty($monthly)): ?>
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden flex-1 flex flex-col justify-between">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-bold text-gray-900">Tren Revenue vs COGS (6 Bulan)</h3>
                    <div class="flex items-center gap-3 text-[10px] font-bold text-gray-400">
                        <span class="flex items-center gap-1"><span class="inline-block w-2.5 h-2.5 rounded-sm bg-gray-800"></span>Revenue</span>
                        <span class="flex items-center gap-1"><span class="inline-block w-2.5 h-2.5 rounded-sm bg-orange-400"></span>COGS</span>
                    </div>
                </div>
                <div class="p-6 flex-1 flex items-end">
                    <?php
                    $max_val = max(array_column($monthly, 'pemasukan') ?: [1]);
                    ?>
                    <div class="flex items-end gap-2 h-44 w-full">
                        <?php foreach($monthly as $m):
                            $rev_pct  = $max_val > 0 ? (($m['nett_revenue'] / $max_val) * 100) : 0;
                            $cogs_pct = $max_val > 0 ? ((min((float)$m['total_cogs'], (float)$m['nett_revenue']) / $max_val) * 100) : 0;
                        ?>
                        <div class="flex-1 flex flex-col items-center gap-1 group">
                            <!-- Bar pair: Revenue (gray) + COGS (orange) side by side -->
                            <div class="w-full flex items-end justify-center gap-0.5 h-36">
                                <!-- Revenue bar -->
                                <div class="flex-1 bg-gray-800 hover:bg-gray-600 rounded-t transition relative"
                                     style="height: <?= max($rev_pct, 3) ?>%"
                                     title="Rev: Rp <?= number_format($m['nett_revenue'], 0, ',', '.') ?>">
                                    <div class="absolute -top-6 left-1/2 -translate-x-1/2 text-[9px] font-bold text-gray-700 bg-white border px-1 py-0.5 rounded shadow-sm opacity-0 group-hover:opacity-100 transition whitespace-nowrap z-10">
                                        Rev Rp <?= number_format($m['nett_revenue']/1000000, 1) ?>jt
                                    </div>
                                </div>
                                <!-- COGS bar -->
                                <div class="flex-1 bg-orange-400 hover:bg-orange-500 rounded-t transition relative"
                                     style="height: <?= max($cogs_pct, 3) ?>%"
                                     title="COGS: Rp <?= number_format($m['total_cogs'], 0, ',', '.') ?>">
                                    <div class="absolute -top-6 left-1/2 -translate-x-1/2 text-[9px] font-bold text-orange-700 bg-white border px-1 py-0.5 rounded shadow-sm opacity-0 group-hover:opacity-100 transition whitespace-nowrap z-10">
                                        COGS Rp <?= number_format((float)$m['total_cogs']/1000000, 1) ?>jt
                                    </div>
                                </div>
                            </div>
                            <span class="text-[9px] text-gray-500 text-center font-medium leading-none"><?= substr($m['label'], 0, 3) ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>


    <!-- Tabel Riwayat Transaksi -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <h3 class="text-base font-bold text-gray-900">Riwayat Transaksi 
                <span class="text-sm font-normal text-gray-500">(<?= $period_label ?>)</span>
            </h3>
            <span class="text-xs text-gray-500"><?= count($transactions) ?> transaksi</span>
        </div>
        
        <?php if(!empty($transactions)): ?>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Invoice</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Pelanggan</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Metode</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status Bayar</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php foreach($transactions as $t): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3 font-mono text-xs text-gray-700 font-medium"><?= htmlspecialchars($t['invoice']) ?></td>
                        <td class="px-5 py-3 text-gray-850"><?= htmlspecialchars($t['customer_name']) ?></td>
                        <td class="px-5 py-3 text-gray-500 text-xs"><?= date('d M Y, H:i', strtotime($t['created_at'])) ?></td>
                        <td class="px-5 py-3 text-gray-600"><?= htmlspecialchars($t['payment_method'] ?? '-') ?></td>
                        <td class="px-5 py-3">
                            <?php if($t['payment_status'] == 'paid'): ?>
                                <span class="px-2 py-0.5 bg-green-100 text-green-700 rounded-full text-xs font-semibold">Lunas</span>
                            <?php else: ?>
                                <span class="px-2 py-0.5 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">Belum Bayar</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-5 py-3 text-right font-bold text-gray-900">
                            Rp <?= number_format((float)$t['total_amount'], 0, ',', '.') ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot class="bg-gray-50 border-t-2 border-gray-300">
                    <tr>
                        <td colspan="5" class="px-5 py-3 text-sm font-bold text-gray-700 text-right">Total Pemasukan (Lunas):</td>
                        <td class="px-5 py-3 text-right font-black text-gray-900">
                            Rp <?= number_format($pemasukan, 0, ',', '.') ?>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <?php else: ?>
        <div class="p-16 text-center text-gray-500">
            <i class="fas fa-chart-bar text-4xl text-gray-200 mb-4 block"></i>
            <p class="font-medium">Belum ada transaksi dalam periode ini</p>
            <p class="text-xs mt-1">Coba ganti filter periode di atas</p>
        </div>
        <?php endif; ?>
    </div>

<?php elseif ($activeTab == 'expenses'): ?>
    <!-- TAB 2: EXPENSE TRACKER & OPERATING COSTS -->
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-lg font-bold text-gray-900">Pencatatan Beban & Pengeluaran</h2>
            <p class="text-xs text-gray-500">Catat dan kelola beban operasional berkala untuk menghitung laba bersih.</p>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="openAddExpenseModal()" class="px-4 py-2 bg-black hover:bg-gray-800 text-white text-xs font-bold rounded-lg transition-all flex items-center gap-1.5 shadow-sm">
                <i class="fas fa-plus"></i> Catat Beban Baru
            </button>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white p-4 border border-gray-200 rounded-2xl shadow-sm mb-6">
        <form method="GET" action="<?= BASEURL ?>/admin/finance" class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <input type="hidden" name="tab" value="expenses">
            
            <!-- Filter Period -->
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Periode Waktu</label>
                <select name="period" onchange="this.form.submit()" class="w-full text-xs border border-gray-300 rounded-lg bg-gray-50 px-3 py-2 outline-none">
                    <option value="7" <?= $period == '7' ? 'selected' : '' ?>>7 Hari Terakhir</option>
                    <option value="30" <?= $period == '30' ? 'selected' : '' ?>>30 Hari Terakhir</option>
                    <option value="90" <?= $period == '90' ? 'selected' : '' ?>>90 Hari Terakhir</option>
                    <option value="365" <?= $period == '365' ? 'selected' : '' ?>>Tahun Ini</option>
                </select>
            </div>

            <!-- Filter Category -->
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Kategori Beban</label>
                <select name="expense_category" onchange="this.form.submit()" class="w-full text-xs border border-gray-300 rounded-lg bg-gray-50 px-3 py-2 outline-none">
                    <option value="">-- Semua Kategori --</option>
                    <?php foreach ($data['expense_categories'] as $cat): ?>
                        <option value="<?= $cat ?>" <?= ($_GET['expense_category'] ?? '') == $cat ? 'selected' : '' ?>><?= $cat ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Filter Search -->
            <div class="sm:col-span-2">
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Cari Keterangan</label>
                <div class="relative">
                    <input type="text" name="expense_search" value="<?= htmlspecialchars($_GET['expense_search'] ?? '') ?>" placeholder="Cari nama beban atau catatan..." class="w-full text-xs border border-gray-300 rounded-lg bg-gray-50 pl-3 pr-10 py-2 outline-none">
                    <button type="submit" class="absolute right-3 top-2 text-gray-400 hover:text-black">
                        <i class="fas fa-search text-xs"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Expenses Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Nama Beban</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Kategori</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Deskripsi/Catatan</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Jumlah Beban</th>
                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-155 bg-white">
                <?php if (empty($data['expenses'])): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400 text-sm">
                            <i class="fas fa-calculator text-3xl mb-3 block text-gray-200"></i>
                            Belum ada beban operasional tercatat pada periode ini.
                        </td>
                    </tr>
                <?php else: 
                    foreach ($data['expenses'] as $exp): ?>
                    <tr class="hover:bg-gray-50/70 transition align-middle">
                        <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                            <?= date('d M Y', strtotime($exp['date'])); ?>
                        </td>
                        <td class="px-6 py-4 font-bold text-gray-900">
                            <?= htmlspecialchars($exp['title']); ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php
                            $catColors = match($exp['category']) {
                                'Gaji' => 'bg-indigo-50 text-indigo-700 border-indigo-150',
                                'Sewa' => 'bg-amber-50 text-amber-700 border-amber-150',
                                'Marketing' => 'bg-pink-50 text-pink-700 border-pink-150',
                                'Operasional' => 'bg-blue-50 text-blue-700 border-blue-150',
                                default => 'bg-gray-50 text-gray-700 border-gray-150',
                            };
                            ?>
                            <span class="px-2 py-0.5 text-[10px] font-bold border rounded-full <?= $catColors ?>">
                                <?= htmlspecialchars($exp['category']); ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-500 max-w-xs truncate">
                            <?= htmlspecialchars($exp['description'] ?? '-'); ?>
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-black text-red-600 whitespace-nowrap">
                            Rp <?= number_format($exp['amount'], 0, ',', '.'); ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-xs font-semibold space-x-3">
                            <button onclick='openEditExpenseModal(<?= json_encode($exp); ?>)' class="text-blue-600 hover:text-blue-800 hover:underline">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <a href="<?= BASEURL; ?>/admin/expense_delete/<?= $exp['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus catatan beban pengeluaran ini?');" class="text-red-600 hover:text-red-800 hover:underline">
                                <i class="fas fa-trash-alt"></i> Hapus
                            </a>
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
            <tfoot class="bg-gray-50 border-t-2 border-gray-300">
                <tr>
                    <td colspan="4" class="px-6 py-3 text-right font-bold text-gray-700">Total Pengeluaran (Periode):</td>
                    <td class="px-6 py-3 text-right font-black text-red-650">
                        Rp <?= number_format($total_expenses, 0, ',', '.'); ?>
                    </td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Modal: Add Expense -->
    <div id="addExpenseModal" class="fixed inset-0 z-50 overflow-y-auto hidden flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm transition-all duration-300">
        <div id="addExpenseModalContent" class="bg-white rounded-3xl max-w-md w-full p-6 shadow-xl transform scale-95 opacity-0 transition-all duration-300 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">Catat Beban / Pengeluaran Baru</h3>
                <button onclick="closeAddExpenseModal()" class="text-gray-400 hover:text-black transition">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            
            <form action="<?= BASEURL; ?>/admin/expense_store" method="POST" class="space-y-4">
                <div>
                    <label for="title" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Nama / Keterangan Beban <span class="text-red-500">*</span></label>
                    <input type="text" id="title" name="title" placeholder="Contoh: Pembayaran Listrik Ruko" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-1 focus:ring-black focus:border-black outline-none text-sm bg-gray-50">
                </div>

                <div>
                    <label for="category" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Kategori Pengeluaran <span class="text-red-500">*</span></label>
                    <select id="category" name="category" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-1 focus:ring-black focus:border-black outline-none text-sm bg-gray-50">
                        <?php foreach ($data['expense_categories'] as $cat): ?>
                            <option value="<?= $cat ?>"><?= $cat ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="amount" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Nominal Biaya (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" id="amount" name="amount" min="100" placeholder="Contoh: 450000" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-1 focus:ring-black focus:border-black outline-none text-sm bg-gray-50">
                </div>

                <div>
                    <label for="date" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Tanggal Beban <span class="text-red-500">*</span></label>
                    <input type="date" id="date" name="date" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-1 focus:ring-black focus:border-black outline-none text-sm bg-gray-50">
                </div>

                <div>
                    <label for="description" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Catatan / Deskripsi Tambahan</label>
                    <textarea id="description" name="description" placeholder="Tulis rincian tambahan..." class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-1 focus:ring-black focus:border-black outline-none text-sm bg-gray-50 h-20 resize-none"></textarea>
                </div>

                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" onclick="closeAddExpenseModal()" class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-semibold rounded-full hover:bg-gray-50 transition">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-black hover:bg-gray-800 text-white text-sm font-semibold rounded-full shadow transition">Simpan Beban</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: Edit Expense -->
    <div id="editExpenseModal" class="fixed inset-0 z-50 overflow-y-auto hidden flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm transition-all duration-300">
        <div id="editExpenseModalContent" class="bg-white rounded-3xl max-w-md w-full p-6 shadow-xl transform scale-95 opacity-0 transition-all duration-300 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">Perbarui Data Beban</h3>
                <button onclick="closeEditExpenseModal()" class="text-gray-400 hover:text-black transition">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            
            <form action="<?= BASEURL; ?>/admin/expense_update" method="POST" class="space-y-4">
                <input type="hidden" id="edit_id" name="id">

                <div>
                    <label for="edit_title" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Nama / Keterangan Beban <span class="text-red-500">*</span></label>
                    <input type="text" id="edit_title" name="title" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-1 focus:ring-black focus:border-black outline-none text-sm bg-gray-50">
                </div>

                <div>
                    <label for="edit_category" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Kategori Pengeluaran <span class="text-red-500">*</span></label>
                    <select id="edit_category" name="category" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-1 focus:ring-black focus:border-black outline-none text-sm bg-gray-50">
                        <?php foreach ($data['expense_categories'] as $cat): ?>
                            <option value="<?= $cat ?>"><?= $cat ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="edit_amount" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Nominal Biaya (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" id="edit_amount" name="amount" min="100" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-1 focus:ring-black focus:border-black outline-none text-sm bg-gray-50">
                </div>

                <div>
                    <label for="edit_date" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Tanggal Beban <span class="text-red-500">*</span></label>
                    <input type="date" id="edit_date" name="date" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-1 focus:ring-black focus:border-black outline-none text-sm bg-gray-50">
                </div>

                <div>
                    <label for="edit_desc" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Catatan / Deskripsi Tambahan</label>
                    <textarea id="edit_desc" name="description" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-1 focus:ring-black focus:border-black outline-none text-sm bg-gray-50 h-20 resize-none"></textarea>
                </div>

                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" onclick="closeEditExpenseModal()" class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-semibold rounded-full hover:bg-gray-50 transition">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-black hover:bg-gray-800 text-white text-sm font-semibold rounded-full shadow transition">Perbarui Beban</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const addExpenseModal = document.getElementById('addExpenseModal');
        const addExpenseModalContent = document.getElementById('addExpenseModalContent');
        const editExpenseModal = document.getElementById('editExpenseModal');
        const editExpenseModalContent = document.getElementById('editExpenseModalContent');

        function openAddExpenseModal() {
            // Set current date
            document.getElementById('date').value = new Date().toISOString().slice(0, 10);
            
            addExpenseModal.classList.remove('hidden');
            setTimeout(() => {
                addExpenseModalContent.classList.remove('scale-95', 'opacity-0');
                addExpenseModalContent.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeAddExpenseModal() {
            addExpenseModalContent.classList.remove('scale-100', 'opacity-100');
            addExpenseModalContent.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                addExpenseModal.classList.add('hidden');
            }, 300);
        }

        function openEditExpenseModal(expense) {
            document.getElementById('edit_id').value = expense.id;
            document.getElementById('edit_title').value = expense.title;
            document.getElementById('edit_category').value = expense.category;
            document.getElementById('edit_amount').value = Math.round(expense.amount);
            document.getElementById('edit_date').value = expense.date;
            document.getElementById('edit_desc').value = expense.description || '';
            
            editExpenseModal.classList.remove('hidden');
            setTimeout(() => {
                editExpenseModalContent.classList.remove('scale-95', 'opacity-0');
                editExpenseModalContent.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeEditExpenseModal() {
            editExpenseModalContent.classList.remove('scale-100', 'opacity-100');
            editExpenseModalContent.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                editExpenseModal.classList.add('hidden');
            }, 300);
        }
    </script>

<?php elseif ($activeTab == 'reconciliation'):
$recon_summary = $data['reconciliation_summary'] ?? [];
$recon_total_statements   = (int)($recon_summary['total_statements'] ?? 0);
$recon_reconciled_count   = (int)($recon_summary['reconciled_count'] ?? 0);
$recon_unreconciled_count = (int)($recon_summary['unreconciled_count'] ?? 0);
$recon_total_inflow       = (float)($recon_summary['total_bank_inflow'] ?? 0);
$recon_reconciled_amount  = (float)($recon_summary['reconciled_amount'] ?? 0);
$recon_unreconciled_amount= (float)($recon_summary['unreconciled_amount'] ?? 0);
$recon_discrepancy        = (float)($recon_summary['discrepancy'] ?? 0);
?>
    <!-- TAB 3: BANK RECONCILIATION -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-lg font-bold text-gray-900">Kesesuaian Rekening Dana Masuk</h2>
            <p class="text-xs text-gray-500 mt-0.5">Hubungkan transaksi mutasi bank rekening dengan pesanan terdaftar di sistem untuk rekonsiliasi akurat.</p>
        </div>
        <button onclick="openAddStatementModal()" class="px-4 py-2 bg-black hover:bg-gray-800 text-white text-xs font-bold rounded-lg transition-all flex items-center gap-1.5 shadow-sm">
            <i class="fas fa-plus"></i> Tambah Mutasi Manual
        </button>
    </div>

    <!-- Reconciliation Summary Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4">
            <div class="flex items-center justify-between mb-2">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total Mutasi Masuk</p>
                <div class="w-8 h-8 bg-blue-50 rounded-xl flex items-center justify-center text-blue-500">
                    <i class="fas fa-bank text-xs"></i>
                </div>
            </div>
            <p class="text-xl font-black text-gray-900">Rp <?= number_format($recon_total_inflow, 0, ',', '.') ?></p>
            <p class="text-[10px] text-gray-400 mt-1"><?= $recon_total_statements ?> mutasi bank tercatat</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4">
            <div class="flex items-center justify-between mb-2">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Sudah Rekonsiliasi</p>
                <div class="w-8 h-8 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-500">
                    <i class="fas fa-check-double text-xs"></i>
                </div>
            </div>
            <p class="text-xl font-black text-emerald-700">Rp <?= number_format($recon_reconciled_amount, 0, ',', '.') ?></p>
            <p class="text-[10px] text-gray-400 mt-1"><?= $recon_reconciled_count ?> mutasi terhubung</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4">
            <div class="flex items-center justify-between mb-2">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Belum Rekonsiliasi</p>
                <div class="w-8 h-8 bg-amber-50 rounded-xl flex items-center justify-center text-amber-500">
                    <i class="fas fa-clock text-xs"></i>
                </div>
            </div>
            <p class="text-xl font-black text-amber-700">Rp <?= number_format($recon_unreconciled_amount, 0, ',', '.') ?></p>
            <p class="text-[10px] text-gray-400 mt-1"><?= $recon_unreconciled_count ?> mutasi menunggu</p>
        </div>
        <div class="rounded-2xl border shadow-sm p-4 <?= abs($recon_discrepancy) < 1 ? 'bg-emerald-50 border-emerald-200' : 'bg-red-50 border-red-200' ?>">
            <div class="flex items-center justify-between mb-2">
                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Selisih (Discrepancy)</p>
                <div class="w-8 h-8 rounded-xl flex items-center justify-center <?= abs($recon_discrepancy) < 1 ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' ?>">
                    <i class="fas fa-scale-balanced text-xs"></i>
                </div>
            </div>
            <p class="text-xl font-black <?= abs($recon_discrepancy) < 1 ? 'text-emerald-700' : 'text-red-700' ?>">
                <?= $recon_discrepancy >= 0 ? '+' : '' ?>Rp <?= number_format(abs($recon_discrepancy), 0, ',', '.') ?>
            </p>
            <p class="text-[10px] text-gray-500 mt-1"><?= abs($recon_discrepancy) < 1 ? 'Semua seimbang ✓' : 'Mutasi bank vs pesanan' ?></p>
        </div>
    </div>

    <!-- Reconciliation Grid Layout -->
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-start mb-8">
        <!-- Unreconciled Statements list -->
        <div class="xl:col-span-12 space-y-4">
            <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm p-6">
                <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-tasks text-gray-400"></i> Antrean Mutasi Rekening Dana Masuk
                </h3>
                
                <?php if (empty($data['unreconciled_statements'])): ?>
                    <p class="text-sm text-gray-500 text-center py-8">Semua mutasi rekening bank telah terekonsiliasi dengan baik.</p>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($data['unreconciled_statements'] as $stmt): ?>
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
                                <div class="space-y-1 flex-1">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs text-gray-500 font-bold"><?= date('d M Y, H:i', strtotime($stmt['statement_date'])); ?></span>
                                        <span class="px-1.5 py-0.5 bg-gray-200 text-gray-700 text-[10px] font-mono rounded font-bold"><?= htmlspecialchars($stmt['reference_code']); ?></span>
                                    </div>
                                    <div class="font-bold text-gray-900 text-sm"><?= htmlspecialchars($stmt['description']); ?></div>
                                    <div class="text-base font-black text-green-700">Rp <?= number_format($stmt['amount'], 0, ',', '.'); ?></div>
                                </div>
                                
                                <!-- Suggestions Panel -->
                                <div class="flex-1 border-t md:border-t-0 md:border-l border-gray-200 pt-3 md:pt-0 md:pl-4">
                                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Saran Kesesuaian Sistem:</h4>
                                    <?php
                                    $suggestions = [];
                                    foreach ($data['unreconciled_orders'] as $order) {
                                        // Invoice match
                                        if (stripos($stmt['description'], $order['invoice']) !== false && (float)$stmt['amount'] == (float)$order['total_amount']) {
                                            $suggestions[] = ['order' => $order, 'score' => 100, 'label' => 'Invoice Cocok (100%)'];
                                        } 
                                        // Amount match only
                                        elseif ((float)$stmt['amount'] == (float)$order['total_amount']) {
                                            $suggestions[] = ['order' => $order, 'score' => 80, 'label' => 'Nominal Cocok (80%)'];
                                        }
                                    }
                                    ?>
                                    
                                    <?php if (!empty($suggestions)): ?>
                                        <div class="space-y-2">
                                            <?php foreach ($suggestions as $sug): ?>
                                                <div class="flex items-center justify-between bg-white border border-gray-200 rounded-lg p-2.5 shadow-sm">
                                                    <div>
                                                        <span class="text-[10px] font-extrabold text-green-600 bg-green-50 px-1.5 py-0.5 rounded-full"><?= $sug['label']; ?></span>
                                                        <div class="text-xs font-bold text-gray-900 mt-1"><?= $sug['order']['invoice']; ?></div>
                                                        <div class="text-[10px] text-gray-500">a/n <?= htmlspecialchars($sug['order']['username']); ?> (Rp <?= number_format($sug['order']['total_amount'], 0, ',', '.'); ?>)</div>
                                                    </div>
                                                    <form action="<?= BASEURL ?>/admin/reconcile_match" method="POST">
                                                        <input type="hidden" name="statement_id" value="<?= $stmt['id']; ?>">
                                                        <input type="hidden" name="order_id" value="<?= $sug['order']['id']; ?>">
                                                        <button type="submit" class="px-3 py-1 bg-black hover:bg-gray-800 text-white text-xs font-bold rounded shadow transition">Hubungkan</button>
                                                    </form>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else: ?>
                                        <!-- No suggestions: Manual Match Selector -->
                                        <form action="<?= BASEURL ?>/admin/reconcile_match" method="POST" class="flex items-center gap-2">
                                            <input type="hidden" name="statement_id" value="<?= $stmt['id']; ?>">
                                            <select name="order_id" required class="text-xs border border-gray-300 rounded-lg bg-white px-2 py-1.5 max-w-[240px] outline-none">
                                                <option value="">-- Hubungkan Manual --</option>
                                                <?php foreach ($data['unreconciled_orders'] as $ord): ?>
                                                    <option value="<?= $ord['id']; ?>"><?= $ord['invoice']; ?> - <?= htmlspecialchars($ord['username']); ?> (Rp <?= number_format($ord['total_amount'], 0, ',', '.'); ?>)</option>
                                                <?php endforeach; ?>
                                            </select>
                                            <button type="submit" class="px-3 py-1.5 bg-gray-200 hover:bg-gray-300 text-gray-800 text-xs font-bold rounded transition">Pilih</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Riwayat Rekonsiliasi -->
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <h3 class="text-base font-bold text-gray-900">Riwayat Rekonsiliasi Rekening</h3>
            <span class="text-xs text-gray-500">Daftar mutasi yang telah diverifikasi dengan transaksi e-commerce</span>
        </div>
        
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-500 uppercase">Tanggal Mutasi</th>
                    <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-500 uppercase">Mutasi Bank</th>
                    <th class="px-5 py-3.5 text-right text-xs font-bold text-gray-500 uppercase">Nominal Mutasi</th>
                    <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-500 uppercase">Invoice</th>
                    <th class="px-5 py-3.5 text-right text-xs font-bold text-gray-500 uppercase">Nominal Pesanan</th>
                    <th class="px-5 py-3.5 text-center text-xs font-bold text-gray-500 uppercase">Selisih</th>
                    <th class="px-5 py-3.5 text-center text-xs font-bold text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                <?php
                $reconciledCount = 0;
                foreach ($data['all_statements'] as $stmt):
                    if ($stmt['status'] !== 'reconciled') continue;
                    $reconciledCount++;
                    $selisih = (float)$stmt['amount'] - (float)($stmt['order_amount'] ?? 0);
                    $selisih_abs = abs($selisih);
                ?>
                    <tr class="align-middle hover:bg-gray-50 transition">
                        <td class="px-5 py-3.5 whitespace-nowrap text-xs text-gray-500">
                            <?= date('d M Y, H:i', strtotime($stmt['statement_date'])); ?>
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="text-sm font-semibold text-gray-900"><?= htmlspecialchars($stmt['description']); ?></div>
                            <div class="text-[10px] text-gray-400 font-mono mt-0.5">Ref: <?= htmlspecialchars($stmt['reference_code']); ?></div>
                        </td>
                        <td class="px-5 py-3.5 text-right text-sm font-black text-green-700 whitespace-nowrap">
                            Rp <?= number_format($stmt['amount'], 0, ',', '.'); ?>
                        </td>
                        <td class="px-5 py-3.5 text-xs font-mono font-bold text-primary">
                            <?= htmlspecialchars($stmt['matched_invoice'] ?? '-'); ?>
                        </td>
                        <td class="px-5 py-3.5 text-right text-sm font-bold text-gray-900 whitespace-nowrap">
                            Rp <?= number_format((float)($stmt['order_amount'] ?? 0), 0, ',', '.'); ?>
                        </td>
                        <td class="px-5 py-3.5 text-center whitespace-nowrap">
                            <?php if ($selisih_abs < 1): ?>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-full">
                                    <i class="fas fa-check text-[9px]"></i> Cocok
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-red-100 text-red-700 text-xs font-bold rounded-full" title="Selisih: Rp <?= number_format($selisih, 0, ',', '.') ?>">
                                    <i class="fas fa-exclamation text-[9px]"></i>
                                    <?= $selisih > 0 ? '+' : '-' ?>Rp <?= number_format($selisih_abs, 0, ',', '.') ?>
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-5 py-3.5 whitespace-nowrap text-center">
                            <form action="<?= BASEURL ?>/admin/reconcile_unmatch" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan rekonsiliasi mutasi bank ini?');">
                                <input type="hidden" name="statement_id" value="<?= $stmt['id']; ?>">
                                <button type="submit" class="inline-flex items-center text-xs text-red-600 hover:text-red-800 hover:underline font-bold transition">
                                    <i class="fas fa-unlink mr-1"></i> Lepas
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                
                <?php if ($reconciledCount == 0): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-sm text-gray-500">Belum ada mutasi yang direkonsiliasikan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>


    <!-- Modal: Add Bank Statement Manual -->
    <div id="addStatementModal" class="fixed inset-0 z-50 overflow-y-auto hidden flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm transition-all duration-300">
        <div id="addStatementModalContent" class="bg-white rounded-3xl max-w-md w-full p-6 shadow-xl transform scale-95 opacity-0 transition-all duration-300 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">Tambah Mutasi Rekening Baru</h3>
                <button onclick="closeAddStatementModal()" class="text-gray-400 hover:text-black transition">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            
            <form action="<?= BASEURL; ?>/admin/add_bank_statement" method="POST" class="space-y-4">
                <div>
                    <label for="stmt_date" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Tanggal Mutasi <span class="text-red-500">*</span></label>
                    <input type="datetime-local" id="stmt_date" name="statement_date" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-1 focus:ring-black focus:border-black outline-none text-sm bg-gray-50">
                </div>

                <div>
                    <label for="stmt_desc" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Keterangan / Deskripsi Transfer <span class="text-red-500">*</span></label>
                    <input type="text" id="stmt_desc" name="description" placeholder="Contoh: TRF DR BUDI - INV-20260528001" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-1 focus:ring-black focus:border-black outline-none text-sm bg-gray-50">
                </div>

                <div>
                    <label for="stmt_amount" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Nominal Dana Masuk (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" id="stmt_amount" name="amount" min="100" placeholder="Contoh: 150000" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-1 focus:ring-black focus:border-black outline-none text-sm bg-gray-50">
                </div>

                <div>
                    <label for="stmt_ref" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Kode Referensi Bank <span class="text-red-500">*</span></label>
                    <input type="text" id="stmt_ref" name="reference_code" placeholder="Contoh: TX94810294" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-1 focus:ring-black focus:border-black outline-none text-sm bg-gray-50">
                </div>

                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" onclick="closeAddStatementModal()" class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-semibold rounded-full hover:bg-gray-50 transition">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-black hover:bg-gray-800 text-white text-sm font-semibold rounded-full shadow transition">Simpan Mutasi</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const addStatementModal = document.getElementById('addStatementModal');
        const addStatementModalContent = document.getElementById('addStatementModalContent');

        function openAddStatementModal() {
            // Set current time to input datetime-local
            const now = new Date();
            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
            document.getElementById('stmt_date').value = now.toISOString().slice(0, 16);
            
            addStatementModal.classList.remove('hidden');
            setTimeout(() => {
                addStatementModalContent.classList.remove('scale-95', 'opacity-0');
                addStatementModalContent.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeAddStatementModal() {
            addStatementModalContent.classList.remove('scale-100', 'opacity-100');
            addStatementModalContent.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                addStatementModal.classList.add('hidden');
            }, 300);
        }
    </script>
<?php endif; ?>

<?php require_once __DIR__ . '/../../layout/admin_footer.php'; ?>
