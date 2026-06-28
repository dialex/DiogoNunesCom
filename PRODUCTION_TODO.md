# Redesign → Production TODO

The single source of truth for shipping the Astro/Astrofy redesign to production.
Supersedes and replaces the old `MIGRATION_PLAN.md` and `REDESIGN_PLAN.md` (both
deleted — their context is folded in below).

**Goal:** converge the three differently-styled properties of `diogonunes.com`
(root portfolio, `/hireme` résumé, WordPress `/blog`) into one unified, fully-static
**Astro + Astrofy** site, deployed on GitHub Pages.

---

## Context

### Current state — three separate styles
| Property | Stack today | Notes |
|---|---|---|
| `diogonunes.com` (root) | Bootstrap + Freelancer theme | Hand-written static `index.html`. |
| `diogonunes.com/hireme` | Own template (`main.css` + light/dark + jQuery + scrollreveal) | Résumé/CV page. |
| `diogonunes.com/blog` | WordPress (PHP + MySQL) | Not in this repo. Dynamic. |

### Target state
One Astro project (`site/`). `astro build` → `dist/` of plain static HTML/CSS/JS,
served by GitHub Pages. No PHP, no DB, no runtime server. Shared layout/nav/footer
authored once. Free from Astrofy/Astro: light/dark theming, RSS, sitemap, SEO/OG.

### Decisions locked in
- **Stack:** Astro + Astrofy (Tailwind/DaisyUI). Static output.
- **Target look:** the Astrofy template (fresh design — neither old root nor old hireme kept), except where a task below says otherwise.
- **Blog:** WP exported to Markdown (already done by user). Comments dropped.
- **Build step accepted:** GH Action runs `astro build`, publishes `dist/`.

### Branch & deploy model
- **`migration/gh-pages` = PROD.** Serves the old (PHP-free) static site as-is at
  `dialex.github.io/DiogoNunesCom/`. Untouched until cut-over. Tag
  `static-deprecated-PHP` marks the prior agent's end.
- **`unified-redesign` = DEV.** The Astro redesign lives here in `site/`. Local dev
  only (`npm run dev` → localhost:4321). Rebased on top of `migration/gh-pages`.
- ⚠️ **Stop the dev server before rebasing** — vite's watcher corrupts mid-rebase.
- This TODO should live on `unified-redesign` alongside `site/`.

---

## Tasks

Flat-numbered so they're easy to reference ("let's do task 7"). Completed tasks
are removed to keep this lean — numbers are stable handles and are never reused.
Grouping is for context only.

### Content & pages
- **6.** **Hireme / CV page — DECISION PENDING.** Merge CV page into About me. Either (1) embed the existing
      `/hireme` page/design largely as-is, or (2) migrate the résumé content into the
      redesign look (Astrofy `cv.astro` has a timeline). Pick before building.

### Blog (BIG)
- **11.** Confirm index, tag pages, and RSS generate correctly.

### Look & feel
- **39.** **Audit text consistency across all pages.** Review **text colour, font
      size, and content margin/max-width** on every page (home, about, cv,
      projects, blog, books, achievements, 404). Too many inconsistencies
      creeping in: e.g. justified vs left-aligned body text, `card-body`'s
      smaller default size vs `text-base`, ad-hoc `text-[15px]` overrides,
      per-page `maxWidthClass`. Settle on shared conventions (a body size, when
      to justify, default content width) and apply them uniformly — ideally via
      shared components / global.css rather than per-page classes.
- **27.** **Revisit heading font once there's more content.** Currently Lato body +
      **Poppins** headings. Re-evaluate switching the title font to **Fraunces**
      (the serif finalist) once more real pages/content exist, to judge it in
      context. One-line change: `--font-heading` in `global.css` + the font load
      in `BaseHead.astro`.
- **28.** **Check badge & link colors (light/dark).** Review the color/contrast of badges (e.g. the
      `badge-secondary` date pills on achievements/cards) and link styling across
      the site for consistency with the emerald theme. Tune the daisyUI theme
      tokens if needed.

### Infrastructure / build
- **31.** **Review & optimise all images.** Audit every image across the site
      (`public/projects`, `public/achievements`, `public/books`, avatar, etc.) for
      oversized dimensions/weight — `no-code-website.png` was 5.5MB (3072px) before
      compression (task 30/3 cleanup). Consider: consistent max dimensions, modern
      formats (WebP/AVIF) via Astro's `<Image>`/`getImage`, **interlaced/progressive
      PNG/JPEG** so images render top-to-bottom while loading, and a build-time
      compression step so large assets can't slip in again.
      **Start with the achievements page** (`public/achievements/`, 148 imgs / ~11M):
      migrating those to Astro `<Image>` is what task 32 deferred here — it auto-derives
      `width`/`height` (kills layout shift) and emits WebP/AVIF + `srcset`. The cheap
      no-JS wins (`decoding="async"` + `content-visibility:auto`) are already in place.
- **38.** **Review redirects in `astro.config.mjs`.** Audit the configured `redirects`
      — some are obsolete (point to pages/routes that no longer exist or are no longer
      relevant post-redesign). Prune the dead ones, confirm the rest still resolve to
      valid targets, and align with the permalink/cut-over decisions (tasks 10, 21–25).
- **17.** **`npm audit`** — review 6 reported vulns (3 low, 2 moderate, 1 high).
- **18.** **Astro `base`** set for the deploy target: `/DiogoNunesCom/` for the subpath,
      or `/` if DNS is flipped to the custom domain at cut-over.
- **19.** **Sitemap:** generate fresh via the Astro sitemap integration; decide whether
      blog URLs are included.
- **20.** **GH Action:** `astro build` → publish `site/dist/` to Pages.

### Cut-over (last)
- **21.** Resolve **old domain / blog / FTP endgame** first: WP blog still on old host;
      decide its fate before cancelling FTP.
- **22.** Merge `unified-redesign` → `migration/gh-pages`.
- **23.** Flip GH Pages deploy to the build Action publishing **only** `site/dist/`.
- **24.** DNS cut-over + decommission FTP & WordPress.
- **25.** Verify: all pages 200, redirects work, 404 serves, assets load.

---

## Open decisions
- Hireme/CV: embed as-is vs migrate into new design (task 6).
- Blog permalink scheme + image handling (tasks 9–10).
- Sitemap: include blog URLs or not (task 19).
- Old domain/blog/FTP endgame (task 21).

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
