#!/usr/bin/env node
/**
 * Syncs legal content blocks from the current live pages into local HTML
 * fragments (content/legal/*.html). Node-Port des PHP-Skripts.
 *
 * Usage: npm run sync:legal
 */
import { writeFileSync, mkdirSync, readFileSync, existsSync } from 'node:fs';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';

const sources = {
  impressum: 'https://volksbuehne-worms.de/impressum/',
  datenschutz: 'https://volksbuehne-worms.de/datenschutz/',
};

const targetDir = join(dirname(dirname(fileURLToPath(import.meta.url))), 'content', 'legal');
mkdirSync(targetDir, { recursive: true });

/** Findet den ersten `elementor-widget-text-editor`-Block und gibt dessen
 *  Container-InnerHTML über Balanced-Tag-Scanning zurück. */
function extractFragment(html) {
  const marker = 'elementor-widget-text-editor';
  const start = html.indexOf(marker);
  if (start === -1) return null;
  const containerClass = html.indexOf('elementor-widget-container', start);
  if (containerClass === -1) return null;
  const openEnd = html.indexOf('>', containerClass);
  if (openEnd === -1) return null;

  let depth = 1;
  let pos = openEnd + 1;
  const tagRe = /<(\/?)div\b[^>]*>/gi;
  tagRe.lastIndex = pos;
  let m;
  while ((m = tagRe.exec(html)) !== null) {
    depth += m[1] === '/' ? -1 : 1;
    if (depth === 0) {
      return html.slice(openEnd + 1, m.index);
    }
  }
  return null;
}

async function fetchPage(url, slug) {
  const cached = `/tmp/vb-${slug}.html`;
  if (existsSync(cached)) {
    const cachedHtml = readFileSync(cached, 'utf-8').trim();
    if (cachedHtml) return cachedHtml;
  }
  const res = await fetch(url, {
    headers: { 'User-Agent': 'vb-static-sync/2.0 (+https://www.volksbuehne-worms.de)' },
  });
  if (!res.ok) throw new Error(`Fetch failed (${res.status}) for ${url}`);
  return res.text();
}

for (const [slug, url] of Object.entries(sources)) {
  try {
    const html = await fetchPage(url, slug);
    let fragment = extractFragment(html);
    if (fragment === null) {
      console.error(`Content container not found for ${url}`);
      process.exit(1);
    }

    // Hero-Überschrift nur einmal im Template behalten.
    fragment = fragment.replace(/^\s*<h1[^>]*>.*?<\/h1>/is, '');

    // Interne absolute Links zu relativen Pfaden umschreiben.
    fragment = fragment.replace(
      /\b(href|src)=("|')https?:\/\/(?:www\.)?volksbuehne-worms\.de([^"']*)\2/gi,
      (_match, attr, quote, path) => {
        let p = String(path).trim();
        p = p === '' ? '/' : p;
        if (!p.startsWith('/')) p = `/${p}`;
        return `${attr}=${quote}${p}${quote}`;
      }
    );

    const targetFile = join(targetDir, `${slug}.html`);
    writeFileSync(targetFile, `${fragment.trim()}\n`, 'utf-8');
    console.log(`Synced ${slug} -> ${targetFile}`);
  } catch (err) {
    console.error(`Sync failed for ${slug}: ${err.message}`);
    process.exit(1);
  }
}
