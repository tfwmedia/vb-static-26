# Security & Compliance 2026

Dieses Dokument beschreibt die umgesetzten Sicherheitsmaßnahmen und Compliance-Richtlinien (in Anlehnung an das satware AG Security Skill Profil).

## 1. OWASP Top 10 (2025 Web) Abdeckung

### A03:2025 - Injection (Cross-Site Scripting / XSS)
- Das Projekt verwendet strenges Output-Escaping via `htmlspecialchars(..., ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')` umgesetzt in der `e()`-Helper-Funktion (`includes/helpers.php`).
- Für dynamisch geladene Rechtstexte (`datenschutz.html`, `impressum.html`) wird ein strikter HTML-Parser (`sanitize_legal_html`) verwendet, der potenziell gefährliche Tags (wie `<script>`, `<iframe>`, `<object>`) und Attribute serverseitig herausfiltert.

### A05:2025 - Security Misconfiguration
- Die HTTP-Response beinhaltet moderne Security-Header, die zentral via `.htaccess` ausgeliefert werden:
  - `Content-Security-Policy`: Begrenzt Ressourcen (`script-src`, `style-src`, `img-src`) auf `'self'` und blockiert Fremdeinbindungen.
  - `X-Frame-Options`: Steht auf `DENY`, um Clickjacking zu verhindern.
  - `X-Content-Type-Options`: Steht auf `nosniff`, um MIME-Type-Sniffing zu blockieren.
  - `X-XSS-Protection`: Blockiert das Laden bei erkannten Cross-Site-Scripting-Angriffen.
  - `Referrer-Policy`: Steht auf `no-referrer-when-downgrade`.
  - `Strict-Transport-Security` (HSTS): Erzwingt HTTPS für mindestens 1 Jahr.

### A06:2025 - Vulnerable and Outdated Components
- Das Projekt verwendet keine tief verwurzelten PHP-Frameworks (minimalistische Architektur).
- Die NPM-Abhängigkeiten (`esbuild`, `playwright` für die Build-Pipeline) weisen keine Sicherheitslücken auf (verifiziert via `npm audit` am 03. April 2026). 

## 2. Datenschutz & DSGVO (GDPR)

- **Datensparsamkeit (Art. 5 DSGVO):** Das Projekt verwendet keine Datenbanken, keine Login-Mechanismen und erhebt keine serverseitigen Logs jener IP-Adressen (ausgenommen die Standard-Logs des Hosters zur Fehleranalyse/Spamabwehr, falls konfiguriert).
- **Lokale Ressourcen:** Alle Schriften (Playfair Display) und Assets werden zu 100% lokal gehostet (`assets/fonts/`). Es fließen keine Daten an Drittanbieter (wie Google Fonts oder externe CDNs).
- **SSL-Verschlüsselung:** Durch `.htaccess` und HSTS-Header wird standardmäßig HTTPS erzwungen, sodass die Übertragung bei etwaigen Formularabschickungen (z.B. E-Mail-App-Verknüpfungen) sicher verläuft.

## 3. Secret Management
- Es befinden sich keine API-Schlüssel, Passwörter oder Zugangscodes (wie AWS-Keys oder FTP-Zugänge) hardcoded im Projekt.
- CI/CD Zugänge und Passwörter für das FTP-Deployment sind korrekt über sichere `secrets` in `.github/workflows/deploy.yml` gekapselt und werden nicht eingecheckt.
