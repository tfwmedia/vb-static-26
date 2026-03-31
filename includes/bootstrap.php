<?php
declare(strict_types=1);

if (!headers_sent()) {
    header('Content-Type: text/html; charset=UTF-8');
}

require_once __DIR__ . '/site-config.php';
require_once __DIR__ . '/helpers.php';
