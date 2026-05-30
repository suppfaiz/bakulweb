<?php
// scratch/run_noc_migration.php — NOC Database Migration
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/Core/Database.php';

$db = new Database();

// 1. Create noc_traffic_logs table
try {
    $db->query("CREATE TABLE IF NOT EXISTS `noc_traffic_logs` (
        `id`              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `ip`              VARCHAR(45)   NOT NULL,
        `method`          VARCHAR(10)   NOT NULL,
        `uri`             VARCHAR(2048) NOT NULL,
        `query_string`    TEXT          DEFAULT NULL,
        `post_data`       TEXT          DEFAULT NULL,
        `user_agent`      VARCHAR(512)  DEFAULT NULL,
        `referer`         VARCHAR(1024) DEFAULT NULL,
        `response_code`   SMALLINT      DEFAULT NULL,
        `exec_time_ms`    FLOAT         DEFAULT NULL,
        `threat_level`    ENUM('none','low','medium','high','critical') NOT NULL DEFAULT 'none',
        `threat_type`     VARCHAR(100)  DEFAULT NULL,
        `threat_detail`   TEXT          DEFAULT NULL,
        `is_blocked`      TINYINT(1)    NOT NULL DEFAULT 0,
        `created_at`      TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_ip (`ip`),
        INDEX idx_threat (`threat_level`),
        INDEX idx_created (`created_at`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    $db->execute();
    echo "Table noc_traffic_logs created or already exists.\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// 2. Create noc_blocked_ips table
try {
    $db->query("CREATE TABLE IF NOT EXISTS `noc_blocked_ips` (
        `id`          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `ip`          VARCHAR(45)  NOT NULL UNIQUE,
        `reason`      VARCHAR(255) NOT NULL,
        `auto_blocked` TINYINT(1)  NOT NULL DEFAULT 1,
        `block_count` INT          NOT NULL DEFAULT 1,
        `expires_at`  DATETIME     DEFAULT NULL,
        `blocked_at`  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_ip (`ip`),
        INDEX idx_expires (`expires_at`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    $db->execute();
    echo "Table noc_blocked_ips created or already exists.\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// 3. Seed mock traffic logs with realistic threat data
try {
    $db->query("SELECT COUNT(*) as cnt FROM noc_traffic_logs");
    $count = $db->single()['cnt'] ?? 0;
    if ($count == 0) {
        $ips = ['103.21.244.1', '185.220.101.45', '42.202.33.11', '180.76.15.28', '192.168.1.1', '103.99.115.44', '52.15.90.203', '115.68.92.183', '202.65.151.1'];
        $threats = [
            ['none', null, null],
            ['none', null, null],
            ['none', null, null],
            ['low', 'Path Scan', "Attempted access to /.env"],
            ['low', 'Path Scan', "Attempted access to /.git/config"],
            ['medium', 'XSS', "Payload detected: <script>alert(1)</script>"],
            ['high', 'SQLi', "Payload: ' OR '1'='1"],
            ['critical', 'SQLi', "Payload: UNION SELECT username,password FROM users"],
            ['high', 'Path Traversal', "Payload: ../../etc/passwd"],
        ];
        $uris = ['/', '/products', '/admin', '/checkout', '/?s=<script>alert(1)</script>', '/?id=1+UNION+SELECT', '/../../etc/passwd', '/.env', '/.git/config', '/wp-admin/admin.php'];
        $uas  = [
            'Mozilla/5.0 (compatible; Googlebot/2.1)',
            'sqlmap/1.7.8#stable',
            'Nikto/2.1.6',
            'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36',
            'curl/7.85.0',
            'python-requests/2.28.2',
        ];
        for ($i = 0; $i < 80; $i++) {
            $t   = $threats[array_rand($threats)];
            $ip  = $ips[array_rand($ips)];
            $uri = $uris[array_rand($uris)];
            $ua  = $uas[array_rand($uas)];
            $ts  = date('Y-m-d H:i:s', time() - rand(0, 86400 * 7));
            $db->query("INSERT INTO noc_traffic_logs (ip, method, uri, user_agent, response_code, exec_time_ms, threat_level, threat_type, threat_detail, is_blocked, created_at)
                        VALUES (:ip, :method, :uri, :ua, :rc, :et, :tl, :tt, :td, :ib, :ca)");
            $db->bind('ip', $ip);
            $db->bind('method', rand(0, 5) > 1 ? 'GET' : 'POST');
            $db->bind('uri', $uri);
            $db->bind('ua', $ua);
            $db->bind('rc', $t[0] === 'none' ? 200 : ($t[0] === 'low' ? 403 : 403));
            $db->bind('et', round(rand(5, 800) / 10, 1));
            $db->bind('tl', $t[0]);
            $db->bind('tt', $t[1]);
            $db->bind('td', $t[2]);
            $db->bind('ib', $t[0] === 'critical' || $t[0] === 'high' ? 1 : 0);
            $db->bind('ca', $ts);
            $db->execute();
        }
        echo "Seeded 80 mock traffic log records.\n";
    } else {
        echo "noc_traffic_logs already has data, skipping seed.\n";
    }
} catch (PDOException $e) {
    echo "Seed error: " . $e->getMessage() . "\n";
}

// 4. Seed mock blocked IPs
try {
    $db->query("SELECT COUNT(*) as cnt FROM noc_blocked_ips");
    $count = $db->single()['cnt'] ?? 0;
    if ($count == 0) {
        $blocked = [
            ['185.220.101.45', 'SQL Injection detected (UNION SELECT)', 1, 5],
            ['42.202.33.11',   'Path Traversal: /etc/passwd', 1, 3],
            ['103.21.244.1',   'Repeated XSS Payload (>10 attempts)', 1, 12],
        ];
        foreach ($blocked as $b) {
            $expires = date('Y-m-d H:i:s', time() + 86400 * 30);
            $db->query("INSERT IGNORE INTO noc_blocked_ips (ip, reason, auto_blocked, block_count, expires_at) VALUES (:ip, :r, :ab, :bc, :ex)");
            $db->bind('ip', $b[0]);
            $db->bind('r', $b[1]);
            $db->bind('ab', $b[2]);
            $db->bind('bc', $b[3]);
            $db->bind('ex', $expires);
            $db->execute();
        }
        echo "Seeded 3 mock blocked IPs.\n";
    } else {
        echo "noc_blocked_ips already has data, skipping seed.\n";
    }
} catch (PDOException $e) {
    echo "Blocked IPs seed error: " . $e->getMessage() . "\n";
}

echo "\nNOC Migration completed successfully!\n";
