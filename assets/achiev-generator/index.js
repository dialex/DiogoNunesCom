const csv = require("csv-parser");
const fs = require("fs");

function readCSV() {
  var lines = [];
  fs.createReadStream("events.csv")
    .pipe(csv())
    .on("data", row => {
      //console.debug("Read event for " + row["PHOTO"]);
      lines.push(row);
    })
    .on("end", () => {
      console.info(`✅  Imported a total of ${lines.length} events`);
      return lines;
    });
}

function main() {
  var events = readCSV();
  console.log(events);
  while (events.length > 0) {
    let row = events.slice(-2);
    console.log(row);
  }
}

main();
