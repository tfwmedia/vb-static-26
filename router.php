<?php
$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

// Static SEO files: served as-is (Apache also has the .htaccess rule).
if (preg_match('/^\/(sitemap\.xml|robots\.txt)$/', $path)) {
    return false;
}

if (preg_match('/^\/spielzeit(\/)?$/', $path)) {
    $_SERVER["SCRIPT_NAME"] = '/spielzeit.php';
    require 'spielzeit.php';
} elseif (preg_match('/^\/verein(\/)?$/', $path)) {
    $_SERVER["SCRIPT_NAME"] = '/verein.php';
    require 'verein.php';
} elseif (preg_match('/^\/kontakt(\/)?$/', $path)) {
    $_SERVER["SCRIPT_NAME"] = '/kontakt.php';
    require 'kontakt.php';
} elseif (preg_match('/^\/weihnachtsmaerchen(\/)?$/', $path)) {
    $_SERVER["SCRIPT_NAME"] = '/weihnachtsmaerchen.php';
    require 'weihnachtsmaerchen.php';
} elseif (preg_match('/^\/impressum(\/)?$/', $path)) {
    $_SERVER["SCRIPT_NAME"] = '/impressum.php';
    require 'impressum.php';
} elseif (preg_match('/^\/datenschutz(\/)?$/', $path)) {
    $_SERVER["SCRIPT_NAME"] = '/datenschutz.php';
    require 'datenschutz.php';
} elseif (preg_match('/^\/tickets-kaufen(\/)?$/', $path)) {
    $_SERVER["SCRIPT_NAME"] = '/tickets-kaufen.php';
    require 'tickets-kaufen.php';
} elseif ($path === '/' || $path === '/index.php') {
    $_SERVER["SCRIPT_NAME"] = '/index.php';
    require 'index.php';
} elseif (file_exists(__DIR__ . $path)) {
    return false; // serve the requested resource as-is.
} else {
    http_response_code(404);
    require '404.php';
}