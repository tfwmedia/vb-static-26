<?php
declare(strict_types=1);
?>
<footer class="site-footer">
    <div class="container footer-grid">
        <section aria-labelledby="footer-contact">
            <h2 id="footer-contact">Kontakt</h2>
            <p>
                <a href="mailto:<?= e($siteConfig['email']) ?>"><?= e($siteConfig['email']) ?></a><br>
                <a href="tel:<?= e($siteConfig['phone_href']) ?>"><?= e($siteConfig['phone_display']) ?></a>
            </p>
        </section>

        <section aria-labelledby="footer-address">
            <h2 id="footer-address">Adresse</h2>
            <address>
                <?= e($siteConfig['address']['name']) ?><br>
                <?= e($siteConfig['address']['street']) ?><br>
                <?= e($siteConfig['address']['postal_city']) ?>
            </address>
        </section>

        <section aria-labelledby="footer-links">
            <h2 id="footer-links">Links</h2>
            <ul class="footer-links">
                <li><a href="<?= e(site_url('/weihnachtsmaerchen/')) ?>">Weihnachtsmärchen</a></li>
                <li><a href="<?= e(site_url('/impressum/')) ?>">Impressum</a></li>
                <li><a href="<?= e(site_url('/datenschutz/')) ?>">Datenschutz</a></li>
            </ul>
        </section>
    </div>

    <div class="container footer-bottom">
        <p>&copy; <?= e((string) date('Y')) ?> <?= e($siteConfig['name']) ?></p>
    </div>
</footer>
</body>
</html>
