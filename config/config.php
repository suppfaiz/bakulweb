<?php
// config.php — Konfigurasi Database & Aplikasi
// Membaca dari environment variable (Docker) atau fallback ke nilai lokal
date_default_timezone_set('Asia/Jakarta');


define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_NAME', getenv('DB_NAME') ?: 'bakul_ecommerce');

// Base URL Dinamis (otomatis HTTP/HTTPS)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'
    || (($_SERVER['SERVER_PORT'] ?? '') == 443)) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
define('BASEURL', $protocol . $host);
