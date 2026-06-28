---
title: "Framework review: Maestro"
description: "This is my review of Maestro, a mobile test automation framework. It has its strengths and a lot of potential, but it's not yet ready for professional use."
pubDate: "2023-05-15T08:00:27"
heroImage: "/blog/uploads/2023/04/ConductorEimearNoonepoisedtomakehistoryatthe2020Oscars.jpg"
heroCaption: "Conductor Eimear Noone"
tags: ["free and open", "maestro", "mobile dev", "review", "testing"]
categories: ["Technology"]
---

> ⭐️ This post was featured in [Software Testing Weekly #169](https://softwaretestingweekly.com/issues/169)

A friend ([Hugo](https://medium.com/@hugocf)) told me about this new mobile test automation framework called [Maestro](https://maestro.mobile.dev/). At the time, my team was using Appium to test a React Native app, that ran on iOS and Android. I was not 100% happy with Appium, so I gave Maestro a try.

### Maestro commands

```sh
# install
brew tap mobile-dev-inc/tap
brew install maestro

# install iOS support
brew tap facebook/fb
brew install facebook/fb/idb-companion
xcrun simctl list           # UDID of available iOS devices
idb_companion --boot {udid} # boots one of those devices

# run
npm run start
npm run e2e:maestro
```

### Maestro code

`package.json`

```
"e2e:maestro": "for i in test-e2e/maestro/*.yml; do maestro test $i; done",
"e2e:maestro:ios": "maestro test test-e2e/maestro/ios/open-app.test.yml",
```

`open-app.test.yaml`

```yaml
appId: com.company.app
---
- clearState
- launchApp

# should render welcome page
- assertVisible: 'The future is here.'
- assertVisible: '.*Terms of Service.*'

# should support login with email
- tapOn: 'Get started'
- inputText: 'someone@company.com'
- hideKeyboard
- tapOn: 'Next'

# should ask for 2FA
- assertVisible: 'Verification code'
- assertVisible: '.*Resend code.*'
```

### My opinion

While I experimented with the framework I discovered a few behaviours:

- Text assertions are case sensitive
- Test stops on first failed action/assertion
- Each file is one test, executed top to bottom
- When running a folder instead of a specific test file the output does not show the results of each test step ([requested](https://github.com/mobile-dev-inc/maestro/issues/507))

#### Pros

- Twice as fast (12.21s) as Appium (24.24s) to boot the app and get to the screen
- Console is not spammed with useless logs
- You have a way to easily reset the state of the app (`clearState`)
- It actually runs [tests on iOS simulators](https://github.com/mobile-dev-inc/maestro/blob/b367a2c1110cebc618a1f7889859120f235193f8/maestro-ios/README.md#ios-device-config) (not real devices)

#### Cons

- Only supports simple actions (eg. things a user would do) -- i.e. can't write complex logic with YAML (eg. extract and fill 2FA code)
- You know a step failed but you don't know why
- Harder to avoid DRY (unless there's a way to define constants in one place)
- You don't get a screenshot when a test fails ([requested](https://github.com/mobile-dev-inc/maestro/issues/237))
- You can't partially match text, unless you use a regex ([requested](https://github.com/mobile-dev-inc/maestro/issues/35))
- Matching text with a regex don't work on iOS (but does on Android)

#### Conclusion

I think it has potential. I think the speed and ease of use (to get started) are its strongest selling points. It's good to get started with mobile testing.

However, it's not mature at all for a professional use, as basic and critical features are not yet available. Also, while `yaml` is great for non-developers to write tests, it becomes very limiting if you need to automate complex logic.
