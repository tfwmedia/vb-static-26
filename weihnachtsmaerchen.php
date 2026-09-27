<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$meta = meta([
    'title' => 'Weihnachtsmärchen 2026 | Die Bremer Stadtmusikanten',
    'description' => 'Die Bremer Stadtmusikanten: Weihnachtsmärchen 2026 der Volksbühne Worms am 01.12. und 02.12.2026 in Das Wormser.',
    'canonical' => site_url('/weihnachtsmaerchen/'),
    'og_type' => 'article',
    'og_image' => asset_url('/assets/img/og-maerchen.svg'),
    'og_image_alt' => 'Weihnachtsmärchen 2026 — Die Bremer Stadtmusikanten',
]);
$activeNav = 'maerchen';
$bodyClass = 'page-maerchen';
$breadcrumbs = [
    ['label' => 'Start', 'href' => '/'],
    ['label' => 'Spielzeit', 'href' => '/spielzeit/'],
    ['label' => 'Weihnachtsmärchen 2026'],
];

$structuredData = [
    '@context' => 'https://schema.org',
    '@graph' => [
        [
            '@type' => 'PerformingGroup',
            'name' => $siteConfig['name'],
            'url' => absolute_url('/'),
            'additionalType' => 'LocalBusiness',
            'email' => $siteConfig['email'],
            'telephone' => $siteConfig['phone_display'],
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => $siteConfig['address']['street'],
                'postalCode' => '67549',
                'addressLocality' => 'Worms',
                'addressCountry' => 'DE',
            ],
        ],
        [
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Start', 'item' => absolute_url('/')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Spielzeit', 'item' => absolute_url('/spielzeit/')],
                ['@type' => 'ListItem', 'position' => 3, 'name' => 'Weihnachtsmärchen 2026', 'item' => $meta['canonical']],
            ],
        ],
        [
            '@type' => 'EventSeries',
            'name' => 'Weihnachtsmärchen 2026: Die Bremer Stadtmusikanten',
            'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
            'location' => [
                '@type' => 'Place',
                'name' => 'Das Wormser',
                'address' => [
                    '@type' => 'PostalAddress',
                    'streetAddress' => 'Rathenaustraße 11',
                    'postalCode' => '67549',
                    'addressLocality' => 'Worms',
                    'addressCountry' => 'DE',
                ],
            ],
            'organizer' => [
                '@type' => 'Organization',
                'name' => $siteConfig['name'],
                'url' => absolute_url('/'),
            ],
            'offers' => [
                '@type' => 'AggregateOffer',
                'lowPrice' => '12.00',
                'highPrice' => '14.00',
                'priceCurrency' => 'EUR',
                'url' => $siteConfig['maerchen_ticket_url'],
                'availability' => 'https://schema.org/InStock',
                'validFrom' => '2026-08-01T00:00:00+02:00',
            ],
            'subEvent' => [
                [
                    '@type' => 'Event',
                    'name' => 'Die Bremer Stadtmusikanten — Vorstellung',
                    'startDate' => '2026-12-01T18:00:00+01:00',
                    'endDate' => '2026-12-01T20:30:00+01:00',
                    'location' => [
                        '@type' => 'Place',
                        'name' => 'Das Wormser',
                        'address' => [
                            '@type' => 'PostalAddress',
                            'streetAddress' => 'Rathenaustraße 11',
                            'postalCode' => '67549',
                            'addressLocality' => 'Worms',
                            'addressCountry' => 'DE',
                        ],
                    ],
                    'eventStatus' => 'https://schema.org/EventScheduled',
                    'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
                    'offers' => [
                        '@type' => 'Offer',
                        'url' => $siteConfig['maerchen_ticket_url'],
                        'price' => '12.00',
                        'priceCurrency' => 'EUR',
                        'availability' => 'https://schema.org/InStock',
                    ],
                    'performer' => [
                        '@type' => 'PerformingGroup',
                        'name' => $siteConfig['name'],
                    ],
                ],
                [
                    '@type' => 'Event',
                    'name' => 'Die Bremer Stadtmusikanten — Vorstellung',
                    'startDate' => '2026-12-02T18:00:00+01:00',
                    'endDate' => '2026-12-02T20:30:00+01:00',
                    'location' => [
                        '@type' => 'Place',
                        'name' => 'Das Wormser',
                        'address' => [
                            '@type' => 'PostalAddress',
                            'streetAddress' => 'Rathenaustraße 11',
                            'postalCode' => '67549',
                            'addressLocality' => 'Worms',
                            'addressCountry' => 'DE',
                        ],
                    ],
                    'eventStatus' => 'https://schema.org/EventScheduled',
                    'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
                    'offers' => [
                        '@type' => 'Offer',
                        'url' => $siteConfig['maerchen_ticket_url'],
                        'price' => '12.00',
                        'priceCurrency' => 'EUR',
                        'availability' => 'https://schema.org/InStock',
                    ],
                    'performer' => [
                        '@type' => 'PerformingGroup',
                        'name' => $siteConfig['name'],
                    ],
                ],
            ],
        ],
    ],
];

