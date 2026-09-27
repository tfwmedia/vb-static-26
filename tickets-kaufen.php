<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

// Sort chronologically
$upcoming = $upcomingEvents;
usort($upcoming, function ($a, $b) {
    return strcmp($a['date'] . ' ' . ($a['time'] ?? '00:00'), $b['date'] . ' ' . ($b['time'] ?? '00:00'));
});

// Group by production for the breadcrumb/structure
$productions = [
    [
        'title' => 'Die Löffelliste',
        'kind' => 'theater',
        'href' => site_url('/spielzeit/#die-loeffelliste'),
        'events' => array_values(array_filter($upcoming, fn($e) => str_contains($e['title'], 'Löffelliste'))),
    ],
    [
        'title' => 'Die Bremer Stadtmusikanten',
        'kind' => 'maerchen',
        'href' => site_url('/weihnachtsmaerchen/'),
        'events' => array_values(array_filter($upcoming, fn($e) => str_contains($e['title'], 'Bremer'))),
    ],
];

$meta = meta([
    'title' => 'Tickets kaufen | ' . $siteConfig['name'],
    'description' => 'Alle Vorstellungen der Volksbühne Worms im Überblick — Tickets direkt bei Ticket-Regional. Saisonstück "Die Löffelliste" und Weihnachtsmärchen "Die Bremer Stadtmusikanten".',
    'canonical' => site_url('/tickets-kaufen/'),
    'og_type' => 'website',
    'og_image' => asset_url('/assets/img/og-tickets.svg'),
    'og_image_alt' => 'Tickets kaufen — Volksbühne Worms',
]);
$activeNav = 'tickets';
$bodyClass = 'page-tickets';
$breadcrumbs = [
    ['label' => 'Start', 'href' => '/'],
    ['label' => 'Tickets kaufen'],
];

// Schema.org: CollectionPage + ItemList of all upcoming Events.
$structuredData = [
    '@context' => 'https://schema.org',
    '@graph' => [
        [
            '@type' => 'CollectionPage',
            'name' => 'Tickets kaufen — Volksbühne Worms',
            'description' => $meta['description'],
            'url' => $meta['canonical'],
            'isPartOf' => ['@id' => absolute_url('/') . '#website'],
            'publisher' => ['@id' => absolute_url('/') . '#organization'],
        ],
        [
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Start', 'item' => absolute_url('/')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Tickets kaufen', 'item' => $meta['canonical']],
            ],
        ],
        [
            '@type' => 'ItemList',
            'name' => 'Alle Vorstellungen 2026',
            'itemListElement' => array_map(function ($e, $i) {
                $item = [
                    '@type' => 'ListItem',
                    'position' => $i + 1,
                    'name' => $e['title'] . ' — ' . format_date_de($e['date'], true) . ' ' . ($e['time'] ?? ''),
                    'url' => absolute_url('/tickets-kaufen/#termin-' . ($i + 1)),
                ];
                return $item;
            }, $upcoming, array_keys($upcoming)),
        ],
    ],
];

