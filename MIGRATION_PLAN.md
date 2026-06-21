# GH Pages Migration Plan

Moving `diogonunes.com` from paid FTP host → free GitHub Pages.

Working branch: `migration/gh-pages`. Keep `main` untouched until cut-over.

---

## Step 1 — Enable GH Pages on this branch, confirm it renders

**Goal:** prove the site loads on a staging URL before any code changes.

- Push `migration/gh-pages` to GitHub.
- Repo Settings → Pages → Source: branch `migration/gh-pages`, folder `/ (root)`.
- Wait for first deploy.
- Open `https://dialex.github.io/DiogoNunesCom/` (or whatever the repo path resolves to).
- Verify:
  - `index.html` loads.
  - CSS, JS, images load (check console for 404s).
  - Navigation to `/work/`, `/livros/`, `/hireme/`, `/achievements.html` works.
  - PHP-dependent things will be broken — that's expected, note them.

**Acceptance:** site visually loads on GH-hosted URL. We have a working playground.

---

## Step 2 — Rename `error.php` → `404.html` — ✅ done

`git mv error.php 404.html`; the dynamic PHP error-code block was replaced with a hardcoded "404 Not Found" message (GH Pages only ever serves 404.html for not-found paths).

---

## Step 3 — Replace `privatedaddy.php` email obfuscation — ✅ done

Removed the `index.html:107` PHP include. Per decision, the footer Contact email was removed entirely (replaced with "Reach out via my social profiles."); the `#contact` anchor was kept since the nav links to it. Deleted `assets/php/privatedaddy.php`.

> **TODO — review all email mentions site-wide (anti-spam).** Only `index.html` footer was handled. The address `email@diogonunes.com` still appears in plain `mailto:` in: `index.html` head (`<meta itemprop="email">`), `achievements.html`, `hireme/index.html` (×2), and `assets/achiev-generator/template.html`. None of these were ever obfuscated. Decide whether to remove/obfuscate them to avoid scrapers.

---

## Step 4 — Remove `downloadmanager` PHP click counter — ✅ done

Removed both `display.php` script tags (`index.html`, `livros/index.html`). The 3 `click.php?id=N` download links in `livros/index.html` were mapped via the CCount backup to their Leanpub URLs and replaced with direct external links (`target="_blank" rel="noopener"`):
- id=1 → `leanpub.com/umlivrodepoesia`
- id=4 → `leanpub.com/ParaLaDoPortao`
- id=13 → `leanpub.com/purpose`

The dynamic `(N já leram / people already did)` counts were **frozen** as static text using the owner's last-known totals, rounded: Para lá do portão **4500+**, Purpose **2300+**, Poesia **4600+**, Portugal **~3000**.

---

## Step 5 — Convert `.htaccess` redirects to static — ✅ done

Generated 23 static redirect stubs (9 internal + 14 external) as `foo/index.html` with `<meta http-equiv="refresh">` + `<link rel="canonical">` + `<meta name="robots" content="noindex">` + a fallback `<a>`. Nested shortcuts handled: `fotos/montijo`, `it/work/jcdp`, `blog/author/diogo-nunes`. HTTP→HTTPS and `ErrorDocument` rules dropped (GH Pages handles HTTPS automatically; 404 served via `404.html` from Step 2). Deleted `.htaccess`.

> Note: `index.html` links to `/blog/` but no `blog/` content exists in the repo (only the new `blog/author/diogo-nunes/` redirect stub). Flag for Step 6 audit — the `/blog/` nav link likely 404s on the static host.

---

## Step 6 — Audit remaining server-side artifacts — ✅ done

Repo is now 100% PHP-free (`git grep '<?php'` and `git ls-files '*.php'` both empty). Actions taken:

- `assets/php/` — gone (emptied in Step 3).
- `assets/downloadmanager-installer/` — was the **full CCount PHP app** (admin/, install/, index.php). Deleted (won't run; publishing admin/install scripts is poor hygiene).
- `assets/downloadmanager-backup/` — CCount data backups. Deleted.
- `feed/index.html` — verified static (meta-refresh to `/blog/feed/`), kept. See blog warning below.
- `404.html` — fixed mixed content: 2 Google Fonts links + 1 cdnjs script were `http://`, upgraded to `https://` (matches `index.html`).
- `robots.txt` / `ads.txt` / `app-ads.txt` — kept (plain text). Sitemap URL fine after DNS flip; `assets/sitemap.xml` present.
- `assets/achiev-generator/` (18M internal Node tool) — excluded from the published site via a new minimal `_config.yml` (`exclude:`), which also drops `MIGRATION_PLAN.md` and `README.md`. GH Pages' default Jekyll copies the hand-written HTML verbatim (no front matter), so pages render unchanged.

Also upgraded the 3 remaining `http://` anchor links in `hireme/index.html` (lage2/tedxist/equalexperts) to `https://`. No `http://` resource or anchor links remain anywhere in the tracked HTML.

---

## Step 7 — DNS cut-over

> ⚠️ **BLOCKER — the WordPress blog.** `/blog/` is a WordPress install on the old hosting, not in this repo. It's linked in the nav of every page and `feed/` points to `/blog/feed/`. The moment the apex/`www` DNS points at GH Pages, `diogonunes.com/blog/` will resolve to GH Pages and **404** — the blog goes dark, independent of (and before) the FTP cancellation in Step 8. **Resolve before cut-over:** move the blog to a subdomain (e.g. `blog.diogonunes.com`) and update the site links, or keep a host/proxy serving `/blog/`. Do not run Step 7 until this is decided.

Once staging is fully working:

1. **Day before:** lower TTL on DNS records to 300s (5min). Old TTL drains.
2. **Cut-over day:**
   - Commit `CNAME` file containing `www.diogonunes.com` to the branch.
   - Merge `migration/gh-pages` → `main`.
   - Repo Settings → Pages → Source: branch `main`.
   - Repo Settings → Pages → Custom domain: `www.diogonunes.com`.
   - DNS provider:
     - Apex `diogonunes.com`: 4 A records → `185.199.108.153`, `185.199.109.153`, `185.199.110.153`, `185.199.111.153`.
     - `www` CNAME → `dialex.github.io`.
   - Wait for HTTPS cert to provision (GH does Let's Encrypt automatically, can take ~1h).
3. **Verify:** `https://www.diogonunes.com` loads, apex redirects to www, no mixed content, redirects work.

---

## Step 8 — Cancel FTP hosting

Wait 3–7 days. Monitor:
- Site loads from multiple locations (use a DNS propagation checker).
- No spike in 404s (check GH Pages traffic graph or external analytics).
- All shortcut redirects still work.

Then cancel FTP subscription. Keep an export/backup of the FTP filesystem just in case (probably already in this repo, but verify).

---

## Risk notes

- GH Pages soft limit: 1GB repo size, 100GB/month bandwidth. Repo is 175M, well within.
- GH Pages doesn't support `.htaccess`, server-side includes, PHP, or arbitrary headers.
- If you ever need server-side logic again: Cloudflare Pages or Netlify (also free tiers) support edge functions.
