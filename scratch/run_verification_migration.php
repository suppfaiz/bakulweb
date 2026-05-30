<?php
// scratch/run_verification_migration.php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/Core/Database.php';

$db = new Database();

// Add is_verified column
try {
    $db->query("ALTER TABLE `users` ADD COLUMN `is_verified` TINYINT(1) NOT NULL DEFAULT 0 AFTER `role` ");
    $db->execute();
    echo "Added is_verified column to users table.\n";
} catch (PDOException $e) {
    echo "is_verified column might already exist: " . $e->getMessage() . "\n";
}

// Add verification_code column
try {
    $db->query("ALTER TABLE `users` ADD COLUMN `verification_code` VARCHAR(6) DEFAULT NULL AFTER `is_verified` ");
    $db->execute();
    echo "Added verification_code column to users table.\n";
} catch (PDOException $e) {
    echo "verification_code column might already exist: " . $e->getMessage() . "\n";
}

// Update existing users to be verified (so they don't get locked out)
try {
    $db->query("UPDATE `users` SET `is_verified` = 1 WHERE `is_verified` = 0");
    $db->execute();
    echo "Set is_verified = 1 for all existing users.\n";
} catch (PDOException $e) {
    echo "Error updating existing users: " . $e->getMessage() . "\n";
}

echo "Database migration completed!\n";
