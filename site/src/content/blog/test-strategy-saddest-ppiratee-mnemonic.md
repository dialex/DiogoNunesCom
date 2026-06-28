---
title: "Creating a test strategy using questions"
description: "The test strategy reveals how tests will be designed and executed to foster quality. To do so, it needs to answer several questions. SADDEST PPIRATEE is the mnemonic."
pubDate: "2020-12-07T07:00:00"
heroImage: "/blog/uploads/2020/05/SadPirate1.jpg"
tags: ["testing", "utilities"]
categories: ["Technology"]
---

### He doesn't have a test strategy ☝️

> ⭐️ This post was featured in [Software Testing Weekly, issue 49](https://softwaretestingweekly.com/issues/49)

The test strategy is defined at the start of the project and it can be revisited and reviewed during the duration of that project. The strategy is usually driven by the testers, yet it should always include feedback from all team members.

This strategy reveals the way tests will be designed and executed to foster quality. To do so, it needs to answer **several questions** like what parts of the product will be tested, what test techniques will be used, who will be involved, and how long it will take.

The number of questions and the detail of the answers depends on your project's [requirements](https://dialex.github.io/start-testing/#/concepts/requirements), so you will have to choose between a formal and detailed document versus a simple and abridged alternative.

### Mnemonic `SADDEST PPIRATEE`

[Erik Brickarp](http://erik.brickarp.se/2016/11/test-plan-questions.html) suggests these first five questions before you start testing:

> 1.  What (product) are we testing?
> 2.  What should be the main focus of our testing?
> 3.  How much resources (time/people) do we have?
> 4.  How should we document our test strategy and results? To who?
> 5.  Where can we get information about the product?

Those questions cover the most critical aspects of any strategy to test your product. You can use them to generate more questions and detail your strategy even more. As the number of questions increases, we should group them into categories.

I took [Jared Quinert](http://www.software-testing.com.au/blog/2009/07/21/thinking-about-test-strategy-a-mnemonic-device/)'s [**`GRATEDD SCRIPTS`**](https://dialex.github.io/start-testing/#/toolbox/mnemonics) mnemonic and further expanded it with more questions from [Erik Brickarp](http://erik.brickarp.se/2016/11/test-plan-questions.html) and [Michael Bolton](https://www.developsense.com/blog/2010/11/context-free-questions-for-testing/).

The result was this mind map, and a new [mnemonic](https://dialex.github.io/start-testing/#/toolbox/mnemonics) called **`SADDEST PPIRATEE`**:

[![](/blog/uploads/2020/06/Test-Strategy-Overview-SADDEST-PPIRATEE.png)](/blog/uploads/2020/06/Test-Strategy-Overview-SADDEST-PPIRATEE.png)

Download: [overview mind map](/blog/uploads/2020/06/Test-Strategy-Overview-SADDEST-PPIRATEE.png) / [detailed mind map](/blog/uploads/2020/05/Test-Strategy-SADDEST-PPIRATEE.png)

### Questions

**Scope**

- How would you describe the success of this project?
- What is expected that we deliver?
- What is expected that we achieve?
- (extra)
    - How flexible is the scope? Can we descope if needed?
    - What are the functional requirements?
    - What are the non-functional requirements?

**Product**

- What problem are we solving? For who?
- What should our solution do? Scenarios?
- (extra)
    - What should our solution never do?
    - Are there alternatives or competitors to our product?
    - How are we expected to be different from the alternatives? Or the same?
    - How are users expected to interact with our product?
    - Which platforms (e.g. OS, browser) should we support?

**Stakeholders**

- Who has a stake on our success? And failure?
- Who is our client? (e.g. who pays the product)
- Who is our user? (e.g. who uses the product)
- (extra)
    - Any other stakeholder?
    - What are their expectations? And concerns?
    - Who is our team? What are their roles?
    - Who can we trust? Who should we avoid?

**Risks**

- What would threaten our success?
- What is likely to change?
- What is still unknown?
- (extra)
    - Do we foresee any obstacles or pain points?
    - How do we continuously verify we're on target?
    - Do we have any concerns or fears?
    - What's the worst thing that could happen? How can we avoid that?

**Dependencies**

- Is our delivery influenced by someone/thing outside our team?
- Do we need to cooperate with other teams? When, how and why?
- (extra)
    - Do we have to comply with rules/regulations?

**Approach**

- How will we work together? (e.g: scrum, kanban)
- How will we develop our product? (e.g. pairing, TDD)
- What would a typical day look like?
- (extra)
    - What is our done criteria?
    - How would we recognize a bug? (e.g. oracle)
    - How should react when we find a bug?
    - How do we make decisions and resolve conflicts?
    - How can we split testing among the team?
    - How do we handle onboarding? And handover?
    - Any regulations or rules that influence or limit the way we work?

**Prioritisation**

- Who will set priorities?
- Who reviews/approves our delivery?
- Who perceives the quality of our delivery?
- (extra)
    - Quality, Cost, Time: pick two
    - What other values are paramount?

**Time**

- Any important dates?
- Any recurring events or ceremonies?
- (extra)
    - How much time do we have to deliver?
    - What happens if we miss a deadline?

**Architecture**

- Can you draw the main components of our system?
- How do they interact

**Technologies**

- Are we expected to use any specific tools/languages?
- Which tools do we want to use to develop? And test? And deliver? And communicate?
- (extra)
    - What is the technological landscape where our product?
    - What tools are we expected to build?
    - What equipment and tools are available to support our testing?
    - Do we have enough resources to meet the expectations?
    - Should we use open-source? Can we pay for SaaS?

**Environments**

- How many do we need? For what?
- Who will manage them? Who has access?
- (extra)
    - What should change to increase testability?
    - What should change to speed up feedback?
    - How can we create/update test data?

**Data**

- Which metrics are relevant to us?
- (extra)
    - What data should we collect about our product?
    - What data should we collect about our approach?
    - How do we display that data? And make it visible?
    - Should we be notified when thresholds are crossed?

**Information**

- What is meaningful to test?
- What questions should our testing answer?
- How should those answers be reported? To who?
- (extra)
    - What do we need to learn more about?
    - Where can we get information about X? Who do we contact?
    - Where do we share knowledge? How?
    - How do we provide feedback to each other?
    - How do we track and visualize our testing?

**Experience**

- Have we ever worked in a similar context?
- What skills/experience can be found in the team?
- (extra)
    - Are we lacking any skills critical to our success?
    - Who else knows something about this, inside our organisation?
    - Who are the experts, even if outside our organisation?
    - Which tools and techniques are useful in our context?

**Emotions**

- How do you feel about our product?
- What do users feel and say about it?

### References

I hope these questions help you create your own test strategy. If you want to learn more about this subject (or testing in general), check my free [testing course](https://dialex.github.io/start-testing/#/toolbox/test-strategy).
