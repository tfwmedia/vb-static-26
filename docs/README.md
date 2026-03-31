# Volksbühne Worms Website — Projektdokumentation

Willkommen in der technischen Dokumentation der Website **vb-static-26**.

Diese Dokumentation beschreibt Aufbau, lokale Entwicklung, Deployment-nahe Details und Content-Workflows.

## Inhalte

- [`architecture.md`](./architecture.md) — Projektstruktur, Rendering-Fluss, Routing und Kernkomponenten
- [`development.md`](./development.md) — Lokales Setup, nützliche Befehle und Qualitätssicherung
- [`content-workflow.md`](./content-workflow.md) — Pflege von Inhalten (v. a. Rechtstexte und Weihnachtsmärchen)
- [`design-changes.md`](./design-changes.md) — Dokumentation der visuellen und UX-seitigen Redesign-Änderungen

## Quick Start

Voraussetzung: **PHP 8.1+**

```bash
php -S 127.0.0.1:8080 scripts/ci-router.php
```

Danach im Browser öffnen:

- `http://127.0.0.1:8080/`
- `http://127.0.0.1:8080/weihnachtsmaerchen/`
- `http://127.0.0.1:8080/impressum/`
- `http://127.0.0.1:8080/datenschutz/`

## Projektziel (Kurzfassung)

Die Anwendung ist eine schlanke, serverseitig gerenderte PHP-Website für die Volksbühne Worms mit:

- Startseite,
- Weihnachtsmärchen-Landingpage,
- statisch eingebundenen Rechtstexten (Impressum/Datenschutz),
- gemeinsam genutzten Layout- und SEO-Bausteinen.
