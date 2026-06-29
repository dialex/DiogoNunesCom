---
title: "Move from PhantomDriver to headless ChromeDriver on Jenkins"
description: "How to configure the headless Chrome (Driver) on Jenkins (Ubuntu CI machine). PhantomJS is dead, long live headless Chromium!"
pubDate: "2017-07-03T13:50:00"
heroImage: "../../assets/blog/uploads/2017/04/ghostbuster-logo.jpg"
tags: ["testing", "tutorial"]
categories: ["Technology"]
---

## I had to make that joke ☝️

**UPDATE:** The initial goal of this post was to guide you through the configuration of the headless Chrome (Driver) on Jenkins. However, in the meantime this happened:

- [the maintainer of PhantomJS is stepping down](https://news.ycombinator.com/item?id=14105489)
- because [headless Chrome is coming](https://news.ycombinator.com/item?id=14101233)

> Chrome is faster and more stable than PhantomJS. And it doesn't eat memory like crazy. -- Vitaly Slobodin, Author of PhantomJS

Therefore I downgraded my _soon-to-be-made-obsolete-by-Chrome-59_ tutorial to a collection of the **helpful links that I used as reference** when I installed and configured my headless Chromium on the CI machine.

* * *

> **Xvfb** (short for X virtual framebuffer) is an in-memory display server for UNIX-like operating system (e.g., Linux). It enables you to run graphical applications without a display (e.g., browser tests on a CI server) while also having the ability to take screenshots.

## Helpful links

[This awesome guide](http://elementalselenium.com/tips/38-headless) will tell you **how to install and configure Xvfb**.

[This gist](https://gist.github.com/praphull27/6950d2a6c76a6e68acb7) helped me **install Chrome on Jenkins/Ubuntu**. Here's my final version:

```
# GIST SOURCE: https://gist.github.com/praphull27/6950d2a6c76a6e68acb7
######################################################################

echo "##### Add Google Chrome's repo to sources..."
echo "deb http://dl.google.com/linux/chrome/deb/ stable main" | sudo tee -a /etc/apt/sources.list
# Install Google's public key used for signing packages (e.g. Chrome)
wget -q -O - https://dl-ssl.google.com/linux/linux_signing_key.pub | sudo apt-key add -

# Update apt sources
sudo apt-get update

echo "##### Installing Headless Chrome dependencies..."
sudo apt-get install -y libxpm4 libxrender1 libgtk2.0-0 libnss3 libgconf-2-4
sudo apt-get install -y google-chrome-stable
sudo apt-get install -y xvfb gtk2-engines-pixbuf
sudo apt-get install -y xfonts-cyrillic xfonts-100dpi xfonts-75dpi xfonts-base xfonts-scalable
sudo apt-get install -y imagemagick x11-apps

## Since https://wiki.jenkins-ci.org/display/JENKINS/ChromeDriver+plugin doesn't work...
echo "##### Downloading latest ChromeDriver..."
LATEST=$(wget -q -O - http://chromedriver.storage.googleapis.com/LATEST_RELEASE)
sudo wget http://chromedriver.storage.googleapis.com/$LATEST/chromedriver_linux64.zip
echo "##### Extracting and symlinking chromedriver to PATH so it's available globally"
sudo unzip chromedriver_linux64.zip && sudo ln -s $PWD/chromedriver /usr/local/bin/chromedriver

# echo "##### Starting X virtual framebuffer (Xvfb) in background..."
# xdpyinfo -display :99 >/dev/null 2>&1 && echo "Xvfb @99 is in use" || echo "Xvfb @99 is free"
# Xvfb -ac :99 -screen 0 1280x1024x16 &
# export DISPLAY=:99
# xdpyinfo -display :99 >/dev/null 2>&1 && echo "Xvfb @99 is in use" || echo "Xvfb @99 is free"
```

I also had to install the **[ChromeDriver Jenkins plugin](https://wiki.jenkins-ci.org/display/JENKINS/ChromeDriver+plugin)**.

[This guide](https://www.blazemeter.com/blog/headless-execution-selenium-tests-jenkins) helps you **configuring Jenkins** for both Win/Nix machines.

And this [short post](http://tobyho.com/2015/01/09/headless-browser-testing-xvfb/) on **how to run Xvbf**.
