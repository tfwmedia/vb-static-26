<?php
declare(strict_types=1);

/** @var array<string, mixed> $siteConfig */
?>
<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <img class="footer-brand__mark" src="<?= e(asset('/assets/img/vb-logo.png')) ?>" width="432" height="324" alt="" loading="lazy" decoding="async">
                <p class="footer-brand__text">
                    Seit <?= e((string) $siteConfig['founding_year']) ?> steht die Volksbühne Worms für handgemachtes, nahbares Theater in der Region — von Klassikern bis zum Weihnachtsmärchen.
                </p>
                <div class="footer-socials" aria-label="Soziale Netzwerke">
                    <?php $fb = $siteConfig['social']['facebook'] ?? ''; ?>
                    <a href="<?= $fb !== '' ? e($fb) : '#' ?>" aria-label="Volksbühne Worms auf Facebook" <?= $fb === '' ? 'aria-disabled="true"' : 'rel="noopener noreferrer" target="_blank"' ?>>
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M22 12a10 10 0 1 0-11.6 9.9V14.9H7.9V12h2.5V9.8c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.2.2 2.2.2v2.4h-1.2c-1.2 0-1.6.8-1.6 1.5V12h2.7l-.4 2.9h-2.3V22A10 10 0 0 0 22 12Z"/>
                        </svg>
                    </a>
                    <?php $ig = $siteConfig['social']['instagram'] ?? ''; ?>
                    <a href="<?= $ig !== '' ? e($ig) : '#' ?>" aria-label="Volksbühne Worms auf Instagram" <?= $ig === '' ? 'aria-disabled="true"' : 'rel="noopener noreferrer" target="_blank"' ?>>
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <rect x="3" y="3" width="18" height="18" rx="5" ry="5" fill="none" stroke="currentColor" stroke-width="2"/>
                            <circle cx="12" cy="12" r="4" fill="none" stroke="currentColor" stroke-width="2"/>
                            <circle cx="17.5" cy="6.5" r="1.2"/>
                        </svg>
                    </a>
                </div>
            </div>

            <div class="footer-col">
                <h2>Spielzeit</h2>
                <ul class="footer-links">
                    <li><a href="<?= e(site_url('/')) ?>">Aktuelle Spielzeit</a></li>
                    <li><a href="<?= e(site_url('/spielzeit/')) ?>">Alle Produktionen</a></li>
                    <li><a href="<?= e(site_url('/weihnachtsmaerchen/')) ?>">Weihnachtsmärchen</a></li>
                    <li><a href="<?= e($siteConfig['spielzeit_ticket_url']) ?>" rel="noopener">Tickets kaufen</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h2>Verein</h2>
                <ul class="footer-links">
                    <li><a href="<?= e(site_url('/verein/')) ?>">Über uns</a></li>
                    <li><a href="<?= e(site_url('/verein/#vorstand')) ?>">Vorstand</a></li>
                    <li><a href="<?= e(site_url('/verein/#mitglied-werden')) ?>">Mitglied werden</a></li>
                    <li><a href="<?= e(site_url('/kontakt/')) ?>">Kontakt</a></li>
                </ul>
                <div class="footer-newsletter">
                    <p class="footer-newsletter__text">
                        Spielplan &amp; Premieren direkt ins Postfach — komplett werbefrei, jederzeit kündbar.
                    </p>
                    <a class="btn btn-light btn-sm" href="mailto:<?= e($siteConfig['email']) ?>?subject=<?= rawurlencode('Newsletter-Anmeldung Volksbühne Worms') ?>&amp;body=<?= rawurlencode('Hallo Volksbühne-Team, ' . "\n\n" . 'bitte tragt mich für den Newsletter ein.' . "\n\n" . 'Vielen Dank!') ?>">
                        Newsletter abonnieren
                    </a>
                </div>
            </div>

            <div class="footer-col">
                <h2>Kontakt</h2>
                <address>
                    <?= e($siteConfig['address']['name']) ?><br>
                    <?= e($siteConfig['address']['street']) ?><br>
                    <?= e($siteConfig['address']['postal_city']) ?>
                </address>
                <p style="margin-top: var(--space-sm);">
                    <a href="tel:<?= e($siteConfig['phone_href']) ?>"><?= e($siteConfig['phone_display']) ?></a><br>
                    <a href="mailto:<?= e($siteConfig['email']) ?>"><?= e($siteConfig['email']) ?></a>
                </p>
                <ul class="footer-links" style="margin-top: var(--space-sm);">
                    <li><a href="<?= e(site_url('/impressum/')) ?>">Impressum</a></li>
                    <li><a href="<?= e(site_url('/datenschutz/')) ?>">Datenschutz</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; <?= e((string) date('Y')) ?> <?= e($siteConfig['name']) ?>. Alle Rechte vorbehalten.</p>
            <p class="footer-bottom__legal">
                <a href="<?= e(site_url('/impressum/')) ?>">Impressum</a>
                <a href="<?= e(site_url('/datenschutz/')) ?>">Datenschutz</a>
                <a href="<?= e(site_url('/sitemap.xml')) ?>">Sitemap</a>
            </p>
        </div>
    </div>
</footer>
</body>
</html>