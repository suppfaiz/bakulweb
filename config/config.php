<?php
// config.php — Konfigurasi Database & Aplikasi
// Membaca dari environment variable (Docker) atau fallback ke nilai lokal
date_default_timezone_set('Asia/Jakarta');


define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_NAME', getenv('DB_NAME') ?: 'bakul_ecommerce');

// Base URL Dinamis (otomatis HTTP/HTTPS, mendeteksi reverse proxy/SSL termination)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'
    || (($_SERVER['SERVER_PORT'] ?? '') == 443)
    || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https')
    || (($_SERVER['HTTP_X_FORWARDED_SSL'] ?? '') === 'on')) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
define('BASEURL', $protocol . $host);

// Password NOC untuk otentikasi log/keamanan
define('NOC_PASSWORD', getenv('NOC_PASSWORD') ?: 'NocBakul2026!');

// Path kustom untuk mengakses Network Operations Center (NOC)
define('NOC_PATH', getenv('NOC_PATH') ?: 'noc');
