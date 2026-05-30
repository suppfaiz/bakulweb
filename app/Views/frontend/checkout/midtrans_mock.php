<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['judul']; ?></title>
    <link href="<?= BASEURL; ?>/css/style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .pay-option { transition: all 0.2s; }
        .pay-option:hover { transform: translateX(4px); }
        .timer-bar { animation: shrink 15s linear forwards; }
        @keyframes shrink { from { width: 100%; } to { width: 0%; } }
        @keyframes fadeUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
        .fade-up { animation: fadeUp 0.4s ease-out forwards; }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 flex items-center justify-center min-h-screen p-4">

    <div class="bg-white w-full max-w-sm rounded-3xl shadow-2xl overflow-hidden fade-up">
        <!-- Midtrans Header -->
        <div class="bg-[#0e214d] px-6 pt-6 pb-5 text-center text-white relative overflow-hidden">
            <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 20px 20px;"></div>
            <div class="relative">
                <div class="flex items-center justify-center gap-2 mb-1">
                    <div class="w-6 h-6 bg-blue-400 rounded flex items-center justify-center">
                        <i class="fas fa-lock text-white text-xs"></i>
                    </div>
                    <span class="font-bold text-sm">Midtrans</span>
                    <span class="bg-green-400 text-green-900 text-[10px] font-black px-1.5 py-0.5 rounded-full">SANDBOX</span>
                </div>
                <p class="text-xs opacity-60 mt-1">Secure Payment Simulator</p>
            </div>
        </div>

        <!-- Order Info -->
        <div class="px-6 py-5 bg-slate-50 border-b border-gray-100">
            <div class="flex justify-between items-center text-sm">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Merchant</p>
                    <p class="font-bold text-gray-900">BAKUL Enterprise</p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Invoice</p>
                    <p class="font-mono text-xs font-bold text-gray-800"><?= htmlspecialchars($data['invoice']); ?></p>
                </div>
            </div>

            <?php 
            $amount = $data['amount'] ?? 0;
            ?>
            <div class="mt-4 bg-white border border-gray-200 rounded-2xl p-4 text-center">
                <p class="text-xs text-gray-400 mb-0.5">Total Pembayaran</p>
                <p class="text-2xl font-black text-gray-900">Rp <?= number_format((float)$amount, 0, ',', '.') ?></p>
            </div>

            <!-- Timer Bar -->
            <div class="mt-4">
                <div class="flex justify-between text-xs text-gray-400 mb-1.5">
                    <span><i class="fas fa-clock mr-1"></i>Batas waktu pembayaran</span>
                    <span id="timerLabel">15:00</span>
                </div>
                <div class="w-full h-1.5 bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full bg-blue-500 rounded-full timer-bar" id="timerBar"></div>
                </div>
            </div>
        </div>

        <!-- Payment Methods -->
        <div class="p-5 space-y-3">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Pilih Metode Pembayaran</p>
            
            <?php 
            $methods = [
                ['icon' => 'fa-qrcode', 'color' => 'blue', 'label' => 'QRIS / GoPay / OVO', 'sub' => 'Scan QR — Bayar Instan'],
                ['icon' => 'fa-university', 'color' => 'orange', 'label' => 'Virtual Account Bank', 'sub' => 'BCA · Mandiri · BNI · BRI'],
                ['icon' => 'fa-credit-card', 'color' => 'purple', 'label' => 'Kartu Kredit / Debit', 'sub' => 'Visa · Mastercard · JCB'],
            ];
            foreach($methods as $m): 
                $colorMap = [
                    'blue' => ['bg' => 'bg-blue-50', 'icon_bg' => 'bg-blue-100', 'icon_c' => 'text-blue-600', 'border' => 'hover:border-blue-400'],
                    'orange' => ['bg' => 'bg-orange-50', 'icon_bg' => 'bg-orange-100', 'icon_c' => 'text-orange-600', 'border' => 'hover:border-orange-400'],
                    'purple' => ['bg' => 'bg-purple-50', 'icon_bg' => 'bg-purple-100', 'icon_c' => 'text-purple-600', 'border' => 'hover:border-purple-400'],
                ];
                $c = $colorMap[$m['color']];
            ?>
            <a href="<?= BASEURL; ?>/checkout/success/<?= $data['invoice']; ?>" 
               class="pay-option flex items-center p-3.5 border-2 border-gray-100 rounded-2xl <?= $c['border'] ?> hover:<?= $c['bg'] ?> group cursor-pointer">
                <div class="w-10 h-10 <?= $c['icon_bg'] ?> <?= $c['icon_c'] ?> rounded-xl flex items-center justify-center mr-3 flex-shrink-0">
                    <i class="fas <?= $m['icon'] ?> text-base"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-gray-900 text-sm"><?= $m['label'] ?></p>
                    <p class="text-xs text-gray-400"><?= $m['sub'] ?></p>
                </div>
                <i class="fas fa-chevron-right text-gray-300 group-hover:translate-x-1 transition-transform ml-2"></i>
            </a>
            <?php endforeach; ?>
        </div>

        <div class="px-5 pb-5 text-center">
            <div class="border-t border-gray-100 pt-4">
                <a href="<?= BASEURL; ?>/account" 
                   class="text-sm text-red-400 hover:text-red-600 font-medium transition">
                    <i class="fas fa-times mr-1"></i>Batalkan Pembayaran
                </a>
                <p class="text-[10px] text-gray-300 mt-3">
                    <i class="fas fa-shield-halved mr-1"></i>
                    Transaksi dilindungi enkripsi SSL 256-bit
                </p>
            </div>
        </div>
    </div>

<script>
// Countdown timer (demo only)
let secs = 900; // 15 minutes
const timerLabel = document.getElementById('timerLabel');
const interval = setInterval(() => {
    secs--;
    if (secs <= 0) { clearInterval(interval); timerLabel.textContent = 'Expired'; return; }
    const m = Math.floor(secs / 60).toString().padStart(2, '0');
    const s = (secs % 60).toString().padStart(2, '0');
    timerLabel.textContent = `${m}:${s}`;
}, 1000);
</script>

</body>
</html>
