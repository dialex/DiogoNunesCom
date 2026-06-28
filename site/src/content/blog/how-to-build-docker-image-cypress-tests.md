---
title: "How to build a Docker image ready to run Cypress tests"
description: "Here you will find a dockerfile that will allow you to build a Docker image containing Cypress, your tests and any dependencies -- ready to run!"
pubDate: "2020-05-11T07:00:00"
heroImage: "/blog/uploads/2020/04/cypress-on-docker.png"
tags: ["coding", "cypress", "tutorial"]
categories: ["Technology"]
---

[](https://www.diogonunes.com/blog/wp-content/uploads/2020/04/cypress-on-docker.png)

My team decided to build a Docker image that contained Cypress, dependencies and all our end-to-end (E2E) tests.

That way, anyone could simply pull the image and with a single command it was ready to run tests. Also, the developers and the CI pipeline we both using the same image, which made our test results more coherent and easier to reproduce.

This was our final Dockerfile:

```dockerfile
# use the official Cypress image as base (keep this up to date)
FROM cypress/browsers:node13.6.0-chrome80-ff72

RUN useradd -ms /bin/bash cypress
RUN install -d -m 0755 -o cypress -g cypress /app
RUN chown cypress:cypress /app
WORKDIR /app

ENV CYPRESS_CACHE_FOLDER="/app/.cypress"
ENV CI=true # reduce Cypress logging

USER cypress

# install dependencies
COPY package.json yarn.lock ./
RUN yarn install --frozen-lockfile
# confirm that Cypress was installed correctly
RUN npx cypress verify

# copy files required by yarn tasks
COPY .eslintrc .prettierrc.yaml cypress.json ./
# copy reporter config, used to output JUnit XML
COPY reporter.config.json ./
# copy tests
COPY cypress cypress
```

After a whole year of writing and maintaining Cypress tests, I learned a lot and added several features on top of the default Cypress behaviour.

So if you want to start writing Cypress tests, you don't have to start from scratch like I did: check this [template repo](https://github.com/dialex/cypress-sapling).
