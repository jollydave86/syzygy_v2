# SYZYGY.VOID Project Digest

## Purpose

This file is the high-trust internal memory document for the SYZYGY.VOID website project.

It exists to preserve the current stable architecture, known fixes, section relationships, and development rules so future edits do not repeat old mistakes.

This is the file to read first before changing anything major.

---

## Current Project Identity

SYZYGY.VOID is the official website for the SYZYGY.VOID music project.

The site is currently a modular PHP website with:
- section templates
- data files
- split CSS
- vanilla JavaScript
- helper-based retrofits
- a localhost-first workflow

The project is intentionally being kept stable and modular so it can later evolve into:
- a more CMS-like architecture
- a reusable framework
- possibly a SaaS/resellable direction long term

---

## Core Stack

Current real stack:
- PHP
- HTML
- CSS
- Vanilla JavaScript

Current local environment:
- Windows
- Laragon
- VS Code

Current deployment direction:
- continue development locally
- later push via Git
- deploy through cPanel-managed Git flow

Important: do not invent build tooling unless it actually exists in the project.

At this stage, the site should be documented honestly as a PHP + CSS + JS project.

---

## Current Stable Site State

The following parts are currently working:

- Hero works
- About works
- Music works again
- Videos work and must not be broken
- Gallery works
- Merch works
- Lightbox works
- Mobile nav exists
- UTM helper retrofit is already done for tracks and videos

This is the baseline stable state.

---

## Critical Project Rule

Preserve the existing working site.

This project has already gone through several rounds of repair and stabilization. Future work should continue from the current working state, not from older broken attempts.

Preferred approach:
- surgical fixes
- exact replacement-ready code
- minimal surface area
- no casual rewrites of stable sections

---

## Main Files Currently Important

Core site files currently important:

- `index.php`
- `data/site.php`
- `data/tracks.php`
- `data/gallery.php`
- `data/merch.php`
- `sections/tracks-section.php`
- `sections/gallery-section.php`
- `sections/merch-section.php`
- `assets/js/main.js`

Other important areas:
- `includes/`
- `assets/css/`
- `assets/img/`
- `assets/img-optimized/` if used as production source
- other section files already working and not to be disturbed casually

---

## What Must Be Protected

Highest sensitivity:
- `data/tracks.php`
- `sections/tracks-section.php`
- shared helper files
- videos section behavior

Stable areas that should not be casually touched:
- videos
- gallery
- merch
- lightbox
- mobile nav

Important principle:
a section being “boring but stable” is better than breaking it with a large cleanup.

---

## Tracks: Most Important Data Warning

Tracks does not use the same data shape as merch or gallery.

This has already caused breakage before.

### Real track structure
`data/tracks.php` is based on:
- `$trackTabs`

Each tab contains:
- `label`
- `tracks`

Each track contains:
- `title`
- `meta`
- `href`

The Featured tab exists and uses:
- `randomTracks($featuredPool, 5)`

### Important helper detail
`data/tracks.php` uses helper functions such as:
- `track()`
- `randomTracks()`

These helper declarations must keep `function_exists` guards to prevent fatal redeclare errors.

### Golden rule
Never rewrite tracks using a merch/gallery releases/items structure.

That is incorrect for this project.

---

## Recent Important Fixes Already Accomplished

These are major fixes that must be remembered:

1. `sections/tracks-section.php` had been corrupted/overwritten and was restored
2. `data/tracks.php` uses helper functions like `track()` and `randomTracks()`
3. `data/tracks.php` must keep `function_exists` guards to prevent fatal redeclare errors
4. `tracks-section.php` must use the real `data/tracks.php` structure, which is based on `$trackTabs`
5. tracks now render properly again
6. Gallery and Merch are stable in their normal versions
7. Videos are stable and must remain untouched
8. UTM helper retrofit is already done for tracks and videos

---

## Link Helper Direction

The project now benefits from a centralized link helper approach.

Purpose of link helpers:
- safe HTML escaping
- internal vs external URL detection
- special scheme detection
- tracked URL generation
- target / rel consistency
- reusable link attributes for templates

Why this matters:
- prevents repeated link logic
- reduces template clutter
- makes future features safer
- supports tracks, videos, merch, contact, promo, and lyrics pages consistently

---

## Current Development Philosophy

This project should be edited with caution and respect for stability.

### Good changes
- exact targeted changes
- helper-based improvements
- section-specific patches
- data-driven additions
- replacement-ready code blocks
- preserving working areas

### Bad changes
- broad rewrites
- assumptions that all sections share the same data model
- touching videos when the task is unrelated
- redefining helpers without guards
- changing stable markup without a reason
- converting section logic to a totally different structure

---

## What To Suspect When Something Breaks

### If a section disappears
First suspects:
- overwritten PHP file
- mismatched data shape
- bad conditional rendering logic
- broken include path
- malformed markup

### If there is a fatal redeclare error
First suspects:
- repeated helper declarations
- missing `function_exists` guards
- duplicated utility functions

### If tracks break
First suspects:
- wrong data structure assumptions
- broken `data/tracks.php`
- helper redeclare issues
- track tabs/panels no longer matching expected rendering logic

---

## Working Assumptions Going Forward

These assumptions are currently correct unless explicitly changed later:

- the safest way to help is to inspect current files first, then patch only what is needed
- if a section disappears, first suspect overwritten PHP files or mismatched data shape
- if there is a fatal redeclare error, suspect `data/tracks.php` or repeated helper declarations
- videos are stable and should remain untouched unless explicitly requested
- Contact is the next actual feature to build
- all future additions should preserve the current stable site structure

---

## Approved Roadmap Items

### Immediate next feature
1. Contact section

### After Contact
2. Back-to-top button
   - bottom right

3. Floating newly published promo module
   - bottom left
   - image
   - title
   - CTA
   - data-driven
   - may randomize from a batch/list of items

4. Dedicated Lyrics architecture/page system
   - main lyrics index
   - release sub-list
   - individual lyrics page

### Longer term
5. evolve toward CMS-like structure
6. later possibly evolve toward SaaS/resellable framework direction
7. hero may eventually evolve from static image to video during CMS phase

---

## Future Editing Rules

Before editing any file:

1. confirm which section is actually being changed
2. verify the real data shape for that section
3. check if the section is already stable and unrelated
4. avoid touching videos unless explicitly requested
5. confirm helper functions are not being redeclared
6. prefer small changes over large rewrites
7. keep output replacement-ready

---

## Short Version For Future Me

The site is stable now.

Do not get fancy and break it.

Tracks is special.
Videos are fragile by policy.
Gallery and Merch are stable.
Use helpers.
Patch surgically.
Document before deploying.