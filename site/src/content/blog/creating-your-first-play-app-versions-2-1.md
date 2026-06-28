---
title: "Creating your first Play 2.1 app"
description: "How to create your very first Play Framework application"
pubDate: "2014-03-17T08:08:29"
heroImage: "../../assets/blog/uploads/2014/03/lets-play-4.png"
tags: ["coding", "play framework"]
categories: ["Technology"]
---



Follow the [official install instructions](http://www.playframework.com/documentation/2.1.x/NewApplication) (these steps will only work for 2.1.\* versions of the Play Framework). Afterwards:

1.  `play new myFirstApp` creates a new app, in the current directory, inside a new folder called `myFirstApp`
2.  Now try to run it. First go inside that folder (`cd myFirstApp`), and run it (`play`).
3.  \[Optional step\] You can transform your Play application into an Eclipse project, by running `play eclipse`. If you want Play's source files and javadoc to be attached to the Eclipse project, you should execute `play "eclipse with-source=true"` [instead](http://stackoverflow.com/q/20305208/675577).

If during step 2 you get this error:

```
Error occurred during initialization of VM
Could not reserve enough space for object heap
Error: Could not create the Java Virtual Machine.
Error: A fatal exception has occurred. Program will exit.
```

You can [solve it](http://stackoverflow.com/questions/11071950/play-framework-run-application-issue) if you:

1.  Edit `%PLAY_HOME%\framework\build.bat` script
2.  Remove the command line argument for the command below: `java -Xms512M -Xmx1024M -Xss1M -XX:+CMSClassUnloadingEnabled -XX:MaxPermSize=256M %DEBUG_PARAM% -Dfile.encoding=UTF8 -Dplay.version="%PLAY_VERSION%" -Dsbt.ivy.home="%~dp0..\repository" -Dplay.home="%~dp0." -Dsbt.boot.properties="file:///%p%sbt/sbt.boot.properties" -jar "%~dp0sbt\sbt-launch.jar" %*`
3.  Replace with `java -XX:+CMSClassUnloadingEnabled %DEBUG_PARAM% -Dfile.encoding=UTF8 -Dplay.version="%PLAY_VERSION%" -Dsbt.ivy.home="%~dp0..\repository" -Dplay.home="%~dp0." -Dsbt.boot.properties="file:///%p%sbt/sbt.boot.properties" -jar "%~dp0sbt\sbt-launch.jar" %*`
