<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$meta = meta([
    'title' => 'Impressum | Volksbühne Worms 1908 e. V.',
    'description' => 'Impressum der Volksbühne Worms 1908 e. V. mit Kontakt- und Adressdaten.',
    'canonical' => site_url('/impressum/'),
]);
$activeNav = 'impressum';
$bodyClass = 'page-legal';
$structuredData = null;
$legalContent = @file_get_contents(__DIR__ . '/content/legal/impressum.html');
if (!is_string($legalContent) || trim($legalContent) === '') {
    $legalContent = '<p>Der Impressumstext konnte lokal nicht geladen werden.</p>';
} else {
    $legalContent = sanitize_legal_html($legalContent);
}

if (trim($legalContent) === '') {
    $legalContent = '<p>Der Impressumstext ist derzeit nicht verfügbar.</p>';
}

require __DIR__ . '/partials/layout-start.php';
?>
<main id="main-content">
    <section class="hero hero-inner">
        <div class="container">
            <p class="eyebrow">Rechtliches</p>
            <h1>Impressum</h1>
            <p class="lead">Angaben gemäß § 5 TMG</p>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="prose prose-legal">
                <?= $legalContent ?>
            </div>
        </div>
    </section>
</main>
<?php require __DIR__ . '/partials/layout-end.php'; ?>
