const csv = require("csv-parser");
const fs = require("fs");

function readCSV(fileName) {
  return new Promise(function(resolve, reject) {
    var lines = [];
    fs.createReadStream(fileName)
      .pipe(csv())
      .on("data", row => {
        //console.debug("Read event for " + row["PHOTO"]);
        if (!row["PHOTO"].startsWith("//")) lines.push(row);
      })
      .on("end", () => {
        console.info(`✅  Imported a total of ${lines.length} events`);
        resolve(lines);
      });
  });
}

function generateColumnHtml(event, isWide) {
  width = isWide ? 12 : 6;
  return `
  <div class="col-lg-${width} text-center">
    <img src="/assets/img/achieves/${
      event["PHOTO"]
    }" class="img-responsive img-centered img-polaroid">
    <br/>
    <p><span class="label label-info">${event["DATE"]}</span> ${
    event["DESC"]
  }</p>
  </div>
  `;
}

function generateRowHtml() {
  var columns = "";
  for (var i = 0; i < arguments.length; i++) {
    columns += arguments[i];
  }

  return `
  <div class="row">
    ${columns}
  </div>
  <br/><br/>
  `;
}

function generateHtmlGrid(events) {
  var htmlGrid = "";

  console.info(`🎨  Generating html grid from events`);
  while (events.length > 0) {
    var isWide, htmlRow;
    var right = events.pop();
    var left = events.pop();
    if (left === undefined) {
      isWide = true;
      //console.debug(`Events per row (1): ${right["PHOTO"]}`);
      var htmlCenter = generateColumnHtml(right, isWide);
      //console.debug("Center column:\n" + htmlCenter);
      var htmlRow = generateRowHtml(htmlCenter);
      //console.debug("Entire row:\n" + htmlRow);
    } else {
      isWide = false;
      //console.debug(`Events per row (2): ${left["PHOTO"]} | ${right["PHOTO"]}`);
      var htmlLeft = generateColumnHtml(left, isWide);
      //console.debug("Left column:\n" + htmlLeft);
      var htmlRight = generateColumnHtml(right, isWide);
      //console.debug("Right column:\n" + htmlRight);
      var htmlRow = generateRowHtml(htmlLeft, htmlRight);
      //console.debug("Entire row:\n" + htmlRow);
    }
    htmlGrid += htmlRow;
  }
  return htmlGrid;
}

function generateHtmlPage(htmlGrid) {
  //TODO
}

async function main() {
  console.info("🏃‍  Started!");
  var events = await readCSV("events.csv");
  var htmlGrid = generateHtmlGrid(events);
  var htmlPage = generateHtmlPage(htmlGrid);
  //console.log(htmlGrid);
}

main();
