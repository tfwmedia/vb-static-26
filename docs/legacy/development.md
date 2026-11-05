# Entwicklung

## Voraussetzungen

| Tool | Version | Zweck |
|------|---------|-------|
| PHP | 8.1 oder neuer | Serverseitiges Rendering |
| Node.js | 18+ | Build-Prozess (esbuild) |
| cwebp | optional | WebP-Konvertierung im Build |

Optional: `wget` (Fallback im Rechtstext-Sync-Script).

## Lokaler Start

Entwicklungsserver starten:

```bash
php -S 127.0.0.1:8080 router.php
```

Alternativ mit CI-Router (robusteres 404-Handling):

```bash
php -S 127.0.0.1:8080 scripts/ci-router.php
```

Verfügbare Routen:

- `http://127.0.0.1:8080/` — Startseite
- `http://127.0.0.1:8080/weihnachtsmaerchen/` — Weihnachtsmärchen
- `http://127.0.0.1:8080/impressum/` — Impressum
- `http://127.0.0.1:8080/datenschutz/` — Datenschutz
- `http://127.0.0.1:8080/tickets-kaufen/` — 301 → ticket-regional.de

## Build-Prozess

### Abhängigkeiten installieren

```bash
npm install
```

Dev-Dependencies: `esbuild` (^0.28.0), `playwright` (^1.59.1).

### Build ausführen

```bash
node scripts/build.js
```

Der Build-Prozess führt drei Schritte aus:

#### 1. CSS-Minifizierung

- **Quelle**: `assets/css/main.css` (756 Zeilen)
- **Ziel**: `assets/css/main.min.css`
- **Tool**: esbuild

#### 2. JS-Minifizierung

- **Quelle**: `assets/js/main.js` (73 Zeilen)
- **Ziel**: `assets/js/main.min.js`
- **Tool**: esbuild

#### 3. WebP-Konvertierung

- Konvertiert alle JPEG/PNG-Dateien in `assets/img/` zu WebP
- **Tool**: `cwebp` (Qualität 80, Methode 6)
- **Ausnahmen**: Dateien mit `icon` oder `logo` im Namen behalten das PNG-Format
- Bereits existierende WebP-Dateien werden nicht erneut konvertiert

### Cache-Busting

Die `asset()`-Helper-Funktion hängt den Datei-Timestamp als Versionsparameter an:

```php
asset('/assets/css/main.min.css')
// → /assets/css/main.min.css?v=1712345678
```

Bei Änderungen an einer Asset-Datei ändert sich automatisch der Timestamp und damit die URL — kein manueller Cache-Bust nötig.

Die Templates laden immer die minifizierten Versionen:

```html
<link rel="stylesheet" href="<?= e(asset('/assets/css/main.min.css')) ?>">
<script src="<?= e(asset('/assets/js/main.min.js')) ?>" defer></script>
```

## Testing

### Visuelle Tests (Playwright)

```bash
# PHP-Server auf Port 8082 starten
php -S 127.0.0.1:8082 scripts/ci-router.php &

# Tests ausführen
node scripts/playwright-test.js
```

Getestet werden:

| Seite | Viewport | Beschreibung |
|-------|----------|-------------|
| Startseite | Desktop (1280×800) | Hero-Bild, Layout, Scroll |
| Startseite | iPhone 13 | Mobile Navigation, Responsive |
| Weihnachtsmärchen | Desktop (1280×800) | Event-Meta, Poster, Layout |
| Weihnachtsmärchen | iPhone 13 | Mobile Ansicht |

Der Test scrolled automatisch, um Lazy-Loading auszulösen, und prüft ob das Hero-Bild vorhanden ist.

### CI-Tests (GitHub Actions)

Die Pipeline (`.github/workflows/deploy.yml`) führt im `test`-Job folgende Checks aus:

