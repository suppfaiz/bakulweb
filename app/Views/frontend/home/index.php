<?php require_once __DIR__ . '/../../layout/header.php'; ?>

<!-- Splash Screen Intro Loader -->
<div id="splash-loader" class="fixed inset-0 z-[9999] flex items-center justify-center bg-white dark:bg-darkbg transition-all duration-700 ease-[cubic-bezier(0.16,1,0.3,1)]">
    <div class="text-center">
        <div class="opacity-0 transform translate-y-4 transition-all duration-1000 ease-[cubic-bezier(0.16,1,0.3,1)] flex flex-col items-center" id="splash-logo">
            <img src="<?= BASEURL ?>/images/logo.jpg" alt="BAKUL Logo" class="h-40 md:h-52 w-auto object-contain">
        </div>
        <div class="mt-4 w-12 h-1 bg-black dark:bg-white mx-auto rounded-full scale-x-0 transition-transform duration-700 delay-300 ease-[cubic-bezier(0.16,1,0.3,1)]" id="splash-bar"></div>
    </div>
</div>

<style>
@keyframes float {
    0%, 100% { transform: translateY(0) rotate(-3deg); }
    50% { transform: translateY(-15px) rotate(-1deg); }
}
@keyframes floatSlow {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-8px); }
}
@keyframes floatMedium {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-12px); }
}
.animate-float {
    animation: float 6s ease-in-out infinite;
}
.animate-float-slow {
    animation: floatSlow 5s ease-in-out infinite;
}
.animate-float-medium {
    animation: floatMedium 5.5s ease-in-out infinite;
}
</style>

