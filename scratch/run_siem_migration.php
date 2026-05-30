<?php
// scratch/run_siem_migration.php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/Core/Database.php';

$db = new Database();

try {
    // 1. Create noc_siem_alerts table
    $db->query("CREATE TABLE IF NOT EXISTS `noc_siem_alerts` (
        `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `ip`          VARCHAR(45)   NOT NULL,
        `rule_name`   VARCHAR(100)  NOT NULL,
        `severity`    ENUM('low','medium','high','critical') NOT NULL,
        `description` TEXT          NOT NULL,
        `event_count` INT           NOT NULL DEFAULT 1,
        `status`      ENUM('open','resolved','ignored') NOT NULL DEFAULT 'open',
        `created_at`  TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at`  TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_ip (`ip`),
        INDEX idx_status (`status`),
        INDEX idx_created (`created_at`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    $db->execute();
    echo "Table noc_siem_alerts created or already exists.\n";

    // 2. Seed default mock alerts
    $db->query("SELECT COUNT(*) as cnt FROM noc_siem_alerts");
    $count = $db->single()['cnt'] ?? 0;
    
    if ($count == 0) {
        $alerts = [
            [
                'ip' => '185.220.101.45',
                'rule_name' => 'Brute Force Detection',
                'severity' => 'high',
                'description' => 'Detected 12 failed login attempts to /admin/login within 5 minutes.',
                'event_count' => 12
            ],
            [
                'ip' => '42.202.33.11',
                'rule_name' => 'Aggressive Scanner',
                'severity' => 'medium',
                'description' => 'IP performed 28 rapid directory scans seeking sensitive configuration files.',
                'event_count' => 28
            ],
            [
                'ip' => '103.21.244.1',
                'rule_name' => 'Multi-Vector Cyber Attack',
                'severity' => 'critical',
                'description' => 'IP executed SQL Injection and XSS payloads within the same session.',
                'event_count' => 4
            ]
        ];

        foreach ($alerts as $a) {
            $db->query("INSERT INTO noc_siem_alerts (ip, rule_name, severity, description, event_count, status) 
                        VALUES (:ip, :rule, :sev, :desc, :ec, 'open')");
            $db->bind('ip', $a['ip']);
            $db->bind('rule', $a['rule_name']);
            $db->bind('sev', $a['severity']);
            $db->bind('desc', $a['description']);
            $db->bind('ec', $a['event_count']);
            $db->execute();
        }
        echo "Seeded default mock SIEM alerts.\n";
    } else {
        echo "noc_siem_alerts already has data, skipping seed.\n";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\nSIEM Migration completed successfully!\n";
