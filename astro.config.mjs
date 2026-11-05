// @ts-check
import { defineConfig } from 'astro/config';

// https://astro.build/config
export default defineConfig({
  site: 'https://www.volksbuehne-worms.de',
  // /spielzeit/ -> dist/spielzeit/index.html (Apache-freundlich, kanonische URLs mit Slash)
  build: {
    format: 'directory',
  },
});