<!-- Hero Section -->
<section class="relative bg-white dark:bg-darkbg overflow-hidden">
    <!-- Animated grid bg -->
    <div class="absolute inset-0 opacity-[0.04] dark:opacity-[0.06]" 
         style="background-image: radial-gradient(#000 1px, transparent 1px); background-size: 28px 28px;"></div>
    <!-- Gradient Blobs -->
    <div class="absolute top-20 right-10 w-96 h-96 bg-gray-100 dark:bg-gray-800 rounded-full blur-3xl opacity-60"></div>
    <div class="absolute bottom-10 left-0 w-72 h-72 bg-gray-200 dark:bg-gray-700 rounded-full blur-3xl opacity-40"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-28 pb-12 lg:pt-32 lg:pb-16 grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
        <!-- Left Copy -->
        <div class="space-y-6">
            <div class="reveal-on-load opacity-0 translate-y-8 transition-all duration-[900ms] ease-[cubic-bezier(0.16,1,0.3,1)] inline-flex items-center gap-2 bg-black text-white text-xs font-bold px-4 py-1.5 rounded-full tracking-widest uppercase">
                <span class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></span>
                Platform E-Commerce Enterprise
            </div>
            <h1 class="reveal-on-load opacity-0 translate-y-8 transition-all duration-[900ms] ease-[cubic-bezier(0.16,1,0.3,1)] text-5xl sm:text-6xl lg:text-7xl font-extrabold text-black dark:text-white leading-[1.05] tracking-tight">
                Gadget<br/>
                <span class="relative">
                    Premium
                    <svg class="absolute -bottom-2 left-0 w-full" viewBox="0 0 300 12" fill="none">
                        <path d="M2 9 Q75 2 150 9 Q225 16 298 9" stroke="black" stroke-width="3" stroke-linecap="round" class="dark:stroke-white"/>
                    </svg>
                </span>
                <br/>Indonesia.
            </h1>
            <p class="reveal-on-load opacity-0 translate-y-8 transition-all duration-[900ms] ease-[cubic-bezier(0.16,1,0.3,1)] text-lg text-gray-500 dark:text-gray-400 max-w-md leading-relaxed">
                Temukan smartphone, laptop, dan aksesoris original terbaik. Garansi resmi, pengiriman cepat, harga transparan.
            </p>
            <div class="reveal-on-load opacity-0 translate-y-8 transition-all duration-[900ms] ease-[cubic-bezier(0.16,1,0.3,1)] flex flex-col sm:flex-row gap-4">
                <a href="<?= BASEURL ?>/catalog" 
                   class="inline-flex items-center justify-center px-8 py-4 bg-black dark:bg-white text-white dark:text-black font-bold rounded-full hover:bg-gray-800 dark:hover:bg-gray-100 transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5 text-sm">
                    <i class="fas fa-shopping-bag mr-2"></i> Mulai Belanja
                </a>
                <?php if(!isset($_SESSION['user_id'])): ?>
                <a href="<?= BASEURL ?>/auth/login" 
                   class="inline-flex items-center justify-center px-8 py-4 border-2 border-gray-200 dark:border-gray-700 text-black dark:text-white font-bold rounded-full hover:border-black dark:hover:border-white transition-all text-sm">
                    Login / Daftar <i class="fas fa-arrow-right ml-2"></i>
                </a>
                <?php endif; ?>
            </div>
            <!-- Stats Row -->
            <div class="reveal-on-load opacity-0 translate-y-8 transition-all duration-[900ms] ease-[cubic-bezier(0.16,1,0.3,1)] grid grid-cols-3 gap-6 border-t border-gray-100 dark:border-gray-800 pt-8">
                <?php 
                $brandCount = count($data['brands'] ?? []);
                $catCount = count($data['categories'] ?? []);
                $prodCount = count($data['featured'] ?? []);
                ?>
                <div>
                    <p class="text-3xl font-black text-black dark:text-white"><?= $prodCount ?>+</p>
                    <p class="text-xs text-gray-500 mt-1 font-medium">Produk Aktif</p>
                </div>
                <div>
                    <p class="text-3xl font-black text-black dark:text-white"><?= $brandCount ?>+</p>
                    <p class="text-xs text-gray-500 mt-1 font-medium">Brand Resmi</p>
                </div>
                <div>
                    <p class="text-3xl font-black text-black dark:text-white">100%</p>
                    <p class="text-xs text-gray-500 mt-1 font-medium">Garansi Asli</p>
                </div>
            </div>
        </div>

        <!-- Right Column: iPhone mockup with shadow and glow -->
        <div class="reveal-on-load opacity-0 translate-y-8 transition-all duration-[1000ms] ease-[cubic-bezier(0.16,1,0.3,1)] hidden lg:flex justify-center items-center relative">
            <!-- Glow background -->
            <div class="absolute w-80 h-80 bg-blue-100 dark:bg-blue-950/20 rounded-full blur-3xl opacity-80 -z-10"></div>
            <!-- Image with floating animation and soft shadow -->
            <div class="relative max-w-sm w-full animate-float">
                <img src="<?= BASEURL ?>/images/iphone_promo.png" alt="Premium iPhone" class="w-full h-auto object-contain rounded-[2.5rem] shadow-[0_25px_60px_rgba(0,0,0,0.18)] transition-transform duration-700">
                <!-- Floating badge 1 -->
                <div class="absolute -top-4 -left-6 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl p-4 shadow-xl flex items-center gap-3 animate-float-slow">
                    <div class="w-9 h-9 bg-black dark:bg-white rounded-xl flex items-center justify-center text-white dark:text-black">
                        <i class="fas fa-certificate text-sm"></i>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider">Garansi Resmi</p>
                        <p class="text-xs font-bold text-gray-900 dark:text-white">Original 100%</p>
                    </div>
                </div>
                <!-- Floating badge 2 -->
                <div class="absolute -bottom-4 -right-6 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl p-4 shadow-xl flex items-center gap-3 animate-float-medium">
                    <div class="w-9 h-9 bg-emerald-500 rounded-xl flex items-center justify-center text-white">
                        <i class="fas fa-truck-fast text-sm"></i>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider">Pengiriman</p>
                        <p class="text-xs font-bold text-gray-900 dark:text-white">Instan & Aman</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Category Strip -->
<?php if(!empty($data['categories'])): ?>
<section class="py-10 bg-gray-50 dark:bg-gray-900 border-y border-gray-100 dark:border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-4 overflow-x-auto pb-2 scrollbar-hide">
            <a href="<?= BASEURL ?>/catalog" class="flex-shrink-0 flex items-center gap-2 bg-black text-white text-xs font-bold px-5 py-2.5 rounded-full whitespace-nowrap">
                <i class="fas fa-th-large"></i> Semua Produk
            </a>
            <?php foreach($data['categories'] as $cat): ?>
            <a href="<?= BASEURL ?>/catalog?category[]=<?= $cat['id'] ?>" 
               class="flex-shrink-0 flex items-center gap-2 bg-white dark:bg-darkcard border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 text-xs font-semibold px-5 py-2.5 rounded-full whitespace-nowrap hover:border-black hover:text-black dark:hover:border-white dark:hover:text-white transition-colors">
                <?= htmlspecialchars($cat['name']) ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Promo Banner Section -->
