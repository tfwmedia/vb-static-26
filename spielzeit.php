<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

/**
 * Productions of the current Spielzeit (2026/27). Structured so the same
 * data could later come from a backend/CMS without changing templates.
 *
 * `productions[]` shape:
 *   - title, subtitle, image, image_alt, lead, ticket_url, status,
 *     director, season, kind ('theater'|'maerchen'), highlight,
 *     upcoming[] (list of dates).
 */
$productions = [
    [
        'title' => 'Die Löffelliste',
        'subtitle' => 'Saisonstück 2026',
        'image' => asset('/assets/img/loeffelliste.webp'),
        'image_alt' => 'Titelbild des Saisonstücks 2026: Die Löffelliste',
        'lead' => 'Ein Tag, den sich Erika ganz anders vorgestellt hat. Eine unerwartete Nachricht von ihrem Vermieter, dann Freunde, die sich in ihr Leben einmischen wollen und zu guter Letzt noch die Begegnung mit Gevatter Tod. Aber nicht mit Erika. Sie weiß sich zu helfen und hat dabei ihren Spaß. Von Gerhard Schreiner, in einer Inszenierung von Peter Schmitt & Kathrin Landmann.',
        'ticket_url' => $siteConfig['spielzeit_ticket_url'],
        'status' => 'tickets',
        'director' => 'Peter Schmitt & Kathrin Landmann',
        'author' => 'Gerhard Schreiner',
        'kind' => 'theater',
        'highlight' => true,
        'upcoming' => array_values(array_filter($upcomingEvents, fn($e) => str_contains($e['title'], 'Löffelliste'))),
    ],
    [
        'title' => 'Die Bremer Stadtmusikanten',
        'subtitle' => 'Weihnachtsmärchen 2026',
        'image' => asset('/assets/img/bsm.webp'),
        'image_alt' => 'Titelbild Weihnachtsmärchen 2026: Die Bremer Stadtmusikanten',
        'lead' => 'Vier alte Tiere auf dem Weg nach Bremen — Ein Märchen der Brüder Grimm in einer Inszenierung von Peter Schmitt. In dem Märchen schließen sich ein alter Esel, ein Hund, eine Katze und ein Hahn zusammen, nachdem ihre Besitzer sie loswerden wollen. Gemeinsam machen sie sich auf den Weg nach Bremen, um dort Stadtmusikanten zu werden. Unterwegs vertreiben sie mit Mut und Zusammenhalt eine Räuberbande aus einem Haus im Wald und finden dort schließlich ein neues Zuhause — und eine starke Freundschaft.',
        'ticket_url' => $siteConfig['maerchen_ticket_url'],
        'status' => 'tickets',
        'director' => 'Peter Schmitt',
        'author' => 'Brüder Grimm',
        'kind' => 'maerchen',
        'highlight' => true,
        'upcoming' => array_values(array_filter($upcomingEvents, fn($e) => str_contains($e['title'], 'Bremer'))),
    ],
];

/**
 * Archiv-Produktionen. Bleibt leer, bis der Verein eine offizielle
 * Liste pflegt. Die Archiv-Sektion rendert nur, wenn \$archive
 * nicht leer ist — siehe Template.
 *
 * @var array<int, array{year:string, title:string, note?:string}> $archive
 */
$archive = [];
$archiveHeading = '';
$archiveLead = '';
$archiveCtaLabel = '';
$archiveCtaUrl = '';

$meta = meta([
    'title' => 'Spielzeit 2026/27 | ' . $siteConfig['name'],
    'description' => 'Aktuelle Produktionen der Volksbühne Worms: das Saisonstück "Die Löffelliste" und das Weihnachtsmärchen "Die Bremer Stadtmusikanten" — Tickets und Termine im Überblick.',
    'canonical' => site_url('/spielzeit/'),
    'og_type' => 'website',
    'og_image' => asset_url('/assets/img/og-spielzeit.svg'),
    'og_image_alt' => 'Spielzeit 2026/27 — Volksbühne Worms',
]);
$activeNav = 'spielzeit';
$bodyClass = 'page-spielzeit';
$breadcrumbs = [
    ['label' => 'Start', 'href' => '/'],
    ['label' => 'Spielzeit'],
];

// Schema.org: CollectionPage mit ItemList der laufenden Produktionen.
$structuredData = [
    '@context' => 'https://schema.org',
    '@graph' => [
        [
            '@type' => 'CollectionPage',
            'name' => 'Spielzeit 2026/27',
            'description' => $meta['description'],
            'url' => $meta['canonical'],
            'isPartOf' => ['@id' => absolute_url('/') . '#website'],
            'publisher' => ['@id' => absolute_url('/') . '#organization'],
        ],
        [
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Start', 'item' => absolute_url('/')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Spielzeit', 'item' => $meta['canonical']],
            ],
        ],
        [
            '@type' => 'ItemList',
            'name' => 'Aktuelle Produktionen 2026/27',
            'itemListElement' => array_map(function ($p, $i) {
                return [
                    '@type' => 'ListItem',
                    'position' => $i + 1,
                    'name' => $p['title'],
                    'url' => $p['kind'] === 'maerchen' ? absolute_url('/weihnachtsmaerchen/') : absolute_url('/spielzeit/#' . e(slugify($p['title']))),
                ];
            }, $productions, array_keys($productions)),
        ],
    ],
];

