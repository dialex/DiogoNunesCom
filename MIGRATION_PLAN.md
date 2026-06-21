# GH Pages Migration Plan — remaining work

Moving `diogonunes.com` from paid FTP host → free GitHub Pages.
Working branch: `migration/gh-pages`. Keep `main` untouched until cut-over.

Code migration is complete: PHP removed, redirects converted to static stubs, static cleanup done, and all `mailto:`/email mentions stripped from the HTML. Branch is pushed. What's left:

---

## A. Verify staging

Branch is pushed. On `https://dialex.github.io/DiogoNunesCom/`:

- ⚠️ `_config.yml` put GH Pages into **Jekyll mode**. The site is hand-written HTML with no front matter, so it should copy verbatim — but confirm all pages + redirect stubs render and there are no console 404s.
- Confirm exclusions return 404: `/assets/achiev-generator/`, `/MIGRATION_PLAN.md`.
- Assets (CSS/JS/img) will look broken on the `/DiogoNunesCom/` subpath — expected, resolves at the root custom domain.

> Residual (low priority): the CV PDF `assets/downloads/misc/NunesDiogo-Resume2021-EN.pdf` still contains the email address (normal for a résumé, but noting it since we scrubbed the HTML for anti-spam).

## B. ⚠️ BLOCKER — the WordPress blog (resolve before DNS cut-over)

`/blog/` is a WordPress install on the old hosting, not in this repo. It's linked in the nav of every page and `feed/` points to `/blog/feed/`. The moment DNS points the apex/`www` at GH Pages, `diogonunes.com/blog/` resolves to GH Pages and **404s** — the blog goes dark, before and independent of the FTP cancellation.

**Resolve first:** move the blog to a subdomain (e.g. `blog.diogonunes.com`) and update the site links, or keep a host/proxy serving `/blog/`. Do not run the DNS cut-over until this is decided.

## C. DNS cut-over

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
3. **Verify:** `https://www.diogonunes.com` loads, apex redirects to www, no mixed content, all shortcut redirects work.

## D. Cancel FTP hosting

Wait 3–7 days after cut-over. Monitor:
- Site loads from multiple locations (DNS propagation checker).
- No spike in 404s (GH Pages traffic graph or analytics).
- All shortcut redirects still work.

Then cancel the FTP subscription. Keep an export/backup of the FTP filesystem just in case (verify the blog is preserved — it's not in this repo).

---

## Reference notes

- GH Pages soft limits: 1GB repo size, 100GB/month bandwidth. Repo ~175M — well within.
- GH Pages doesn't support `.htaccess`, server-side includes, PHP, or arbitrary headers.
- If server-side logic is ever needed again: Cloudflare Pages or Netlify (free tiers) support edge functions.