<section class="py-16 bg-white dark:bg-darkbg border-b border-gray-100 dark:border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="lg:grid lg:grid-cols-12 lg:gap-8 items-stretch">
            
            <!-- Left Side: Interactive Promo Slideshow/Carousel -->
            <div class="lg:col-span-6 flex flex-col justify-between">
                <div class="relative rounded-3xl overflow-hidden shadow-xl border border-gray-150 dark:border-gray-850 aspect-[4/5] bg-gray-50 flex items-center justify-center group">
                    
                    <!-- Slides Container -->
                    <div class="relative w-full h-full">
                        <?php if(!empty($data['promos'])): ?>
                            <?php foreach($data['promos'] as $index => $promo): ?>
                                <div class="promo-slide absolute inset-0 <?= $index === 0 ? 'opacity-100' : 'opacity-0'; ?> transition-opacity duration-700 ease-in-out">
                                    <?php if(!empty($promo['link_url'])): ?>
                                        <a href="<?= htmlspecialchars($promo['link_url']); ?>" target="_blank">
                                    <?php endif; ?>
                                    <img src="<?= BASEURL ?>/<?= htmlspecialchars($promo['image_path']); ?>" alt="<?= htmlspecialchars($promo['title'] ?? ''); ?>" class="w-full h-full object-cover">
                                    <?php if(!empty($promo['link_url'])): ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <!-- Slide 1 -->
                            <div class="promo-slide absolute inset-0 opacity-100 transition-opacity duration-700 ease-in-out">
                                <img src="<?= BASEURL ?>/images/promo_banner_1.jpg" alt="Promo Bakul 1" class="w-full h-full object-cover">
                            </div>
                            <!-- Slide 2 -->
                            <div class="promo-slide absolute inset-0 opacity-0 transition-opacity duration-700 ease-in-out">
                                <img src="<?= BASEURL ?>/images/promo_banner_2.jpg" alt="Promo Bakul 2" class="w-full h-full object-cover">
                            </div>
                            <!-- Slide 3 -->
                            <div class="promo-slide absolute inset-0 opacity-0 transition-opacity duration-700 ease-in-out">
                                <img src="<?= BASEURL ?>/images/promo_banner_3.jpg" alt="Promo Bakul 3" class="w-full h-full object-cover">
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Slide Navigation Buttons -->
                    <button onclick="prevPromoSlide()" class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/80 dark:bg-gray-900/80 hover:bg-white dark:hover:bg-gray-900 text-black dark:text-white rounded-full flex items-center justify-center shadow-lg transition-all opacity-0 group-hover:opacity-100 z-10 focus:outline-none">
                        <i class="fas fa-chevron-left text-sm"></i>
                    </button>
                    <button onclick="nextPromoSlide()" class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/80 dark:bg-gray-900/80 hover:bg-white dark:hover:bg-gray-900 text-black dark:text-white rounded-full flex items-center justify-center shadow-lg transition-all opacity-0 group-hover:opacity-100 z-10 focus:outline-none">
                        <i class="fas fa-chevron-right text-sm"></i>
                    </button>

                    <!-- Indicators (Dots) -->
                    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex space-x-2 z-10">
                        <?php 
                        $promoCount = !empty($data['promos']) ? count($data['promos']) : 3;
                        for ($i = 0; $i < $promoCount; $i++): 
                        ?>
                            <button onclick="setPromoSlide(<?= $i; ?>)" class="promo-dot <?= $i === 0 ? 'w-3 h-3 bg-white' : 'w-2 h-2 bg-white/50'; ?> rounded-full transition-all shadow-md focus:outline-none"></button>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>

            <!-- Right Side: Rich Text Promo Details -->
            <div class="lg:col-span-6 flex flex-col justify-between mt-8 lg:mt-0 pl-0 lg:pl-10">
                <div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-primary/10 text-primary uppercase tracking-wider mb-4 animate-pulse">
                        <i class="fas fa-bullhorn text-[11px]"></i> Info Rilis & Promo
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-black dark:text-white tracking-tight leading-tight">
                        BAKUL DOT <span class="text-primary font-black">Segera Hadir!</span>
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-3 leading-relaxed">
                        Tempat jual beli HP bekas berkualitas dengan harga bersahabat. Kami segera hadir melayani kebutuhan gadget Anda secara jujur, amanah, dan terpercaya.
                    </p>

                    <!-- Feature Bullet Points -->
                    <div class="mt-8 space-y-4">
                        <!-- Prop 1 -->
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center shrink-0 mt-0.5 shadow-sm border border-primary/20">
                                <i class="fas fa-check-double text-xs"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-gray-900 dark:text-white">Kualitas Terpilih</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Setiap unit handphone bekas dicek ketat fungsi dan fisiknya sebelum dijual.</p>
                            </div>
                        </div>

                        <!-- Prop 2 -->
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center shrink-0 mt-0.5 shadow-sm border border-primary/20">
                                <i class="fas fa-handshake text-xs"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-gray-900 dark:text-white">Jujur & Amanah</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Kondisi barang sesuai deskripsi, jaminan transaksi nyaman tanpa manipulasi.</p>
                            </div>
                        </div>

                        <!-- Prop 3 -->
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center shrink-0 mt-0.5 shadow-sm border border-primary/20">
                                <i class="fas fa-tag text-xs"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-gray-900 dark:text-white">Harga Bersahabat</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Kualitas terjamin dengan penawaran harga yang masuk akal dan ramah kantong.</p>
                            </div>
                        </div>

                        <!-- Prop 4 -->
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center shrink-0 mt-0.5 shadow-sm border border-primary/20">
                                <i class="fas fa-box-open text-xs"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-gray-900 dark:text-white">Packing Aman & Pengiriman Cepat</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Pengemasan ekstra rapi dengan proteksi tinggi untuk menjamin unit tiba dengan selamat.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Call to Actions Cards -->
                <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-800 space-y-4">
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-gray-50 dark:bg-gray-900/50 border border-gray-150 dark:border-gray-800 rounded-2xl p-4 flex items-center gap-3">
                            <div class="w-10 h-10 bg-primary text-white rounded-xl flex items-center justify-center shrink-0 shadow-md">
                                <i class="fas fa-location-dot"></i>
                            </div>
                            <div>
                                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-wider">COD Area</p>
                                <p class="text-xs font-black text-gray-900 dark:text-white">Semarang</p>
                            </div>
                        </div>
                        <a href="https://instagram.com/bakul.dot" target="_blank" class="bg-gray-50 dark:bg-gray-900/50 border border-gray-150 dark:border-gray-800 hover:border-pink-300 dark:hover:border-pink-900/50 rounded-2xl p-4 flex items-center gap-3 transition-colors group">
                            <div class="w-10 h-10 bg-gradient-to-tr from-yellow-500 via-pink-500 to-purple-600 text-white rounded-xl flex items-center justify-center shrink-0 shadow-md">
                                <i class="fab fa-instagram text-lg"></i>
                            </div>
                            <div>
                                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-wider group-hover:text-pink-500 transition-colors">Instagram</p>
                                <p class="text-xs font-black text-gray-900 dark:text-white">@bakul.dot</p>
                            </div>
                        </a>
                    </div>
                    
                    <!-- WA Contact Button -->
                    <a href="https://wa.me/6281919525931?text=Halo%20Bakul%20Dot,%20saya%20ingin%20tanya-tanya%20mengenai%20info%20promo%20HP%20bekas" target="_blank" 
                       class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3.5 px-6 rounded-2xl shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2 text-sm">
                        <i class="fab fa-whatsapp text-lg"></i> Hubungi WhatsApp (081919525931)
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Promo Slideshow JS -->
<script>
let currentPromoIdx = 0;
const promoSlides = document.querySelectorAll('.promo-slide');
const promoDots = document.querySelectorAll('.promo-dot');

