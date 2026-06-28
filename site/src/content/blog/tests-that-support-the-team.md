---
title: "Testing for agile teams: Tests that support the team (#4)"
description: "This is a personal summary of a chapter from the book Agile Testing: A Practical Guide For Testers And Agile Teams. I'm sure you'll find it useful too."
pubDate: "2017-02-13T08:01:01"
heroImage: "/blog/uploads/2016/03/Agile-Testing-Book-Part-5.jpg"
tags: ["agile", "testing"]
categories: ["Work"]
---

_This post is part of the ["Testing for Agile Teams" series](/blog/?s=Testing+for+Agile+Teams)_.

## Technology-Facing Tests (Q1)

- Strive to **make time to test** more complex scenarios and edge cases.
- **TDD** means writing a test that captures the behavior of a tiny bit of code and then working the code until the test passes.
- Programmers make sure at least one path (happy-path) works end-to-end.
- When a bug is found, **a unit test is created to reproduce the bug** and the code is fixed.
- Each unit test is independent and tests one dimension at a time. The business-facing tests (Q2) rarely cover only one dimension, since they test more complex user scenarios.
- **Push tests to lower levels** whenever possible (simpler, quicker, +ROI).

> Example: The unit test (Q1) would check the calculation of the date, and the business-facing (Q2) test would verify that it displays correctly in the borrower’s loan report.

## Business-Facing Tests (Q2)

> Q1 tests help programmers make sure they have written the code right. How do they know the right thing to build?

- **Stories** don't provide that much information. They're a brief description of desired functionality and an aid to planning and prioritization.
- Stories have a **level of detail** that will let programmers start writing working code almost immediately. The remaining details come from examples and tests, that will confirm what the customer really wants.
- A story is just a sentence about **who** wants the feature, **why** and **what** the feature is -- a starting point for an ongoing dialogue.

> Requirement = Story + Example + Test + Conversation

- The tests need to be written in a **language** that’s comprehensible to a business user reading them yet still executable by the technical team (e.g. [easyb](http://easyb.org/)).
- Clarify requirements by asking these questions to your customer:
    - What value will users get from this feature?
    - What will they do immediately before and after using that feature?
    - How do we know we're done with this story?
- A **Product Owner** guarantees only “one voice of the customer” presented to the developer team.
- It's easy to lose track of the **big picture** when we’re focusing on a small number of stories. Always consider how each individual story impacts other parts of the system (ripple effects).
- The sooner you can build the end-to-end path, the sooner you can do meaningful testing. Find the **most stripped-down functionality that can be tested** (i.e. steel thread, tracer bullet, critical path).
- The **true test** is whether the software’s user can perform the action the story was supposed to provide. The users or product owners are the right people to determine whether the value/requirement has been delivered.
- Test **edge cases** that have a bad outcome & good probability of happening. Discuss potential impacts and risky areas with programmers.

This is the recommended order for tests' creation:

1.  High-level story tests before coding;
2.  Detailed test cases once coding starts;
3.  Exploratory testing on the code as it’s delivered.

* * *

_This post is a personal summary of a chapter from the book [Agile Testing: A Practical Guide For Testers And Agile Teams](http://www.amazon.co.uk/gp/product/0321534468/ref=as_li_tl?ie=UTF8&camp=1634&creative=6738&creativeASIN=0321534468&linkCode=as2&tag=dionun-21). I'm sure you'll find that book useful too._
