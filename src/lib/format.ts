/**
 * Deutsche Datums-/Text-Formatierung ohne Locale-Abhängigkeit.
 * 1:1-Port der PHP-Helper aus der Vorgängersite (includes/helpers.php).
 */

const WEEKDAYS_FULL = ['Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag'] as const;
const WEEKDAYS_SHORT = ['So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa'] as const;
const MONTHS_FULL = [
  'Januar', 'Februar', 'März', 'April', 'Mai', 'Juni',
  'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember',
] as const;
const MONTHS_SHORT = [
  'Jan', 'Feb', 'Mär', 'Apr', 'Mai', 'Jun',
  'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dez',
] as const;

function parseIso(iso: string): Date | null {
  // Reiner Datum-String ohne Zeit: als lokales Datum interpretieren.
  const m = /^(\d{4})-(\d{2})-(\d{2})$/.exec(iso);
  if (m) {
    return new Date(Number(m[1]), Number(m[2]) - 1, Number(m[3]));
  }
  const d = new Date(iso);
  return Number.isNaN(d.getTime()) ? null : d;
}

/** "Samstag, 3. Oktober 2026" (Wochentag optional). */
export function formatDateDe(iso: string, withWeekday = true): string {
  const d = parseIso(iso);
  if (!d) return iso;
  const formatted = `${d.getDate()}. ${MONTHS_FULL[d.getMonth()]} ${d.getFullYear()}`;
  return withWeekday ? `${WEEKDAYS_FULL[d.getDay()]}, ${formatted}` : formatted;
}

export function weekdayFullDe(iso: string): string {
  const d = parseIso(iso);
  return d ? WEEKDAYS_FULL[d.getDay()] : '';
}

export function weekdayShortDe(iso: string): string {
  const d = parseIso(iso);
  return d ? WEEKDAYS_SHORT[d.getDay()] : '';
}

export function monthShortDe(iso: string): string {
  const d = parseIso(iso);
  return d ? MONTHS_SHORT[d.getMonth()] : '';
}

/** Tag zweistellig ("01"), wie render_date_badge in der Vorgängersite. */
export function dayPaddedDe(iso: string): string {
  const d = parseIso(iso);
  return d ? String(d.getDate()).padStart(2, '0') : '';
}

/**
 * Lokale ISO-Zeit mit Offset für schema.org startDate:
 * Winter +01:00 (CET), Sommer +02:00 (CEST) — wie iso_local() in PHP.
 * DST-Grenzen nach EU-Regel (letzter Sonntag März/Oktober); alle
 * Vorstellungen liegen am Abend, daher ist die Uhren-Sprungstunde irrelevant.
 */
export function isoLocal(date: string, time = '18:00'): string {
  const [y, mo, da] = date.split('-').map(Number);
  const [h, mi] = time.split(':').map(Number);
  if ([y, mo, da, h, mi].some((n) => Number.isNaN(n))) return `${date}T${time}:00+01:00`;
  const pad = (n: number) => String(n).padStart(2, '0');
  return `${date}T${pad(h)}:${pad(mi)}:00${isCest(date) ? '+02:00' : '+01:00'}`;
}

/** EU-Sommerzeit: letzter Sonntag im März bis letzter Sonntag im Oktober. */
function isCest(date: string): boolean {
  const [y, m, d] = date.split('-').map(Number);
  if (m < 3 || m > 10) return false;
  if (m === 3) return d >= lastSundayOf(y, 3);
  if (m === 10) return d < lastSundayOf(y, 10);
  return true;
}

function lastSundayOf(year: number, month: number): number {
  const d = new Date(Date.UTC(year, month, 0)); // letzter Tag des Monats (UTC)
  return d.getUTCDate() - d.getUTCDay();
}

/** Initialen für Avatar-Platzhalter: max. 2 Buchstaben, uppercase. */
export function personInitials(name: string): string {
  const parts = name.trim().split(/\s+/).filter(Boolean);
  const initials = parts
    .slice(0, 2)
    .map((p) => p.charAt(0))
    .join('');
  return initials.toUpperCase();
}

/** URL-sicherer Slug mit deutschen Umlauten (wie slugify() in PHP). */
export function slugify(s: string): string {
  return s
    .toLowerCase()
    .replaceAll('ä', 'ae')
    .replaceAll('ö', 'oe')
    .replaceAll('ü', 'ue')
    .replaceAll('ß', 'ss')
    .replaceAll(/[^a-z0-9]+/g, '-')
    .replace(/^-+|-+$/g, '');
}

/** Kürzt Text multibyte-sicher auf max Zeichen (wie truncate_text()). */
export function truncateText(text: string, max: number, suffix = '…'): string {
  if (max <= 0) return '';
  if (text.length <= max) return text;
  return text.slice(0, Math.max(0, max - suffix.length)).trimEnd() + suffix;
}