function showPromoSlide(index) {
    if (index >= promoSlides.length) currentPromoIdx = 0;
    else if (index < 0) currentPromoIdx = promoSlides.length - 1;
    else currentPromoIdx = index;

    promoSlides.forEach((slide, idx) => {
        if (idx === currentPromoIdx) {
            slide.classList.remove('opacity-0');
            slide.classList.add('opacity-100');
            slide.style.zIndex = '5';
        } else {
            slide.classList.remove('opacity-100');
            slide.classList.add('opacity-0');
            slide.style.zIndex = '0';
        }
    });

    promoDots.forEach((dot, idx) => {
        if (idx === currentPromoIdx) {
            dot.classList.remove('bg-white/50', 'w-2', 'h-2');
            dot.classList.add('bg-white', 'w-3', 'h-3');
        } else {
            dot.classList.remove('bg-white', 'w-3', 'h-3');
            dot.classList.add('bg-white/50', 'w-2', 'h-2');
        }
    });
}

function nextPromoSlide() {
    showPromoSlide(currentPromoIdx + 1);
}

function prevPromoSlide() {
    showPromoSlide(currentPromoIdx - 1);
}

function setPromoSlide(index) {
    showPromoSlide(index);
}

// Auto play slideshow
let promoInterval = setInterval(nextPromoSlide, 5000);

