# Entwicklung

## Voraussetzungen

- PHP **8.1 oder neuer**
- optional: `wget` (wird vom Sync-Script als Fallback verwendet)

## Lokaler Start

Projektverzeichnis öffnen und den integrierten PHP-Server mit Router starten:

```bash
php -S 127.0.0.1:8080 router.php
```

Danach im Browser aufrufen:
- `http://127.0.0.1:8080/`
- `http://127.0.0.1:8080/weihnachtsmaerchen/`

## Build-Prozess (Optimierung 2026)

Das Projekt verfügt über einen automatisierten Build-Prozess für Assets:

```bash
npm install
node scripts/build.js
```

Dieser Prozess führt folgende Optimierungen aus:
- **JS Minification**: Komprimiert `assets/js/main.js` zu `assets/js/main.min.js`.
- **CSS Minification**: Komprimiert `assets/css/main.css` zu `assets/css/main.min.css`.
- **Image Conversion**: Konvertiert alle Bilder aus `img/` in das moderne `WebP`-Format in `assets/img/`.

Die optimierten Assets werden automatisch über die `asset()`-Helper-Funktion in den Templates eingebunden.

## Entwicklungsprinzipien im Projekt

- **Keine Framework-Abhängigkeit**: bewusst kleine, direkte PHP-Struktur.
- **Gemeinsame Layout-Teile** in `partials/` statt dupliziertem Markup.
- **Zentrale Konfiguration** in `includes/site-config.php`.
- **Sichere Ausgabe** über `e(...)` für dynamische Werte.
- **Cache-Busting** für Assets via `asset(...)` (Timestamp-Versionierung).

## Arbeiten an Seiten

Typischer Ablauf bei neuen/angepassten Seiten:

1. `require_once __DIR__ . '/includes/bootstrap.php';`
2. `$meta = meta([...]);`
3. `$activeNav`, `$bodyClass`, ggf. `$structuredData` setzen
4. `partials/layout-start.php` einbinden
5. Hauptinhalt rendern
6. `partials/layout-end.php` einbinden

## SEO & strukturierte Daten

- Meta-Defaults kommen aus `site-config.php`.
- Jede Seite überschreibt nur relevante Meta-Felder.
- JSON-LD wird über `$structuredData` gesetzt und in `layout-start.php` ausgegeben.

## Frontend (CSS/JS)

- **CSS**: `assets/css/main.css`
  - Design-Tokens (Farben, Spacing, Radius, Shadows)
  - responsive Layouts über Media Queries
  - Motion-Reduktion via `prefers-reduced-motion`
- **JS**: `assets/js/main.js`
  - mobile Menüsteuerung
  - Reveal-Animation mit `IntersectionObserver`

## Routing-Verhalten prüfen

Folgende Punkte sollten immer getestet werden:

- Zugriff über kanonische URLs (ohne `.php`)
- Aufruf der Root (`/`) und Unterseiten
- 404-Verhalten für unbekannte Pfade
- direkte Asset-Auslieferung (CSS, JS, Bilder)

## Deployment-Hinweise

- `.htaccess` setzt URL-Rewrites und `.php`-Kanonisierung um.
- Wenn Hosting-Umgebung alle Requests auf `index.php` routet, greift der Fallback dort bereits.
- Legal-HTML sollte vor Deployment aktuell sein (siehe `content-workflow.md`).
