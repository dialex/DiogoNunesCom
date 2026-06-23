import { defineConfig } from 'astro/config';
import mdx from '@astrojs/mdx';
import sitemap from '@astrojs/sitemap';

// https://astro.build/config
// Tailwind 4 is wired via PostCSS (postcss.config.mjs) rather than
// @tailwindcss/vite, which is not yet compatible with Astro 6's rolldown-vite.
export default defineConfig({
  site: 'https://www.diogonunes.com',
  // Short links / legacy paths from the old site. Replaces the hand-maintained
  // folders that each held a single meta-refresh index.html. Astro emits the
  // redirect pages at build time for static output.
  redirects: {
    // External profiles & projects
    '/pod': 'https://linktr.ee/podser.podcast',
    '/test': 'https://github.com/dialex/start-testing',
    '/testing': 'https://dialex.github.io/start-testing/#/',
    '/purpose': 'https://leanpub.com/purpose',
    '/anime': 'https://anilist.co/user/dialexxx/animelist/Completed',
    '/ateneu': 'https://sites.google.com/view/ateneumontijo',
    // Photography (500px / Instagram)
    '/fotos': 'https://500px.com/p/diogonunes?view=galleries',
    '/photos': 'https://500px.com/p/diogonunes?view=galleries',
    '/travel': 'https://500px.com/p/diogonunes?view=galleries',
    '/portraits': 'https://500px.com/p/diogonunes/galleries/portraits',
    '/events': 'https://500px.com/p/diogonunes/galleries/events',
    '/fotos/montijo': 'https://www.instagram.com/montijo.ao.quadrado/',
    '/insta': 'https://www.instagram.com/montijo.ao.quadrado/',
    '/medium': 'https://dialex.medium.com/',
    // Internal — work/projects (old homepage #work section now lives at /projects)
    '/work': '/projects',
    '/work/jcdp': '/projects',
    '/it/work/jcdp': '/projects',
    '/jcdp': '/projects',
    '/work/personaltroller': '/projects',
    '/work/googleearthtypewriter': '/projects', // TODO: point at project slug once demo is migrated
    '/foto': '/projects', // old #hobbies section
    // Internal — CV / resume
    '/resume': '/cv',
    '/cvpdf': '/cv', // TODO: point at the resume PDF once the asset is migrated
    // Internal — blog / feed
    '/feed': '/rss.xml',
    '/blog/author/diogo-nunes': '/blog/',
    // Internal — pages not yet rebuilt (will resolve once those pages land)
    '/achievs': '/achievements',
    // Books page lives at /books; preserve the old Portuguese path.
    '/livros': '/books',
    '/livros/quero-mais': '/books#quero-mais'
  },
  integrations: [mdx(), sitemap()]
});
