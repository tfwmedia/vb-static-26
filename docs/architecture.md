# Architektur

## Stack

| Layer | Technologie |
|-------|-------------|
| Framework | Astro 5.18, `build.format: 'directory'` (→ `/route/index.html`) |
| Typisierung | TypeScript strict (`tsconfig` extends `astro/tsconfigs/strict`), `npm run check` |
| Client-JS | 2 kleine Vanilla-Dateien in `public/js/` (`site.js`, `countdown.js`), per `<script is:inline src defer>` eingebunden — kein Bundling nötig, CSP-kompatibel |
| Styling | `src/styles/global.css`, wird von Astro gebündelt nach `dist/_astro/*.css` |
| Bilder/Fonts | statisch in `public/assets/` (bereits optimierte WebP/PNG, Inter-Variable-Font, OFL) |

Site-Konstante: `https://www.volksbuehne-worms.de` (`astro.config.mjs` → `site`, `src/data/site.ts` → `ORIGIN`).

## Verzeichnisstruktur

```
src/
  data/           Alle Inhalte (Quelle der Wahrheit)
    site.ts       Site-Konfiguration: Name, Kontakte, Adressen, Bank, Navigation, Meta-Defaults, ORIGIN
    events.ts     15 Termine (Titel, Datum, Zeit, Spielstätte, Ticket-URL, Status) + nextUpcomingEvent()
    productions.ts  2 Produktionen (Löffelliste, Bremer Stadtmusikanten) mit Volltexten
    vorstand.ts   Vorstand (3 Personen) + chronik (leer bis redaktionell gepflegt)
  lib/
    format.ts     Deutsche Datums-/Text-Formatter (Port der PHP-Helper): formatDateDe,
                  weekdayFullDe, monthShortDe, dayPaddedDe, isoLocal (CET/CEST-Regel),
                  personInitials, slugify, truncateText
    legal.ts      Lädt content/legal/*.html zur Build-Zeit (fs, cwd-basiert)
  layouts/
    BaseLayout.astro  Komplettes Head-Setup (Meta, OG, Canonical, hreflang, JSON-LD,
                      Favicon, Preloads) + Skip-Link + Header/Footer-Einbindung
  components/
    SiteHeader.astro    Sticky Header, Desktop-Nav, native-<dialog>-Mobile-Menü
    SiteFooter.astro    4-spaltiger Footer inkl. Newsletter-Mailto
    Breadcrumbs.astro   <nav><ol> mit aria-current
    Countdown.astro     Serverseitig gerendertes Ziel-Datum, clientseitig weitergeführt
    ScheduleList.astro  Spielplan-Tabelle, gruppiert je Produktion
    ProductionCard.astro  Produktions-Karte (Spielzeit-Seite, mit vollständigen Fakten)
    StatusChip.astro    tickets/sold_out/premiere/few_left
  pages/          9 Routen (siehe content-workflow.md)
  styles/global.css  Design-System (siehe design-system.md)
public/
  assets/img/     Bilder + og-*.png Social Cards (Quell-SVGs liegen daneben)
  assets/fonts/   InterVariable.woff2 (+Italic)
  js/             site.js (Header/Nav/Reveal), countdown.js
  .htaccess       Tickets-301, 404, Security-Header
  robots.txt      Disallow /tickets-kaufen/, Sitemap-Verweis
  sitemap.xml     statisch, 7 Index-Routen
content/
  legal/          impressum.html, datenschutz.html (1:1-Fragmente der Rechtstexte)
scripts/
  sync-legal-content.mjs   Zieht Rechtstexte von der Live-Site in content/legal/
  screenshots.mjs          Visueller Smoke-Test (Desktop + Mobile, alle Routen)
  audit-seo-responsive.mjs SEO/Responsive-Audit (CI-Gate)
docs/            Diese Dokumentation (git-only, nie deployed)
```

## Rendering-Datenfluss

```
src/data/*.ts ──> pages/*.astro (Frontmatter: Daten selektieren, JSON-LD bauen)
                     │
                     ├─> BaseLayout.astro  (Meta aus PageMeta + metaDefaults,
                     │                      structuredData als JSON.stringify → set:html)
                     └─> Components (props rein, HTML raus)
content/legal/*.html ──> impressum/datenschutz via set:html (1:1, keine Änderung)
public/*            ──> unverändert nach dist/
```

Build: `astro build` → `dist/` mit `/route/index.html` pro Seite, `/_astro/*.css` (gebündeltes CSS), statische Assets 1:1.

## Meta-System

`PageMeta` (src/data/site.ts) + `metaDefaults`: jede Seite übergibt `title`, `description`, `canonicalPath`, optional `ogType`/`ogImage`/`ogImageAlt`/`robots`. BaseLayout leitet daraus ab:

- `<title>`, description, canonical (absolut via `absoluteUrl`)
- hreflang `de` + `x-default` (beide auf canonical)
- OG/Twitter-Set (og:image default `/assets/img/og-image.png`, 1200×630, **PNG** — SVG wird von Social-Plattformen nicht gerendert)
- robots default `index, follow, max-image-preview:large, max-snippet:-1`

## Strukturierte Daten

Pro Seite als `structuredData`-Prop (`@graph`). Vorhandene Knoten:

| Seite | Typen |
|-------|-------|
| / | Organization (`#organization`), WebSite (`#website`), BreadcrumbList, ItemList |
| /spielzeit/ | CollectionPage, BreadcrumbList, ItemList |
| /weihnachtsmaerchen/ | PerformingGroup, BreadcrumbList, EventSeries mit 2 SubEvents (01./02.12., Offer 12.00 EUR), Breadcrumb |
| /verein/ | AboutPage, BreadcrumbList, Organization |
| /kontakt/ | ContactPage, BreadcrumbList |
| /impressum/, /datenschutz/ | BreadcrumbList |

Konventionen: `#organization`/`#website` @ids verweisen auf `/`; Vorstellungen im Schema sind nur die **frei verkäuflichen** (01./02.12.) — geschlossene Schulvorstellungen bewusst nicht als buchbare Events ausweisen.

## Client-JS-Verhalten

- `site.js`: Scroll-Schatten am Header, `<dialog>`-Mobile-Menü (open/close/backdrop-click), IntersectionObserver-Reveal (`[data-reveal]`, `--reveal-delay`-Staffelung)
- `countdown.js`: liest `data-countdown`-Attribut, Sekundentakt (bei `prefers-reduced-motion` nur Minuten), aria-live-Updates
- Beide IIFE, strikt CSP-konform (`script-src 'self'`, kein Inline-JS im Build)

## Bekannte Grenzen/Bewusste Entscheidungen

| Thema | Entscheidung |
|-------|--------------|
| Helvetica Neue | Lizenzpflichtig, nicht einbettbar — Headings nutzen lokal installiertes HelveticaNeue (Gewicht 800), Fallback Inter ExtraBold (self-hosted, OFL) |
| `isoLocal()` | DST-Grenze per EU-Regel (letzter Sonntag März/Oktober) — alle Vorstellungen abends, Sprungstunde irrelevant |
| Rechtstext-Fragmente | Mit `<br>`-Struktur aus WordPress/Elementor importiert; wird per `.legal`-CSS lesbar formatiert, Inhalt bewusst unangetastet |
| Such-Action | Die SearchAction der Vorgängersite wurde entfernt (statische Site hat keine `?s=`-Suche) |
