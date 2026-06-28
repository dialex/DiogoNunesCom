import type { ImageMetadata } from "astro";

// Resolve a blog hero path (frontmatter string) to an optimisable ImageMetadata.
// We do NOT use the content-collection image() schema helper — it emits the full-size
// original into the bundle for every post.
//
// The glob is LAZY (no `eager`): it returns per-file dynamic importers and only the
// heroes we actually resolve get imported. Critically, this avoids touching the 227
// in-body content images that ALSO match this pattern but are already handled by
// Astro's markdown pipeline — an eager glob double-registers them and races the
// original-emit vs optimise steps, causing flaky `ENOENT … dist/_astro/*.jpg` builds.
const loaders = import.meta.glob<ImageMetadata>(
  "/src/assets/blog/uploads/**/*.{jpg,JPG,jpeg,JPEG,png,PNG,gif,GIF,webp}",
  { import: "default" },
);

/** True when `p` is a path into the blog asset tree (vs a public/ string or undefined). */
export function isBlogAsset(p: unknown): p is string {
  return typeof p === "string" && p.includes("assets/blog/uploads/");
}

/** Resolve a stored hero path (e.g. "../../assets/blog/uploads/…") to ImageMetadata. */
export async function resolveBlogImage(p?: string): Promise<ImageMetadata | undefined> {
  if (!p) return undefined;
  const i = p.indexOf("assets/blog/uploads/");
  if (i === -1) return undefined;
  const loader = loaders["/src/" + p.slice(i)];
  return loader ? await loader() : undefined;
}
