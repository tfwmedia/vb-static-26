# Deployment

## Pipeline

GitHub Actions Workflow `.github/workflows/deploy.yml` ("Test & Deploy"):

```
push/PR → main ──> Job "Quality Checks" ──> Job "Deploy dist/ to FTP"
                    ├── npm ci
                    ├── npm run check (astro check)
                    ├── npm run build
                    ├── dist/ via python http.server :8080
                    ├── Routen-Check (9 Routen → 200)
                    ├── Asset-Check (kritische Bilder/Fonts → 200)
                    ├── Content-Needles (Termine, VR 10302, DSGVO, Ticket-Redirect-Fallback)
                    ├── Playwright-Install + audit-seo-responsive.mjs (Exit 0 = Gate)
                    └── Deploy: nur bei push auf main (nicht PR), FTP local-dir dist/
```

Nur `dist/` wird hochgeladen (`local-dir: dist/`) — `docs/`, Quellcode, `node_modules` und Skripte landen **nie** auf dem Server. Der FTP-Sync löscht serverseitige Dateien, die lokal nicht mehr existieren (Alter Build/PHP-Reste wurden dadurch bei der Migration automatisch entfernt).

Deploy-Retries: 3 Versuche (primary protocol → retry → alternativer Fallback-Protocol), konfigurierbar über Secrets. Per `workflow_dispatch` manuell mit `dry_run` (nur Liste) oder `clean_slate` (Server-Verzeichnis vorher komplett leeren — für Full-Replace).

## Secrets (Repo-Settings)

`FTP_SERVER`, `FTP_USERNAME`, `FTP_PASSWORD`, optional `FTP_PORT`, `FTP_PROTOCOL` (ftp/ftps/ftps-legacy), `FTP_SECURITY`, `FTP_TIMEOUT`, `FTP_SERVER_DIR`, `FTP_STATE_NAME`, `FTP_LOG_LEVEL`, `FTP_FALLBACK_PROTOCOL`.

## Server-Verhalten (Apache)

`public/.htaccess` wird mit nach `dist/.htaccess` kopiert und aktiv:

- `/tickets-kaufen/?` → **301** `ticket-regional.de/events.php?mysearchSpecificType=eventtype&mysearchSpecificID=1883` (greift vor Dateizugriff; die statische Fallback-Seite `dist/tickets-kaufen/index.html` mit Meta-Refresh + noindex wird auf Apache nie ausgeliefert)
- `ErrorDocument 404 /404.html`
- Security-Header: CSP `default-src 'self'; script-src 'self'; style-src 'self'; img-src 'self' data:; font-src 'self' data:; connect-src 'self'; frame-ancestors 'none'`, X-Frame-Options DENY, nosniff, HSTS (1 Jahr, includeSubDomains), Referrer-Policy strict-origin-when-cross-origin

Schöne URLs durch `build.format: 'directory'` (`/spielzeit/` → `spielzeit/index.html`, DirectoryIndex liefert aus). Keine Rewrite-Regeln für Seiten-Routen nötig.

## Nach jedem Deploy: Live-Verifikation

```bash
curl -s https://www.volksbuehne-worms.de/ | grep -o '<h1[^>]*>[^<]*'        # H1 aktuell
curl -sI https://www.volksbuehne-worms.de/tickets-kaufen/ | grep -iE '^HTTP|^Location'   # 301
curl -s https://www.volksbuehne-worms.de/impressum/ | grep -c 'VR 10302'     # 1
curl -sI https://www.volksbuehne-worms.de/ | grep -iE 'content-security|strict-transport' # Header
```

Danach im Browser quer klicken (Start, Spielplan, Märchen-Strip, Footer-Links).

## Rollback

1. Vorherigen grünen Commit im Repo finden (`git log --oneline`)
2. `git revert` der betroffenen Commits **oder** gezielt den alten Stand wiederherstellen und pushen — jede Push löst Build + Deploy erneut aus
3. Notfall: `workflow_dispatch` mit `dry_run: true` zur Analyse; `clean_slate` nur wenn bewusst komplett neu aufgesetzt werden soll (löscht alles im server-dir vor dem Upload)

## Deployment-Historie (wichtige Meilensteine)

- **2026-11-05**: Erst-Deploy des Astro-Redesigns (Run 42025213873, grün). Alte PHP-Dateien wurden durch den FTP-Sync vom Server entfernt (`/index.php` → 404), neue .htaccess aktiv, og-PNG-Social-Cards live.
