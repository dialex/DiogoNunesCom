---
title: "Geotag your DSLR photos using just a phone"
description: "Some high-end cameras have built-in GPS. If yours doesn't, fear not, your smartphone will save the day. Follow this easy tutorial to find out how."
pubDate: "2014-12-22T08:00:00"
heroImage: "/blog/uploads/2014/12/nikon-gps-external-unit.jpg"
tags: ["minimalism", "tutorial", "utilities"]
categories: ["Photography"]
---

### You already carry a GPS, it's your smartphone.

Some high-end cameras have built-in GPS units that geotag your photos as soon as you shoot them. However my entry-level DSLR does not have that functionality. Sometimes I don't know the name of the place where I'm shooting, other times I'm just lazy with my metadata, other times I want to upload my photos to a photography site without manually specifying where I took the photo.

So I started looking for an **external GPS unit** for my camera. That would cost me at least 40€, add more weight to the camera, disenable the built-in flash, drain more battery, and make me look like a weirdo. The minimalist in me said _"Nonsense! There's got to be a better way!"_ -- and he was right.

## What you need

-   A smartphone (i.e. a GPS device capable of recording/exporting a route)
-   A digital camera
-   A computer
-   A beautiful place to photograph

## Part 1 - Recording your GPS location

1.  Assuming you have an Android smartphone, install [Geo Tracker](https://play.google.com/store/apps/details?id=com.ilyabogdanovich.geotracker) app.
2.  Before taking any photos start the app. Make sure you enable the Location services on your smartphone's settings.
3.  The app will search for a GPS signal and your current location (up to 5 minutes). When your location appears in a map it means your good to go.
4.  Press the big red button to start recording your path.
5.  After you finish your photo trip, open the app again and stop the recording.
6.  Make sure you export your recently recorded track to Google Drive.

## Part 2 - Embedding the GPS metadata into your photos

1.  Import your photos from your camera. I use raw files (.nef because I own a Nikon) but .jpeg will work just fine too.
2.  Download your track from Google Drive (or directly from the app) to your computer.
3.  Install [GeoSetter](http://www.geosetter.de/en/download/).
4.  Start GeoSetter and open the folder containing the photos you imported on step 1. Then press the button that synchronizes a GPS file with the selected photos. The screenshot below illustrates this step. [![dslr-gps-1](/blog/uploads/2014/12/dslr-gps-1-e1417889924893-1024x751.png)](http://www.diogonunes.com/blog/wp-content/uploads/2014/12/dslr-gps-1-e1417889924893.png)
5.  A window will popup asking you to selected the GPS track file to sync. Browse your `track.kmz` file. Configure the remaining options to suit your preferences. [![dslr-gps-2](/blog/uploads/2014/12/dslr-gps-2.png)](http://www.diogonunes.com/blog/wp-content/uploads/2014/12/dslr-gps-2.png)
6.  Start the synchronization process. [![dslr-gps-3](/blog/uploads/2014/12/dslr-gps-3.png)](http://www.diogonunes.com/blog/wp-content/uploads/2014/12/dslr-gps-3.png)
7.  After the sync is complete, don't forget to save the changes to your photos, by pressing the blue diskette button. Doing so will tell GeoSetter to embed the GPS information from the track into your photo's metadata. [![dslr-gps-4](/blog/uploads/2014/12/dslr-gps-4-1024x455.png)](http://www.diogonunes.com/blog/wp-content/uploads/2014/12/dslr-gps-4.png)

### Well done

Now your photos have GPS coordinates, without buying any additional gear and even though your camera doesn't have an internal GPS. You should be proud of yourself. Help your photography friends by sharing this tutorial with them.
