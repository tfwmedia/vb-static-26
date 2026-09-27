<?php
declare(strict_types=1);

// Fallback front-controller routing:
// Some hosters route every request to index.php. In that case we still
// render the correct subpage based on REQUEST_URI.
$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
if (preg_match('~(?:^|/)(tickets-kaufen|spielzeit|verein|kontakt|weihnachtsmaerchen|impressum|datenschutz)(?:\.php)?/?$~i', $requestPath, $matches) === 1) {
    $routeMap = [
        'tickets-kaufen' => __DIR__ . '/tickets-kaufen.php',
        'spielzeit' => __DIR__ . '/spielzeit.php',
        'verein' => __DIR__ . '/verein.php',
        'kontakt' => __DIR__ . '/kontakt.php',
        'weihnachtsmaerchen' => __DIR__ . '/weihnachtsmaerchen.php',
        'impressum' => __DIR__ . '/impressum.php',
        'datenschutz' => __DIR__ . '/datenschutz.php',
    ];

    $routeKey = strtolower($matches[1]);
    require $routeMap[$routeKey];
    exit;
}

require_once __DIR__ . '/includes/bootstrap.php';

$yearsActive = max(0, (int) date('Y') - (int) $siteConfig['founding_year']);

// Determine the next upcoming event (including sold-out premieres)
// for the hero countdown. Premiere dates count down even when sold out
// — that's part of the marketing message ("the premiere is coming up").
$now = time();
$nextUpcoming = null;
foreach ($upcomingEvents as $evt) {
    $ts = strtotime($evt['date'] . ' ' . ($evt['time'] ?? '18:00'));
    if ($ts !== false && $ts >= $now) {
        $nextUpcoming = $evt;
        break;
    }
}
$countdownTarget = $nextUpcoming ? iso_local($nextUpcoming['date'], $nextUpcoming['time'] ?? '18:00') : null;

// Slice upcoming events into "nächste Vorstellungen" strip (top 6, sorted ascending).
$upcomingSorted = $upcomingEvents;
usort($upcomingSorted, function ($a, $b) {
    return strcmp($a['date'] . ' ' . ($a['time'] ?? '00:00'), $b['date'] . ' ' . ($b['time'] ?? '00:00'));
});
$upcomingStrip = array_values(array_filter($upcomingSorted, function ($e) use ($now) {
    $ts = strtotime($e['date'] . ' ' . ($e['time'] ?? '00:00'));
    return $ts !== false && $ts >= $now;
}));
$upcomingStrip = array_slice($upcomingStrip, 0, 6);

// Mitglied-werden Block: heading + lead + CTA kommen aus \$mitgliedHeading /
// \$mitgliedLead / \$mitgliedCtaLabel — siehe redaktionelle Konfiguration.
$mitgliedHeading = null;
$mitgliedLead = null;
$mitgliedCtaLabel = null;

// Section-Überschriften auf der Startseite — bleiben leer, bis die
// Redaktion konkrete Wording-Vorgaben liefert.
$upcomingHeading = null;
$upcomingLead = null;
$spielzeitHeading = null;
$spielzeitLead = null;
$vereinHeading = null;
$vereinLead = null;

// Spielzeit-Highlights (the two current productions).
$spielzeitHighlights = [
    [
        'eyebrow' => 'Saisonstück 2026',
        'title' => 'Die Löffelliste',
        'lead' => 'Ein Tag, den sich Erika ganz anders vorgestellt hat. Eine unerwartete Nachricht von ihrem Vermieter, dann Freunde, die sich in ihr Leben einmischen wollen und zu guter Letzt noch die Begegnung mit Gevatter Tod. Aber nicht mit Erika. Sie weiß sich zu helfen und hat dabei ihren Spaß. Von Gerhard Schreiner, in einer Inszenierung von Peter Schmitt & Kathrin Landmann.',
        'image' => asset('/assets/img/loeffelliste.webp'),
        'image_alt' => 'Titelbild des Saisonstücks 2026: Die Löffelliste',
        'cta_url' => $siteConfig['spielzeit_ticket_url'],
        'cta_label' => 'Tickets kaufen',
        'cta_external' => true,
        'meta' => 'Premiere 03.10.2026 · Volksbühne — kleines Theater',
    ],
    [
        'eyebrow' => 'Nicht verpassen',
        'title' => 'Weihnachtsmärchen 2026 — Die Bremer Stadtmusikanten',
        'lead' => 'Ein Märchen der Brüder Grimm, in einer Inszenierung von Peter Schmitt.',
        'image' => asset('/assets/img/bsm.webp'),
        'image_alt' => 'Titelbild Weihnachtsmärchen 2026: Die Bremer Stadtmusikanten',
        'cta_url' => site_url('/weihnachtsmaerchen/'),
        'cta_label' => 'Mehr erfahren',
        'cta_external' => false,
        'meta' => 'Premiere 01.12.2026 · Das Wormser',
    ],
];

