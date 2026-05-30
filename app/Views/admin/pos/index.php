<?php require_once __DIR__ . '/../../layout/admin_header.php'; ?>

<div class="mb-4">
    <h1 class="text-2xl font-bold text-gray-900">Point of Sale (Kasir)</h1>
</div>

<div class="flex flex-col lg:flex-row gap-6 h-[calc(100vh-140px)]">
    <!-- Katalog Produk POS -->
    <div class="flex-1 bg-white rounded-2xl shadow-sm border border-gray-200 flex flex-col overflow-hidden">
        <div class="p-4 border-b border-gray-200 flex space-x-2">
            <div class="relative flex-1">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                <input type="text" id="posSearch" placeholder="Cari nama produk, SKU, barcode..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-black focus:border-black outline-none text-sm">
            </div>
            <button class="bg-gray-100 border border-gray-200 p-2 rounded-lg text-gray-600 hover:bg-gray-200"><i class="fas fa-barcode"></i></button>
        </div>
        <div class="flex-1 p-4 overflow-y-auto grid grid-cols-2 md:grid-cols-3 gap-4 content-start" id="posCatalog">
            <p class="text-sm text-gray-500 col-span-3 text-center py-10">Mencari produk...</p>
        </div>
    </div>

    <!-- Cart POS -->
    <div class="w-full lg:w-96 bg-white rounded-2xl shadow-sm border border-gray-200 flex flex-col overflow-hidden">
        <div class="p-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <h2 class="text-sm font-bold text-gray-900">Keranjang Kasir</h2>
            <button id="clearCart" class="text-xs text-red-500 hover:underline">Kosongkan</button>
        </div>
        
        <div class="flex-1 p-4 overflow-y-auto space-y-4" id="posCart">
            <p class="text-xs text-gray-500 text-center py-10">Keranjang masih kosong</p>
        </div>

        <div class="p-4 border-t border-gray-200 bg-gray-50">
            <div class="flex justify-between text-lg font-bold text-gray-900 mb-4">
                <span>Total</span>
                <span id="posTotal">Rp 0</span>
            </div>
            <div class="mb-4">
                <label class="block text-xs font-medium text-gray-700 mb-1">Uang Diterima (Rp)</label>
                <input type="number" id="posAmountPaid" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-black focus:border-black outline-none text-sm" placeholder="Contoh: 1500000">
            </div>
            <button id="posCheckoutBtn" class="w-full bg-black text-white py-3 rounded-xl font-bold hover:bg-gray-800 transition shadow-md disabled:bg-gray-400">
                Bayar & Cetak
            </button>
        </div>
    </div>
</div>

<!-- Modal Struk / Receipt -->
<div id="changeModal" class="hidden fixed inset-0 bg-black bg-opacity-60 z-50 flex items-center justify-center p-4 backdrop-blur-sm">
    <div class="bg-white rounded-2xl max-w-sm w-full mx-4 overflow-hidden shadow-2xl">
        <div class="p-6 text-center border-b border-gray-100">
            <div class="w-14 h-14 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-2xl mx-auto mb-3">
                <i class="fas fa-check"></i>
            </div>
            <h3 class="text-lg font-black text-gray-900 mb-0.5">Transaksi Berhasil!</h3>
            <p class="text-sm text-gray-500" id="successInvoice">INV-12345</p>
        </div>

        <!-- Struk Area (Printable) -->
        <div id="receiptArea" class="p-5 font-mono text-xs">
            <div class="text-center mb-4">
                <p class="font-black text-sm text-gray-900">BAKUL Enterprise</p>
                <p class="text-gray-500">Jl. Sudirman No.1, Jakarta</p>
                <p class="text-gray-500" id="receiptDate"></p>
            </div>
            <div class="border-t border-dashed border-gray-300 my-3"></div>
            <table class="w-full text-xs" id="receiptItems"></table>
            <div class="border-t border-dashed border-gray-300 my-3"></div>
            <div class="flex justify-between font-bold text-sm">
                <span>TOTAL</span>
                <span id="receiptTotal"></span>
            </div>
            <div class="flex justify-between text-gray-600 mt-1">
                <span>TUNAI</span>
                <span id="receiptPaid"></span>
            </div>
            <div class="flex justify-between font-bold text-green-700 mt-1 text-sm">
                <span>KEMBALI</span>
                <span id="successChange">Rp 0</span>
            </div>
            <div class="border-t border-dashed border-gray-300 my-3"></div>
            <p class="text-center text-gray-400">Terima kasih telah berbelanja!</p>
            <p class="text-center text-gray-400">Barang yang sudah dibeli</p>
            <p class="text-center text-gray-400">tidak dapat dikembalikan.</p>
        </div>

        <div class="p-4 pt-0 flex gap-3">
            <button onclick="window.print()" class="flex-1 border border-gray-300 text-gray-700 py-2.5 rounded-xl font-semibold text-sm hover:bg-gray-50 transition flex items-center justify-center gap-2">
                <i class="fas fa-print"></i> Cetak Struk
            </button>
            <button onclick="closeChangeModal()" class="flex-1 bg-black text-white py-2.5 rounded-xl font-semibold text-sm hover:bg-gray-800 transition">
                Selesai
            </button>
        </div>
    </div>
