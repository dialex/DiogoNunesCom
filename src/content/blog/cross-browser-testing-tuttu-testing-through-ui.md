---
title: "Before doing cross-browser testing ask yourself: are you TuTTu?"
description: "I had automated checks in Java+Selenium, ready for cross-browser testing. I should have asked myself: am I Testing the UI or Testing Through the UI (TuTTu)?"
pubDate: "2016-07-18T08:00:00"
heroImage: "../../assets/blog/uploads/2016/07/cross-browser-header.png"
tags: ["heuristics", "testing"]
categories: ["Work"]
---

Recently I had the requirement to test the behavior of a web application on Chrome, Firefox... and the dreaded Internet Explorer. The team already had automated end-to-end test written in Java using Selenium web driver.

We decided to explore the world of automated cross-browser testing and we were recommended BrowserStack and SauceLabs.

## You could have just asked...

But our endeavor was doomed to fail because **we were following an anti-pattern**. According to the [TuTTu mnemonic](http://www.mwtestconsultancy.co.uk/cross-browser-checking-anti-pattern/) coined by [Mark Winteringham](https://twitter.com/2bittester) we should ask ourselves whether we are _Testing the UI or Testing Through the UI_.

Our end-to-end tests were testing through the UI and that's something not browser dependent. Any decent browser (even IE) should process JavaScript code the same way -- according to spec. Testing the UI is what really matters on cross-browser testing. In fact, our exploratory testing found out the web app's logic was right across the different browsers (thanks to Babel) but we had UI inconsistencies. Our end-to-end tests were not designed to catch those UI issues.

In a sentence, I guess we were using the wrong tool for the wrong job. [TuTTu](http://www.mwtestconsultancy.co.uk/cross-browser-checking-anti-pattern/) #NeverForget
