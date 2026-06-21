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

## Step 3 — Replace `privatedaddy.php` email obfuscation

Used in `index.html:107` as a PHP include to hide email from scrapers.

Options (pick one):
- **A. JS obfuscation** — small inline script that builds `mailto:` at runtime from split strings. Equivalent protection.
- **B. Plain `<a href="mailto:...">`** — simpler. Modern scrapers defeat obfuscation anyway.
- **C. Contact form via Formspree/Web3Forms** — overkill for this site.

Recommendation: **B** (plain mailto) or **A** if you want light obfuscation.

Delete `assets/php/privatedaddy.php` after.

---

## Step 4 — Remove `downloadmanager` PHP click counter

Used in `index.html` and `livros/index.html`:
- `<script src="/assets/downloadmanager/display.php">` — renders download stats.
- `<a href="/assets/downloadmanager/click.php?id=N">` — counts clicks then redirects.

Options:
- **A. Drop counter entirely.** Replace `click.php?id=N` links with direct file URLs (the actual PDF/zip path). Remove `display.php` script tag.
- **B. External analytics.** Add Plausible/Umami/GA; track downloads as events.

Recommendation: **A** unless the counter numbers matter to you.

Need to identify what file each `id=N` maps to (look at `assets/downloadmanager` config or old backup).

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
