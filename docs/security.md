# Sicherheit & Compliance

## OWASP Top 10 (2025) Abdeckung

### A03:2025 — Injection (XSS)

**Output-Escaping** über die `e()`-Helper-Funktion (`includes/helpers.php`):

```php
function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
```

Verwendung: Alle dynamischen Ausgaben in Templates werden durch `e()` geleitet:

```php
<?= e($siteConfig['email']) ?>
<?= e($meta['title']) ?>
<?= e(asset('/assets/css/main.min.css')) ?>
```

**HTML-Sanitizing** für Rechtstexte über `sanitize_legal_html()` (`includes/helpers.php`):

Blockierte Tags (werden komplett entfernt):

```
script, style, iframe, object, embed, form, input, button
```

Erlaubte Tags (alle anderen werden entpackt, Inhalt bleibt):

```
p, br, strong, em, b, i, u, small, ul, ol, li, a, h2, h3, h4, address
```

Sonderbehandlungen:

- `h1` → wird zu `h2` (Seitentitel-Hierarchie bleibt konsistent)
- `<a>`-Tags: Nur sichere `href`-Schemata erlaubt (`https://`, `mailto:`, `tel:`, `/`)
- Externe Links erhalten automatisch `rel="noopener noreferrer"`
- Alle anderen Attribute werden von allen Tags entfernt

### A05:2025 — Security Misconfiguration

Security-Header werden zentral über `.htaccess` gesetzt (exakte Werte):

```apache
Header always set Content-Security-Policy "default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'; img-src 'self' data: https:; font-src 'self' data:; connect-src 'self'; frame-ancestors 'none'"
Header always set X-Frame-Options "DENY"
Header always set X-Content-Type-Options "nosniff"
Header always set X-XSS-Protection "1; mode=block"
Header always set Referrer-Policy "no-referrer-when-downgrade"
Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains"
```

| Header | Wert | Zweck |
|--------|------|-------|
| Content-Security-Policy | siehe oben | Begrenzt ladbare Ressourcen auf `'self'`, blockiert Fremdeinbindungen |
| X-Frame-Options | `DENY` | Verhindert Clickjacking (Seite kann nicht in iframes eingebettet werden) |
| X-Content-Type-Options | `nosniff` | Blockiert MIME-Type-Sniffing im Browser |
| X-XSS-Protection | `1; mode=block` | Aktiviert XSS-Filter im Browser |
| Referrer-Policy | `no-referrer-when-downgrade` | Sendet keinen Referrer bei HTTPS→HTTP-Wechsel |
| Strict-Transport-Security | `max-age=31536000; includeSubDomains` | Erzwingt HTTPS für 1 Jahr inkl. Subdomains |

### A06:2025 — Vulnerable and Outdated Components

- Keine PHP-Framework-Abhängigkeiten (kein Composer, keine Vendor-Dateien)
- Keine JavaScript-Framework-Abhängigkeiten (kein React, Vue, jQuery)
- Dev-Dependencies minimal: `esbuild` (^0.28.0) für Build, `playwright` (^1.59.1) für Tests
- Regelmäßige Prüfung via `npm audit`

## Datenschutz & DSGVO

### Datensparsamkeit (Art. 5 DSGVO)

- Keine Datenbank, kein Datenbank-Server
- Kein Login-System, keine Benutzerkonten
- Keine Cookies, kein Session-Tracking
- Keine clientseitigen Tracking-Scripte (Google Analytics, Facebook Pixel etc.)
- Keine serverseitigen Applikations-Logs (nur Standard-Webserver-Logs des Hosters)

### Lokale Ressourcen

Alle Ressourcen werden selbstgehostet:

- **Schriften**: Playfair Display (8 WOFF2-Dateien in `assets/fonts/`)
- **Bilder**: Alle in `assets/img/`
- **CSS/JS**: Alle in `assets/css/` und `assets/js/`

Es fließen keine Daten an Drittanbieter:

- Kein Google Fonts
- Kein CDN (Cloudflare, jsDelivr etc.)
- Keine externen Scripte oder Stylesheets

### SSL-Verschlüsselung

- HSTS-Header erzwingt HTTPS (1 Jahr, inkl. Subdomains)
- Alle `mailto:`- und `tel:`-Links nutzen sichere Protokolle
- CSP erlaubt keine Mixed-Content-Ressourcen

## Secret Management

- Keine API-Schlüssel, Passwörter oder Zugangscodes im Quellcode
- FTP-Zugangsdaten für Deployment liegen als GitHub Secrets:
  - `FTP_SERVER`, `FTP_USERNAME`, `FTP_PASSWORD`
  - `FTP_PORT`, `FTP_PROTOCOL`, `FTP_SECURITY`
  - `FTP_FALLBACK_PROTOCOL`, `FTP_TIMEOUT`, `FTP_STATE_NAME`
  - `FTP_SERVER_DIR`, `FTP_LOG_LEVEL`
- `.gitignore` schließt `.env`-Dateien aus
- Die Rechtstext-Sync-URLs (`volksbuehne-worms.de`) sind öffentliche URLs, keine Secrets

## PHP-Sicherheit

- `declare(strict_types=1)` in jeder PHP-Datei
- Host-Bereinigung in `site_origin()`: Nur alphanumerische Zeichen, Punkte, Bindestriche
- Content-Type wird explizit gesetzt: `text/html; charset=UTF-8`
- Fallback-Meldungen bei fehlenden Rechtstexten (keine Pfade oder Systeminformationen preisgegeben)
