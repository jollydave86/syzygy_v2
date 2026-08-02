const fs = require("fs");
const path = require("path");
const sharp = require("sharp");

const targetDir = process.argv[2];

if (!targetDir || !fs.existsSync(targetDir)) {
  console.error("Target folder not found:", targetDir);
  process.exit(1);
}

const imageExtensions = new Set([".jpg", ".jpeg", ".png", ".webp"]);
const widths = [400, 800, 1200];

function isGeneratedVariant(fileName) {
  return /-(400|800|1200)\.webp$/i.test(fileName);
}

function shouldProcess(filePath) {
  const parsed = path.parse(filePath);
  const ext = parsed.ext.toLowerCase();

  if (!imageExtensions.has(ext)) return false;
  if (isGeneratedVariant(parsed.base)) return false;
  if (filePath.includes(`${path.sep}_originals_backup${path.sep}`)) return false;

  return true;
}

async function optimizeImage(filePath) {
  const parsed = path.parse(filePath);
  const outputBase = path.join(parsed.dir, parsed.name);
  const metadata = await sharp(filePath).metadata();

  for (const width of widths) {
    if (metadata.width && width > metadata.width) continue;

    const outputFile = `${outputBase}-${width}.webp`;

    await sharp(filePath)
      .rotate()
      .resize({
        width,
        withoutEnlargement: true,
        fit: "inside"
      })
      .webp({
        quality: 80,
        effort: 6
      })
      .toFile(outputFile);
  }

  const mainOutput = `${outputBase}.webp`;

  if (path.resolve(filePath) !== path.resolve(mainOutput)) {
    await sharp(filePath)
      .rotate()
      .resize({
        width: Math.min(metadata.width || 1200, 1200),
        withoutEnlargement: true,
        fit: "inside"
      })
      .webp({
        quality: 82,
        effort: 6
      })
      .toFile(mainOutput);
  }

  console.log(`Optimized: ${path.basename(filePath)}`);
}

async function run() {
  const files = fs
    .readdirSync(targetDir)
    .map((file) => path.join(targetDir, file))
    .filter((filePath) => fs.statSync(filePath).isFile())
    .filter(shouldProcess);

  console.log(`Target folder: ${targetDir}`);
  console.log(`Found ${files.length} source image(s).`);

  for (const file of files) {
    try {
      await optimizeImage(file);
    } catch (error) {
      console.error(`Failed: ${file}`);
      console.error(error.message);
    }
  }

  console.log("Done.");
}

run();
