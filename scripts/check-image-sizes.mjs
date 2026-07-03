#!/usr/bin/env node
// Build-time guard: fail if any source image under src/assets is oversized, so a
// huge asset can't silently slip back into the repo (e.g. a 5 MB / 3000px export).
// Animated GIFs are exempt from the dimension cap (frames inflate byte size; they're
// re-encoded to animated WebP at build anyway). Run via `npm run check:images`
// (wired as prebuild). Tune the limits below if a legitimate asset needs more.
import { readdirSync, statSync } from "node:fs";
import { join, extname } from "node:path";
import sharp from "sharp";

const ROOT = "src/assets";
const MAX_DIM = 2000; // px on the longest side (sources are downscaled to ~1600)
const MAX_BYTES = 3_000_000; // 3 MB — catches egregious slip-ins (the old no-code-website.png was 5.5 MB)
const RASTER = new Set([".png", ".jpg", ".jpeg", ".webp", ".gif"]);

function walk(dir) {
  return readdirSync(dir, { withFileTypes: true }).flatMap((e) => {
    const p = join(dir, e.name);
    return e.isDirectory() ? walk(p) : [p];
  });
}

const violations = [];
for (const file of walk(ROOT)) {
  if (!RASTER.has(extname(file).toLowerCase())) continue;
  const bytes = statSync(file).size;
  let meta;
  try {
    meta = await sharp(file, { animated: true }).metadata();
  } catch {
    continue; // unreadable by sharp — skip
  }
  const animated = (meta.pages ?? 1) > 1;
  const longest = Math.max(meta.width ?? 0, meta.pageHeight ?? meta.height ?? 0);
  if (longest > MAX_DIM) violations.push(`${file}: ${longest}px (max ${MAX_DIM})`);
  if (bytes > MAX_BYTES && !animated) {
    violations.push(`${file}: ${(bytes / 1024 / 1024).toFixed(1)}MB (max ${(MAX_BYTES / 1024 / 1024).toFixed(1)}MB)`);
  }
}

if (violations.length) {
  console.error(`\n✗ ${violations.length} oversized source image(s) — downscale before committing:`);
  for (const v of violations) console.error("  " + v);
  console.error(`\nGuard: max ${MAX_DIM}px / ${(MAX_BYTES / 1024 / 1024).toFixed(1)}MB (see scripts/check-image-sizes.mjs).`);
  process.exit(1);
}
console.log("✓ image size guard: all source images within limits");
