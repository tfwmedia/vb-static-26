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
        <div class="container prose prose-legal">
            <h2>1. Verantwortlicher</h2>
            <p>
                <?= e($siteConfig['name']) ?><br>
                <?= e($siteConfig['address']['street']) ?><br>
                <?= e($siteConfig['address']['postal_city']) ?><br>
                E-Mail: <a href="mailto:<?= e($siteConfig['email']) ?>"><?= e($siteConfig['email']) ?></a><br>
                Telefon: <a href="tel:<?= e($siteConfig['phone_href']) ?>"><?= e($siteConfig['phone_display']) ?></a>
            </p>

            <h2>2. Zugriffsdaten</h2>
            <p>
                Beim Aufruf dieser Website können durch den Hosting-Provider technisch notwendige Daten
                (z. B. IP-Adresse, Datum/Uhrzeit, angeforderte Seite, Browsertyp) in Server-Logfiles gespeichert werden.
                Die Verarbeitung erfolgt auf Grundlage unseres berechtigten Interesses an einem sicheren und stabilen Betrieb
                der Website (Art. 6 Abs. 1 lit. f DSGVO).
            </p>

            <h2>3. Kontaktaufnahme</h2>
            <p>
                Wenn Sie uns per E-Mail kontaktieren, verarbeiten wir die von Ihnen übermittelten Daten ausschließlich zur
                Bearbeitung Ihrer Anfrage. Rechtsgrundlage ist Art. 6 Abs. 1 lit. b DSGVO (vorvertragliche Kommunikation)
                bzw. Art. 6 Abs. 1 lit. f DSGVO.
            </p>

            <h2>4. Externe Links und Ticketing</h2>
            <p>
                Für den Ticketkauf verlinken wir auf einen externen Anbieter. Beim Aufruf dieser externen Seiten gelten
                die Datenschutzbestimmungen des jeweiligen Betreibers.
            </p>

            <h2>5. Ihre Rechte</h2>
            <p>
                Sie haben im Rahmen der gesetzlichen Vorgaben das Recht auf Auskunft, Berichtigung, Löschung,
                Einschränkung der Verarbeitung, Datenübertragbarkeit sowie Widerspruch gegen die Verarbeitung
                Ihrer personenbezogenen Daten.
            </p>

            <h2>6. Aktualität</h2>
            <p>Diese Datenschutzhinweise wurden zuletzt am <?= e(date('d.m.Y')) ?> aktualisiert.</p>
        </div>
    </section>
</main>
<?php require __DIR__ . '/partials/layout-end.php'; ?>
