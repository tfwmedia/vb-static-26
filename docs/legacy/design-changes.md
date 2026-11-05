# Design-System

## Design-Philosophie

Das Design folgt einem **Premium-Minimalist**-Ansatz: Reduktion auf das Wesentliche, kombiniert mit hochwertiger Haptik durch Tiefe und Bewegung. Inspiriert von Apples Design-Sprache: klares Layout, großzügige Weißräume, typografische Hierarchie und dezente Animationen.

## CSS Custom Properties (Design-Tokens)

Alle Design-Entscheidungen sind als CSS Custom Properties in `:root` definiert (`assets/css/main.css`).

### Farben

```css
:root {
    --bg: #f5f5f7;            /* Seitenhintergrund: Neutrales Off-White */
    --surface: #ffffff;        /* Karten, Container: Reinweiß */
    --surface-soft: #fbfbfd;   /* Highlight-Sektionen: Sanftes Weiß */
    --ink: #1d1d1f;            /* Haupttext: Deep Charcoal */
    --ink-muted: #86868b;      /* Sekundärtext: Muted Gray */
    --line: #d2d2d7;           /* Rahmen, Trennlinien */
    --brand: #c31e2e;          /* Volksbühne-Rot: Hauptakzent */
    --brand-deep: #a51927;     /* Dunkleres Rot: Hover-Zustände */
    --accent: var(--brand);    /* Alias für Markenfarbe */
    --accent-soft: #f9e8ea;    /* Sanftes Rot: Hintergrund-Akzent */
    --success: #2d814d;        /* Erfolgsmeldungen */
}
```

### Abstände (Spacing)

```css
:root {
    --space-2xs: 4px;
    --space-xs: 8px;
    --space-sm: 12px;
    --space-md: 16px;
    --space-lg: 24px;
    --space-xl: 40px;
    --space-2xl: 60px;
    --space-3xl: 80px;
}
```

### Radien

```css
:root {
    --radius-sm: 8px;
    --radius-md: 12px;
    --radius-lg: 18px;
    --radius-xl: 24px;
    --radius-2xl: 32px;
}
```

### Schatten

```css
:root {
    --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.04);
    --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.08);
    --shadow-lg: 0 12px 24px rgba(0, 0, 0, 0.12);
}
```

### Layout

```css
:root {
    --container: 1024px;        /* Maximale Inhaltsbreite */
    --header-height: 72px;      /* Header-Höhe */
}
```

## Typografie

### Schriftarten

| Verwendung | Schriftart | Quelle |
|-----------|-----------|--------|
| Überschriften (h1, h2, h3) | Playfair Display | Selbstgehostet, 8 WOFF2-Subset-Dateien |
| Fließtext | System-Font-Stack | `-apple-system, BlinkMacSystemFont, "SF Pro Text", "Helvetica Neue", Arial, sans-serif` |

```css
--font-heading: "Playfair Display", Georgia, "Times New Roman", serif;
--font-body: -apple-system, BlinkMacSystemFont, "SF Pro Text", "Helvetica Neue", Arial, sans-serif;
```

### Playfair Display

Die Schrift wird in 8 WOFF2-Subset-Dateien selbstgehostet (`assets/fonts/`):

| Datei | Stil | Subset |
|-------|------|--------|
| `playfair-display-normal-latin.woff2` | Normal | Latin |
| `playfair-display-normal-latin-ext.woff2` | Normal | Latin Extended |
| `playfair-display-normal-cyrillic.woff2` | Normal | Cyrillic |
| `playfair-display-normal-vietnamese.woff2` | Normal | Vietnamese |
| `playfair-display-italic-latin.woff2` | Kursiv | Latin |
| `playfair-display-italic-latin-ext.woff2` | Kursiv | Latin Extended |
| `playfair-display-italic-cyrillic.woff2` | Kursiv | Cyrillic |
| `playfair-display-italic-vietnamese.woff2` | Kursiv | Vietnamese |

Gewichtsbereich: 400–900, `font-display: swap`.

### Schriftgrößen (Responsive Scaling)

Alle Größen verwenden `clamp()` für fließende Skalierung:

