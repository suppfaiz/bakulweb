<?php require_once __DIR__ . '/../../layout/header.php'; ?>

<div class="bg-white dark:bg-darkbg min-h-screen pt-24 pb-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        
        <!-- Header Title -->
        <div class="mb-6">
            <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">Katalog Produk</h1>
        </div>

        <!-- Filter Form -->
        <form id="filterForm">
            <!-- Search Bar (Mobile Only) -->
            <div class="block md:hidden mb-6 relative w-full">
                <input type="text" id="mobileSearchInput" name="search" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" class="w-full bg-gray-50 dark:bg-darkcard border border-gray-200 dark:border-gray-800 text-sm font-semibold rounded-2xl pl-11 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent transition-all shadow-sm" placeholder="Cari HP, casing, atau aksesoris...">
                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                    <i class="fas fa-search"></i>
                </div>
            </div>

            <div class="flex flex-wrap gap-4 items-center justify-between border-b border-gray-200 dark:border-gray-800 pb-6 mb-8">
                <div class="flex flex-wrap gap-3 items-center">
                    
                    <!-- Dropdown Kategori -->
                    <div class="relative inline-block text-left" id="dropdownCategoryContainer">
                        <button type="button" onclick="toggleDropdown('categoryDropdown')" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-full bg-white dark:bg-darkcard text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-200 hover:border-gray-400 focus:outline-none transition-all shadow-sm">
                            <span>Kategori</span>
                            <i class="fas fa-chevron-down text-[9px] text-gray-400"></i>
                        </button>
                        <!-- Dropdown Panel -->
                        <div id="categoryDropdown" class="origin-top-left absolute left-0 mt-2 w-60 rounded-3xl shadow-[0_15px_45px_rgba(0,0,0,0.12)] bg-white/95 dark:bg-gray-900/95 backdrop-blur-md border border-gray-100 dark:border-gray-800 focus:outline-none hidden z-30 transition-all duration-200 opacity-0 scale-95 overflow-hidden">
                            <div class="p-3 space-y-1 max-h-72 overflow-y-auto">
                                <?php 
                                $selectedCat = [];
                                if (!empty($_GET['category'])) {
                                    $selectedCat = is_array($_GET['category']) ? $_GET['category'] : [$_GET['category']];
                                }
                                if(!empty($data['categories'])): foreach($data['categories'] as $cat): 
                                    $isChecked = in_array($cat['id'], $selectedCat) ? 'checked' : '';
                                ?>
                                <label class="flex items-center justify-between cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800 p-2 rounded-2xl transition-all group">
                                    <span class="text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300 group-hover:text-black dark:group-hover:text-white transition-colors"><?= htmlspecialchars($cat['name']); ?></span>
                                    <div class="relative flex items-center justify-center">
                                        <input id="cat-<?= $cat['id']; ?>" name="category[]" value="<?= $cat['id']; ?>" type="checkbox" <?= $isChecked; ?> class="sr-only peer">
                                        <div class="w-5 h-5 border-2 border-gray-200 dark:border-gray-700 rounded-full flex items-center justify-center peer-checked:bg-black dark:peer-checked:bg-white peer-checked:border-black dark:peer-checked:border-white transition-all">
                                            <i class="fas fa-check text-[8px] text-white dark:text-black opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                                        </div>
                                    </div>
                                </label>
                                <?php endforeach; else: ?>
                                <p class="text-xs text-gray-400 p-2 text-center">Belum ada kategori.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Dropdown Merek -->
                    <div class="relative inline-block text-left" id="dropdownBrandContainer">
                        <button type="button" onclick="toggleDropdown('brandDropdown')" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-full bg-white dark:bg-darkcard text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-200 hover:border-gray-400 focus:outline-none transition-all shadow-sm">
                            <span>Merek</span>
                            <i class="fas fa-chevron-down text-[9px] text-gray-400"></i>
                        </button>
                        <!-- Dropdown Panel -->
                        <div id="brandDropdown" class="origin-top-left absolute left-0 mt-2 w-60 rounded-3xl shadow-[0_15px_45px_rgba(0,0,0,0.12)] bg-white/95 dark:bg-gray-900/95 backdrop-blur-md border border-gray-100 dark:border-gray-800 focus:outline-none hidden z-30 transition-all duration-200 opacity-0 scale-95 overflow-hidden">
                            <div class="p-3 space-y-1 max-h-72 overflow-y-auto">
                                <?php 
                                $selectedBrand = [];
                                if (!empty($_GET['brand'])) {
                                    $selectedBrand = is_array($_GET['brand']) ? $_GET['brand'] : [$_GET['brand']];
                                }
                                if(!empty($data['brands'])): foreach($data['brands'] as $brand): 
                                    $isChecked = in_array($brand['id'], $selectedBrand) ? 'checked' : '';
                                ?>
                                <label class="flex items-center justify-between cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800 p-2 rounded-2xl transition-all group">
                                    <span class="text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300 group-hover:text-black dark:group-hover:text-white transition-colors"><?= htmlspecialchars($brand['name']); ?></span>
                                    <div class="relative flex items-center justify-center">
                                        <input id="brand-<?= $brand['id']; ?>" name="brand[]" value="<?= $brand['id']; ?>" type="checkbox" <?= $isChecked; ?> class="sr-only peer">
                                        <div class="w-5 h-5 border-2 border-gray-200 dark:border-gray-700 rounded-full flex items-center justify-center peer-checked:bg-black dark:peer-checked:bg-white peer-checked:border-black dark:peer-checked:border-white transition-all">
                                            <i class="fas fa-check text-[8px] text-white dark:text-black opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                                        </div>
                                    </div>
                                </label>
                                <?php endforeach; else: ?>
                                <p class="text-xs text-gray-400 p-2 text-center">Belum ada merek.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                    <!-- Dropdown Urutkan -->
                    <div class="relative inline-block text-left" id="dropdownSortContainer">
                        <button type="button" onclick="toggleDropdown('sortDropdown')" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-full bg-white dark:bg-darkcard text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-200 hover:border-gray-400 focus:outline-none transition-all shadow-sm">
                            <span class="text-gray-400">Urutan:</span>
                            <span id="selectedSortLabel">
                                <?php
                                $sortOptions = [
                                    'latest'     => 'Terbaru',
                                    'price_asc'  => 'Harga: Terendah',
                                    'price_desc' => 'Harga: Tertinggi'
                                ];
                                $currentSort = $_GET['sort'] ?? 'latest';
                                echo $sortOptions[$currentSort] ?? 'Terbaru';
                                ?>
                            </span>
                            <i class="fas fa-chevron-down text-[9px] text-gray-400"></i>
                        </button>
                        <!-- Dropdown Panel -->
                        <div id="sortDropdown" class="origin-top-left md:origin-top-right absolute left-0 md:right-0 md:left-auto mt-2 w-60 rounded-3xl shadow-[0_15px_45px_rgba(0,0,0,0.12)] bg-white/95 dark:bg-gray-900/95 backdrop-blur-md border border-gray-100 dark:border-gray-800 focus:outline-none hidden z-30 transition-all duration-200 opacity-0 scale-95 overflow-hidden">
                            <div class="p-3 space-y-1">
                                <?php foreach($sortOptions as $val => $label): ?>
                                <label class="flex items-center justify-between cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800 p-2 rounded-2xl transition-all group">
                                    <span class="text-xs font-bold uppercase tracking-wider text-gray-650 dark:text-gray-300 group-hover:text-black dark:group-hover:text-white transition-colors"><?= $label ?></span>
                                    <div class="relative flex items-center justify-center">
                                        <input type="radio" name="sort" value="<?= $val ?>" <?= $currentSort === $val ? 'checked' : '' ?> class="sr-only peer" onchange="updateSortLabel('<?= $label ?>')">
                                        <div class="w-5 h-5 border-2 border-gray-200 dark:border-gray-700 rounded-full flex items-center justify-center peer-checked:bg-black dark:peer-checked:bg-white peer-checked:border-black dark:peer-checked:border-white transition-all">
                                            <i class="fas fa-check text-[8px] text-white dark:text-black opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                                        </div>
                                    </div>
                                </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
            </div>
        </form>

        <!-- Product Grid (Full Width, 2-columns on mobile) -->
        <main class="w-full">
            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-4 gap-y-8 sm:gap-x-6 sm:gap-y-10" id="productGrid">
                
                <?php if(!empty($data['products'])): foreach($data['products'] as $product): ?>
                <a href="<?= BASEURL; ?>/product/<?= $product['slug']; ?>" class="group">
                    <div class="w-full aspect-square bg-gray-50 dark:bg-darkcard rounded-2xl overflow-hidden border border-gray-200 dark:border-gray-800 mb-3 flex items-center justify-center relative">
                        <?php if($product['image']): ?>
                            <img src="<?= BASEURL; ?>/<?= $product['image']; ?>" alt="<?= $product['name']; ?>" class="w-full h-full object-center object-cover group-hover:scale-105 transition-transform duration-500">
                        <?php else: ?>
                            <i class="fas fa-mobile-screen-button text-3xl text-gray-300 dark:text-gray-700"></i>
                        <?php endif; ?>
                        
                        <?php if((float)$product['avg_rating'] >= 4): ?>
                        <div class="absolute top-2 left-2 bg-amber-400 text-white text-[10px] font-black px-2 py-0.5 rounded-full">
                            <i class="fas fa-star text-[9px]"></i> <?= number_format((float)$product['avg_rating'], 1) ?>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($product['has_flash_sale'])): ?>
                        <div class="absolute top-2 right-2 bg-red-600 text-white text-[9px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider shadow-sm flex items-center gap-1">
                            <i class="fas fa-bolt text-[9px] animate-bounce"></i> Flash Sale
                        </div>
                        <?php endif; ?>
                    </div>
                    <p class="text-[11px] text-gray-400 font-medium mb-0.5"><?= htmlspecialchars($product['brand_name'] ?? '') ?></p>
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white leading-tight mb-1 group-hover:underline underline-offset-2 line-clamp-2">
                        <?= htmlspecialchars($product['name']); ?>
                    </h3>
                    
                    <!-- Rating Count -->
                    <?php if ($product['review_count'] > 0): ?>
                        <div class="flex items-center gap-1 mb-1 text-[11px] text-gray-500 font-medium">
                            <i class="fas fa-star text-amber-400 text-[10px]"></i>
                            <span><?= number_format((float)$product['avg_rating'], 1); ?></span>
                            <span class="text-gray-300 dark:text-gray-700">•</span>
                            <span><?= $product['review_count']; ?> ulasan</span>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($product['has_flash_sale'])): ?>
                        <div class="space-y-1 mt-1">
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <span class="text-sm font-black text-red-600">
                                    Rp <?= number_format((float)$product['starting_price'], 0, ',', '.') ?>
                                </span>
                                <del class="text-xs text-gray-400" style="text-decoration: line-through;">
                                    Rp <?= number_format((float)$product['original_starting_price'], 0, ',', '.') ?>
                                </del>
                            </div>
                            <div class="flex items-center gap-1 bg-red-50 dark:bg-red-950/20 text-red-600 rounded-full px-2.5 py-0.5 w-fit text-[9px] font-black border border-red-100 dark:border-red-900/30">
                                <i class="fas fa-clock text-[8px] animate-pulse"></i> <span data-countdown="<?= $product['flash_sale_end'] ?>" class="font-mono">--:--:--</span>
                            </div>
                        </div>
                    <?php else: ?>
                        <p class="text-sm font-black text-black dark:text-white mt-1">Rp <?= number_format($product['starting_price'], 0, ',', '.'); ?></p>
                    <?php endif; ?>
                </a>
                <?php endforeach; else: ?>
                <div class="col-span-full py-20 text-center border-2 border-dashed border-gray-200 dark:border-gray-800 rounded-2xl">
                    <i class="fas fa-box-open text-4xl text-gray-300 dark:text-gray-700 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Belum ada produk</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Produk akan muncul di sini setelah ditambahkan melalui dashboard admin.</p>
                </div>
                <?php endif; ?>

            </div>
        </main>
    </div>
