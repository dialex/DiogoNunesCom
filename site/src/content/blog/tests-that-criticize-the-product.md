---
title: "Testing for agile teams: Tests that review the product (#5)"
description: "This is a personal summary of a chapter from the book Agile Testing: A Practical Guide For Testers And Agile Teams. I'm sure you'll find it useful too."
pubDate: "2017-03-13T08:00:03"
heroImage: "/blog/uploads/2016/03/Agile-Testing-Book-Part-3.jpg"
tags: ["agile", "testing"]
categories: ["Work"]
---

_This post is part of the ["Testing for Agile Teams" series](http://www.diogonunes.com/blog/?s=Testing+for+Agile+Teams)_.

> All that's left is to possibly find some obscure or interesting bugs. And to avoid things like "that's what I said, but it’s not what I meant."

## Business-Facing Tests (Q3)

-   All about trying to **recreate actual experiences** of the end users.
-   This kind of testing relies on human **creativity, experience, and instinct**.
-   Consider [Soap Opera Testing](https://en.wikipedia.org/wiki/Scenario_testing) or scenario testing. Use it together with workflows to test the system end-to-end.
-   **Exploratory Testing** combines learning, test design, and execution. You test just a little more to see if the "done" stories are done to your (users') satisfaction.
-   Exploratory testing is about **looking for and following the "smells"**. You review and redirect a test into unexpected directions on the fly.
-   **Assign a time slot** for this type of testing and measure it. It's easy to off track and end up chasing a bug that might not be important.
-   To test usability create [personas](http://www.measuringu.com/blog/personas-ux.php). For some, your app just needs to do it right rather than intuitively.
-   Check out the competition, research how their apps work and compare.

## Technology-Facing Tests (Q4)

-   Not all projects are concerned about nonfunctional requirements, but it is a good idea to have a **checklist** to make sure you at least think about them once. And clients usually assume the dev team will just take care of issues like security, performance, etc.
-   **PSR testing** is answering "How fast?" (performance), "How long?" (stability), "How often?" (reliability), and "How much?" (scalability).
-   The sooner technology-facing tests are created, the cheaper it is to fix issues.
-   This should be done by **security** expert: static analysis tools, SQL injection, cross-site scripting, remote code inclusion.
-   To increase **maintainability** develop standards and guidelines and share code ownership. Avoid duplication. Refactor.
-   Test end-to-end functionality between two or more communicating systems to ensure **interoperability**. Stubs and mocks simulate the behavior of other systems. Test with multiple OS, Browsers, Servers, Hardware.
-   **Reliability** is about "How long will it run before it breaks?" (mean time to and between failures). Think about how your application will be used all day, every day, over a period of time. Run simulations. Recovery from power outages?
-   **Performance testing** identify bottlenecks in a system. **Load testing** evaluates the system's behavior as more and more users start using it at the same time.

> You will need **time to obtain the expertise** needed -- either by acquiring it through learning and practicing the skills or by bringing in outside help.

**Tools:** `Nessus`, `JUnitPerf`, `JMeter`, `JProfiler`, `JConsole`

* * *

_This post is a personal summary of a chapter from the book [Agile Testing: A Practical Guide For Testers And Agile Teams](http://www.amazon.co.uk/gp/product/0321534468/ref=as_li_tl?ie=UTF8&camp=1634&creative=6738&creativeASIN=0321534468&linkCode=as2&tag=dionun-21). I'm sure you'll find that book useful too._
