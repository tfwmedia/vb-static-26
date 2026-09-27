<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

if (!headers_sent()) {
    http_response_code(404);
    header('Status: 404 Not Found');
}

$meta = meta([
    'title' => '404 — Seite nicht gefunden | ' . $siteConfig['name'],
    'description' => 'Die angeforderte Seite konnte leider nicht gefunden werden.',
    'canonical' => site_url('/'),
    'og_type' => 'website',
    'robots' => 'noindex, follow',
    'og_image' => asset_url('/assets/img/og-home.svg'),
]);
$activeNav = '';
$bodyClass = 'page-error page-404';

require __DIR__ . '/partials/layout-start.php';
?>
<main id="main-content">
    <section class="error-page">
        <div class="container">
            <div class="error-page__inner" data-reveal>
                <p class="eyebrow">Fehler 404</p>
                <h1 class="error-page__code">404</h1>
                <div class="error-page__curtain" aria-hidden="true"></div>
                <h2>Hier ist leider kein Vorhang zu ziehen.</h2>
                <p class="lead" style="margin-inline: auto;">
                    Die Seite, die du suchst, existiert nicht (mehr). Vielleicht hilft einer
                    der folgenden Wege weiter:
                </p>
                <div class="actions" style="justify-content: center; margin-top: var(--space-lg);">
                    <a class="btn btn-primary" href="<?= e(site_url('/')) ?>">Zur Startseite</a>
                    <a class="btn btn-secondary" href="<?= e(site_url('/spielzeit/')) ?>">Aktuelle Spielzeit</a>
                    <a class="btn btn-tertiary" href="<?= e(site_url('/weihnachtsmaerchen/')) ?>">Weihnachtsmärchen</a>
                </div>
            </div>
        </div>
    </section>
</main>
<?php require __DIR__ . '/partials/layout-end.php'; ?>