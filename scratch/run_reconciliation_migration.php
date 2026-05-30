<?php
// scratch/run_reconciliation_migration.php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/Core/Database.php';

$db = new Database();

// Create bank_statements table
$query1 = "CREATE TABLE IF NOT EXISTS `bank_statements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `statement_date` datetime NOT NULL,
  `description` varchar(255) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `reference_code` varchar(100) NOT NULL,
  `reconciled_order_id` int(11) DEFAULT NULL,
  `status` enum('unreconciled','reconciled') NOT NULL DEFAULT 'unreconciled',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`reconciled_order_id`) REFERENCES `orders`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

try {
    $db->query($query1);
    $db->execute();
    echo "Table bank_statements created or already exists.\n";
} catch (PDOException $e) {
    echo "Error creating bank_statements table: " . $e->getMessage() . "\n";
}

// Check if we need to seed dummy bank statements based on actual paid orders
$db->query("SELECT COUNT(*) as count FROM bank_statements");
$res = $db->single();
if ($res['count'] == 0) {
    // Fetch last 5 paid orders to create matching bank statement records
    $db->query("SELECT * FROM orders WHERE payment_status = 'paid' ORDER BY created_at DESC LIMIT 5");
    $paidOrders = $db->resultSet();
    
    foreach ($paidOrders as $idx => $order) {
        $date = date('Y-m-d H:i:s', strtotime($order['created_at']) + 900); // 15 mins later
        $description = "TRF DR " . strtoupper($order['recipient_name'] ?? 'CUSTOMER') . " - " . $order['invoice'];
        $amount = $order['total_amount'];
        $refCode = "TX" . date('Ymd') . "00" . ($idx + 1);
        
        $db->query("INSERT INTO bank_statements (statement_date, description, amount, reference_code, status) VALUES (:date, :description, :amount, :ref, 'unreconciled')");
        $db->bind('date', $date);
        $db->bind('description', $description);
        $db->bind('amount', $amount);
        $db->bind('ref', $refCode);
        $db->execute();
    }
    
    // Add two unmatched dummy bank statements
    $db->query("INSERT INTO bank_statements (statement_date, description, amount, reference_code, status) VALUES 
        (NOW(), 'BIAYA ADM BULANAN', -15000.00, 'ADM-BANK', 'unreconciled'),
        (NOW() - INTERVAL 1 DAY, 'TRF DR UNKNOWN - REF 99201', 250000.00, 'TX99201', 'unreconciled')");
    $db->execute();
    
    echo "Seeded dummy bank statements matched with paid orders.\n";
}

echo "Bank reconciliation migration completed successfully.\n";
