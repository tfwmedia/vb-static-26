<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$yearsActive = max(0, (int) date('Y') - (int) $siteConfig['founding_year']);

$meta = meta([
    'title' => 'Verein | ' . $siteConfig['name'],
    'description' => 'Über die Volksbühne Worms 1908 e. V.: Vorstand, Geschichte und Mitgliedschaft. Eingetragener Verein seit ' . $siteConfig['founding_year'] . '.',
    'canonical' => site_url('/verein/'),
    'og_type' => 'website',
    'og_image' => asset_url('/assets/img/og-verein.svg'),
    'og_image_alt' => 'Verein — Volksbühne Worms',
]);
$activeNav = 'verein';
$bodyClass = 'page-verein';
$breadcrumbs = [
    ['label' => 'Start', 'href' => '/'],
    ['label' => 'Verein'],
];

$structuredData = [
    '@context' => 'https://schema.org',
    '@graph' => [
        [
            '@type' => 'AboutPage',
            'name' => 'Verein',
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
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Verein', 'item' => $meta['canonical']],
            ],
        ],
        [
            '@type' => 'Organization',
            '@id' => absolute_url('/') . '#organization',
            'name' => $siteConfig['name'],
            'url' => absolute_url('/'),
            'logo' => absolute_url('/assets/img/vb-logo.png'),
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
            'subOrganization' => [
                '@type' => 'OrganizationRole',
                'roleName' => 'Eingetragener Verein (e. V.)',
            ],
        ],
    ],
];

