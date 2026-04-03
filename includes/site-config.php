<?php
declare(strict_types=1);

$siteConfig = [
    'name' => 'Volksbühne Worms 1908 e. V.',
    'email' => 'info@volksbuehne-worms.de',
    'phone_display' => '06241 51429',
    'phone_href' => '+49624151429',
    'maerchen_contact_email' => 'kontakt@volksbuehne-worms.de',
    'maerchen_contact_phone_display' => '0152 57204453',
    'maerchen_contact_phone_href' => '+4915257204453',
    'address' => [
        'name' => 'Volksbühne Worms 1908 e. V.',
        'street' => 'Würdtweinstraße 11',
        'postal_city' => '67549 Worms',
    ],
];

$metaDefaults = [
    'title' => 'Volksbühne Worms 1908 e. V.',
    'description' => 'Theater seit 1908: Spielzeit, Weihnachtsmärchen und Kultur für Worms und die Region.',
    'canonical' => '/',
    'og_type' => 'website',
    'og_locale' => 'de_DE',
    'og_image' => '/assets/img/vorhang.webp',
];

$navigation = [
    ['key' => 'home', 'label' => 'Start', 'href' => '/'],
    ['key' => 'maerchen', 'label' => 'Weihnachtsmärchen', 'href' => '/weihnachtsmaerchen/'],
];
