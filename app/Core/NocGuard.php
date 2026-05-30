<?php
/**
 * NocGuard — Middleware Deteksi Ancaman Siber & Logger Traffic
 * Dipanggil di public/index.php sebelum routing.
 */
class NocGuard {

    // Password NOC (bisa diubah di config/config.php via constant NOC_PASSWORD)
    const DEFAULT_NOC_PASSWORD = 'NocBakul2026!';

    // Pola deteksi SQL Injection
    private static $sqliPatterns = [
        "/('|%27)\s*(or|OR|Or)\s*('|%27|\d)/i",
        "/(UNION\s+(ALL\s+)?SELECT)/i",
        "/(SELECT\s+.+\s+FROM)/i",
        "/(INSERT\s+INTO|DELETE\s+FROM|DROP\s+TABLE|ALTER\s+TABLE)/i",
        "/(INFORMATION_SCHEMA|SLEEP\s*\(|BENCHMARK\s*\(|LOAD_FILE\s*\()/i",
        "/(\-\-|\#|\/\*.*\*\/)/",
        "/(1\s*=\s*1|OR\s+1\s*=\s*1|' OR ')/i",
    ];

    // Pola deteksi XSS
    private static $xssPatterns = [
        "/<script[\s>]/i",
        "/(on(error|load|click|mouseover|focus|blur|submit|change|input|keydown|keyup|keypress)\s*=)/i",
        "/javascript\s*:/i",
        "/<iframe[\s>]/i",
        "/(eval\s*\(|document\.cookie|document\.write)/i",
        "/%3Cscript/i",
    ];

    // Pola deteksi Path Traversal
    private static $pathTraversalPatterns = [
        "/\.\.\/|\.\.\\\\/",
        "/\/etc\/passwd|\/etc\/shadow|\/proc\/self/i",
        "/\.(bash_history|ssh|htpasswd|htaccess)/i",
    ];

    // File sensitif yang seharusnya tidak bisa diakses publik
    private static $sensitiveFilePaths = [
        '/.env', '/.env.local', '/.env.production',
        '/.git', '/.git/config', '/.gitignore',
        '/composer.json', '/composer.lock',
        '/phpinfo.php', '/info.php', '/test.php', '/debug.php',
        '/wp-admin', '/wp-login.php', '/xmlrpc.php',
        '/phpmyadmin', '/pma', '/adminer',
        '/config.php', '/database.sql',
    ];

    private static $db = null;
    private static $logId = null;
    private static $startTime = null;
    private static $threatLevel = 'none';
    private static $threatType  = null;
    private static $threatDetail = null;
    private static $isBlocked   = false;

    public static function init() {
        self::$startTime = microtime(true);

        // Jika NOC route → skip logging dan threat check
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        $uri = '/' . ltrim($uri, '/');
        if (strpos($uri, '/noc') === 0) {
            return;
        }

        try {
            require_once __DIR__ . '/../../config/config.php';
            require_once __DIR__ . '/Database.php';
            self::$db = new Database();
        } catch (Throwable $e) {
            return; // DB not ready yet, skip silently
        }

        $ip = self::getClientIP();
        $isWhitelisted = self::isIPWhitelisted($ip);

        // Send security headers if enabled
        $settings = self::getSettings();
        if (($settings['enable_security_headers'] ?? '0') === '1') {
            header("X-Frame-Options: SAMEORIGIN");
            header("X-Content-Type-Options: nosniff");
            header("X-XSS-Protection: 1; mode=block");
            header("Referrer-Policy: no-referrer-when-downgrade");
            if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
                header("Strict-Transport-Security: max-age=31536000; includeSubDomains");
            }
        }

        // 1. Check if IP is currently blocked (skip if whitelisted)
        if (!$isWhitelisted && self::isIPBlocked($ip)) {
            self::$isBlocked = true;
            self::logRequest($ip, 403, 'critical', 'Blocked IP', 'Request from known blocked IP address');
            http_response_code(403);
            self::renderBlockedPage($ip);
            exit;
        }

        // 1.5. Rate Limiting Check (Anti-Scraper & Anti-Bot)
        if (!$isWhitelisted && self::checkRateLimit($ip)) {
            self::$isBlocked = true;
            self::logRequest($ip, 429, 'high', 'Rate Limit Exceeded', 'Too many requests in a short time (Scraper Blocked)');
            http_response_code(429);
            self::renderRateLimitPage($ip);
            exit;
        }

