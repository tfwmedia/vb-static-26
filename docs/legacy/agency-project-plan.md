# Implementierungs-Roadmap

Statusübersicht der Umsetzung nach Phasen. Grundlage: Optimierungsplan für die Volksbühne Worms Website (`vb-static-26`).

## Phase 1: Audit & Foundation

**Status: ✅ Abgeschlossen**

| Aufgabe | Status | Umsetzung |
|---------|--------|-----------|
| Technical SEO Audit | ✅ | HTML-Struktur, Heading-Hierarchie, Meta-Tags geprüft |
| Build Pipeline Setup | ✅ | esbuild für CSS/JS-Minifizierung, cwebp für WebP, `scripts/build.js` |
| HTML Structure Review | ✅ | Eine `H1` pro Seite, semantische HTML5-Elemente, logische Heading-Abfolge |

**Ergebnis**: Automatisierter Build-Prozess (`node scripts/build.js`) und saubere HTML-Struktur in allen 4 Seiten.

## Phase 2: Design System & UX

**Status: ✅ Abgeschlossen**

| Aufgabe | Status | Umsetzung |
|---------|--------|-----------|
| Typography & Spacing | ✅ | CSS Custom Properties, `clamp()` für responsive Schriftgrößen, 16px Basis |
| Mobile Navigation | ✅ | Hamburger-Menü, Overlay, Escape-Taste, Resize-Handling in `main.js` |
| Touch Targets (48px) | ✅ | `min-height: 48px` für Navigations-Links |
| Sticky Header | ✅ | `position: sticky`, Glassmorphism (`backdrop-filter`) |
| Accessibility (A11y) | ✅ | Skip-Link, ARIA-Attribute, `focus-visible` Styles, `alt`-Texte |
| Reduced Motion | ✅ | `@media (prefers-reduced-motion: reduce)` deaktiviert Animationen |

**Ergebnis**: Vollständiges Design-System mit Design-Tokens, 3 Breakpoints und Barrierefreiheits-Features.

## Phase 3: Content Strategy & SEO

**Status: 🟡 Teilweise umgesetzt**

| Aufgabe | Status | Umsetzung |
|---------|--------|-----------|
| Content Formatting | ✅ | Kurze Absätze, Listen, Fakten-Grid in Templates |
| Schema.org EventSeries | ✅ | `weihnachtsmaerchen.php`: EventSeries mit subEvent, AggregateOffer |
| Schema.org LocalBusiness | ✅ | Über `additionalType: LocalBusiness` in PerformingGroup |
| Schema.org WebSite | ✅ | In `index.php` |
| Open Graph / Twitter Cards | ✅ | Vollständige OG- und Twitter-Meta in `layout-start.php` |
| Trust-Elemente | ❌ Offen | Testimonials, "Ausverkauft"-Hinweise, Social Proof fehlen noch |
| Sitemap.xml | ❌ Offen | Keine Sitemap für Google Search Console vorhanden |
| robots.txt | ❌ Offen | Keine explizite robots.txt vorhanden |

**Ergebnis**: Strukturierte Daten und Meta-Tags vollständig. Trust-Elemente und Sitemap stehen noch aus.

## Phase 4: QA, Testing & Deployment

**Status: ✅ Abgeschlossen**

| Aufgabe | Status | Umsetzung |
|---------|--------|-----------|
| CI/CD Pipeline | ✅ | GitHub Actions: Test-Job + Deploy-Job |
| PHP-Syntax-Prüfung | ✅ | `php -l` für alle `.php`-Dateien in CI |
| Route-Tests (HTTP 200) | ✅ | Alle 4 Routen werden geprüft |
| Asset-Tests | ✅ | 6 kritische Assets auf Erreichbarkeit geprüft |
| 404-Handling | ✅ | Unbekannte Route → HTTP 404 |
| FTP-Deploy | ✅ | 3 Versuche mit Fallback-Protokoll, Exclude-Liste |
| Visuelle Tests | ✅ | Playwright: Desktop + iPhone 13 für Startseite und Märchen-Seite |
| Bildoptimierung im CI | ✅ | `jpegtran` (JPEG), `optipng` (PNG) lossless |

**Ergebnis**: Vollständige CI/CD-Pipeline mit automatisierten Tests und robustem Deployment.

## Zusammenfassung

| Phase | Status |
|-------|--------|
| Phase 1: Audit & Foundation | ✅ Abgeschlossen |
| Phase 2: Design System & UX | ✅ Abgeschlossen |
| Phase 3: Content Strategy & SEO | 🟡 Teilweise (Trust-Elemente, Sitemap offen) |
| Phase 4: QA, Testing & Deployment | ✅ Abgeschlossen |

### Offene Aufgaben (nach Priorität)

1. **Trust-Elemente**: Testimonials, "Ausverkauft"-Hinweise, Social Proof auf der Märchen-Seite
2. **Sitemap.xml**: Automatisch generierte Sitemap für Google Search Console
3. **robots.txt**: Explizite Steuerung für Suchmaschinen-Crawler
4. **AVIF-Unterstützung**: Zusätzliches Bildformat im Build-Prozess
5. **Lighthouse-Baseline**: Performance-Metriken dokumentieren und überwachen
