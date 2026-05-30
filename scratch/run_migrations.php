<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/Core/Database.php';

$db = new Database();

// Create homepage_promos table
$query1 = "CREATE TABLE IF NOT EXISTS `homepage_promos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `subtitle` text DEFAULT NULL,
  `image_path` varchar(255) NOT NULL,
  `link_url` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

$db->query($query1);
$db->execute();
echo "Table homepage_promos created or already exists.\n";

// Create vouchers table
$query2 = "CREATE TABLE IF NOT EXISTS `vouchers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `discount_type` enum('percentage','fixed') NOT NULL DEFAULT 'fixed',
  `discount_amount` decimal(15,2) NOT NULL,
  `min_spend` decimal(15,2) NOT NULL DEFAULT 0.00,
  `expiry_date` date DEFAULT NULL,
  `usage_limit` int(11) DEFAULT NULL,
  `usage_count` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

$db->query($query2);
$db->execute();
echo "Table vouchers created or already exists.\n";

// Add voucher columns to orders table if they don't exist
try {
    $db->query("ALTER TABLE `orders` ADD COLUMN `voucher_code` varchar(50) DEFAULT NULL AFTER `shipping_address` ");
    $db->execute();
    echo "Added voucher_code column to orders table.\n";
} catch (PDOException $e) {
    echo "voucher_code column might already exist: " . $e->getMessage() . "\n";
}

try {
    $db->query("ALTER TABLE `orders` ADD COLUMN `discount_amount` decimal(15,2) DEFAULT 0.00 AFTER `voucher_code` ");
    $db->execute();
    echo "Added discount_amount column to orders table.\n";
} catch (PDOException $e) {
    echo "discount_amount column might already exist: " . $e->getMessage() . "\n";
}

// Seed homepage_promos if empty
$db->query("SELECT COUNT(*) as count FROM homepage_promos");
$res = $db->single();
if ($res['count'] == 0) {
    $promos = [
        [
            'title' => 'COMING SOON!',
            'subtitle' => 'BAKUL DOT SEGERA HADIR! Tempat jual beli HP bekas berkualitas dengan harga bersahabat.',
            'image_path' => 'images/promo_banner_1.jpg',
            'link_url' => 'https://wa.me/6281919525931',
            'sort_order' => 1
        ],
        [
            'title' => 'COMING SOON!',
            'subtitle' => 'BAKUL DOT SEGERA HADIR UNTUK KAMU! HP Bekas, Kualitas Juara!',
            'image_path' => 'images/promo_banner_2.jpg',
            'link_url' => 'https://wa.me/6281919525931',
            'sort_order' => 2
        ],
        [
            'title' => 'COMING SOON!',
            'subtitle' => 'KENAPA BELI DI BAKUL DOT? HP Bekas Kualitas Oke! Tunggu promo dari kami!',
            'image_path' => 'images/promo_banner_3.jpg',
            'link_url' => 'https://wa.me/6281919525931',
            'sort_order' => 3
        ]
    ];
    
    foreach ($promos as $promo) {
        $db->query("INSERT INTO homepage_promos (title, subtitle, image_path, link_url, sort_order, is_active) VALUES (:title, :subtitle, :image_path, :link_url, :sort_order, 1)");
        $db->bind(':title', $promo['title']);
        $db->bind(':subtitle', $promo['subtitle']);
        $db->bind(':image_path', $promo['image_path']);
        $db->bind(':link_url', $promo['link_url']);
        $db->bind(':sort_order', $promo['sort_order']);
        $db->execute();
    }
    echo "Seeded default promos into homepage_promos table.\n";
} else {
    echo "homepage_promos table already has data. Seeding skipped.\n";
}
