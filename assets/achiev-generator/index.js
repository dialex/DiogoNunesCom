const csv = require("csv-parser");
const fs = require("fs");

function readCSV(fileName) {
  return new Promise(function(resolve, reject) {
    var lines = [];
    fs.createReadStream(fileName)
      .pipe(csv())
      .on("data", row => {
        //console.debug("Read event for " + row["PHOTO"]);
        lines.push(row);
      })
      .on("end", () => {
        console.info(`✅  Imported a total of ${lines.length} events`);
        resolve(lines);
      });
  });
}

async function main() {
  var events = await readCSV("events.csv");
  console.log(events.slice(-2));
}

main();