require __DIR__ . '/partials/layout-start.php';
?>
<main id="main-content">

    <section class="hero hero-inner" aria-labelledby="tickets-title">
        <div class="container container--narrow" data-reveal>
            <p class="eyebrow">Tickets</p>
            <h1 id="tickets-title">Tickets kaufen</h1>
            <p class="lead">
                Alle Vorstellungen der aktuellen Spielzeit im Überblick. Der Ticket-Kauf
                führt direkt zu unserem Vorverkaufs-Partner
                <a href="https://www.ticket-regional.de" rel="noopener">Ticket-Regional</a>.
            </p>
            <div class="actions" style="margin-top: var(--space-lg);">
                <a class="btn btn-primary" href="#alle-termine">
                    Zu den Terminen
                    <svg class="btn__icon" viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 5v14M19 12l-7 7-7-7"/>
                    </svg>
                </a>
                <a class="btn btn-ghost" href="<?= e(site_url('/spielzeit/')) ?>">Zur Spielzeit-Übersicht</a>
            </div>
        </div>
    </section>

    <section class="section" id="alle-termine" aria-labelledby="termine-title">
        <div class="container">
            <div class="section-head" data-reveal>
                <p class="eyebrow">Vorstellungen 2026</p>
                <h2 id="termine-title">Alle Termine</h2>
                <p class="lead-compact">
                    <?= count($upcoming) ?> Vorstellungen in der laufenden Spielzeit — Tickets verfügbar
                    über Ticket-Regional.
                </p>
            </div>

            <?php foreach ($productions as $prod) : if (empty($prod['events'])) continue; ?>
                <article class="tickets-production" id="produktion-<?= e(slugify($prod['title'])) ?>" data-reveal>
                    <header class="tickets-production__head">
                        <div>
                            <p class="eyebrow"><?= $prod['kind'] === 'maerchen' ? 'Weihnachtsmärchen 2026' : 'Saisonstück 2026' ?></p>
                            <h3 class="tickets-production__title"><?= e($prod['title']) ?></h3>
                        </div>
                        <a class="btn btn-ghost btn-sm" href="<?= e($prod['href']) ?>">
                            Zur Produktion
                            <svg class="btn__icon" viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14M13 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </header>

                    <ul class="ticket-list" role="list">
                        <?php foreach ($prod['events'] as $evt) :
                            $isAvailable = !empty($evt['ticket_url']) && ($evt['status'] ?? 'tickets') !== 'sold_out';
                            $globalIdx = array_search($evt, $upcoming, true);
                        ?>
                            <li class="ticket-row" id="termin-<?= e((string) ($globalIdx !== false ? $globalIdx + 1 : 0)) ?>"
                                itemscope itemtype="https://schema.org/Event">
                                <meta itemprop="name" content="<?= e($evt['title'] . ' — ' . ($evt['subtitle'] ?? '')) ?>">
                                <meta itemprop="startDate" content="<?= e(iso_local($evt['date'], $evt['time'] ?? '20:00')) ?>">
                                <meta itemprop="eventStatus" content="<?= e(event_status_url($evt['status'] ?? 'tickets')) ?>">
                                <meta itemprop="eventAttendanceMode" content="https://schema.org/OfflineEventAttendanceMode">
                                <?php
                                    $isBremer = str_contains($evt['title'] ?? '', 'Bremer');
                                    $placeName = $isBremer ? 'Das Wormser' : 'Volksbühne — kleines Theater';
                                    $placeStreet = $isBremer ? 'Rathenaustraße 11' : 'Würdtweinstraße 11';
                                ?>
                                <div itemprop="location" itemscope itemtype="https://schema.org/Place" style="display:none;">
                                    <meta itemprop="name" content="<?= e($placeName) ?>">
                                    <div itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
                                        <meta itemprop="streetAddress" content="<?= e($placeStreet) ?>">
                                        <meta itemprop="postalCode" content="67549">
                                        <meta itemprop="addressLocality" content="Worms">
                                        <meta itemprop="addressCountry" content="DE">
                                    </div>
                                </div>

                                <div class="ticket-row__date" aria-hidden="true">
                                    <span class="day"><?= e(date('d', strtotime($evt['date']))) ?></span>
                                    <span class="month"><?= e(month_short_de($evt['date'])) ?></span>
                                    <span class="weekday"><?= e(weekday_short_de($evt['date'])) ?></span>
                                </div>
                                <div class="ticket-row__main">
                                    <p class="ticket-row__title"><?= e($evt['title']) ?></p>
                                    <p class="ticket-row__meta">
                                        <span><?= e(format_date_de($evt['date'], true)) ?></span>
                                        <span aria-hidden="true">·</span>
                                        <span><?= e($evt['time']) ?> Uhr</span>
                                        <span aria-hidden="true">·</span>
                                        <?php if (!empty($evt['venue'])) : ?>
                                            <span><?= e($evt['venue']) ?></span>
                                        <?php endif; ?>
                                    </p>
                                    <?php if (!empty($evt['subtitle']) && $evt['subtitle'] !== 'Saisonstück 2026' && $evt['subtitle'] !== 'Weihnachtsmärchen 2026') : ?>
                                        <p class="ticket-row__note"><?= e($evt['subtitle']) ?></p>
                                    <?php endif; ?>
                                </div>
                                <div class="ticket-row__status">
                                    <?= render_status_chip($evt['status'] ?? 'tickets') ?>
                                </div>
                                <div class="ticket-row__cta">
                                    <?php if ($isAvailable) : ?>
                                        <a class="btn btn-primary btn-sm" href="<?= e($evt['ticket_url']) ?>" rel="noopener"
                                           itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                                            <meta itemprop="url" content="<?= e($evt['ticket_url']) ?>">
                                            <meta itemprop="priceCurrency" content="EUR">
                                            <meta itemprop="availability" content="https://schema.org/InStock">
                                            <meta itemprop="price" content="<?= e(str_replace(',', '.', preg_replace('/[^\d,]/', '', $evt['price'] ?? '0'))) ?>">
                                            Tickets kaufen
                                            <svg class="btn__icon" viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M5 12h14M13 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    <?php else : ?>
                                        <span class="btn btn-secondary btn-sm" aria-disabled="true">Ausverkauft</span>
                                    <?php endif; ?>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="section section--alt" aria-labelledby="hinweise-title">
        <div class="container container--narrow">
            <div class="section-head" data-reveal>
                <p class="eyebrow">Hinweise</p>
                <h2 id="hinweise-title">Gut zu wissen</h2>
            </div>

            <div class="benefits">
                <div class="benefit" data-reveal>
                    <div class="benefit__icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.86 19.86 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.86 19.86 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.37 1.9.72 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.35 1.85.59 2.81.72A2 2 0 0 1 22 16.92Z"/></svg>
                    </div>
                    <h3 class="benefit__title">Ticket-Bestellung am Telefon</h3>
                    <p class="benefit__text">
                        Ticket-Regional Hotline: <a href="tel:06519790777">0651 / 97 90 777</a>.
                        Wir selbst verkaufen keine Karten — Ticket-Regional ist unser Vorverkaufs-Partner.
                    </p>
                </div>
                <div class="benefit" data-reveal>
                    <div class="benefit__icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </div>
                    <h3 class="benefit__title">Einlass &amp; Vorstellungsbeginn</h3>
                    <p class="benefit__text">
                        Einlass in der Regel eine Stunde vor Vorstellungsbeginn. Wir empfehlen,
                        rund 20 Minuten vor Beginn da zu sein.
                    </p>
                </div>
                <div class="benefit" data-reveal>
                    <div class="benefit__icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                    </div>
                    <h3 class="benefit__title">Spielstätten</h3>
                    <p class="benefit__text">
                        <strong>Saisonstück:</strong> Volksbühne — kleines Theater,
                        Würdtweinstraße 11, 67549 Worms.<br>
                        <strong>Weihnachtsmärchen:</strong> <a href="<?= e(site_url('/kontakt/')) ?>">Das Wormser</a>,
                        Rathenaustraße 11, 67549 Worms.
                    </p>
                </div>
            </div>

            <div class="actions" style="margin-top: var(--space-2xl); justify-content: center;" data-reveal>
                <a class="btn btn-ghost" href="<?= e(site_url('/kontakt/')) ?>">
                    Fragen? Kontakt aufnehmen
                    <svg class="btn__icon" viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14M13 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>

</main>
<?php require __DIR__ . '/partials/layout-end.php'; ?>
