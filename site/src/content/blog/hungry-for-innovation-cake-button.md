---
title: "Hungry for innovation: the cake button"
description: "A story about cakes and geeks. How I used the Internet Button to notify my team on Slack. Also the receptionist can now play the Nyan Cat on demand."
pubDate: "2016-08-29T08:00:05"
heroImage: "/blog/uploads/2016/08/cakes-batch.jpg"
tags: ["hardware", "internet button", "story"]
categories: ["Technology"]
---

Here at Equal Experts we like to have pet projects and help the community. And of course, we are also geeks at heart. So when we at [EE Portugal](https://www.equalexperts.com/portugal/) came across the problem I’m about to explain, we had to solve it.

## The problem

Everyday, around 10:30am and 4:30pm, a company called [YumYum](http://www.yum-yum.pt/) arrives with a trolley full of croissants, pastries, sweet and normal bread.

Naturally we strive to stay healthy and avoid the temptation of sweets, thus whenever possible we choose brown bread. That means the last people that get to the trolley have no choice but to eat cakes. And to make things worse, EE’s office is on the upper floor – so when we finally notice the trolley has arrived, it is already too late.

![Humm... cakes](/blog/uploads/2016/08/yumyum-1cakes.jpg)

## The solution

We had already tried a low-tech solution: the receptionist tried to phone one of us, who would then shout to the room **_“YumYum time!”_**. But the people using headphones would always miss it.

This situation was far from optimal. Clearly, an expert could do better.

> What if... there was a magic button that the receptionist would press and would notify the whole team on Slack?

Then I spoke with my colleague [Mark Winteringham](https://twitter.com/2bittester) and he suggested: "The Internet Button is that magic you are looking for!"

## The implementation

Before long, The [Internet Button](https://www.particle.io/products/hardware/internet-button) was in our hands. The little package includes 11 LEDs, four buttons, an accelerometer, a speaker and a small chip (Photon) that controls all this and directly connects to wifi. [![I give you... the Internet Button](/blog/uploads/2016/08/internet-button.jpg)](/blog/uploads/2016/08/internet-button.jpg)

I programmed one of the buttons to push a message to our Slack channel on every click. The code was written in C++, coded locally on Particle’s local IDE (a flavour of Atom). The editor includes a button to compile the code and another to deploy it to the device. So the way it works is the code is sent to Particle’s cloud, where it is compiled, and then sent to the physical device – all through wifi. It’s great!

![Architecture overview](/blog/uploads/2016/08/internet-button-overview-diagram.png)

## The glory

[![working demo](/blog/uploads/2016/08/combo.gif)](/blog/uploads/2016/08/combo.gif)

- EE's team is now the first to arrive, even though we are on another floor.
- People who use headphones are back to a healthy diet.
- The receptionist's efficiency on this task was improved by 700%.
- Three companies are now subscribers of our YumYutton alerts.
- We are being asked to develop a visual and audible alert along the main floor.

![The trolley](/blog/uploads/2016/08/yumyum-2trolley.jpg)

**Remember: make the world a wee bit more awesome everyday!**
