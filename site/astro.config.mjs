import { defineConfig } from 'astro/config';
import mdx from '@astrojs/mdx';
import sitemap from '@astrojs/sitemap';

// https://astro.build/config
// Tailwind 4 is wired via PostCSS (postcss.config.mjs) rather than
// @tailwindcss/vite, which is not yet compatible with Astro 6's rolldown-vite.
export default defineConfig({
  site: 'https://www.diogonunes.com',
  integrations: [mdx(), sitemap()]
});
