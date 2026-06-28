---
title: "Internet Button: unboxing and first steps"
description: "This tutorial is a guide to configure the Internet Button for the first time, either via USB, wifi or Particle mobile app. I had several errors and fails."
pubDate: "2016-12-05T08:00:00"
heroImage: "/blog/uploads/2016/07/IMG_20160614_140426195.jpg"
tags: ["hardware", "internet button", "tutorial"]
categories: ["Technology"]
---

### Brace yourself, it's going to be a bumpy ride.

This is hardware and on top of that it runs on C++. I knew it would be painful. But that was the challenge. If you're new to [Particle's Internet Button](/blog/particle-internet-button-overview/) you might want to check [this overview](/blog/particle-internet-button-overview/). Summarizing it: Particle is the brand that produces the Internet Button, which is a Photon chip with some additional hardware and a plastic case.

The internet button is a handy little piece of hardware that **contains 11 leds, 4 buttons, wifi capabilities, a sound speaker and even an accelerometer!** At the time is costed me $49, plus $20 of shipping if you live in the UK. Since I live in the middle of nowhere, also known as Portugal, it costed $50 for shipping and $20 for customs...

![tumblr_m48qaxPQTF1qg8vkwo1_250](/blog/uploads/2016/07/tumblr_m48qaxPQTF1qg8vkwo1_250.jpg)

Let's start.

### Configuring the Internet Button for the first time

Here's what worked for me (YMMV):

1.  Connected the Internet Button to laptop using the USB cable provided.
2.  The little status led started blinking blue.
3.  Installed Particle's Android app. The app could not find the device.
    - **\[FAIL\]**: Forget their app and go with the CLI setup.
4.  So I decided to try the [command line setup](https://docs.particle.io/guide/getting-started/connect/photon/).
    - I already had Node.JS installed thanks to `brew`.
    - So I just installed their client: `npm install -g particle-cli`.
5.  Time for the big moment: `particle setup`.
6.  The setup instructions were clear and sometimes humorous. However...
    - **\[FAIL\]**: The setup could not find the device. Just do the classic unplug & plug. Then make sure you're connected to your usual wifi (and not the Photon's wifi).
7.  I ran the setup a second time and this time the device was found. During configuration the setup automatically connects to the device's wifi and back to yours.
8.  Just tell the setup your wifi's password and give your device a name.
9.  DONE!
10.  Have a cookie, you deserved it after all this.

![Screenshot_20160724-151825](/blog/uploads/2016/07/Screenshot_20160724-151825-400x282.png)

If you did it correctly you should now see the led **flashing magenta** while it updates its own firmware. After that it goes **breathing cyan**. Remember to check [this doc](https://docs.particle.io/guide/getting-started/modes/photon/) to know the [meaning of each color and pattern](https://docs.particle.io/guide/getting-started/modes/photon/).

You're ready to interact with the Internet Button. **Right now it's a paper weight, you still have to program it**, so don't miss the next part of the tutorial.
