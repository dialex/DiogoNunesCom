import { defineConfig } from "astro/config";
import mdx from "@astrojs/mdx";
import sitemap from "@astrojs/sitemap";
import rehypeFigureCaptions from "./src/plugins/rehype-figure-captions.mjs";
import rehypeBaseLinks from "./src/plugins/rehype-base-links.mjs";

// Deployed as a GitHub Pages project site, served under this subpath. Shared
// with the rehype plugin so in-content links get the same prefix as routes.
const BASE = "/DiogoNunesCom/";

// https://astro.build/config
// Tailwind 4 is wired via PostCSS (postcss.config.mjs) rather than
// @tailwindcss/vite, which is not yet compatible with Astro 6's rolldown-vite.
export default defineConfig({
  site: "https://dialex.github.io",
  base: BASE,
  // Short links / legacy paths from the old site. Replaces the hand-maintained
  // folders that each held a single meta-refresh index.html. Astro emits the
  // redirect pages at build time for static output.
  redirects: {
    // External profiles & projects
    "/pod": "https://linktr.ee/podser.podcast",
    "/test": "https://github.com/dialex/start-testing/#/",
    "/testing": "https://dialex.github.io/start-testing/#/",
    "/purpose": "https://leanpub.com/purpose",
    "/anime": "https://anilist.co/user/dialexxx/animelist/Completed",
    "/ateneu": "https://sites.google.com/view/ateneumontijo",
    // Photography (Unsplash / 500px / Instagram)
    "/unsplash": "https://unsplash.com/@dialex",
    "/fotos": "https://500px.com/p/diogonunes?view=galleries",
    "/photos": "https://500px.com/p/diogonunes?view=galleries",
    "/travel": "https://500px.com/p/diogonunes?view=galleries",
    "/portraits": "https://500px.com/p/diogonunes/galleries/portraits",
    "/events": "https://500px.com/p/diogonunes/galleries/events",
    "/fotos/montijo": "https://www.instagram.com/montijo.ao.quadrado/",
    "/insta": "https://www.instagram.com/montijo.ao.quadrado/",
    "/medium": "https://dialex.medium.com/",
    // Internal targets must carry the base prefix: Astro applies `base` to the
    // redirect page location but NOT to the destination string.
    // Internal: Work
    "/work": `${BASE}projects/`,
    "/work/jcdp": `${BASE}projects/jcolor/`,
    "/it/work/jcdp": `${BASE}projects/jcolor/`,
    "/jcdp": `${BASE}projects/jcolor/`,
    "/work/personaltroller": `${BASE}projects/`,
    "/work/googleearthtypewriter": `${BASE}projects/`,
    // Internal: About me
    "/about-me": `${BASE}about/`,
    "/hire-me": `${BASE}about/`,
    "/cv": `${BASE}about/`,
    "/resume": `${BASE}about/`,
    "/cvpdf": `${BASE}docs/cv.pdf`,
    // Internal: Blog
    "/feed": `${BASE}rss.xml`,
    "/blog/feed": `${BASE}rss.xml`,
    "/blog/author/diogo-nunes": `${BASE}blog/`,
    // Internal pages
    "/achievs": `${BASE}achievements/`,
    "/livros": `${BASE}books/`,
    "/livros/quero-mais": `${BASE}books/`,
  },
  integrations: [mdx(), sitemap()],
  markdown: {
    rehypePlugins: [rehypeFigureCaptions, [rehypeBaseLinks, BASE]],
  },
});
