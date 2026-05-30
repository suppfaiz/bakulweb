<?php
/**
 * Migration: Fix order_items FK untuk support hapus produk
 * 
 * Masalah: order_items.product_variant_id NOT NULL + FK ke product_variants
 *          menyebabkan error saat admin hapus produk yang sudah pernah dipesan.
 * 
 * Solusi: Ubah kolom jadi NULLABLE + tambah ON DELETE SET NULL pada FK
 * Jalankan: docker compose exec -T app php scratch/fix_order_items_fk.php
 */

define('DBHOST', getenv('DB_HOST') ?: 'db');
define('DBNAME', getenv('DB_NAME') ?: 'bakul_ecommerce');
define('DBUSER', getenv('DB_USER') ?: 'bakul_user');
define('DBPASS', getenv('DB_PASS') ?: 'bakul_secret');

echo "=== Migration: Fix order_items FK constraint ===\n\n";

try {
    $pdo = new PDO(
        "mysql:host=" . DBHOST . ";dbname=" . DBNAME . ";charset=utf8mb4",
        DBUSER, DBPASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    echo "[OK] Koneksi database berhasil.\n";

    // Step 1: Drop FK constraint lama
    echo "[...] Menghapus FK constraint lama pada order_items...\n";
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");

    // Ambil nama FK constraint dari information_schema
    $stmt = $pdo->query("
        SELECT CONSTRAINT_NAME 
        FROM information_schema.KEY_COLUMN_USAGE 
        WHERE TABLE_SCHEMA = '" . DBNAME . "'
          AND TABLE_NAME = 'order_items'
          AND COLUMN_NAME = 'product_variant_id'
          AND REFERENCED_TABLE_NAME = 'product_variants'
    ");
    $fkRow = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($fkRow) {
        $fkName = $fkRow['CONSTRAINT_NAME'];
        echo "[OK] FK ditemukan: {$fkName}. Menghapus...\n";
        $pdo->exec("ALTER TABLE `order_items` DROP FOREIGN KEY `{$fkName}`");
        echo "[OK] FK lama berhasil dihapus.\n";
    } else {
        echo "[INFO] FK constraint tidak ditemukan (mungkin sudah dihapus sebelumnya).\n";
    }

    // Step 2: Ubah kolom jadi nullable
    echo "[...] Mengubah kolom product_variant_id menjadi NULLABLE...\n";
    $pdo->exec("ALTER TABLE `order_items` 
                MODIFY COLUMN `product_variant_id` INT(11) NULL DEFAULT NULL");
    echo "[OK] Kolom berhasil diubah menjadi nullable.\n";

    // Step 3: Tambah FK baru dengan ON DELETE SET NULL
    echo "[...] Menambahkan FK baru dengan ON DELETE SET NULL...\n";
    $pdo->exec("ALTER TABLE `order_items` 
                ADD CONSTRAINT `order_items_ibfk_2` 
                FOREIGN KEY (`product_variant_id`) 
                REFERENCES `product_variants`(`id`) 
                ON DELETE SET NULL");
    echo "[OK] FK baru berhasil ditambahkan.\n";

    // Step 4: Lakukan hal yang sama untuk inventory_logs jika ada
    $stmt2 = $pdo->query("
        SELECT CONSTRAINT_NAME 
        FROM information_schema.KEY_COLUMN_USAGE 
        WHERE TABLE_SCHEMA = '" . DBNAME . "'
          AND TABLE_NAME = 'inventory_logs'
          AND COLUMN_NAME = 'product_variant_id'
          AND REFERENCED_TABLE_NAME = 'product_variants'
    ");
    $fkRow2 = $stmt2->fetch(PDO::FETCH_ASSOC);

    if ($fkRow2) {
        $fkName2 = $fkRow2['CONSTRAINT_NAME'];
        echo "[...] Memperbaiki FK pada inventory_logs ({$fkName2})...\n";
        $pdo->exec("ALTER TABLE `inventory_logs` DROP FOREIGN KEY `{$fkName2}`");
        $pdo->exec("ALTER TABLE `inventory_logs` 
                    MODIFY COLUMN `product_variant_id` INT(11) NULL DEFAULT NULL");
        $pdo->exec("ALTER TABLE `inventory_logs` 
                    ADD CONSTRAINT `inventory_logs_ibfk_1` 
                    FOREIGN KEY (`product_variant_id`) 
                    REFERENCES `product_variants`(`id`) 
                    ON DELETE SET NULL");
        echo "[OK] FK inventory_logs berhasil diperbaiki.\n";
    }

    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

    echo "\n=== MIGRASI SELESAI ===\n";
    echo "[OK] Sekarang admin dapat menghapus produk meskipun sudah pernah dipesan.\n";
    echo "[INFO] Riwayat order_items tetap tersimpan (product_variant_id akan NULL).\n";

} catch (PDOException $e) {
    echo "[ERROR] " . $e->getMessage() . "\n";
    exit(1);
}
