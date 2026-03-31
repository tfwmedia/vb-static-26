<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$meta = meta([
    'title' => 'Datenschutz | Volksbühne Worms 1908 e. V.',
    'description' => 'Datenschutzhinweise der Volksbühne Worms 1908 e. V.',
    'canonical' => site_url('/datenschutz/'),
]);
$activeNav = 'datenschutz';
$bodyClass = 'page-legal';
$structuredData = null;
$legalContent = @file_get_contents(__DIR__ . '/content/legal/datenschutz.html');
if (!is_string($legalContent) || trim($legalContent) === '') {
    $legalContent = '<p>Der Datenschutztext konnte lokal nicht geladen werden.</p>';
} else {
    $legalContent = sanitize_legal_html($legalContent);
}

if (trim($legalContent) === '') {
    $legalContent = '<p>Der Datenschutztext ist derzeit nicht verfügbar.</p>';
}

require __DIR__ . '/partials/layout-start.php';
?>
<main id="main-content">
    <section class="hero hero-inner">
        <div class="container">
            <p class="eyebrow">Rechtliches</p>
            <h1>Datenschutz</h1>
            <p class="lead">Hinweise zur Verarbeitung personenbezogener Daten</p>
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
