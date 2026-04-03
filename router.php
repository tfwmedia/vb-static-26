<?php
$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

if (preg_match('/^\/weihnachtsmaerchen(\/)?$/', $path)) {
    $_SERVER["SCRIPT_NAME"] = '/weihnachtsmaerchen.php';
    require 'weihnachtsmaerchen.php';
} elseif (preg_match('/^\/impressum(\/)?$/', $path)) {
    $_SERVER["SCRIPT_NAME"] = '/impressum.php';
    require 'impressum.php';
} elseif (preg_match('/^\/datenschutz(\/)?$/', $path)) {
    $_SERVER["SCRIPT_NAME"] = '/datenschutz.php';
    require 'datenschutz.php';
} elseif ($path === '/' || $path === '/index.php') {
    $_SERVER["SCRIPT_NAME"] = '/index.php';
    require 'index.php';
} elseif (file_exists(__DIR__ . $path)) {
    return false; // serve the requested resource as-is.
} else {
    http_response_code(404);
    echo "404 Not Found";
}