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
        <div class="container prose prose-legal">
            <h2>Anbieter</h2>
            <p>
                <?= e($siteConfig['name']) ?><br>
                <?= e($siteConfig['address']['street']) ?><br>
                <?= e($siteConfig['address']['postal_city']) ?>
            </p>

            <h2>Kontakt</h2>
            <p>
                Telefon: <a href="tel:<?= e($siteConfig['phone_href']) ?>"><?= e($siteConfig['phone_display']) ?></a><br>
                E-Mail: <a href="mailto:<?= e($siteConfig['email']) ?>"><?= e($siteConfig['email']) ?></a>
            </p>

            <h2>Vertretungsberechtigt</h2>
            <p>Der vertretungsberechtigte Vorstand der Volksbühne Worms 1908 e. V.</p>

            <h2>Haftung für Inhalte</h2>
            <p>
                Die Inhalte dieser Website wurden mit größter Sorgfalt erstellt. Für die Richtigkeit,
                Vollständigkeit und Aktualität der Inhalte übernehmen wir jedoch keine Gewähr.
            </p>

            <h2>Haftung für Links</h2>
            <p>
                Diese Website enthält Links zu externen Websites Dritter. Auf deren Inhalte haben wir keinen Einfluss.
                Für die Inhalte der verlinkten Seiten ist stets der jeweilige Anbieter verantwortlich.
            </p>

            <h2>Urheberrecht</h2>
            <p>
                Die durch die Seitenbetreiber erstellten Inhalte und Werke auf diesen Seiten unterliegen dem deutschen
                Urheberrecht. Eine Vervielfältigung, Bearbeitung, Verbreitung und jede Art der Verwertung außerhalb der
                Grenzen des Urheberrechts bedürfen der schriftlichen Zustimmung des jeweiligen Autors bzw. Erstellers.
            </p>
        </div>
    </section>
</main>
<?php require __DIR__ . '/partials/layout-end.php'; ?>
