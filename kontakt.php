<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$meta = meta([
    'title' => 'Kontakt | ' . $siteConfig['name'],
    'description' => 'Kontakt und Anfahrt zur Volksbühne Worms 1908 e. V.: Anschrift, Telefon, E-Mail, Spielstätte Das Wormser.',
    'canonical' => site_url('/kontakt/'),
    'og_type' => 'website',
    'og_image' => asset_url('/assets/img/og-kontakt.svg'),
    'og_image_alt' => 'Kontakt — Volksbühne Worms',
]);
$activeNav = 'kontakt';
$bodyClass = 'page-kontakt';
$breadcrumbs = [
    ['label' => 'Start', 'href' => '/'],
    ['label' => 'Kontakt'],
];

$structuredData = [
    '@context' => 'https://schema.org',
    '@graph' => [
        [
            '@type' => 'ContactPage',
            'name' => 'Kontakt',
            'description' => $meta['description'],
            'url' => $meta['canonical'],
            'isPartOf' => ['@id' => absolute_url('/') . '#website'],
            'publisher' => ['@id' => absolute_url('/') . '#organization'],
            'mainEntity' => ['@id' => absolute_url('/') . '#organization'],
        ],
        [
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Start', 'item' => absolute_url('/')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Kontakt', 'item' => $meta['canonical']],
            ],
        ],
    ],
];

require __DIR__ . '/partials/layout-start.php';
?>
<main id="main-content">
    <section class="hero hero-inner" aria-labelledby="kontakt-h1">
        <div class="container container--narrow" data-reveal>
            <p class="eyebrow">Kontakt</p>
            <h1 id="kontakt-h1">So erreichst du uns.</h1>
            <p class="lead">
                Postanschrift des Vereins, Spielstätte für Vorstellungen und die direkten Kontaktwege
                — Telefon, E-Mail und Vorverkaufs-Partner.
            </p>
        </div>
    </section>

    <section class="section" aria-labelledby="kanael-title">
        <div class="container">
            <div class="card-grid card-grid--2" style="grid-template-columns: 1fr;">
                <article class="contact-card" data-reveal>
                    <div>
                        <p class="eyebrow">Vereinsadresse</p>
                        <h2 id="kanael-title">Volksbühne Worms 1908 e. V.</h2>
                        <address style="font-style: normal;">
                            Würdtweinstraße 11<br>
                            67549 Worms
                        </address>
                        <p style="margin-top: var(--space-md);">
                            <a href="tel:<?= e($siteConfig['phone_href']) ?>"><?= e($siteConfig['phone_display']) ?></a><br>
                            <a href="mailto:<?= e($siteConfig['email']) ?>"><?= e($siteConfig['email']) ?></a>
                        </p>
                    </div>
                    <div class="actions">
                        <a class="btn btn-primary" href="mailto:<?= e($siteConfig['email']) ?>">E-Mail senden</a>
                        <a class="btn btn-tertiary" href="tel:<?= e($siteConfig['phone_href']) ?>">Anrufen</a>
                    </div>
                </article>

                <article class="contact-card" data-reveal>
                    <div>
                        <p class="eyebrow">Märchen-Kontakt</p>
                        <h2>Gruppen, Schulen &amp; Kindergärten</h2>
                        <p>
                            Für Gruppenanfragen zum Weihnachtsmärchen:<br>
                            <a href="mailto:<?= e($siteConfig['maerchen_contact_email']) ?>"><?= e($siteConfig['maerchen_contact_email']) ?></a><br>
                            <a href="tel:<?= e($siteConfig['maerchen_contact_phone_href']) ?>"><?= e($siteConfig['maerchen_contact_phone_display']) ?></a>
                        </p>
                    </div>
                    <div class="actions">
                        <a class="btn btn-primary" href="mailto:<?= e($siteConfig['maerchen_contact_email']) ?>">E-Mail senden</a>
                        <a class="btn btn-tertiary" href="tel:<?= e($siteConfig['maerchen_contact_phone_href']) ?>">Anrufen</a>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <section class="section section--alt" aria-labelledby="spielstaette-title">
        <div class="container">
            <div class="section-head" data-reveal>
                <p class="eyebrow">Spielstätte</p>
                <h2 id="spielstaette-title">Das Wormser</h2>
                <p class="lead-compact">
                    Sämtliche Vorstellungen der aktuellen Spielzeit finden im Wormser Kulturzentrum statt.
                </p>
            </div>

            <div class="map-card" data-reveal>
                <div class="map-card__image" aria-hidden="true">
                    <img src="<?= e(asset('/assets/img/map-wormser.svg')) ?>"
                         width="800" height="450"
                         alt=""
                         loading="lazy"
                         decoding="async">
                </div>
                <div class="map-card__body">
                    <h3 class="map-card__title">Das Wormser — Kulturzentrum</h3>
                    <address class="map-card__address">
                        Rathenaustraße 11<br>
                        67549 Worms
                    </address>
                    <p style="margin: 0;">
                        <a href="<?= e($siteConfig['spielzeit_ticket_url']) ?>" rel="noopener" class="btn btn-secondary btn-sm">
                            Tickets über Ticket-Regional →
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="section section--ink" aria-labelledby="post-title">
        <div class="container container--narrow">
            <div class="section-head section-head--center" data-reveal>
                <p class="eyebrow" style="color: #fff; opacity: 0.85;">Postanschrift</p>
                <h2 id="post-title" style="color: #fff;">Postfach</h2>
            </div>
            <div class="contact-card" style="background: rgba(255,255,255,0.06); border-color: rgba(255,255,255,0.18); color: #fff;" data-reveal>
                <address style="font-style: normal; font-size: 1.125rem;">
                    Volksbühne Worms 1908 e. V.<br>
                    Postfach 2317<br>
                    67513 Worms
                </address>
            </div>
        </div>
    </section>
</main>
<?php require __DIR__ . '/partials/layout-end.php'; ?>