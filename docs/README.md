# Projektdokumentation — Volksbühne Worms 1908 e. V. (Astro v2)

Technische Dokumentation der Website **vb-static-26**: statische Astro-5-Site für den Theaterverein Volksbühne Worms 1908 e. V., produktiv unter https://www.volksbuehne-worms.de.

Diese Dokumentation lebt **ausschließlich im Repository** — deployed wird nur `dist/`, `docs/` ist im Web nicht abrufbar.

## Dokumente

| Dokument | Inhalt |
|----------|--------|
| [architecture.md](./architecture.md) | Stack, Verzeichnisstruktur, Datenfluss, Rendering, Komponenten, Helper-Ports |
| [content-workflow.md](./content-workflow.md) | Inhalte pflegen: Termine, Produktionen, Vorstand, Rechtstexte-Sync, neue Seite anlegen |
| [design-system.md](./design-system.md) | Designsprache "Programmheft", Tokens (Farben/Typo/Raum), Komponenten, Motion & A11y |
| [development.md](./development.md) | Quick Start, Befehle, Checks, Screenshots, Audit-Skript |
| [deployment.md](./deployment.md) | CI-Pipeline, FTP-Deploy, Redirects, Server-Verhalten, Live-Verifikation, Rollback |
| [seo-responsive.md](./seo-responsive.md) | Audit-Skript, geprüfte Kriterien, OG-Image-Prozess, Standards für neue Seiten |

## Kurzüberblick

| Layer | Technologie |
|-------|-------------|
| Framework | Astro 5.18 (statisch, kein Client-Framework) |
| Sprachen | TypeScript strict, Vanilla-JS (2 Dateien, extern geladen) |
| Styling | Ein globales CSS-Design-System (`src/styles/global.css`) |
| Bilder/Fonts | Alles selbstgehostet in `public/assets/` — keine externen Requests (DSGVO) |
| CI/CD | GitHub Actions: Build → Checks → Audit → FTP-Deploy von `dist/` |
| Hosting | Apache mit `public/.htaccess`, FTP |

## Grundprinzipien

1. **Inhalte sind Daten**: Alle Texte, Termine, Kontaktdaten liegen typisiert in `src/data/` — nie im Markup verstreut.
2. **1:1-Inhaltstreue**: Redaktionelle Texte und Rechtstexte werden nicht umformuliert (Quelle: Vorgängersite + Verein).
3. **DSGVO by default**: Keine externen Requests, keine Tracker, keine Cookies.
4. **Audit before deploy**: SEO-/Responsive-Audit blockiert das Deploy bei HOCH/KRITISCH-Funden.

## Historie

Die Vorgängerversion (serverseitig gerendertes PHP, Stand `91c9d6a`) ist unter [legacy/](./legacy/README.md) dokumentiert — als Architektur-Referenz; die beschriebenen Pfade (`includes/`, `partials/`, `*.php`) existieren nicht mehr.
