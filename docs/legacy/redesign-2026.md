# Redesign 2026 — Übersicht

Vollständiger Relaunch der Volksbühne-Worms-Website im September 2026. Diese
Seite dokumentiert in Kurzform, was sich strukturell, visuell und technisch
geändert hat — sie ist die Tour für die Reviewer und das Gedächtnis für die
nächste Iteration.

## Markentreue

Logo, Markenrot (`#c31e2e`), Hover-Ton (`#a01825`), Soft-Ton (`#fdf0f1`),
Schrift (Cormorant Garamond für Headings, System-Stack für Body) und alle
bestehenden Bilder (Vorhang, Bremer Stadtmusikanten, Löffelliste, alle SVGs)
bleiben unverändert.

## Neue Seitenstruktur

| Route | Datei | Zweck |
|------|-------|------|
| `/` | `index.php` | Komplett neue Landingpage |
| `/spielzeit/` | `spielzeit.php` | Neue Spielzeit-Übersicht |
| `/weihnachtsmaerchen/` | `weihnachtsmaerchen.php` | Märchenseite strukturell angereichert |
| `/verein/` | `verein.php` | Neue Vereinsseite |
| `/kontakt/` | `kontakt.php` | Neue Kontaktseite mit statischer Anfahrtskarte |
| `/impressum/`, `/datenschutz/` | unverändert | Rechtstexte |
| `/sitemap.xml` | statisch | SEO |
| `/robots.txt` | statisch | SEO |
| `/404` | `404.php` | Gestaltete Fehlerseite |

## Neue Komponenten (CSS + JS)

- `.event-card` mit Date-Badge, Status-Chip, Schema.org `Event` Microdata
- `.chip` (Varianten: `success`, `warning`, `brand`, `soft`)
- `.countdown-unit` + `data-countdown` JS-Modul (Pause bei `document.hidden`,
  `prefers-reduced-motion`-respekt)
- `.hero-curtain` Reveal-Effekt (CSS-only, mit Auto-Open + Interaction-Early)
- `.gallery` + `<dialog class="lightbox">` mit nativer Focus-Trap
- `.accordion` via `<details>` (zero-JS)
- `.timeline`, `.person`, `.benefit`, `.testimonial`, `.map-card`, `.breadcrumb`
- `.error-page__curtain` für die 404
- Mobile-Nav als `<dialog>` mit Focus-Trap, Body-Scroll-Lock, ESC

## SEO & Strukturierte Daten

Jede Seite rendert ihre eigene JSON-LD. Highlights:

- `Organization` mit `foundingDate`, `logo`, `address`, `email`,
  `telephone`, `member count`
- `WebSite` mit `SearchAction`
- `BreadcrumbList` auf jeder Hauptseite
- `CollectionPage` (Spielzeit) / `AboutPage` (Verein) /
  `ContactPage` (Kontakt)
- `EventSeries` + zwei `Event`-`subEvent`-Einträge auf der Märchenseite,
  jeweils mit `Place`-Adresse, `Offer`, `Performer`
- `ItemList` für Nächste Vorstellungen und Aktuelle Spielzeit

Meta-Tags neu:

- `theme-color` + `color-scheme: light`
- `robots: index, follow, max-image-preview:large, max-snippet:-1`
- `hreflang="de"` + `x-default`
- OG/Twitter mit `og:image:width`, `og:image:height`, `og:image:alt`
- Fünf neue per-page OG-SVGs (`og-home`, `og-spielzeit`, `og-verein`,
  `og-kontakt`, `og-maerchen`)

## A11y (WCAG 2.2 AA-Ziel)

- `:focus-visible` global mit 3px Outline
- `prefers-contrast: more`-Variante (dickere Outlines, höhere Linien-Kontraste)
- `prefers-reduced-motion: reduce` deaktiviert Animationen
- Mobile-Nav als `<dialog>` mit Focus-Trap, ESC, Body-Lock
- FAQ via `<details>` (zero-JS, screenreader-freundlich)
- Touch-Targets ≥ 44×44px
- Skip-Link + `.visually-hidden` Utility
- `aria-live="polite"` für Countdown

## Performance

- Single CSS minified (`main.min.css`) bleibt
- `fetchpriority="high"` + `<link rel="preload">` für Hero-Bild
- `loading="lazy"` + `decoding="async"` Off-Screen
- `width`/`height` auf allen Bildern (CLS = 0)
- `content-visibility: auto` selektiv
- Self-hosted Fonts, keine externen Requests
- DSGVO-clean (kein Tracking, keine Cookies, keine externen Schriftarten)

## Sicherheit

- Bestehende OWASP-Top-10-Maßnahmen bleiben unverändert
- Statische SVG-Karten rendern keine externen Ressourcen
- Kein iframe-Embed, keine Third-Party-Scripte

## CI/CD

- GitHub Actions: 9 Routen + 14 Assets getestet
- PHP-Syntax über alle `.php`-Dateien
- FTP-Deploy mit 3 Versuchen + Fallback-Protokoll

## Was redaktionell noch zu füllen ist

Damit die Seite „warm" wird statt nur strukturell korrekt:

- `$upcomingHeading`, `$upcomingLead`, `$spielzeitHeading`, `$spielzeitLead`,
  `$vereinHeading`, `$vereinLead` auf der Startseite (aktuell `null`)
- `$mitgliedHeading`, `$mitgliedLead`, `$mitgliedCtaLabel` für die Mitglied-Band
- `$productionsPageHeading`, `$productionsPageLead` für /spielzeit/
- `$archive`-Array für /spielzeit/ Archiv-Timeline
- `$chronik`-Array für /verein/ Chronik-Timeline
- Vorstand- und Ensemble-Fotos (avatare rendern automatisch Initialen)
- Echte Social-Media-URLs (`$siteConfig['social']` ist Platzhalter)
- FAQ-Texte und Pressestimmen für die Märchenseite (Sektionen derzeit weggelassen)