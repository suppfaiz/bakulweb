<?php
// scratch/run_finance_cogs_migration.php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/Core/Database.php';

$db = new Database();

// 1. Add purchase_price to product_variants table
try {
    $db->query("ALTER TABLE `product_variants` ADD COLUMN `purchase_price` DECIMAL(15,2) DEFAULT NULL AFTER `price`");
    $db->execute();
    echo "Added purchase_price column to product_variants table.\n";
} catch (PDOException $e) {
    echo "purchase_price in product_variants might already exist: " . $e->getMessage() . "\n";
}

// 2. Add purchase_price to order_items table
try {
    $db->query("ALTER TABLE `order_items` ADD COLUMN `purchase_price` DECIMAL(15,2) DEFAULT 0.00 AFTER `price`");
    $db->execute();
    echo "Added purchase_price column to order_items table.\n";
} catch (PDOException $e) {
    echo "purchase_price in order_items might already exist: " . $e->getMessage() . "\n";
}

// 3. Initialize purchase_price for existing variants (70% of current selling price)
try {
    $db->query("UPDATE `product_variants` SET `purchase_price` = `price` * 0.70 WHERE `purchase_price` IS NULL");
    $db->execute();
    echo "Initialized purchase_price for existing product variants (70% of selling price).\n";
} catch (PDOException $e) {
    echo "Error initializing variant purchase prices: " . $e->getMessage() . "\n";
}

// 4. Initialize purchase_price for existing order items (70% of selling price)
try {
    $db->query("UPDATE `order_items` SET `purchase_price` = `price` * 0.70 WHERE `purchase_price` = 0.00 OR `purchase_price` IS NULL");
    $db->execute();
    echo "Initialized purchase_price for existing order items (70% of sold price).\n";
} catch (PDOException $e) {
    echo "Error initializing order items purchase prices: " . $e->getMessage() . "\n";
}

// 5. Create expenses table
try {
    $db->query("CREATE TABLE IF NOT EXISTS `expenses` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `title` VARCHAR(255) NOT NULL,
        `category` VARCHAR(100) NOT NULL,
        `amount` DECIMAL(15,2) NOT NULL,
        `date` DATE NOT NULL,
        `description` TEXT DEFAULT NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    $db->execute();
    echo "Created expenses table successfully.\n";
} catch (PDOException $e) {
    echo "Error creating expenses table: " . $e->getMessage() . "\n";
}

// 6. Seed mock expenses
try {
    // Check if table is empty
    $db->query("SELECT COUNT(*) as count FROM `expenses`");
    $count = $db->single()['count'] ?? 0;
    
    if ($count == 0) {
        $mockExpenses = [
            [
                'title' => 'Sewa Ruko Bulanan (Semarang Tengah)',
                'category' => 'Sewa',
                'amount' => 1500000.00,
                'date' => date('Y-m-d', strtotime('-25 days')),
                'description' => 'Biaya bulanan sewa ruko operasional toko fisik.'
            ],
            [
                'title' => 'Gaji Karyawan Toko & Admin Chat (Mei)',
                'category' => 'Gaji',
                'amount' => 3500000.00,
                'date' => date('Y-m-d', strtotime('-15 days')),
                'description' => 'Pembayaran gaji bulanan staf penjaga toko dan admin operasional.'
            ],
            [
                'title' => 'Beban Listrik, Air & Paket Internet Biznet',
                'category' => 'Operasional',
                'amount' => 450000.00,
                'date' => date('Y-m-d', strtotime('-10 days')),
                'description' => 'Biaya utilitas internet fiber dan listrik ruko bulanan.'
            ],
            [
                'title' => 'Iklan Promosi Facebook Ads & Instagram Ads',
                'category' => 'Marketing',
                'amount' => 1200000.00,
                'date' => date('Y-m-d', strtotime('-5 days')),
                'description' => 'Budget iklan online mingguan untuk menarik pelanggan.'
            ]
        ];
        
        foreach ($mockExpenses as $expense) {
            $db->query("INSERT INTO `expenses` (title, category, amount, date, description) 
                        VALUES (:title, :category, :amount, :date, :description)");
            $db->bind('title', $expense['title']);
            $db->bind('category', $expense['category']);
            $db->bind('amount', $expense['amount']);
            $db->bind('date', $expense['date']);
            $db->bind('description', $expense['description']);
            $db->execute();
        }
        echo "Seeded mock operational expenses into expenses table.\n";
    } else {
        echo "Expenses table already contains data, skipping seeding.\n";
    }
} catch (PDOException $e) {
    echo "Error seeding mock expenses: " . $e->getMessage() . "\n";
}

echo "Database migration completed!\n";
