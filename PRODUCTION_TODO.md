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

### Look & feel

- **1.** **Audit text consistency across all pages.** Review **text colour, font
      size, and content margin/max-width** on every page (home, about, cv,
      projects, blog, books, achievements, 404). Too many inconsistencies
      creeping in: e.g. justified vs left-aligned body text, `card-body`'s
      smaller default size vs `text-base`, ad-hoc `text-[15px]` overrides,
      per-page `maxWidthClass`. Settle on shared conventions (a body size, when
      to justify, default content width) and apply them uniformly — ideally via
      shared components / global.css rather than per-page classes.
- **2.** **Audit buttons → settle primary vs secondary.** Review every button across
      the site and decide which is `primary` (one dominant action per view) vs
      `secondary` (everything else). Then **define the format** of each — including
      the `secondary` token itself, which is currently off-theme (blue/orange);
      either retune it (neutral/green-family) or style secondary buttons as
      `btn-outline`/`btn-ghost`/`btn-neutral` so they recede instead of clashing.
      Then **verify both themes** (light/dark) look right.
- **3.** **Settle body font size.** Deferred from the task-1 audit (the harder half).
      Body copy is currently inconsistent: `text-lg` (home intro), `text-base`
      (about/books), ad-hoc `text-[15px]` (about cards), `prose-lg` (posts), and a
      broken `text-1xl` (not a real class → silently falls back to base) in
      `HorizontalCard`, `ProjectCard`, `HorizontalShopItem`. Pick one body size, fix
      the `text-1xl` typo, and drive it from `global.css` / shared components rather
      than per-page. Width, justify, muted text, links and heading semantics were
      already settled in the first pass.

### Infrastructure / build

- **4.** **GH Action:** `astro build` → publish `site/dist/` to Pages.

### Cut-over (last)

- **5.** Merge `unified-redesign` → `migration/gh-pages`.
- **6.** Flip GH Pages deploy to the build Action publishing **only** `site/dist/`
      (with `base: "/DiogoNunesCom"`, already configured).
- **7.** **Verify the new site** is live and correct at
      `dialex.github.io/DiogoNunesCom/`: all pages 200, redirect shortcuts work, 404
      serves, assets/images load.
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
