---
title: "Internet Button by Particle: an overview"
description: "Particle has this flexible IoT device called Internet Button. I'll explain what it is, what it includes and how it works."
pubDate: "2016-09-26T08:00:05"
heroImage: "/blog/uploads/2016/08/internetbuttondiagram-1.jpg"
tags: ["hardware", "internet button"]
categories: ["Technology"]
---

![](/blog/uploads/2016/08/internet-button.jpg)

## What it is

The [Internet Button](https://www.particle.io/products/hardware/internet-button) contains a lot of useful hardware in a quite compact package. This is what it includes:

- Photon (the brain) with wifi access
- 11 bright RGB LEDs
- 4 physical buttons
- Sound speaker
- Accelerometer
- Plastic cover
- USB cable
- Particle's swag sticker :)

## How it works

![internet button overview diagram](/blog/uploads/2016/08/internet-button-overview-diagram-1024x321.png)

While some [IoT buttons](https://flic.io/) require Bluetooth pairing with a "master" device, **the Internet Button works independently via wifi**. That was one of the reasons that made me choose it over [Flic](https://flic.io/).

After some [initial setup](internet-button-unboxing-first-steps), Particle's cloud will know you and your Internet Button. Their cloud is an abstraction layer that lets you easily interact and manage all your Particle devices -- a connection between your keyboard and the device's hardware.

You can code locally on Atom IDE (recommended) or remotely on their web IDE (avoid at all costs). To deploy your code to the Internet Button you simply click **Publish** on your IDE. That will trigger several events:

1.  Your code is uploaded via wifi to Particle's cloud.
2.  The cloud compiles your code.
3.  If no errors are found, the build is deployed to your device via wifi.
4.  During this whole process, a LED on the Internet Button will change colors accordingly.

Each time the button powered on, it will fetch the most recent version of your code. That allows you to publish a new version with the button turned off.

This cloud connection is bi-directional, meaning that the Internet Button can also send data to Particle's cloud. That means you can publish or trigger events on [IFTTT](https://ifttt.com/particle), an API or a webhook.

**If you want to know more about the Internet Button and other IoT devices, [check out these other posts and tutorials](/blog/tag/internet-button/).**
