<?php
// scratch/seed_admin.php — Seed Default Admin Account
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/Core/Database.php';

$db = new Database();

try {
    $db->query("SELECT COUNT(*) as count FROM users");
    $res = $db->single();
    if ($res['count'] == 0) {
        $username = 'admin';
        $email = 'admin@bakul.com';
        $password = 'admin123';
        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        $db->query("INSERT INTO users (username, email, password, role, is_verified) 
                    VALUES (:username, :email, :password, 'superadmin', 1)");
        $db->bind(':username', $username);
        $db->bind(':email', $email);
        $db->bind(':password', $password_hash);
        $db->execute();
        
        echo "Default admin user created successfully!\n";
        echo "Email: $email\n";
        echo "Password: $password\n";
    } else {
        echo "Users table already has data. Seeding skipped.\n";
    }
} catch (PDOException $e) {
    echo "Error seeding admin: " . $e->getMessage() . "\n";
}
