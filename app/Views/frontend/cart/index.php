<?php require_once __DIR__ . '/../../layout/header.php'; ?>

<div class="bg-white min-h-screen pt-24 pb-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 mb-10">Keranjang Belanja</h1>

        <div class="lg:grid lg:grid-cols-12 lg:gap-x-12 lg:items-start">
            <!-- Items -->
            <div class="lg:col-span-8">
                <?php if(!empty($data['cart_items'])): ?>
                    <ul role="list" class="border-t border-b border-gray-200 divide-y divide-gray-200">
                        <?php foreach($data['cart_items'] as $item): ?>
                        <li class="flex py-6 sm:py-10">
                            <div class="flex-shrink-0">
                                <div class="w-24 h-24 sm:w-32 sm:h-32 rounded-xl bg-gray-100 border border-gray-200 flex items-center justify-center overflow-hidden">
                                    <?php if($item['image']): ?>
                                        <img src="<?= BASEURL; ?>/<?= $item['image']; ?>" alt="<?= $item['name']; ?>" class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <span class="text-gray-400 text-xs text-center px-2">No Image</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="ml-4 flex-1 flex flex-col justify-between sm:ml-6">
                                <div class="relative pr-9 sm:grid sm:grid-cols-2 sm:gap-x-6 sm:pr-0">
                                    <div>
                                        <div class="flex justify-between">
                                            <h3 class="text-lg">
                                                <a href="#" class="font-medium text-gray-900 hover:text-black"><?= $item['name']; ?></a>
                                            </h3>
                                        </div>
                                        <div class="mt-1 flex text-sm">
                                            <p class="text-gray-500"><?= $item['color']; ?>, <?= $item['storage']; ?></p>
                                        </div>
                                        <p class="mt-2 text-sm font-medium text-gray-900">Rp <?= number_format($item['price'], 0, ',', '.'); ?></p>
                                    </div>

                                    <div class="mt-4 sm:mt-0 sm:pr-9">
                                        <label class="sr-only">Quantity</label>
                                        
                                        <form action="<?= BASEURL; ?>/cart/update" method="POST" id="update-form-<?= $item['variant_id']; ?>">
                                            <input type="hidden" name="variant_id" value="<?= $item['variant_id']; ?>">
                                            <div class="flex items-center space-x-2">
                                                <span class="text-sm text-gray-500 font-medium">Qty:</span>
                                                <select name="qty" onchange="document.getElementById('update-form-<?= $item['variant_id']; ?>').submit()" class="border border-gray-300 rounded-lg text-sm bg-white py-1 pl-2 pr-6 outline-none focus:border-black font-semibold">
                                                    <?php for ($i = 1; $i <= 10; $i++): ?>
                                                        <option value="<?= $i; ?>" <?= $i == $item['qty'] ? 'selected' : ''; ?>><?= $i; ?></option>
                                                    <?php endfor; ?>
                                                </select>
                                            </div>
                                        </form>
                                        
                                        <div class="absolute top-0 right-0">
                                            <a href="<?= BASEURL; ?>/cart/remove/<?= $item['variant_id']; ?>" class="-m-2 p-2 inline-flex text-gray-400 hover:text-red-500 transition-colors" title="Hapus barang">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-4 flex text-sm text-gray-700 space-x-2">
                                    <i class="fas fa-check text-green-500 mt-0.5"></i>
                                    <span class="font-bold">Total: Rp <?= number_format($item['total'], 0, ',', '.'); ?></span>
                                </p>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <div class="text-center py-16 border-2 border-dashed border-gray-200 rounded-3xl">
                        <i class="fas fa-shopping-cart text-5xl text-gray-300 mb-4"></i>
                        <h3 class="text-xl font-medium text-gray-900">Keranjang Masih Kosong</h3>
                        <p class="text-sm text-gray-500 mt-2">Anda belum menambahkan produk apapun ke dalam keranjang.</p>
                        <div class="mt-6">
                            <a href="<?= BASEURL; ?>/catalog" class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-full text-white bg-black hover:bg-gray-800 transition">
                                Mulai Belanja
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Order summary -->
            <section aria-labelledby="summary-heading" class="mt-16 bg-gray-50 rounded-3xl px-4 py-6 sm:p-6 lg:p-8 lg:mt-0 lg:col-span-4 border border-gray-100">
                <h2 id="summary-heading" class="text-lg font-bold text-gray-900">Ringkasan Pesanan</h2>

                <dl class="mt-6 space-y-4 text-sm text-gray-600">
                    <div class="flex items-center justify-between">
                        <dt>Subtotal</dt>
                        <dd class="font-medium text-gray-900">Rp <?= number_format($data['subtotal'] ?? 0, 0, ',', '.'); ?></dd>
                    </div>
                    <div class="flex items-center justify-between border-t border-gray-200 pt-4">
                        <dt class="flex items-center">
                            <span>Ongkos Kirim</span>
                        </dt>
                        <dd class="font-medium text-gray-900">Dihitung di Checkout</dd>
                    </div>
                    <div class="flex items-center justify-between border-t border-gray-200 pt-4">
                        <dt class="text-base font-bold text-gray-900">Total Harga</dt>
                        <dd class="text-base font-bold text-gray-900">Rp <?= number_format($data['total'] ?? 0, 0, ',', '.'); ?></dd>
                    </div>
                </dl>

                <div class="mt-8">
                    <?php if(!empty($data['cart_items'])): ?>
                    <a href="<?= BASEURL; ?>/checkout" class="block text-center w-full bg-black border border-transparent rounded-full shadow-sm py-4 px-4 text-base font-medium text-white hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black transition-all">
                        Lanjut ke Pembayaran
                    </a>
                    <?php else: ?>
                    <button disabled class="w-full bg-gray-300 border border-transparent rounded-full shadow-sm py-4 px-4 text-base font-medium text-white cursor-not-allowed">
                        Lanjut ke Pembayaran
                    </button>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>
