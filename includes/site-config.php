<?php
declare(strict_types=1);

$siteConfig = [
    'name' => 'Volksbühne Worms 1908 e. V.',
    'base_url' => 'https://volksbuehne-worms.de',
    'email' => 'info@volksbuehne-worms.de',
    'phone_display' => '06241 51429',
    'phone_href' => '+49624151429',
    'address' => [
        'name' => 'Volksbühne Worms 1908 e. V.',
        'street' => 'Würdtweinstraße 11',
        'postal_city' => '67549 Worms',
    ],
    'ticket_url' => 'https://www.ticket-regional.de/events_info.php?eventID=235101',
];

$metaDefaults = [
    'title' => 'Volksbühne Worms 1908 e. V.',
    'description' => 'Theater seit 1908: Spielzeit, Weihnachtsmärchen und Kultur für Worms und die Region.',
    'canonical' => 'https://volksbuehne-worms.de/',
    'og_type' => 'website',
    'og_locale' => 'de_DE',
    'og_image' => '/assets/img/vorhang.jpg',
];

$navigation = [
    ['key' => 'home', 'label' => 'Start', 'href' => '/'],
    ['key' => 'maerchen', 'label' => 'Weihnachtsmärchen', 'href' => '/weihnachtsmaerchen/'],
    ['key' => 'impressum', 'label' => 'Impressum', 'href' => '/impressum/'],
    ['key' => 'datenschutz', 'label' => 'Datenschutz', 'href' => '/datenschutz/'],
];
