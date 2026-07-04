## Tasks

### Look & feel

- **1.** **Audit text consistency across all pages.** Review **text colour, font
      size, and content margin/max-width** on every page (home, about, cv,
      projects, blog, books, achievements, 404). Too many inconsistencies
      creeping in: e.g. justified vs left-aligned body text, `card-body`'s
      smaller default size vs `text-base`, ad-hoc `text-[15px]` overrides,
      per-page `maxWidthClass`. Settle on shared conventions (a body size, when
      to justify, default content width) and apply them uniformly — ideally via
      shared components / global.css rather than per-page classes.
- **3.** **Settle body font size.** Deferred from the task-1 audit (the harder half).
      Body copy is currently inconsistent: `text-lg` (home intro), `text-base`
      (about/books), ad-hoc `text-[15px]` (about cards), `prose-lg` (posts), and a
      broken `text-1xl` (not a real class → silently falls back to base) in
      `HorizontalCard`, `ProjectCard`, `HorizontalShopItem`. Pick one body size, fix
      the `text-1xl` typo, and drive it from `global.css` / shared components rather
      than per-page. Width, justify, muted text, links and heading semantics were
      already settled in the first pass.

### Cut-over (last)

- **8.** **SEO migration + domain wind-down** (`diogonunes.com` → github.io). Do this
      *after* the new site is live (tasks 5–7). The domain is paid until
      **2026-09-10** — that's the 301 window. All steps free; ~1–2h one-time. Worth it
      because the tech tutorials (JColor, Cypress, Playwright…) draw organic search
      traffic; expect ~60–80% preserved through the transition, long tail lost when the
      domain lapses (a github.io subpath can't be fully owned).
      1. **Cloudflare 301s.** Move `diogonunes.com` nameservers to Cloudflare (free).
         Add one path-preserving Redirect Rule (301):
         `diogonunes.com/*` → `https://dialex.github.io/DiogoNunesCom/$1`
         — one wildcard covers all 178 posts + pages, since the paths are unchanged.
      2. **Decommission WP/FTP.** Once the redirect serves from Cloudflare's edge,
         cancel the WordPress + FTP hosting (no origin needed for an edge rule). Keep
         the *domain registered* until it expires — that's what powers the 301s.
      3. **Search Console.** Add a URL-prefix property for
         `https://dialex.github.io/DiogoNunesCom/` (verify via an HTML file committed to
         the repo); submit the sitemap. Try the "Change of Address" tool (old → new) —
         it may reject a subpath target; the 301s + sitemap do the real work.
      4. **Let it ride, then lapse.** Keep the 301s up until **2026-09-10** so Google
         has the full window to re-crawl and transfer ranking; then let the domain
         expire. (Permalink scheme + internal-link rewrite are already done, so every
         old `/blog/<slug>/` maps 1:1 to the new URL.)
12. Shorter domain with Cloudflare?
fiz exatamente o mm ha uns anos
se usares o cloudflare em vez do pages o url fica mais curto
e.g. next-watch.pages.dev
(e tbm ha uma action to gh q faz deploy la)

### Cleanup

- **13.** **Remove dead code / unused assets.** Astrofy scaffolding left leftovers.
      `knip` confirms two unused components: `src/components/Card.astro` and
      `src/components/HorizontalShopItem.astro` (the store-template shop item — no store
      exists), plus an unused `Props` type export in `PostLayout.astro`. Delete those,
      then do the harder sweep knip can't see: **orphan `public/` assets** (images from
      the old Freelancer/hireme themes + Astrofy demo). Also fix/kill the broken
      `text-md` class in `HorizontalShopItem` — moot if that file is deleted. Re-run
      `knip` + `astro build` after.

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