1. **PHP-Syntax-Prüfung**: Alle `.php`-Dateien werden mit `php -l` geprüft
2. **Route-Tests**: Alle 4 Routen müssen HTTP 200 zurückgeben
3. **Asset-Tests**: Kritische Assets müssen erreichbar sein:
   - `/assets/css/main.css`
   - `/assets/js/main.js`
   - `/assets/img/vb-logo.png`
   - `/assets/img/vb-icon.png`
   - `/assets/img/vorhang.jpg`
   - `/assets/img/weihnachtsmaerchen-2025.jpeg`
4. **404-Test**: Unbekannte Route muss HTTP 404 zurückgeben

## Neue Seite hinzufügen

Schritt-für-Schritt-Anleitung:

### 1. PHP-Seite erstellen

Beispiel: `spielzeit.php` im Projektverzeichnis:

```php
<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$meta = meta([
    'title' => 'Spielzeit 2026/2027 | Volksbühne Worms 1908 e. V.',
    'description' => 'Aktuelle Spielzeit der Volksbühne Worms.',
    'canonical' => site_url('/spielzeit/'),
]);
$activeNav = 'spielzeit';
$bodyClass = 'page-spielzeit';
$structuredData = null;

require __DIR__ . '/partials/layout-start.php';
?>
<main id="main-content">
    <section class="hero hero-inner">
        <div class="container">
            <p class="eyebrow">Aktuelles</p>
            <h1>Spielzeit 2026/2027</h1>
            <p class="lead">Übersicht aller Stücke und Termine.</p>
        </div>
    </section>

    <section class="section">
        <div class="container prose" data-reveal>
            <!-- Inhalt hier -->
        </div>
    </section>
</main>
<?php require __DIR__ . '/partials/layout-end.php'; ?>
```

### 2. Navigation erweitern

In `includes/site-config.php` den `$navigation`-Array ergänzen:

```php
['key' => 'spielzeit', 'label' => 'Spielzeit', 'href' => '/spielzeit/'],
```

### 3. Routing hinzufügen

**Produktion** (`.htaccess`):

```apache
RewriteRule ^spielzeit/?$ spielzeit.php [L,QSA]
```

**Lokal/CI** (`router.php` und `scripts/ci-router.php`):

Die Route-Map erweitern:

```php
'/spielzeit' => 'spielzeit.php',
```

### 4. Fallback-Routing in `index.php`

Den Regex und die Route-Map ergänzen.

### 5. CI-Asset-Tests aktualisieren

Falls die Seite neue kritische Assets verwendet, diese in `.github/workflows/deploy.yml` ergänzen.

## Konventionen

### PHP

- `declare(strict_types=1)` in jeder PHP-Datei
- Alle dynamischen Ausgaben durch `e()` escapen: `<?= e($value) ?>`
- Zentrale Konfiguration in `includes/site-config.php`
- Keine Framework-Abhängigkeit, kein Composer

### CSS

- Custom Properties (Design-Tokens) in `:root`
- Komponenten-Klassen mit BEM-ähnlicher Namenskonvention
- Responsive Breakpoints bei `48rem` (768px) und `80rem` (1280px)
- `prefers-reduced-motion` wird berücksichtigt

### JavaScript

- Vanilla JS, kein Framework
- Strict Mode (`"use strict"`)
- IIFE-Pattern zur Kapselung
- Feature-Prüfung vor Verwendung (`IntersectionObserver in window`)

### Assets

- Alle Bilder, Fonts und Stylesheets werden selbstgehostet (keine CDN-Abhängigkeiten)
- Fonts liegen als WOFF2-Subset-Dateien vor
- Bilder: WebP-Format bevorzugt, PNG/JPEG als Fallback für Icons/Logos

## Deployment-Hinweise

- `.htaccess` setzt URL-Rewrites und `.php`-Kanonisierung um
- Wenn die Hosting-Umgebung alle Requests auf `index.php` routet, greift der Fallback
- Legal-HTML sollte vor Deployment aktuell sein (siehe [content-workflow.md](./content-workflow.md))
- FTP-Deploy über GitHub Actions bei Push auf `main` (siehe [architecture.md](./architecture.md) für Details zur CI/CD-Pipeline)
