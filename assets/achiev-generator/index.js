const csv = require("csv-parser");
const fs = require("fs");
const path = require("path");
const cheerio = require("cheerio");

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
        console.info(`✅  Imported a total of ${lines.length} achievs`);
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

function generateHtmlGrid(achievs) {
  var htmlGrid = "";

  console.info(`🎨  Generating html grid from achievements`);
  while (achievs.length > 0) {
    var isWide, htmlRow;
    var right = achievs.pop();
    var left = achievs.pop();
    if (left === undefined) {
      isWide = true;
      //console.debug(`Achievs per row (1): ${right["PHOTO"]}`);
      var htmlCenter = generateColumnHtml(right, isWide);
      //console.debug("Center column:\n" + htmlCenter);
      var htmlRow = generateRowHtml(htmlCenter);
      //console.debug("Entire row:\n" + htmlRow);
    } else {
      isWide = false;
      //console.debug(`Achievs per row (2): ${left["PHOTO"]} | ${right["PHOTO"]}`);
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
  console.info(`🏗  Reading html template`);

  var templatePath = path.resolve(__dirname, "template.html");
  //console.debug("Located at " + templatePath);
  var templateHtml = fs.readFileSync(templatePath, "utf8", function(err, html) {
    if (err) throw err;
    //console.debug("Template read");
  });

  console.info(`👨‍⚕️ Inserting the grid in the template`);
  var $ = cheerio.load(templateHtml);
  $("#achievsGrid").replaceWith(htmlGrid);
  //console.debug($.html());
}

async function main() {
  console.info("🏃‍  Started!");
  var achievs = await readCSV("achievs.csv");
  var htmlGrid = generateHtmlGrid(achievs);
  var htmlPage = generateHtmlPage(htmlGrid);
  //writeToFile(htmlPage, "achievements_new.html");
}

main();
