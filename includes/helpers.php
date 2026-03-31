<?php
declare(strict_types=1);

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function site_origin(): string
{
    $host = $_SERVER['HTTP_HOST'] ?? '';
    if (!is_string($host) || trim($host) === '') {
        return '';
    }

    $host = preg_replace('/[^a-z0-9\.\-:\[\]]/i', '', $host) ?? '';
    if ($host === '') {
        return '';
    }

    $https = $_SERVER['HTTPS'] ?? '';
    $forwardedProto = $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '';
    $isHttps =
        (is_string($https) && strtolower($https) !== '' && strtolower($https) !== 'off')
        || (is_string($forwardedProto) && strtolower($forwardedProto) === 'https');

    return ($isHttps ? 'https://' : 'http://') . $host;
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

function absolute_url(string $urlOrPath): string
{
    if (preg_match('~^https?://~i', $urlOrPath) === 1) {
        return $urlOrPath;
    }

    $path = site_url($urlOrPath);
    $origin = site_origin();

    return $origin === '' ? $path : $origin . $path;
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
    $merged['canonical'] = absolute_url((string) $merged['canonical']);
    $merged['og_image'] = absolute_url((string) $merged['og_image']);

    return $merged;
}

function sanitize_legal_html(string $html): string
{
    $allowedTags = [
        'p', 'br', 'strong', 'em', 'b', 'i', 'u', 'small',
        'ul', 'ol', 'li', 'a', 'h2', 'h3', 'h4', 'address',
    ];
    $blockedTags = ['script', 'style', 'iframe', 'object', 'embed', 'form', 'input', 'button'];

    $dom = new DOMDocument('1.0', 'UTF-8');
    $previousLibxmlState = libxml_use_internal_errors(true);
    $wrappedHtml =
        '<!doctype html><html><head><meta charset="UTF-8"></head><body><div id="legal-root">'
        . $html
        . '</div></body></html>';

    $dom->loadHTML(
        '<?xml encoding="UTF-8">' . $wrappedHtml,
        LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
    );
    libxml_clear_errors();
    libxml_use_internal_errors($previousLibxmlState);

    $xpath = new DOMXPath($dom);
    $root = $xpath->query('//*[@id="legal-root"]')->item(0);
    if (!$root instanceof DOMElement) {
        return '';
    }

    $nodes = $xpath->query('.//*', $root);
    if ($nodes !== false) {
        /** @var DOMElement[] $elements */
        $elements = [];
        foreach ($nodes as $node) {
            if ($node instanceof DOMElement) {
                $elements[] = $node;
            }
        }

        for ($i = count($elements) - 1; $i >= 0; $i--) {
            $el = $elements[$i];
            $tag = strtolower($el->tagName);

            if ($tag === 'h1') {
                $replacement = $dom->createElement('h2');
                while ($el->firstChild !== null) {
                    $replacement->appendChild($el->firstChild);
                }
                $el->parentNode?->replaceChild($replacement, $el);
                continue;
            }

            if (in_array($tag, $blockedTags, true)) {
                $el->parentNode?->removeChild($el);
                continue;
            }

            if (!in_array($tag, $allowedTags, true)) {
                $parent = $el->parentNode;
                if ($parent === null) {
                    continue;
                }
                while ($el->firstChild !== null) {
                    $parent->insertBefore($el->firstChild, $el);
                }
                $parent->removeChild($el);
                continue;
            }

            if ($tag === 'a') {
                $href = trim((string) $el->getAttribute('href'));
                $isAllowedHref =
                    $href === ''
                    || preg_match('~^(https?://|mailto:|tel:|/)~i', $href) === 1;

                while ($el->attributes->length > 0) {
                    $el->removeAttributeNode($el->attributes->item(0));
                }

                if ($isAllowedHref) {
                    $normalizedHref = $href === '' ? '#' : $href;
                    $el->setAttribute('href', $normalizedHref);
                    if (preg_match('~^https?://~i', $normalizedHref) === 1) {
                        $el->setAttribute('rel', 'noopener noreferrer');
                    }
                }
                continue;
            }

            while ($el->attributes->length > 0) {
                $el->removeAttributeNode($el->attributes->item(0));
            }
        }
    }

    $result = '';
    foreach ($root->childNodes as $child) {
        $result .= $dom->saveHTML($child);
    }

    return trim($result);
}

function json_ld(array $schema): string
{
    return json_encode(
        $schema,
        JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR
    );
}
