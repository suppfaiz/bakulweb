<?php require_once __DIR__ . '/../../layout/admin_header.php'; ?>

<div class="mb-6 flex items-center gap-3">
    <a href="<?= BASEURL; ?>/admin/promos" class="w-9 h-9 bg-gray-100 hover:bg-gray-200 rounded-xl flex items-center justify-center text-gray-500 hover:text-black transition">
        <i class="fas fa-arrow-left"></i>
    </a>
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Tambah Banner Promo</h1>
        <p class="text-sm text-gray-500 mt-1">Buat slideshow banner promosi baru untuk dipasang di beranda.</p>
    </div>
</div>

<div class="max-w-2xl bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden p-6 sm:p-8">
    <form action="<?= BASEURL; ?>/admin/promo_store" method="POST" enctype="multipart/form-data" class="space-y-6">
        
        <div>
            <label class="block text-sm font-semibold text-gray-900 mb-2">Judul Banner</label>
            <input type="text" name="title" placeholder="Contoh: COMING SOON!" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-black focus:border-black outline-none bg-white font-medium" required>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-900 mb-2">Subjudul / Deskripsi Promo</label>
            <textarea name="subtitle" rows="3" placeholder="Deskripsikan penawaran promo atau rincian detail peluncuran..." class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-black focus:border-black outline-none bg-white font-medium resize-none" required></textarea>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-900 mb-2">File Gambar Banner</label>
            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover:border-gray-400 transition cursor-pointer relative bg-gray-50">
                <div class="space-y-1 text-center">
                    <i class="far fa-image text-gray-400 text-3xl mb-2"></i>
                    <div class="flex text-sm text-gray-600">
                        <label for="image-upload" class="relative cursor-pointer bg-white rounded-md font-semibold text-primary hover:text-sky-600 focus-within:outline-none">
                            <span>Unggah file gambar</span>
                            <input id="image-upload" name="image" type="file" class="sr-only" accept="image/*" onchange="previewFile()" required>
                        </label>
                        <p class="pl-1">atau seret ke sini</p>
                    </div>
                    <p class="text-xs text-gray-400">PNG, JPG, JPEG hingga 2MB (Rekomendasi rasio 4:5)</p>
                </div>
            </div>
            <!-- Image preview container -->
            <div id="preview-box" class="mt-4 hidden border border-gray-200 rounded-xl overflow-hidden max-w-xs relative bg-gray-50">
                <img id="image-preview" src="#" alt="Preview" class="w-full h-auto object-cover max-h-48">
                <button type="button" onclick="removePreview()" class="absolute top-2 right-2 w-7 h-7 bg-black/60 hover:bg-black text-white rounded-full flex items-center justify-center text-xs"><i class="fas fa-times"></i></button>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-900 mb-2">Tautan URL (Optional)</label>
                <input type="url" name="link_url" placeholder="Contoh: https://wa.me/..." class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-black focus:border-black outline-none bg-white font-medium">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-900 mb-2">Urutan Tampil (Sort Order)</label>
                <input type="number" name="sort_order" value="0" min="0" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-black focus:border-black outline-none bg-white font-medium" required>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <input type="checkbox" name="is_active" id="is_active" value="1" checked class="w-4 h-4 text-black border-gray-300 rounded focus:ring-black cursor-pointer">
            <label for="is_active" class="text-sm font-semibold text-gray-700 cursor-pointer">Aktifkan banner ini di halaman utama</label>
        </div>

        <div class="pt-4 border-t border-gray-150 flex justify-end gap-3">
            <a href="<?= BASEURL; ?>/admin/promos" class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-50 transition">Batal</a>
            <button type="submit" class="px-6 py-2.5 bg-black hover:bg-gray-800 text-white rounded-xl text-sm font-semibold transition shadow-sm">Simpan Banner</button>
        </div>

    </form>
</div>

<script>
function previewFile() {
    const preview = document.getElementById('image-preview');
    const previewBox = document.getElementById('preview-box');
    const file = document.getElementById('image-upload').files[0];
    const reader = new FileReader();

    reader.addEventListener("load", function () {
        preview.src = reader.result;
        previewBox.classList.remove('hidden');
    }, false);

    if (file) {
        reader.readAsDataURL(file);
    }
}

function removePreview() {
    const preview = document.getElementById('image-preview');
    const previewBox = document.getElementById('preview-box');
    const fileInput = document.getElementById('image-upload');
    fileInput.value = '';
    preview.src = '#';
    previewBox.classList.add('hidden');
}
</script>

<?php require_once __DIR__ . '/../../layout/admin_footer.php'; ?>
