---
title: "Testing for agile teams: The four testing quadrants (#3)"
description: "This is a personal summary of a chapter from the book Agile Testing: A Practical Guide For Testers And Agile Teams. I'm sure you'll find it useful too."
pubDate: "2017-01-16T08:00:00"
heroImage: "/blog/uploads/2016/03/Agile-Testing-Book-Part-4.jpg"
tags: ["agile", "testing"]
categories: ["Work"]
---

_This post is part of the ["Testing for Agile Teams" series](/blog/?s=Testing+for+Agile+Teams)_.

> We do different types of testing to accomplish different goals.

[![testing quadrants](/blog/uploads/2016/04/testing-quadrants-400x324.png)](/blog/uploads/2016/04/testing-quadrants.png)

> Your team has its own unique context. **It's a tool, not a rule.**

## Tests that support the team

- Supports the team as it develops the product:
    - Drives development to deliver the requested business value.
    - Safety net to prevent silent regressions.

**Quadrant 1**

- Test-Driven Development
- **Units tests** verify functionality of a small subset of the system (object/method).
- **Component tests** verify a larger part, as a group of classes that provide a service.
- These tests are automated and written in the same programming language as the application (dev team only).

**Quadrant 2**

- Define external quality and the features the customers want.
- Drive dev team at a higher level.
- Tests are derived from examples, provided by the customer team.
- Each test verifies a business satisfaction condiction (readable by the customer team).

## Tests that ~critique~ review the product

**Quadrant 3**

- Even if Q1 and Q2 are OK...
- Testers use their **senses and intuition** to check if the team has delivered the value required, "what the customer really wants".
- Users and customers perform these tests, in order to approve finished stories.
- Gathering their reactions an knowledge of how they use the systems is an advantage.
- In exploratory testing, the tester simultaneously designs and performs tests.
- It's not _ad hoc_ testing, it's guided by a strategy, creativity and intuition with defined constraints.
- From the start, testers start thinking of scenarios they want to try, as end users will.

**Quadrant 4**

- For some cases **they might be more important** than actual functionality.
    - _Example: if application performance is critical, plan to test with production-level loads as soon as testable code is available._
- Make sure all necessary **testing is done at the right time**.
    - _Example: if you find out too late that your design doesn’t scale, start load testing earlier next time._
- This quadrant provides feedback to the left side of the matrix.
- Use lightweight tools to create test data and set up scenarios.

* * *

_This post is a personal summary of a chapter from the book [Agile Testing: A Practical Guide For Testers And Agile Teams](http://www.amazon.co.uk/gp/product/0321534468/ref=as_li_tl?ie=UTF8&camp=1634&creative=6738&creativeASIN=0321534468&linkCode=as2&tag=dionun-21). I'm sure you'll find that book useful too._
