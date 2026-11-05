# Content-Workflow

## Überblick

Inhalte werden im Projekt bewusst simpel gepflegt:

- Seitentexte direkt in den jeweiligen PHP-Seiten
- Rechtstexte als externe HTML-Fragmente unter `content/legal/`
- Bilder und Medien unter `assets/img/`

## Weihnachtsmärchen aktualisieren

Die Seite `weihnachtsmaerchen.php` enthält alle veranstaltungsspezifischen Inhalte direkt im PHP-Template. Bei einem Jahreswechsel oder neuer Inszenierung müssen folgende Stellen angepasst werden:

### 1. `weihnachtsmaerchen.php`

- **Titel und Untertitel**: `<h1>` und `.eyebrow` Text
- **Inszenierung**: Name des Regisseurs
- **Termine**: `<ul>` im `.event-meta` Bereich, inklusive Wochentage und Uhrzeiten
- **Preise**: Vorverkauf und Abendkasse
- **Inhaltsbeschreibung**: `<div class="prose">` Bereich
- **Strukturierte Daten** (`$structuredData`):
  - `EventSeries.name`
  - `EventSeries.subEvent` (Startdatum, ISO 8601 Format)
  - `AggregateOffer.lowPrice` / `highPrice`

### 2. `index.php`

- **Highlight-Block** (`section-highlight`): Titel, Beschreibung, Termine, Preise
- **Strukturierte Daten**: ggf. anpassen

### 3. Bilder

- Neues Poster in `assets/img/` ablegen (JPEG + WebP)
- Bildreferenzen in beiden PHP-Dateien aktualisieren
- Nach dem Hinzufügen neuer Bilder den Build ausführen: `node scripts/build.js`

### 4. Meta-Daten

In beiden Seiten die `$meta`-Variablen prüfen:

- `title`: Seitentitel
- `description`: Meta-Description
- `og_image`: Pfad zum neuen Poster

## Rechtstexte (Impressum/Datenschutz)

### Speicherort

```
content/legal/
├── impressum.html
└── datenschutz.html
```

Diese HTML-Fragmente werden in `impressum.php` und `datenschutz.php` geladen und durch `sanitize_legal_html()` bereinigt.

### Manuelles Bearbeiten

Die Dateien können direkt mit einem Texteditor bearbeitet werden. Es sind reine HTML-Fragmente (kein vollständiges HTML-Dokument).

Erlaubte HTML-Elemente:

| Tags | Verwendung |
|------|-----------|
| `p`, `br` | Absätze, Zeilenumbrüche |
| `strong`, `em`, `b`, `i`, `u`, `small` | Textauszeichnung |
| `ul`, `ol`, `li` | Listen |
| `a` | Links (nur `https://`, `mailto:`, `tel:`, `/` Pfade) |
| `h2`, `h3`, `h4` | Überschriften (`h1` wird automatisch zu `h2`) |
| `address` | Adressangaben |

Blockierte Elemente: `script`, `style`, `iframe`, `object`, `embed`, `form`, `input`, `button`.

### Sync aus Live-Quelle

Das Script `scripts/sync-legal-content.php` lädt die aktuellen Rechtstexte von der bestehenden Live-Website:

```bash
php scripts/sync-legal-content.php
```

#### Funktionsweise

1. **Quell-URLs**:
   - Impressum: `https://volksbuehne-worms.de/impressum/`
   - Datenschutz: `https://volksbuehne-worms.de/datenschutz/`

2. **Download-Reihenfolge** (mit Fallbacks):
   - Zuerst: `/tmp/vb-{slug}.html` (lokaler Cache)
   - Dann: `file_get_contents($url)` (direkter Download)
   - Dann: `wget -qO- $url` (Shell-Fallback)
   - Bei Fehler: Script bricht mit Exit-Code 1 ab

3. **DOM-Extraktion**:
   - Das Script sucht den ersten `elementor-widget-text-editor` Container
   - Extrahiert den Inhalt des `elementor-widget-container` div
   - Entfernt die erste `<h1>` (da die Seite bereits eine eigene H1 hat)

4. **Link-Normalisierung**:
   - Interne absolute Links (`https://volksbuehne-worms.de/...`) werden zu relativen Pfaden (`/...`)
   - Externe Links bleiben unverändert

5. **Speicherung**:
   - Fragment wird als HTML in `content/legal/{slug}.html` gespeichert
   - Konsolenausgabe: `Synced {slug} -> {pfad}`

## Redaktionsablauf (Checkliste)

Vor jedem Content-Update:

- [ ] Inhalte lokal aktualisieren (oder Legal-Sync ausführen)
- [ ] Lokal im Browser prüfen: `php -S 127.0.0.1:8080 scripts/ci-router.php`
- [ ] Rechtschreibung und Typografie kontrollieren
- [ ] Datums- und Preisangaben verifizieren
- [ ] Kontaktinformationen auf Aktualität prüfen
- [ ] Interne Links auf Funktion testen
- [ ] Mobile Darstellung prüfen (Responsive)
- [ ] Änderungen committen und deployen

## Bei Jahreswechsel

Folgende Punkte sollten bei einem Jahreswechsel oder neuer Spielzeit überprüft und angepasst werden:

1. **Weihnachtsmärchen**: Alle Inhalte in `weihnachtsmaerchen.php` (siehe oben)
2. **Startseite**: Highlight-Block in `index.php`
3. **Bilder**: Neues Poster/Visuals in `assets/img/`
4. **Meta-Daten**: Title, Description, og_image in beiden Seiten
5. **Strukturierte Daten**: EventSeries mit neuen Daten
6. **Rechtstexte**: Sync prüfen (`php scripts/sync-legal-content.php`)
7. **Copyright-Jahr**: Wird automatisch per `date('Y')` gesetzt
8. **Build**: `node scripts/build.js` für neue WebP-Bilder ausführen
