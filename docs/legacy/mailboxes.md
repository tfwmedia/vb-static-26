# E-Mail-Adressen

Stand: 13.05.2026
Quelle: IONOS E-Mail-Verwaltung

Diese Datei dokumentiert die aktuell in IONOS sichtbaren E-Mail-Adressen **ohne Zugangsdaten**.

## Postfächer

| Typ | Adresse | Hinweis |
| --- | --- | --- |
| Mail Basic | `buchhaltung@volksbuehne-worms.de` | Postfach für Buchhaltung |
| Mail Basic | `info@volksbuehne-worms.de` | Hauptkontakt (verwendet in `site-config.php` als `$siteConfig['email']`) |
| Mail Basic | `kontakt@volksbuehne-worms.de` | Märchen-Kontakt (verwendet als `$siteConfig['maerchen_contact_email']`) |

## Weiterleitungen

| Typ | Adresse | Ziel |
| --- | --- | --- |
| Weiterleitung | `webmaster@volksbuehne.info` | `mail@timfriedrichweber.de` |

## Hinweise

- Es werden bewusst **keine Passwörter oder sonstige Zugangsdaten** im Repository gespeichert.
- Änderungen an Postfächern oder Weiterleitungen sollten nach Anpassungen in IONOS auch hier dokumentiert werden.
- Die E-Mail-Adressen `info@` und `kontakt@` werden zentral in `includes/site-config.php` referenziert und in den Templates verwendet.
