<?php
// scratch/run_noc_settings_migration.php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/Core/Database.php';

$db = new Database();

try {
    // 1. Create noc_settings table
    $db->query("CREATE TABLE IF NOT EXISTS `noc_settings` (
        `key_name`   VARCHAR(50) PRIMARY KEY,
        `value_text` TEXT NOT NULL,
        `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    $db->execute();
    echo "Table noc_settings created or already exists.\n";

    // 2. Seed default values
    $defaults = [
        'block_sqli'              => 'block', // block, log, off
        'block_xss'               => 'block', // block, log, off
        'block_path_scan'         => 'block', // block, log, off
        'block_traversal'         => 'block', // block, log, off
        'enable_security_headers' => '1',     // 1 = enabled, 0 = disabled
        'whitelisted_ips'         => '127.0.0.1,::1'
    ];

    foreach ($defaults as $key => $val) {
        $db->query("INSERT IGNORE INTO noc_settings (key_name, value_text) VALUES (:k, :v)");
        $db->bind('k', $key);
        $db->bind('v', $val);
        $db->execute();
    }
    echo "Default security settings seeded.\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\nNOC Settings Migration completed successfully!\n";
