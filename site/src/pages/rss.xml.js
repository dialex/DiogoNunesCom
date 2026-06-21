import { SITE_TITLE, SITE_DESCRIPTION } from "../config";
import { getCollection } from "astro:content";

// Hand-rolled RSS instead of @astrojs/rss: the gate (koi.security) blocks
// @astrojs/rss >= 4.0.12, and <= 4.0.11 uses a Zod 3 API (z.function().returns)
// that Astro 6 (Zod 4) removed. A plain XML endpoint avoids the dependency.
const esc = (s) =>
  String(s)
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;");

export async function GET(context) {
  const site = context.site?.href ?? "https://www.diogonunes.com/";
  const blog = (await getCollection("blog")).sort(
    (a, b) => b.data.pubDate.valueOf() - a.data.pubDate.valueOf()
  );

  const items = blog
    .map((post) => {
      const url = new URL(`/blog/${post.id}/`, site).href;
      return `    <item>
      <title>${esc(post.data.title)}</title>
      <link>${url}</link>
      <guid>${url}</guid>
      <pubDate>${post.data.pubDate.toUTCString()}</pubDate>
      <description>${esc(post.data.description)}</description>
    </item>`;
    })
    .join("\n");

  const xml = `<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0">
  <channel>
    <title>${esc(SITE_TITLE)}</title>
    <description>${esc(SITE_DESCRIPTION)}</description>
    <link>${site}</link>
${items}
  </channel>
</rss>`;

  return new Response(xml, {
    headers: { "Content-Type": "application/xml; charset=utf-8" },
  });
}
