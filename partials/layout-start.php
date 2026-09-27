<?php
declare(strict_types=1);

/** @var array<string, mixed> $meta */
/** @var string $activeNav */
/** @var array<string, mixed>|null $structuredData */
/** @var string|null $bodyClass */
/** @var string|null $ogImage */
/** @var array<int, array<string, string>> $breadcrumbs */
?>
<!doctype html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($meta['title']) ?></title>
    <meta name="description" content="<?= e($meta['description']) ?>">
    <meta name="theme-color" content="<?= e($meta['theme_color'] ?? '#c31e2e') ?>">
    <meta name="color-scheme" content="light">
    <meta name="robots" content="<?= e($meta['robots'] ?? 'index, follow, max-image-preview:large, max-snippet:-1') ?>">
    <link rel="canonical" href="<?= e($meta['canonical']) ?>">
    <link rel="alternate" hreflang="de" href="<?= e($meta['canonical']) ?>">
    <link rel="alternate" hreflang="x-default" href="<?= e($meta['canonical']) ?>">

    <meta property="og:site_name" content="<?= e($siteConfig['name']) ?>">
    <meta property="og:title" content="<?= e($meta['title']) ?>">
    <meta property="og:description" content="<?= e($meta['description']) ?>">
    <meta property="og:type" content="<?= e($meta['og_type']) ?>">
    <meta property="og:url" content="<?= e($meta['canonical']) ?>">
    <meta property="og:image" content="<?= e($meta['og_image']) ?>">
    <meta property="og:image:width" content="<?= e($meta['og_image_width'] ?? '1200') ?>">
    <meta property="og:image:height" content="<?= e($meta['og_image_height'] ?? '630') ?>">
    <meta property="og:image:alt" content="<?= e($meta['og_image_alt'] ?? $siteConfig['name']) ?>">
    <meta property="og:locale" content="<?= e($meta['og_locale']) ?>">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= e($meta['title']) ?>">
    <meta name="twitter:description" content="<?= e($meta['description']) ?>">
    <meta name="twitter:image" content="<?= e($meta['og_image']) ?>">
    <meta name="twitter:image:alt" content="<?= e($meta['og_image_alt'] ?? $siteConfig['name']) ?>">

    <link rel="icon" type="image/png" href="<?= e(asset('/assets/img/vb-icon.png')) ?>" sizes="32x32">
    <link rel="apple-touch-icon" href="<?= e(asset('/assets/img/vb-logo.png')) ?>">

    <link rel="preload" href="<?= e(asset('/assets/img/bsm.webp')) ?>" as="image" fetchpriority="high">
    <link rel="preload" href="<?= e(asset('/assets/css/main.min.css')) ?>" as="style">
    <link rel="stylesheet" href="<?= e(asset('/assets/css/main.min.css')) ?>">
    <script src="<?= e(asset('/assets/js/main.min.js')) ?>" defer></script>

    <?php if (!empty($structuredData)) : ?>
        <script type="application/ld+json"><?= json_ld($structuredData) ?></script>
    <?php endif; ?>
</head>
<body class="<?= e($bodyClass ?? '') ?><?= !empty($breadcrumbs) ? ' has-breadcrumb' : '' ?>">
<a class="skip-link" href="#main-content">Direkt zum Inhalt</a>
<header class="site-header" data-header>
    <div class="header-shell">
        <a class="brand" href="<?= e(site_url('/')) ?>" aria-label="Startseite Volksbühne Worms">
            <img class="brand-mark" src="<?= e(asset('/assets/img/vb-logo.png')) ?>" width="432" height="324" alt="Logo der Volksbühne Worms" loading="eager" decoding="async">
            <span class="brand-text">
                <strong><?= e($siteConfig['short_name']) ?></strong>
                <span><?= e($siteConfig['tagline']) ?></span>
            </span>
        </a>

        <nav class="site-nav-desktop" aria-label="Hauptnavigation">
            <ul>
                <?php foreach ($navigation as $item) : ?>
                    <?php $isActive = nav_is_active($activeNav, $item['key']); ?>
                    <li>
                        <a
                            href="<?= e(site_url($item['href'])) ?>"
                            <?= $isActive ? 'aria-current="page"' : '' ?>
                        ><?= e($item['label']) ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>

        <div class="header-controls">
            <a class="header-cta header-cta--header btn btn-primary btn-sm" href="<?= e($siteConfig['spielzeit_ticket_url']) ?>" rel="noopener">
                Tickets kaufen
            </a>
            <button class="nav-toggle" type="button" data-nav-toggle aria-expanded="false" aria-controls="primary-navigation" aria-label="Menü öffnen">
                <svg class="icon-open" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true">
                    <line x1="4" y1="7" x2="20" y2="7"/>
                    <line x1="4" y1="12" x2="20" y2="12"/>
                    <line x1="4" y1="17" x2="20" y2="17"/>
                </svg>
                <svg class="icon-close" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true">
                    <line x1="6" y1="6" x2="18" y2="18"/>
                    <line x1="18" y1="6" x2="6" y2="18"/>
                </svg>
            </button>
        </div>
    </div>

    <dialog class="site-nav-dialog" id="primary-navigation" data-nav-dialog aria-label="Hauptnavigation mobil">
        <div class="site-nav-dialog__inner">
            <div class="site-nav-dialog__head">
                <span class="site-nav-dialog__title">Menü</span>
                <button class="nav-toggle" type="button" data-nav-dialog-close aria-label="Menü schließen">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" width="22" height="22">
                        <line x1="6" y1="6" x2="18" y2="18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <line x1="18" y1="6" x2="6" y2="18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </button>
            </div>
            <ul>
                <?php foreach ($navigation as $item) : ?>
                    <?php $isActive = nav_is_active($activeNav, $item['key']); ?>
                    <li>
                        <a
                            href="<?= e(site_url($item['href'])) ?>"
                            <?= $isActive ? 'aria-current="page"' : '' ?>
                        ><?= e($item['label']) ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="site-nav-dialog__footer">
                <a class="btn btn-primary" href="<?= e($siteConfig['spielzeit_ticket_url']) ?>" rel="noopener">Tickets kaufen</a>
                <a class="btn btn-secondary" href="mailto:<?= e($siteConfig['email']) ?>">Kontakt</a>
            </div>
        </div>
    </dialog>
</header>

<?php if (!empty($breadcrumbs)) : ?>
    <div class="container breadcrumb-wrap" style="padding-top: var(--space-md);">
        <?= render_breadcrumb($breadcrumbs) ?>
    </div>
<?php endif; ?>