// Astro does not prefix raw `href`/`src` strings with the configured `base`
// (https://docs.astro.build/en/reference/configuration-reference/#base), only
// asset imports and generated routes. Use this for any hand-written internal
// link or public/ asset reference so it resolves under the deploy subpath.
const BASE = import.meta.env.BASE_URL; // e.g. "/DiogoNunesCom/"

export function withBase(path: string): string {
  return `${BASE.replace(/\/$/, "")}/${path.replace(/^\//, "")}`;
}
