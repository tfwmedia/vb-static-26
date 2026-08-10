# Architektur

## Überblick

Die Website ist als **klassische PHP-Multi-Page-Anwendung** aufgebaut — bewusst ohne Framework, ohne Composer, ohne Datenbank. Jede Seite rendert serverseitig HTML und nutzt gemeinsame Includes für Konfiguration, Helper-Funktionen und Layout.

Architekturziele:

- Einfache Wartbarkeit durch klare Struktur
- Geringe Komplexität, keine Framework-Abhängigkeiten
- SEO-freundliche, saubere URLs
- Klare Trennung zwischen Seiteninhalten und wiederverwendbarem Layout

## Verzeichnisstruktur

```text
.
├── .github/workflows/
│   └── deploy.yml                  # CI/CD Pipeline (GitHub Actions)
├── assets/
│   ├── css/
│   │   ├── main.css                # Quelldatei (756 Zeilen)
│   │   └── main.min.css            # Minifizierte Version (esbuild)
│   ├── fonts/                      # 8 WOFF2-Dateien (Playfair Display)
│   │   ├── playfair-display-italic-cyrillic.woff2
│   │   ├── playfair-display-italic-latin-ext.woff2
│   │   ├── playfair-display-italic-latin.woff2
│   │   ├── playfair-display-italic-vietnamese.woff2
│   │   ├── playfair-display-normal-cyrillic.woff2
│   │   ├── playfair-display-normal-latin-ext.woff2
│   │   ├── playfair-display-normal-latin.woff2
│   │   └── playfair-display-normal-vietnamese.woff2
│   ├── img/                        # 16 Bilddateien (JPEG, PNG, WebP, SVG)
│   └── js/
│       ├── main.js                 # Quelldatei (73 Zeilen)
│       └── main.min.js             # Minifizierte Version (esbuild)
├── content/
│   └── legal/
│       ├── impressum.html          # HTML-Fragment
│       └── datenschutz.html        # HTML-Fragment
├── docs/                           # Projektdokumentation (9 Markdown-Dateien)
├── includes/
│   ├── bootstrap.php               # Autoloader: Config + Helpers
│   ├── helpers.php                 # 8 Helper-Funktionen
│   └── site-config.php             # Zentrale Konfiguration
├── partials/
│   ├── layout-start.php            # HTML-Head, Header, Navigation
│   └── layout-end.php              # Footer, Dokumentende
├── scripts/
│   ├── build.js                    # esbuild-Build (CSS/JS/WebP)
│   ├── ci-router.php               # Router für CI/lokalen Dev-Server
│   ├── playwright-test.js          # Visuelle Tests (Playwright)
│   └── sync-legal-content.php      # Rechtstext-Sync von Live-Seite
├── .gitignore
├── .htaccess                       # Apache: URL-Rewriting + Security Header
├── datenschutz.php                 # Seite: Datenschutz
├── impressum.php                   # Seite: Impressum
├── index.php                       # Startseite + Fallback-Router
├── package.json                    # npm-Manifest (esbuild, playwright)
├── package-lock.json
├── router.php                      # Lokaler Entwicklungsrouter
└── weihnachtsmaerchen.php          # Seite: Weihnachtsmärchen
```

## Rendering-Fluss

Jede Seite folgt demselben Ablauf:

```
1. Seiten-Datei (z.B. index.php)
   │
   ├── require_once 'includes/bootstrap.php'
   │       ├── header('Content-Type: text/html; charset=UTF-8')
   │       ├── require 'site-config.php'  → $siteConfig, $metaDefaults, $navigation
   │       └── require 'helpers.php'      → e(), site_url(), asset(), ...
   │
   ├── $meta = meta([...])                  // Seiten-spezifische Meta-Daten
   ├── $activeNav = 'home'                  // Aktiver Navigationspunkt
   ├── $bodyClass = 'page-home'             // CSS-Klasse für <body>
   ├── $structuredData = [...]              // JSON-LD Schema.org Daten
   │
   ├── require 'partials/layout-start.php'  // <head>, Header, <nav>
   │
   ├── <main> ... </main>                   // Individueller Seiteninhalt
   │
   └── require 'partials/layout-end.php'    // Footer, </body>, </html>
```

