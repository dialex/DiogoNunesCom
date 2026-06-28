---
title: "Internet Button: post to a Slack channel (tutorial #5)"
description: "In this Particle Internet Button tutorial we'll use everything we learned: buttons, leds and Slack integration! Save time with this guide and its examples."
pubDate: "2017-04-24T08:00:00"
heroImage: "/blog/uploads/2016/08/Internet-Button-Tutorial5.jpg"
tags: ["coding", "hardware", "internet button", "tutorial"]
categories: ["Technology"]
---

### We're going fullstack: Button > Led > Wifi > Slack

Particle released a C++ library that handles much of the interaction with the Internet Button hardware components. That lib is publicly available on [GitHub](https://github.com/spark/InternetButton) and includes a bunch of [useful examples](https://github.com/spark/InternetButton/tree/master/firmware/examples).

First you need to include that library:

1.  Go to Particle [GitHub](https://github.com/spark/InternetButton/tree/master/firmware).
2.  Download `InternetButton.cpp` and `InternetButton.h` files.
3.  Move them to a folder on your project's repo (I used a `libs` folder).
4.  Add at the top of your `.ino` file this line `#include "InternetButton.h"`.

**Say you want to configure one of your buttons to publish a message on a Slack channel on every press**. For that you will have to play with webhooks or webapps. [Robin Laurén](https://github.com/llauren) released a [precious tutorial](https://github.com/llauren/slackbutton#how-to-do-it) on how to do it.

### All together now

To finish this [series](/blog/tag/hardware/), I decided to use a bit of every tutorial so far. This is the flow we are about to implement:

- Someone presses the 3rd button
- LEDs light up in a circular pattern
- Using wifi, a Slack webhook is called
- A message is published on a Slack channel
- All LEDs blink green to display success

Below are code examples that implement each of those features. Although I added extra comment lines, these are just snippets. You may need additional code to make them work.

#### Pressing the button

```
  // when the 3rd button (6 o'clock) is pressed
  if (b.buttonOn(3)) {

    b.ledOn(6, 255, 255, 255); // visual feedback of button press
    ledsDisplayWait(6);        // fancy light show

    // Get a message and send it to Slack
    String message = SlackHelper::getMessage();
    SlackHelper::publishMessage(message);

    ledsBlinkSuccess();        // visual feedback of success
  }
```

#### Helper: light show

```
int BLINK_TIMEOUT = 500; //milliseconds 

// Lights up LEDs in a circular pattern, starting somewhere
void ledsDisplayWait(int ledToLight = 1) {
  for (int i = 1; i <= 12; i++)
  {
    b.ledOn(ledToLight, 0, 0, 255);
    ledToLight = (ledToLight % 12) + 1;
    delay(100);
  }
  b.allLedsOff();
}

// Blink all LEDs green
void ledsBlinkSuccess() {
  b.allLedsOn(0, 255, 0);
  delay(BLINK_TIMEOUT);
  b.allLedsOff();
}
```

#### Helper: get a random message

```
  String SlackHelper::getMessage() {
    /* DISCLAIMER
     * I know I should use an array of strings and get a rand string.
     * Just you try to:
     * 1. Use declare a vector with const strings;
     * 2. Generate a random number based on current time;
     * 3. Get an element from the vector at a specific index;
     * 4. Convert that string to String (or is it String^)
     * Good luck with that
     */
    int randIndex = rand() % 3 + 1;
    switch (randIndex) {
      case 1: return "*Hello World!* :yum: (@here)";
      case 2: return "*Olá Mundo!* :walking: (@here)";
      case 3: return "*Bonjour le monde!* :laughing: (@here)";
    }
  }
```

#### Helper: push a message to Slack

```
void SlackHelper::publishMessage(String message) {
/*
 * Spark.publish() is a build-in method.
 * Just give it a webhook name and a message.
 * It will take care of the rest.
 */
  Spark.publish("event-name", message, 60, PRIVATE);
  Spark.publish("subscriber-name", message, 60, PRIVATE);
  Spark.publish("another-subscriber", message, 60, PRIVATE);
  Spark.publish("add-as-many-as-you-want", message, 60, PRIVATE);
}
```

* * *

### Slack integration using webhooks

That's _almost_ it. You are just lacking a bit of configuration. You see, that `Spark.publish()` is warning Particle's cloud that an event has happened. The cloud does not know what to do with it.

For the purpose of our example, we want the cloud to passthrough the message to Slack. That's when webhooks come to the rescue.

#### Steps for the subscriber

Whoever wants to receive your event needs to create a Slack webhook or endpoint that you can call when the even is triggered on the Internet Button.

[![](/blog/uploads/2016/08/subscriber1.png)](/blog/uploads/2016/08/subscriber1.png)

1.  Go to your Slack's **Incoming WebHooks**.
2.  Click **Add Configuration**.
3.  Select the channel that should received the notifications and click **Add**.
4.  Send us the **Webhook URL**.
5.  Feel free to customize the **Name** and **Icon** of this integration.

[![](/blog/uploads/2016/08/subscriber3.png)](/blog/uploads/2016/08/subscriber3.png)

#### Steps for the publisher

This is a template for any `webhook.json` (I like to keep them in a folder, each with suggestive names):

```
{
    "event": "XXXXXXXX",
    "url": "YYYYYYYY",
    "requestType": "POST",
    "json": {
        "text": "{{SPARK_EVENT_VALUE}}",
        "link_names": "1"
    },
    "deviceID": "ZZZZZZZZZ"
}
```

That files describes a [Particle's webhook](https://docs.particle.io/guide/how-to-build-a-product/web-app/):

- Replace the `XXX` with your desired and unique event name.
- Replace the `YYY` with the Slack's webhook URL from the previous section.
- Replace the `ZZZ` with your device ID, even though this field is optional, it will ensure only your device can make calls to that webhook.
- `{{SPARK_EVENT_VALUE}}` is a variable tag that will be replaced with the message from `Spark.publish()` method.
- `"link_names": "1"` is specific to Slack's API, it's a workaround to let your bots use `@here` notifications.

[![](/blog/uploads/2016/08/Screen_Shot_2016-07-30_at_18.14.39.png)](/blog/uploads/2016/08/Screen_Shot_2016-07-30_at_18.14.39.png)

Once you are done, open a terminal and run `particle webhook create <name-of-webhook.json>`. Then you can check online, on Particle's cloud, that the webhook was created.

### Mission accomplished

Sorry for the long and detailed tutorial. But hey, at least you know every action you have to take to make it work :P Good luck!