        // 2. Analyze request for threats
        self::analyzeRequest($ip);

        // 3. Check dynamic block policies
        if (self::$threatLevel !== 'none') {
            $policy = 'block';
            if (self::$threatType === 'SQLi')           $policy = $settings['block_sqli'] ?? 'block';
            if (self::$threatType === 'XSS')            $policy = $settings['block_xss'] ?? 'block';
            if (self::$threatType === 'Path Scan')      $policy = $settings['block_path_scan'] ?? 'block';
            if (self::$threatType === 'Path Traversal') $policy = $settings['block_traversal'] ?? 'block';

            if ($policy === 'off') {
                self::$threatLevel  = 'none';
                self::$threatType   = null;
                self::$threatDetail = null;
            } elseif ($policy === 'log') {
                self::$isBlocked = false;
            } elseif ($policy === 'block') {
                if (self::$threatLevel === 'critical' || self::$threatLevel === 'high') {
                    if (!$isWhitelisted) {
                        self::autoBlockIP($ip, self::$threatType . ': ' . substr(self::$threatDetail, 0, 200));
                        self::$isBlocked = true;
                    }
                }
            }
        }

        // 4. Register shutdown handler for response code + timing
        register_shutdown_function(function() use ($ip) {
            $execMs = round((microtime(true) - self::$startTime) * 1000, 2);
            $code   = http_response_code() ?: 200;
            if (self::$isBlocked) $code = 403;
            self::logRequest($ip, $code, self::$threatLevel, self::$threatType, self::$threatDetail, $execMs);
        });