## Routing

### Ebene 1: Produktion (`.htaccess`)

Apache `mod_rewrite` mappt saubere URLs auf PHP-Dateien:

| Kanonische URL | Ziel |
|----------------|------|
| `/` | `index.php` |
| `/weihnachtsmaerchen/` | `weihnachtsmaerchen.php` |
| `/impressum/` | `impressum.php` |
| `/datenschutz/` | `datenschutz.php` |
| `/tickets-kaufen/` | 301 → `https://www.ticket-regional.de/events.php?mysearchSpecificType=eventtype&mysearchSpecificID=1883` |

Zusätzlich:
- Vorhandene Dateien/Ordner werden direkt ausgeliefert (Assets, Fonts)
- Direkte `.php`-Aufrufe werden per 301 auf die saubere URL umgeleitet

### Ebene 2: Fallback in `index.php`

Falls ein Hoster alle Requests auf `index.php` leitet, erkennt die Datei bekannte Pfade über einen Regex und lädt die passende Zielseite. Externe Redirects (z. B. `/tickets-kaufen/`) werden vorab per 301 an den externen Dienst weitergeleitet:

```php
preg_match('~(?:^|/)(weihnachtsmaerchen|impressum|datenschutz)(?:\.php)?/?$~i', $requestPath, $matches)
```

### Ebene 3: Lokal/CI (`router.php` bzw. `scripts/ci-router.php`)

Beide Router nutzen den eingebauten PHP-Dev-Server:

- `router.php` — für lokale Entwicklung (`php -S 127.0.0.1:8080 router.php`)
- `scripts/ci-router.php` — für CI-Umgebung, mit explizitem 404-Handling und `Content-Type`-Header

## Konfiguration (`includes/site-config.php`)

Drei globale Variablen:

### `$siteConfig`

```php
[
    'name' => 'Volksbühne Worms 1908 e. V.',
    'email' => 'info@volksbuehne-worms.de',
    'phone_display' => '06241 51429',
    'phone_href' => '+49624151429',
    'maerchen_contact_email' => 'kontakt@volksbuehne-worms.de',
    'maerchen_contact_phone_display' => '0152 57204453',
    'maerchen_contact_phone_href' => '+4915257204453',
    'address' => [
        'name' => 'Volksbühne Worms 1908 e. V.',
        'street' => 'Würdtweinstraße 11',
        'postal_city' => '67549 Worms',
    ],
]
```

### `$metaDefaults`

```php
[
    'title' => 'Volksbühne Worms 1908 e. V.',
    'description' => 'Theater seit 1908: Spielzeit, Weihnachtsmärchen und Kultur für Worms und die Region.',
    'canonical' => '/',
    'og_type' => 'website',
    'og_locale' => 'de_DE',
    'og_image' => '/assets/img/vorhang.webp',
]
```

### `$navigation`

```php
[
    ['key' => 'home', 'label' => 'Start', 'href' => '/'],
    ['key' => 'maerchen', 'label' => 'Weihnachtsmärchen', 'href' => '/weihnachtsmaerchen/'],
]
```

## Helper-Funktionen (`includes/helpers.php`)

