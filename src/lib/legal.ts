import { readFileSync } from 'node:fs';
import { join } from 'node:path';

/**
 * Lädt die Rechtstext-Fragmente (Impressum, Datenschutz) 1:1 aus
 * content/legal/. Die Fragmente werden per `set:html` gerendert;
 * der Inhalt stammt aus dem Live-Abruf des Vereins und wird bewusst
 * nicht redaktionell verändert.
 *
 * Wird zur Build-Zeit ausgeführt; `process.cwd()` ist dabei das
 * Projektverzeichnis (wie in astro.config.mjs verankert).
 */

function legalDir(): string {
  // Stabil in Build-Umgebungen: Erst cwd, dann Fallback über die Modul-URL.
  const fromCwd = join(process.cwd(), 'content', 'legal');
  try {
    readFileSync(join(fromCwd, 'impressum.html'), 'utf-8');
    return fromCwd;
  } catch {
    // Fallback: src/lib -> src -> Repo-Root
    const moduleDir = import.meta.dirname ?? '.';
    return join(moduleDir, '..', '..', 'content', 'legal');
  }
}

export function loadLegalHtml(slug: 'impressum' | 'datenschutz'): string {
  const file = join(legalDir(), `${slug}.html`);
  try {
    return readFileSync(file, 'utf-8').trim();
  } catch {
    return '';
  }
}