        // 5. If blocked, show blocked page
        if (self::$isBlocked) {
            http_response_code(403);
            self::renderBlockedPage($ip);
            exit;
        }
    }

    private static function analyzeRequest($ip) {
        $uri   = urldecode($_SERVER['REQUEST_URI'] ?? '');
        $path  = parse_url($uri, PHP_URL_PATH) ?? '/';
        $query = urldecode($_SERVER['QUERY_STRING'] ?? '');
        $post  = urldecode(http_build_query($_POST));
        $ua    = $_SERVER['HTTP_USER_AGENT'] ?? '';

        $combined = $uri . ' ' . $query . ' ' . $post;

        // Path scan check
        foreach (self::$sensitiveFilePaths as $sp) {
            if (stripos($path, $sp) !== false) {
                self::$threatLevel  = 'low';
                self::$threatType   = 'Path Scan';
                self::$threatDetail = "Attempted access to sensitive path: {$path}";
                return;
            }
        }

        // Path traversal check
        foreach (self::$pathTraversalPatterns as $pattern) {
            if (preg_match($pattern, $combined)) {
                self::$threatLevel  = 'high';
                self::$threatType   = 'Path Traversal';
                self::$threatDetail = "Path traversal payload detected in request";
                return;
            }
        }

        // SQLi check
        foreach (self::$sqliPatterns as $pattern) {
            if (preg_match($pattern, $combined)) {
                $level = 'high';
                if (preg_match('/(UNION\s+SELECT|INFORMATION_SCHEMA)/i', $combined)) {
                    $level = 'critical';
                }
                self::$threatLevel  = $level;
                self::$threatType   = 'SQLi';
                self::$threatDetail = "SQL Injection payload: " . substr($combined, 0, 300);
                return;
            }
        }

        // XSS check
        foreach (self::$xssPatterns as $pattern) {
            if (preg_match($pattern, $combined)) {
                self::$threatLevel  = 'medium';
                self::$threatType   = 'XSS';
                self::$threatDetail = "XSS payload detected: " . substr($combined, 0, 300);
                return;
            }
        }

        // Suspicious User-Agent (automated scanner / scrapers / bots)
        $suspiciousUAs = [
            'sqlmap', 'nikto', 'nmap', 'masscan', 'metasploit', 'zgrab',
            'python', 'scrapy', 'headless', 'selenium', 'puppeteer', 'playwright',
            'curl', 'wget', 'axios', 'urllib', 'libwww', 'perl', 'httrack', 
            'webcopy', 'postman', 'http-client', 'go-http-client'
        ];
        foreach ($suspiciousUAs as $sua) {
            if (stripos($ua, $sua) !== false) {
                self::$threatLevel  = 'medium';
                self::$threatType   = 'Scanner UA';
                self::$threatDetail = "Suspicious User-Agent: {$ua}";
                return;
            }
        }
    }

    private static function isIPBlocked($ip) {
        if (!self::$db) return false;
        try {
            self::$db->query("SELECT id FROM noc_blocked_ips WHERE ip = :ip AND (expires_at IS NULL OR expires_at > NOW()) LIMIT 1");
            self::$db->bind('ip', $ip);
            $row = self::$db->single();
            return !empty($row);
        } catch (Throwable $e) {
            return false;
        }
    }

    private static function autoBlockIP($ip, $reason) {
        if (!self::$db) return;
        try {
            self::$db->query("INSERT INTO noc_blocked_ips (ip, reason, auto_blocked, block_count, expires_at)
                              VALUES (:ip, :r, 1, 1, DATE_ADD(NOW(), INTERVAL 30 DAY))
                              ON DUPLICATE KEY UPDATE block_count = block_count + 1, reason = :r, blocked_at = NOW()");
            self::$db->bind('ip', $ip);
            self::$db->bind('r', substr($reason, 0, 250));
            self::$db->execute();
        } catch (Throwable $e) {}
    }

    private static function logRequest($ip, $code, $level, $type, $detail, $execMs = null) {
        if (!self::$db) return;
        try {
            $uri    = substr($_SERVER['REQUEST_URI'] ?? '/', 0, 2048);
            $qs     = substr($_SERVER['QUERY_STRING'] ?? '', 0, 2000);
            $post   = substr(json_encode(array_map(fn($v) => substr($v, 0, 200), $_POST)), 0, 2000);
            $ua     = substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 512);
            $ref    = substr($_SERVER['HTTP_REFERER'] ?? '', 0, 1024);
            $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

            self::$db->query("INSERT INTO noc_traffic_logs 
                (ip, method, uri, query_string, post_data, user_agent, referer, response_code, exec_time_ms, threat_level, threat_type, threat_detail, is_blocked)
                VALUES (:ip,:m,:u,:qs,:pd,:ua,:ref,:rc,:et,:tl,:tt,:td,:ib)");
            self::$db->bind('ip', $ip);
            self::$db->bind('m', $method);
            self::$db->bind('u', $uri);
            self::$db->bind('qs', $qs ?: null);
            self::$db->bind('pd', $post !== '[]' ? $post : null);
            self::$db->bind('ua', $ua ?: null);
            self::$db->bind('ref', $ref ?: null);
            self::$db->bind('rc', $code);
            self::$db->bind('et', $execMs);
            self::$db->bind('tl', $level ?? 'none');
            self::$db->bind('tt', $type);
            self::$db->bind('td', $detail ? substr($detail, 0, 2000) : null);
            self::$db->bind('ib', self::$isBlocked ? 1 : 0);
            self::$db->execute();
        } catch (Throwable $e) {}
    }

    public static function getClientIP() {
        $keys = ['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'REMOTE_ADDR'];
        foreach ($keys as $key) {
            if (!empty($_SERVER[$key])) {
                $ip = trim(explode(',', $_SERVER[$key])[0]);
                if (filter_var($ip, FILTER_VALIDATE_IP)) return $ip;
            }
        }
        return '0.0.0.0';
    }

    private static function renderBlockedPage($ip) {
        echo '<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>403 - Akses Ditolak | BAKUL Security</title>
        <style>
            *{margin:0;padding:0;box-sizing:border-box;}
            body{font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,sans-serif;background:#f8fafc;display:flex;align-items:center;justify-content:center;min-height:100vh;}
            .card{background:#fff;border:1px solid #e2e8f0;border-radius:16px;padding:48px 40px;max-width:500px;text-align:center;box-shadow:0 4px 24px rgba(0,0,0,.06);}
            .icon{font-size:64px;margin-bottom:16px;}
            h1{font-size:28px;font-weight:700;color:#1a202c;margin-bottom:8px;}
            p{color:#718096;line-height:1.6;margin-bottom:12px;}
            .ip{font-family:monospace;background:#fff5f5;color:#e53e3e;border:1px solid #fed7d7;border-radius:6px;padding:6px 14px;display:inline-block;font-size:14px;margin:8px 0;}
            .sub{font-size:13px;color:#a0aec0;margin-top:20px;}
        </style></head><body>
        <div class="card">
            <div class="icon">🛡️</div>
            <h1>Akses Ditolak</h1>
            <p>IP Anda telah diblokir oleh sistem keamanan BAKUL karena terdeteksi aktivitas berbahaya.</p>
            <div class="ip">' . htmlspecialchars($ip) . '</div>
            <p>Jika Anda merasa ini adalah kesalahan, hubungi administrator.</p>
            <div class="sub">BAKUL Security Shield &bull; HTTP 403 Forbidden</div>
        </div></body></html>';
    }

    private static $settings = null;

    public static function getSettings() {
        if (self::$settings !== null) {
            return self::$settings;
        }

        $defaults = [
            'block_sqli'              => 'block',
            'block_xss'               => 'block',
            'block_path_scan'         => 'block',
            'block_traversal'         => 'block',
            'enable_security_headers' => '1',
            'whitelisted_ips'         => '127.0.0.1,::1'
        ];

        if (!self::$db) {
            try {
                require_once __DIR__ . '/Database.php';
                self::$db = new Database();
            } catch (Throwable $e) {
                self::$settings = $defaults;
                return self::$settings;
            }
        }

        try {
            self::$db->query("SELECT key_name, value_text FROM noc_settings");
            $rows = self::$db->resultSet() ?: [];
            foreach ($rows as $r) {
                $defaults[$r['key_name']] = $r['value_text'];
            }
        } catch (Throwable $e) {}

        self::$settings = $defaults;
        return self::$settings;
    }

    public static function isIPWhitelisted($ip) {
        $settings = self::getSettings();
        $whitelist = array_map('trim', explode(',', $settings['whitelisted_ips'] ?? ''));
        return in_array($ip, $whitelist);
    }

    private static function checkRateLimit($ip) {
        if (!self::$db) return false;
        try {
            // Count requests in the last 10 seconds for this IP
            self::$db->query("SELECT COUNT(*) as count FROM noc_traffic_logs 
                              WHERE ip = :ip AND created_at >= NOW() - INTERVAL 10 SECOND");
            self::$db->bind('ip', $ip);
            $res = self::$db->single();
            
            // If request count exceeds 25 in 10 seconds, trigger auto-block
            if ($res && $res['count'] >= 25) {
                self::autoBlockIP($ip, 'Rate Limit Exceeded: ' . $res['count'] . ' requests in 10s (Scraper Auto-Blocked)');
                return true;
            }
        } catch (Throwable $e) {}
        return false;
    }

    private static function renderRateLimitPage($ip) {
        echo '<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>429 - Terlalu Banyak Permintaan | BAKUL Security</title>
        <style>
            *{margin:0;padding:0;box-sizing:border-box;}
            body{font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,sans-serif;background:#f8fafc;display:flex;align-items:center;justify-content:center;min-height:100vh;}
            .card{background:#fff;border:1px solid #e2e8f0;border-radius:16px;padding:48px 40px;max-width:500px;text-align:center;box-shadow:0 4px 24px rgba(0,0,0,.06);}
            .icon{font-size:64px;margin-bottom:16px;}
            h1{font-size:28px;font-weight:700;color:#1a202c;margin-bottom:8px;}
            p{color:#718096;line-height:1.6;margin-bottom:12px;}
            .ip{font-family:monospace;background:#fff5f5;color:#e53e3e;border:1px solid #fed7d7;border-radius:6px;padding:6px 14px;display:inline-block;font-size:14px;margin:8px 0;}
            .sub{font-size:13px;color:#a0aec0;margin-top:20px;}
        </style></head><body>
        <div class="card">
            <div class="icon">🚦</div>
            <h1>Batas Kecepatan Terlampaui</h1>
            <p>Sistem kami mendeteksi aktivitas berlebih dari alamat IP Anda dalam waktu singkat. Akses dibatasi sementara guna mencegah scraping otomatis.</p>
            <div class="ip">IP Anda: ' . htmlspecialchars($ip) . '</div>
            <p class="sub">Silakan tunggu beberapa saat sebelum mencoba memuat ulang halaman.</p>
        </div></body></html>';
    }
}
