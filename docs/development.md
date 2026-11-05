# Entwicklung

## Quick Start

Voraussetzung: Node 22+ (auf dem Mac: homebrew node@24 getestet).

```bash
npm install          # Dependencies (esbuild/sharp/fsevents-Skripte freigeben, wenn npm install-scripts fragt)
npm run dev          # Dev-Server http://localhost:4321 (Port ggf. anpassen: npx astro dev --port 4330)
npm run build        # Statischer Build nach dist/
npm run preview      # dist/ servieren (Achtung: lauscht auf ::1, nicht 127.0.0.1)
npm run check        # astro check (TypeScript strict)
npm run sync:legal   # Rechtstexte von der Live-Site ziehen (überschreibt lokale Korrekturen!)
```

## Checks vor jedem Push

```bash
npx astro check                          # 0 errors
npm run build                            # Build grün
node scripts/audit-seo-responsive.mjs "http://127.0.0.1:4399"   # Audit Exit 0
```

Server für das Audit (statisch, IPv4):

```bash
(cd dist && python3 -m http.server 4399)
```

Die gleiche Kette läuft in CI (siehe deployment.md) — lokal grün heißt in der Regel CI grün.

## Visueller Smoke-Test

```bash
node scripts/screenshots.mjs "http://127.0.0.1:4399"
```

Schreibt Desktop- (1440px) + Mobile-Shots (390px) aller Routen nach `shots/` (gitignored). Scrollt vor jedem Shot durch die Seite, damit Lazy-Images laden. `reducedMotion: 'reduce'` ist gesetzt, damit Reveal-Elemente sichtbar sind.

Bei Layout-Prüfung: FullPage-Screenshots können am Sticky-Header Stitching-Artefakte zeigen (z. B. scheinbare rote Linien) — immer per Element-Screenshot (`locator.screenshot()`) nachprüfen, bevor man CSS ändert.

## Audit-Skript

`scripts/audit-seo-responsive.mjs` prüft deterministisch (Details: seo-responsive.md). Exit 1 bei HOCH/KRITISCH. Wird in CI nach dem Build gegen den statischen Server ausgeführt und blockiert das Deploy.

Typische Stolpersteine im Skript-Umfeld:

- `astro preview` lauscht auf `[::1]` (IPv6), nicht `127.0.0.1`
- Port 4321 kann durch andere Projekte belegt sein (verwendete Dev-Ports: 4330 lokal)
- Nach `astro build` den Server neu starten ist nicht nötig (er serviert dist/ aus dem Dateisystem), aber Browser-Caches leeren

## Konventionen

- **Keine Inline-Scripts**: Astro inlining kleine `<script>`-Tags würde die CSP (`script-src 'self'`) brechen — Client-JS gehört nach `public/js/`, Einbindung mit `<script is:inline src defer>`
- **Keine externen Requests**: Fonts/Bilder lokal, Maps als SVG, Tracker verboten (DSGVO)
- **Texte 1:1**: siehe content-workflow.md
- **Commits**: atomic, ein Intent pro Commit, imperative Conventional Commits (`feat:`, `fix:`, `chore:`, `chore!:` bei Breaking)
- **Docs**: Änderungen am Setup/Workflow sofort in `docs/` nachziehen — sie sind git-only (nicht deployed)
