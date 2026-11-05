#!/usr/bin/env node
/**
 * SEO- und Responsive-Audit für den statischen Build (dist/).
 * Deterministische Checks gegen den laufenden Server:
 *
 *   SEO:      Titles, Descriptions, Canonicals, Robots, hreflang,
 *             OG/Twitter, JSON-LD (Pars + Typen), Headings, Bild-Attribute,
 *             interne Links inkl. Anker, sitemap.xml, html lang
 *   Responsive: Horizontale Overflows über Viewport-Raster, Tap-Targets
 *             (WCAG 2.5.8: >= 24x24 px), Schriftgrößen < 12px
 *
 * Usage: node scripts/audit-seo-responsive.mjs [baseUrl]
 * Exit 1, wenn KRITISCH/HOCH-Findungen existieren.
 */
import { readFileSync } from 'node:fs';
import { chromium } from 'playwright';

const baseUrl = process.argv[2] ?? 'http://127.0.0.1:4399';
const ORIGIN = 'https://www.volksbuehne-worms.de';

const ROUTES = [
  { path: '/', name: 'home', index: true },
  { path: '/spielzeit/', name: 'spielzeit', index: true },
  { path: '/weihnachtsmaerchen/', name: 'maerchen', index: true },
  { path: '/verein/', name: 'verein', index: true },
  { path: '/kontakt/', name: 'kontakt', index: true },
  { path: '/impressum/', name: 'impressum', index: true },
  { path: '/datenschutz/', name: 'datenschutz', index: true },
  { path: '/tickets-kaufen/', name: 'tickets', index: false },
  { path: '/404.html', name: '404', index: false },
];

const findings = [];
const add = (sev, area, msg) => findings.push({ sev, area, msg });

/* ---------------- SEO: statische Dateien ---------------- */
const sitemap = readFileSync(new URL('../dist/sitemap.xml', import.meta.url), 'utf-8');
const sitemapUrls = [...sitemap.matchAll(/<loc>([^<]+)<\/loc>/g)].map((m) => m[1].replace(ORIGIN, ''));
const robots = readFileSync(new URL('../dist/robots.txt', import.meta.url), 'utf-8');

for (const url of sitemapUrls) {
  if (!sitemapUrls.length) break;
}
const expectedIndexable = ROUTES.filter((r) => r.index).map((r) => r.path);
for (const p of expectedIndexable) {
  if (!sitemapUrls.includes(p)) add('HOCH', 'sitemap', `${p} fehlt in sitemap.xml`);
}
for (const u of sitemapUrls) {
  if (!expectedIndexable.includes(u)) add('MITTEL', 'sitemap', `Unerwarteter Sitemap-Eintrag: ${u}`);
}
if (!/Sitemap: https:\/\/www\.volksbuehne-worms\.de\/sitemap\.xml/.test(robots)) {
  add('MITTEL', 'robots', 'Sitemap-Zeile fehlt in robots.txt');
}
if (!/Disallow: \/tickets-kaufen\//.test(robots)) {
  add('NIEDRIG', 'robots', 'tickets-kaufen nicht disallowed (Fallback-Seite ist noindex, ok aber sauberer mit Disallow)');
}

/* ---------------- SEO + Responsive: gecrawlt ---------------- */
const browser = await chromium.launch();
const titles = new Map();
const descriptions = new Map();
const brokenLinks = [];
const jsonldReport = [];

