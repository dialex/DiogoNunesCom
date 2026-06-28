---
title: "Cypress: PageObjects vs AppActions"
description: "I use Selenium for most of my automated checks and PageObjects is a must. With Cypress they recommend AppActions. This is a benchmark between both patterns."
pubDate: "2020-03-02T07:00:11"
heroImage: "../../assets/blog/uploads/2020/02/jason-dent-JVD3XPqjLaQ-unsplash.jpg"
heroCaption: "Photo by Jason Dent on Unsplash"
tags: ["coding", "cypress", "review", "testing"]
categories: ["Technology"]
---

I use Selenium to write most of my automated checks, and the [PageObjects pattern](https://thefriendlytester.co.uk/2014/05/pageobject-pattern-why-how-and-more.html) is a must. My current team is using [Cypress](https://www.cypress.io/) and, to my surprise, this test framework recommends `AppActions` instead of `PageObjects`. So I decided to benchmark both patterns using the following criteria:

- Can it abstract page selectors?
- Can it abstract page actions?
- Is it easy to write and maintain those abstractions?
- Is it easy to write tests?

### A) PageObjects

A page looks like this...

**page.js**

```js
// Here you list the page selectors that you are currently using
const cssSearchBar = ".locationsContainer"
const cssSearchField = ".select2-dropdown .select2-search__field"
const cssSearchResults = ".select2-results__options"
const cssSearchResultRow = ".select2-results__option"
const cssFoundAdsTotal = ".offers-index > strong"

// Each "page object" is a function with the name of the page
export function searchBuyFlatPage() {

  // Read routes config files
  return cy.fixture(ConfigHelper.getRoutesPath()).then(routes => {
    return {

      // Each page action is another function
      visit() {
        cy.visit(routes.buy.listFlats)
      },

      // and so on...
      previewSearch(text) {
        cy.get(cssSearchBar).click()
        cy.get(cssSearchField).type(text)
        cy.get(cssSearchResults).should("be.visible")
      },

      getAutocompleteHints() {
        return cy.get(searchResults)
      }
    }
  })
}
```

A test looks like this...

**test.spec.js**

```js
import { searchBuyFlatPage } from "../../pages/searchBuyFlatPage"

let keywords
before(() => {
  cy.fixture(ConfigHelper.getKeywordsPath()).then(c => {
    keywords = c
  })
})

describe("Autocomplete", function() {
  it("displays results at different hierarchical levels", function() {

    // there's no page constructor, you just call a function with the name of the page, and then...
    searchBuyFlatPage().then(page => {

      // you use "page" to call actions
      page.visit()

      // and again, and so on
      page.previewSearch(keywords.locationWithMultipleHierarchy)

      page.getAutocompleteHints().should("contain", keywords.locationWithMultipleHierarchy)
      page.getAutocompleteHints().should("contain", `(${keywords.hierarchyLevel2})`)
      page.getAutocompleteHints().should("contain", `(${keywords.hierarchyLevel3})`)
    })
  })
})
```

* * *

### B) AppActions

An app action looks like this...

**commands.js**

```js
Cypress.Commands.add("searchFlatForBuy", searchTerm => {
  cy.log("searchFlatForBuy")

  const cssSearchBar = ".locationsContainer" // code duplication of selectors (A)
  const cssSearchField = ".select2-dropdown .select2-search__field"
  const cssAutocompleteHints = ".select2-results__options"

  cy.fixture(ConfigHelper.getRoutesPath()).then(routes => { // code duplication of fixture loading (B)
    cy.visit(routes.buy.listFlats)
  })
  cy.get(cssSearchBar).click()
  cy.get(cssSearchField).type(searchTerm)
  return cy.get(cssAutocompleteHints).as("result")
})

Cypress.Commands.add("searchFlatForBuyUsingTree", () => {
  cy.log("searchFlatForBuyUsingTree")

  const cssSearchBar = ".locationsContainer" // code duplication of selectors (A)
  const cssAutocompleteHintRow = ".select2-results__option"

  cy.fixture(ConfigHelper.getRoutesPath()).then(routes => { // code duplication of fixture loading (B)
    cy.visit(routes.buy.listFlats)
  })

  cy.fixture(ConfigHelper.getFixturesPath()).then(fixtures => {
    const hierarchyLevels = fixtures.locations.hierarchyLevels

    cy.get(cssSearchBar).click()
    for (let i = 0; i < hierarchyLevels; i++) {
      cy.get(cssAutocompleteHintRow)
        .eq(2)
        .click()
    }
  })
})
```

A test looks like this...

**test.spec.js**

```js
import { ConfigHelper } from "../../support/utils/configHelper"

let keywords
before(() => {
  cy.fixture(ConfigHelper.getKeywordsPath()).then(c => {
    keywords = c
  })
})

describe("Autocomplete", function() {
  it("displays results at different hierarchical levels", function() {
    // calls page action
    cy.searchFlatForBuy(keywords.locationWithMultipleHierarchy).as("autocompleteHints")
    // and then asserts
    cy.get("@autocompleteHints").should("be.visible")
    cy.get("@autocompleteHints").should("contain", keywords.locationWithMultipleHierarchy)
    cy.get("@autocompleteHints").should("contain", `(${keywords.hierarchyLevel2})`)
    cy.get("@autocompleteHints").should("contain", `(${keywords.hierarchyLevel3})`)
  })
})
```

### Conclusions

> Can it abstract page selectors?

- **PageObjects**
    - ✅ Encapsulated and reused inside each PageObject
- **AppActions**
    - ❌ Either duplicated selectors on each AppAction or long enumeration on `commands.js`

> Can it abstract page actions?

- **PageObjects**
    - ✅ Encapsulated inside each PageObject
    - ✅ Intuitive usage: `homepage.searchAds("Lisbon")`
- **AppActions**
    - ✅ Encapsulated inside each AppAction
    - ⚠️ Not so intuitive usage: `cy.searchAds("Lisbon")` → everything is `cy.*`

> Is it easy to maintain pages?

- **PageObjects**
    - ✅ Each page has a single file, named accordingly
    - ⚠️ Some UI changes will fail tests until the affected PageObjects are updated
- **AppActions**
    - ❌ Pages are used ad hoc inside actions; you might need to "Find/Replace" changes to a page
    - ❌ Fixtures load is duplicated on each command

> Is it easy to write tests?

- **PageObjects**
    - ✅ IDE will autocomplete page actions
    - ✅ If pages and their actions are modular enough, tests are quite easy to write and understand
- **AppActions**
    - ❌ o IDE autocomplete, you need to skim the existing custom `commands.js` and decide which one works for you
    - ❌ There might be a tendency to reinvent the wheel, because actions are blackboxes of functionality. Some devs might breakdown that functionality differently, which might lead to slightly diff duplicates of a single AppAction.
    - ⚠️ This syntax is more oriented for E2E, if you use it to write UI tests you will have a hard time – since you only care about user actions and not the underlying pages.

#### Notes

- IDE Autocomplete issue
    - [This dependency](https://github.com/cypress-io/add-cypress-custom-command-in-typescript) did make IDE autocomplete work for custom commands
    - Based on [this comment](https://github.com/cypress-io/cypress/issues/2293#issuecomment-412034813) it seems like we need to write commands in TypeScript to have autocompletion
- Selector issue
    - You can extract all selectors to a single `selectors.js` file and then... `import {searchBar} from './common-selectors'` ([source](https://github.com/cypress-io/testing-workshop-cypress/blob/master/slides/03-selector-playground/PITCHME.md#cypress-is-just-javascript))
