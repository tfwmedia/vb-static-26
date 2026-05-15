# Optimierungs-Plan — Statusübersicht

Dieser Dokumentiert den Umsetzungsstand der Optimierungen für die Volksbühne Worms Website. Basierend auf Webdesign-, SEO- und Content-Best-Practices für 2026.

## Bereits umgesetzt

### Performance & Core Web Vitals

| Maßnahme | Status | Details |
|----------|--------|---------|
| CSS-Minifizierung | ✅ Umgesetzt | esbuild-Build: `main.css` → `main.min.css` |
| JS-Minifizierung | ✅ Umgesetzt | esbuild-Build: `main.js` → `main.min.js` |
| WebP-Konvertierung | ✅ Umgesetzt | Automatischer Build per `cwebp`, Ausnahmen für Icons/Logos |
| Cache-Busting | ✅ Umgesetzt | `asset()`-Helper mit `filemtime`-Parameter |
| Preload CSS | ✅ Umgesetzt | `<link rel="preload">` für Stylesheet |
| Deferred JS | ✅ Umgesetzt | `<script defer>` für JavaScript |
| Lazy Loading Bilder | ✅ Umgesetzt | `loading="lazy"` für unter dem Fold liegende Bilder |
| Bildoptimierung im CI | ✅ Umgesetzt | `jpegtran` (lossless JPEG), `optipng` (lossless PNG) in GitHub Actions |
| Hero-Bild Priorisierung | ✅ Umgesetzt | `fetchpriority="high"` für Above-the-Fold Bild |

### SEO & Strukturierte Daten

| Maßnahme | Status | Details |
|----------|--------|---------|
| Semantic HTML | ✅ Umgesetzt | `<header>`, `<nav>`, `<main>`, `<footer>`, `<section>`, `<aside>`, `<address>` |
| Heading-Hierarchie | ✅ Umgesetzt | Eine `H1` pro Seite, logische Abfolge |
| Kanonische URLs | ✅ Umgesetzt | `<link rel="canonical">` pro Seite |
| Open Graph Meta | ✅ Umgesetzt | `og:title`, `og:description`, `og:type`, `og:url`, `og:image`, `og:locale` |
| Twitter Cards | ✅ Umgesetzt | `twitter:card`, `twitter:title`, `twitter:description`, `twitter:image` |
| Schema.org EventSeries | ✅ Umgesetzt | In `weihnachtsmaerchen.php` mit `subEvent`, `AggregateOffer` |
| Schema.org PerformingGroup | ✅ Umgesetzt | In `index.php` und `weihnachtsmaerchen.php` |
| Schema.org WebSite | ✅ Umgesetzt | In `index.php` |
| LocalBusiness | ✅ Umgesetzt | Über `additionalType: LocalBusiness` in `PerformingGroup` |
| Saubere URLs | ✅ Umgesetzt | `/weihnachtsmaerchen/`, `/impressum/`, `/datenschutz/` per `.htaccess` |

### Design & UX

| Maßnahme | Status | Details |
|----------|--------|---------|
| Responsive Design | ✅ Umgesetzt | 3 Breakpoints: Mobile, 48rem (768px), 80rem (1280px) |
| Mobile Navigation | ✅ Umgesetzt | Hamburger-Menü mit Overlay, Escape-Taste, Klick-außen-Schließen |
| Sticky Header | ✅ Umgesetzt | `position: sticky`, Glassmorphism mit `backdrop-filter` |
| Touch-Targets | ✅ Umgesetzt | `min-height: 48px` für Navigations-Links |
| Skip-Link | ✅ Umgesetzt | `<a href="#main-content">` für Barrierefreiheit |
| Focus-Styles | ✅ Umgesetzt | `outline: 3px solid var(--brand)` für `focus-visible` |
| Reduced Motion | ✅ Umgesetzt | `@media (prefers-reduced-motion: reduce)` deaktiviert alle Animationen |
| Reveal-Animationen | ✅ Umgesetzt | `IntersectionObserver` mit `data-reveal`, 15% Schwellwert |
| Design-Tokens | ✅ Umgesetzt | CSS Custom Properties für Farben, Spacing, Radien, Schatten |
| Responsive Typografie | ✅ Umgesetzt | `clamp()` für alle Schriftgrößen |

### Sicherheit

| Maßnahme | Status | Details |
|----------|--------|---------|
| Content-Security-Policy | ✅ Umgesetzt | Default `'self'`, keine externen Ressourcen |
| HSTS | ✅ Umgesetzt | `max-age=31536000; includeSubDomains` |
| X-Frame-Options | ✅ Umgesetzt | `DENY` |
| XSS-Schutz | ✅ Umgesetzt | `e()`-Helper für alle dynamischen Ausgaben |
| HTML-Sanitizing | ✅ Umgesetzt | `sanitize_legal_html()` für Rechtstexte |

### CI/CD & Testing

| Maßnahme | Status | Details |
|----------|--------|---------|
| GitHub Actions Pipeline | ✅ Umgesetzt | Test + Deploy Jobs |
| PHP-Syntax-Prüfung | ✅ Umgesetzt | `php -l` für alle `.php`-Dateien |
| Route-Tests | ✅ Umgesetzt | HTTP 200 für alle 4 Routen |
| Asset-Tests | ✅ Umgesetzt | Erreichbarkeit kritischer Assets |
| 404-Handling-Test | ✅ Umgesetzt | Unbekannte Route → HTTP 404 |
| FTP-Deploy mit Retry | ✅ Umgesetzt | 3 Versuche, Fallback-Protokoll |
| Visuelle Tests | ✅ Umgesetzt | Playwright (Desktop + iPhone 13) |

## Noch offen

| Maßnahme | Priorität | Beschreibung |
|----------|-----------|-------------|
| AVIF-Unterstützung | Niedrig | AVIF als zusätzliches Bildformat neben WebP |
| Trust-Elemente | Mittel | Testimonials, "Ausverkauft"-Hinweise, Social Proof |
| Sitemap.xml | Mittel | Automatisch generierte Sitemap für Google Search Console |
| robots.txt | Mittel | Explizite Steuerung für Suchmaschinen-Crawler |
| Erweiterte A11y | Mittel | Keyboard-Navigation für alle interaktiven Elemente prüfen |
| Lighthouse-Audit | Niedrig | Baseline-Metriken dokumentieren und überwachen |
| Performance-Budget | Niedrig | Definierte Schwellwerte für LCP, FID, CLS |
