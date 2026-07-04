## Tasks

### Cut-over (last)

1.  Shorter domain with Cloudflare?
fiz exatamente o mm ha uns anos
se usares o cloudflare em vez do pages o url fica mais curto
e.g. next-watch.pages.dev
(e tbm ha uma action to gh q faz deploy la)

---

## Reference notes / gotchas

- ⚠️ **MoonPay npm proxy (koi.security)** blocks package `is-unsafe`, pulled in by
  `@astrojs/rss` ≥ 4.0.12. Astro 5/6 cores, mdx, sitemap, Tailwind 4, DaisyUI 5,
  `@tailwindcss/postcss` are all clean through the gate — only newest RSS is blocked.
- ⚠️ **RSS: `@astrojs/rss` dropped entirely** on Astro 6. ≥ 4.0.12 is gate-blocked;
  ≤ 4.0.11 uses a removed Zod 3 API (`z.function().returns`) that Astro 6 (Zod 4)
  rejects. Replaced with a hand-rolled XML endpoint in `src/pages/rss.xml.js` — no
  dependency. (The 4.0.11 pin only ever mattered on Astro 5.)
- ⚠️ **`@tailwindcss/vite` is incompatible with Astro 6's rolldown-vite**
  (`Missing field tsconfigPaths`). Use the **PostCSS** plugin (`@tailwindcss/postcss`
  + `postcss.config.mjs`) instead. Revisit if/when the Vite plugin gains rolldown support.
- **GH Pages limits:** 1GB repo / 100GB-month bandwidth; repo ~175M — fine. No
  `.htaccess`, SSI, PHP, or arbitrary headers. (For edge logic later: Cloudflare
  Pages / Netlify free tiers.)
- CV PDF (`assets/downloads/misc/NunesDiogo-Resume2021-EN.pdf`) still contains an
  email address — acceptable for a résumé (HTML was scrubbed).
- Done already (context): PHP removed; `.htaccess` redirects → static stubs;
  server-side cleanup; all emails stripped from HTML; paths prefixed `/DiogoNunesCom/`;
  Astrofy scaffolded in `site/`; **dependency upgrade fully done — Astro 6 + Tailwind 4
  + DaisyUI 5**; projects content-collection + detail pages built (JColor seeded).
