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

/**
 * Returns 'true'/'false' suitable for the aria-current attribute, based
 * on whether the given navigation key matches the currently active one.
 */
function nav_is_active(string $activeNav, string $key): bool
{
    return $activeNav === $key;
}

/**
 * Returns 'page' for the active nav item, or an empty string.
 * Safe to drop into aria-current="...".
 */
function nav_aria_current(string $activeNav, string $key): string
{
    return nav_is_active($activeNav, $key) ? 'page' : '';
}

/**
 * Renders the HTML class attribute string fragment for the active
 * navigation entry.
 */
function nav_active_class(string $activeNav, string $key, string $base = ''): string
{
    if (!nav_is_active($activeNav, $key)) {
        return $base === '' ? '' : ' ' . $base;
    }
    return $base === '' ? 'is-active' : $base . ' is-active';
}

/**
 * Returns the Gravatar-friendly initials for a person, used as avatar
 * placeholder when no portrait photo is supplied.
 */
function person_initials(string $name): string
{
    $parts = preg_split('/\s+/u', trim($name)) ?: [];
    $initials = '';
    $useMb = function_exists('mb_substr') && function_exists('mb_strtoupper');
    foreach ($parts as $part) {
        if ($part === '') continue;
        $first = $useMb ? mb_substr($part, 0, 1) : substr($part, 0, 1);
        if ($first !== '') $initials .= $first;
        $len = $useMb ? mb_strlen($initials) : strlen($initials);
        if ($len >= 2) break;
    }
    return $useMb ? mb_strtoupper($initials) : strtoupper($initials);
}

/**
 * Renders an array of breadcrumbs as a <nav><ol> structure.
 *
 * @param array<int, array{label:string, href?:string|null> $items
 */
function render_breadcrumb(array $items): string
{
    if (count($items) === 0) return '';
    $html = '<nav class="breadcrumb-nav" aria-label="Brotkrumen">';
    $html .= '<ol class="breadcrumb">';
    $last = count($items) - 1;
    foreach ($items as $i => $item) {
        $label = e($item['label']);
        $href = $item['href'] ?? null;
        if ($i === $last || $href === null) {
            $html .= '<li><span aria-current="page">' . $label . '</span></li>';
        } else {
            $html .= '<li><a href="' . e(site_url($href)) . '">' . $label . '</a></li>';
        }
    }
    $html .= '</ol></nav>';
    return $html;
}

/**
 * Pretty-prints a date in de-DE format (e.g. "01. Dezember 2026").
 */
function format_date_de(string $isoDate, bool $withWeekday = true): string
{
    $ts = strtotime($isoDate);
    if ($ts === false) return $isoDate;
    $fmt = $withWeekday ? 'l, d. F Y' : 'd. F Y';
    return date($fmt, $ts);
}

/**
 * Truncates a string to a max width without breaking multibyte chars.
 * Falls back to byte-substr when the mbstring extension is unavailable.
 */
function truncate_text(string $text, int $max, string $suffix = '…'): string
{
    if ($max <= 0) return '';
    if (function_exists('mb_strimwidth')) {
        $out = mb_strimwidth($text, 0, $max, $suffix);
        if ($out !== false) return $out;
    }
    if (strlen($text) <= $max) return $text;
    return substr($text, 0, max(0, $max - strlen($suffix))) . $suffix;
}

/**
 * Returns the German short day name for an ISO date (e.g. "Di").
 */
function weekday_short_de(string $isoDate): string
{
    $ts = strtotime($isoDate);
    if ($ts === false) return '';
    // Manual mapping for stable German short labels independent of locale.
    static $map = ['So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa'];
    return $map[(int) date('w', $ts)];
}

/**
 * Returns the German short month name for an ISO date (e.g. "Dez").
 */
function month_short_de(string $isoDate): string
{
    $ts = strtotime($isoDate);
    if ($ts === false) return '';
    static $map = [
        'Jan', 'Feb', 'Mär', 'Apr', 'Mai', 'Jun',
        'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dez',
    ];
    return $map[(int) date('n', $ts) - 1];
}

/**
 * German weekday full name (e.g. "Dienstag").
 */
function weekday_full_de(string $isoDate): string
{
    $ts = strtotime($isoDate);
    if ($ts === false) return '';
    static $map = [
        'Sonntag', 'Montag', 'Dienstag', 'Mittwoch',
        'Donnerstag', 'Freitag', 'Samstag',
    ];
    return $map[(int) date('w', $ts)];
}

/**
 * Renders a <time> element with a German-formatted visible text.
 * Pass a callable or string for the format. Default: short "01. Dez".
 */
function render_date_badge(string $isoDate, string $time = ''): string
{
    $day = date('d', strtotime($isoDate));
    $month = month_short_de($isoDate);
    $html = '<span class="event-card__date-badge" aria-hidden="true">';
    $html .= '<span class="day">' . e($day) . '</span>';
    $html .= '<span class="month">' . e($month) . '</span>';
    $html .= '</span>';
    if ($time !== '') {
        $html .= '<span class="visually-hidden">' . e(weekday_full_de($isoDate)) . ', ' . e($day) . '. ' . e($month) . ' ' . date('Y', strtotime($isoDate)) . ', ' . e($time) . ' Uhr</span>';
    }
    return $html;
}

/**
 * Renders a chip for an event status.
 *
 * @param 'tickets'|'few_left'|'premiere'|'sold_out'|'archive' $status
 */
function render_status_chip(string $status): string
{
    switch ($status) {
        case 'sold_out':
            return '<span class="chip chip--warning"><span class="chip__dot" aria-hidden="true"></span>Ausverkauft</span>';
        case 'few_left':
            return '<span class="chip chip--warning"><span class="chip__dot" aria-hidden="true"></span>Nur noch wenige Plätze</span>';
        case 'premiere':
            return '<span class="chip chip--brand">Premiere</span>';
        case 'archive':
            return '<span class="chip">Archiv</span>';
        case 'tickets':
        default:
            return '<span class="chip chip--success"><span class="chip__dot" aria-hidden="true"></span>Tickets verfügbar</span>';
    }
}

/**
 * Maps an event status to a Schema.org EventStatus URL.
 */
function event_status_url(string $status): string
{
    switch ($status) {
        case 'sold_out':
            return 'https://schema.org/EventCancelled';
        case 'archive':
            return 'https://schema.org/EventScheduled';
        case 'few_left':
        case 'premiere':
        case 'tickets':
        default:
            return 'https://schema.org/EventScheduled';
    }
}

/**
 * Builds an absolute ISO 8601 date+time in Europe/Berlin for a date and time.
 */
function iso_local(string $date, string $time = '18:00'): string
{
    $ts = strtotime($date . ' ' . $time);
    if ($ts === false) return $date . 'T' . $time . ':00+01:00';
    // Choose CET (+01:00) for winter, CEST (+02:00) for summer.
    $offset = (int) date('I', $ts) === 1 ? '+02:00' : '+01:00';
    return date('Y-m-d\TH:i:s', $ts) . $offset;
}
