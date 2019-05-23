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

async function main() {
  var events = await readCSV("events.csv");
  while (events.length > 0) {
    var right = events.pop();
    var left = events.pop();

    if (left === undefined) {
      console.debug(`Events per row (1): ${right["PHOTO"]}`);
    } else {
      console.debug(`Events per row (2): ${left["PHOTO"]} | ${right["PHOTO"]}`);
    }
  }
}

main();