$meta = meta([
    'title' => $siteConfig['name'] . ' — ' . $siteConfig['tagline'],
    'description' => 'Semiprofessionelles Theater in Worms seit ' . $siteConfig['founding_year'] . ': Spielzeit, Weihnachtsmärchen und Kulturarbeit — handgemacht, nahbar, mit Herzblut.',
    'canonical' => site_url('/'),
    'og_type' => 'website',
    'og_image' => asset_url('/assets/img/og-home.svg'),
    'og_image_alt' => 'Volksbühne Worms — Theater seit 1908',
]);
$activeNav = 'home';
$bodyClass = 'page-home';

// JSON-LD
$organizationSchema = [
    '@type' => 'Organization',
    '@id' => absolute_url('/') . '#organization',
    'name' => $siteConfig['name'],
    'alternateName' => 'VB Worms',
    'url' => absolute_url('/'),
    'logo' => absolute_url('/assets/img/vb-logo.png'),
    'description' => $meta['description'],
    'foundingDate' => (string) $siteConfig['founding_year'],
    'email' => $siteConfig['email'],
    'telephone' => $siteConfig['phone_display'],
    'address' => [
        '@type' => 'PostalAddress',
        'streetAddress' => $siteConfig['address']['street'],
        'postalCode' => '67549',
        'addressLocality' => 'Worms',
        'addressCountry' => $siteConfig['address']['country'],
    ],
    'member' => [
        '@type' => 'QuantitativeValue',
        'value' => (int) $siteConfig['member_count'],
        'unitText' => 'Mitglieder',
    ],
];

$websiteSchema = [
    '@type' => 'WebSite',
    '@id' => absolute_url('/') . '#website',
    'name' => $siteConfig['name'],
    'url' => absolute_url('/'),
    'inLanguage' => 'de-DE',
    'publisher' => ['@id' => absolute_url('/') . '#organization'],
    'potentialAction' => [
        '@type' => 'SearchAction',
        'target' => [
            '@type' => 'EntryPoint',
            'urlTemplate' => absolute_url('/') . '?s={search_term_string}',
        ],
        'query-input' => 'required name=search_term_string',
    ],
];

$breadcrumbSchema = [
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
        [
            '@type' => 'ListItem',
            'position' => 1,
            'name' => 'Start',
            'item' => absolute_url('/'),
        ],
    ],
];

// Build ItemList schema for the "Aktuelle Spielzeit" cards.
$spielzeitItems = [];
foreach ($spielzeitHighlights as $i => $card) {
    $spielzeitItems[] = [
        '@type' => 'ListItem',
        'position' => $i + 1,
        'name' => $card['title'],
        'url' => $card['cta_external']
            ? $card['cta_url']
            : absolute_url($card['cta_url']),
        'image' => absolute_url($card['image']),
    ];
}
$itemListSchema = [
    '@type' => 'ItemList',
    'name' => 'Aktuelle Spielzeit 2026',
    'itemListElement' => $spielzeitItems,
];

// Schema.org Event JSON-LD is now sourced from the per-row microdata
// on /tickets-kaufen/ (Playwright-verified). Home page only emits the
// Organization + WebSite + Breadcrumb + ItemList(ItemList of items
// with #termin-N deep-links) blocks below.

