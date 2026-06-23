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

Flat-numbered so they're easy to reference ("let's do task 7"). Grouped by area
for context only — the numbers, not the groups, are the handle.

### Content & pages
1. **Homepage + avatar:** put real personal info (name, intro, …) from old
      `index.html` on the home page; replace the top-left sidebar photo with the
      real avatar (`assets/img/avatar.jpg`). Replace Astrofy placeholder copy.
2. ✅ **Trim the sidebar menu.**
3. **Projects + hobbies → `projects` collection.** Port each modal from old
      `index.html` into `site/src/content/projects/` (schema: image, description,
      date, skills[], links[]) → detail page at `/projects/<slug>`. Old **thumbnail**
      → card image; old **detail image** → detail-page image; carry description,
      date, skills. `jcolor.md` is the seeded pattern. Decide projects-vs-hobbies
      handling (one collection w/ tag, or split).
      - Work: JColor, DCID, Google Earth Typewriter, PISTAE, Talks, Research,
        Open Source, Android Apps, Birthday Slack Bot, YumYutton, Testing Course,
        No-code website
      - Hobbies: Photography, The Geeky Gecko, Books, Pod Ser, Code4PT, Readers' Forum
4. ✅ **Achievements page.**
5. **achiev-generator tool:** update the internal generator in
      `assets/achiev-generator/` (`index.js`, `template.html`, `achievs.csv`) so its
      output matches the new achievements page markup/design above.
6. **Hireme / CV page — DECISION PENDING.** Either (1) embed the existing
      `/hireme` page/design largely as-is, or (2) migrate the résumé content into the
      redesign look (Astrofy `cv.astro` has a timeline). Pick before building.
7. **Misc pages:** decide port-vs-fold-in for `donate.html`, `thanks.html`, and
      Books (from `/livros`).

### Blog (BIG)
8. Import all WP-exported post `.md` files into `site/src/content/blog/`
      (→ one static page per post + index + tags + RSS). Remove placeholder
      `post1/2/3.md`.
9. **Images:** find where WP images live + how they're referenced; decide
      rewrite-to-local (`src/assets`) vs keep external.
10. **Permalinks/SEO:** preserve inbound links via per-post `slug`; settle the
      permalink scheme.
11. Confirm index, tag pages, and RSS generate correctly.

### Look & feel
12. [x] ✅ **Sidebar-footer social icons** (LinkedIn, Instagram, Goodreads, GitHub, RSS).
13. **Light/dark theme following the OS** (one task, two requirements):
      (a) provide coherent light **and** dark themes; (b) auto-detect the user's OS
      preference (`prefers-color-scheme`) and default to it on first load, with the
      manual toggle still working on top.
14. ✅ **Replace template placeholders** (`SITE_TITLE`/`SITE_DESCRIPTION`, OG image) — done as part of task 26.

### SEO
26. ✅ **Port SEO metadata from the old site** (title/description, author, canonical, favicon set, theme-color, Schema.org Person JSON-LD, GA4, robots.txt sitemap).

### Redirects
15. [x] ✅ **Migrate legacy redirects to Astro `redirects` config.**

### Infrastructure / build
16. [x] **Dependency upgrade — DONE.** Astro 5 (`d119ead`), then Astro 6 + Tailwind 4
      + DaisyUI 5 (`071cee8`). Content collections migrated to the loader API
      (`src/content.config.ts`, `glob()`, `entry.id`, `render(entry)`);
      `ViewTransitions` → `ClientRouter`. Tailwind 4 wired via **PostCSS**
      (`postcss.config.mjs`), not `@tailwindcss/vite` (incompatible with Astro 6's
      rolldown-vite); CSS-based config in `global.css`; `tailwind.config.cjs` removed.
      Builds clean, routes 200, daisyUI `lofi` theme renders.
17. **`npm audit`** — review 6 reported vulns (3 low, 2 moderate, 1 high).
18. **Astro `base`** set for the deploy target: `/DiogoNunesCom/` for the subpath,
      or `/` if DNS is flipped to the custom domain at cut-over.
19. **Sitemap:** generate fresh via the Astro sitemap integration; decide whether
      blog URLs are included.
20. **GH Action:** `astro build` → publish `site/dist/` to Pages.

### Cut-over (last)
21. Resolve **old domain / blog / FTP endgame** first: WP blog still on old host;
      decide its fate before cancelling FTP.
22. Merge `unified-redesign` → `migration/gh-pages`.
23. Flip GH Pages deploy to the build Action publishing **only** `site/dist/`.
24. DNS cut-over + decommission FTP & WordPress.
25. Verify: all pages 200, redirects work, 404 serves, assets load.

---

## Open decisions
- Hireme/CV: embed as-is vs migrate into new design (task 6).
- Projects vs hobbies: one collection w/ tag, or split (task 3).
- Blog permalink scheme + image handling (tasks 9–10).
- Misc pages (donate/thanks/books): port vs fold in (task 7).
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
