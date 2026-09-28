<?php
declare(strict_types=1);

$siteConfig = [
    'name' => 'Volksbühne Worms 1908 e. V.',
    'short_name' => 'Volksbühne Worms',
    'tagline' => 'Theater seit 1908',
    'email' => 'info@volksbuehne-worms.de',
    'phone_display' => '06241 51429',
    'phone_href' => '+49624151429',
    'maerchen_contact_email' => 'kontakt@volksbuehne-worms.de',
    'maerchen_contact_phone_display' => '0152 57204453',
    'maerchen_contact_phone_href' => '+4915257204453',
    'maerchen_ticket_url' => 'https://www.ticket-regional.de/events_info.php?eventID=257737',
    'spielzeit_ticket_url' => 'https://www.ticket-regional.de/events.php?mysearchSpecificType=eventtype&mysearchSpecificID=1883',
    'social' => [
        'facebook' => 'https://www.facebook.com/VolksbuehneWorms/',
        'instagram' => 'https://www.instagram.com/volksbuehne_worms/',
    ],
    'address' => [
        'name' => 'Volksbühne Worms 1908 e. V.',
        'street' => 'Würdtweinstraße 11',
        'postal_city' => '67549 Worms',
        'country' => 'DE',
    ],
    'founding_year' => 1908,
    'member_count' => 80,
    'production_count' => 500,
    'verein_bank' => [
        'iban_primary' => 'DE82550912000000025046',
        'bic_primary' => 'GENODE61AZY',
        'bank_primary' => 'Volksbank Alzey-Worms',
        'iban_secondary' => 'DE24553500100000005422',
        'bic_secondary' => 'MALADE51WOR',
        'bank_secondary' => 'Sparkasse Worms-Alzey-Ried',
        'membership_fee' => '25,00',
    ],
];

/**
 * Nächste Vorstellungen — wird auf der Startseite, der Spielzeit-Übersicht
 * und der Tickets-Kaufen-Seite ausgespielt. Quelle der Wahrheit sind die
 * Ticket-Regional-Veranstaltungsseiten:
 *   - Löffelliste (eventID 274872) — kleines Theater, Würdtweinstraße 11:
 *     https://www.ticket-regional.de/events_info.php?eventID=274872
 *     Normalpreis € 25,00
 *   - Bremer Stadtmusikanten (eventID 257737) — Das Wormser, Rathenaustraße 11:
 *     https://www.ticket-regional.de/events_info.php?eventID=257737
 *     Normalpreis € 12,00
 *
 * Reihenfolge = Anzeigereihenfolge (aufsteigend nach Datum).
 * `ticket_url` führt direkt zur Ticket-Regional-Buchung mit timeID.
 * `status`:
 *   - 'tickets'   Tickets verfügbar
 *   - 'sold_out'  Ausverkauft (kein Ticket-Button)
 *   - 'premiere'  Premiere / Sondervorstellung
 *   - 'few_left'  Nur noch wenige Plätze
 */
