// Turn a tag/category name into a URL-safe slug, matching WordPress's scheme:
//   "free and open" -> "free-and-open", "c#" -> "c", "CI Pipelines" -> "ci-pipelines"
export default function slugify(value: string): string {
  return value
    .trim()
    .toLowerCase()
    .replace(/[^a-z0-9]+/g, "-")
    .replace(/^-+|-+$/g, "");
}
