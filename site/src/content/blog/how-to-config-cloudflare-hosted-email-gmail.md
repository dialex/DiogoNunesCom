---
title: "Cloudflare + Hosting + Gmail"
description: "How to keep using your hosted email on Gmail, after migrating to Cloudflare. You just need two steps but they are not straightforward."
pubDate: "2018-12-17T07:00:41"
heroImage: "../../assets/blog/uploads/2018/12/938a195f8810cb9b31c6503221891897_XL.jpg"
tags: ["tutorial", "web dev"]
categories: ["Technology"]
---

## How to keep using your hosted email on Gmail, after migrating to Cloudflare

My initial setup for this website was: - **Domain** from Namecheap _(no one beats their price/quality)_ - **WebHost** from a Portuguese company

My webhost offers domain email addresses, so I had a few `*@diogonunes.com` addresses configured.

Since I use Gmail daily for my personal email, I configured it to send and receive from `*@diogonunes.com` addresses just like any other `*@gmail.com`. You can easily find guides online on how to configure it. It's totally worth it, I'm very productive this way.

## Adding Cloudflare to the mix

Then a friend recommended me Cloudflare: a free service that sits in front of your webhost and protects your website from malicious attacks (like DoS). For free!

The service was easy to setup thanks to their semi-automated guide. After some DNS changes my website was protected.

However, my domain email addresses stopped working. I could not receive emails and Gmail would display some errors in the settings tab.

To keep my Gmail integration working I followed [these instructions](https://serverfault.com/a/642956/131531):

1. The MX record should be for your domain, not for a subdomain.
2. An MX record should not point to a CNAME. Point it to a record with an IP.

An example of a working domain:

![](https://i.stack.imgur.com/GccBx.png)

I hope this helps you as much as it did for me.
