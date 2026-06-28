---
title: "Asserting text using Cypress"
description: "With Cypress eventually you will have to assert text. They provide at least three methods: have.text, include.text and contain.text. What's the difference?"
pubDate: "2020-07-06T07:00:00"
heroImage: "/blog/uploads/2020/04/sunyu-jtjS4F8Q7sY-unsplash-scaled.jpg"
heroCaption: "Photo by Sunyu on Unsplash"
tags: ["cypress", "testing"]
categories: ["Technology"]
---

If you're using [Cypress](https://www.cypress.io/), eventually you will have to assert some text. However, they provide at least three methods to do that, and from the documentation is not clear the difference between:

- `.should("have.text", "expected text goes here")`
- `.should("include.text", "expected text goes here")`
- `.should("contain.text", "expected text goes here")`

Do they all accept text? Some accept regex? Exact match or substring?

This is a summary of the [answer](https://github.com/cypress-io/cypress/issues/5996) I received:

- `have.text`: exact text match
- `include(s).text`: substring match
- `contain(s).text`: substring match

Let's see it in action with some examples. Assume we want to assert the heading of a webpage. When you select that element with `cy.get("h1")` you get the text "This is a heading". So if you do:

- `.should("have.text", "This is a heading")` passes ✅
- `.should("have.text", "heading")` fails ❌
- `.should("include.text", "This is a heading")` passes ✅
- `.should("include.text", "heading")` passes ✅
- `"contain.text"` is as alias for `"include.text"`, so expect the same results
