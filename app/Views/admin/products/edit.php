<?php require_once __DIR__ . '/../../layout/admin_header.php'; ?>

<?php
$product = $data['product'];
$first_variant = $data['first_variant'];
?>

<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Edit Produk</h1>
        <p class="text-sm text-gray-500 mt-1">Perbarui detail data produk dan varian utama.</p>
    </div>
    <a href="<?= BASEURL; ?>/admin/products" class="text-gray-500 hover:text-black font-medium text-sm transition">
        &larr; Kembali
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 md:p-8">
    <form action="<?= BASEURL; ?>/admin/product_update/<?= $product['id']; ?>" method="POST" enctype="multipart/form-data">
        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
            
            <div class="sm:col-span-6">
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Produk</label>
                <div class="mt-1">
                    <input type="text" name="name" id="name" required value="<?= htmlspecialchars($product['name']); ?>" class="shadow-sm focus:ring-black focus:border-black block w-full sm:text-sm border-gray-300 rounded-md py-2 px-3 border outline-none">
                </div>
            </div>

            <div class="sm:col-span-3">
                <label for="category_id" class="block text-sm font-medium text-gray-700">Kategori</label>
                <div class="mt-1">
                    <select id="category_id" name="category_id" required class="shadow-sm focus:ring-black focus:border-black block w-full sm:text-sm border-gray-300 rounded-md py-2 px-3 border outline-none bg-white">
                        <option value="">-- Pilih Kategori --</option>
                        <?php if(!empty($data['categories'])): foreach($data['categories'] as $cat): ?>
                            <option value="<?= $cat['id']; ?>" <?= $product['category_id'] == $cat['id'] ? 'selected' : ''; ?>><?= $cat['name']; ?></option>
                        <?php endforeach; endif; ?>
                    </select>
                </div>
            </div>

            <div class="sm:col-span-3">
                <label for="brand_id" class="block text-sm font-medium text-gray-700">Merek</label>
                <div class="mt-1">
                    <select id="brand_id" name="brand_id" required class="shadow-sm focus:ring-black focus:border-black block w-full sm:text-sm border-gray-300 rounded-md py-2 px-3 border outline-none bg-white">
                        <option value="">-- Pilih Merek --</option>
                        <?php if(!empty($data['brands'])): foreach($data['brands'] as $brand): ?>
                            <option value="<?= $brand['id']; ?>" <?= $product['brand_id'] == $brand['id'] ? 'selected' : ''; ?>><?= $brand['name']; ?></option>
                        <?php endforeach; endif; ?>
                    </select>
                </div>
            </div>

            <div class="sm:col-span-6">
                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi Lengkap</label>
                <div class="mt-1">
                    <textarea id="description" name="description" rows="5" required class="shadow-sm focus:ring-black focus:border-black block w-full sm:text-sm border-gray-300 rounded-md py-2 px-3 border outline-none"><?= htmlspecialchars($product['description']); ?></textarea>
                </div>
            </div>

            <div class="sm:col-span-6">
                <label class="block text-sm font-medium text-gray-700">Ganti Gambar Produk (Opsional)</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                    <div class="space-y-1 text-center">
                        <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                        <div class="flex text-sm text-gray-600 justify-center">
                            <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-black hover:underline focus-within:outline-none">
                                <span>Upload a file</span>
                                <input id="file-upload" name="image" type="file" class="sr-only">
                            </label>
                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                    </div>
                </div>
            </div>

            <!-- Detail Varian Awal (Model & Harga) -->
            <div class="sm:col-span-6 border-t border-gray-200 pt-6">
                <h3 class="text-lg font-medium text-gray-900">Detail Varian Utama</h3>
                <p class="text-xs text-gray-500 mt-1">Perbarui detail tipe model handphone, SKU, harga, dan stok varian utama ini.</p>
            </div>

            <div class="sm:col-span-3">
                <label for="storage" class="block text-sm font-medium text-gray-700">Model / Kapasitas <span class="text-red-500">*</span></label>
                <div class="mt-1">
                    <input type="text" name="storage" id="storage" required value="<?= htmlspecialchars($first_variant['storage'] ?? ''); ?>" placeholder="Contoh: iPhone 15 Pro Max ATAU 256GB" class="shadow-sm focus:ring-black focus:border-black block w-full sm:text-sm border-gray-300 rounded-md py-2 px-3 border outline-none">
                </div>
            </div>

            <div class="sm:col-span-3">
                <label for="color" class="block text-sm font-medium text-gray-700">Warna <span class="text-red-500">*</span></label>
                <div class="mt-1">
                    <input type="text" name="color" id="color" required value="<?= htmlspecialchars($first_variant['color'] ?? ''); ?>" placeholder="Contoh: Titanium Black ATAU Hitam" class="shadow-sm focus:ring-black focus:border-black block w-full sm:text-sm border-gray-300 rounded-md py-2 px-3 border outline-none">
                </div>
            </div>

            <div class="sm:col-span-3">
                <label for="sku" class="block text-sm font-medium text-gray-700">SKU Varian <span class="text-red-500">*</span></label>
                <div class="mt-1">
                    <input type="text" name="sku" id="sku" required value="<?= htmlspecialchars($first_variant['sku'] ?? ''); ?>" placeholder="Contoh: I15PM-256-BLK" class="shadow-sm focus:ring-black focus:border-black block w-full sm:text-sm border-gray-300 rounded-md py-2 px-3 border outline-none">
                </div>
            </div>

            <div class="sm:col-span-3">
                <label for="stock" class="block text-sm font-medium text-gray-700">Stok Awal <span class="text-red-500">*</span></label>
                <div class="mt-1">
                    <input type="number" name="stock" id="stock" required min="0" value="<?= (int)($first_variant['stock'] ?? 0); ?>" placeholder="Contoh: 20" class="shadow-sm focus:ring-black focus:border-black block w-full sm:text-sm border-gray-300 rounded-md py-2 px-3 border outline-none">
                </div>
            </div>

            <div class="sm:col-span-3">
                <label for="purchase_price" class="block text-sm font-medium text-gray-700">Harga Beli / Modal (Rp) <span class="text-red-500">*</span></label>
                <div class="mt-1">
                    <input type="number" name="purchase_price" id="purchase_price" required min="0" value="<?= (int)($first_variant['purchase_price'] ?? 0); ?>" placeholder="Contoh: 11000000" class="shadow-sm focus:ring-black focus:border-black block w-full sm:text-sm border-gray-300 rounded-md py-2 px-3 border outline-none">
                </div>
            </div>

            <div class="sm:col-span-3">
                <label for="price" class="block text-sm font-medium text-gray-700">Harga Jual (Rp) <span class="text-red-500">*</span></label>
                <div class="mt-1">
                    <input type="number" name="price" id="price" required min="0" value="<?= (int)($first_variant['original_price'] ?? $first_variant['price'] ?? 0); ?>" placeholder="Contoh: 15990000" class="shadow-sm focus:ring-black focus:border-black block w-full sm:text-sm border-gray-300 rounded-md py-2 px-3 border outline-none">
                </div>
            </div>

            <!-- Flash Sale Fields -->
            <div class="sm:col-span-6 border-t border-gray-150 pt-4 mt-4 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                <div class="sm:col-span-6">
                    <h4 class="text-sm font-bold text-gray-800 uppercase tracking-wider"><i class="fas fa-bolt text-amber-500 mr-1.5"></i> Konfigurasi Flash Sale (Opsional)</h4>
                    <p class="text-xs text-gray-500 mt-1">Kosongkan harga atau durasi jika tidak ingin mengaktifkan/ingin mematikan promo flash sale.</p>
                </div>

                <div class="sm:col-span-3">
                    <label for="flash_sale_price" class="block text-sm font-medium text-gray-700">Harga Flash Sale (Rp)</label>
                    <div class="mt-1">
                        <input type="number" name="flash_sale_price" id="flash_sale_price" min="0" value="<?= !empty($first_variant['flash_sale_price']) ? (int)$first_variant['flash_sale_price'] : ''; ?>" placeholder="Masukkan harga coret / diskon" class="shadow-sm focus:ring-black focus:border-black block w-full sm:text-sm border-gray-300 rounded-md py-2 px-3 border outline-none">
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <label for="flash_sale_duration" class="block text-sm font-medium text-gray-700">Durasi Flash Sale</label>
                    <div class="mt-1">
                        <select name="flash_sale_duration" id="flash_sale_duration" class="shadow-sm focus:ring-black focus:border-black block w-full sm:text-sm border-gray-300 rounded-md py-2 px-3 border outline-none bg-white">
                            <option value="0">-- Nonaktif / Stop Flash Sale --</option>
                            <option value="1" <?= !empty($first_variant['flash_sale_price']) && strtotime($first_variant['flash_sale_end']) > time() ? 'selected' : ''; ?>>Aktifkan (1 Jam)</option>
                            <option value="2">Aktifkan (2 Jam)</option>
                            <option value="4">Aktifkan (4 Jam)</option>
                            <option value="6">Aktifkan (6 Jam)</option>
                            <option value="12">Aktifkan (12 Jam)</option>
                            <option value="24">Aktifkan (24 Jam)</option>
                            <option value="48">Aktifkan (48 Jam)</option>
                        </select>
                    </div>
                </div>

                <?php if (!empty($first_variant['flash_sale_price']) && strtotime($first_variant['flash_sale_end']) > time()): ?>
                <div class="sm:col-span-6 bg-amber-50 border border-amber-200 rounded-xl p-4 flex items-center gap-3">
                    <i class="fas fa-clock text-amber-600 text-lg animate-pulse"></i>
                    <div>
                        <p class="text-xs font-bold text-amber-800">Flash Sale Sedang Aktif!</p>
                        <p class="text-[11px] text-amber-700 mt-0.5">
                            Berakhir pada: <span class="font-mono font-bold"><?= $first_variant['flash_sale_end']; ?></span> 
                            (± <?= ceil((strtotime($first_variant['flash_sale_end']) - time()) / 60); ?> menit lagi)
                        </p>
                    </div>
                </div>
                <?php endif; ?>
            </div>

        <div class="mt-8 pt-5 border-t border-gray-200 flex justify-end">
            <button type="submit" class="bg-black hover:bg-gray-800 text-white px-6 py-2.5 rounded-full text-sm font-medium transition shadow-sm">
                Perbarui Produk
            </button>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../../layout/admin_footer.php'; ?>
