<?php require_once __DIR__ . '/../../layout/admin_header.php'; ?>

<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Tambah Produk Baru</h1>
    </div>
    <a href="<?= BASEURL; ?>/admin/products" class="text-gray-500 hover:text-black font-medium text-sm transition">
        &larr; Kembali
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 md:p-8">
    <form action="<?= BASEURL; ?>/admin/product_store" method="POST" enctype="multipart/form-data">
        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
            
            <div class="sm:col-span-6">
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Produk</label>
                <div class="mt-1">
                    <input type="text" name="name" id="name" required class="shadow-sm focus:ring-black focus:border-black block w-full sm:text-sm border-gray-300 rounded-md py-2 px-3 border outline-none">
                </div>
            </div>

            <div class="sm:col-span-3">
                <label for="category_id" class="block text-sm font-medium text-gray-700">Kategori</label>
                <div class="mt-1">
                    <select id="category_id" name="category_id" required class="shadow-sm focus:ring-black focus:border-black block w-full sm:text-sm border-gray-300 rounded-md py-2 px-3 border outline-none bg-white">
                        <option value="">-- Pilih Kategori --</option>
                        <?php if(!empty($data['categories'])): foreach($data['categories'] as $cat): ?>
                            <option value="<?= $cat['id']; ?>"><?= $cat['name']; ?></option>
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
                            <option value="<?= $brand['id']; ?>"><?= $brand['name']; ?></option>
                        <?php endforeach; endif; ?>
                    </select>
                </div>
            </div>

            <div class="sm:col-span-6">
                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi Lengkap</label>
                <div class="mt-1">
                    <textarea id="description" name="description" rows="5" required class="shadow-sm focus:ring-black focus:border-black block w-full sm:text-sm border-gray-300 rounded-md py-2 px-3 border outline-none"></textarea>
                </div>
            </div>

            <div class="sm:col-span-6">
                <label class="block text-sm font-medium text-gray-700">Gambar Utama (Opsional saat ini)</label>
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
                <h3 class="text-lg font-medium text-gray-900">Detail Varian Awal</h3>
                <p class="text-xs text-gray-500 mt-1">Setiap produk wajib memiliki minimal satu varian (model/kapasitas, warna, harga, dan stok awal).</p>
            </div>

            <div class="sm:col-span-3">
                <label for="storage" class="block text-sm font-medium text-gray-700">Model / Kapasitas <span class="text-red-500">*</span></label>
                <div class="mt-1">
                    <input type="text" name="storage" id="storage" required placeholder="Contoh: iPhone 15 Pro Max ATAU 256GB" class="shadow-sm focus:ring-black focus:border-black block w-full sm:text-sm border-gray-300 rounded-md py-2 px-3 border outline-none">
                </div>
            </div>

            <div class="sm:col-span-3">
                <label for="color" class="block text-sm font-medium text-gray-700">Warna <span class="text-red-500">*</span></label>
                <div class="mt-1">
                    <input type="text" name="color" id="color" required placeholder="Contoh: Titanium Black ATAU Hitam" class="shadow-sm focus:ring-black focus:border-black block w-full sm:text-sm border-gray-300 rounded-md py-2 px-3 border outline-none">
                </div>
            </div>

            <div class="sm:col-span-3">
                <label for="sku" class="block text-sm font-medium text-gray-700">SKU Varian <span class="text-red-500">*</span></label>
                <div class="mt-1">
                    <input type="text" name="sku" id="sku" required placeholder="Contoh: I15PM-256-BLK" class="shadow-sm focus:ring-black focus:border-black block w-full sm:text-sm border-gray-300 rounded-md py-2 px-3 border outline-none">
                </div>
            </div>

            <div class="sm:col-span-3">
                <label for="stock" class="block text-sm font-medium text-gray-700">Stok Awal <span class="text-red-500">*</span></label>
                <div class="mt-1">
                    <input type="number" name="stock" id="stock" required min="0" placeholder="Contoh: 20" class="shadow-sm focus:ring-black focus:border-black block w-full sm:text-sm border-gray-300 rounded-md py-2 px-3 border outline-none">
                </div>
            </div>

            <div class="sm:col-span-3">
                <label for="purchase_price" class="block text-sm font-medium text-gray-700">Harga Beli / Modal (Rp) <span class="text-red-500">*</span></label>
                <div class="mt-1">
                    <input type="number" name="purchase_price" id="purchase_price" required min="0" placeholder="Contoh: 11000000" class="shadow-sm focus:ring-black focus:border-black block w-full sm:text-sm border-gray-300 rounded-md py-2 px-3 border outline-none">
                </div>
            </div>

            <div class="sm:col-span-3">
                <label for="price" class="block text-sm font-medium text-gray-700">Harga Jual (Rp) <span class="text-red-500">*</span></label>
                <div class="mt-1">
                    <input type="number" name="price" id="price" required min="0" placeholder="Contoh: 15990000" class="shadow-sm focus:ring-black focus:border-black block w-full sm:text-sm border-gray-300 rounded-md py-2 px-3 border outline-none">
                </div>
            </div>

        </div>

        <div class="mt-8 pt-5 border-t border-gray-200 flex justify-end">
            <button type="submit" class="bg-black hover:bg-gray-800 text-white px-6 py-2.5 rounded-full text-sm font-medium transition shadow-sm">
                Simpan Produk
            </button>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../../layout/admin_footer.php'; ?>
