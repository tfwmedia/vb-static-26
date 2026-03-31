# Design-Änderungen (Redesign 2026)

Dieses Dokument beschreibt die wesentlichen visuellen und UX-seitigen Änderungen des Redesigns in `vb-static-26`.

## Zielbild des Redesigns

Die Website wurde auf eine moderne, ruhige Theater-Ästhetik umgestellt:

- klarere visuelle Hierarchie,
- bessere Lesbarkeit auf allen Viewports,
- konsistentes Komponenten-Design,
- höherer Wiedererkennungswert der Marke,
- solide Accessibility-Baseline.

## 1) Neues visuelles System (Design Tokens)

Die zentrale Basis liegt in `assets/css/main.css` (`:root`):

- **Farben** mit warmen Flächen und bühnenrotem Akzent (`--brand`, `--brand-deep`),
- **Typografie** mit Serif-Headlines (`--font-heading`) und neutraler Sans im Fließtext (`--font-body`),
- **Spacing-Skala** von `--space-2xs` bis `--space-3xl`,
- **Radius- und Schattenstufen** für ein einheitliches Karten-/Panel-Verhalten,
- **Container-Breite** über `--container` für konsistente horizontale Rhythmik.

Dadurch können neue Sektionen und Komponenten ohne visuelle Brüche ergänzt werden.

## 2) Header & Navigation

### Struktur

- Sticky Header (`.site-header`) mit leichter Transparenz/Blur.
- Brand-Bereich mit Logo und Tagline „Theater seit 1908“.
- Mobile Menüsteuerung per Toggle-Button.

### Interaktion (`assets/js/main.js`)

Das Navigationsverhalten wurde robuster gemacht:

- `aria-expanded` wird sauber synchronisiert,
- Menü schließt bei Klick außerhalb,
- Menü schließt bei `Escape`,
- Menü schließt nach Linkklick,
- auf Desktop-Breakpoint (`min-width: 48rem`) wird der Mobile-State zurückgesetzt.

## 3) Seitenlayout & Content-Inszenierung

### Startseite (`index.php`)

Neue Dramaturgie der Inhalte in klar getrennten Blöcken:

1. **Hero** mit Leitbotschaft + CTA,
2. **„Was uns ausmacht“** als 3-Karten-Raster,
3. **Highlight-Sektion** für das Weihnachtsmärchen (Split-Layout mit Poster + Fakten),
4. **Kontakt-Sektion** als markantes Conversion-Element.

### Weihnachtsmärchen (`weihnachtsmaerchen.php`)

- Hero mit Titel/Untertitel und direkten Kontakt-CTAs,
- seitliches Event-Panel (`.event-meta`) mit Terminen/Preisen,
- klar strukturierter Inhaltsabschnitt,
- abschließender Gruppenkontakt im selben visuellen Muster wie auf der Startseite.

## 4) Komponenten & Muster

Einheitliche UI-Bausteine wurden geschärft:

- Buttons mit festen Varianten (`.btn-primary`, `.btn-secondary`, `.btn-tertiary`),
- Karten- und Panel-Styling über gemeinsame Radius-/Border-/Shadow-Logik,
- wiederverwendbare Abschnittsmuster (`.section`, `.section-highlight`, `.section-contact`),
- Footer als dreispaltige Informationszone mit deutlich kontrastierter Fläche.

## 5) Motion, Responsiveness, Accessibility

### Motion

- Reveal-Animationen über `[data-reveal]` + `IntersectionObserver`.
- Bei fehlendem Observer werden Elemente direkt sichtbar geschaltet.

### Reduced Motion

- `@media (prefers-reduced-motion: reduce)` deaktiviert Animationen/Transitions weitgehend.

### Accessibility

- Skip-Link für Tastaturnutzung,
- klare Fokus-Indikatoren über `:focus-visible`,
- semantische Bereiche (Header, Main, Footer, `aria-label`/`aria-labelledby`) in zentralen Layoutteilen.

## 6) SEO-/Meta-Konsistenz im neuen Look

Parallel zum visuellen Redesign bleiben technische SEO-Bausteine konsistent:

- zentrale Meta-Defaults,
- seitenindividuelle Überschreibungen,
- OG/Twitter-Meta aus den Layout-Partials,
- strukturierte Daten (JSON-LD) je nach Seitentyp.

So sind Design- und Auslieferungsqualität synchron umgesetzt.

## 7) Leitplanken für zukünftige Design-Änderungen

Bei Erweiterungen bitte bevorzugt:

1. vorhandene Tokens und Komponenten wiederverwenden,
2. neue Varianten in `main.css` nahe bestehender Muster ergänzen,
3. Mobile-First testen (inkl. Navigation und CTA-Lesbarkeit),
4. `prefers-reduced-motion` und Fokuszustände nicht regressiv verändern,
5. Inhalt + visuelle Änderungen gemeinsam dokumentieren (`docs/`).