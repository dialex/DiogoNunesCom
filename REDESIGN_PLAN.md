# Redesign Plan — Converge 3 sites into one static Astro site

**Goal:** merge the three differently-styled properties of `diogonunes.com` into a
single, unified, fully-static site built with **Astro** using the **Astrofy**
template (https://github.com/manuelernestog/astrofy). Deploy on GitHub Pages.

This is a companion to `MIGRATION_PLAN.md` (FTP → GH Pages). That plan kept the
site as hand-written static HTML and explicitly rejected a generator. **This plan
supersedes that decision** because the blog forces a generator anyway, and Astrofy
already covers every page type the site has.

Working branch / worktree: `explore/parallel` (off `migration/gh-pages`).

---

## Current state — three separate styles

| Property | Stack today | Notes |
|---|---|---|
| `diogonunes.com` (root) | Bootstrap + **Freelancer** theme (`assets/css/freelancer.css`) | Hand-written static HTML (`index.html`). |
| `diogonunes.com/hireme` | Own template: `main.css` + `themes.light/dark.css` + jQuery + scrollreveal | Résumé/CV page. Separate CSS/JS world. |
| `diogonunes.com/blog` | **WordPress** (PHP + MySQL) | Not in this repo. Dynamic. |

Other static sections in repo: `/work` (projects), `/livros` (books), plus
`achievements.html`, `donate.html`, `thanks.html`, `feed/`.

---

## Target state — one Astro (Astrofy) site

Single Astro project. Output is plain static HTML/CSS/JS (`astro build` → `dist/`),
served by GitHub Pages. No PHP, no DB, no server at runtime.

Astrofy ships the exact page types we need, so the mapping is near 1:1:

| Current | Astrofy equivalent |
|---|---|
| `index.html` (root) | Home / portfolio page |
| `/hireme` (résumé) | CV page (built-in timeline) |
| `/work`, `/livros` | Projects + Store pages |
| `/blog` (WordPress) | Blog (Markdown/MDX content collection) |

Free from Astrofy/Astro: light/dark theming, RSS, sitemap, SEO/OG tags,
responsive nav, one shared layout across all pages.

---

## Decisions locked in

- **Stack:** Astro + Astrofy (Tailwind/DaisyUI under the hood). Output static.
- **Blog content:** export WordPress → Markdown. **User already has the `.md`
  files for each post.** (Biggest pain point already done.)
- **WP comments:** ignored / dropped. Not migrating them. (No Giscus/utterances
  unless decided later.)
- **Build step:** accepted. GH Pages will build via a GitHub Action (`astro build`,
  publish `dist/`) — this replaces the "serve branch as-is" model in
  `MIGRATION_PLAN.md`.
- **Target look:** the Astrofy template (a fresh design — neither the old root nor
  the old hireme style is kept).

## Branch & deploy model

- **`migration/gh-pages` = PROD.** Serves the old (PHP-free) static site as-is at
  the GH Pages project subpath `dialex.github.io/DiogoNunesCom/`. We do **not**
  touch what it deploys until cut-over. Tag `static-deprecated-PHP` marks where the
  previous agent finished.
- **`unified-redesign` = DEV.** The Astro/Astrofy redesign lives here, in `site/`.
  Rebased on top of the latest `migration/gh-pages`. Developed locally only
  (`npm run dev` → localhost:4321). GH Pages never sees this branch.
- **Why a separate branch:** keeps prod = old site only. The redesign appears
  nowhere in prod, and we avoid the "both at different paths" trap (if `site/` were
  on the prod branch under root-serving, `/site/` would leak raw unbuilt source).
- **Re-sync:** if `migration/gh-pages` advances, rebase `unified-redesign` onto it
  again. Clean as long as prod commits don't touch `site/`.
  ⚠️ **Stop the dev server before rebasing** — vite's file watcher/cache writes
  mid-rebase corrupt it (we hit this once: scaffold commit went empty + untracked).

### Cut-over (prod flips to redesign-only) — happens later, needs BOTH:
1. Merge `unified-redesign` → `migration/gh-pages`.
2. Switch GH Pages deploy to a GitHub Action: `astro build` → publish **only**
   `site/dist/`. Old root files stay in git history + behind the tag, but are not
   in the published artifact, so they vanish from prod. Result: prod = redesign only.

- **⚠️ Astro `base` config at deploy time:** staging is the subpath
  `/DiogoNunesCom/` (commit `cc56c11`). Set `base: '/DiogoNunesCom/'` in
  `astro.config.mjs` so links resolve there — OR `base: '/'` if DNS has been
  flipped to the custom domain by cut-over. Left unset for now (localhost is root).

## Open questions (not yet decided)

- Exact URL/permalink scheme for blog posts — must preserve existing inbound links
  & SEO (set per-post `slug`).
- Image handling for blog posts (where the WP images live, how they're referenced
  in the `.md`, rewrite to local `src/assets` or keep external).
- ~~What to do with `/work` content~~ — **decided & built:** `projects` content
  collection (image, description, date, skills[], links[]) + per-project detail
  pages at `/projects/<slug>`. Seeded with real JColor entry. Remaining: port the
  other projects from the old homepage modals.
- What to do with `/livros` content — Store page vs. plain content collection.
- Whether to keep `achievements.html`, `donate.html`, `thanks.html` as-is ports or
  fold into the new design.
- Download files in `assets/downloads` — keep as static assets (no counter).

---

## Why Astro (context for later)

Astro is a static site generator: write content (Markdown) + reusable components,
run `astro build`, get a folder of plain HTML/CSS. The browser never sees Astro —
identical static end-state to today, but:

- Shared nav/head/footer authored **once** as components, stamped into every page
  at build (vs. copy-pasted across ~10 HTML files today).
- Blog = drop `.md` files into `src/content/blog/`; Astro generates one static page
  per post + index + RSS + tags automatically.
- One shared layout → all pages unified by construction = the "converge" goal.

Day-to-day: `npm run dev` (live preview) → edit `.md`/`.astro` → `git push` → GH
Action builds → site updates. JS/Node toolchain enters local dev.

```
your .md posts ─┐
your content    ├─► astro build ─► dist/ (plain HTML+CSS) ─► GitHub Pages
Astrofy layout ─┘
```

---

## Migration carry-overs from MIGRATION_PLAN.md

These still apply but get cleaner under Astro:

- **Redirects (~25 shortcuts):** use Astro's native `redirects` config instead of
  meta-refresh stub folders. (`/achievs`, `/books`, `/cv`, `/medium`, `/insta`, …)
- **PHP bits die naturally:** `privatedaddy.php` email obfuscation, the
  `downloadmanager` click counter, `assets/php/`. Static site = none of these exist.
- **GH Pages limits:** repo well within (175M vs 1GB). Fine.

---

## TODO — dependency upgrade (Astrofy ships old deps)

Astrofy template (v3.0.0) pins **Astro `^4.0.2`** and Astro-4-era integrations,
while Astro **5.x** is current. We already hit one symptom: `@astrojs/sitemap`
floated to 3.7.3 (needs the Astro-5 `astro:routes:resolved` hook) and crashed on
Astro 4.16 — pinned back to 3.2.1 as a stopgap.

- [ ] Audit all deps: `astro`, `@astrojs/{mdx,rss,sitemap,tailwind}`, `daisyui`,
      `tailwindcss`, `sharp`, `dayjs`, `@tailwindcss/typography`.
- [ ] Decide: stay on Astro 4 (low risk, but EOL-ing) **or** upgrade to Astro 5.
      Astro 5 changed content collections (`post.slug` → `post.id`, new
      `loader` API) — Astrofy's components use the old API, so a 5 upgrade means
      touching template code. Do this deliberately, not by accident.
- [ ] Once on a target Astro major, un-pin sitemap and move all integrations to
      matching versions. Re-run `npm run build` to confirm green.
- [ ] `npm audit` — scaffold reported vulnerabilities; review after the upgrade.

## Rough phase outline (to flesh out next)

1. **Scaffold** Astrofy in the worktree; get `npm run dev` running. — ✅ done
   (`site/`, builds clean, 16 pages).
2. **Dependency upgrade decision** (see TODO above) before building real content
   on top of a stack we might rip out.
4. **Proof of concept:** drop one real blog `.md` in, confirm it renders in style.
5. **Blog migration:** all posts in, fix images + permalinks + RSS.
6. **Port pages:** Home, CV (from hireme), Projects (from work), Store/Books
   (from livros), misc pages.
7. **Redirects** via Astro config.
8. **GH Action** build + deploy to Pages.
9. **DNS cut-over** (reuse MIGRATION_PLAN.md Step 7) + decommission FTP & WordPress.

---

## Status

- [x] Feasibility assessed — confirmed viable, good fit.
- [x] Stack + blog approach + build model decided.
- [x] Astro/Astrofy scaffolded in `site/`, builds clean (16 pages).
- [ ] Dependency upgrade decision (Astro 4 vs 5) — see TODO.
- [ ] Proof-of-concept blog post not started.
