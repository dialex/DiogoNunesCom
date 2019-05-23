const csv = require("csv-parser");
const fs = require("fs");
const promisify = require("util");

const readstream = promisify(fs.createReadStream);

async function readCSV() {
  var lines = [];
  await readstream("events.csv")
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
}

main();
