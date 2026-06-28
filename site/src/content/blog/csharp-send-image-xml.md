---
title: "Sending image in XML using C#"
description: "A code example in C# to exchange images through the internet using an XML payload. Convert your byte[] image to a text representation like base64."
pubDate: "2017-06-19T13:50:00"
heroImage: "/blog/uploads/2016/09/hands-opening-envelope-on-crowded-desk.jpg"
tags: ["c#", "coding"]
categories: ["Technology"]
---

-   On the sender side, start with your image as a `byte[]`.
-   Convert the array to a text representation (a base64 string) using `Convert.ToBase64String(array)`.
-   Put that base64 string into the XML.
-   On the receiver side, simply decode the base64 back to `byte[]` using `Convert.FromBase64String(string)`.

For [details](http://diogonunes.com/blog/csharp-write-read-images-sql-server) on each of those operations [check this other post](http://diogonunes.com/blog/csharp-write-read-images-sql-server).

[Source](http://stackoverflow.com/questions/2965345/how-to-send-image-through-xml-using-c-sharp)