require __DIR__ . '/partials/layout-start.php';
?>
<main id="main-content">
    <section class="hero hero-inner" aria-labelledby="verein-h1">
        <div class="container container--narrow" data-reveal>
            <p class="eyebrow">Verein</p>
            <h1 id="verein-h1">Volksbühne Worms 1908 e. V.</h1>
            <p class="lead">
                Eingetragener Verein, gegründet <?= e((string) $siteConfig['founding_year']) ?>.
                Spielstätte, Vorstand, Mitgliedschaft und Bankverbindung — alles Wichtige auf einer Seite.
            </p>
        </div>
    </section>

    <section class="section" aria-labelledby="ueber-title">
        <div class="container">
            <div class="section-head" data-reveal>
                <p class="eyebrow">In Kürze</p>
                <h2 id="ueber-title">Fakten zum Verein</h2>
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
            <dl class="facts" style="margin-top: var(--space-xl); max-width: 60ch;" data-reveal>
                <div>
                    <dt>Rechtsform</dt>
                    <dd>Eingetragener Verein (e. V.)</dd>
                </div>
                <div>
                    <dt>Gründung</dt>
                    <dd><?= e((string) $siteConfig['founding_year']) ?></dd>
                </div>
                <div>
                    <dt>Vereinsregister</dt>
                    <dd>Amtsgericht Mainz · VR 10302</dd>
                </div>
                <div>
                    <dt>Gemeinnützig</dt>
                    <dd>Mitgliedsbeiträge und Spenden sind steuerlich absetzbar.</dd>
                </div>
            </dl>
        </div>
    </section>

    <section class="section section--alt" id="vorstand" aria-labelledby="vorstand-title">
        <div class="container">
            <div class="section-head" data-reveal>
                <p class="eyebrow">Vorstand</p>
                <h2 id="vorstand-title">Wer den Verein trägt</h2>
            </div>

            <div class="people-grid">
                <?php foreach ($vorstand as $p) : ?>
                    <article class="person" data-reveal>
                        <div class="person__avatar" aria-hidden="true">
                            <?php if (!empty($p['photo'])) : ?>
                                <img src="<?= e(asset($p['photo'])) ?>" alt="" width="96" height="96" loading="lazy" decoding="async">
                            <?php else : ?>
                                <?= e(person_initials($p['name'])) ?>
                            <?php endif; ?>
                        </div>
                        <p class="person__role"><?= e($p['role']) ?></p>
                        <h3 class="person__name"><?= e($p['name']) ?></h3>
                        <?php if (!empty($p['note'])) : ?>
                            <p class="person__note"><?= e($p['note']) ?></p>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <?php if (!empty($chronik)) : ?>
        <section class="section" aria-labelledby="chronik-title">
            <div class="container container--narrow">
                <div class="section-head" data-reveal>
                    <p class="eyebrow">Geschichte</p>
                    <h2 id="chronik-title">Stationen des Vereins</h2>
                </div>
                <ul class="timeline" data-reveal>
                    <?php foreach ($chronik as $c) : ?>
                        <li>
                            <span class="year"><?= e($c['year']) ?></span>
                            <span class="event"><?= e($c['event']) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </section>
    <?php endif; ?>

    <section class="section section--brand" id="mitglied-werden" aria-labelledby="mitglied-title">
        <div class="container" style="max-width: 64rem;">
            <div class="section-head section-head--center" data-reveal>
                <p class="eyebrow" style="color: #fff; opacity: 0.9;">Mitgliedschaft</p>
                <h2 id="mitglied-title" style="color: #fff;">Mitglied werden</h2>
            </div>
            <div class="benefits" data-reveal>
                <div class="benefit" style="background: color-mix(in srgb, #fff 12%, transparent); color: #fff; border-color: color-mix(in srgb, #fff 22%, transparent);">
                    <div class="benefit__icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24"><path d="M12 2v20M2 12h20"/></svg>
                    </div>
                    <h3 style="color: #fff;">Mitgliedsbeitrag</h3>
                    <p style="color: rgba(255,255,255,0.92);">
                        <?= e($siteConfig['verein_bank']['membership_fee']) ?> € pro Jahr.
                        Gemeinnützig — steuerlich absetzbar.
                    </p>
                </div>
                <div class="benefit" style="background: color-mix(in srgb, #fff 12%, transparent); color: #fff; border-color: color-mix(in srgb, #fff 22%, transparent);">
                    <div class="benefit__icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24"><path d="M3 7h18M3 12h18M3 17h12"/></svg>
                    </div>
                    <h3 style="color: #fff;">Stimmrecht</h3>
                    <p style="color: rgba(255,255,255,0.92);">
                        Mitgliederversammlung · Vereinsregister VR 10302 (Amtsgericht Mainz).
                    </p>
                </div>
                <div class="benefit" style="background: color-mix(in srgb, #fff 12%, transparent); color: #fff; border-color: color-mix(in srgb, #fff 22%, transparent);">
                    <div class="benefit__icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 22a8 8 0 0 1 16 0"/></svg>
                    </div>
                    <h3 style="color: #fff;">Gemeinschaft</h3>
                    <p style="color: rgba(255,255,255,0.92);">
                        <?= e((string) $siteConfig['member_count']) ?>+ Mitglieder — Laienspielgruppe in Rheinland-Pfalz.
                    </p>
                </div>
            </div>
            <div class="actions" style="justify-content: center; margin-top: var(--space-xl);" data-reveal>
                <a class="btn btn-light btn-lg" href="mailto:<?= e($siteConfig['email']) ?>?subject=Mitgliedschaft%20Volksb%C3%BChne%20Worms">
                    Mitgliedschaft anfragen
                    <svg class="btn__icon" viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14M13 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <section class="section" aria-labelledby="bank-title">
        <div class="container container--narrow">
            <div class="section-head" data-reveal>
                <p class="eyebrow">Bankverbindung</p>
                <h2 id="bank-title">Spenden &amp; Beiträge</h2>
                <p class="lead-compact">
                    Der Verein ist gemeinnützig. Mitgliedsbeiträge und Spenden sind steuerlich absetzbar.
                </p>
            </div>
            <dl class="facts" data-reveal>
                <div>
                    <dt><?= e($siteConfig['verein_bank']['bank_primary']) ?></dt>
                    <dd>
                        IBAN: <?= e($siteConfig['verein_bank']['iban_primary']) ?><br>
                        BIC: <?= e($siteConfig['verein_bank']['bic_primary']) ?>
                    </dd>
                </div>
                <div>
                    <dt><?= e($siteConfig['verein_bank']['bank_secondary']) ?></dt>
                    <dd>
                        IBAN: <?= e($siteConfig['verein_bank']['iban_secondary']) ?><br>
                        BIC: <?= e($siteConfig['verein_bank']['bic_secondary']) ?>
                    </dd>
                </div>
            </dl>
        </div>
    </section>
</main>
<?php require __DIR__ . '/partials/layout-end.php'; ?>