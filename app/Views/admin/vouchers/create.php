<?php require_once __DIR__ . '/../../layout/admin_header.php'; ?>

<div class="mb-6 flex items-center gap-3">
    <a href="<?= BASEURL; ?>/admin/vouchers" class="w-9 h-9 bg-gray-100 hover:bg-gray-200 rounded-xl flex items-center justify-center text-gray-500 hover:text-black transition">
        <i class="fas fa-arrow-left"></i>
    </a>
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Buat Voucher Baru</h1>
        <p class="text-sm text-gray-500 mt-1">Buat kode voucher baru untuk dipromosikan ke pembeli.</p>
    </div>
</div>

<div class="max-w-2xl bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden p-6 sm:p-8">
    <form action="<?= BASEURL; ?>/admin/voucher_store" method="POST" class="space-y-6">
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-900 mb-2">Kode Voucher (Unik)</label>
                <input type="text" name="code" placeholder="Contoh: BAKULMERDEKA" style="text-transform: uppercase;" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-black focus:border-black outline-none bg-white font-mono font-bold tracking-wider" required>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-900 mb-2">Tipe Potongan</label>
                <select name="discount_type" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-black focus:border-black outline-none bg-white font-medium" required>
                    <option value="fixed">Potongan Rupiah (Rp)</option>
                    <option value="percentage">Persentase (%)</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-900 mb-2">Nilai Diskon</label>
                <input type="number" name="discount_amount" min="1" placeholder="Contoh: 50000 atau 10" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-black focus:border-black outline-none bg-white font-medium" required>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-900 mb-2">Min. Belanja (Rupiah)</label>
                <input type="number" name="min_spend" value="0" min="0" placeholder="Contoh: 150000" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-black focus:border-black outline-none bg-white font-medium" required>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-900 mb-2">Batas Penggunaan (Kuota / Opsional)</label>
                <input type="number" name="usage_limit" min="1" placeholder="Kosongkan jika tidak dibatasi" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-black focus:border-black outline-none bg-white font-medium">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-900 mb-2">Tanggal Kedaluwarsa (Opsional)</label>
                <input type="date" name="expiry_date" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-black focus:border-black outline-none bg-white font-medium">
            </div>
        </div>

        <div class="flex items-center gap-2">
            <input type="checkbox" name="is_active" id="is_active" value="1" checked class="w-4 h-4 text-black border-gray-300 rounded focus:ring-black cursor-pointer">
            <label for="is_active" class="text-sm font-semibold text-gray-700 cursor-pointer">Aktifkan voucher ini agar dapat langsung digunakan</label>
        </div>

        <div class="pt-4 border-t border-gray-150 flex justify-end gap-3">
            <a href="<?= BASEURL; ?>/admin/vouchers" class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-50 transition">Batal</a>
            <button type="submit" class="px-6 py-2.5 bg-black hover:bg-gray-800 text-white rounded-xl text-sm font-semibold transition shadow-sm">Buat Voucher</button>
        </div>

    </form>
</div>

<?php require_once __DIR__ . '/../../layout/admin_footer.php'; ?>
