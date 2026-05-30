<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    
    <!-- Override console logs in production to keep browser console clean -->
    <script>
        if (!['localhost', '127.0.0.1', '[::1]'].includes(window.location.hostname)) {
            console.log = () => {};
            console.error = () => {};
            console.warn = () => {};
            console.info = () => {};
            console.debug = () => {};
        }
    </script>
    <?php
    // Default SEO values
    $meta_title = isset($data['judul']) ? $data['judul'] : 'BAKUL E-Commerce';
    $meta_desc = "BAKUL E-Commerce - Belanja Gadget, HP, dan Aksesoris Original dengan Harga Terbaik dan Terpercaya.";
    $meta_keywords = "bakul, ecommerce, hp murah, aksesoris original, toko gadget";
    $meta_image = BASEURL . "/images/logo.jpg";
    $meta_url = BASEURL . ($_SERVER['REQUEST_URI'] ?? '');

    if (isset($data['product'])) {
        $meta_title = htmlspecialchars($data['product']['name']) . ' | BAKUL E-Commerce';
        
        // Clean description from HTML and limit to 155 characters
        $desc = strip_tags($data['product']['description']);
        $meta_desc = mb_strimwidth($desc, 0, 155, "...");
        
        // Keywords from product name & brand
        $meta_keywords = htmlspecialchars($data['product']['name']) . ", " . htmlspecialchars($data['product']['brand_name'] ?? 'BAKUL') . ", beli " . htmlspecialchars($data['product']['name']) . ", bakul ecommerce";
        
        if (!empty($data['images'])) {
            $meta_image = BASEURL . "/" . $data['images'][0]['image_path'];
        }
    }
    ?>
    <title><?= $meta_title; ?></title>
    <meta name="description" content="<?= htmlspecialchars($meta_desc); ?>">
    <meta name="keywords" content="<?= htmlspecialchars($meta_keywords); ?>">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?= $meta_url; ?>">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= $meta_url; ?>">
    <meta property="og:title" content="<?= $meta_title; ?>">
    <meta property="og:description" content="<?= htmlspecialchars($meta_desc); ?>">
    <meta property="og:image" content="<?= $meta_image; ?>">
    <meta property="og:site_name" content="BAKUL E-Commerce">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?= $meta_url; ?>">
    <meta property="twitter:title" content="<?= $meta_title; ?>">
    <meta property="twitter:description" content="<?= htmlspecialchars($meta_desc); ?>">
    <meta property="twitter:image" content="<?= $meta_image; ?>">

    <?php if (isset($data['product'])): ?>
    <!-- Product Schema JSON-LD for Google Rich Snippets -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org/",
      "@type": "Product",
      "name": "<?= htmlspecialchars($data['product']['name']); ?>",
      "image": [
        <?php 
        $img_urls = [];
        if (!empty($data['images'])) {
            foreach ($data['images'] as $img) {
                $img_urls[] = '"' . BASEURL . '/' . $img['image_path'] . '"';
            }
        } else {
            $img_urls[] = '"' . BASEURL . '/images/logo.jpg"';
        }
        echo implode(",\n        ", $img_urls);
        ?>
      ],
      "description": "<?= htmlspecialchars(mb_strimwidth(strip_tags($data['product']['description']), 0, 300, "...")); ?>",
      "brand": {
        "@type": "Brand",
        "name": "<?= htmlspecialchars($data['product']['brand_name'] ?? 'BAKUL'); ?>"
      },
      <?php if (!empty($data['variants'])): 
          $min_price = min(array_column($data['variants'], 'price'));
          $max_price = max(array_column($data['variants'], 'price'));
          $in_stock = false;
          foreach ($data['variants'] as $v) {
              if ($v['stock'] > 0) $in_stock = true;
          }
      ?>
      "offers": {
        "@type": "AggregateOffer",
        "priceCurrency": "IDR",
        "lowPrice": "<?= $min_price; ?>",
        "highPrice": "<?= $max_price; ?>",
        "offerCount": "<?= count($data['variants']); ?>",
        "availability": "<?= $in_stock ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock'; ?>"
      },
      <?php endif; ?>
      <?php if (!empty($data['rating_info']) && $data['rating_info']['review_count'] > 0): ?>
      "aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "<?= number_format($data['rating_info']['avg_rating'], 1); ?>",
        "reviewCount": "<?= $data['rating_info']['review_count']; ?>"
      }
      <?php endif; ?>
    }
    </script>
    <?php endif; ?>
    
    <!-- Web App / PWA Meta -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="BAKUL">
    <meta name="theme-color" content="#ffffff">
    <link rel="manifest" href="<?= BASEURL; ?>/manifest.json">
    <link rel="icon" type="image/png" href="<?= BASEURL; ?>/favicon.png">
    <link rel="apple-touch-icon" href="<?= BASEURL; ?>/favicon.png">

    <link href="<?= BASEURL; ?>/css/style.css?v=<?= time(); ?>" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            const nav = document.getElementById('navbar');
            if(window.scrollY > 10) {
                nav.classList.add('shadow-sm');
                nav.classList.remove('border-transparent');
            } else {
                nav.classList.remove('shadow-sm');
                nav.classList.add('border-gray-100');
            }
        });
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        
        body { font-family: 'Inter', sans-serif; }
        
        /* Smooth Scrolling */
        html { scroll-behavior: smooth; }

        /* Prevent content from being hidden behind the bottom navbar on mobile devices */
        @media (max-width: 768px) {
            body {
                padding-bottom: 4.5rem;
            }
        }
        
        /* Safe area padding for iPhones with Home Indicator */
        .pb-safe {
            padding-bottom: env(safe-area-inset-bottom, 0px);
        }
    </style>
