#!/usr/bin/env node
/**
 * Visueller Smoke-Test: rendert alle Seiten in Astro Preview und
 * speichert Desktop- (1440px) und Mobile-Screenshots (390px).
 *
 * Usage: node scripts/screenshots.mjs [baseUrl]
 */
import { mkdirSync } from 'node:fs';
import { chromium } from 'playwright';

const baseUrl = process.argv[2] ?? 'http://[::1]:4323';
const outDir = new URL('../shots/', import.meta.url).pathname;
mkdirSync(outDir, { recursive: true });

const pages = [
  ['index', '/'],
  ['spielzeit', '/spielzeit/'],
  ['weihnachtsmaerchen', '/weihnachtsmaerchen/'],
  ['verein', '/verein/'],
  ['kontakt', '/kontakt/'],
  ['impressum', '/impressum/'],
  ['datenschutz', '/datenschutz/'],
  ['404', '/404.html'],
];

const browser = await chromium.launch();
const errors = [];

for (const [width, height, suffix] of [
  [1440, 1000, 'desktop'],
  [390, 844, 'mobile'],
]) {
  const context = await browser.newContext({
    viewport: { width, height },
    reducedMotion: 'reduce', // Reveal-Animationen sofort sichtbar
  });
  const page = await context.newPage();
  page.on('console', (msg) => {
    if (msg.type() === 'error') errors.push(`[console ${suffix}] ${msg.text()}`);
  });
  page.on('pageerror', (err) => errors.push(`[pageerror ${suffix}] ${err.message}`));

  for (const [name, path] of pages) {
    const res = await page.goto(baseUrl + path, { waitUntil: 'networkidle' });
    if (!res.ok()) {
      errors.push(`[http] ${path} -> ${res.status()}`);
      continue;
    }
    // Lazy-Images laden: einmal durch die Seite scrollen, dann zurück nach oben.
    await page.evaluate(async () => {
      const step = window.innerHeight * 0.8;
      for (let y = 0; y < document.body.scrollHeight; y += step) {
        window.scrollTo(0, y);
        await new Promise((r) => setTimeout(r, 120));
      }
      window.scrollTo(0, 0);
      await new Promise((r) => setTimeout(r, 300));
    });
    await page.screenshot({ path: `${outDir}${name}-${suffix}.png`, fullPage: true });
    console.log(`shot: ${name}-${suffix}.png`);
  }
  await context.close();
}

await browser.close();
if (errors.length) {
  console.error('\nFEHLER:');
  for (const e of errors) console.error('  ' + e);
  process.exit(1);
}
console.log('\nAlle Screenshots erstellt, keine Konsolen-/Seitenfehler.');
