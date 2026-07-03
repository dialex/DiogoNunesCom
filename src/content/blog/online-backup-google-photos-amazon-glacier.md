---
title: "Online affordable photo backup: Google Photos vs Amazon Glacier"
description: "I wanted to backup my originals (RAWs/NEFs), my edits (XML) and my other photos (JPGs). It should be easy to sync changes in my catalog and it shouldn't cost me a fortune per GB."
pubDate: "2019-11-18T07:00:32"
heroImage: "../../assets/blog/uploads/2019/11/alex-machado-80sv993lUKI-unsplash-scaled.jpg"
heroCaption: "Photo by Alex Machado on Unsplash"
tags: ["experience", "review", "utilities"]
categories: ["Photography"]
---

## The winner depends on your needs

I started with manual backups of my photos on a local external drive. It was boring and unreliable. So I started considering online services.

> I wanted to backup my originals (RAWs/NEFs), my edits (XML) and my other photos (JPGs). It should be easy to sync changes in my catalog and it shouldn't cost me a fortune per GB.

Two services, out of the dozens I tried, survived my criteria.

## Google Photos

![](../../assets/blog/uploads/2019/11/google-photos-400x400.jpeg)

I recommend if you are an amateur photographer, who only uses JPGs and doesn't worry about resolutions above 16MP.

### What they don't tell you

Here's what their documentation doesn't mention (I had to discover it by trial and error):

- If you upload a DNG it will show up without post-edit
- If you upload a NEF/RAW it will show up without post-edit and upload a JPG copy with the effects applied
- XML files are applied to your NEF on the fly, to generate the JPG mentioned above
- NEFs/RAWs are uploaded, but once in the cloud you can only download them as JPG
- If you upload the same photo twice (via web or via uploader), it will detect that it is a duplicate and ignore it

### Verdict

Very easy to use. You can upload photos by drag and drop on your browser or by syncing any folder on your computer.

But it is horrible to store originals, because of the weird way it handles RAWs/NEFs. However it's great for JPGs. You can organize photos on albums, it supports searching for tags, and you can easily share a photo with anyone.

It's one of the cheapest and potentially free (if your photos are <16MP).

## Amazon Glacier

![](../../assets/blog/uploads/2019/11/amazon-glacier-logo.jpg)

I recommend if you are an enthusiastic/pro photographer, who uses mostly RAWs/NEFs with big resolutions. A Software Engineering degree might also be required (I'm only half joking).

### What they don't tell you

- [The setup is a nightmare!](https://thegreyblog.blogspot.com/2012/12/amazon-s3-and-glacier-cheap-solution.html) I have a master degree on Computer Science and even I had trouble knowing what I was doing.
- Amazon's UI is not user friendly at all. The naming used is mostly tech jargon. You will be staring at stuff you have no clue what it means.
- You need a software to sync the files from folder to Amazon's Glacier cloud. Just like with GPhotos. Except Amazon doesn't provide that app.
- There are a couple free apps for [Windows](https://www.cloudwards.net/best-backup-tools-amazon-glacier/), but for Mac you have a single freemium app called [Freeze](https://www.freezeapp.net/), which does the job.

### Verdict

Confusing and frustrating setup and tooling.

On the other hand you get peace of mind, that all your original files (RAW) and edits (XML) are backed up.

And the cost per GB is the cheapest in the market for high volumes of data. If you don't need to access your old photos that often, Glacier's download penalties shouldn't hurt you.

* * *

## tl;dr

Use GPhotos if you only use JPG of low resolution and favor ease of use. Use Amazon Glacier if you work with RAW+Edits and favor cheap high-resolution backups.
