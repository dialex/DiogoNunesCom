---
title: "Lean contract testing with Chakram"
description: "Every time my team has to rely on an external API, one day that API fails. In this post I'll show you how you can write lean contract tests."
pubDate: "2022-10-17T08:00:52"
heroImage: "../../assets/blog/uploads/2022/10/cytonn-photography-n95VMLxqM2I-unsplash-1.jpg"
heroCaption: "Photo by Cytonn Photography on Unsplash"
tags: ["coding", "testing"]
categories: ["Technology"]
---

> ⭐️ This post was featured in [Software Testing Weekly #142](https://softwaretestingweekly.com/issues/142) and [CodingJab #111](https://www.lambdatest.com/newsletter/editions/issue111)

During my career, every time my team has to rely on a 3rd-party API, there comes the day where that API fails. Maybe it is down but usually they released a breaking change that breaks the previous contract. Our team does not notice it, but the client/user does and then we look bad.

Even though there was a problem in the backstage (provider API), our system is the one that faces the users. So there's a problem, we are the ones who get hit with the rotten vegetables, thrown by the angry audience.

## Test closer to the problem

There's multiple ways to reduce the risk of this ever happening.

You can test flows of your system, so that you can notice when something becomes broken. This approach will tell you there's a problem, but it's unlikely it will tell you where. That's the problem of _"Testing Through the UI"_, instead of _"Testing the API"_. If you want to read more about it, check [Say TaTTa to your TuTTu](https://www.mwtestconsultancy.co.uk/say-tatta-to-your-tuttu-talk/) by Mark Winteringham.

Since we want to detect a broken API, let's test as close as possible to that API. That's the interface or contract of the API. If you are curious, check [Integration tests are a scam](https://blog.thecodewhisperer.com/permalink/integrated-tests-are-a-scam) by J. B. Rainsberger.

We know how to call the API and we know what we expect to get in return, both in terms of **structure** and **data**. We want a test to fail when:

- One required field goes missing (structure), e.g. `bookAuthor` field is not returned.
- One required value changes (data), e.g. `publishedAt` value no longer follows [ISO 8601](https://en.wikipedia.org/wiki/ISO_8601).

## The right tool for the job

Now that we know what we want to achieve, it is time to pick a tool.

### Pact ❌

[Pact.io](https://docs.pact.io/) is probably the best tool for long-term and large scale contract testing. The learning curve is high and on top of that it requires infrastructure ([Pact Broker](https://docs.pact.io/getting_started/sharing_pacts)). Given we are just starting this would be overkill.

### Postman ❌

Most of our requests were already saved in [Postman](https://www.postman.com/) collections. We could leverage that and simply attach some tests to those requests, right? Just because you can, doesn't mean you should:

1. The test code was hard to edit in their UI (e.g. no formatting, no auto-complete)
2. The collection needs to be exported to a JSON in order to be executed by Newman
3. When the test failed due to a schema violation it was not clear what was wrong

### Chakram ✅

We decided to write Jest tests with [Chakram](https://dareid.github.io/chakram/) because:

1. The test code exists next to the code being tested (same repo)
2. It uses our current toolchain (e.g. Jest, VS Code, Prettier, etc.)
3. When a schema is violated we know exactly why (structure/type/value mismatch)

## Examples

This is the simplest test you can write, it just checks that the API is alive.

```js
import { expect, get, wait } from 'chakram';
import { PROVIDER_X_API_URL } from '../configs';

describe('Provider X', () => {
  it('should be online and healthy', () => {
    const response = get(PROVIDER_X_API_URL);
    expect(response).to.have.status(200);
    return wait();
  });
});
```

The code below goes a step further and examplifies how to build the URL under test, how to assert the structure and the data, even if the data is dynamic.

```js
import { expect, get, wait } from 'chakram';
import { PROVIDER_X_API_URL } from '../configs';

const expectedSchema = require('./contracts/books.schema.json');

describe('Provider X /books', () => {
  const endpointUrl = new URL('books', PROVIDER_X_API_URL);

  it('should exist', () => {
    const url = endpointUrl.href;
    const response = get(url);

    expect(response).to.have.status(200);
    return wait();
  });

  it('should reply with valid JSON schema', () => {
    // Example: some assertions will need a regex because actual values will be dynamic
    const regexPrice = /\d{1,3}\.\d{1,2}/; // up to 5 digits, separated by a dot

    // Example: how to use URL search parameters
    const searchParams = new URLSearchParams({
      language: 'PT',
      apikey: process.env.PROVIDER_X_API_KEY,
    });
    const bookIsbn = '9783161484100';
    // Example: how to build the URL under test
    const url = `${endpointUrl.href}/${bookIsbn}?${searchParams}`;

    const response = get(url);

    // Assert structure
    expect(response).to.have.status(200);
    expect(response).to.have.schema(expectedSchema);

    // Assert values
    // Example: how to assert fixed values
    expect(response).to.have.json('books[0].language', 'Portuguese');
    // Example: use the dot notation to get a specific JSON element
    expect(response).to.have.json('books[0].price', (value) => {
      // Example: how to assert a dynamic values (using a regular expression)
      expect(value).to.match(regexPrice);
    });
    return wait();
  });
});
```
