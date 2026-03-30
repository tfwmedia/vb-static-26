<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$meta = meta([
    'title' => 'Weihnachtsmärchen 2026 | Die Bremer Stadtmusikanten',
    'description' => 'Die Bremer Stadtmusikanten: Weihnachtsmärchen 2026 der Volksbühne Worms am 01.12. und 02.12.2026 in Das Wormser.',
    'canonical' => site_url('/weihnachtsmaerchen/'),
    'og_type' => 'article',
    'og_image' => '/img/bsm.jpeg',
]);
$activeNav = 'maerchen';
$bodyClass = 'page-maerchen';

$structuredData = [
    '@context' => 'https://schema.org',
    '@graph' => [
        [
            '@type' => 'PerformingGroup',
            'name' => $siteConfig['name'],
            'url' => site_url('/'),
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
                    'addressLocality' => 'Worms',
                    'addressCountry' => 'DE',
                ],
            ],
            'organizer' => [
                '@type' => 'Organization',
                'name' => $siteConfig['name'],
                'url' => site_url('/'),
            ],
            'offers' => [
                '@type' => 'AggregateOffer',
                'lowPrice' => '12.00',
                'highPrice' => '14.00',
                'priceCurrency' => 'EUR',
                'url' => site_url('/weihnachtsmaerchen/'),
                'availability' => 'https://schema.org/InStock',
            ],
            'subEvent' => [
                [
                    '@type' => 'Event',
                    'name' => 'Die Bremer Stadtmusikanten',
                    'startDate' => '2026-12-01T18:00:00+01:00',
                    'location' => [
                        '@type' => 'Place',
                        'name' => 'Das Wormser',
                    ],
                    'eventStatus' => 'https://schema.org/EventScheduled',
                ],
                [
                    '@type' => 'Event',
                    'name' => 'Die Bremer Stadtmusikanten',
                    'startDate' => '2026-12-02T18:00:00+01:00',
                    'location' => [
                        '@type' => 'Place',
                        'name' => 'Das Wormser',
                    ],
                    'eventStatus' => 'https://schema.org/EventScheduled',
                ],
            ],
        ],
    ],
];

require __DIR__ . '/partials/layout-start.php';
?>
<main id="main-content">
    <section class="hero hero-inner">
        <div class="container hero-grid">
            <div class="hero-copy">
                <p class="eyebrow">Weihnachtsmärchen 2026</p>
                <h1>Die Bremer Stadtmusikanten</h1>
                <p class="lead">
                    Ein Märchen der Brüder Grimm.
                    In einer Inszenierung von Peter Schmitt.
                </p>
                <div class="actions">
                    <a class="btn btn-primary" href="mailto:<?= e($siteConfig['maerchen_contact_email']) ?>">Kontakt für Gruppen</a>
                    <a class="btn btn-secondary" href="tel:<?= e($siteConfig['maerchen_contact_phone_href']) ?>">Anrufen: <?= e($siteConfig['maerchen_contact_phone_display']) ?></a>
                </div>
            </div>
            <aside class="event-meta" aria-label="Veranstaltungsdetails">
                <img class="maerchen-poster" src="<?= e(asset('/img/bsm.jpeg')) ?>" width="1024" height="1536" alt="Titelmotiv des Weihnachtsmärchens 2026: Die Bremer Stadtmusikanten" loading="lazy" decoding="async">
                <h2>Vorstellungen 2026</h2>
                <ul>
                    <li><strong>Dienstag:</strong> 01.12.2026 · 18:00 Uhr · Das Wormser</li>
                    <li><strong>Mittwoch:</strong> 02.12.2026 · 18:00 Uhr · Das Wormser</li>
                    <li><strong>Vorverkauf:</strong> 12,00 €</li>
                    <li><strong>Abendkasse:</strong> 14,00 €</li>
                </ul>
            </aside>
        </div>
    </section>

    <section class="section">
        <div class="container prose" data-reveal>
            <h2>Inhalt</h2>
            <p>
                In dem Märchen „Die Bremer Stadtmusikanten“ schließen sich ein alter Esel, ein Hund,
                eine Katze und ein Hahn zusammen, nachdem ihre Besitzer sie loswerden wollen.
                Gemeinsam machen sie sich auf den Weg nach Bremen, um dort Stadtmusikanten zu werden.
            </p>
            <p>
                Unterwegs vertreiben sie mit Mut und Zusammenhalt eine Räuberbande aus einem Haus im Wald
                und finden dort schließlich ein neues Zuhause – und eine starke Freundschaft.
            </p>
            <p>
                Die Volksbühne Worms 1908 e. V. freut sich, in der Vorweihnachtszeit wieder ein
                Märchen für Groß und Klein auf die Bühne zu bringen.
            </p>
        </div>
    </section>

    <section class="section section-contact">
        <div class="container contact-card" data-reveal>
            <div>
                <p class="eyebrow">Sie möchten mit einer Gruppe kommen?</p>
                <h2>Wir helfen gerne weiter.</h2>
                <p>Kontakt für Kindergärten und Schulklassen: <?= e($siteConfig['maerchen_contact_email']) ?>, <?= e($siteConfig['maerchen_contact_phone_display']) ?>.</p>
            </div>
            <div class="actions">
                <a class="btn btn-primary" href="mailto:<?= e($siteConfig['maerchen_contact_email']) ?>">E-Mail senden</a>
                <a class="btn btn-tertiary" href="tel:<?= e($siteConfig['maerchen_contact_phone_href']) ?>">Anrufen</a>
            </div>
        </div>
    </section>
</main>
<?php require __DIR__ . '/partials/layout-end.php'; ?>