</div>

<style>
@media print {
    body > *:not(#changeModal) { display: none !important; }
    #changeModal { display: flex !important; position: static !important; background: none !important; }
    #changeModal > div { box-shadow: none !important; width: 280px !important; margin: 0 auto !important; }
    #changeModal .p-4.pt-0 { display: none !important; }
    #changeModal .p-6 { padding: 8px !important; }
}
</style>

<script>
    let posCart = [];
    let posTotal = 0;
    const formatRp = (num) => 'Rp ' + parseInt(num).toLocaleString('id-ID');

    // Search Products
    function searchProducts(query = '') {
        fetch('<?= BASEURL; ?>/admin/api_pos_search?q=' + encodeURIComponent(query))
            .then(res => res.json())
            .then(data => {
                const catalog = document.getElementById('posCatalog');
                catalog.innerHTML = '';
                if(data.length === 0) {
                    catalog.innerHTML = '<p class="text-sm text-gray-500 col-span-3 text-center py-10">Tidak ada produk ditemukan.</p>';
                    return;
                }
                data.forEach(p => {
                    const el = document.createElement('div');
                    el.className = `border border-gray-200 rounded-xl p-3 cursor-pointer hover:border-black hover:shadow-md transition bg-white flex flex-col justify-between ${p.stock <= 0 ? 'opacity-50' : ''}`;
                    el.onclick = () => { if(p.stock > 0) addToCart(p); else showToast('Stok habis!', 'warning'); };
                    el.innerHTML = `
                        <div>
                            <h3 class="text-xs font-bold text-gray-900 leading-tight mb-1">${p.name}</h3>
                            <p class="text-[10px] text-gray-500">SKU: ${p.sku}</p>
                            <p class="text-[10px] ${p.stock > 0 ? 'text-green-600' : 'text-red-500'}">Stok: ${p.stock}</p>
                        </div>
                        <div class="mt-2 text-sm font-semibold text-gray-900">
                            ${formatRp(p.price)}
                        </div>
                    `;
                    catalog.appendChild(el);
                });
            });
    }

    document.getElementById('posSearch').addEventListener('keyup', (e) => {
        searchProducts(e.target.value);
    });

    // Cart Logic
    function addToCart(product) {
        const existing = posCart.find(i => i.id === product.id);
        if(existing) {
            if(existing.qty < product.stock) {
                existing.qty++;
            } else {
                showToast('Stok tidak mencukupi!', 'warning');
            }
        } else {
            posCart.push({...product, qty: 1});
        }
        renderCart();
    }

    function updateQty(id, delta) {
        const item = posCart.find(i => i.id === id);
        if(!item) return;
        
        const newQty = item.qty + delta;
        if(newQty <= 0) {
            posCart = posCart.filter(i => i.id !== id);
        } else if(newQty <= item.stock) {
            item.qty = newQty;
        } else {
            showToast('Stok tidak mencukupi!', 'warning');
        }
        renderCart();
    }

    function renderCart() {
        const cartEl = document.getElementById('posCart');
        posTotal = 0;
        
        if(posCart.length === 0) {
            cartEl.innerHTML = '<p class="text-xs text-gray-500 text-center py-10">Keranjang masih kosong</p>';
            document.getElementById('posTotal').innerText = 'Rp 0';
            return;
        }

        cartEl.innerHTML = '';
        posCart.forEach(item => {
            const subtotal = item.qty * item.price;
            posTotal += subtotal;
            
            const el = document.createElement('div');
            el.className = 'flex justify-between items-start pb-4 border-b border-gray-100';
            el.innerHTML = `
                <div class="flex-1 pr-2">
                    <h4 class="text-xs font-bold text-gray-900 leading-tight">${item.name}</h4>
                    <p class="text-[10px] font-semibold text-gray-900 mt-1">${formatRp(item.price)}</p>
                </div>
                <div class="flex items-center space-x-2">
                    <button onclick="updateQty(${item.id}, -1)" class="w-6 h-6 rounded bg-gray-100 text-gray-600 flex items-center justify-center hover:bg-gray-200">-</button>
                    <span class="text-xs font-medium w-4 text-center">${item.qty}</span>
                    <button onclick="updateQty(${item.id}, 1)" class="w-6 h-6 rounded bg-gray-100 text-gray-600 flex items-center justify-center hover:bg-gray-200">+</button>
                </div>
            `;
            cartEl.appendChild(el);
        });

        document.getElementById('posTotal').innerText = formatRp(posTotal);
    }

    document.getElementById('clearCart').addEventListener('click', () => {
        posCart = [];
        renderCart();
    });

    // Checkout
    document.getElementById('posCheckoutBtn').addEventListener('click', () => {
        if(posCart.length === 0) return showToast('Keranjang kosong!', 'warning');
        const amountPaid = parseInt(document.getElementById('posAmountPaid').value || 0);
        
        if(amountPaid < posTotal) return showToast('Uang diterima kurang dari Total belanja!', 'warning');

        const btn = document.getElementById('posCheckoutBtn');
        btn.disabled = true;
        btn.innerText = 'Memproses...';

        fetch('<?= BASEURL; ?>/admin/api_pos_checkout', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ items: posCart, amount_paid: amountPaid })
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btn.innerText = 'Bayar & Cetak';
            
            if(data.success) {
                // Populate receipt
                const now = new Date();
                document.getElementById('successInvoice').innerText = data.invoice;
                document.getElementById('receiptDate').innerText = now.toLocaleDateString('id-ID', {day:'2-digit',month:'long',year:'numeric'}) + ' ' + now.toLocaleTimeString('id-ID', {hour:'2-digit',minute:'2-digit'});
                document.getElementById('receiptTotal').innerText = formatRp(posTotal);
                document.getElementById('receiptPaid').innerText = formatRp(amountPaid);
                document.getElementById('successChange').innerText = formatRp(data.change);
                
                // Build receipt items table
                let itemRows = '';
                posCart.forEach(item => {
                    itemRows += `<tr>
                        <td class="py-0.5 pr-2" style="max-width:130px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">${item.name}</td>
                        <td class="py-0.5 text-center">${item.qty}x</td>
                        <td class="py-0.5 text-right">${formatRp(item.price * item.qty)}</td>
                    </tr>`;
                });
                document.getElementById('receiptItems').innerHTML = itemRows;
                
                document.getElementById('changeModal').classList.remove('hidden');
                
                posCart = [];
                renderCart();
                document.getElementById('posAmountPaid').value = '';
                searchProducts(); // refresh stock
            } else {
                showToast(data.message, 'error');
            }
        });
    });

    function closeChangeModal() {
        document.getElementById('changeModal').classList.add('hidden');
    }

    // Init
    searchProducts();
</script>

<?php require_once __DIR__ . '/../../layout/admin_footer.php'; ?>
