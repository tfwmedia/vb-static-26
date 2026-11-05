# SEO & Responsive

Das Audit-Skript `scripts/audit-seo-responsive.mjs` prüft den Build deterministisch gegen den lokalen Server (und in CI als Deploy-Gate). Exit 1 bei HOCH/KRITISCH.

## Geprüfte Kriterien

**SEO (pro Route, 9 Routen):**

| Check | Kriterium |
|-------|-----------|
| Title | vorhanden, 15-65 Zeichen, seiteneindeutig |
| Description | 70-165 Zeichen (nur Index-Seiten), eindeutig |
| Canonical | absolut, exakt `ORIGIN + Pfad` (trailing slash) |
| Robots | Index-Seiten ohne noindex; 404 + tickets-kaufen mit noindex |
| hreflang | `de` + `x-default` auf Index-Seiten |
| Open Graph | og:title/description/type/url/image/image:alt/locale/site_name vollständig; **og:image nicht .svg** |
| Twitter | twitter:card |
| JSON-LD | alle Blöcke parsebar (JSON.parse), Typen werden inventarisiert |
| Headings | genau 1 H1, keine Level-Sprünge |
| Bilder | alt-Attribut überall, width/height gesetzt (CLS) |
| Interne Links | alle hrefs → 200 (auch .xml), Anker-Ziele vorhanden (auch seitenübergreifend) |
| Sitemap/Robots | Sitemap enthält alle Index-Routen, keine Fremden; Sitemap-Verweis in robots.txt |
| lang | `html lang="de"` |

**Responsive (9 Viewports: 320-1920px, pro Route):**

| Check | Kriterium |
|-------|-----------|
| Horizontaler Overflow | `scrollWidth > clientWidth + 1` — bei ≤430px HOCH |
| Schriftgröße | sichtbarer Text < 12px → NIEDRIG |
| Tap-Targets | a/button < 24×24px auf ≤430px → NIEDRIG (Inline-Links im Fließtext nach Padding-Fix größtenteils behoben) |

**Redirect-Stub** `/tickets-kaufen/` wird speziell behandelt: kein Navigation-Follow (der Meta-Refresh führt zum Shop), stattdessen Roh-Fetch-Prüfung von noindex + exaktem Meta-Refresh-Ziel.

## og-Images: Prozess

- Quellen: `public/assets/img/og-*.svg` (1200×630, CI-Designs)
- Rendering: Playwright öffnet die SVG-Datei bei Viewport 1200×630 und macht einen Clip-Screenshot → `og-*.png`
- Meta-Referenzen zeigen **ausschließlich auf die PNGs** (SVG wird von Facebook/X/LinkedIn/WhatsApp nicht gerendert)
- Bei Design-Änderung: SVG anpassen → PNG neu rendern → Commit

```bash
node -e "…Playwright-Screenshot pro SVG…"   # siehe Chat-Historie/CI, ~10 Zeilen
```

## Standards für neue Seiten (Checkliste)

- [ ] PageMeta vollständig (title ≤ 60, description 120-160, canonicalPath mit trailing slash)
- [ ] JSON-LD: BreadcrumbList mindestens, weitere Typen passend
- [ ] genau ein H1 mit relevantem Keyword, h2/h3 ohne Sprünge
- [ ] Bilder: alt, width/height, webp wo möglich
- [ ] Sitemap-Eintrag + Audit-ROUTES-Eintrag
- [ ] Audit Exit 0
- [ ] Mobile-Sicht geprüft (390px) + FullPage-Screenshot in shots/

## Akzeptierte Restposten (bewusst, dokumentiert)

- Breadcrumb-/Inline-Links mit <24px Höhe sind als Inline-Links im Fließtext von der WCAG-2.5.8-Regel ausgenommen; Hauptrouten sind gepaddet
- Rechtstext-Fragmente enthalten `<br>`-Struktur aus dem CMS-Import — Zeilenumbruch-Dichte ist redaktionelle Quelle, nicht Layout-Fehler
- og:image-Dateigröße (~200-340 KB/PNG) ist für Social-Crawler unkritisch
