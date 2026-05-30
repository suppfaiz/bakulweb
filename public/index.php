<?php
// Deteksi environment (localhost atau production)
$isLocalhost = in_array($_SERVER['HTTP_HOST'] ?? '', ['localhost', '127.0.0.1', '[::1]']) 
    || (strpos($_SERVER['HTTP_HOST'] ?? '', 'localhost:') === 0);

if ($isLocalhost) {
    // Development/Local: Tampilkan error secara detail untuk debugging
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    // Production/VPS: Sembunyikan error dari publik, catat ke log sistem internal
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    ini_set('log_errors', 1);
    error_reporting(E_ALL);
}

// Serve static files directly if using PHP built-in server
if (php_sapi_name() === 'cli-server') {
    $path = realpath(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    if ($path && is_file($path)) {
        return false; // serve the requested resource as-is
    }
}

if (!session_id()) session_start();

require_once __DIR__ . '/../app/init.php';

NocGuard::init();

$app = new App();
