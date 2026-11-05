# Design-System "Programmheft"

Design-Sprache des 2026er Redesigns: Theater-Programmheft trifft moderne Editorial-Typografie. seriös, flächig, druckgerecht. Alles in `src/styles/global.css`, kein CSS-Framework.

## Farben (CI Volksbühne, 1:1 aus der Vorgängersite)

| Token | Wert | Verwendung |
|-------|------|-----------|
| `--bg` | `#faf9f7` | Seitenhintergrund (warmes Papier) |
| `--surface` / `--surface-alt` / `--surface-deep` | `#ffffff` / `#f0eeeb` / `#ece9e3` | Karten, Sektions-Varianten |
| `--ink` / `--ink-2` / `--ink-muted` | `#1a1a1a` / `#4a4a48` / `#6b6b68` | Textstufen (muted = WCAG AA auf Weiß) |
| `--line` / `--line-strong` | `#e0ddd8` / `#c9c5be` | Hairlines |
| `--brand` / `--brand-hover` / `--brand-deep` | `#c31e2e` / `#a01825` / `#7d121c` | CI-Rot (Kontrast auf Weiß 5,9:1) |
| `--brand-soft` / `--brand-softer` | `#fdf0f1` / `#fef6f7` | Rote Flächen-Tints |
| `--success` / `--warning` | `#2d814d` / `#b56a00` | Status-Chips |

Sektionen: `.section` (Papier), `--alt` (Graupapier + Hairlines), `--surface`, `--ink` (dunkles Band), `--brand` (flaches CI-Rot, **kein Verlauf**).

## Typografie

| Rolle | Font | Größe |
|-------|------|-------|
| Display/H1 Hero | Helvetica Neue, 800 | `--text-hero: clamp(2.25rem, 1.8rem + 1.8vw, 4.25rem)` |
| H1 Unterseiten | dito | `clamp(1.9rem, 1.6rem + 1.6vw, 3.5rem)` + `hyphens: auto` |
| H2 | dito | `--text-2xl: clamp(2rem, …, 2.875rem)` |
| H3 / Karten-H2 | dito | `--text-xl: clamp(1.5rem, …, 2rem)` |
| Body | Inter (self-hosted, variabel) | 17px / 1.7 |
| Eyebrow | Inter 700 | 13px, uppercase, 0.16em Sperrung, roter Kurzstrich davor |

Regeln:
- H1 bricht **natürlich** (kein `text-wrap: balance` auf h1) — Balance erzeugt Waisen wie "1908."
- Kontrollierte Umbräche im Markup (`<br>`) nur wo redaktionell gewollt ("Theater in Worms," / "seit 1908")
- `text-wrap: balance` für h2-h4
- Headings erhalten `letter-spacing: -0.02em`, `line-height: 1.05`

## Signaturelemente

- **Eyebrow mit Kurzstrich**: `.eyebrow::before` (28×2px rot); auf `--brand`/`--ink`-Sektionen `currentColor` + 0.7 opacity
- **Event-Strip**: `.event-strip` — Ticket-Streifen unter dem Märchen-Hero (Datum/Ort/Tickets/CTA), rote 3px-Oberkante, 1px-Hairlines via `gap: 1px` + Line-Background, 4→2→1 Spalten
- **Spielplan**: `.schedule` — 2px-Tinten-Topline, Gruppen je Produktion, Zeilen grid `4.5rem 1fr auto`, alles `align-items: start` (kein Mittig-Schweben)
- **Poster**: `.poster` — feiner Rahmen, Caption mit rotem Abschlusstrich (kein Offset-Farbblock)
- **Produktions-Karten**: `.production-card` (`height: 100%` + `.facts { margin-top: auto }` → Footer und Faktenzeilen bündig über die Kartenreihe)
- **Stats**: `.stat-row` mit 2px-Tintenlinien oben/unten, Zahlen in CI-Rot Helvetica 800

## Radien & Buttons

Radien druckgerecht scharf: sm/md = 2px, lg = 4px (nur Chips/Avatare rund). Buttons: `.btn` (sm/md/lg), `primary` (rot), `secondary` (outline), `tertiary` (Textlink rot), `light` (weiß auf dunklen Bändern).

## Motion & A11y

- Reveal: `[data-reveal]` fade-up 14px via IntersectionObserver, Staffelung `--reveal-delay` (90ms-Stufen); `prefers-reduced-motion` → sofort sichtbar, keine Sekundentakt-Animationen
- Countdown tickt bei reduced-motion nur minütlich
- Fokus: `:focus-visible` doppelte Focus-Ring-Technik (bg + brand), Skip-Link
- Kontrast: ink-muted dunkel genug für AA; Brand-Rot auf Weiß 5,9:1
- Tap-Targets: Inline-Links mindestens 24px Klickhöhe über `padding-block` (WCAG 2.5.8)
- Mobile-Menü: natives `<dialog>` mit Backdrop-Close, Fokus-Handling durch den Browser