| Element | Formel | Ergebnis |
|---------|--------|----------|
| h1 | `clamp(2.5rem, 6vw + 1rem, 4.5rem)` | ~40px → ~72px |
| h2 | `clamp(2rem, 4vw + 1rem, 3rem)` | ~32px → ~48px |
| h3 | `clamp(1.5rem, 3vw + 0.5rem, 2rem)` | ~24px → ~32px |
| Lead | `clamp(1.125rem, 2vw + 0.5rem, 1.5rem)` | ~18px → ~24px |
| Body | `16px` (fest) | Basisgröße |
| Eyebrow | `0.75rem` | ~12px, uppercase, spacing 0.18em |

Zeilenhöhen:

| Kontext | Wert |
|---------|------|
| Überschriften | 1.1 |
| Lead-Text | 1.4 |
| Fließtext | 1.6 |
| Rechtstexte | 1.72 |

## Komponenten

### Header (`.site-header`)

- **Position**: Sticky, `top: 0`, z-index 100
- **Höhe**: `var(--header-height)` = 72px
- **Glassmorphism**: Hintergrund 80% weiß, `backdrop-filter: saturate(180%) blur(20px)`
- **Rahmen**: 1px unten, `var(--line)`
- **Mobile Navigation**: Overlay, gesteuert durch `data-nav-open="true|false"`
- **Navigation-Toggle**: Button mit `aria-expanded`, `aria-controls`
- **CTA-Button**: Rechts, nur sichtbar ab 48rem Breakpoint

### Hero (`.hero`)

- **Padding**: `var(--space-2xl)` (60px) auf Mobile, `var(--space-3xl)` (80px) ab 48rem
- **Grid**: `.hero-grid` — einspaltig auf Mobile, zweispaltig ab 48rem
- **Varianten**: `.hero-inner` für Unterseiten (reduzierter Padding-Bottom)
- **Hero-Bild**: `.hero-visual img` — max 42rem breit, `border-radius: var(--radius-lg)`

### Split-Layout (`.split`)

- Zweispaltiges Grid für Medien + Text
- Einspaltig auf Mobile, zweispaltig ab 48rem
- Gap: `var(--space-2xl)` auf Mobile, `5.5rem` ab 80rem

### Event-Meta (`.event-meta`)

- Sidebar-Karte für Veranstaltungsdetails
- Oberer Akzent-Rand: 3px, Markenfarbe
- Enthält: Poster, Termine, Preise
- Nur auf `weihnachtsmaerchen.php`

### Kontakt-Karte (`.contact-card`)

- Grid-Layout: einspaltig auf Mobile, `1fr auto` ab 48rem
- Hintergrund: `var(--surface)`, Rahmen, Schatten
- Responsive Padding per `clamp()`

### Buttons

| Klasse | Stil | Hover |
|--------|------|-------|
| `.btn-primary` | Markenfarbe Hintergrund, weißer Text | Hintergrund wird schwarz |
| `.btn-secondary` | Heller Hintergrund, Akzent-Text, Rahmen | Hintergrund wird grau |
| `.btn-tertiary` | Weißer Hintergrund, Akzent-Text, Rahmen | Rahmen wird dunkler |

Alle Buttons:
- Padding: `0.86rem 1.36rem`
- Radius: `0.24rem`
- Transition: 180ms für Hintergrund, Farbe, Rahmen, Transform
- Hover: `translateY(-1px)` (leichtes Anheben)

### Karten (`.card`)

- Hintergrund: `var(--surface)`
- Rahmen: 1px `var(--line)`
- Radius: `var(--radius-lg)`
- Padding: `var(--space-xl)`
- Hover: `translateY(-4px)` + stärkerer Schatten

### Footer (`.site-footer`)

- Dunkler Hintergrund: `#1a1614`
- Textfarbe: `#c4bab4`
- Linkfarbe: `#e5d5cf`, Hover: `#fff`
- Drei-Spalten-Grid ab 48rem (Kontakt, Adresse, Links)
- Copyright mit dynamischem Jahr

### Prosa (`.prose`)

