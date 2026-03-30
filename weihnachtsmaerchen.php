<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$meta = meta([
    'title' => 'Weihnachtsmärchen 2025 | Volksbühne Worms 1908 e. V.',
    'description' => '„Die Schöne und das Biest“ als Weihnachtsmärchen 2025 der Volksbühne Worms mit Freiverkaufsvorstellung am 03.12.2025.',
    'canonical' => site_url('/weihnachtsmaerchen/'),
    'og_type' => 'article',
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
            '@type' => 'Event',
            'name' => 'Weihnachtsmärchen 2025: Die Schöne und das Biest',
            'startDate' => '2025-12-03T18:00:00+01:00',
            'eventStatus' => 'https://schema.org/EventScheduled',
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
                'lowPrice' => '10.00',
                'highPrice' => '12.00',
                'priceCurrency' => 'EUR',
                'url' => $siteConfig['ticket_url'],
                'availability' => 'https://schema.org/InStock',
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
                <p class="eyebrow">Weihnachtsmärchen 2025</p>
                <h1>Die Schöne und das Biest</h1>
                <p class="lead">
                    Nach den Motiven des französischen Volksmärchens.
                    In einer Inszenierung von Peter Schmitt.
                </p>
                <div class="actions">
                    <a class="btn btn-primary" href="<?= e($siteConfig['ticket_url']) ?>" target="_blank" rel="noopener noreferrer">Tickets kaufen</a>
                    <a class="btn btn-secondary" href="mailto:<?= e($siteConfig['email']) ?>">Schul-/Gruppenanfrage</a>
                </div>
            </div>
            <aside class="event-meta" aria-label="Veranstaltungsdetails">
                <img src="<?= e(asset('/assets/img/weihnachtsmaerchen-2025.jpeg')) ?>" width="640" height="362" alt="Titelmotiv des Weihnachtsmärchens 2025" loading="lazy" decoding="async">
                <h2>Freiverkaufsvorstellung</h2>
                <ul>
                    <li><strong>Datum:</strong> Mittwoch, 03.12.2025</li>
                    <li><strong>Uhrzeit:</strong> 18:00 Uhr</li>
                    <li><strong>Ort:</strong> Das Wormser</li>
                    <li><strong>Vorverkauf:</strong> 10,00 €</li>
                    <li><strong>Abendkasse:</strong> 12,00 €</li>
                </ul>
            </aside>
        </div>
    </section>

    <section class="section">
        <div class="container prose" data-reveal>
            <h2>Inhalt</h2>
            <p>
                Das kluge, hübsche Mädchen Belle möchte ihren Vater retten und geht an seiner Stelle in
                Gefangenschaft zu einem abscheulichen Biest, das in einem düsteren Schloss wohnt.
                Trotz seines schrecklichen Aussehens lässt das Ungeheuer auch liebenswürdige Seiten erkennen.
            </p>
            <p>
                Verwunschene Dienstboten kümmern sich fürsorglich um Belle, die allmählich ihre Sorgen
                zu vergessen scheint. Währenddessen machen sich ihr Vater und die Dorfbewohner auf,
                um sie zu retten. Lüftet sich bis dahin der Schleier aller Geheimnisse?
            </p>
            <p>
                Die Volksbühne Worms 1908 e. V. freut sich, in der Vorweihnachtszeit ein Märchen in
                mehreren Vormittagsvorstellungen für Kindergärten und Schulen zu präsentieren.
                Die Freiverkaufsvorstellung gibt auch erwachsenen Märchenfans die Möglichkeit,
                sich verzaubern zu lassen.
            </p>
        </div>
    </section>

    <section class="section section-contact">
        <div class="container contact-card" data-reveal>
            <div>
                <p class="eyebrow">Sie möchten mit einer Gruppe kommen?</p>
                <h2>Wir helfen gerne weiter.</h2>
                <p>Für Anfragen zu Schulen, Kitas oder größeren Gruppen erreichen Sie uns direkt per E-Mail oder Telefon.</p>
            </div>
            <div class="actions">
                <a class="btn btn-primary" href="mailto:<?= e($siteConfig['email']) ?>">E-Mail senden</a>
                <a class="btn btn-tertiary" href="tel:<?= e($siteConfig['phone_href']) ?>">Anrufen</a>
            </div>
        </div>
    </section>
</main>
<?php require __DIR__ . '/partials/layout-end.php'; ?>
