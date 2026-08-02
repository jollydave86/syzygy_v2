# SYZYGY.VOID Image Optimization

## Purpose

This document explains how the project's image optimization workflow works so it can be re-run later without guesswork.

This optimization workflow was used to convert and reduce the site's image assets into optimized WebP-based production assets.

Important:
- do not re-run blindly
- do not overwrite stable production references without checking paths first
- use this document together with `package.json` and the current image folder structure

---

## Current Project Assumption

The site originally used heavier raw image assets and later generated optimized image assets for better performance.

Known result from the earlier optimization pass:
- original asset folder total was approximately 800 MB
- optimized asset folder total was approximately 85 MB

The optimized assets are intended to support the production website with improved loading/performance.

---

## Source of Truth For Exact Commands

The exact npm package names and script names should come from:

- `package.json`
- `package-lock.json` if present
- any dedicated optimization script file in the repo

Examples of files to check:
- `package.json`
- `scripts/optimize-images.js`
- `tools/optimize-images.js`
- any local PowerShell helper used alongside the npm tool

If the exact script names differ from the examples below, always use the real project files as source of truth.

---

## Likely Workflow Shape

The image optimization workflow generally consists of:

1. keeping raw/source images in a source folder
2. running an npm-based optimization/conversion process
3. outputting optimized WebP assets to a destination folder
4. updating PHP/site references only if needed
5. verifying image totals and size reduction
6. testing the site locally to ensure nothing broke

---

## Likely Folders Involved

These should be adjusted to match the current project reality.

### Possible source folder
- `assets/img/`

### Possible optimized output folder
- `assets/img-optimized/`

Important:
the actual source and destination paths must match the real project structure.

Do not assume a path. Confirm it before re-running.

---

## Required Tools

### Node / npm
The optimization process used npm tooling, so the following are generally required:

- Node.js
- npm
- project `package.json`
- any local optimization script used by the project

### Local environment
Typical local environment:
- Windows
- project inside Laragon site directory
- command line from project root

---

## Install Step

If the project includes a `package.json` for the image optimization workflow, install dependencies from the project root:

```bash
npm install




Run Commands

Use the exact script names from the real package.json.

Placeholder examples only

These are examples and may need to be replaced with the real script names:

npm run optimize:images

or

npm run images

or

node scripts/optimize-images.js





What Needs To Be Updated If Images Are Rebuilt

If the optimization process is run again, check the following:

1. Output folder path

Confirm whether optimized files are being written to:

assets/img-optimized/
or another production asset directory
2. File names

Confirm whether optimized output:

preserves original names
changes extensions only
changes nested folder structure
renames files entirely
3. Site references

If output paths or filenames changed, update references in:

data/site.php
data/gallery.php
data/merch.php
any section template that directly references images
any helper file handling image paths
any JS/lightbox logic that depends on image URLs
4. Fallback logic

If the site uses fallback logic between original and optimized assets, confirm that logic still works.

5. Lazy loading / performance attributes

If images are reintroduced or changed, re-check:

loading="lazy" below the fold
width/height attributes where appropriate
decoding="async"
fetchpriority only for the true hero/LCP image
Verification Commands Used Earlier

The following PowerShell commands were used to compare source and optimized image totals.

Original/source folder total
Get-ChildItem .\assets\img -Recurse -Include *.jpg,*.jpeg,*.png,*.webp | Measure-Object Length -Sum
Optimized folder total
Get-ChildItem .\assets\img-optimized -Recurse -Include *.webp | Measure-Object Length -Sum

These commands are useful for:

checking whether optimization actually happened
comparing folder weight before and after
confirming output volume is reasonable

If the project paths differ, update the paths in these commands accordingly.

Recommended Re-Run Workflow

Use this order:

confirm current source folder
confirm current optimized output folder
confirm exact npm script from package.json
run npm install if dependencies are missing
run the image optimization command
verify output folder contents
compare folder sizes
test the website locally
confirm hero, gallery, merch, and other image-heavy sections still work
only then commit changes
Files To Track In Git

If the optimization workflow is still part of the project, usually track:

package.json
package-lock.json
any image optimization script file
documentation for the workflow

Usually ignore:

node_modules/
Files That May Need Review After Re-Optimization

After re-running image optimization, review:

data/site.php
data/gallery.php
data/merch.php
any helper controlling image path selection
any hardcoded asset paths in section templates
any CSS background image paths
any JS/lightbox references to full-size images
Change Control Notes

Do not re-run the optimization process in the middle of unrelated feature work unless needed.

Good times to re-run:

before a performance pass
after adding a large batch of new art
before a deployment phase
when standardizing asset structure

Bad times to re-run:

during a fragile template/debugging session
when multiple section bugs are still unresolved
when output paths are unclear