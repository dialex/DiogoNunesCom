---
title: "Cypress Sapling (automation template)"
description: "We chose Cypress to automate our E2E tests. I've extracted the best bits of our test repository into this cypress template. No need to start from scratch."
pubDate: "2020-05-18T07:00:00"
heroImage: "/blog/uploads/2020/04/tzingtao-chow-J8oxnYHBpWM-unsplash-scaled.jpg"
heroCaption: "Photo by Tzingtao Chow on Unsplash"
tags: ["cypress", "free and open", "testing", "utilities"]
categories: ["Technology"]
---

### No need to start with the seed – plant the sapling!

_Get it? Because "Cypress" is a tree..._

On my last project we chose Cypress to automate our E2E tests. During a year and a half we constantly tweaked and improved our test repository. We added **more functionary** on top of Cypress (using plugins) and **automated everyday tasks** (e.g. dependencies, Docker, static code analysis).

I've extracted the best bits of our test repo into this "cypress template repository", which I decided to call [Cypress Sapling](https://github.com/dialex/cypress-sapling).

Thanks to this repository, you no longer have to start from scratch if you want to create your own Cypress testing repository. You will get all this functionality for free:

-   Code linting, via [ESLint](https://github.com/eslint/eslint)
-   Code formatting, via [Prettier](https://github.com/prettier/prettier)
-   Conventional commits, via [git-cz](https://github.com/streamich/git-cz)
-   HTML test results, via [mochawesome](https://github.com/adamgruber/mochawesome)
-   JUnit test results (for CI integration), via [mocha-junit-reporter](https://github.com/michaelleeallen/mocha-junit-reporter)
-   Intuitive test commands, via [Testing Library](https://github.com/testing-library/cypress-testing-library)
-   Filter tests to run based on tags, via [cypress-select-tests](https://github.com/bahmutov/cypress-select-tests)
-   Auto retry failed tests, via [cypress-plugin-retries](https://github.com/Bkucera/cypress-plugin-retries)
-   Wait the test execution until a condition is true, via [cypress-wait-until](https://github.com/NoriSte/cypress-wait-until)
-   Docker commands to build an image ready to run tests anywhere

Even if you already have your own Cypress repository, you can have a look at this template to see what you might be missing and how you could implement it on your repository.

_P.S: If you want to learn more about the Cypress "ready-to-run" Docker image, I wrote a [dedicated post](https://www.diogonunes.com/blog/how-to-build-docker-image-cypress-tests/) about it._
