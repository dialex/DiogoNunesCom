---
title: "Birthday bot for Slack"
description: "Automatically post a \"happy birthday\" message on your Slack channel on every colleague's birthday. Easy to configure. Deploy to Heroku for free. MIT License"
pubDate: "2016-07-04T08:00:32"
heroImage: "../../assets/blog/uploads/2016/06/work-birthday-slack-bot.png"
tags: ["free and open", "ruby", "slack", "utilities"]
categories: ["Technology"]
---

## Never forget a birthday, right from your Slack channel.

The purpose of this bot is to post a greeting message on your team's Slack channel on a colleague's birthday.

Every time the bot runs it will read some configuration files, check who was born at that date, and send a push notification to a Slack channel.

You just need to create those configuration files, deploy this code to a server (e.g. Heroku), and run that command based on a daily schedule (e.g. Heroku Scheduler).

The steps are pretty straightforward and just six! There's a **[detailed tutorial](https://github.com/dialex/BirthdaySlackBot/blob/master/README.md)** at the official [GitHub](https://github.com/dialex/BirthdaySlackBot/) that walks you through the process.

![demo screenshot](../../assets/blog/uploads/2016/07/Screen-Shot-2016-06-26-at-10.13.22.png)

I created this bot a side project while I was working at [Equal Experts](https://www.equalexperts.com). I googled and there was already [one](https://github.com/jeKnowledge/slack-birthday-bot) created by [Tiago Botelho](https://github.com/tiagonbotelho) while he was also an intern (at [jeKnowledge](http://jeknowledge.pt/)).

It is now released under the **MIT license**, so feel free to improve it.
