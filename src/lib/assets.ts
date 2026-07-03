import type { ImageMetadata } from "astro";

// Lazy globs for every optimisable asset tree under src/assets. Lazy (no `eager`) so only
// the assets we actually resolve get imported — critical for the blog tree, which also
// holds the 227 markdown body images already handled by Astro's pipeline (an eager glob
// double-registers them and races original-emit vs optimise → flaky ENOENT cold builds).
const loaders: Record<string, () => Promise<unknown>> = {
  ...import.meta.glob("/src/assets/blog/uploads/**/*.{jpg,JPG,jpeg,JPEG,png,PNG,gif,GIF,webp}", { import: "default" }),
  ...import.meta.glob("/src/assets/projects/*.{jpg,jpeg,png,gif,webp}", { import: "default" }),
  ...import.meta.glob("/src/assets/books/*.{jpg,jpeg,png,webp}", { import: "default" }),
};

// Stored paths look like: blog "../../assets/blog/uploads/…", project "/projects/x.png",
// book "/books/x.png". Map each to the glob key "/src/assets/…".
function toKey(p: string): string | undefined {
  const i = p.indexOf("assets/blog/uploads/");
  if (i !== -1) return "/src/" + p.slice(i);
  if (p.includes("/projects/")) return "/src/assets" + p.slice(p.indexOf("/projects/"));
  if (p.includes("/books/")) return "/src/assets" + p.slice(p.indexOf("/books/"));
  return undefined;
}

/** True when `p` resolves to an optimisable src/assets image (vs a public/ string, video, or undefined). */
export function isAsset(p: unknown): p is string {
  return typeof p === "string" && toKey(p) !== undefined && toKey(p)! in loaders;
}

/** Resolve a stored image path to optimisable ImageMetadata (undefined if not found). */
export async function resolveAsset(p?: string): Promise<ImageMetadata | undefined> {
  if (!p) return undefined;
  const key = toKey(p);
  const loader = key ? loaders[key] : undefined;
  return loader ? ((await loader()) as ImageMetadata) : undefined;
}
