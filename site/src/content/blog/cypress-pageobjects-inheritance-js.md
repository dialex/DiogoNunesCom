---
title: "Cypress: PageObjects using inheritance"
description: "Maybe your page under test that behaves differently based on a config. You can use JS inheritance to avoid duplicated code on PageObjects with Cypress."
pubDate: "2020-06-01T07:00:00"
heroImage: "/blog/uploads/2020/04/68747470733a2f2f65726c616e67656e776c6164696d69722e66696c65732e776f726470726573732e636f6d2f323031352f30352f6d6174726a6f7363686b612d332e6a7067.jpeg"
tags: ["cypress", "testing", "tutorial"]
categories: ["Technology"]
---

> Scenario:
> 
> -   Your web site is deployed on several countries. The behaviour of the page you want to test (e.g. sign up) is mostly the same across countries, however some business rules change per country.
> -   You are using the PageObjects pattern to encapsulate the details of each page. You want to avoid duplicated code.

Our goal was to create a `SignUpTemplatePage.java` with the page behaviour that was common across all countries. Then one `SignUp<Country>Page.java` per country, with the specifics of each country, while inheriting the common behaviour from the template page.

It wasn't straightforward to achieve it with JavaScript and Cypress' async behaviour. Thanks to the help of a friendly developer, we did it:

### The test

```js
describe("Sign Up page", () => {
  it("creates account when fields are valid", function() {
    const userDataObject = {
      email: "...",
      password: "..."
    }

    // the E2E test doesn't care about implementation details
    const page = getSignupAgencyUserPage()
    page.visit()                        // env var will determine which country to test
    page.fillForm(userDataObject)       // this is different per country
    page.submitForm()                   // this is common
    page.shouldDisplayAccountCreated()  // this is common
  })
})
```

### The switch (returns the country PageObject)

```js
import { ConfigHelper } from "../utils/configHelper"
import { signupUserPageDE } from "./uk/SignupUserPageDE"
import { signupUserPageFR } from "./uk/SignupUserPageFR"
import { signupUserPagePT } from "./pt/SignupUserPagePT"
import { signupUserPageUK } from "./uk/SignupUserPageUK"

const implementations = {
  de: signupUserPageDE,
  fr: signupUserPageFR,
  pt: signupUserPagePT,
  uk: signupUserPageUK
}

export function getSignupUserPage() {
  const countryCode = ConfigHelper.getCountryCode() // reads an env var
  const page = implementations[countryCode]
  if (!page)
    throw new Error(`There's no PageObject implementation for the current site: ${countryCode}`)
  return page
}
```

### The template (contains what is common)

```js
import { ConfigHelper } from "../utils/configHelper";

// Selectors
const btnSubmit = "#registerSubmit";

export class SignupUserPageTemplate {
  constructor() {
    // even though we will never instantiate this class
  }

  // Actions
  visit() {
    cy.visit(routes.account.signUp);
  }

  submitForm() {
    cy.get(btnSubmit).click();
  }

  // Assertions
  shouldDisplayAccountCreated() {
    cy.url().should("include", routes.account.signUpSuccess);
  }
}

export const signupAgencyUserPageTemplate = new SignupAgencyUserPageTemplate();
```

### The country page (contains what is different)

```js
import { SignupUserPageTemplate } from "../SignupUserPageTemplate";

// Selectors
const errorInputMessage = "p .errorbox";
const fieldEmail = `input[name="register[email]"]`;
const fieldPhone = `input[name="register[default_phone]"]`;

export class SignupUserPagePT extends SignupUserPageTemplate {
  constructor() {
    super();
  }

  // Actions
  fillForm(account) {
    cy.get(fieldEmail).type(account.email);
    cy.get(fieldPhone).type(account.phone);
  }

  // Assertions
  shouldDisplayRequiredFieldsMessage() {
    const requiredFieldsLength = 2;
    cy.get(errorInputMessage).should("have.length", requiredFieldsLength);
  }
}

export const signupUserPagePT = new SignupUserPagePT();
```
