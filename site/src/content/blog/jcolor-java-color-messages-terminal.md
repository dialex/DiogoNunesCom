---
title: "Colored messages on a terminal using Java"
description: "JCDP allows you to print colored output on your console/terminal. It's API is easy to use and extend. Also has some neat debug logging features."
pubDate: "2014-10-27T08:00:44"
heroImage: "/blog/uploads/2014/10/JColor-logo.png"
tags: ["free and open", "utilities"]
categories: ["Technology"]
---

[](/blog/uploads/2014/10/JColor-logo.png)

### JColor is a Java library that offers an easy way to print colored messages on a terminal

It all started when I needed to create an application with multiple levels of debug. The solution was an object (a Printer) with a maximum level of debug. Every debug message delivered to it had a level. If the Printer had an equal or greater level, the debug message would be printed. This allows you to place debug messages with different levels along the code. Then, when initiating the app, you choose a maximum level of debug and only the debug messages with an equal or lower level will be displayed. Even better you can change that maximum level dynamically.

To enhance the usefulness of this library I decided to add colored messages, which turned out to be pretty tricky. By abstracting the Ansi Escape Codes, printing a colored message became as easy as `print("message", Attribute.BOLD, FColor.BLUE, BColor.YELLOW);`

[![mac example output](/blog/uploads/2014/10/example-mac-iterm-fancy.png)](/blog/uploads/2014/10/example-mac-iterm-fancy.png)

### Main features

- Print colored messages on a terminal using Java.
- **Cross-platform**. Works on Unix, Windows 10 and macOS.
- **Open-source**. [Repo on GitHub](https://github.com/dialex/JColor), you will find documentation and examples there.
- **Legacy friendly**. The latest version requires Java 8, but it supports Java 6 too.
- **Rainbows!** You can use any RGB color combination (TrueColor).
- **Easy to use**. Format your messages using a simple API, and print them with `System.out.print`.

### License

This program is free software under the terms of the **MIT LICENSE**. If this software was useful to you, consider donating.