- Maximale Breite: `74ch` für optimale Lesbarkeit
- Absätze: max `70ch`, `margin-bottom: var(--space-lg)`
- Überschriften: `margin-top: var(--space-xl)` für Abstand

### Fakten-Liste (`.facts`)

- Grid-Layout mit `var(--space-md)` Gap
- Linker Rand: 2px, Markenfarbe 45% gemischt mit Line
- `<dt>`: fett (800)
- `<dd>`: muted color

## Responsive Breakpoints

| Breakpoint | Breite | Auswirkung |
|-----------|--------|-----------|
| Mobile | < 48rem (768px) | Einspaltiges Layout, Hamburger-Menü, Navigation als Overlay |
| Tablet/Desktop | ≥ 48rem (768px) | Zweispaltiges Grid, Desktop-Navigation sichtbar, CTA-Button |
| Wide | ≥ 80rem (1280px) | Größerer Gap (5.5rem) in Hero und Split-Layout |

Wichtige Änderungen bei 48rem:

- Header: Grid-Layout, Navigation wird statisch, Toggle verschwindet
- Hero/Split: Zweispaltiges Grid
- Kontakt-Karte: Zweispaltig (`1fr auto`)
- Footer: Dreispaltiges Grid
- Karten-Grid: Dreispaltig

## Animationen

### Reveal-Animation

Elemente mit `data-reveal`-Attribut erscheinen beim Scrollen:

```css
[data-reveal] {
    opacity: 0;
    transform: translateY(12px);
    transition: opacity 420ms ease, transform 420ms ease;
}

[data-reveal].is-visible {
    opacity: 1;
    transform: translateY(0);
}
```

- Ausgelöst per `IntersectionObserver` in `assets/js/main.js`
- Schwellwert: 15% Sichtbarkeit, `rootMargin: "0px 0px -8% 0px"`
- Einmalige Animation: Element wird nach dem Reveal nicht mehr beobachtet
- Fallback: Falls `IntersectionObserver` nicht verfügbar, werden alle Elemente sofort sichtbar

### Reduced Motion

```css
@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        transition-duration: 0.01ms !important;
        scroll-behavior: auto !important;
    }
    [data-reveal] {
        opacity: 1;
        transform: none;
    }
}
```

Alle Animationen werden deaktiviert, Reveal-Elemente sind sofort sichtbar.

## Barrierefreiheit

- **Skip-Link**: `<a class="skip-link" href="#main-content">` — springt zum Hauptinhalt
- **Focus-Styles**: `outline: 3px solid var(--brand); outline-offset: 3px` für `a:focus-visible` und `button:focus-visible`
- **Touch-Targets**: Alle Navigations-Links haben `min-height: 48px`
- **Alt-Texte**: Alle Bilder haben beschreibende `alt`-Attribute
- **ARIA**: Navigation hat `aria-label="Hauptnavigation"`, aktiver Link hat `aria-current="page"`
- **Semantisches HTML**: `<header>`, `<nav>`, `<main>`, `<footer>`, `<section>`, `<aside>`, `<address>`

## Bilddateien

Alle Bilder in `assets/img/`:

| Datei | Format | Verwendung |
|-------|--------|-----------|
| `vb-logo.png` | PNG | Logo im Header, Apple Touch Icon |
| `vb-icon.png` | PNG | Favicon (32×32) |
| `vorhang.jpg` / `vorhang.webp` | JPEG/WebP | Hero-Bild Startseite |
| `bsm.jpeg` / `bsm.png` / `bsm.webp` | JPEG/PNG/WebP | Weihnachtsmärchen-Poster |
| `weihnachtsmaerchen-2025.jpeg` / `.webp` | JPEG/WebP | Archiv: Märchen 2025 |
| `weihnachtsmaerchen-2026.jpeg` / `.webp` | JPEG/WebP | Märchen 2026 |
| `hero-stage.svg` | SVG | Decorativ |
| `logo-mark.svg` | SVG | Decorativ |
| `maerchen-stage.svg` | SVG | Decorativ |
| `og-image.svg` | SVG | Open Graph Placeholder |

Icons und Logos (`vb-icon.png`, `vb-logo.png`) werden nicht zu WebP konvertiert.
