/**
 * Nächste Vorstellungen — 1:1 aus includes/site-config.php portiert.
 *
 * Quelle der Wahrheit sind die Ticket-Regional-Veranstaltungsseiten:
 *   - Löffelliste (eventID 274872) — kleines Theater, Würdtweinstraße 11:
 *     https://www.ticket-regional.de/events_info.php?eventID=274872
 *     Normalpreis € 25,00
 *   - Bremer Stadtmusikanten (eventID 257737) — Das Wormser, Rathenaustraße 11:
 *     https://www.ticket-regional.de/events_info.php?eventID=257737
 *     Normalpreis € 12,00
 *
 * Reihenfolge = Anzeigereihenfolge (aufsteigend nach Datum).
 */

export type EventStatus = 'tickets' | 'sold_out' | 'premiere' | 'few_left';

export interface VbEvent {
  title: string;
  subtitle: string;
  /** ISO-Datum "YYYY-MM-DD". */
  date: string;
  /** Uhrzeit "HH:MM". */
  time: string;
  venue: string;
  ticketUrl: string | null;
  price: string;
  priceNote: string;
  status: EventStatus;
}

export const upcomingEvents: VbEvent[] = [
  // Die Löffelliste — eventID 274872 — kleines Theater, Würdtweinstraße 11
  {
    title: 'Die Löffelliste',
    subtitle: 'Saisonstück 2026 · Premiere',
    date: '2026-10-03',
    time: '20:00',
    venue: 'Volksbühne — kleines Theater',
    ticketUrl: null,
    price: '25,00 €',
    priceNote: 'VVK',
    status: 'sold_out',
  },
  {
    title: 'Die Löffelliste',
    subtitle: 'Saisonstück 2026',
    date: '2026-10-09',
    time: '20:00',
    venue: 'Volksbühne — kleines Theater',
    ticketUrl: 'https://www.ticket-regional.de/stage_7698.php?timeID=1139633',
    price: '25,00 €',
    priceNote: 'VVK',
    status: 'tickets',
  },
  {
    title: 'Die Löffelliste',
    subtitle: 'Saisonstück 2026',
    date: '2026-10-10',
    time: '20:00',
    venue: 'Volksbühne — kleines Theater',
    ticketUrl: 'https://www.ticket-regional.de/stage_7698.php?timeID=1139634',
    price: '25,00 €',
    priceNote: 'VVK',
    status: 'tickets',
  },
  {
    title: 'Die Löffelliste',
    subtitle: 'Saisonstück 2026',
    date: '2026-10-16',
    time: '20:00',
    venue: 'Volksbühne — kleines Theater',
    ticketUrl: 'https://www.ticket-regional.de/stage_7698.php?timeID=1139635',
    price: '25,00 €',
    priceNote: 'VVK',
    status: 'tickets',
  },
  {
    title: 'Die Löffelliste',
    subtitle: 'Saisonstück 2026',
    date: '2026-10-17',
    time: '20:00',
    venue: 'Volksbühne — kleines Theater',
    ticketUrl: 'https://www.ticket-regional.de/stage_7698.php?timeID=1139636',
    price: '25,00 €',
    priceNote: 'VVK',
    status: 'tickets',
  },
  {
    title: 'Die Löffelliste',
    subtitle: 'Saisonstück 2026',
    date: '2026-10-24',
    time: '20:00',
    venue: 'Volksbühne — kleines Theater',
    ticketUrl: 'https://www.ticket-regional.de/stage_7698.php?timeID=1139637',
    price: '25,00 €',
    priceNote: 'VVK',
    status: 'tickets',
  },
  {
    title: 'Die Löffelliste',
    subtitle: 'Saisonstück 2026 · Sonntagsvorstellung',
    date: '2026-10-25',
    time: '18:00',
    venue: 'Volksbühne — kleines Theater',
    ticketUrl: 'https://www.ticket-regional.de/stage_7698.php?timeID=1139638',
    price: '25,00 €',
    priceNote: 'VVK',
    status: 'tickets',
  },
  {
    title: 'Die Löffelliste',
    subtitle: 'Saisonstück 2026',
    date: '2026-11-06',
    time: '20:00',
    venue: 'Volksbühne — kleines Theater',
    ticketUrl: null,
    price: '25,00 €',
    priceNote: 'VVK',
    status: 'sold_out',
  },
  {
    title: 'Die Löffelliste',
    subtitle: 'Saisonstück 2026',
    date: '2026-11-14',
    time: '20:00',
    venue: 'Volksbühne — kleines Theater',
    ticketUrl: 'https://www.ticket-regional.de/stage_7698.php?timeID=1139640',
    price: '25,00 €',
    priceNote: 'VVK',
    status: 'tickets',
  },
  // Die Bremer Stadtmusikanten — eventID 257737 — Das Wormser, Rathenaustraße 11
  {
    title: 'Die Bremer Stadtmusikanten',
    subtitle: 'Weihnachtsmärchen 2026',
    date: '2026-12-01',
    time: '18:00',
    venue: 'Das Wormser',
    ticketUrl: 'https://www.ticket-regional.de/stage_3769.php?timeID=1119484',
    price: '12,00 €',
    priceNote: 'VVK',
    status: 'tickets',
  },
  {
    title: 'Die Bremer Stadtmusikanten',
    subtitle: 'Weihnachtsmärchen 2026',
    date: '2026-12-02',
    time: '18:00',
    venue: 'Das Wormser',
    ticketUrl: 'https://www.ticket-regional.de/stage_3769.php?timeID=1119485',
    price: '12,00 €',
    priceNote: 'VVK',
    status: 'tickets',
  },
  // Die Löffelliste — Dezember
  {
    title: 'Die Löffelliste',
    subtitle: 'Saisonstück 2026',
    date: '2026-12-11',
    time: '20:00',
    venue: 'Volksbühne — kleines Theater',
    ticketUrl: 'https://www.ticket-regional.de/stage_7698.php?timeID=1139641',
    price: '25,00 €',
    priceNote: 'VVK',
    status: 'tickets',
  },
  {
    title: 'Die Löffelliste',
    subtitle: 'Saisonstück 2026',
    date: '2026-12-12',
    time: '20:00',
    venue: 'Volksbühne — kleines Theater',
    ticketUrl: 'https://www.ticket-regional.de/stage_7698.php?timeID=1139642',
    price: '25,00 €',
    priceNote: 'VVK',
    status: 'tickets',
  },
  {
    title: 'Die Löffelliste',
    subtitle: 'Saisonstück 2026',
    date: '2026-12-18',
    time: '20:00',
    venue: 'Volksbühne — kleines Theater',
    ticketUrl: 'https://www.ticket-regional.de/stage_7698.php?timeID=1139643',
    price: '25,00 €',
    priceNote: 'VVK',
    status: 'tickets',
  },
  {
    title: 'Die Löffelliste',
    subtitle: 'Saisonstück 2026',
    date: '2026-12-19',
    time: '20:00',
    venue: 'Volksbühne — kleines Theater',
    ticketUrl: 'https://www.ticket-regional.de/stage_7698.php?timeID=1139644',
    price: '25,00 €',
    priceNote: 'VVK',
    status: 'tickets',
  },
];

/** Nächste künftige Vorstellung (inkl. ausverkaufter Premieren) für den Hero-Countdown. */
export function nextUpcomingEvent(now: Date = new Date()): VbEvent | null {
  for (const evt of upcomingEvents) {
    const ts = new Date(`${evt.date}T${evt.time}:00`).getTime();
    if (!Number.isNaN(ts) && ts >= now.getTime()) return evt;
  }
  return null;
}

export const EVENT_STATUS_LABELS: Record<EventStatus, string> = {
  tickets: 'Tickets verfügbar',
  sold_out: 'Ausverkauft',
  premiere: 'Premiere',
  few_left: 'Nur noch wenige Plätze',
};

export function eventStatusUrl(status: EventStatus): string {
  // Wie event_status_url() in der Vorgängersite.
  return status === 'sold_out'
    ? 'https://schema.org/EventCancelled'
    : 'https://schema.org/EventScheduled';
}