for (const route of ROUTES) {
  const page = await browser.newPage({ viewport: { width: 1440, height: 1000 } });

  // Redirect-Stub (/tickets-kaufen/): Der Meta-Refresh führt zum externen
  // Shop - nur Stub-Eigenschaften per Roh-Fetch prüfen, nicht navigieren.
  if (route.path === '/tickets-kaufen/') {
    const res = await page.request.get(baseUrl + route.path);
    const html = await res.text();
    if (!res.ok()) add('KRITISCH', 'http', `${route.path} -> HTTP ${res.status()}`);
    if (!/name="robots"\s+content="noindex/.test(html)) add('MITTEL', 'robots', `${route.path}: noindex fehlt`);
    if (!/url=https:\/\/www\.ticket-regional\.de\/events\.php\?mysearchSpecificType=eventtype&(amp;|#38;)?mysearchSpecificID=1883/.test(html)) {
      add('KRITISCH', 'redirect', `${route.path}: Meta-Refresh-Ziel falsch oder fehlt`);
    } else {
      console.error('tickets-kaufen: Redirect-Stub ok (noindex + Meta-Refresh auf Eventtyp 1883)');
    }
    await page.close();
    continue;
  }

  const res = await page.goto(baseUrl + route.path, { waitUntil: 'networkidle' });
  if (!res.ok()) {
    add('KRITISCH', 'http', `${route.path} -> HTTP ${res.status()}`);
    await page.close();
    continue;
  }

  const seo = await page.evaluate(() => {
    const $ = (sel) => document.querySelector(sel);
    const $$ = (sel) => [...document.querySelectorAll(sel)];
    const meta = (name) => $(`meta[name="${name}"]`)?.content ?? null;
    const prop = (name) => $(`meta[property="${name}"]`)?.content ?? null;
    const headings = $$('h1, h2, h3, h4, h5, h6').map((h) => h.tagName.toLowerCase());
    const imgs = $$('img');
    return {
      title: document.title,
      description: meta('description'),
      canonical: $('link[rel="canonical"]')?.href ?? null,
      robots: meta('robots'),
      hreflangDe: $('link[rel="alternate"][hreflang="de"]')?.href ?? null,
      hreflangXd: $('link[rel="alternate"][hreflang="x-default"]')?.href ?? null,
      lang: document.documentElement.lang,
      og: {
        title: prop('og:title'),
        description: prop('og:description'),
        type: prop('og:type'),
        url: prop('og:url'),
        image: prop('og:image'),
        imageAlt: prop('og:image:alt'),
        locale: prop('og:locale'),
        siteName: prop('og:site_name'),
      },
      twitterCard: meta('twitter:card'),
      jsonld: $$('script[type="application/ld+json"]').map((s) => {
        try {
          return { ok: true, data: JSON.parse(s.textContent) };
        } catch (e) {
          return { ok: false, error: e.message };
        }
      }),
      h1: $$('h1').map((h) => h.textContent.trim().slice(0, 60)),
      headings,
      imgsTotal: imgs.length,
      imgsMissingAlt: imgs.filter((i) => i.getAttribute('alt') === null).length,
      imgsMissingDims: imgs.filter((i) => !i.getAttribute('width') || !i.getAttribute('height')).length,
      links: $$('a[href]').map((a) => ({ href: a.getAttribute('href'), id: a.id })),
      anchors: [...new Set($$('[id]').map((el) => el.id))],
    };
  });

  // Titles / Descriptions
  if (!seo.title || seo.title.length < 15) add('HOCH', 'title', `${route.path}: Title fehlt/zu kurz ("${seo.title}")`);
  if (seo.title.length > 65) add('NIEDRIG', 'title', `${route.path}: Title ${seo.title.length} Zeichen (>65, wird gekürzt)`);
  titles.set(seo.title, (titles.get(seo.title) ?? 0) + 1);
  if (!seo.description || seo.description.length < 70) {
    if (route.index) add('HOCH', 'description', `${route.path}: Description fehlt/zu kurz`);
  }
  if (seo.description && seo.description.length > 165) add('NIEDRIG', 'description', `${route.path}: Description ${seo.description.length} Zeichen`);
  descriptions.set(seo.description, (descriptions.get(seo.description) ?? 0) + 1);

  // Canonical + Indexierbarkeit
  const wantsIndex = route.index;
  if (wantsIndex) {
    if (seo.canonical !== ORIGIN + route.path) add('HOCH', 'canonical', `${route.path}: canonical="${seo.canonical}" (erwartet ${ORIGIN}${route.path})`);
    if (seo.robots && /noindex/.test(seo.robots)) add('KRITISCH', 'robots', `${route.path}: noindex auf indexierbarer Seite!`);
    if (!sitemapUrls.includes(route.path)) add('HOCH', 'sitemap', `${route.path}: fehlt in sitemap.xml`);
  } else {
    if (!seo.robots || !/noindex/.test(seo.robots)) add('MITTEL', 'robots', `${route.path}: sollte noindex tragen`);
  }

  // hreflang + lang
  if (wantsIndex) {
    if (!seo.hreflangDe) add('MITTEL', 'hreflang', `${route.path}: hreflang de fehlt`);
    if (!seo.hreflangXd) add('NIEDRIG', 'hreflang', `${route.path}: hreflang x-default fehlt`);
  }
  if (seo.lang !== 'de') add('HOCH', 'lang', `${route.path}: html lang="${seo.lang}"`);

  // OG / Twitter
  const ogNeeded = wantsIndex;
  if (ogNeeded) {
    for (const [k, v] of Object.entries(seo.og)) {
      if (!v) add('MITTEL', 'og', `${route.path}: og:${k} fehlt`);
    }
    if (seo.og.image && /\.svg($|\?)/i.test(seo.og.image)) {
      add('HOCH', 'og:image', `${route.path}: og:image ist SVG (${seo.og.image}) - Facebook/X/LinkedIn/WhatsApp rendern kein SVG, Social-Cards laufen leer`);
    }
    if (!seo.twitterCard) add('NIEDRIG', 'og', `${route.path}: twitter:card fehlt`);
  }

  // JSON-LD
  for (const block of seo.jsonld) {
    if (!block.ok) {
      add('HOCH', 'json-ld', `${route.path}: JSON-LD nicht parsebar: ${block.error}`);
      continue;
    }
    const graph = block.data['@graph'] ?? [block.data];
    for (const node of graph) {
      jsonldReport.push({ page: route.name, type: node['@type'] });
    }
  }

  // Headings
  const h1count = seo.h1.length;
  if (h1count === 0) add('HOCH', 'headings', `${route.path}: keine H1`);
  if (h1count > 1) add('MITTEL', 'headings', `${route.path}: ${h1count} H1 (${seo.h1.join(' | ')})`);
  let prev = 0;
  for (const h of seo.headings) {
    const lvl = Number(h[1]);
    if (prev && lvl > prev + 1) add('NIEDRIG', 'headings', `${route.path}: Heading-Sprung h${prev} -> h${lvl}`);
    prev = lvl;
  }

  // Bilder
  if (seo.imgsMissingAlt > 0) add('HOCH', 'images', `${route.path}: ${seo.imgsMissingAlt}/${seo.imgsTotal} Bilder ohne alt-Attribut`);
  if (seo.imgsMissingDims > 0) add('NIEDRIG', 'images', `${route.path}: ${seo.imgsMissingDims} Bilder ohne width/height (CLS-Risiko)`);

  // Interne Links + Anker
  for (const link of seo.links) {
    const href = link.href;
    if (!href || href.startsWith('mailto:') || href.startsWith('tel:') || href.startsWith('http')) continue;
    if (href.startsWith('#')) {
      const anchor = href.slice(1);
      if (anchor && !seo.anchors.includes(anchor)) add('HOCH', 'links', `${route.path}: Anker-Ziel fehlt: ${href}`);
      continue;
    }
    const [pathPart, hash] = href.split('#');
    try {
      const target = new URL(pathPart, baseUrl + route.path).pathname;
      // Erst exakt anfragen, dann mit Trailing Slash (Verzeichnis-Routen)
      let resp = await page.request.get(baseUrl + target);
      if (resp.status() !== 200 && !target.endsWith('/')) {
        resp = await page.request.get(baseUrl + target + '/');
      }
      if (resp.status() !== 200) brokenLinks.push(`${route.path} -> ${href} (${resp.status()})`);
    } catch {
      brokenLinks.push(`${route.path} -> ${href} (Fehler)`);
    }
    if (hash) {
      const targetPage = await page.request.get(baseUrl + href.split('#')[0]);
      const body = await targetPage.text();
      if (!body.includes(`id="${hash}"`)) add('MITTEL', 'links', `${route.path}: Anker #${hash} auf Zielseite ${href.split('#')[0]} nicht gefunden`);
    }
  }
  if (brokenLinks.length) add('HOCH', 'links', brokenLinks.join('; '));

  /* ---------------- Responsive Sweep ---------------- */
  for (const width of [320, 375, 390, 430, 560, 768, 1024, 1440, 1920]) {
    await page.setViewportSize({ width, height: 900 });
    await page.waitForTimeout(120);
    const resp = await page.evaluate(() => {
      const vw = document.documentElement.clientWidth;
      const overflow = document.documentElement.scrollWidth > vw + 1;
      const tooSmallText = [];
      document.querySelectorAll('body *').forEach((el) => {
        if (el.children.length > 0) return;
        const fs = parseFloat(getComputedStyle(el).fontSize);
        const txt = (el.textContent ?? '').trim();
        if (txt && fs < 12) tooSmallText.push(`${el.tagName}.${String(el.className).slice(0, 30)}@${fs}px`);
      });
      const smallTargets = [];
      document.querySelectorAll('a, button').forEach((el) => {
        const r = el.getBoundingClientRect();
        if (r.width === 0 || r.height === 0) return;
        if (r.width < 24 || r.height < 24) {
          smallTargets.push(`${el.tagName}.${String(el.className).slice(0, 30)} ${Math.round(r.width)}x${Math.round(r.height)}`);
        }
      });
      return { vw, scrollW: document.documentElement.scrollWidth, overflow, tooSmallText: [...new Set(tooSmallText)].slice(0, 3), smallTargets: [...new Set(smallTargets)].slice(0, 5) };
    });
    if (resp.overflow) {
      add(resp.vw <= 430 ? 'HOCH' : 'MITTEL', 'responsive', `${route.path} @${width}px: horizontaler Overflow (${resp.scrollW} > ${resp.vw})`);
    }
    if (resp.tooSmallText.length) {
      add('NIEDRIG', 'responsive', `${route.path} @${width}px: Schrift < 12px: ${resp.tooSmallText.join(', ')}`);
    }
    if (width <= 430 && resp.smallTargets.length) {
      add('NIEDRIG', 'responsive', `${route.path} @${width}px: Tap-Targets < 24px: ${resp.smallTargets.join(', ')}`);
    }
  }

  await page.close();
}

// Duplikate Titles/Descriptions
for (const [t, n] of titles) if (n > 1) add('HOCH', 'title', `Title ${n}x dupliziert: "${t}"`);
for (const [d, n] of descriptions) if (n > 1 && d) add('MITTEL', 'description', `Description ${n}x dupliziert: "${String(d).slice(0, 50)}…"`);

// og:image-SVG-Übersicht
const ogImages = readFileSync(new URL('../dist/index.html', import.meta.url), 'utf-8').match(/og:image" content="([^"]+)"/)?.[1];
if (ogImages) add('INFO', 'og:image', `Home og:image = ${ogImages}`);

// JSON-LD Zusammenfassung
const types = {};
for (const j of jsonldReport) types[j.type] = (types[j.type] ?? 0) + 1;
console.error('JSON-LD Typen:', JSON.stringify(types));

await browser.close();

/* ---------------- Report ---------------- */
const order = { KRITISCH: 0, HOCH: 1, MITTEL: 2, NIEDRIG: 3, INFO: 4 };
findings.sort((a, b) => order[a.sev] - order[b.sev]);
const sevIcon = { KRITISCH: '[!!!]', HOCH: '[!! ]', MITTEL: '[!  ]', NIEDRIG: '[   ]', INFO: '(i) ' };
let hasBlocking = false;
for (const f of findings) {
  if (f.sev === 'KRITISCH' || f.sev === 'HOCH') hasBlocking = true;
  console.log(`${sevIcon[f.sev]} ${f.sev.padEnd(8)} ${f.area.padEnd(12)} ${f.msg}`);
}
console.log(`\n${findings.length} Findungen`);
process.exit(hasBlocking ? 1 : 0);
