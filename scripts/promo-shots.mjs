#!/usr/bin/env node
/**
 * Visueller Promo-Popup-Check: erfasst das Dialogfenster auf allen
 * relevanten Viewports. Usage: node scripts/promo-shots.mjs [baseUrl]
 */
import { mkdirSync } from 'node:fs';
import { chromium } from 'playwright';

const baseUrl = process.argv[2] ?? 'http://localhost:4322';
const outDir = new URL('../shots/promo/', import.meta.url).pathname;
mkdirSync(outDir, { recursive: true });

const viewports = [
  ['desktop-1440x1000', 1440, 1000],
  ['laptop-1280x720', 1280, 720],
  ['tablet-768x1024', 768, 1024],
  ['mobile-390x844', 390, 844],
  ['mobile-360x740', 360, 740],
  ['landscape-844x390', 844, 390],
];

const browser = await chromium.launch();

for (const [name, width, height] of viewports) {
  const context = await browser.newContext({ viewport: { width, height } });
  const page = await context.newPage();
  await page.goto(`${baseUrl}/`, { waitUntil: 'networkidle' });
  await page.waitForTimeout(1600); // Popup-Verzögerung (900 ms) + Animation
  const dlg = page.locator('#promo-popup');
  const open = await dlg.evaluate((el) => el.open);
  await page.screenshot({ path: `${outDir}${name}.png` });
  // Geo-Metriken für Zentrierungsprüfung
  const metrics = open
    ? await dlg.evaluate((el) => {
        const r = el.getBoundingClientRect();
        return {
          open: el.open,
          left: Math.round(r.left),
          right: Math.round(window.innerWidth - r.right),
          top: Math.round(r.top),
          bottom: Math.round(window.innerHeight - r.bottom),
          imgComplete: el.querySelector('img')?.complete ?? false,
        };
      })
    : { open: false };
  console.log(name, JSON.stringify(metrics));
  await context.close();
}

await browser.close();
