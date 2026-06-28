---
title: "Monitor PageSpeed (v5) with Google Sheets"
description: "The goal is to create a GSheet that can track the historical PageSpeed Insights (PSI) perfomance data of one or more pages/URLs."
pubDate: "2021-06-21T07:00:00"
heroImage: "/blog/uploads/2020/10/Screenshot_2020-08-26_at_15.40.55.png"
tags: ["javascript", "testing", "tutorial", "utilities", "web dev"]
categories: ["Technology"]
---

[](https://www.diogonunes.com/blog/wp-content/uploads/2020/10/Screenshot_2020-08-26_at_15.40.55.png)

The goal is to create a Google Sheet that can track the historical PageSpeed Insights (PSI) perfomance data of one or more pages/URLs. You will use JavaScript code to call the PageSpeed Insights API and store the result in your sheet.

### Run the test + Store the data

For each target URL, the script will run the PSI test for both desktop and mobile and record the overall test score (configurable). Since the PSI scores tend to slightly vary between executions, the script runs the test three times (configurable) and store the average.

1.  Generate a **PageSpeed Insights** (PSI) key:
    1.  Open the [PSI docs](https://developers.google.com/speed/docs/insights/v5/get-started) and click the **Get a Key** button
    2.  Follow the wizard and copy the generated key
2.  Create a new [Google Sheet](https://sheets.google.com/)
3.  Navigate to **Tools > Script Editor**
4.  From the **Google Apps Script** editor, give your script a name (e.g. `PSI Monitoring`)
5.  Navigate to **File > Project properties**, create a new **Script property** named `PSI_API_KEY` and paste in your API key
6.  In `Code.gs`, paste the script below
    1.  Don't forget to find the `// TODO` comments and configure them as you desire

```javascript
// Adapted from Rick Viscomi (@rick_viscomi)
// Originally adapted from https://ithoughthecamewithyou.com/post/automate-google-pagespeed-insights-with-apps-script by Robert Ellison

var scriptProperties = PropertiesService.getScriptProperties();
var pageSpeedApiKey = scriptProperties.getProperty("PSI_API_KEY");
var pageSpeedMonitorTitles = [
    // TODO: CHANGE THESE
  "Website name",
  "More descriptive than long urls",
];
var pageSpeedMonitorUrls = [
  // TODO: CHANGE THESE
  "https://yourdomain.com",
  "https://another.website",
];

function monitor() {
  for (var i = 0; i < pageSpeedMonitorUrls.length; i++) {
    var url = pageSpeedMonitorUrls[i];
    var title = pageSpeedMonitorTitles[i];
    // Scores tend to fluctuate, specially on STG, so we average them
    var desktopScore = getAverageScore(url, "desktop", 3);
    var mobileScore = getAverageScore(url, "mobile", 3);
    addRow("Sheet1", title, desktopScore, mobileScore);
  }
}

function callPageSpeed(url, strategy) {
  var pageSpeedUrl =
    "https://pagespeedonline.googleapis.com/pagespeedonline/v5/runPagespeed?url=" + url + "&key=" + pageSpeedApiKey + "&strategy=" + strategy;
  var response = UrlFetchApp.fetch(pageSpeedUrl);
  var json = response.getContentText();
  return JSON.parse(json);
}

function addRow(sheetName, desc, desktopScore, mobileScore) {
  var spreadsheet = SpreadsheetApp.getActiveSpreadsheet();
  var sheet = spreadsheet.getSheetByName(sheetName);
  sheet.appendRow([
    Utilities.formatDate(new Date(), "GMT", "yyyy-MM-dd"),
    desc,
    desktopScore,
    mobileScore
  ]);
}

function extractOverallScore(data) {
  return data.lighthouseResult.categories.performance.score * 100;
}

function getAverageScore(url, strategy, attempts) {
  var scores = [];
  for (var i = 0; i < attempts; i++) {
    var auditData = callPageSpeed(url, strategy);
    scores.push(extractOverallScore(auditData));
  }
  var totalScore = 0;
  for (const score of scores) {
    totalScore += score;
  }
  return totalScore / scores.length; // average
}
```

The script will store the results in a sheet named "Sheet1" (configurable). If you rename it, make sure you update the script as well.

You can test the script by opening the **Select function** menu, selecting **monitor**, and clicking the **Run** button. For your first run, you'll need to authorize the script to run the API. If all goes well, you can open up your sheet to see the results.

### Run it on a schedule

Right now, you can run the PSI test with the click of a button. But ideally the test would run every day automatically. Luckily, we can configure triggers to run our script:

1.  Return to the **Google Apps Script** editor
2.  Navigate to **Edit > Current project's triggers**
3.  Configure your trigger – if you want to run it daily, use the following config:
    - Run the `monitor` function
    - "Time-driven", "Day timer", Select any hour
4.  Save your trigger

[![](/blog/uploads/2020/10/PSI-Schedule-953x1024.png)](https://www.diogonunes.com/blog/wp-content/uploads/2020/10/PSI-Schedule.png)

That's it. Now your Google Sheet will be updated on a daily basis with your latest PSI scores for your target URLs.

### tl;dr

If you want to copy a Google Sheet that already contains all this (plus a dashboard), you can duplicate [this sheet from Rick Viscomi](https://docs.google.com/spreadsheets/d/1VBHLI7v_07t8iDlagrYgiLaYrKGMTRJRwonZ5I4MPm8/edit?usp=sharing). Go to "File > Make a copy..." to clone it. Everything is now set up for you except for the API key property and the daily trigger. Follow the steps above to set those up.

Don't forget to clear the historical data there and overwrite the sample URLs with your target URLs. Easy!
