<?php
declare(strict_types=1);

// Fallback front-controller routing:
// Some hosters route every request to index.php. In that case we still
// render the correct subpage based on REQUEST_URI.
$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
if (preg_match('~^/tickets-kaufen/?$~i', $requestPath) === 1) {
    header('Location: ' . $siteConfig['spielzeit_ticket_url'], true, 301);
    exit;
}
if (preg_match('~(?:^|/)(spielzeit|verein|kontakt|weihnachtsmaerchen|impressum|datenschutz)(?:\.php)?/?$~i', $requestPath, $matches) === 1) {
    $routeMap = [
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

// Determine the next upcoming event with a target date in the future for countdown.
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
        'meta' => 'Einlass 1,5 h vor Beginn · Das Wormser',
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
        'meta' => 'Premiere 29.11.2026 · Das Wormser',
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

// Upcoming events as Event list.
$upcomingEventsSchema = [];
foreach (array_slice($upcomingSorted, 0, 8) as $evt) {
    $upcomingEventsSchema[] = [
        '@type' => 'Event',
        'name' => $evt['title'] . ' — ' . ($evt['subtitle'] ?? ''),
        'startDate' => iso_local($evt['date'], $evt['time'] ?? '18:00'),
        'eventStatus' => event_status_url($evt['status'] ?? 'tickets'),
        'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
        'location' => [
            '@type' => 'Place',
            'name' => $evt['venue'] ?? 'Das Wormser',
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => 'Worms',
                'addressCountry' => 'DE',
            ],
        ],
        'organizer' => ['@id' => absolute_url('/') . '#organization'],
    ];
}

$structuredData = [
    '@context' => 'https://schema.org',
    '@graph' => array_values(array_filter([
        $organizationSchema,
        $websiteSchema,
        $breadcrumbSchema,
        $itemListSchema,
        count($upcomingEventsSchema) > 0 ? [
            '@type' => 'ItemList',
            'name' => 'Nächste Vorstellungen',
            'itemListElement' => array_map(function ($evt, $i) {
                return [
                    '@type' => 'ListItem',
                    'position' => $i + 1,
                    'name' => $evt['title'],
                    'startDate' => iso_local($evt['date'], $evt['time'] ?? '18:00'),
                    'url' => isset($evt['ticket_url']) ? $evt['ticket_url'] : absolute_url('/'),
                ];
            }, $upcomingEventsSchema, array_keys($upcomingEventsSchema)),
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
                        Wir sind Theater.<br>
                        Mit Haltung, Herz und Bühne.
                    </h1>
                    <p class="lead">
                        Seit <?= e((string) $siteConfig['founding_year']) ?> begeistert die Volksbühne Worms Generationen
                        mit einfallsreichem, mitreißendem Theater. Von Saisonstücken bis zum Weihnachtsmärchen:
                        Kultur lebt hier nahbar und handgemacht.
                    </p>

                    <?php if ($countdownTarget && $nextUpcoming) : ?>
                        <div class="hero__countdown"
                             role="group"
                             aria-labelledby="countdown-label"
                             data-countdown="<?= e($countdownTarget) ?>"
                             data-countdown-label="Noch">
                            <p id="countdown-label" class="eyebrow" style="margin-bottom: 0; align-self: center;">
                                Nächste Premiere: <?= e(format_date_de($nextUpcoming['date'], true)) ?> · <?= e($nextUpcoming['time']) ?> Uhr
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
                                Countdown zur nächsten Vorstellung
                            </span>
                        </div>
                    <?php endif; ?>

                    <div class="actions">
                        <a class="btn btn-primary btn-lg" href="<?= e($siteConfig['maerchen_ticket_url']) ?>" rel="noopener">
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
                        Die Bremer Stadtmusikanten — Premiere 29. November 2026, Das Wormser
                    </span>
                </div>
            </div>
        </div>
    </section>

    <section class="section" aria-labelledby="upcoming-title">
        <div class="container">
            <div class="section-head" data-reveal>
                <p class="eyebrow">Nächste Vorstellungen</p>
                <h2 id="upcoming-title">Was bei uns als Nächstes auf der Bühne steht</h2>
                <p class="lead-compact">Alle Termine auf einen Blick — Tickets in wenigen Klicks.</p>
            </div>

            <?php if (!empty($upcomingStrip)) : ?>
                <div class="upcoming-strip" role="list">
                    <?php foreach ($upcomingStrip as $evt) : ?>
                        <a class="upcoming-item"
                           href="<?= e($evt['ticket_url'] ?? $siteConfig['spielzeit_ticket_url']) ?>"
                           rel="noopener"
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
                                    <?= e(weekday_short_de($evt['date'])) ?>, <?= e($evt['time']) ?> · <?= e($evt['venue'] ?? 'Das Wormser') ?>
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
                <a class="btn btn-ghost" href="<?= e(site_url('/spielzeit/')) ?>">Alle Termine ansehen →</a>
            </div>
        </div>
    </section>

    <section class="section section--alt" aria-labelledby="spielzeit-title">
        <div class="container">
            <div class="section-head" data-reveal>
                <p class="eyebrow">Aktuelle Spielzeit</p>
                <h2 id="spielzeit-title">Was wir gerade spielen</h2>
            </div>

            <div class="card-grid card-grid--featured">
                <?php foreach ($spielzeitHighlights as $i => $card) : ?>
                    <article class="event-card" data-reveal data-reveal-delay="<?= e((string) ($i + 1)) ?>" itemscope itemtype="https://schema.org/Event">
                        <a class="event-card__media" href="<?= e($card['cta_url']) ?>" <?= $card['cta_external'] ? 'rel="noopener"' : '' ?> itemprop="url">
                            <img src="<?= e($card['image']) ?>"
                                 width="450" height="300"
                                 alt="<?= e($card['image_alt']) ?>"
                                 loading="lazy"
                                 decoding="async"
                                 itemprop="image">
                        </a>
                        <div class="event-card__body">
                            <p class="eyebrow" style="margin-bottom: 0;"><?= e($card['eyebrow']) ?></p>
                            <h3 class="event-card__title" itemprop="name"><?= e($card['title']) ?></h3>
                            <p class="event-card__subtitle" itemprop="description"><?= e(mb_strimwidth($card['lead'], 0, 220, '…')) ?></p>
                            <div class="event-card__meta">
                                <span class="event-card__meta-item">📍 <?= e($card['meta']) ?></span>
                            </div>
                        </div>
                        <div class="event-card__footer">
                            <span class="text-muted" style="font-size: 0.875rem;">Tickets im Vorverkauf</span>
                            <a class="btn btn-primary btn-sm" href="<?= e($card['cta_url']) ?>" <?= $card['cta_external'] ? 'rel="noopener"' : '' ?>>
                                <?= e($card['cta_label']) ?>
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="section" aria-labelledby="verein-title">
        <div class="container">
            <div class="section-head" data-reveal>
                <p class="eyebrow">Der Verein</p>
                <h2 id="verein-title">Mehr als Theater — Gemeinschaft seit <?= e((string) $siteConfig['founding_year']) ?>.</h2>
                <p class="lead">
                    Eine der ältesten Laienspielgruppen in Rheinland-Pfalz — getragen von Mitgliedern,
                    die ihre Begeisterung für das Theater teilen wollen. Ob Märchen oder Komödie:
                    Unsere Inszenierungen sind handgemacht, nahbar und voller Herzblut.
                </p>
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
                <h2 id="mitglied-title" style="color: #fff;">Werde Teil unserer Bühne.</h2>
                <p class="lead" style="color: rgba(255,255,255,0.92); margin-inline: auto;">
                    Für nur <?= e($siteConfig['verein_bank']['membership_fee']) ?> € im Jahr unterstützt du unsere Arbeit,
                    erhältst Vorzugspreise auf ausgewählte Vorstellungen und gehörst zu einer
                    traditionsreichen Theatergemeinschaft.
                </p>
            </div>
            <div class="actions" style="justify-content: center; margin-top: var(--space-lg);" data-reveal>
                <a class="btn btn-light btn-lg" href="<?= e(site_url('/verein/#mitglied-werden')) ?>">
                    Mitgliedschaft erfahren
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