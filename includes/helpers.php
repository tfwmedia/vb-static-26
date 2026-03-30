<?php
declare(strict_types=1);

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function site_url(string $path = '/'): string
{
    $trimmedPath = ltrim($path, '/');

    if ($trimmedPath === '') {
        return '/';
    }

    return '/' . $trimmedPath;
}

function asset(string $path): string
{
    $normalized = '/' . ltrim($path, '/');
    $fullPath = dirname(__DIR__) . $normalized;
    $version = file_exists($fullPath) ? (string) filemtime($fullPath) : '1';

    return $normalized . '?v=' . rawurlencode($version);
}

function asset_url(string $path): string
{
    return site_url(ltrim(asset($path), '/'));
}

/**
 * @param array<string, mixed> $pageMeta
 * @return array<string, mixed>
 */
function meta(array $pageMeta): array
{
    /** @var array<string, mixed> $metaDefaults */
    global $metaDefaults;

    $merged = array_merge($metaDefaults, $pageMeta);
    $merged['og_image'] = str_starts_with((string) $merged['og_image'], 'http')
        ? $merged['og_image']
        : site_url(ltrim((string) $merged['og_image'], '/'));

    return $merged;
}

function json_ld(array $schema): string
{
    return json_encode(
        $schema,
        JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR
    );
}
