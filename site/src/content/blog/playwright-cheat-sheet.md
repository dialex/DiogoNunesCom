---
title: "Playwright cheat sheet"
description: "A list of code snippets and examples to implement common automation scenarios in Playwright"
pubDate: "2022-02-07T07:00:58"
heroImage: "../../assets/blog/uploads/2021/11/patrick-tomasso-Oaqk7qqNh_c-unsplash.jpg"
heroCaption: "Photo by Patrick Tomasso on Unsplash"
tags: ["playwright", "testing", "utilities"]
categories: ["Technology"]
---

> ⭐️ This post was featured in [Software Testing Weekly #110](https://softwaretestingweekly.com/issues/110) and [Coding JAG #76](https://www.lambdatest.com/newsletter/editions/issue76)

My first experience with Playwright was terrible. However the testing community seems to be loving it, thus I gave it another shot. I started by doing a [free course](https://testautomationu.applitools.com/js-playwright-tutorial/), but I don't recommend it, it's very outdated by now.

I know you loved my [Cypress recipes post](/blog/cypress-tips-tricks/), so here's a new one for Playwright with up-to-date code snippets on how to implement common automation scenarios.

## ⚙️ Setup

### Test skeleton (Test version)

```js
const { expect, test } = require("@playwright/test");

test.describe("Google search", () => {
  test("is online", async ({ page }) => {
    await page.goto("https://google.com/");
    await expect(page).not.toBeNull();
  });
});
```

### Test skeleton (Library version)

```js
const { chromium } = require("playwright");

(async () => {
    const browser = await chromium.launch({ headless: false, slowMo: 100 }); // options
    const page = await browser.newPage();
    // do stuff
    await browser.close();
})();
```

## ⚡️ Actions

### Visit URL

```js
await page.goto("https://google.com");
```

### Select page element

```js
// locate based on user visible text
const emailInput = await page.locator("text=Action was successful");
// locate based on CSS selector
const emailInput = await page.locator("#cssSelector > goes here");
```

### Count selected elements

```js
const matches = await page.locator("#cssSelector > goes here");
const total = matches.count();
const firstMatch = matches.first();
const thirdMatch = matches.nth(2);  // index is 0-based
const lastMatch = matches.last();
```

### Type text

```js
const emailInput = ...;
await emailInput.fill("Text to type goes here");
// or inline
await page.locator("#cssSelector").fill("Fake user will type this");

// send keys to the page, regardless what is currently focused
await page.keyboard.type("pressing some KEYS!");
// send key combinations (e.g. shortcuts)
await page.keyboard.press('Ctrl+C');
// press and hold a key (e.g. select something)
await page.keyboard.down('Shift');
for (let i = 0; i < 'KEYS!'.length; i++)
  await page.keyboard.press('ArrowLeft');
await page.keyboard.up('Shift');
```

### Click element

```js
await page.locator("#cssSelector").click();
```

### Dropdowns

```js
await page.selectOption("#css", "blue" );                   // by internal value
await page.selectOption("#css", { label: "Blue ink" });     // by visible label
await page.selectOption("#css", { index: 1 });              // by 0-based index
await page.selectOption("#css", ["red", "blue"]);           // multi select
```

### Checkboxes and Radios

```js
await page.check('#css');
await page.uncheck('#css');
await page.check('text=XL'); // check radio

expect(await page.isChecked('#css')).toBeTruthy(); // assert state
```

### iFrames

```js
const iframeCheckoutSanddox = "#checkout-demo";
const sectionPaymentSummary = "#ProductSummary-totalAmount";
const inputEmail = "#email";

await page.goto("https://checkout.stripe.dev/preview");

// select the iframe
const stripeFrame = page.frameLocator(iframeCheckoutSanddox);
await expect(stripeFrame).not.toBeNull();

// assert elements withing the iframe
await expect(stripeFrame.locator(sectionPaymentSummary)).toBeVisible();

// interact with elements withing the iframe
await frame.locator(inputEmail).fill("someone@gmail.com");
```

### Alerts / Dialog popups

```js
/* TODO: Update this code, I think it's outdated by now
  // code to listen for a dialog popup
  page.once("dialog", async (dialog) => {
      console.log(dialog.message()); // confirm msg seen by user
      await dialog.accept(); // close dialog
  });
  // actually trigger the dialog
  await page.click("#confirmButton");

  // using ".once" instead of ".on" allows multiple listeners in the same test
  page.once("dialog", async (dialog) => {
      await dialog.accept("Hello input!"); // to input something in a dialog
  });
  await page.click("#promptButton");
*/
```

### Check status of element

```js
await page.locator("#cssSelector").isVisible();
```

P.S: [list with all the assertions available](https://playwright.dev/docs/actionability#assertions)

## ✅ Assertions

### Check the page title

```js
await expect(page).toHaveTitle("The Geeky Gecko - The most amazing blog");
```

### Check something exists in the page

```js
await expect(page.locator("#cssSelector")).toBeVisible();
```

## Miscellaneous

### Take a screenshot

```js
await page.screenshot({path: "path/to/generated_file.png"});          // page above the fold
await page.screenshot({path: "path/to/file.png", fullPage: true});    // entire page
await page.locator("#css").screenshot({path: "path/to/file.png"});    // just an element
```

### Record a video

[Check the official docs](https://playwright.dev/docs/api/class-video#video-save-as)

### Emulate a mobile device

- [Check the official docs](https://playwright.dev/docs/cli#emulate-devices)
- P.S: [list of supported devices](https://github.com/microsoft/playwright/blob/master/packages/playwright-core/src/server/deviceDescriptorsSource.json)
