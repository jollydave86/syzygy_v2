const fs = require('fs');
const path = require('path');
const sharp = require('sharp');

const ROOT_DIR = path.resolve(__dirname, '..');
const INPUT_DIR = path.join(ROOT_DIR, 'assets', 'img');
const OUTPUT_DIR = path.join(ROOT_DIR, 'assets', 'img-optimized');

const IMAGE_EXTENSIONS = new Set(['.jpg', '.jpeg', '.png', '.webp']);

function ensureDir(dir) {
  fs.mkdirSync(dir, { recursive: true });
}

function walk(dir) {
  const results = [];
  const items = fs.readdirSync(dir, { withFileTypes: true });

  for (const item of items) {
    const fullPath = path.join(dir, item.name);

    if (item.isDirectory()) {
      results.push(...walk(fullPath));
      continue;
    }

    const ext = path.extname(item.name).toLowerCase();
    if (IMAGE_EXTENSIONS.has(ext)) {
      results.push(fullPath);
    }
  }

  return results;
}

async function processFile(filePath) {
  const relativePath = path.relative(INPUT_DIR, filePath);
  const parsed = path.parse(relativePath);
  const outDir = path.join(OUTPUT_DIR, parsed.dir);

  ensureDir(outDir);

  const metadata = await sharp(filePath).metadata();
  const widths = [400, 800, 1200];

  for (const width of widths) {
    if (metadata.width && width > metadata.width) continue;

    const outputFile = path.join(outDir, `${parsed.name}-${width}.webp`);

    await sharp(filePath)
      .rotate()
      .resize({
        width,
        withoutEnlargement: true,
        fit: 'inside',
      })
      .webp({
        quality: 80,
        effort: 6,
      })
      .toFile(outputFile);
  }

  const mainOutput = path.join(outDir, `${parsed.name}.webp`);

  await sharp(filePath)
    .rotate()
    .resize({
      width: Math.min(metadata.width || 1200, 1200),
      withoutEnlargement: true,
      fit: 'inside',
    })
    .webp({
      quality: 82,
      effort: 6,
    })
    .toFile(mainOutput);

  console.log(`Optimized: ${relativePath}`);
}

async function run() {
  console.log('ROOT_DIR =', ROOT_DIR);
  console.log('INPUT_DIR =', INPUT_DIR);
  console.log('OUTPUT_DIR =', OUTPUT_DIR);

  if (!fs.existsSync(INPUT_DIR)) {
    console.error('Input folder not found:', INPUT_DIR);
    process.exit(1);
  }

  ensureDir(OUTPUT_DIR);

  const files = walk(INPUT_DIR);
  console.log(`Found ${files.length} image(s).`);

  if (!files.length) {
    console.log('No image files found.');
    return;
  }

  for (const file of files) {
    try {
      await processFile(file);
    } catch (error) {
      console.error(`Failed: ${file}`);
      console.error(error.message);
    }
  }

  console.log('Done.');
}

run();