// Prefixes the configured Astro `base` onto root-relative links and asset URLs
// found in markdown/MDX content. Astro applies `base` to generated routes and
// imported assets, but NOT to literal paths authored in content bodies, e.g.
//
//   [my book](/books)      ->   <a href="/DiogoNunesCom/books">
//   ![](/blog/x/img.png)   ->   <img src="/DiogoNunesCom/blog/x/img.png">
//
// Without this, every in-content internal link 404s on a subpath deployment.
// Relative paths (./, ../ — Astro-processed assets) and absolute URLs (http://,
// //, mailto:, #) are left untouched.

// Attributes that hold a single URL, keyed by tag name.
const URL_ATTRS = { a: "href", img: "src", source: "src", video: "src", audio: "src" };

function isInternal(value, base) {
  return (
    typeof value === "string" &&
    value.startsWith("/") &&
    !value.startsWith("//") && // protocol-relative URL
    !value.startsWith(base) // already prefixed
  );
}

export default function rehypeBaseLinks(base = "/") {
  // Normalise so base has exactly one trailing slash and we never double it.
  const prefix = base.replace(/\/+$/, "");

  function walk(node) {
    if (node.type === "element") {
      const attr = URL_ATTRS[node.tagName];
      if (attr && isInternal(node.properties?.[attr], base)) {
        node.properties[attr] = prefix + node.properties[attr];
      }
      // <img srcset> / <source srcset>: comma-separated "url descriptor" list.
      const srcset = node.properties?.srcset;
      if (typeof srcset === "string") {
        node.properties.srcset = srcset
          .split(",")
          .map((entry) => {
            const [url, ...descriptor] = entry.trim().split(/\s+/);
            return [isInternal(url, base) ? prefix + url : url, ...descriptor].join(" ");
          })
          .join(", ");
      }
    }
    (node.children ?? []).forEach(walk);
  }

  return (tree) => walk(tree);
}
