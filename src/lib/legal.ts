import { readFileSync } from 'node:fs';
import { fileURLToPath } from 'node:url';
import { dirname, join } from 'node:path';

/**
 * Lädt die Rechtstext-Fragmente (Impressum, Datenschutz) 1:1 aus
 * content/legal/. Die Fragmente werden per `set:html` gerendert;
 * der Inhalt stammt aus dem Live-Abruf des Vereins und wird bewusst
 * nicht redaktionell verändert.
 */

const projectRoot = dirname(dirname(fileURLToPath(import.meta.url))); // -> src/.. = Repo-Root

export function loadLegalHtml(slug: 'impressum' | 'datenschutz'): string {
  const file = join(projectRoot, 'content', 'legal', `${slug}.html`);
  try {
    return readFileSync(file, 'utf-8').trim();
  } catch {
    return '';
  }
}
