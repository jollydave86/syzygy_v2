const fs = require("fs");
const path = require("path");
const sharp = require("sharp");

const sourceFolder = process.argv[2];
const targetFolder = process.argv[3];

const files = {
  "merch_cd.jpg": "the-chapel-protocol-aftermath-cd",
  "merch_vinyl_single.jpg": "the-chapel-protocol-aftermath-vinyl",
  "merch_double_vinyl.jpg": "the-chapel-protocol-aftermath-double-vinyl",
  "merch_cassette.jpg": "the-chapel-protocol-aftermath-cassette",
  "merch_t_shirt.jpg": "the-chapel-protocol-aftermath-merch"
};

const variants = [400, 800, 1200];

async function optimizeOne(sourceName, outputBaseName) {
  const sourcePath = path.join(sourceFolder, sourceName);

  if (!fs.existsSync(sourcePath)) {
    console.warn(`Missing, skipped: ${sourceName}`);
    return;
  }

  const metadata = await sharp(sourcePath).metadata();

  const mainOutput = path.join(targetFolder, `${outputBaseName}.webp`);

  await sharp(sourcePath)
    .rotate()
    .resize({
      width: Math.min(metadata.width || 1600, 1600),
      withoutEnlargement: true,
      fit: "inside"
    })
    .webp({
      quality: 84,
      effort: 6
    })
    .toFile(mainOutput);

  for (const width of variants) {
    const variantOutput = path.join(targetFolder, `${outputBaseName}-${width}.webp`);

    await sharp(sourcePath)
      .rotate()
      .resize({
        width,
        withoutEnlargement: true,
        fit: "inside"
      })
      .webp({
        quality: 82,
        effort: 6
      })
      .toFile(variantOutput);
  }

  console.log(`Optimized: ${sourceName} -> ${outputBaseName}.webp`);
}

async function run() {
  if (!fs.existsSync(sourceFolder)) {
    console.error(`Source folder not found: ${sourceFolder}`);
    process.exit(1);
  }

  if (!fs.existsSync(targetFolder)) {
    fs.mkdirSync(targetFolder, { recursive: true });
  }

  for (const [sourceName, outputBaseName] of Object.entries(files)) {
    try {
      await optimizeOne(sourceName, outputBaseName);
    } catch (error) {
      console.error(`Failed: ${sourceName}`);
      console.error(error.message);
    }
  }

  console.log("Done.");
}

run();
