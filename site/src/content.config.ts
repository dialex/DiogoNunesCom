import { z, defineCollection } from "astro:content";
import { glob } from "astro/loaders";

const blogSchema = z.object({
    title: z.string(),
    description: z.string(),
    pubDate: z.coerce.date(),
    updatedDate: z.string().optional(),
    heroImage: z.string().optional(),
    badge: z.string().optional(),
    tags: z.array(z.string()).refine(items => new Set(items).size === items.length, {
        message: 'tags must be unique',
    }).optional(),
});

const projectSchema = z.object({
    title: z.string(),
    description: z.string(),
    image: z.string().optional(),
    // freeform on purpose — some projects span multiple years, e.g. "2011, 2016, 2020"
    date: z.string(),
    skills: z.array(z.string()),
    links: z.array(z.object({
        label: z.string(),
        url: z.string(),
    })).optional(),
    badge: z.string().optional(),
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
    'blog': blogCollection,
    'projects': projectCollection
}
