# Volksbühne Worms 1908 e. V. — Projektdokumentation

Technische Dokumentation der Website **vb-static-26**: eine schlanke, serverseitig gerenderte PHP-Website für den Theaterverein Volksbühne Worms 1908 e. V.

## Projektübersicht

Die Website umfasst vier Seiten und einen externen Redirect:

| Seite | Route | Datei | Beschreibung |
|-------|-------|-------|-------------|
| Startseite | `/` | `index.php` | Hero-Bereich, Weihnachtsmärchen- und Saisonstück-Highlight, Kontakt |
| Weihnachtsmärchen | `/weihnachtsmaerchen/` | `weihnachtsmaerchen.php` | Landingpage mit Event-Details, Schema.org EventSeries |
| Impressum | `/impressum/` | `impressum.php` | Rechtstext, geladen aus HTML-Fragment |
| Datenschutz | `/datenschutz/` | `datenschutz.php` | Rechtstext, geladen aus HTML-Fragment |
| Tickets kaufen | `/tickets-kaufen/` | — | 301-Redirect zu ticket-regional.de (Eventtyp-Übersicht 1883) |

## Technologie-Stack

| Schicht | Technologie |
|---------|-------------|
| Server | PHP 8.1+ (kein Framework, kein Composer) |
| Frontend | Vanilla HTML/CSS/JS |
| Build | esbuild (CSS/JS-Minifizierung), cwebp (WebP-Konvertierung) |
| Testing | Playwright (visuell), PHP-Syntax-Check (CI) |
| CI/CD | GitHub Actions (Tests + FTP-Deploy) |
| Hosting | Apache mit `.htaccess`, FTP-Deployment |
| Fonts | Playfair Display (selbstgehostet, 8 WOFF2-Subset-Dateien) |
| Sicherheit | CSP, HSTS, X-Frame-Options, HTML-Sanitizing, XSS-Schutz |

## Quick Start

Voraussetzung: **PHP 8.1+**

Entwicklungsserver starten:

```bash
php -S 127.0.0.1:8080 router.php
```

Oder mit CI-Router:

```bash
php -S 127.0.0.1:8080 scripts/ci-router.php
```

Verfügbare Routen im Browser:

- `http://127.0.0.1:8080/` — Startseite
- `http://127.0.0.1:8080/weihnachtsmaerchen/` — Weihnachtsmärchen
- `http://127.0.0.1:8080/impressum/` — Impressum
- `http://127.0.0.1:8080/datenschutz/` — Datenschutz
- `http://127.0.0.1:8080/tickets-kaufen/` — 301 → ticket-regional.de

## Dokumentation

| Dokument | Inhalt |
|----------|--------|
| [architecture.md](./architecture.md) | Projektstruktur, Rendering-Fluss, Routing, Konfiguration, Helper-Funktionen, Layout-System, strukturierte Daten |
| [development.md](./development.md) | Lokales Setup, Build-Prozess, Testing, Konventionen, neue Seite hinzufügen |
| [content-workflow.md](./content-workflow.md) | Content-Pflege: Weihnachtsmärchen aktualisieren, Rechtstexte synchronisieren, Redaktionsablauf |
| [design-changes.md](./design-changes.md) | Design-System: CSS Custom Properties, Typografie, Komponenten, Responsive Breakpoints, Animationen |
| [security.md](./security.md) | OWASP Top 10 Abdeckung, Security-Header, DSGVO-Konformität, HTML-Sanitizing, Secret Management |
| [optimization-plan.md](./optimization-plan.md) | Umsetzungsstatus der Optimierungen (erledigt/offen) |
| [agency-project-plan.md](./agency-project-plan.md) | Implementierungs-Roadmap mit Status aller Phasen |
| [mailboxes.md](./mailboxes.md) | E-Mail-Postfächer und Weiterleitungen bei IONOS |