| Funktion | Signatur | Beschreibung |
|----------|----------|-------------|
| `e()` | `e(mixed $value): string` | HTML-Escaping via `htmlspecialchars($value, ENT_QUOTES \| ENT_SUBSTITUTE, 'UTF-8')` |
| `site_origin()` | `site_origin(): string` | Ermittelt die Origin (`https://host`) aus `$_SERVER`, bereinigt den Host |
| `site_url()` | `site_url(string $path = '/'): string` | Normalisiert interne Pfade (führendes `/`, kein doppelter Slash) |
| `asset()` | `asset(string $path): string` | Gibt den Asset-Pfad mit Cache-Busting-Parameter zurück (`?v=filemtime`) |
| `asset_url()` | `asset_url(string $path): string` | Kombiniert `site_url()` und `asset()` für absolute Asset-URLs |
| `absolute_url()` | `absolute_url(string $urlOrPath): string` | Macht relative URLs absolut, lässt bereits absolute URLs unverändert |
| `meta()` | `meta(array $pageMeta): array` | Merged Seiten-Meta mit `$metaDefaults`, setzt `canonical` und `og_image` auf absolute URLs |
| `sanitize_legal_html()` | `sanitize_legal_html(string $html): string` | Bereinigt HTML-Fragmente: entfernt gefährliche Tags, normalisiert `h1` → `h2`, filtert Attribute |
| `json_ld()` | `json_ld(array $schema): string` | JSON-kodiert Schema.org-Daten (`JSON_UNESCAPED_SLASHES \| JSON_PRETTY_PRINT`) |

## Layout-System

### `partials/layout-start.php`

Rendert den HTML-Kopf:

1. `<!doctype html>`, `<html lang="de">`, `<head>`
2. Charset, Viewport, Title, Description, Canonical
3. Open Graph Meta-Tags (`og:site_name`, `og:title`, `og:description`, `og:type`, `og:url`, `og:image`, `og:locale`)
4. Twitter Card Meta-Tags (`twitter:card`, `twitter:title`, `twitter:description`, `twitter:image`)
5. Favicon und Apple Touch Icon (mit Cache-Busting)
6. CSS-Stylesheet (`main.min.css`, mit `<link rel="preload">`)
7. JavaScript (`main.min.js`, mit `defer`)
8. JSON-LD strukturierte Daten (wenn `$structuredData` gesetzt)
9. Skip-Link (`<a href="#main-content">`)
10. Header mit Logo, Navigation und Kontakt-Button

### `partials/layout-end.php`

Rendert das HTML-Ende:

1. Footer mit drei Spalten: Kontakt, Adresse, Links
2. Copyright-Zeile (dynamisches Jahr)
3. `</body>`, `</html>`

## Strukturierte Daten (JSON-LD)

Jede Seite kann `$structuredData` setzen. Die Daten werden in `layout-start.php` per `json_ld()` ausgegeben.

### Startseite (`index.php`)

- `PerformingGroup` mit `additionalType: LocalBusiness`
- `WebSite`

### Weihnachtsmärchen (`weihnachtsmaerchen.php`)

- `PerformingGroup` mit `additionalType: LocalBusiness`
- `EventSeries` mit:
  - `OfflineEventAttendanceMode`
  - `Place`: Das Wormser
  - `AggregateOffer` (12,00 € – 14,00 €)
  - `subEvent`: Zwei einzelne `Event`-Einträge (01.12. und 02.12.2026)

### Impressum / Datenschutz

`$structuredData = null` — keine strukturierten Daten.

## Rechtstext-System

1. `content/legal/impressum.html` und `content/legal/datenschutz.html` enthalten HTML-Fragmente
2. Die jeweilige PHP-Seite lädt den Inhalt per `file_get_contents()`
3. Bei Fehlschlagen wird eine Fallback-Meldung angezeigt
4. Der HTML-Inhalt wird durch `sanitize_legal_html()` bereinigt:
   - Blockierte Tags: `script`, `style`, `iframe`, `object`, `embed`, `form`, `input`, `button`
   - Erlaubte Tags: `p`, `br`, `strong`, `em`, `b`, `i`, `u`, `small`, `ul`, `ol`, `li`, `a`, `h2`, `h3`, `h4`, `address`
   - `h1` wird zu `h2` normalisiert
   - Bei `<a>`-Tags werden nur sichere `href`-Schemata erlaubt (`https://`, `mailto:`, `tel:`, `/`)
   - Externe Links erhalten `rel="noopener noreferrer"`
   - Alle anderen Attribute werden entfernt
5. Nach leerem Ergebnis wird ebenfalls eine Fallback-Meldung angezeigt
