/**
 * Vorstand des Vereins — Quelle: Impressum. `photo` ist optional und
 * kann später ergänzt werden — Initialen-Placeholder rendert automatisch.
 * 1:1 aus includes/site-config.php portiert.
 */

export interface Person {
  role: string;
  name: string;
  note: string;
  photo: string | null;
}

export const vorstand: Person[] = [
  {
    role: '1. Vorsitzender',
    name: 'Gerhard Baum',
    note: 'Verantwortlich i. S. d. § 55 Abs. 2 RStV.',
    photo: null,
  },
  {
    role: '2. Vorsitzende',
    name: 'Nicole Schmitt',
    note: 'Koordination Spielzeit & Ensemble.',
    photo: null,
  },
  {
    role: 'Schatzmeisterin',
    name: 'Roswitha Ramb',
    note: 'Finanzen, Mitgliederverwaltung, Förderung.',
    photo: null,
  },
];

/**
 * Stationen der Vereinschronik. Bleibt leer, bis der Verein eine
 * autoritative Liste pflegt. Rendering nur, wenn chronik nicht leer.
 */
export const chronik: Array<{ year: string; event: string }> = [];