</head>
<body class="bg-white dark:bg-darkbg text-gray-900 dark:text-gray-100 transition-colors duration-300 antialiased">

    <!-- Navbar -->
    <nav class="fixed w-full z-50 bg-white/80 dark:bg-darkbg/80 backdrop-blur-md border-b border-gray-100 dark:border-gray-800 transition-all duration-300" id="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="<?= BASEURL; ?>" class="flex items-center">
                        <img src="<?= BASEURL; ?>/images/logo.jpg" alt="BAKUL Logo" class="h-16 md:h-20 w-auto object-contain transition-transform duration-300 hover:scale-105">
                    </a>
                </div>
                
                <!-- Search Bar (Desktop) -->
                <form action="<?= BASEURL; ?>/catalog" method="GET" class="hidden md:flex flex-1 items-center justify-center px-8">
                    <div class="w-full max-w-lg relative">
                        <input type="text" name="search" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" class="w-full bg-gray-100 dark:bg-gray-800 border border-transparent dark:border-gray-700 text-sm rounded-full pl-4 pr-10 py-2 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all" placeholder="Cari HP, Laptop, Aksesoris...">
                        <button type="submit" class="absolute right-3 top-2 text-gray-400 hover:text-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>

                <!-- Icons & User Menu -->
                <div class="flex items-center space-x-6">
                    <?php 
                    $cartCount = 0;
                    if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
                        foreach ($_SESSION['cart'] as $item) {
                            $cartCount += $item['qty'];
                        }
                    }
                    ?>
                    <!-- Hidden on Mobile: Cart icon is moved to Bottom Nav -->
                    <a href="<?= BASEURL; ?>/cart" class="relative text-gray-600 dark:text-gray-300 hover:text-primary transition hidden md:block">
                        <i class="fas fa-shopping-cart text-xl"></i>
                        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold px-1.5 py-0.5 rounded-full"><?= $cartCount; ?></span>
                    </a>

                    <?php if(isset($_SESSION['user_id'])): ?>
                        <!-- Notifications Bell Button -->
                        <div class="relative">
                            <button id="notificationBtn" class="relative text-gray-600 dark:text-gray-300 hover:text-primary transition focus:outline-none flex items-center">
                                <i class="far fa-bell text-xl"></i>
                                <span id="notificationBadge" class="absolute -top-1.5 -right-1.5 bg-black text-white text-[9px] font-black px-1.5 py-0.5 rounded-full hidden">0</span>
                            </button>
                            
                            <!-- Dropdown List -->
                            <div id="notificationDropdown" class="absolute right-0 mt-3 w-80 bg-white dark:bg-darkcard rounded-2xl shadow-xl border border-gray-100 dark:border-gray-800 overflow-hidden hidden z-[100] transform scale-95 opacity-0 transition-all duration-200 origin-top-right">
                                <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center bg-white dark:bg-darkcard">
                                    <span class="font-bold text-xs text-gray-900 dark:text-white">Notifikasi</span>
                                    <button id="markAllReadBtn" class="text-[10px] text-gray-500 hover:text-black dark:hover:text-white font-bold transition">Tandai semua dibaca</button>
                                </div>
                                <div id="notificationList" class="max-h-64 overflow-y-auto divide-y divide-gray-100 dark:divide-gray-800">
                                    <p class="text-xs text-gray-400 text-center py-6">Memuat...</p>
                                </div>
                            </div>
                        </div>

                        <!-- Hidden on Mobile: Account icon is moved to Bottom Nav -->
                        <a href="<?= BASEURL; ?>/account" class="text-gray-600 dark:text-gray-300 hover:text-primary transition hidden md:block">
                            <i class="fas fa-user-circle text-2xl"></i>
                        </a>
                    <?php else: ?>
                        <!-- Hidden on Mobile: Login is moved to Bottom Nav -->
                        <a href="<?= BASEURL; ?>/auth/login" class="hidden md:inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-full text-white bg-black hover:bg-gray-800 shadow-md hover:shadow-lg transition-all dark:bg-white dark:text-black dark:hover:bg-gray-200">
                            Login / Daftar
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Mobile Bottom Navigation Bar (App Shell style for iOS/Android) -->
    <div class="fixed bottom-0 left-0 right-0 z-50 bg-white/95 dark:bg-darkbg/95 backdrop-blur-md border-t border-gray-100 dark:border-gray-800 md:hidden flex items-center justify-around h-16 pb-safe shadow-[0_-4px_20px_rgba(0,0,0,0.04)]">
        <!-- Tab: Home -->
        <a href="<?= BASEURL; ?>" class="flex flex-col items-center justify-center w-full h-full text-gray-500 hover:text-black dark:text-gray-400 dark:hover:text-white transition-colors <?= empty($_SERVER['REQUEST_URI']) || $_SERVER['REQUEST_URI'] == '/' || strpos($_SERVER['REQUEST_URI'], 'index.php') !== false ? 'text-black dark:text-white font-bold' : '' ?>">
            <i class="fas fa-home text-lg"></i>
            <span class="text-[9px] mt-1 font-medium">Beranda</span>
        </a>
        
        <!-- Tab: Catalog -->
        <a href="<?= BASEURL; ?>/catalog" class="flex flex-col items-center justify-center w-full h-full text-gray-500 hover:text-black dark:text-gray-400 dark:hover:text-white transition-colors <?= strpos($_SERVER['REQUEST_URI'], '/catalog') !== false ? 'text-black dark:text-white font-bold' : '' ?>">
            <i class="fas fa-th-large text-lg"></i>
            <span class="text-[9px] mt-1 font-medium">Katalog</span>
        </a>
        
        <!-- Tab: Cart -->
        <a href="<?= BASEURL; ?>/cart" class="flex flex-col items-center justify-center w-full h-full text-gray-500 hover:text-black dark:text-gray-400 dark:hover:text-white transition-colors relative <?= strpos($_SERVER['REQUEST_URI'], '/cart') !== false ? 'text-black dark:text-white font-bold' : '' ?>">
            <i class="fas fa-shopping-cart text-lg"></i>
            <?php if ($cartCount > 0): ?>
                <span class="absolute top-1.5 right-6 bg-red-500 text-white text-[9px] font-bold px-1 rounded-full min-w-[16px] h-[16px] flex items-center justify-center"><?= $cartCount; ?></span>
            <?php endif; ?>
            <span class="text-[9px] mt-1 font-medium">Keranjang</span>
        </a>

        <!-- Tab: Account -->
        <a href="<?= isset($_SESSION['user_id']) ? BASEURL . '/account' : BASEURL . '/auth/login'; ?>" class="flex flex-col items-center justify-center w-full h-full text-gray-500 hover:text-black dark:text-gray-400 dark:hover:text-white transition-colors <?= strpos($_SERVER['REQUEST_URI'], '/account') !== false || strpos($_SERVER['REQUEST_URI'], '/auth/login') !== false ? 'text-black dark:text-white font-bold' : '' ?>">
            <i class="fas fa-user text-lg"></i>
            <span class="text-[9px] mt-1 font-medium">Akun</span>
        </a>
    </div>

    <!-- Padding to offset fixed navbar -->
    <div class="pt-16"></div>
