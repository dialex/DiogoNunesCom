---
title: "Cross-browser testing with BrowserStack: a review"
description: "I had to test a web app on Chrome, Firefox and IE and I already had the tests in Java using Selenium web driver. So I tried BrowserStack... be careful."
pubDate: "2016-08-15T11:00:10"
heroImage: "/blog/uploads/2016/07/browserstack-logo.png"
tags: ["review", "testing"]
categories: ["Technology"]
---

These are BrowserStack's main services or tools:

-   **Automate**: Where you run your automated Selenium tests and check the results.
-   **Screenshots**: Paste an URL, select the browsers and version you want, and in a few minutes you get a batch of screenshots.
-   **Live**: by connecting to their data center you are able to do exploratory testing of your web app on the environment you need without having to worry about virtual machines.

Recently I had the requirement to test the behavior of a web application on Chrome, Firefox... and the dreaded Internet Explorer. The team already had automated end-to-end test written in Java using Selenium web driver.

We decided to explore the world of automated cross-browser testing and we were recommended BrowserStack and SauceLabs. Both have trial periods, though [BrowserStack](https://www.browserstack.com/) is cheaper and it seemed simpler to me. So a few minutes after signing up for the trial, I get an email from their support asking if I needed any help setting up my integration with BrowserStack. I must say their support is five stars.

## How Automate works

On your code you replace your `WebDriver` with a `RemoteWebDriver`. That way, when you run your tests, BrowserStack's remote browser is listening for events and executing the same tests.

You are not forced to publicly expose your web app since they allow you to test locally. You just need to change a flag on the `RemoteWebDriver`'s capabilities and have [their executable](https://www.browserstack.com/local-testing#command-line) running on your local machine, which maintains a secure connection with their data center. All this is explained on their [documentation](https://www.browserstack.com/automate/java), which is clear and contains several examples.

![browserstack-automate-example](/blog/uploads/2016/06/browserstack-automate-example.png)

## And how it doesn't work

In the end, we gave up on BrowserStack and automated cross-browser testing for a number of reasons:

-   We were spending more time on setting up the automation than the time required to manually run the tests until the end of the project (about 35 days). That time could be used more effectively on exploratory testing and developing new end-to-end tests.
-   **Remote tests failing locally but not on BrowserStack.** Remember when I said the tests run both locally and on their remote browser? Now listen to this: when a test fails locally it doesn't (necessarily) fail remotely. I explicitly created an assert that would always fails like `assertThat(true, is(false))` and it would fail locally and pass remotely. Weird! I had to use an [API workaround to mark tests as failed](http://stackoverflow.com/a/35102092/675577), but the point is: I want to use the Automate service to discover failing asserts and broken tests, if it passes on BrowserStack's side then I question what's really being tested by Automate...
-   **Failed integration with Jenkins.** I installed their Jenkins plugin and the environment variables were correctly configured. The builds were running successfully, however Automate was not being hit and I couldn't figure out why. I even manually started their `BrowserStackLocal.exe` to no avail.
-   **Failed configuration of Jenkins reports.** Their plugin's installation pdf contained some Maven code snippets but our team use Gradle, so dead end right there. I tried to configure the post-build action as the pdf instructed but I had issues with the reports path.

## YMMV

If you want to have a go with it, you can [check this post](http://www.diogonunes.com/blog/how-to-use-browserstack-cross-browser-testing) for a real code example.
