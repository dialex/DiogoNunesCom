---
title: "Internet Button: let there be light (tutorial #2)"
description: "In this Particle Internet Button tutorial we'll deal with the LEDs and displaying patterns of light. Save time with this guide and its examples."
pubDate: "2017-01-30T08:00:00"
heroImage: "/blog/uploads/2016/08/Internet-Button-Tutorial2.jpg"
tags: ["coding", "hardware", "internet button", "tutorial"]
categories: ["Technology"]
---

### Turning leds on and displaying light patterns.

You can use Particle's mobile app to light the small built-in led on Photon (the chip). There's a [guide here](https://docs.particle.io/guide/getting-started/start/photon/#step-3-blink-an-led-). It actually worked and the led turned blue. But we have an Internet Button with 11 large and bright LEDs. Those are the ones we want to light up!

Particle released a C++ library that handles much of the interaction with the Internet Button hardware components. That lib is publicly available on [GitHub](https://github.com/spark/InternetButton) and includes a bunch of [useful examples](https://github.com/spark/InternetButton/tree/master/firmware/examples). Lighting up a led becomes as easy as `ledOn(LED, red, green, blue)`.

First you need to include that library:

1.  Go to Particle [GitHub](https://github.com/spark/InternetButton/tree/master).
2.  Download `InternetButton.cpp` and `InternetButton.h` files.
3.  Move them to a folder on your project's repo (I used a `libs` folder).
4.  Add at the top of your `.ino` file this line `#include "InternetButton.h"`.

And now you're ready to use their helper functions.

```
void loop(){
    InternetButton b = InternetButton();

    // The format here is (LED, red, green, blue),
    // so we're making a color with no red or green, but ALL blue
    // You should know that the range of brightness here is 0-255,
    // so 0 is off and 255 is the most possible.
    b.ledOn(6, 0, 0, 255);

    // Since the LED is now on, let's have it stay that way for one second
    // Delay pauses the code for the amount of time given, in milliseconds- so 1000 millis is one whole second
    delay(1000);

    // To blink the LED, we need to turn it back off and then pause
    b.ledOff(6);
    delay(1000);
}
```

To blink all leds green in a one second interval use the code below:

```
void loop(){
    b.allLedsOn(0,255,0);
    delay(1000);

    b.allLedsOff();
    delay(1000);
}
```

Other [libraries](https://docs.particle.io/guide/getting-started/build/photon/#using-libraries) can be found [through this method](https://docs.particle.io/guide/getting-started/build/photon/#using-libraries).
