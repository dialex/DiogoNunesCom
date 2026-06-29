---
title: "Internet Button: push my buttons (tutorial #3)"
description: "In this Particle Internet Button tutorial we'll deal with the physical buttons and assigning actions to them. Save time with this guide and its examples."
pubDate: "2017-02-27T08:00:00"
heroImage: "../../assets/blog/uploads/2016/08/Internet-Button-Tutorial3.jpg"
tags: ["coding", "hardware", "internet button", "tutorial"]
categories: ["Technology"]
---

### Assign a different action to each button.

Particle released a C++ library that handles much of the interaction with the Internet Button hardware components. That lib is publicly available on [GitHub](https://github.com/spark/InternetButton) and includes a bunch of [useful examples](https://github.com/spark/InternetButton/tree/master/firmware/examples). To check if a button was pressed becomes as easy as `buttonOn(led_number)`.

First you need to include that library:

1. Go to Particle [GitHub](https://github.com/spark/InternetButton/tree/master/firmware).
2. Download `InternetButton.cpp` and `InternetButton.h` files.
3. Move them to a folder on your project's repo (I used a `libs` folder).
4. Add at the top of your `.ino` file this line `#include "InternetButton.h"`.

Below is the code I used to click a button and turn a led on.

```
void loop(){
    if(b.allButtonsOff()) {
        b.allLedsOff();
    }
    // When you click a button it lights on the respective led
    else if(b.buttonOn(1)){
        b.ledOn(1, 255, 255, 255);
        b.ledOn(11, 255, 255, 255);
        delay(1000);
    }
    else if(b.buttonOn(2)){
        b.ledOn(3, 255, 255, 255);
        delay(1000);
    }
    else if(b.buttonOn(3)){
        b.ledOn(6, 255, 255, 255);
        delay(1000);
    }
    else if(b.buttonOn(4)){
        b.ledOn(9, 255, 255, 255);
        delay(1000);
    }
}
```
