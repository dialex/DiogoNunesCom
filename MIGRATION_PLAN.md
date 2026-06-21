# GH Pages Migration Plan — remaining work

Moving the site to free GitHub Pages. **Prod URL: `https://dialex.github.io/DiogoNunesCom/`** (project subpath, permanent — no custom domain).
Working branch: `migration/gh-pages`.

Done so far: PHP removed, `.htaccess` redirects → static stubs, server-side cleanup, all emails stripped from the HTML, and **all paths prefixed with `/DiogoNunesCom/`** so assets/nav/redirects work at the subpath. SEO meta tags point to the github.io URL; internal links are relative-prefixed; blog links intentionally still point to `www.diogonunes.com/blog` (WP, old host).

What's left:

---

## B. Regenerate `sitemap.xml`

The old `assets/sitemap.xml` was deleted (380 stale `www.diogonunes.com` URLs, mostly blog posts). Regenerate a fresh one listing current pages under `https://dialex.github.io/DiogoNunesCom/`. `robots.txt` already points there. Decide whether to include blog URLs (they live on the old domain).

## C. Open question — old domain, blog & FTP

The original goal was to stop paying for FTP hosting. With prod now on github.io, decide the endgame for `diogonunes.com`:

- The WordPress **blog** is still on the old host, and the site's `/blog` links + `feed/` point there. Cancelling FTP kills the blog.
- If the domain/blog are kept on the old host, FTP keeps costing money; if cancelled, the blog needs a new home and the blog links need updating.

Resolve before cancelling any hosting.

---

## Residual / low priority

- CV PDF `assets/downloads/misc/NunesDiogo-Resume2021-EN.pdf` still contains the email address (normal for a résumé; noted since we scrubbed the HTML).

## Reference notes

- GH Pages soft limits: 1GB repo, 100GB/month bandwidth. Repo ~175M — fine.
- GH Pages doesn't support `.htaccess`, SSI, PHP, or arbitrary headers.
- If server-side logic is ever needed: Cloudflare Pages or Netlify (free tiers) support edge functions.
