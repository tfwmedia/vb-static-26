<?php
declare(strict_types=1);

$requestUri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$projectRoot = dirname(__DIR__);
$publicPath = $projectRoot . $requestUri;

// Let PHP's built-in server handle existing static files directly.
if ($requestUri !== '/' && is_file($publicPath)) {
    return false;
}

$route = rtrim($requestUri, '/');
if ($route === '') {
    $route = '/';
}

// External redirect: ticket purchase is handled by ticket-regional.de.
if ($route === '/tickets-kaufen') {
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: https://www.ticket-regional.de/events.php?mysearchSpecificType=eventtype&mysearchSpecificID=1883');
    return true;
}

$routeMap = [
    '/' => 'index.php',
    '/weihnachtsmaerchen' => 'weihnachtsmaerchen.php',
    '/impressum' => 'impressum.php',
    '/datenschutz' => 'datenschutz.php',
];

if (isset($routeMap[$route])) {
    require $projectRoot . '/' . $routeMap[$route];
    return true;
}

// Keep direct access for known PHP files if requested.
if (str_ends_with($requestUri, '.php')) {
    $phpFile = $projectRoot . '/' . ltrim($requestUri, '/');
    if (is_file($phpFile)) {
        require $phpFile;
        return true;
    }
}

http_response_code(404);
header('Content-Type: text/plain; charset=UTF-8');
echo "404 Not Found\n";
return true;
