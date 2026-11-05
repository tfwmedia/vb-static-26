/**
 * Zentrale Site-Konfiguration — 1:1 aus includes/site-config.php portiert.
 * Quelle der Wahrheit für Kontaktdaten, Adressen, Tickets und Rechtstexte.
 */

export const ORIGIN = 'https://www.volksbuehne-worms.de';

export const site = {
  name: 'Volksbühne Worms 1908 e. V.',
  shortName: 'Volksbühne Worms',
  tagline: 'Theater seit 1908',
  email: 'info@volksbuehne-worms.de',
  phoneDisplay: '06241 51429',
  phoneHref: '+49624151429',
  maerchenContactEmail: 'kontakt@volksbuehne-worms.de',
  maerchenContactPhoneDisplay: '0152 57204453',
  maerchenContactPhoneHref: '+4915257204453',
  maerchenTicketUrl: 'https://www.ticket-regional.de/events_info.php?eventID=257737',
  spielzeitTicketUrl:
    'https://www.ticket-regional.de/events.php?mysearchSpecificType=eventtype&mysearchSpecificID=1883',
  social: {
    facebook: 'https://www.facebook.com/VolksbuehneWorms/',
    instagram: 'https://www.instagram.com/volksbuehne_worms/',
  },
  address: {
    name: 'Volksbühne Worms 1908 e. V.',
    street: 'Würdtweinstraße 11',
    postalCity: '67549 Worms',
    country: 'DE',
  },
  foundingYear: 1908,
  memberCount: 80,
  productionCount: 500,
  vereinBank: {
    ibanPrimary: 'DE82550912000000025046',
    bicPrimary: 'GENODE61AZY',
    bankPrimary: 'Volksbank Alzey-Worms',
    ibanSecondary: 'DE24553500100000005422',
    bicSecondary: 'MALADE51WOR',
    bankSecondary: 'Sparkasse Worms-Alzey-Ried',
    membershipFee: '25,00',
  },
} as const;

export const navigation = [
  { key: 'home', label: 'Start', href: '/' },
  { key: 'spielzeit', label: 'Spielzeit', href: '/spielzeit/' },
  { key: 'maerchen', label: 'Weihnachtsmärchen', href: '/weihnachtsmaerchen/' },
  { key: 'verein', label: 'Verein', href: '/verein/' },
  { key: 'kontakt', label: 'Kontakt', href: '/kontakt/' },
] as const;

export type NavKey = (typeof navigation)[number]['key'];

export interface PageMeta {
  title: string;
  description: string;
  /** Pfad der Seite, z. B. "/spielzeit/". */
  canonicalPath: string;
  ogType?: string;
  ogImage?: string;
  ogImageAlt?: string;
  robots?: string;
}

export const metaDefaults = {
  title: 'Volksbühne Worms 1908 e. V. — Theater seit 1908',
  description:
    'Semiprofessionelles Theater in Worms: Spielzeit, Weihnachtsmärchen, Kulturarbeit und Engagement seit 1908.',
  themeColor: '#c31e2e',
  ogLocale: 'de_DE',
  ogType: 'website',
  ogImage: '/assets/img/og-image.svg',
  ogImageWidth: '1200',
  ogImageHeight: '630',
  robots: 'index, follow, max-image-preview:large, max-snippet:-1',
} as const;

export function absoluteUrl(path = '/'): string {
  return ORIGIN + path;
}

/** Jahre seit Gründung, nie negativ. */
export function yearsActive(now = new Date()): number {
  return Math.max(0, now.getFullYear() - site.foundingYear);
}
