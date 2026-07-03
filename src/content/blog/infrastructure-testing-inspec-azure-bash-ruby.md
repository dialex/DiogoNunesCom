---
title: "Infrastructure testing (Ruby): InSpec + Azure"
description: "My experience with infrastructure testing, using InSpec, tests written in Ruby, asserting bash scripts, and deployments to Azure."
pubDate: "2020-09-07T07:00:00"
heroImage: "../../assets/blog/uploads/2020/04/jakub-kriz-arOyDPUAJzc-unsplash-scaled.jpg"
tags: ["azure", "devops", "review", "ruby", "testing"]
categories: ["Technology"]
---

## Infra testing is mostly uncharted territory

I once worked in a project that was all about DevOps, pipelines and bash scripts. At the time we didn't find many guides, so we developed our own test strategy.

## Context

The project's goal was to create a collection of bash scripts that would spin up and environment, configure it, and deploy some applications into that same environment. At the end of the project we introduced Kubernetes to streamline most of this behaviour.

## Test strategy

### Unit level: Check scripts

We unit tested the **contract** of our bash functions. For example, if the number of arguments changed, either because we changed the function or because we forgot to pass them during the invocation, we would get a test failure or a runtime error, respectively.

The **behaviour** of our functions was harder to test. Most of them, were just proxies to an external dependency (Azure) – e.g. they would receive some parameters, build an azure command, and execute it. To workaround it, we developed a way to mock calls to external dependencies (e.g. we would intercept the call to Azure, write the command that was being sent to Azure to a file, and then assert the syntax/content of that command).

We treated external dependencies as black boxes, so we coded/tested under the assumption that if we sent the right command then the external dependency would behave as expected.

At the time we used **[Chef's InSpec](https://github.com/inspec/inspec) test framework** and wrote all these tests in Ruby.

### Acceptance level: Check infrastructure

The purpose of our pipeline was to use our bash functions to provision an environment (kubernetes, pods, resources).

So at the end of our pipeline we had an extra step to ran our provisioning or acceptance tests. These checks simulated what a DevOps would do at the end of the pipeline, by confirming that the expected resources were all up and running.

We also used InSpec and Ruby for coherence.

## Final thoughts

The implementation of this test strategy was a collaboration between developers and testers. The team was very satisfied with the final result, specially with the acceptance tests. These tests acted like a health check, we could on demand target a specific resource group, run the acceptance tests, and get a health/correctness test report. This improved our confidence and saved us debug time.

If you want to read more about infrastructure testing, I recommend Katrina's ["A Practical Guide to Testing in DevOps"](https://leanpub.com/testingindevops) book.
