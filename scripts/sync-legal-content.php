<?php
declare(strict_types=1);

/**
 * Syncs legal content blocks from the current live pages into local HTML fragments.
 *
 * Usage:
 *   php scripts/sync-legal-content.php
 */

$sources = [
    'impressum' => 'https://volksbuehne-worms.de/impressum/',
    'datenschutz' => 'https://volksbuehne-worms.de/datenschutz/',
];

$targetDir = dirname(__DIR__) . '/content/legal';
if (!is_dir($targetDir) && !mkdir($targetDir, 0775, true) && !is_dir($targetDir)) {
    fwrite(STDERR, "Could not create directory: {$targetDir}\n");
    exit(1);
}

libxml_use_internal_errors(true);

foreach ($sources as $slug => $url) {
    $cachedSource = '/tmp/vb-' . $slug . '.html';
    $html = is_file($cachedSource) ? @file_get_contents($cachedSource) : false;
    if (!is_string($html) || trim($html) === '') {
        $html = @file_get_contents($url);
    }
    if (!is_string($html) || trim($html) === '') {
        $html = shell_exec('wget -qO- ' . escapeshellarg($url));
    }
    if (!is_string($html) || trim($html) === '') {
        fwrite(STDERR, "Failed to download {$url}\n");
        exit(1);
    }

    $dom = new DOMDocument();
    $dom->loadHTML($html);
    $xpath = new DOMXPath($dom);

    $container = $xpath->query(
        "(//div[contains(concat(' ', normalize-space(@class), ' '), ' elementor-widget-text-editor ')]" .
        "//div[contains(concat(' ', normalize-space(@class), ' '), ' elementor-widget-container ')])[1]"
    )->item(0);

    if (!$container instanceof DOMElement) {
        fwrite(STDERR, "Content container not found for {$url}\n");
        exit(1);
    }

    $fragment = '';
    foreach ($container->childNodes as $childNode) {
        $fragment .= $dom->saveHTML($childNode);
    }

    // Keep page hero heading only once in our template.
    $fragment = preg_replace('/^\s*<h1[^>]*>.*?<\/h1>/is', '', $fragment, 1) ?? $fragment;

    // Convert internal absolute links to relative links.
    $fragment = preg_replace_callback(
        '/\b(href|src)=("|\')https?:\/\/(?:www\.)?volksbuehne-worms\.de([^"\']*)\2/i',
        static function (array $matches): string {
            $attr = $matches[1];
            $quote = $matches[2];
            $path = trim($matches[3]);
            $path = $path === '' ? '/' : $path;
            if ($path[0] !== '/') {
                $path = '/' . $path;
            }
            return $attr . '=' . $quote . $path . $quote;
        },
        $fragment
    ) ?? $fragment;

    $targetFile = $targetDir . '/' . $slug . '.html';
    file_put_contents($targetFile, trim($fragment) . PHP_EOL);
    echo "Synced {$slug} -> {$targetFile}\n";
}