require __DIR__ . '/partials/layout-start.php';
?>
<main id="main-content">
    <section class="hero hero-inner" aria-labelledby="maerchen-h1">
        <div class="container hero-grid" data-reveal>
            <div class="hero-copy">
                <p class="eyebrow">Weihnachtsmärchen 2026</p>
                <h1 id="maerchen-h1">Die Bremer Stadtmusikanten</h1>
                <p class="lead">
                    Ein Märchen der Brüder Grimm.
                    In einer Inszenierung von Peter Schmitt.
                </p>
                <div class="actions">
                    <a class="btn btn-primary" href="<?= e($siteConfig['maerchen_ticket_url']) ?>" rel="noopener">
                        Tickets bestellen
                        <svg class="btn__icon" viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14M13 5l7 7-7 7"/>
                        </svg>
                    </a>
                    <a class="btn btn-secondary" href="mailto:<?= e($siteConfig['maerchen_contact_email']) ?>">Kontakt für Gruppen</a>
                    <a class="btn btn-tertiary" href="tel:<?= e($siteConfig['maerchen_contact_phone_href']) ?>">Anrufen: <?= e($siteConfig['maerchen_contact_phone_display']) ?></a>
                </div>
            </div>
            <aside class="event-meta" aria-label="Veranstaltungsdetails">
                <p class="event-meta__title">Vorstellungen 2026</p>
                <img class="event-meta__poster" src="<?= e(asset('/assets/img/bsm.webp')) ?>" width="1024" height="1536" alt="Titelmotiv des Weihnachtsmärchens 2026: Die Bremer Stadtmusikanten" loading="lazy" decoding="async" itemprop="image">
                <dl>
                    <div>
                        <dt>Datum</dt>
                        <dd>
                            <time datetime="2026-12-01">Dienstag, 01.12.2026</time>, 18:00 Uhr<br>
                            <time datetime="2026-12-02">Mittwoch, 02.12.2026</time>, 18:00 Uhr
                        </dd>
                    </div>
                    <div>
                        <dt>Ort</dt>
                        <dd>Das Wormser, Rathenaustraße 11, 67549 Worms</dd>
                    </div>
                    <div>
                        <dt>Vorverkauf</dt>
                        <dd><span class="event-meta__price">12,00 €<small>Vorverkaufspreis</small></span></dd>
                    </div>
                    <div>
                        <dt>Abendkasse</dt>
                        <dd><span class="event-meta__price">14,00 €<small>Tagespreis</small></span></dd>
                    </div>
                </dl>
                <div class="event-meta__cta">
                    <a class="btn btn-primary" href="<?= e($siteConfig['maerchen_ticket_url']) ?>" rel="noopener">Tickets bestellen</a>
                </div>
            </aside>
        </div>
    </section>

    <section class="section">
        <div class="container prose" data-reveal>
            <h2>Inhalt</h2>
            <p>
                In dem Märchen "Die Bremer Stadtmusikanten" schließen sich ein alter Esel, ein Hund,
                eine Katze und ein Hahn zusammen, nachdem ihre Besitzer sie loswerden wollen.
                Gemeinsam machen sie sich auf den Weg nach Bremen, um dort Stadtmusikanten zu werden.
            </p>
            <p>
                Unterwegs vertreiben sie mit Mut und Zusammenhalt eine Räuberbande aus einem Haus im Wald
                und finden dort schließlich ein neues Zuhause - und eine starke Freundschaft.
            </p>
            <p>
                Die Volksbühne Worms 1908 e. V. freut sich, in der Vorweihnachtszeit wieder ein
                Märchen für Groß und Klein auf die Bühne zu bringen.
            </p>
        </div>
    </section>

    <section class="section section--alt" id="mitwirkende" aria-labelledby="mitwirkende-title">
        <div class="container container--narrow">
            <div class="section-head" data-reveal>
                <p class="eyebrow">Mitwirkende</p>
                <h2 id="mitwirkende-title">Wer mitwirkt</h2>
            </div>
            <dl class="facts" data-reveal>
                <div>
                    <dt>Inszenierung</dt>
                    <dd>Peter Schmitt</dd>
                </div>
                <div>
                    <dt>Vorlage</dt>
                    <dd>Brüder Grimm</dd>
                </div>
                <div>
                    <dt>Aufführungsrechte</dt>
                    <dd>Volkstheater-Verlag</dd>
                </div>
            </dl>
        </div>
    </section>

    <section class="section" id="anfahrt" aria-labelledby="anfahrt-title">
        <div class="container">
            <div class="section-head" data-reveal>
                <p class="eyebrow">Anfahrt</p>
                <h2 id="anfahrt-title">Das Wormser in Worms</h2>
                <p class="lead-compact">
                    Alle Vorstellungen des Weihnachtsmärchens finden im Kulturzentrum
                    Das Wormser statt, Rathenaustraße 11 in 67549 Worms.
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
                    <h3 class="map-card__title">Das Wormser</h3>
                    <address class="map-card__address">
                        Rathenaustraße 11<br>
                        67549 Worms
                    </address>
                    <p style="margin: 0;">
                        <a class="btn btn-secondary btn-sm" href="<?= e(site_url('/kontakt/')) ?>">
                            Kontakt &amp; Anfahrt →
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="section section--surface" aria-labelledby="kontakt-title">
        <div class="container container--narrow">
            <div class="contact-card" data-reveal>
                <div>
                    <p class="eyebrow">Sie möchten mit einer Gruppe kommen?</p>
                    <h2 id="kontakt-title">Wir helfen gerne weiter.</h2>
                    <p>Kontakt für Kindergärten und Schulklassen: <?= e($siteConfig['maerchen_contact_email']) ?>, <?= e($siteConfig['maerchen_contact_phone_display']) ?>.</p>
                </div>
                <div class="actions">
                    <a class="btn btn-primary" href="mailto:<?= e($siteConfig['maerchen_contact_email']) ?>">E-Mail senden</a>
                    <a class="btn btn-tertiary" href="tel:<?= e($siteConfig['maerchen_contact_phone_href']) ?>">Anrufen</a>
                </div>
            </div>
        </div>
    </section>
</main>
<?php require __DIR__ . '/partials/layout-end.php'; ?>