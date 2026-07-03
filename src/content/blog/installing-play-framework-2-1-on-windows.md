---
title: "Installing Play Framework 2.1 on Windows"
description: "The Play frameowrk is probably on of the few web frameworks that installs and works pretty well on Windows. To install Play just follow these steps."
pubDate: "2014-03-17T08:00:30"
heroImage: "../../assets/blog/uploads/2014/03/lets-play-1.png"
tags: ["coding", "play framework"]
categories: ["Technology"]
---



The Play Framework is probably one of the few web frameworks that installs and works pretty well on Windows. I started web development with Django, but I had to learn Python and Django at the same time, and the documentation was not so good as [they](http://stackoverflow.com/a/846266/675577) said it was. So I decided to leverage my Java experience and use Play. And since Play supports both Java and Scala I could refactor and move to Scala at any time.

![Overview of Play Framework](http://i.stack.imgur.com/9pZhT.png)

_Overview of Play Framework_

On this guide I'll just refer to these variables and not their absolute paths:

- `%JAVA_HOME%`, where Java installer installs JDK, e.g. `C:\Program Files (x86)\Java\jdk1.7.0_25\bin`
- `%PLAY_HOME%`, where you extract the Play! Framework files, e.g. `C:\Play\play-2.1.2`

To install Play just follow these steps:

1. _First, you should follow the default steps from the [official guide](http://www.playframework.com/documentation/2.1.x/Installing)._
2. Uninstall previous JDK. Download and install the most recent [JDK](http://www.oracle.com/technetwork/java/javase/downloads/index.html). Don't forget to (1) create a `%JAVA_HOME%` system variable and (2) append that variable to your `PATH` environment variable.
3. Extract the Play! Framework install package (zip) to `%PLAY_HOME%`. Attention: you must have read and write permissions on that folder.
4. Open a command line, go to `%PLAY_HOME%`, type `play` and enter. That should start the Play! installer. Wait for the installation to finish. Don't forget to add `%PLAY_HOME%` to your `PATH` environment variable.
5. To test if everything is ok, open a command line, then execute `java -version`, then `javac -version`, then `play help`. Check the outputs of each command and you'll know if it worked.
