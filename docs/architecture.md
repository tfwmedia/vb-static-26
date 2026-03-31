# Architektur

## Überblick

Die Website ist als **klassische PHP-Multi-Page-Anwendung** aufgebaut (ohne Framework).
Jede Seite rendert serverseitig HTML und nutzt gemeinsame Includes für Konfiguration, Helper und Layout.

Wichtige Ziele der Architektur:

- einfache Wartbarkeit,
- geringe Komplexität,
- SEO-freundliche, statische URLs,
- klare Trennung zwischen Seiteninhalten und wiederverwendbarem Layout.

## Verzeichnisstruktur (relevant)

```text
.
├── index.php
├── weihnachtsmaerchen.php
├── impressum.php
├── datenschutz.php
├── includes/
│   ├── bootstrap.php
│   ├── helpers.php
│   └── site-config.php
├── partials/
│   ├── layout-start.php
│   └── layout-end.php
├── assets/
│   ├── css/main.css
│   ├── js/main.js
│   └── img/*
├── content/
│   └── legal/
│       ├── impressum.html
│       └── datenschutz.html
├── scripts/
│   ├── ci-router.php
│   └── sync-legal-content.php
└── .htaccess
```

## Rendering-Fluss pro Seite

1. Seite lädt `includes/bootstrap.php`.
2. Bootstrap bindet `site-config.php` + `helpers.php`.
3. Seite setzt Meta-Informationen (`meta(...)`), aktive Navigation und optionale strukturierte Daten.
4. Seite rendert `partials/layout-start.php` (Head, Header, Navigation).
5. Seite rendert den individuellen Main-Content.
6. Seite rendert `partials/layout-end.php` (Footer, Dokumentende).

## Routing

### Produktion (`.htaccess`)

- vorhandene Dateien/Ordner werden direkt ausgeliefert,
- kanonische Routen werden auf PHP-Dateien gemappt:
  - `/` → `index.php`
  - `/weihnachtsmaerchen/` → `weihnachtsmaerchen.php`
  - `/impressum/` → `impressum.php`
  - `/datenschutz/` → `datenschutz.php`
- direkte `.php`-Aufrufe werden auf die saubere URL umgeleitet (301).

### Fallback in `index.php`

Falls ein Hoster alle Requests über `index.php` leitet, erkennt die Datei bestimmte Pfade selbst und lädt die passende Zielseite.

### Lokal/CI (`scripts/ci-router.php`)

Der Router für den eingebauten PHP-Server bildet dieselben Routen nach und liefert bei unbekannten Pfaden ein klares `404 Not Found`.

## Konfiguration

`includes/site-config.php` enthält:

- Vereinsname,
- Kontaktinformationen,
- Weihnachtsmärchen-Kontaktdaten,
- Adressdaten,
- Meta-Defaults,
- Navigationsdefinition.

## Helper-Funktionen

`includes/helpers.php` stellt zentrale Utilities bereit:

- `e(...)`: HTML-Escaping,
- `site_url(...)`: interne URL-Normalisierung,
- `asset(...)`: Asset-Pfade mit Cache-Busting per `filemtime`,
- `absolute_url(...)`: relative/absolute URL-Auflösung,
- `meta(...)`: Merge aus Default- und Seiten-Meta,
- `sanitize_legal_html(...)`: Sanitizing von Rechtstext-HTML,
- `json_ld(...)`: JSON-LD-Ausgabe.

## Layout & Frontend

- `partials/layout-start.php`: HTML-Head, SEO/OG/Twitter-Meta, Header, Navigation.
- `partials/layout-end.php`: Footer mit Kontakt, Adresse, Legal-Links.
- `assets/css/main.css`: zentrales Designsystem (Variablen, Layout, responsive Regeln).
- `assets/js/main.js`: mobile Navigation + Reveal-Animation per IntersectionObserver.

## Rechtstexte

- Inhalte liegen als HTML-Fragmente in `content/legal/*.html`.
- `impressum.php` und `datenschutz.php` laden diese Fragmente und sanitizen sie vor Ausgabe.
- Bei fehlenden Inhalten gibt es robuste Fallback-Meldungen.
