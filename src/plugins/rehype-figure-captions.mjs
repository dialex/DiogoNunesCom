// Turns image captions into a semantic <figure> + <figcaption>, styled to
// match the hero caption in PostLayout.astro (centered, smaller, dimmed).
//
// The caption comes from the image's alt text:
//
//   ![My caption](/img.png)   ->   <figure><img><figcaption>My caption
//
// An image with empty alt renders no caption. For backwards-compat with the
// old "_italic line_ under the image" convention, a following italic-only
// paragraph is always consumed (so it never double-renders): alt wins when
// present, otherwise the italic line is used as the caption.

function isWhitespace(node) {
  return node.type === "text" && !node.value.trim();
}

// The single meaningful (non-whitespace) child of a node, or null.
function soleChild(node) {
  const kids = (node.children ?? []).filter((c) => !isWhitespace(c));
  return kids.length === 1 ? kids[0] : null;
}

// A <p> whose only content is an <img> or a linked <img> (<a><img></a>).
// Returns { media, img }, or null.
function mediaOf(node) {
  if (node?.type !== "element" || node.tagName !== "p") return null;
  const child = soleChild(node);
  if (!child) return null;
  if (child.tagName === "img") return { media: child, img: child };
  if (child.tagName === "a") {
    const inner = soleChild(child);
    if (inner?.tagName === "img") return { media: child, img: inner };
  }
  return null;
}

// A <p> whose only content is <em>…</em> (the italic caption line).
// Returns the <em> node, or null.
function captionOf(node) {
  if (node?.type !== "element" || node.tagName !== "p") return null;
  const child = soleChild(node);
  return child?.tagName === "em" ? child : null;
}

function walk(node) {
  if (!Array.isArray(node.children)) return;

  const out = [];
  for (let i = 0; i < node.children.length; i++) {
    const child = node.children[i];
    const found = mediaOf(child);

    if (found) {
      const { media, img } = found;

      // Always consume a following italic-only paragraph (legacy caption line)
      // so it can never double-render alongside the alt-derived caption.
      let j = i + 1;
      while (j < node.children.length && isWhitespace(node.children[j])) j++;
      const em = j < node.children.length ? captionOf(node.children[j]) : null;

      // Caption source: alt text wins; fall back to the legacy italic line.
      const alt = img.properties?.alt?.trim();
      const captionChildren = alt ? [{ type: "text", value: alt }] : em ? em.children : null;

      if (captionChildren) {
        out.push({
          type: "element",
          tagName: "figure",
          properties: { className: ["my-8"] },
          children: [
            media,
            {
              type: "element",
              tagName: "figcaption",
              properties: {
                className: ["not-prose", "mt-2", "text-center", "text-sm", "opacity-60"],
              },
              children: captionChildren,
            },
          ],
        });
        if (em) i = j; // drop the legacy caption paragraph
        continue;
      }
    }

    walk(child);
    out.push(child);
  }

  node.children = out;
}

export default function rehypeFigureCaptions() {
  return (tree) => walk(tree);
}
