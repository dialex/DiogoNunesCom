## DiogoNunes.com

This repo contains my [**personal website**](http://dialex.github.io/DiogoNunesCom/).

*Since 12:34:56 07/08/2009.*

---

Built with [Astro](https://astro.build), [Tailwind CSS](https://tailwindcss.com/), and [DaisyUI](https://daisyui.com/) — based on the [Astrofy](https://github.com/manuelernestog/astrofy) template.

### Development

```bash
npm install      # install dependencies
npm run dev      # start the local dev server
npm run build    # build the production site to ./dist
npm run preview  # preview the production build locally
npm run format   # format the codebase with Prettier
```

### Structure

- `src/` — components, layouts, pages, content collections, and `config.ts` (global site config).
- `public/` — static assets served at the site root (favicons, `robots.txt`, `ads.txt`, etc.).
- `astro.config.mjs` — Astro configuration.

Blog posts live as Markdown/MDX in `src/content/blog/`. The sitemap is generated automatically at build time.
