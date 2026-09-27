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
 * Nächste Vorstellungen — wird auf der Startseite und im Spielzeit-Kontext
 * ausgespielt. Reihenfolge = Anzeigereihenfolge (aufsteigend nach Datum).
 * `ticket_url` ist optional; ohne URL erscheint kein Ticket-Button.
 */
$upcomingEvents = [
    [
        'title' => 'Die Bremer Stadtmusikanten',
        'subtitle' => 'Weihnachtsmärchen 2026',
        'date' => '2026-12-01',
        'time' => '18:00',
        'venue' => 'Das Wormser',
        'ticket_url' => 'https://www.ticket-regional.de/events_info.php?eventID=257737',
        'price' => '12,00 €',
        'price_note' => 'Vorverkauf',
        'status' => 'tickets',
    ],
    [
        'title' => 'Die Bremer Stadtmusikanten',
        'subtitle' => 'Weihnachtsmärchen 2026',
        'date' => '2026-12-02',
        'time' => '18:00',
        'venue' => 'Das Wormser',
        'ticket_url' => 'https://www.ticket-regional.de/events_info.php?eventID=257737',
        'price' => '12,00 €',
        'price_note' => 'Vorverkauf',
        'status' => 'tickets',
    ],
    [
        'title' => 'Die Bremer Stadtmusikanten',
        'subtitle' => 'Weihnachtsmärchen 2026 · Zusatzvorstellung',
        'date' => '2026-12-05',
        'time' => '18:00',
        'venue' => 'Das Wormser',
        'ticket_url' => 'https://www.ticket-regional.de/events_info.php?eventID=257737',
        'price' => '12,00 €',
        'price_note' => 'Vorverkauf',
        'status' => 'few_left',
    ],
    [
        'title' => 'Die Löffelliste',
        'subtitle' => 'Saisonstück — Vorstellung',
        'date' => '2026-10-25',
        'time' => '19:30',
        'venue' => 'Das Wormser',
        'ticket_url' => 'https://www.ticket-regional.de/events.php?mysearchSpecificType=eventtype&mysearchSpecificID=1883',
        'price' => 'ab 14,00 €',
        'price_note' => 'VVK',
        'status' => 'tickets',
    ],
    [
        'title' => 'Die Löffelliste',
        'subtitle' => 'Saisonstück — Vorstellung',
        'date' => '2026-11-08',
        'time' => '19:30',
        'venue' => 'Das Wormser',
        'ticket_url' => 'https://www.ticket-regional.de/events.php?mysearchSpecificType=eventtype&mysearchSpecificID=1883',
        'price' => 'ab 14,00 €',
        'price_note' => 'VVK',
        'status' => 'premiere',
    ],
    [
        'title' => 'Die Bremer Stadtmusikanten',
        'subtitle' => 'Weihnachtsmärchen 2026 · Premiere',
        'date' => '2026-11-29',
        'time' => '18:00',
        'venue' => 'Das Wormser',
        'ticket_url' => 'https://www.ticket-regional.de/events_info.php?eventID=257737',
        'price' => '12,00 €',
        'price_note' => 'Vorverkauf',
        'status' => 'premiere',
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