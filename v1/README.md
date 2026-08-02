# SYZYGY.VOID Official Website

Official PHP website for the **SYZYGY.VOID** music project.

This project is built as a modular PHP site with reusable section templates, data-driven content files, split CSS, and vanilla JavaScript behavior. It is being developed locally first and prepared for later Git-based deployment to cPanel.

---

## Project Status

Current site state is stable.

### Working sections/features
- Hero section
- About section
- Music section
- Videos section
- Gallery section
- Merch section
- Lightbox
- Mobile navigation
- Split CSS architecture
- Main JS working overall
- UTM helper retrofit already working for tracks and videos

### Important current constraints
- Videos are stable and must not be broken casually
- Gallery is stable
- Merch is stable
- Tracks are stable again after repair
- Site changes should be surgical, not from-scratch rewrites
- Do not assume all data files share the same shape

---

## Project Stack

### Current stack
- PHP
- HTML
- CSS
- Vanilla JavaScript

### Local development environment
- Laragon on Windows
- VS Code

### Planned deployment path
- Localhost development first
- Git push workflow
- cPanel-hosted repository
- Later deployment into live site directory using Git/cPanel deployment flow

---

## Project Structure

Main files currently important:

- `index.php`
- `data/site.php`
- `data/tracks.php`
- `data/gallery.php`
- `data/merch.php`
- `sections/tracks-section.php`
- `sections/gallery-section.php`
- `sections/merch-section.php`
- `assets/js/main.js`

Other important directories/files may include:

- `assets/css/`
- `assets/img/`
- `assets/img-optimized/`
- `includes/`
- `sections/`
- `data/`

---

## Architecture Overview

The site uses a modular PHP structure:

- **`index.php`** assembles the page
- **`data/*.php`** stores structured content used by sections
- **`sections/*.php`** renders section markup
- **`assets/css/`** contains split stylesheet architecture
- **`assets/js/main.js`** contains frontend interaction logic
- **`includes/`** contains helper files such as link helpers

This structure is intentionally being kept simple and stable so it can later evolve into a more CMS-like architecture.

---

## Important Section Notes

### Hero
- Working
- Future idea: evolve from static image to video during a later CMS phase

### About
- Working
- Current content values should remain source of truth unless explicitly changed

### Music / Tracks
- Working again after previous corruption/fix
- Uses the real `data/tracks.php` structure
- Does **not** use a merch/gallery-style releases/items shape

#### Track data shape
`data/tracks.php` is based on:

- `$trackTabs`

Each tab contains:
- `label`
- `tracks`

Each track contains:
- `title`
- `meta`
- `href`

Important current logic:
- Featured tab exists
- Featured tab uses `randomTracks($featuredPool, 5)`

#### Important protection rules
- `data/tracks.php` must keep `function_exists` guards
- This prevents fatal redeclare errors
- If tracks break, first suspect helper redeclare issues or wrong data assumptions

### Videos
- Stable
- Must remain untouched unless explicitly requested

### Gallery
- Stable
- Normal version is currently working

### Merch
- Stable
- Normal version is currently working

### Lightbox
- Working

### Mobile nav
- Working

---

## Recent Important Fixes

These are critical to remember before future edits:

1. `sections/tracks-section.php` had previously been corrupted/overwritten and was restored
2. `data/tracks.php` uses helper functions like `track()` and `randomTracks()`
3. `data/tracks.php` must keep `function_exists` guards to prevent fatal redeclare errors
4. `tracks-section.php` must use the real `data/tracks.php` structure based on `$trackTabs`
5. Tracks now render properly again
6. Gallery and Merch are stable in their normal versions
7. Videos are stable and must remain untouched
8. UTM helper retrofit is already working for tracks and videos

---

## Link Helpers

The site includes a centralized link helper approach so section files do not each implement their own link logic.

Typical responsibilities:
- safe HTML escaping
- internal vs external detection
- special scheme detection
- UTM appending
- target / rel management
- reusable link attributes for templates

Why this matters:
- prevents duplicated logic
- keeps section files cleaner
- reduces risk of inconsistent link behavior
- helps future sections like Contact and promo modules

---

## Commands / Running the Site

### Local development
This project currently runs as a PHP site in Laragon.

Typical local workflow:

1. Place the project in your Laragon `www` directory
2. Start Laragon
3. Open the local site URL in your browser
4. Edit in VS Code
5. Refresh and test changes locally

### Example local path
```text
D:\laragon\www\your-project-folder