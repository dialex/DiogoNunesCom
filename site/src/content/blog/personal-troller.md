---
title: "Personal Troller"
description: "An executable that enables you to troll someone by: showing a message, opening a new tab on a specific URL, opening the disks drive several times and more!"
pubDate: "2015-07-13T08:00:10"
heroImage: "/blog/uploads/2015/07/ogre.png"
tags: ["c#", "free and open", "utilities"]
categories: ["Technology"]
---

![Troll face](/blog/uploads/2015/07/Troll-face-400x365.png)

I created an executable that enables you to troll someone by:

- Showing a dialog message
- Opening a new tab on for a specific URL
- Opening and closing the CD/DVD drive several times
- Shutting down or logging off displaying a message

## How to use

1.  Copy the executable (`Troller.exe`) to your victim's pc.
2.  Open the executable -- it will run silently on the background.
3.  Enjoy.

The executable searches for a `Tasks.txt` file containing the trolling actions to perform. If it doesn't exists, the executable will create one with default actions.

## Download

You can build the source files to get the latest `Troller.exe` or download this [one ready to use](http://www.diogonunes.com/assets/downloadmanager/click.php?id=11).

## How to configure Troller's tasks

Each line is a command. The syntax is `action|parameter`. Don't surround the `|` separator with spaces.

- Start by specifying the time to start (`BEGIN` action) and suspend (`END` action) the troller.
- Then specify the time interval between trolling actions (`EVERY` action).
- All of these `actions` receive a time `parameter` in the format `HH:mm:ss`.

Trolling actions:

- **`MESSAGE`** shows a dialog message, `parameter` is the message.
- **`OPENURL`** opens a new tab, `parameter` is the link to open.
- **`DISKDRV`** opens the disk drive, `parameter` is the number of times.
- **`SHUTDWN`** shutsdown the computer, `parameter` is the message to display 15min before.
- **`LOGUOFF`** logs off the user, `parameter` is the message to display 15min before.

Example:

```
BEGIN|09:42:57
EVERY|00:19:23
END|17:47:14

OPENURL|http://www.sanger.dk/
OPENURL|http://www.ringingtelephone.com/
OPENURL|http://cachemonet.com/
DISKDRV|1
OPENURL|http://giantbatfarts.com/
OPENURL|http://www.ooooiiii.com/
OPENURL|http://cat-bounce.com/
OPENURL|http://www.iiiiiiii.com/
DISKDRV|3
OPENURL|http://leekspin.com/
OPENURL|http://iamawesome.com/
OPENURL|http://www.nelson-haha.com/
DISKDRV|5
OPENURL|http://www.muchbetterthanthis.com/
OPENURL|http://baconsizzling.com/
DISKDRV|7
OPENURL|http://www.sadtrombone.com/?autoplay=true
MESSAGE|I can't wait for tomorrow :)
```

## Code, Documentation, New features, License

This project is open-source ([@GitHub](https://github.com/dialex/PersonalTroller)) and licensed under the terms of the MIT License.

**Be responsible - this program is for fun, not for harm.** Both creator and contributors of this program cannot be held responsible for the consequences of how others use it. If you liked this software, consider making a [donation](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=DGR2KAV5RLGBW) :D or contributing with a [new trolling action](https://github.com/dialex/PersonalTroller/pulls) :P
