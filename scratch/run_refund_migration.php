<?php
// scratch/run_refund_migration.php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/Core/Database.php';

$db = new Database();

// Add refund_balance column to users table if it doesn't exist
try {
    $db->query("ALTER TABLE `users` ADD COLUMN `refund_balance` DECIMAL(15,2) NOT NULL DEFAULT 0.00 AFTER `address` ");
    $db->execute();
    echo "Added refund_balance column to users table.\n";
} catch (PDOException $e) {
    echo "refund_balance column might already exist: " . $e->getMessage() . "\n";
}

// Create refund_requests table
$query1 = "CREATE TABLE IF NOT EXISTS `refund_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `reason` text NOT NULL,
  `proof_path` varchar(255) DEFAULT NULL,
  `status` enum('pending','under_review','approved','rejected') NOT NULL DEFAULT 'pending',
  `admin_notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

try {
    $db->query($query1);
    $db->execute();
    echo "Table refund_requests created or already exists.\n";
} catch (PDOException $e) {
    echo "Error creating refund_requests table: " . $e->getMessage() . "\n";
}

// Create withdrawals table
$query2 = "CREATE TABLE IF NOT EXISTS `withdrawals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `bank_name` varchar(100) NOT NULL,
  `account_number` varchar(50) NOT NULL,
  `account_holder` varchar(100) NOT NULL,
  `status` enum('pending','processing','completed','failed') NOT NULL DEFAULT 'pending',
  `admin_notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

try {
    $db->query($query2);
    $db->execute();
    echo "Table withdrawals created or already exists.\n";
} catch (PDOException $e) {
    echo "Error creating withdrawals table: " . $e->getMessage() . "\n";
}

echo "Refund & Withdrawal migrations completed successfully.\n";
