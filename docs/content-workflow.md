# Content-Workflow

Alle redaktionellen Inhalte liegen in `src/data/`. Regel: **Texte 1:1 pflegen**, nichts umformulieren — Änderungen kommen vom Verein (Mail/Telefon) oder aus der Live-Site.

## Termine ändern (häufigster Fall)

Datei: `src/data/events.ts`

Ein Eintrag:

```ts
{
  title: 'Die Löffelliste',            // exakter Produktionstitel (gruppiert den Spielplan)
  subtitle: 'Saisonstück 2026',        // Zeile im Spielplan + Karten-Eyebrow
  date: '2026-10-09',                  // ISO
  time: '20:00',
  venue: 'Volksbühne — kleines Theater', // oder 'Das Wormser'
  ticketUrl: 'https://www.ticket-regional.de/stage_7698.php?timeID=1139633', // null = ausverkauft/kein VVK
  price: '25,00 €',
  priceNote: 'VVK',
  status: 'tickets',                   // 'tickets' | 'sold_out' | 'premiere' | 'few_left'
}
```

Auswirkung: Startseite (Countdown nimmt automatisch die nächste künftige Vorstellung), Spielplan-Tabelle auf `/spielzeit/`, JSON-LD bleibt unberührt (Märchen-SubEvents stehen separat in `weihnachtsmaerchen.astro`).

Reihenfolge: aufsteigend nach Datum halten.

## Produktionen / Weihnachtsmärchen

- `src/data/productions.ts`: Titel, Lead (Volltext), Autor, Regie, Bilder, Ticket-URLs
- Weihnachtsmärchen-Seitentexte (Inhalt-Prosa, Schulvorstellungen-Hinweis, Gruppen-Kontakt) stehen direkt in `src/pages/weihnachtsmaerchen.astro` — dort ändern
- Wichtig: Ticket-Link der Märchen-Seite gilt nur für die **Freiverkaufsvorstellungen** (01./02.12.); geschlossene Schulvorstellungen (ab Montagvormittag, täglich 2, nicht frei verkäuflich) werden im Abschnitt `#schulvorstellungen` kommuniziert und laufen über den Märchen-Kontakt

## Vorstand / Verein

`src/data/vorstand.ts`: Rolle, Name, Notiz, optional `photo` (dann Initialen-Avatar statt Kürzel). Chronik-Einträge ergänzen, sobald der Verein eine Liste liefert (`chronik`-Array, Rendering automatisch sichtbar).

## Bankverbindungen / Kontaktdaten

`src/data/site.ts` (`vereinBank`, `email`, `phone*`, `address`, `social`). Änderungen wirken global (Footer, Kontakt-Seite, Verein-Seite, JSON-LD).

## Rechtstexte (Impressum/Datenschutz)

Quelle: `content/legal/*.html` (1:1-Fragmente). Zwei Wege:

1. **Von der Live-Site ziehen**: `npm run sync:legal` (lädt volksbuehne-worms.de/impressum + /datenschutz, extrahiert den Text-Container, macht interne Links relativ).
   Achtung: Überschreibt lokale Korrekturen. Beispiel: "Postfach 2317" wurde 2026-11 lokal entfernt — wenn die Live-Quelle es noch enthält, kommt es beim Sync zurück (also vorher auf der Live-Quelle korrigieren).
2. **Direkt editieren**: `content/legal/impressum.html` bzw. `datenschutz.html` anpassen (HTML mit `<br>`-Struktur, Erlaubt-Filter siehe `src/lib/legal.ts`-Kommentar).

Rendering: `set:html` in den Legal-Seiten, Absicherung über erlaubte Tags/Hrefs ist bewusst einfach gehalten (Quelle ist der Verein selbst).

## Neue Seite anlegen

1. `src/pages/slug.astro` anlegen (build.format `directory` → `/slug/` + `index.html`)
2. BaseLayout nutzen, `PageMeta` füllen (title ≤ 60 Zeichen, description 120-160, canonicalPath mit trailing slash)
3. JSON-LD: BreadcrumbList mindestens; weitere Typen nach Bedarf
4. `public/sitemap.xml`: Route ergänzen
5. `scripts/audit-seo-responsive.mjs` → `ROUTES` ergänzen
6. Navigation (`src/data/site.ts` → `navigation`) und Footer-Links prüfen
7. `npm run build && npm run audit:see development.md` — Audit muss Exit 0 liefern

## Bilder

Neue Motive nach `public/assets/img/` (bereits optimiert: WebP für Fotos, PNG für Logos/Social Cards). `<img>` immer mit `width`/`height` + `alt` (deko: `alt=""`). Hero-Bilder der Startseite: `preloadHero`-Prop in BaseLayout.