// Reset interval on manual control
const promoContainer = document.querySelector('.promo-slide').parentElement.parentElement;
promoContainer.addEventListener('mouseenter', () => clearInterval(promoInterval));
promoContainer.addEventListener('mouseleave', () => promoInterval = setInterval(nextPromoSlide, 5000));
</script>

<!-- Flash Sale Section -->
<?php 
$flash_sales = array_filter($data['featured'], function($p) {
    return !empty($p['has_flash_sale']);
});
if(!empty($flash_sales)): 
?>
<section class="py-12 bg-red-50/30 dark:bg-red-950/5 border-y border-red-100 dark:border-red-950/20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div class="flex items-center gap-3">
                <span class="w-10 h-10 bg-red-600 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-red-500/20 animate-pulse">
                    <i class="fas fa-bolt text-lg"></i>
                </span>
                <div>
                    <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white uppercase tracking-tight flex items-center gap-2">
                        Flash Sale <span class="bg-red-600 text-white text-[10px] font-black px-2 py-0.5 rounded-md">HOT</span>
                    </h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Penawaran smartphone & case premium dengan waktu terbatas!</p>
                </div>
            </div>
            <!-- Timer countdown -->
            <?php 
                $fs_ends = array_column($flash_sales, 'flash_sale_end');
                $earliest_end = min($fs_ends);
            ?>
            <div class="flex items-center gap-2 bg-white dark:bg-gray-900 shadow-sm border border-gray-150 dark:border-gray-800 rounded-full px-4 py-2 w-fit">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Sisa Waktu:</span>
                <span data-countdown="<?= $earliest_end; ?>" class="font-mono text-sm font-black text-red-600 animate-pulse">--:--:--</span>
            </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5">
            <?php foreach($flash_sales as $product): ?>
            <a href="<?= BASEURL ?>/product/<?= $product['slug'] ?>" class="group bg-white dark:bg-darkcard border border-gray-100 dark:border-gray-800 rounded-3xl p-3 shadow-sm hover:shadow-md hover:border-red-200 dark:hover:border-red-950/40 transition-all duration-300 relative flex flex-col justify-between">
                <div>
                    <div class="aspect-square bg-gray-50 dark:bg-gray-900 rounded-2xl overflow-hidden mb-3 relative flex items-center justify-center border border-gray-100 dark:border-gray-800">
                        <?php if($product['image']): ?>
                            <img src="<?= BASEURL ?>/<?= $product['image'] ?>" 
                                 alt="<?= htmlspecialchars($product['name']) ?>" 
                                 class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-500">
                        <?php else: ?>
                            <i class="fas fa-mobile-screen-button text-3xl text-gray-300 dark:text-gray-700"></i>
                        <?php endif; ?>
                        
                        <div class="absolute top-2 left-2 bg-red-600 text-white text-[9px] font-black px-2 py-0.5 rounded-full uppercase tracking-wider flex items-center gap-1 shadow-sm">
                            <i class="fas fa-bolt text-[8px]"></i> Promo
                        </div>
                    </div>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-0.5"><?= htmlspecialchars($product['brand_name'] ?? '') ?></p>
                    <h3 class="text-xs font-bold text-gray-900 dark:text-white leading-tight mb-2 group-hover:underline underline-offset-2 line-clamp-2">
                        <?= htmlspecialchars($product['name']) ?>
                    </h3>
                </div>
                
                <div class="space-y-1 mt-auto">
                    <div class="flex items-baseline gap-1.5 flex-wrap">
                        <span class="text-sm font-black text-red-600">
                            Rp <?= number_format((float)$product['starting_price'], 0, ',', '.') ?>
                        </span>
                        <del class="text-[10px] text-gray-400" style="text-decoration: line-through;">
                            Rp <?= number_format((float)$product['original_starting_price'], 0, ',', '.') ?>
                        </del>
                    </div>
                    <?php 
                        $disc = round((($product['original_starting_price'] - $product['starting_price']) / $product['original_starting_price']) * 100);
                    ?>
                    <span class="inline-block bg-red-100 dark:bg-red-950/20 text-red-600 text-[8px] font-black px-1.5 py-0.5 rounded">
                        Hemat <?= $disc; ?>%
                    </span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Featured Products -->
