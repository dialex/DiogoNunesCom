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

## Step 5 — Convert `.htaccess` redirects to static

`.htaccess` is ignored by GH Pages. ~25 redirects to migrate:

- HTTP→HTTPS: GH Pages does this automatically. ✓ delete that rule.
- Internal: `/achievs → /achievements.html`, `/books → /livros`, `/cv → /hireme`, etc.
- External: `/medium → dialex.medium.com`, `/insta → instagram.com/...`, etc.

**Decision: static folders with meta-refresh.**

For each `/foo`, create `foo/index.html` with `<meta http-equiv="refresh" content="0;url=...">` + a canonical `<link rel="canonical">` + a fallback `<a>` for no-JS/no-meta clients.

Works for both internal (`/achievs → /achievements.html`) and external (`/medium → dialex.medium.com`) redirects. Same mechanism, identical to what Jekyll's `jekyll-redirect-from` would generate at build time.

Why not Jekyll: site is hand-written HTML, not a Jekyll project. Adopting Jekyll just for 25 redirects risks it touching templates/paths it shouldn't, and adds Ruby/bundler to local dev.

Why not a `/s/?link=foo` JS router: turns short URLs into `/s/?link=medium`, defeating the point of shortcuts. Breaks existing inbound links and SEO.

Implementation: write a small script that parses `.htaccess` `Redirect` lines and generates each folder. ~25 stub files.

Delete `.htaccess` after.

---

## Step 6 — Audit remaining server-side artifacts

Sweep for anything else that won't work statically:

- `assets/php/` → delete after Step 3.
- `assets/downloadmanager/` → delete after Step 4.
- `assets/downloadmanager-installer/` → looks like vendor docs, probably safe to delete.
- `assets/downloadmanager-backup/` → likely DB backup, delete.
- Any `.php` in subdirs.
- `feed/index.html` — verify it's static, not a PHP-generated RSS.
- `robots.txt`, `ads.txt`, `app-ads.txt` — keep, they're plain text.
- `Sitemap` URL in robots.txt still points to `diogonunes.com` — fine, will be correct after DNS flip.

---

## Step 7 — DNS cut-over

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
