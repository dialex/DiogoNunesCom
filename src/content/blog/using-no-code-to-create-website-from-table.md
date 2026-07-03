---
title: "Using no-code to create a website from a table"
description: "A guide to create a website from a table of curated data, without a single line of code. Also works as a review of Airtable and Pory."
pubDate: "2021-03-01T07:00:00"
heroImage: "../../assets/blog/uploads/2021/01/iker-urteaga-TL5Vy1IM-uA-unsplash.jpg"
heroCaption: "Photo by Iker Urteaga on Unsplash"
tags: ["review", "tutorial", "utilities"]
categories: ["Technology"]
---

I have been hearing about "no-code" solutions for a while but never had the chance to use one. The concept is interesting because programming is usually a barrier for the average person. If they are not required to code, many more people would be able to develop digital and automated solutions to their problems.

## Goal

Let's say I want to create **a website with a curated list of local clubs and other infrastructures that promote a high quality of life**.

```gherkin
Given a person wants to practice basketball
 When they visit the site and search for "basketball"
 Then they see a list of local basketball groups
  And they see a list places to practice basketball
```

## Requirements

1. Collect data in rows
2. Categorize data with tags
3. Display data online
    - _(nice to haves)_
    - With search
    - With filters
    - With modern look
    - With free upkeep (domain + hosting)

## Candidates

- To implement requirement 1 and 2:
  - [Airtable](https://airtable.com/)
  - [Notion](https://www.notion.so/)
- To implement requirement 3:
  - [Pory](https://pory.io/) (requires Airtable)
  - [Table2Site](https://table2site.com/) (requires Airtable, paid, invite-only)

Given requirement 3 is a must, Airtable was a strong contender from the start.

I concluded **Notion** is more suited to create and manage knowledge bases – documents, notes, and markdown content in general. On the other hand, **Airtable** resembles a Google Sheet and seems to have more integrations to turn that table data into something else (like a website).

I found a few examples of Airtable powered websites, like the job board [Nolojo](https://www.nolojo.com/) or the curated list of newsletters [Pidgeon](https://www.pigeonnewsletters.com/).

Here's the detailed breakdown of my experience:

- [Webflow](https://webflow.com/)
  - ❌ It's for websites
  - ❌ You can't use it properly without paying
- [Job Board Fire](https://www.jobboardfire.com/)
  - ❌ Specific to job boards
  - ❌ Horribly expensive
- [Tabbli](https://tabbli.com/)
  - ❌ Paid after 15 days
- [Notion](https://www.notion.so/)
  - ✅ Useful free plan; Many features; Good for docs and knowledge bases; Big player
  - ❌ Doesn't implement well requirement #3, see [example](https://www.notion.so/Job-Board-cfea2c00f81a490b82489ab515177ecb)
- [Airtable](https://airtable.com/)
  - ✅ Useful free plan; Many features; Good for sheets and lists of data; Big player
  - ✅ Plenty of examples and tools to implement requirement #3
  - Airtable wins ([Notion vs Airtable](https://radreads.co/notion-airtable/))

## Implementation

- ✅ Requirement 1+2: **collect data in rows**, using [Airtable](https://airtable.com/)
  - Easy to create the base's structure, plenty of field types
  - Modern and friendly UI, a pleasure to use
- ✅ Requirement 3: **display data online**, using [Pory](https://pory.io/)
  - [Video guide](https://www.youtube.com/watch?v=qcFbfDDgkqM)
  - Pory links with Airtable via API key
  - Mapping from Airtable's base to Pory's UI blocks is straightforward
- ⚠️ Nice to have: How to **show categories and tags** as search filters?
  - First pain: far from "it just works" like until now, not intuitive at all
  - [I had to follow this official guide](https://pory.io/support/sites/filters)
  - ❌ You have to create another base and manually add the values you want as filters
        1. From your source base, select a Filter column, copy all values
        2. Copy-paste to a text editor (e.g. Sublime Text or VS Code)
        3. Replace all commas with new lines (e.g. multiple cursor)
        4. Use a command to filter only unique values and then sort them
        5. Copy the results to the Filter base
- ✅ Nice to have: How to **collect user submitted data** via form?
  - [Official guide](https://support.airtable.com/hc/en-us/articles/206058268-Guide-to-forms#create), easy to follow
        1. You take your existing base and create a "Form" view – neat!
        2. Customise which fields appear in the form
        3. And what happens when the user submits the form
  - The best way is to create a separate "User submissions" base
  - Then you moderate by cut-pasting the moderated submissions to the "Live" base
- ⚠️ Nice to have: How to track number of visitors?
  - Not possible to add Google Analytics in the free plan
