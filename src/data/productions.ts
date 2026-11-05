/**
 * Produktionen der aktuellen Spielzeit (2026) — 1:1 aus spielzeit.php portiert.
 */

export interface Production {
  title: string;
  subtitle: string;
  image: string;
  imageAlt: string;
  lead: string;
  ticketUrl: string;
  status: string;
  director: string;
  author: string;
  kind: 'theater' | 'maerchen';
  highlight: boolean;
}

export const productions: Production[] = [
  {
    title: 'Die Löffelliste',
    subtitle: 'Saisonstück 2026',
    image: '/assets/img/loeffelliste.webp',
    imageAlt: 'Titelbild des Saisonstücks 2026: Die Löffelliste',
    lead: 'Ein Tag, den sich Erika ganz anders vorgestellt hat. Eine unerwartete Nachricht von ihrem Vermieter, dann Freunde, die sich in ihr Leben einmischen wollen und zu guter Letzt noch die Begegnung mit Gevatter Tod. Aber nicht mit Erika. Sie weiß sich zu helfen und hat dabei ihren Spaß. Von Gerhard Schreiner, in einer Inszenierung von Peter Schmitt & Kathrin Landmann.',
    ticketUrl:
      'https://www.ticket-regional.de/events.php?mysearchSpecificType=eventtype&mysearchSpecificID=1883',
    status: 'tickets',
    director: 'Peter Schmitt & Kathrin Landmann',
    author: 'Gerhard Schreiner',
    kind: 'theater',
    highlight: true,
  },
  {
    title: 'Die Bremer Stadtmusikanten',
    subtitle: 'Weihnachtsmärchen 2026',
    image: '/assets/img/bsm.webp',
    imageAlt: 'Titelbild Weihnachtsmärchen 2026: Die Bremer Stadtmusikanten',
    lead: 'Vier alte Tiere auf dem Weg nach Bremen — Ein Märchen der Brüder Grimm in einer Inszenierung von Peter Schmitt. In dem Märchen schließen sich ein alter Esel, ein Hund, eine Katze und ein Hahn zusammen, nachdem ihre Besitzer sie loswerden wollen. Gemeinsam machen sie sich auf den Weg nach Bremen, um dort Stadtmusikanten zu werden. Unterwegs vertreiben sie mit Mut und Zusammenhalt eine Räuberbande aus einem Haus im Wald und finden dort schließlich ein neues Zuhause — und eine starke Freundschaft.',
    ticketUrl: 'https://www.ticket-regional.de/events_info.php?eventID=257737',
    status: 'tickets',
    director: 'Peter Schmitt',
    author: 'Brüder Grimm',
    kind: 'maerchen',
    highlight: true,
  },
];
