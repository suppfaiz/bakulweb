<?php
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
