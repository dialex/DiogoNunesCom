---
title: "Running and debugging your Play app"
description: "`play run` will start the local (Netty) server and your app will be available at localhost:9000."
pubDate: "2014-03-31T08:00:25"
heroImage: "/blog/uploads/2014/03/lets-play-3.png"
tags: ["coding", "play framework"]
categories: ["Technology"]
---

[](http://www.diogonunes.com/blog/wp-content/uploads/2014/03/lets-play-3.png)

## Running locally

- `play run` will start the local (Netty) server and your app will be available at [localhost:9000](http://localhost:9000).
- `play ~run` will start the local server and reload it automatically every time you change a file of you app, that way you don't need to hit refresh (F5) on your browser.
- **Useful tip:** If you don’t want to have to worry about [applying evolutions](http://www.playframework.com/documentation/2.2-SNAPSHOT/Evolutions) each time you restart Play, add `applyEvolutions.your_database_name=true` to your application.conf file. For instance, considering the default database, you should add `applyEvolutions.default=true`.

## Debugging using Eclipse

1.  First you must start the Play! app in debug mode by executing the command `play debug run`.
2.  On Eclipse add your breakpoints as you would normally do.
3.  Then go to `Run > Debug Configurations...` and double-click `Remote Java Application`. This will add a new configuration.
4.  On `Connect` tab, find `Connection properties` and change `Port` to `9999`.
5.  On `Common` tab, check `Debug` so that it later appears below the Debug icon. Click `Apply`. Click `Debug`.

That's it! [Check this video for a live explanation](http://www.youtube.com/watch?v=1SCTOl0qrlM).