$upcomingEvents = [
    // Die Löffelliste — eventID 274872 — kleines Theater, Würdtweinstraße 11
    [
        'title' => 'Die Löffelliste',
        'subtitle' => 'Saisonstück 2026 · Premiere',
        'date' => '2026-10-03',
        'time' => '20:00',
        'venue' => 'Volksbühne — kleines Theater',
        'ticket_url' => null,
        'price' => '25,00 €',
        'price_note' => 'VVK',
        'status' => 'sold_out',
    ],
    [
        'title' => 'Die Löffelliste',
        'subtitle' => 'Saisonstück 2026',
        'date' => '2026-10-09',
        'time' => '20:00',
        'venue' => 'Volksbühne — kleines Theater',
        'ticket_url' => 'https://www.ticket-regional.de/stage_7698.php?timeID=1139633',
        'price' => '25,00 €',
        'price_note' => 'VVK',
        'status' => 'tickets',
    ],
    [
        'title' => 'Die Löffelliste',
        'subtitle' => 'Saisonstück 2026',
        'date' => '2026-10-10',
        'time' => '20:00',
        'venue' => 'Volksbühne — kleines Theater',
        'ticket_url' => 'https://www.ticket-regional.de/stage_7698.php?timeID=1139634',
        'price' => '25,00 €',
        'price_note' => 'VVK',
        'status' => 'tickets',
    ],
    [
        'title' => 'Die Löffelliste',
        'subtitle' => 'Saisonstück 2026',
        'date' => '2026-10-16',
        'time' => '20:00',
        'venue' => 'Volksbühne — kleines Theater',
        'ticket_url' => 'https://www.ticket-regional.de/stage_7698.php?timeID=1139635',
        'price' => '25,00 €',
        'price_note' => 'VVK',
        'status' => 'tickets',
    ],
    [
        'title' => 'Die Löffelliste',
        'subtitle' => 'Saisonstück 2026',
        'date' => '2026-10-17',
        'time' => '20:00',
        'venue' => 'Volksbühne — kleines Theater',
        'ticket_url' => 'https://www.ticket-regional.de/stage_7698.php?timeID=1139636',
        'price' => '25,00 €',
        'price_note' => 'VVK',
        'status' => 'tickets',
    ],
    [
        'title' => 'Die Löffelliste',
        'subtitle' => 'Saisonstück 2026',
        'date' => '2026-10-24',
        'time' => '20:00',
        'venue' => 'Volksbühne — kleines Theater',
        'ticket_url' => 'https://www.ticket-regional.de/stage_7698.php?timeID=1139637',
        'price' => '25,00 €',
        'price_note' => 'VVK',
        'status' => 'tickets',
    ],
    [
        'title' => 'Die Löffelliste',
        'subtitle' => 'Saisonstück 2026 · Sonntagsvorstellung',
        'date' => '2026-10-25',
        'time' => '18:00',
        'venue' => 'Volksbühne — kleines Theater',
        'ticket_url' => 'https://www.ticket-regional.de/stage_7698.php?timeID=1139638',
        'price' => '25,00 €',
        'price_note' => 'VVK',
        'status' => 'tickets',
    ],
    [
        'title' => 'Die Löffelliste',
        'subtitle' => 'Saisonstück 2026',
        'date' => '2026-11-06',
        'time' => '20:00',
        'venue' => 'Volksbühne — kleines Theater',
        'ticket_url' => null,
        'price' => '25,00 €',
        'price_note' => 'VVK',
        'status' => 'sold_out',
    ],
    [
        'title' => 'Die Löffelliste',
        'subtitle' => 'Saisonstück 2026',
        'date' => '2026-11-14',
        'time' => '20:00',
        'venue' => 'Volksbühne — kleines Theater',
        'ticket_url' => 'https://www.ticket-regional.de/stage_7698.php?timeID=1139640',
        'price' => '25,00 €',
        'price_note' => 'VVK',
        'status' => 'tickets',
    ],
    // Die Bremer Stadtmusikanten — eventID 257737 — Das Wormser, Rathenaustraße 11
    [
        'title' => 'Die Bremer Stadtmusikanten',
        'subtitle' => 'Weihnachtsmärchen 2026',
        'date' => '2026-12-01',
        'time' => '18:00',
        'venue' => 'Das Wormser',
        'ticket_url' => 'https://www.ticket-regional.de/stage_3769.php?timeID=1119484',
        'price' => '12,00 €',
        'price_note' => 'VVK',
        'status' => 'tickets',
    ],
    [
        'title' => 'Die Bremer Stadtmusikanten',
        'subtitle' => 'Weihnachtsmärchen 2026',
        'date' => '2026-12-02',
        'time' => '18:00',
        'venue' => 'Das Wormser',
        'ticket_url' => 'https://www.ticket-regional.de/stage_3769.php?timeID=1119485',
        'price' => '12,00 €',
        'price_note' => 'VVK',
        'status' => 'tickets',
    ],
    // Die Löffelliste — Dezember
    [
        'title' => 'Die Löffelliste',
        'subtitle' => 'Saisonstück 2026',
        'date' => '2026-12-11',
        'time' => '20:00',
        'venue' => 'Volksbühne — kleines Theater',
        'ticket_url' => 'https://www.ticket-regional.de/stage_7698.php?timeID=1139641',
        'price' => '25,00 €',
        'price_note' => 'VVK',
        'status' => 'tickets',
    ],
    [
        'title' => 'Die Löffelliste',
        'subtitle' => 'Saisonstück 2026',
        'date' => '2026-12-12',
        'time' => '20:00',
        'venue' => 'Volksbühne — kleines Theater',
        'ticket_url' => 'https://www.ticket-regional.de/stage_7698.php?timeID=1139642',
        'price' => '25,00 €',
        'price_note' => 'VVK',
        'status' => 'tickets',
    ],
    [
        'title' => 'Die Löffelliste',
        'subtitle' => 'Saisonstück 2026',
        'date' => '2026-12-18',
        'time' => '20:00',
        'venue' => 'Volksbühne — kleines Theater',
        'ticket_url' => 'https://www.ticket-regional.de/stage_7698.php?timeID=1139643',
        'price' => '25,00 €',
        'price_note' => 'VVK',
        'status' => 'tickets',
    ],
    [
        'title' => 'Die Löffelliste',
        'subtitle' => 'Saisonstück 2026',
        'date' => '2026-12-19',
        'time' => '20:00',
        'venue' => 'Volksbühne — kleines Theater',
        'ticket_url' => 'https://www.ticket-regional.de/stage_7698.php?timeID=1139644',
        'price' => '25,00 €',
        'price_note' => 'VVK',
        'status' => 'tickets',
    ],
];

