import { z, defineCollection } from "astro:content";
import { glob } from "astro/loaders";

const blogSchema = z.object({
  title: z.string(),
  description: z.string(),
  pubDate: z.coerce.date(),
  updatedDate: z.string().optional(),
  heroImage: z.string().optional(),
  heroCaption: z.string().optional(),
  badge: z.string().optional(),
  tags: z
    .array(z.string())
    .refine((items) => new Set(items).size === items.length, {
      message: "tags must be unique",
    })
    .optional(),
  // WordPress categories, kept distinct from tags so we can decide later
    // whether one of the two is enough.
  categories: z
    .array(z.string())
    .refine((items) => new Set(items).size === items.length, {
      message: "categories must be unique",
    })
    .optional(),
});

const projectSchema = z.object({
  title: z.string(),
  description: z.string(),
  // `image` is the card thumbnail. `detailImage`/`detailVideo` are the larger
  // media shown on the detail page (some projects had a distinct one in the old
  // site); the detail page falls back to `image` when neither is set.
  image: z.string().optional(),
  detailImage: z.string().optional(),
  detailVideo: z.string().optional(),
  // freeform on purpose — some projects span multiple years, e.g. "2011, 2016, 2020"
  date: z.string(),
  skills: z.array(z.string()),
  links: z
    .array(
      z.object({
        label: z.string(),
        url: z.string(),
        // mark one link as the main CTA — renders solid; others render neutral
        primary: z.boolean().optional(),
      }),
    )
    .optional(),
});

export type BlogSchema = z.infer<typeof blogSchema>;
export type ProjectSchema = z.infer<typeof projectSchema>;

const blogCollection = defineCollection({
  loader: glob({ pattern: "**/*.{md,mdx}", base: "./src/content/blog" }),
  schema: blogSchema,
});
const projectCollection = defineCollection({
  loader: glob({ pattern: "**/*.{md,mdx}", base: "./src/content/projects" }),
  schema: projectSchema,
});

export const collections = {
  blog: blogCollection,
  projects: projectCollection,
};
