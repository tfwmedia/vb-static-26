# Volksbühne Worms 1908 e. V. — Website (Astro v2)

Statische Website des Theatervereins, gebaut mit **Astro 5**. Komplett-Redesign 2026 ("Programmheft"): CI-Rot auf warmem Papier, HelveticaNeue-Extrabold-Displays, tabellarischer Spielplan. Inhalte, Termine und Rechtstexte sind 1:1 aus der Vorgänger-Website (PHP, Stand `91c9d6a`) übernommen.

## Quick Start

```bash
npm install
npm run dev        # Dev-Server (http://localhost:4321)
npm run build      # Statischer Build nach dist/
npm run preview    # dist/ lokal servieren
npm run check      # Astro/TypeScript-Prüfung
npm run sync:legal # Rechtstexte von der Live-Site in content/legal/ ziehen
node scripts/screenshots.mjs   # Visueller Smoke-Test (benötigt laufenden Preview-Server)
```

## Struktur

| Pfad | Inhalt |
|------|--------|
| `src/data/` | Alle Inhalte als typisierte Daten: Site-Konfiguration, 15 Termine, Produktionen, Vorstand |
| `src/pages/` | 8 Seiten: Start, Spielzeit, Weihnachtsmärchen, Verein, Kontakt, Impressum, Datenschutz, 404 |
| `src/components/` | Header, Footer, Countdown, Spielplan-Liste, Produktions-Karten, Status-Chips |
| `src/styles/global.css` | Design-System (Tokens, Komponenten, Motion) |
| `src/lib/format.ts` | Deutsche Datums-/Text-Formatter (1:1-Port der PHP-Helper) |
| `src/lib/legal.ts` | Lädt Rechtstext-Fragmente aus `content/legal/` zur Build-Zeit |
| `content/legal/` | Impressum + Datenschutz als HTML-Fragmente (Quelle: Live-Site, `npm run sync:legal`) |
| `public/` | Bilder (`assets/img/`), Fonts (`assets/fonts/`), robots.txt, sitemap.xml, `.htaccess` |

## Deployment

GitHub Actions baut und prüft bei jedem Push (Routen, Assets, Inhalts-Needles) und deployt `dist/` per FTP auf den Apache-Host. Konfiguration über Secrets (`FTP_*`), siehe `.github/workflows/deploy.yml`.

Wichtig für den Live-Betrieb (Apache):

- Schöne URLs (`/spielzeit/`) durch `build.format: 'directory'` (`spielzeit/index.html`).
- `/tickets-kaufen/` → 301 auf Ticket-Regional per `public/.htaccess`.
- `ErrorDocument 404 /404.html`, Security-Header (CSP, HSTS, X-Frame-Options) ebenfalls in der `.htaccess`.

## DSGVO

Keine externen Requests der Seite selbst: Fonts selbstgehostet (Inter, OFL), keine Tracker/Analytics, keine Einbindungen (Maps nur als eigenes SVG + Link). Rechtstexte 1:1 vom Verein. CSP `default-src 'self'`.

## Dokumentation

Die vollständige Projektdokumentation liegt unter [`docs/`](./docs/README.md) (nur im Repository, nicht Teil des Deployments): Architektur, Content-Workflow, Design-System, Development, Deployment, SEO/Responsive-Audit.

## Historie

Die Vorgängerversion (serverseitig gerendertes PHP, Git-Stand `91c9d6a`) ist unter [`docs/legacy/`](./docs/legacy/README.md) dokumentiert — Architektur-Referenz, die beschriebenen PHP-Pfade existieren nicht mehr.
