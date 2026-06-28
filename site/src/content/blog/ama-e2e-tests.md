---
title: "7 questions we all had about end-to-end tests"
description: "This is a summary of an Ask Me Anything session I did at my company. Developers voted what they wanted to know about (E2E) end-to-end tests and I shared my experience."
pubDate: "2021-10-04T08:00:33"
heroImage: "/blog/uploads/2021/09/Knowledge-Sharing-E2E-tests-1.jpg"
tags: ["experience", "testing"]
categories: ["Technology"]
---

### The summary of an Ask Me Anything session I hosted at my company

> This post was **featured** in [Software Testing Weekly, #92](https://softwaretestingweekly.com/issues/92?#automation) and [Software Testing Notes, #28](https://softwaretestingnotes.substack.com/p/issue-28-software-testing-notes)

![](/blog/uploads/2021/09/Knowledge-Sharing-E2E-tests-0.jpg)

### What’s the definition of E2E in frontend?

I don't think there's a definition specific to frontend (BE). An end-to-end test interacts with your system as a user would, they are not aware of front or backend boundaries.

An E2E exercises your system in terms of "width" and "depth". These tests exercises the **width** of your system by checking the behaviour of a user journey from start to end, across multiple pages. While they do so, they are also exercising the **depth** of your system by checking all the tech layers, across frontend and backend.

![](/blog/uploads/2021/09/Knowledge-Sharing-E2E-tests-1.jpg)

### How does a test survive the “test of time”?

First we need to define what the test of time is. In my opinion, that's an automated check that remains _reliable_ and _relevant_ through time. To achieve that, I suggest three guidelines:

- **Assert what is critical.** For example, core business rarely changes. If you focus your assertions on that type of business logic it is very likely that your test will remain relevant.
- **Assert what is final.** For example, features under A/B testing is not final. While the experiment is running, the feature might change (which would make the test unreliable) or even be dropped (which would make the test irrelevant). Save your automation efforts until a decision is made.
- **Assert what is stable.** If your work was climbing a mountain, you would choose carefully where to place your supports. You would pick the places that are solid. Tests support your work, and selectors support your tests. If you use selectors that are not tied to your design implementation (e.g. classes via CSS selectors) they will be more reliable.

![](/blog/uploads/2021/09/Knowledge-Sharing-E2E-tests-2.jpg)

### How to prevent test flakiness?

One way is build tests on stable ground and for that you should follow the guidelines from the previous question.

The other guideline – one you should always keep in mind – is that tests exist to give you confidence. Any time you detect flakiness, investigate the cause and fix it. If that's not possible, then you can't trust the test. **Unreliable tests have no purpose, delete them without mercy.**

I would also stay away from **mocking** as a solution to flakiness. A property of E2E tests is that they are realistic, they are an automated user. If the test is facing a problem then a user might face it too. If you mock your E2E test then you transform it into a slow bloated unit test.

![](/blog/uploads/2021/09/Knowledge-Sharing-E2E-tests-3.jpg)

### How to prevent them from slowing down pipelines?

Each new test adds a handful of seconds to your development process. It's a trade-off between confidence and speed. It's not a one size fits all – your team needs to agree their balance.

Think of it as crossing a bridge, you can cross it slowly and carefully or you can make a run for it. Maybe it's a short bridge. Maybe you are used to cross bridges. Maybe you will only cross it once. Context, context, context.

[Marie Kondo](https://en.wikipedia.org/wiki/Marie_Kondo) has a question for you: _Does your pipeline spark joy?_ That should be your guiding light, is the pipeline helping or hurting me? Optimise accordingly.

![](/blog/uploads/2021/09/Knowledge-Sharing-E2E-tests-4.jpg)

### How do you run them in your pipeline?

My team owns several code repositories with individual pipelines. Therefore we need the ability to trigger E2E tests from any pipeline. That's why we created a separate repo, one that builds a docker image on each push to main.

When we want to run the E2E tests we simply pull the image and run it. That's it (assuming you pass all the necessary secrets and configurations as env vars parameters).

![](/blog/uploads/2021/09/Knowledge-Sharing-E2E-tests-5.jpg)

### How do you structure test code?

Just like with code styling rules, let your team agree on it. There's no right way but... some rules have consequences. For instance, if you have one assertion per test, then you will have a lot more tests to cover the same system behaviour. And you know that each test adds (execution/maintenance) time.

A test suite with _too many files/lines_ is harder to maintain. A test suite that takes _too long_ to run is executed fewer times. So I would argue that the way you structure your test code should avoid those two pitfalls.

I start with the user goal, what they want to achieve in our system. Then I map their journey, the actions they will need to perform and which screens they will use. Finally I code the E2E test, asserting along the way, just like a user would.

![](/blog/uploads/2021/09/Knowledge-Sharing-E2E-tests-6.jpg)

### How to cleanup the existing test mess using E2E?

The E2E tests are not a perfect tool that replaces everything. These tests can be messy too. We already know they are _slow_ in comparison to other types of tests. Since they are realistic, their correct execution is _highly dependent_ on your system's state – e.g. if the login feature is down, you might have all your E2E tests failing. Another consequence of their realism is that they _create and update data_, changing your system's state.

Remember why you wanted E2E tests in the first place and keep in mind the testing pyramid. Every type of test has a place and a desirable quantity.
