---
title: "Internet Button: it even has a speaker! (tutorial #4)"
description: "In this Particle Internet Button tutorial we'll deal with the speakers and playing sounds. Save time with this guide and its examples."
pubDate: "2017-03-27T08:00:00"
heroImage: "../../assets/blog/uploads/2016/08/Internet-Button-Tutorial4.jpg"
tags: ["coding", "hardware", "internet button", "tutorial"]
categories: ["Technology"]
---

### Now you can listen to Nyan Cat on demand.

Particle released a C++ library that handles much of the interaction with the Internet Button hardware components. That lib is publicly available on [GitHub](https://github.com/spark/InternetButton) and includes a bunch of [useful examples](https://github.com/spark/InternetButton/tree/master/firmware/examples).

First you need to include that library:

1. Go to Particle [GitHub](https://github.com/spark/InternetButton/tree/master/firmware).
2. Download `InternetButton.cpp` and `InternetButton.h` files.
3. Move them to a folder on your project's repo (I used a `libs` folder).
4. Add at the top of your `.ino` file this line `#include "InternetButton.h"`.

One of the examples allows you to [play the Nyan Cat](https://github.com/spark/InternetButton/blob/master/firmware/examples/8_MakingMusic.cpp) theme song! I extracted that functionality into a separate class.

On your setup you can add to a `libs` folder this `NyanCat.h` file:

```
#ifndef NYAN_CAT
#define NYAN_CAT

class NyanCatMelody {
    InternetButton b;
  public:
    NyanCatMelody(InternetButton button) {
      b = button;
    }
    void play();
  private:
    bool isPaused();
    void otherTasks();
    void playSongAndProcess(String song);
};

#endif
```

And this `NyanCat.cpp`:

```
// SOURCE: https://github.com/spark/InternetButton
#include "InternetButton.h"
#include "NyanCat.h"

// This is our Nyan Cat song definition
const char nyan_cat_intro[] = "DS5,16,E5,16,FS5,8,B5,8,DS5,16,E5,16,FS5,16,B5,16,CS6,16,DS6,16,CS6,16,AS5,16,B5,8,FS5,8,DS5,16,E5,16,FS5,8,B5,8,CS6,16,AS5,16,B5,16,CS6,16,E6,16,DS6,16,E6,16,B5,16\n";
const char nyan_cat_melody[] = "FS5,8,GS5,8,DS5,16,DS5,16,R,16,B4,16,D5,16,CS5,16,B4,16,R,16,B4,8,CS5,8,D5,8,D5,16,CS5,16,B4,16,CS5,16,DS5,16,FS5,16,GS5,16,DS5,16,FS5,16,CS5,16,DS5,16,B4,16,CS5,16,B4,16,DS5,8,FS5,8,GS5,16,DS5,16,FS5,16,CS5,16,DS5,16,B4,16,D5,16,DS5,16,D5,16,CS5,16,B4,16,CS5,16,D5,8,B4,16,CS5,16,DS5,16,FS5,16,CS5,16,DS5,16,CS5,16,B4,16,CS5,8,B4,8,CS5,8,FS5,8,GS5,8,DS5,16,DS5,16,R,16,B4,16,D5,16,CS5,16,B4,16,R,16,B4,8,CS5,8,D5,8,D5,16,CS5,16,B4,16,CS5,16,DS5,16,FS5,16,GS5,16,DS5,16,FS5,16,CS5,16,DS5,16,B4,16,CS5,16,B4,16,DS5,8,FS5,8,GS5,16,DS5,16,FS5,16,CS5,16,DS5,16,B4,16,D5,16,DS5,16,D5,16,CS5,16,B4,16,CS5,16,D5,8,B4,16,CS5,16,DS5,16,FS5,16,CS5,16,DS5,16,CS5,16,B4,16,CS5,8,B4,8,CS5,8,B4,8,FS4,16,GS4,16,B4,8,FS4,16,GS4,16,B4,16,CS5,16,DS5,16,B4,16,E5,16,DS5,16,E5,16,FS5,16,B4,8,B4,8,FS4,16,GS4,16,B4,16,FS4,16,E5,16,DS5,16,CS5,16,B4,16,FS4,16,DS4,16,E4,16,FS4,16,B4,8,FS4,16,GS4,16,B4,8,FS4,16,GS4,16,B4,16,B4,16,CS5,16,DS5,16,B4,16,FS4,16,GS4,16,FS4,16,B4,8,B4,16,AS4,16,B4,16,FS4,16,GS4,16,E4,16,E5,16,DS5,16,E5,16,FS5,16,B4,8,AS4,8,B4,8,FS4,16,GS4,16,B4,8,FS4,16,GS4,16,B4,16,CS5,16,DS5,16,B4,16,E5,16,DS5,16,E5,16,FS5,16,B4,8,B4,8,FS4,16,GS4,16,B4,16,FS4,16,E5,16,DS5,16,CS5,16,B4,16,FS4,16,DS4,16,E4,16,FS4,16,B4,8,FS4,16,GS4,16,B4,8,FS4,16,GS4,16,B4,16,B4,16,CS5,16,DS5,16,B4,16,FS4,16,GS4,16,FS4,16,B4,8,B4,16,AS4,16,B4,16,FS4,16,GS4,16,B4,16,E5,16,DS5,16,E5,16,FS5,16,B4,8,CS5,8\n";

void NyanCatMelody::play(){
  b.setBrightness(255);
  b.setBPM(142); // set the songs BPM
  playSongAndProcess(nyan_cat_intro);
  // Now loop the melody forever! You may pause the song by pressing any button :)
  playSongAndProcess(nyan_cat_melody);
}

/*********************************
* Below are our helper functions
*********************************/

// If any button is pressed, toggle `paused` and return it's value
bool NyanCatMelody::isPaused() {
  static bool paused = false;
  if (b.buttonOn(2) || b.buttonOn(3) || b.buttonOn(4)) {
    delay(50);
    paused = !paused;
    // wait until button is released
    while (b.buttonOn(2) || b.buttonOn(3) || b.buttonOn(4));
    delay(50);
  }
  return paused;
}

// Called once per note in the song
void NyanCatMelody::otherTasks() {
  b.advanceRainbow(25, 0);
  while (isPaused()) {      // while paused...
    b.setBrightness(50);    // dim the LEDs
    b.advanceRainbow(1, 2); // smoother rainbow
    Particle.process();     // keep our Cloud connection going
  }
  b.setBrightness(255); // re-brighten the jam!
}

/* This is the InternetButton::playSong(String song) routine
* pulled out here so we can call advanceRainbow() and read
* buttons in-between playing notes!
*/
void NyanCatMelody::playSongAndProcess(String song){
  char inputStr[song.length()];
  song.toCharArray(inputStr,song.length());
  char* note = strtok(inputStr,",");
  char* duration = strtok(NULL,", \n");

  while (duration != NULL) {
    // Play the note, and then process otherTasks()
    b.playNote(note, atoi(duration));

    // If button 1 is pressed the song stops, otherwise it is paused
    if (b.buttonOn(1)) {
      b.allLedsOff();
      return;
    }

    else
      otherTasks();

    // advance the note
    note = strtok(NULL,",");
    duration = strtok(NULL,", \n");
  }
}
```

On your `*.ino` file you can now include and play the tune as easy as:

```
#include "NyanCat.h"
int BLINK_TIMEOUT = 500; //milliseconds

void loop(){      
  // When you press the 1st button (12 o'clock)
  if(b.buttonOn(1)) {
    NyanCatMelody ncat = NyanCatMelody(b);
    ncat.play();
    delay(BLINK_TIMEOUT);
  }    
}
```