$structuredData = [
    '@context' => 'https://schema.org',
    '@graph' => array_values(array_filter([
        $organizationSchema,
        $websiteSchema,
        $breadcrumbSchema,
        $itemListSchema,
        count($upcomingSorted) > 0 ? [
            '@type' => 'ItemList',
            'name' => 'Nächste Vorstellungen',
            'itemListElement' => array_values(array_map(function ($evt, $globalIdx) {
                return [
                    '@type' => 'ListItem',
                    'position' => $globalIdx + 1,
                    'name' => $evt['title'] . ' — ' . ($evt['subtitle'] ?? ''),
                    'startDate' => iso_local((string) $evt['date'], (string) ($evt['time'] ?? '18:00')),
                    'url' => absolute_url('/tickets-kaufen/#termin-' . ($globalIdx + 1)),
                ];
            }, $upcomingSorted, array_keys($upcomingSorted))),
        ] : null,
    ])),
];

require __DIR__ . '/partials/layout-start.php';
?>
<main id="main-content">
    <section class="hero hero--feature" aria-labelledby="hero-title">
        <div class="container">
            <div class="hero-grid">
                <div class="hero-copy" data-reveal>
                    <p class="hero__eyebrow">
                        <span class="hero__eyebrow-dot" aria-hidden="true"></span>
                        Tickets für das Weihnachtsmärchen verfügbar
                    </p>
                    <h1 id="hero-title">
                        Theater seit 1908.<br>
                        Handgemacht, nahbar, mit Herzblut.
                    </h1>
                    <p class="lead">
                        Seit <?= e((string) $siteConfig['founding_year']) ?> begeistert die Volksbühne Worms Generationen
                        mit einfallsreichem, mitreißendem Theater. Von Saisonstücken bis zum Weihnachtsmärchen:
                        Kultur lebt hier nahbar und handgemacht.
                    </p>

                    <?php if ($countdownTarget && $nextUpcoming) : ?>
                        <?php
                            $isPremiere = str_contains(strtolower($nextUpcoming['subtitle'] ?? ''), 'premiere');
                            $countdownLabel = $isPremiere ? 'Nächste Premiere' : 'Nächste Vorstellung';
                            $isCountdownSoldOut = ($nextUpcoming['status'] ?? 'tickets') === 'sold_out';
                        ?>
                        <div class="hero__countdown"
                             role="group"
                             aria-labelledby="countdown-label"
                             data-countdown="<?= e($countdownTarget) ?>"
                             data-countdown-label="Noch">
                            <p id="countdown-label" class="eyebrow" style="margin-bottom: 0; align-self: center;">
                                <?= e($countdownLabel) ?>: <?= e(format_date_de($nextUpcoming['date'], true)) ?> · <?= e($nextUpcoming['time']) ?> Uhr
                                <?php if ($isCountdownSoldOut) : ?>
                                    <span class="chip chip--warning" style="margin-left: var(--space-sm); vertical-align: middle;">
                                        <span class="chip__dot" aria-hidden="true"></span>Ausverkauft
                                    </span>
                                <?php endif; ?>
                            </p>
                            <div class="countdown-unit" aria-hidden="true">
                                <span class="num" data-countdown-day>–</span>
                                <span class="lbl">Tage</span>
                            </div>
                            <div class="countdown-unit" aria-hidden="true">
                                <span class="num" data-countdown-hour>––</span>
                                <span class="lbl">Std</span>
                            </div>
                            <div class="countdown-unit" aria-hidden="true">
                                <span class="num" data-countdown-min>––</span>
                                <span class="lbl">Min</span>
                            </div>
                            <div class="countdown-unit" aria-hidden="true">
                                <span class="num" data-countdown-sec>––</span>
                                <span class="lbl">Sek</span>
                            </div>
                            <span class="visually-hidden" data-countdown-live aria-live="polite">
                                Countdown zur <?= $isPremiere ? 'Premiere' : 'nächsten Vorstellung' ?>
                            </span>
                        </div>
                    <?php endif; ?>

                    <div class="actions">
                        <a class="btn btn-primary btn-lg" href="<?= e(site_url('/tickets-kaufen/#alle-termine')) ?>">
                            Tickets bestellen
                            <svg class="btn__icon" viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14M13 5l7 7-7 7"/>
                            </svg>
                        </a>
                        <a class="btn btn-secondary btn-lg" href="<?= e(site_url('/spielzeit/')) ?>">
                            Spielzeit ansehen
                        </a>
                    </div>
                </div>
                <div class="hero-visual" data-reveal data-reveal-delay="1" aria-hidden="false">
                    <div class="hero-curtain" data-curtain aria-hidden="true"></div>
                    <img src="<?= e(asset('/assets/img/bsm.webp')) ?>"
                         width="1024" height="1536"
                         alt="Titelbild des Weihnachtsmärchens 2026: Die Bremer Stadtmusikanten"
                         fetchpriority="high"
                         decoding="async">
                    <span class="hero-visual__caption">
                        Die Bremer Stadtmusikanten — ab 1. Dezember 2026, Das Wormser
                    </span>
                </div>
            </div>
        </div>
    </section>

    <section class="section" aria-labelledby="upcoming-title">
        <div class="container">
            <div class="section-head" data-reveal>
                <p class="eyebrow">Nächste Vorstellungen</p>
                <?php if (!empty($upcomingHeading)) : ?>
                    <h2 id="upcoming-title"><?= e($upcomingHeading) ?></h2>
                <?php else : ?>
                    <h2 id="upcoming-title" class="visually-hidden">Nächste Vorstellungen</h2>
                <?php endif; ?>
                <?php if (!empty($upcomingLead)) : ?>
                    <p class="lead-compact"><?= e($upcomingLead) ?></p>
                <?php endif; ?>
            </div>

            <?php if (!empty($upcomingStrip)) : ?>
                <div class="upcoming-strip" role="list">
                    <?php foreach ($upcomingStrip as $i => $evt) : ?>
                        <?php
                        $eventIdx = array_search($evt, $upcomingSorted, true);
                        $deepLink = $eventIdx !== false
                            ? site_url('/tickets-kaufen/#termin-' . ($eventIdx + 1))
                            : site_url('/tickets-kaufen/');
                        ?>
                        <a class="upcoming-item"
                           href="<?= e($deepLink) ?>"
                           role="listitem">
                            <div class="upcoming-item__date" aria-hidden="true">
                                <span class="day"><?= e(date('d', strtotime($evt['date']))) ?></span>
                                <span class="month"><?= e(month_short_de($evt['date'])) ?></span>
                            </div>
                            <div>
                                <span class="upcoming-item__title">
                                    <?= e($evt['title']) ?>
                                </span>
                                <span class="upcoming-item__meta">
                                    <?= e(weekday_short_de($evt['date'])) ?>, <?= e($evt['time']) ?><?= !empty($evt['venue']) ? ' · ' . e($evt['venue']) : '' ?>
                                </span>
                            </div>
                            <div class="upcoming-item__cta">
                                <?= render_status_chip($evt['status'] ?? 'tickets') ?>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="actions" style="margin-top: var(--space-xl); justify-content: center;" data-reveal>
                <a class="btn btn-ghost" href="<?= e(site_url('/tickets-kaufen/')) ?>">Alle Termine &amp; Tickets →</a>
            </div>
        </div>
    </section>

    <section class="section section--alt" aria-labelledby="spielzeit-title">
        <div class="container">
            <div class="section-head" data-reveal>
                <p class="eyebrow">Aktuelle Spielzeit</p>
                <?php if (!empty($spielzeitHeading)) : ?>
                    <h2 id="spielzeit-title"><?= e($spielzeitHeading) ?></h2>
                <?php else : ?>
                    <h2 id="spielzeit-title" class="visually-hidden">Aktuelle Spielzeit</h2>
                <?php endif; ?>
                <?php if (!empty($spielzeitLead)) : ?>
                    <p class="lead-compact"><?= e($spielzeitLead) ?></p>
                <?php endif; ?>
            </div>

            <div class="card-grid card-grid--featured">
                <?php foreach ($spielzeitHighlights as $i => $card) : ?>
                    <?php
                    $event = [
                        'title'      => $card['title'],
                        'subtitle'   => truncate_text($card['lead'], 220, '…'),
                        'image'      => $card['image'],
                        'image_alt'  => $card['image_alt'],
                        'href'       => $card['cta_url'],
                        'ticket_url' => $card['cta_url'],
                        'price'      => $card['meta'], // "Premiere 03.10.2026 · Volksbühne — kleines Theater"
                        'eyebrow'    => $card['eyebrow'],
                        'status'     => 'tickets',
                    ];
                    ?>
                    <div data-reveal data-reveal-delay="<?= e((string) ($i + 1)) ?>">
                        <?php $compact = false; include __DIR__ . '/partials/event-card.php'; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="section" aria-labelledby="verein-title">
        <div class="container">
            <div class="section-head" data-reveal>
                <p class="eyebrow">Der Verein</p>
                <h2 id="verein-title"><?= e($vereinHeading ?? ('Seit ' . (string) $siteConfig['founding_year'] . ' auf der Bühne.')) ?></h2>
                <?php if (!empty($vereinLead)) : ?>
                    <p class="lead"><?= e($vereinLead) ?></p>
                <?php endif; ?>
            </div>

            <div class="stat-row" data-reveal>
                <div class="stat-item">
                    <span class="stat-number"><?= e($yearsActive) ?>+</span>
                    <span class="stat-label">Jahre aktiv</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number"><?= e((string) $siteConfig['member_count']) ?>+</span>
                    <span class="stat-label">Mitglieder</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number"><?= e((string) $siteConfig['production_count']) ?>+</span>
                    <span class="stat-label">Vorstellungen</span>
                </div>
            </div>

            <div class="actions" style="margin-top: var(--space-xl); justify-content: center;" data-reveal>
                <a class="btn btn-secondary" href="<?= e(site_url('/verein/')) ?>">Über den Verein erfahren →</a>
            </div>
        </div>
    </section>

    <section class="section section--brand" aria-labelledby="mitglied-title" id="mitglied-werden-home">
        <div class="container" style="max-width: 64rem;">
            <div class="section-head section-head--center" data-reveal>
                <p class="eyebrow" style="color: #fff; opacity: 0.9;">Mitglied werden</p>
                <h2 id="mitglied-title" style="color: #fff;"><?= e($mitgliedHeading ?? 'Mitgliedschaft') ?></h2>
                <?php if (!empty($mitgliedLead)) : ?>
                    <p class="lead" style="color: rgba(255,255,255,0.92); margin-inline: auto;">
                        <?= e($mitgliedLead) ?>
                    </p>
                <?php endif; ?>
            </div>
            <div class="actions" style="justify-content: center; margin-top: var(--space-lg);" data-reveal>
                <a class="btn btn-light btn-lg" href="<?= e(site_url('/verein/#mitglied-werden')) ?>">
                    <?= e($mitgliedCtaLabel ?? 'Mehr erfahren') ?>
                    <svg class="btn__icon" viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14M13 5l7 7-7 7"/>
                    </svg>
                </a>
                <a class="btn btn-light btn-lg" href="mailto:<?= e($siteConfig['email']) ?>?subject=Mitgliedschaft%20Volksb%C3%BChne%20Worms">
                    Per E-Mail anfragen
                </a>
            </div>
        </div>
    </section>

    <section class="section section--surface" aria-labelledby="kontakt-title">
        <div class="container container--narrow">
            <div class="contact-card" data-reveal>
                <div>
                    <p class="eyebrow">Kontakt</p>
                    <h2 id="kontakt-title">Schreiben Sie uns.</h2>
                    <p>Wir beantworten Anfragen zu Stücken, Mitgliedschaft und Vorstellungen schnell und persönlich.</p>
                </div>
                <div class="actions">
                    <a class="btn btn-primary" href="mailto:<?= e($siteConfig['email']) ?>">E-Mail senden</a>
                    <a class="btn btn-tertiary" href="tel:<?= e($siteConfig['phone_href']) ?>">Anrufen: <?= e($siteConfig['phone_display']) ?></a>
                    <a class="btn btn-secondary" href="<?= e(site_url('/kontakt/')) ?>">Kontaktseite →</a>
                </div>
            </div>
        </div>
    </section>
</main>
<?php require __DIR__ . '/partials/layout-end.php'; ?>