</div>

<script>
    const filterForm = document.getElementById('filterForm');
    const productGrid = document.getElementById('productGrid');
    const BASEURL = '<?= BASEURL; ?>';
    
    // Get search term from URL query parameter
    const urlParams = new URLSearchParams(window.location.search);
    const searchVal = urlParams.get('search') || '';

    // Toggle Dropdown logic
    function toggleDropdown(id) {
        const el = document.getElementById(id);
        if (!el) return;
        
        const isHidden = el.classList.contains('hidden');
        
        // Hide all other dropdowns first
        document.querySelectorAll('[id$="Dropdown"]').forEach(d => {
            if (d.id !== id) {
                d.classList.add('hidden', 'opacity-0', 'scale-95');
            }
        });

        if (isHidden) {
            el.classList.remove('hidden');
            setTimeout(() => {
                el.classList.remove('opacity-0', 'scale-95');
            }, 10);
        } else {
            el.classList.add('opacity-0', 'scale-95');
            setTimeout(() => {
                el.classList.add('hidden');
            }, 200);
        }
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('#dropdownCategoryContainer') && !e.target.closest('#dropdownBrandContainer') && !e.target.closest('#dropdownSortContainer')) {
            document.querySelectorAll('[id$="Dropdown"]').forEach(d => {
                d.classList.add('opacity-0', 'scale-95');
                setTimeout(() => {
                    d.classList.add('hidden');
                }, 200);
            });
        }
    });

    // Update sort label and close dropdown
    function updateSortLabel(label) {
        document.getElementById('selectedSortLabel').innerText = label;
        const d = document.getElementById('sortDropdown');
        d.classList.add('opacity-0', 'scale-95');
        setTimeout(() => {
            d.classList.add('hidden');
        }, 200);
    }

    // Function to render products
    function renderProducts(products) {
        if (!products || products.length === 0) {
            productGrid.innerHTML = `
                <div class="col-span-full py-20 text-center border-2 border-dashed border-gray-200 dark:border-gray-800 rounded-2xl">
                    <i class="fas fa-box-open text-4xl text-gray-300 dark:text-gray-700 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Belum ada produk</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Tidak ada produk yang cocok dengan filter yang dipilih.</p>
                </div>`;
            return;
        }

        let html = '';
        products.forEach(product => {
            // Format price to Rupiah
            const formattedPrice = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(product.starting_price).replace('Rp', 'Rp ');

            // Build rating stars
            let ratingHtml = '';
            if (product.review_count > 0) {
                const avgRating = parseFloat(product.avg_rating).toFixed(1);
                ratingHtml = `
                    <div class="flex items-center gap-1 mb-1 text-[11px] text-gray-500 font-medium">
                        <i class="fas fa-star text-amber-400 text-[10px]"></i>
                        <span>${avgRating}</span>
                        <span class="text-gray-300 dark:text-gray-700">•</span>
                        <span>${product.review_count} ulasan</span>
                    </div>`;
            }

            const imageHtml = product.image 
                ? `<img src="${BASEURL}/${product.image}" alt="${product.name}" class="w-full h-full object-center object-cover group-hover:scale-105 transition-transform duration-500">`
                : `<i class="fas fa-mobile-screen-button text-3xl text-gray-300 dark:text-gray-700"></i>`;

            const isFlashSale = product.has_flash_sale && parseInt(product.has_flash_sale) === 1;
            const originalFormattedPrice = product.original_starting_price ? new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(product.original_starting_price).replace('Rp', 'Rp ') : formattedPrice;

            const priceHtml = isFlashSale ? `
                <div class="space-y-1 mt-1">
                    <div class="flex items-center gap-1.5 flex-wrap">
                        <span class="text-sm font-black text-red-600">${formattedPrice}</span>
                        <del class="text-xs text-gray-400" style="text-decoration: line-through;">${originalFormattedPrice}</del>
                    </div>
                    <div class="flex items-center gap-1 bg-red-50 dark:bg-red-950/20 text-red-600 rounded-full px-2.5 py-0.5 w-fit text-[9px] font-black border border-red-100 dark:border-red-900/30">
                        <i class="fas fa-clock text-[8px] animate-pulse"></i> <span data-countdown="${product.flash_sale_end}" class="font-mono">--:--:--</span>
                    </div>
                </div>
            ` : `<p class="text-sm font-black text-black dark:text-white mt-1">${formattedPrice}</p>`;

            html += `
                <a href="${BASEURL}/product/${product.slug}" class="group">
                    <div class="w-full aspect-square bg-gray-50 dark:bg-darkcard rounded-2xl overflow-hidden border border-gray-200 dark:border-gray-800 mb-3 flex items-center justify-center relative">
                        ${imageHtml}
                        ${parseFloat(product.avg_rating) >= 4 ? `
                        <div class="absolute top-2 left-2 bg-amber-400 text-white text-[10px] font-black px-2 py-0.5 rounded-full">
                            <i class="fas fa-star text-[9px]"></i> ${parseFloat(product.avg_rating).toFixed(1)}
                        </div>` : ''}
                        ${isFlashSale ? `
                        <div class="absolute top-2 right-2 bg-red-600 text-white text-[9px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider shadow-sm flex items-center gap-1">
                            <i class="fas fa-bolt text-[9px] animate-bounce"></i> Flash Sale
                        </div>` : ''}
                    </div>
                    <p class="text-[11px] text-gray-400 font-medium mb-0.5">${product.brand_name || ''}</p>
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white leading-tight mb-1 group-hover:underline underline-offset-2 line-clamp-2">
                        ${escapeHtml(product.name)}
                    </h3>
                    ${ratingHtml}
                    ${priceHtml}
                </a>`;
        });
        productGrid.innerHTML = html;
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

    // Function to fetch filtered products
    function fetchFilteredProducts() {
        const formData = new FormData(filterForm);
        const mobileSearch = document.getElementById('mobileSearchInput');
        
        // Construct the filters payload
        const filters = {
            category: formData.getAll('category[]'),
            brand: formData.getAll('brand[]'),
            sort: formData.get('sort') || 'latest',
            search: mobileSearch ? mobileSearch.value : searchVal
        };

        // Add loading state style
        productGrid.style.opacity = '0.5';

        fetch(`${BASEURL}/catalog/filter`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(filters)
        })
        .then(res => res.json())
        .then(data => {
            productGrid.style.opacity = '1';
            renderProducts(data);
        })
        .catch(err => {
            productGrid.style.opacity = '1';
            console.error('Error fetching filtered products:', err);
        });
    }

    // Add change event listener to all checkboxes and radio buttons
    const inputs = filterForm.querySelectorAll('input[type="checkbox"], input[type="radio"]');
    inputs.forEach(box => {
        box.addEventListener('change', fetchFilteredProducts);
    });

    // Prevent default form submit (e.g. when pressing enter key in mobile search)
    filterForm.addEventListener('submit', function(e) {
        e.preventDefault();
    });

    // Mobile search input event listener with debounce
    const mobileSearchInput = document.getElementById('mobileSearchInput');
    let searchDebounceTimer;
    if (mobileSearchInput) {
        mobileSearchInput.addEventListener('input', function() {
            clearTimeout(searchDebounceTimer);
            searchDebounceTimer = setTimeout(fetchFilteredProducts, 300);
        });

        // Fast search on Enter
        mobileSearchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                clearTimeout(searchDebounceTimer);
                fetchFilteredProducts();
            }
        });
    }

    // Global Countdown Script
    function updateCountdowns() {
        const now = new Date().getTime();
        document.querySelectorAll("[data-countdown]").forEach(el => {
            const endTimeStr = el.getAttribute("data-countdown");
            const endTime = new Date(endTimeStr.replace(/-/g, "/")).getTime();
            const distance = endTime - now;
            
            if (distance < 0) {
                el.parentElement.style.display = 'none';
                return;
            }
            
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            el.innerHTML = `${String(hours).padStart(2, '0')}j : ${String(minutes).padStart(2, '0')}m : ${String(seconds).padStart(2, '0')}d`;
        });
    }
    
    updateCountdowns();
    setInterval(updateCountdowns, 1000);
</script>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>
