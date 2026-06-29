---
title: "How to fix texture problems (pink squares) on Garry's Mod"
description: "If you join a multiplayer map you will see the whole map covered on fuchsia/pink and black squares and `ERROR` messages. There's a simple solution to that."
pubDate: "2016-01-04T13:45:24"
heroImage: "../../assets/blog/uploads/2015/12/maxresdefault.jpg"
tags: ["games", "tutorial"]
categories: ["Technology"]
---

If you try to play Garry's Mod right after you download and install it _you'll have a bad time_. If you join a multiplayer map you will most probably see the whole map covered on bright fuchsia/pink and black squares and `ERROR` messages.

That's because the map requires textures not included in the standard installation. It seems that most gmod maps depend on a lot of Counter Strike textures, and for some weird reason they are not included in the installation.

**Solution:** you need to manually download and install all those textures.

1. Download this [zip package](http://adf.ly/10475475/gmod-textures), which contains all the most used textures. Wait a few seconds, close the ad, and download the file (~700MB).
2. Find gmod installation folder. It should be somewhere, inside Steam's folder. In my case, it was located at  
    `C:\Program Files (x86)\Steam\steamapps\common\GarrysMod\garrysmod\addons`
3. Create a folder named `CSS Content Addon`.
4. Extract the zip contents to the folder created in the previous step.
5. Done. You should now have a `maps`, `models`, ... folders inside `CSS Content Addon`.

For the complete guide on how to install and configure gmod, have a look at [Configure Garry's Mod (the complete guide)](/blog/install-config-gmod-guide/).
