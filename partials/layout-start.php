<?php
declare(strict_types=1);

/** @var array<string, mixed> $meta */
/** @var string $activeNav */
/** @var array<string, mixed>|null $structuredData */
/** @var string|null $bodyClass */
?>
<!doctype html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($meta['title']) ?></title>
    <meta name="description" content="<?= e($meta['description']) ?>">
    <meta name="theme-color" content="#c31e2e">
    <link rel="canonical" href="<?= e($meta['canonical']) ?>">

    <meta property="og:site_name" content="<?= e($siteConfig['name']) ?>">
    <meta property="og:title" content="<?= e($meta['title']) ?>">
    <meta property="og:description" content="<?= e($meta['description']) ?>">
    <meta property="og:type" content="<?= e($meta['og_type']) ?>">
    <meta property="og:url" content="<?= e($meta['canonical']) ?>">
    <meta property="og:image" content="<?= e($meta['og_image']) ?>">
    <meta property="og:locale" content="<?= e($meta['og_locale']) ?>">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= e($meta['title']) ?>">
    <meta name="twitter:description" content="<?= e($meta['description']) ?>">
    <meta name="twitter:image" content="<?= e($meta['og_image']) ?>">

    <link rel="icon" type="image/png" href="<?= e(asset('/assets/img/vb-icon.png')) ?>" sizes="32x32">
    <link rel="apple-touch-icon" href="<?= e(asset('/assets/img/vb-logo.png')) ?>">

    <link rel="preload" href="<?= e(asset('/assets/css/main.min.css')) ?>" as="style">
    <link rel="stylesheet" href="<?= e(asset('/assets/css/main.min.css')) ?>">
    <script src="<?= e(asset('/assets/js/main.min.js')) ?>" defer></script>

    <?php if (!empty($structuredData)) : ?>
        <script type="application/ld+json"><?= json_ld($structuredData) ?></script>
    <?php endif; ?>
</head>
<body class="<?= e($bodyClass ?? '') ?>">
<a class="skip-link" href="#main-content">Direkt zum Inhalt</a>
<header class="site-header" data-header>
    <div class="container header-shell">
        <a class="brand" href="<?= e(site_url('/')) ?>" aria-label="Startseite Volksbühne Worms">
            <img class="brand-mark" src="<?= e(asset('/assets/img/vb-logo.png')) ?>" width="432" height="324" alt="Volksbühne Worms Logo" loading="eager">
        </a>

        <nav id="primary-navigation" class="site-nav" aria-label="Hauptnavigation" data-nav>
            <ul>
                <?php foreach ($navigation as $item) : ?>
                    <?php $isActive = $activeNav === $item['key']; ?>
                    <li>
                        <a
                            href="<?= e(site_url($item['href'])) ?>"
                            <?= $isActive ? 'aria-current="page"' : '' ?>
                        >
                            <?= e($item['label']) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>

        <div class="header-controls">
            <button class="nav-toggle" type="button" data-nav-toggle aria-expanded="false" aria-controls="primary-navigation" aria-label="Menü öffnen">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true">
                    <line x1="4" y1="7" x2="20" y2="7"/>
                    <line x1="4" y1="12" x2="20" y2="12"/>
                    <line x1="4" y1="17" x2="20" y2="17"/>
                </svg>
            </button>

            <a class="header-cta header-cta--secondary" href="mailto:<?= e($siteConfig['email']) ?>">
                Kontakt
            </a>
            <a class="header-cta" href="<?= e(site_url('/tickets-kaufen/')) ?>">
                Tickets kaufen
            </a>
        </div>
    </div>
</header>
