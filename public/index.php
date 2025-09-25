<?php
/**
 * Strybk_ Front Controller
 * Routes all requests through this single entry point
 */

session_start();
error_reporting(E_ALL);

// Check if installed
$configFile = __DIR__ . '/../app/config.php';
if (!file_exists($configFile)) {
    header('Location: /install/');
    exit;
}

// Load configuration
$config = require $configFile;

// Set timezone
date_default_timezone_set($config['app']['timezone']);

// Debug mode
if ($config['app']['debug']) {
    ini_set('display_errors', 1);
} else {
    ini_set('display_errors', 0);
}

// Autoloader for classes
spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . '/../app/',
        __DIR__ . '/../app/models/',
        __DIR__ . '/../app/controllers/',
    ];
    
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Load core files
require_once __DIR__ . '/../app/db.php';
require_once __DIR__ . '/../app/auth.php';
require_once __DIR__ . '/../app/helpers.php';
require_once __DIR__ . '/../app/routes.php';

// Initialize database
$db = Database::getInstance($config['database']);

// Initialize auth
$auth = new Auth($db, $config);

// Get request URI
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Remove trailing slash
$requestUri = rtrim($requestUri, '/');
if (empty($requestUri)) {
    $requestUri = '/';
}

// Initialize router
$router = new Router($db, $auth, $config);

// Handle the request
$router->handle($requestUri, $requestMethod);