/**
 * Vorstand des Vereins. Quelle: Impressum. `photo` ist optional und
 * kann später ergänzt werden — Initialen-Placeholder rendert automatisch.
 */
$vorstand = [
    [
        'role' => '1. Vorsitzender',
        'name' => 'Gerhard Baum',
        'note' => 'Verantwortlich i. S. d. § 55 Abs. 2 RStV.',
        'photo' => null,
    ],
    [
        'role' => '2. Vorsitzende',
        'name' => 'Nicole Schmitt',
        'note' => 'Koordination Spielzeit & Ensemble.',
        'photo' => null,
    ],
    [
        'role' => 'Schatzmeisterin',
        'name' => 'Roswitha Ramb',
        'note' => 'Finanzen, Mitgliederverwaltung, Förderung.',
        'photo' => null,
    ],
];

/**
 * Stationen der Vereinschronik. Bleibt leer, bis der Verein eine
 * autoritative Liste pflegt. Rendering nur, wenn \$chronik nicht leer.
 *
 * @var array<int, array{year:string, event:string}> $chronik
 */
$chronik = [];

$metaDefaults = [
    'title' => 'Volksbühne Worms 1908 e. V. — Theater seit 1908',
    'description' => 'Semiprofessionelles Theater in Worms: Spielzeit, Weihnachtsmärchen, Kulturarbeit und Engagement seit 1908.',
    'canonical' => '/',
    'og_type' => 'website',
    'og_locale' => 'de_DE',
    'og_image' => '/assets/img/og-image.svg',
    'og_image_width' => '1200',
    'og_image_height' => '630',
    'theme_color' => '#c31e2e',
    'robots' => 'index, follow, max-image-preview:large, max-snippet:-1',
];

$navigation = [
    ['key' => 'home', 'label' => 'Start', 'href' => '/'],
    ['key' => 'spielzeit', 'label' => 'Spielzeit', 'href' => '/spielzeit/'],
    ['key' => 'maerchen', 'label' => 'Weihnachtsmärchen', 'href' => '/weihnachtsmaerchen/'],
    ['key' => 'verein', 'label' => 'Verein', 'href' => '/verein/'],
    ['key' => 'kontakt', 'label' => 'Kontakt', 'href' => '/kontakt/'],
];