require __DIR__ . '/partials/layout-start.php';
?>
<main id="main-content">
<section class="hero hero-inner" aria-labelledby="spielzeit-title">
        <div class="container container--narrow" data-reveal>
            <p class="eyebrow">Spielzeit 2026/27</p>
            <h1 id="spielzeit-title"><?= e($productionsPageHeading ?? 'Spielzeit 2026/27') ?></h1>
            <p class="lead">
                <?= e($productionsPageLead ?? 'Aktuelle Produktionen der Volksbühne Worms — Tickets und Termine im Überblick.') ?>
            </p>
        </div>
    </section>

    <section class="section" aria-label="Aktuelle Produktionen">
        <div class="container">
            <div class="card-grid card-grid--featured">
                <?php foreach ($productions as $i => $prod) : ?>
                    <article id="<?= e(slugify($prod['title'])) ?>" class="event-card" data-reveal data-reveal-delay="<?= e((string) ($i + 1)) ?>"
                             itemscope itemtype="https://schema.org/TheaterEvent">
                        <a class="event-card__media" href="<?= $prod['kind'] === 'maerchen' ? e(site_url('/weihnachtsmaerchen/')) : '#' . e(slugify($prod['title'])) ?>" itemprop="url">
                            <img src="<?= e($prod['image']) ?>"
                                 alt="<?= e($prod['image_alt']) ?>"
                                 width="450" height="300"
                                 loading="lazy" decoding="async"
                                 itemprop="image">
                            <span class="event-card__chip-row">
                                <?php if ($prod['kind'] === 'maerchen') : ?>
                                    <span class="chip chip--brand">Märchen</span>
                                <?php else : ?>
                                    <span class="chip chip--soft">Saisonstück</span>
                                <?php endif; ?>
                            </span>
                        </a>
                        <div class="event-card__body">
                            <p class="eyebrow" style="margin-bottom: 0;"><?= e($prod['subtitle']) ?></p>
                            <h2 class="event-card__title" itemprop="name"><?= e($prod['title']) ?></h2>
                            <p class="event-card__subtitle" itemprop="description"><?= e($prod['lead']) ?></p>
                            <dl class="facts" style="margin: var(--space-md) 0 0;">
                                <?php if ($prod['author']) : ?>
                                    <div>
                                        <dt>Autor</dt>
                                        <dd><?= e($prod['author']) ?></dd>
                                    </div>
                                <?php endif; ?>
                                <?php if ($prod['director']) : ?>
                                    <div>
                                        <dt>Inszenierung</dt>
                                        <dd><?= e($prod['director']) ?></dd>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <dt>Spielstätte</dt>
                                    <dd><?= $prod['kind'] === 'maerchen' ? 'Das Wormser, Rathenaustraße 11, Worms' : 'Volksbühne — kleines Theater, Würdtweinstraße 11, Worms' ?></dd>
                                </div>
                            </dl>
                        </div>
                        <div class="event-card__footer">
                            <span class="event-card__price">
                                Tickets<small><?= $prod['kind'] === 'maerchen' ? 'VVK 12,00 €' : 'VVK 25,00 €' ?></small>
                            </span>
                            <a class="btn btn-primary btn-sm" href="<?= e($prod['ticket_url']) ?>" rel="noopener" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                                <meta itemprop="url" content="<?= e($prod['ticket_url']) ?>">
                                <meta itemprop="availability" content="https://schema.org/InStock">
                                <meta itemprop="priceCurrency" content="EUR">
                                <span itemprop="price">12.00</span>
                                Tickets kaufen
                                <svg class="btn__icon" viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M5 12h14M13 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="section section--alt" aria-labelledby="termine-title">
        <div class="container">
            <div class="section-head" data-reveal>
                <p class="eyebrow">Alle Vorstellungen</p>
                <h2 id="termine-title">Termine im Überblick</h2>
                <p class="lead-compact">
                    Termine der aktuellen Spielzeit — Tickets führt direkt zu unserem
                    Vorverkaufs-Partner Ticket-Regional.
                </p>
            </div>

            <?php
            $allUpcoming = $upcomingEvents;
            usort($allUpcoming, fn($a, $b) => strcmp($a['date'] . $a['time'], $b['date'] . $b['time']));
            ?>

            <div class="upcoming-strip" role="list">
                <?php foreach ($allUpcoming as $evt) : ?>
                    <a class="upcoming-item" href="<?= e($evt['ticket_url']) ?>" rel="noopener" role="listitem">
                        <div class="upcoming-item__date" aria-hidden="true">
                            <span class="day"><?= e(date('d', strtotime($evt['date']))) ?></span>
                            <span class="month"><?= e(month_short_de($evt['date'])) ?></span>
                        </div>
                        <div>
                            <span class="upcoming-item__title"><?= e($evt['title']) ?></span>
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
        </div>
    </section>

    <?php if (!empty($archive)) : ?>
    <section class="section" aria-labelledby="archiv-title">
        <div class="container container--narrow">
            <div class="section-head" data-reveal>
                <p class="eyebrow">Archiv</p>
                <h2 id="archiv-title"><?= e($archiveHeading ?? 'Frühere Produktionen') ?></h2>
                <p class="lead-compact">
                    <?= e($archiveLead ?? '') ?>
                </p>
            </div>
            <ul class="timeline" data-reveal>
                <?php foreach ($archive as $a) : ?>
                    <li>
                        <span class="year"><?= e($a['year']) ?></span>
                        <span class="event"><strong><?= e($a['title']) ?></strong><?= isset($a['note']) && $a['note'] !== '' ? ' — ' . e($a['note']) : '' ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
            <?php if (!empty($archiveCtaLabel)) : ?>
                <div class="actions" style="margin-top: var(--space-xl);" data-reveal>
                    <a class="btn btn-secondary" href="<?= e($archiveCtaUrl ?? ('mailto:' . $siteConfig['email'])) ?>">
                        <?= e($archiveCtaLabel) ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </section>
<?php endif; ?>
</main>
<?php require __DIR__ . '/partials/layout-end.php'; ?>
