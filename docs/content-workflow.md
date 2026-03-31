# Content-Workflow

## Überblick

Inhalte werden im Projekt bewusst simpel gepflegt:

- Seitentexte direkt in den jeweiligen PHP-Seiten,
- Rechtstexte als externe HTML-Fragmente unter `content/legal/`,
- Bilder/Medien unter `assets/img/`.

## Weihnachtsmärchen-Inhalte aktualisieren

Die Seite `weihnachtsmaerchen.php` enthält die veranstaltungsspezifischen Inhalte direkt:

- Titel und Untertitel,
- Termine,
- Preise,
- Inhaltsbeschreibung,
- Kontaktbereich.

Zusätzlich sollten bei einem Jahreswechsel folgende Stellen geprüft/angepasst werden:

1. `weihnachtsmaerchen.php`: sichtbare Texte + strukturierte Daten (`EventSeries`, `subEvent`).
2. `index.php`: Highlight-Block zum aktuellen Märchen.
3. Bilddateien in `assets/img/` (Poster/Visuals) und Referenzen in den Seiten.
4. Meta-Daten (`title`, `description`, ggf. `og_image`) pro Seite.

## Rechtstexte (Impressum/Datenschutz)

### Speicherort

- `content/legal/impressum.html`
- `content/legal/datenschutz.html`

Diese Fragmente werden in `impressum.php` und `datenschutz.php` geladen.

### Sync aus Live-Quelle

Es gibt ein Hilfsscript:

```bash
php scripts/sync-legal-content.php
```

Das Script:

- lädt Impressum/Datenschutz von `volksbuehne-worms.de`,
- extrahiert den relevanten Inhaltscontainer,
- normalisiert interne absolute Links auf relative Pfade,
- speichert die Ergebnisse lokal in `content/legal/`.

Falls `file_get_contents` fehlschlägt, nutzt das Script `wget` als Fallback.

## Sicherheit bei Rechtstexten

Vor der Ausgabe wird Legal-HTML durch `sanitize_legal_html(...)` gefiltert.

Dabei werden u. a.:

- riskante Tags entfernt (`script`, `style`, `iframe`, ...),
- nur erlaubte Tags und sichere Link-Schemata zugelassen,
- Attribute weitgehend entfernt,
- `h1` zu `h2` normalisiert (damit die Seitentitel-Hierarchie konsistent bleibt).

## Empfohlener Redaktionsablauf

1. Inhalte lokal aktualisieren (oder Legal-Sync ausführen).
2. Lokal im Browser gegenprüfen (`php -S 127.0.0.1:8080 scripts/ci-router.php`).
3. Besonders prüfen:
   - Rechtschreibung/Typografie,
   - Datums-/Preisangaben,
   - Kontaktinfos,
   - funktionierende interne Links,
   - korrekte Darstellung auf Mobilgeräten.
4. Änderungen committen und deployen.
