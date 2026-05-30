<?php

class App {
    protected $controller = 'HomeController';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->parseURL();

        if (isset($url[0])) {
            $nocPath = defined('NOC_PATH') ? NOC_PATH : 'noc';
            
            // Jika path NOC kustom diaktifkan (bukan 'noc')
            if (strtolower($nocPath) !== 'noc') {
                if (strtolower($url[0]) === 'noc') {
                    // Blokir akses langsung ke default /noc route
                    http_response_code(404);
                    echo '<!DOCTYPE html><html><head><title>404 Not Found</title></head><body><h1>404 Not Found</h1>The requested URL was not found on this server.</body></html>';
                    exit;
                }
                if (strtolower($url[0]) === strtolower($nocPath)) {
                    // Petakan rute kustom ke 'noc' secara internal agar ditangani NocController
                    $url[0] = 'noc';
                }
            }
        }

        // Check if file controller exists
        if (isset($url[0])) {
            $controllerName = ucfirst($url[0]) . 'Controller';
            if (file_exists(__DIR__ . '/../Controllers/' . $controllerName . '.php')) {
                $this->controller = $controllerName;
                unset($url[0]);
            }
        }

        require_once __DIR__ . '/../Controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        // Check method
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // Params
        if (!empty($url)) {
            $this->params = array_values($url);
        }

        // Run controller & method, send params if any
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseURL() {
        $url = rtrim($_SERVER['REQUEST_URI'], '/');
        $basePath = parse_url(BASEURL, PHP_URL_PATH);
        if ($basePath && strpos($url, $basePath) === 0) {
            $url = substr($url, strlen($basePath));
        }
        
        if (($pos = strpos($url, '?')) !== false) {
            $url = substr($url, 0, $pos);
        }

        $url = ltrim($url, '/');
        if (!empty($url)) {
            $url = filter_var($url, FILTER_SANITIZE_URL);
            return explode('/', $url);
        }
        return [];
    }
}