<?php if(!empty($data['featured'])): ?>
<section class="py-20 bg-white dark:bg-darkbg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-10">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Koleksi Pilihan</p>
                <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white">Produk Terbaru</h2>
            </div>
            <a href="<?= BASEURL ?>/catalog" class="text-sm font-bold text-black dark:text-white hover:underline underline-offset-4 flex items-center gap-1">
                Lihat Semua <i class="fas fa-arrow-right text-xs"></i>
            </a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5">
            <?php foreach(array_slice($data['featured'], 0, 10) as $product): ?>
            <a href="<?= BASEURL ?>/product/<?= $product['slug'] ?>" class="group">
                <div class="bg-gray-100 dark:bg-darkcard rounded-2xl overflow-hidden aspect-square mb-3 relative border border-gray-100 dark:border-gray-800">
                    <?php if($product['image']): ?>
                        <img src="<?= BASEURL ?>/<?= $product['image'] ?>" 
                             alt="<?= htmlspecialchars($product['name']) ?>" 
                             class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-500">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center text-gray-300 dark:text-gray-600">
                            <i class="fas fa-mobile-screen-button text-4xl"></i>
                        </div>
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
                    <?= htmlspecialchars($product['name']) ?>
                </h3>
                <?php if($product['starting_price']): ?>
                <div class="space-y-1">
                    <?php if (!empty($product['has_flash_sale'])): ?>
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
                    <?php else: ?>
                        <p class="text-sm font-black text-black dark:text-white">
                            Rp <?= number_format((float)$product['starting_price'], 0, ',', '.') ?>
                        </p>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>


<!-- CTA Section -->
<section class="py-20 bg-white dark:bg-darkbg">
    <div class="max-w-2xl mx-auto px-4 text-center">
        <h2 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-4">Siap Mulai Belanja?</h2>
        <p class="text-gray-500 dark:text-gray-400 mb-8">Bergabung dengan ribuan pelanggan yang sudah percaya BAKUL.</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="<?= BASEURL ?>/catalog" 
               class="inline-flex items-center justify-center px-8 py-4 bg-black dark:bg-white text-white dark:text-black font-bold rounded-full hover:bg-gray-800 dark:hover:bg-gray-100 transition-all shadow-lg hover:-translate-y-0.5">
                <i class="fas fa-shopping-bag mr-2"></i> Lihat Katalog
            </a>
            <?php if(!isset($_SESSION['user_id'])): ?>
            <a href="<?= BASEURL ?>/auth/login" 
               class="inline-flex items-center justify-center px-8 py-4 border-2 border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 font-bold rounded-full hover:border-black dark:hover:border-white transition-all">
                Daftar Gratis
            </a>
            <?php endif; ?>
        </div>
    </div>
</section>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const splash = document.getElementById('splash-loader');
    const logo = document.getElementById('splash-logo');
    const bar = document.getElementById('splash-bar');

    // Trigger loader elements transition
    setTimeout(() => {
        logo.classList.remove('opacity-0', 'translate-y-4');
        logo.classList.add('opacity-100', 'translate-y-0');
        logo.style.letterSpacing = '0.25em';
        bar.classList.remove('scale-x-0');
        bar.classList.add('scale-x-100');
    }, 100);

    // Fade out and dismiss splash overlay
    setTimeout(() => {
        splash.classList.add('opacity-0', '-translate-y-full');
        setTimeout(() => {
            splash.remove();
        }, 700);

        // Staggered reveal for hero items
        document.querySelectorAll('.reveal-on-load').forEach((el, idx) => {
            setTimeout(() => {
                el.classList.remove('opacity-0', 'translate-y-8');
                el.classList.add('opacity-100', 'translate-y-0');
            }, idx * 120);
        });
    }, 1200);

    // Global Countdown Script
    function updateCountdowns() {
        const now = new Date().getTime();
        document.querySelectorAll("[data-countdown]").forEach(el => {
            const endTimeStr = el.getAttribute("data-countdown");
            const endTime = new Date(endTimeStr.replace(/-/g, "/")).getTime(); // browser compatibility
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
});
</script>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>
