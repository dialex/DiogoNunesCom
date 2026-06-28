---
title: "Upgrading your Play app to a newer version"
description: "Let's assume you have an existing app using Play! 2.1.A and a 2.1.B version was released. You want to update your environment to use that new release."
pubDate: "2014-03-31T09:10:26"
heroImage: "/blog/uploads/2014/03/lets-play-2.png"
tags: ["coding", "play framework"]
categories: ["Technology"]
---

[](/blog/uploads/2014/03/lets-play-2.png)

Let's assume you have an existing app using **Play! 2.1.A** and a **2.1.B** version was released. You want to update your environment to use that new release. Note: This tutorial was tested to be working with 2.1.\* versions; A and B are two arbitrary numbers.

## Updating environment

Like when installing a new version of Play:

1.  Extract the `Play 2.1.B` install package (zip) to `%PLAY_HOME%\play-2.1.b`. Attention: you must have read and write permissions on that folder.
2.  Open a command line, go to the parent folder of `%PLAY_HOME%`, open `play-2.1.b` folder, type `play` and enter. That will start the Play! installer. Wait for the installation to finish.
3.  To finish up, you must tell your system to use the new Play version. To do so you must go edit your environment variable "%PLAY\_HOME%" from `some-path\play-2.1.a` to `some-path\play-2.1.b`.
4.  To test if everything is ok, open a command line, then execute `play version`. Check if the version displayed is **Play! 2.1.B**.

## Updating projects

Now that your environment is using the newer version of Play you must update your (Eclipse) project's imported files.

1.  Go to your project's main folder, open `project` and edit `plugins.sbt`.
2.  Look for `addSbtPlugin("play" % "sbt-plugin" % "2.1.a")` and simply change the version to `"2.1.b"`
3.  Open a command line on your project's main folder and run `play eclipse`, just like in the old days. Play startsr to fetch the updated files, remove the old ones and update all those import references. Just sit back while it does all the work.

## Consequences

And it's done! But mind that... since we replaced the `build.bat` file, the following error will probably reappear:

```
Error occurred during initialization of VM
Could not reserve enough space for object heap
Error: Could not create the Java Virtual Machine.
Error: A fatal exception has occurred. Program will exit.
```

You know [what to do](http://stackoverflow.com/questions/11071950/play-framework-run-application-issue). And since we replaced `play.bat` file, you may also want to add that `-DapplyEvolutions.default=true` argument to the play command (`play` for unix and `play.bat` for windows).
