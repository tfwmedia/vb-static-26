<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$yearsActive = max(0, (int) date('Y') - 1908);
$meta = meta([
    'title' => 'Volksbühne Worms 1908 e. V. | Theater seit 1908',
    'description' => 'Semiprofessionelles Theater in Worms: Spielzeit, Weihnachtsmärchen, Kulturarbeit und Engagement seit 1908.',
    'canonical' => site_url('/'),
]);
$activeNav = 'home';
$bodyClass = 'page-home';

$structuredData = [
    '@context' => 'https://schema.org',
    '@graph' => [
        [
            '@type' => 'PerformingGroup',
            'name' => $siteConfig['name'],
            'url' => site_url('/'),
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
            '@type' => 'WebSite',
            'name' => $siteConfig['name'],
            'url' => site_url('/'),
            'inLanguage' => 'de-DE',
        ],
    ],
];

require __DIR__ . '/partials/layout-start.php';
?>
<main id="main-content">
    <section class="hero">
        <div class="container hero-grid">
            <div class="hero-copy">
                <p class="eyebrow">Theater seit 1908</p>
                <h1>Wir sind Theater.<br>Mit Haltung, Herz und Bühne.</h1>
                <p class="lead">
                    Die Volksbühne Worms begeistert Generationen mit einfallsreichem, mitreißendem Theater.
                    Von Saisonstücken bis Weihnachtsmärchen: Kultur lebt hier nahbar und handgemacht.
                </p>
                <div class="actions">
                    <a class="btn btn-primary" href="<?= e(site_url('/weihnachtsmaerchen/')) ?>">Zum Weihnachtsmärchen</a>
                    <a class="btn btn-secondary" href="<?= e($siteConfig['ticket_url']) ?>" target="_blank" rel="noopener noreferrer">Tickets kaufen</a>
                </div>
            </div>
            <div class="hero-visual">
                <img src="<?= e(asset('/assets/img/vorhang.jpg')) ?>" width="1132" height="635" alt="Bühnenvorhang der Volksbühne Worms" fetchpriority="high">
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="section-head">
                <p class="eyebrow">Auf einen Blick</p>
                <h2>Was uns ausmacht</h2>
            </div>
            <div class="card-grid">
                <article class="card" data-reveal>
                    <h3>Historie & Zukunft</h3>
                    <p>
                        Seit über <?= e((string) $yearsActive) ?> Jahren gestalten wir in Worms lebendige Theaterkultur
                        und entwickeln unser Programm kontinuierlich weiter.
                    </p>
                </article>
                <article class="card" data-reveal>
                    <h3>Weihnachtsmärchen</h3>
                    <p>
                        Ein fester Termin im Jahreskalender für Familien, Schulen und Kitas:
                        atmosphärisch inszeniert und generationsübergreifend.
                    </p>
                </article>
                <article class="card" data-reveal>
                    <h3>Gemeinschaft</h3>
                    <p>
                        Wir freuen uns immer über neue Mitglieder. Ob auf, vor oder hinter der Bühne:
                        Engagement ist bei uns willkommen.
                    </p>
                </article>
            </div>
        </div>
    </section>

    <section class="section section-highlight" aria-labelledby="spielzeit-highlight">
        <div class="container split">
            <div class="split-media">
                <img src="<?= e(asset('/assets/img/weihnachtsmaerchen-2025.jpeg')) ?>" width="640" height="362" alt="Titelbild des Weihnachtsmärchens 2025: Die Schöne und das Biest" loading="lazy" decoding="async">
            </div>
            <div class="split-copy" data-reveal>
                <p class="eyebrow">Nicht verpassen</p>
                <h2 id="spielzeit-highlight">Das Weihnachtsmärchen 2025</h2>
                <p class="lead-compact">
                    In diesem Jahr erwartet Sie „Die Schöne und das Biest“ nach dem französischen Volksmärchen,
                    liebevoll inszeniert von Peter Schmitt.
                </p>
                <dl class="facts">
                    <div>
                        <dt>Freiverkaufsvorstellung</dt>
                        <dd>Mittwoch, 03.12.2025 · 18:00 Uhr</dd>
                    </div>
                    <div>
                        <dt>Ort</dt>
                        <dd>Das Wormser</dd>
                    </div>
                    <div>
                        <dt>Eintritt</dt>
                        <dd>Vorverkauf 10,00 € · Abendkasse 12,00 €</dd>
                    </div>
                </dl>
                <div class="actions">
                    <a class="btn btn-primary" href="<?= e(site_url('/weihnachtsmaerchen/')) ?>">Mehr Infos</a>
                    <a class="btn btn-secondary" href="<?= e($siteConfig['ticket_url']) ?>" target="_blank" rel="noopener noreferrer">Tickets sichern</a>
                </div>
            </div>
        </div>
    </section>

    <section class="section section-contact" id="kontakt">
        <div class="container contact-card" data-reveal>
            <div>
                <p class="eyebrow">Fragen oder Interesse?</p>
                <h2>Schreiben Sie uns.</h2>
                <p>Wir beantworten Anfragen zu Stücken, Mitgliedschaft und Vorstellungen schnell und persönlich.</p>
            </div>
            <div class="actions">
                <a class="btn btn-primary" href="mailto:<?= e($siteConfig['email']) ?>">E-Mail senden</a>
                <a class="btn btn-tertiary" href="tel:<?= e($siteConfig['phone_href']) ?>">Anrufen: <?= e($siteConfig['phone_display']) ?></a>
            </div>
        </div>
    </section>
</main>
<?php require __DIR__ . '/partials/layout-end.php'; ?>
