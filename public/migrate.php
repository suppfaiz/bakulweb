<?php
// public/migrate.php
if (php_sapi_name() !== 'cli' && !isset($_GET['run'])) {
    echo "This script is designed to be run from the command line, or via web browser by visiting: ?run=1";
    exit;
}

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/Core/Database.php';

$db = new Database();

// 1. Create migrations tracking table if not exists
$db->query("CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `migration_name` varchar(255) NOT NULL,
  `executed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `migration_name` (`migration_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
$db->execute();

echo "Migrations table ready.\n";

// 2. Scan scratch/ directory for migration files
$migrationDir = __DIR__ . '/../scratch';
$files = glob($migrationDir . '/*.php');
sort($files);

foreach ($files as $file) {
    $basename = basename($file);
    
    // Check if migration already executed
    $db->query("SELECT id FROM migrations WHERE migration_name = :name");
    $db->bind('name', $basename);
    if ($db->single()) {
        echo "Migration '$basename' already executed. Skipping.\n";
        continue;
    }

    echo "Running migration '$basename'...\n";
    
    // Capture output of the migration script by including it
    ob_start();
    try {
        // Include the migration script
        include $file;
        $output = ob_get_clean();
        echo "Migration '$basename' output:\n$output\n";
        
        // Log to migrations table
        $db->query("INSERT INTO migrations (migration_name) VALUES (:name)");
        $db->bind('name', $basename);
        $db->execute();
        echo "Migration '$basename' successfully logged.\n";
    } catch (Throwable $e) {
        ob_get_clean();
        echo "ERROR running migration '$basename': " . $e->getMessage() . "\n";
    }
}

echo "All migrations completed.\n";
