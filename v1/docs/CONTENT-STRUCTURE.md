# SYZYGY.VOID Content Structure

## Purpose

This file explains the real shape of the key content/data files used by the site.

It exists to prevent wrong assumptions between sections.

Not every section uses the same structure.

That matters.

---

## Core Principle

Do not assume tracks, gallery, merch, and site settings all use the same array model.

Each section may be data-driven in a different way.

Future work should respect the actual structure in each file instead of forcing a universal shape.

---

## `data/site.php`

## Role
Stores the site-wide content and high-level display values.

Typical concerns include:
- site metadata
- hero values
- about content
- footer content
- possibly nav labels depending on structure

## Important current truth
The current about section values should remain source of truth unless explicitly changed.

Known current about values:
- eyebrow: `ABOUT THE PROJECT`
- title: `Dark signal. Human emotion. Cinematic pressure.`
- provided text/text_2 copy
- image path: `/assets/img/about-image.jpg`
- stats:
  - `4K+ Tracks`
  - `700+ Active Fans`
  - `7 Eras`

## Editing rule
Only change site-level content intentionally.
Do not casually move section logic into `data/site.php` unless it belongs there.

---

## `data/tracks.php`

## Role
Stores the music/track tab data used by the tracks section.

## Real structure
Tracks are based on:
- `$trackTabs`

Each tab contains:
- `label`
- `tracks`

Each track item contains:
- `title`
- `meta`
- `href`

## Important special logic
A Featured tab exists and uses:
- `randomTracks($featuredPool, 5)`

## Important helper functions
This file may define helpers such as:
- `track()`
- `randomTracks()`

These helper declarations must keep `function_exists` guards.

## Critical warning
Tracks does **not** use a merch/gallery-style releases/items structure.

Do not rewrite it that way.

## Common failure mode
Breaking tracks usually happens when:
- the file gets overwritten
- the helper guards are removed
- the template expects the wrong shape
- tabs/panels no longer map correctly

---

## `sections/tracks-section.php`

## Role
Renders the music section using the real `data/tracks.php` structure.

## Real expectation
This section must render from `$trackTabs`.

It is not a generic section expecting `releases` and `items`.

## Important warning
This file had previously been corrupted/overwritten and had to be restored.

Treat it carefully.

## Editing rule
Only make track-specific changes that respect:
- `$trackTabs`
- each tab label
- each tab track list
- each track item with `title`, `meta`, and `href`

---

## `data/gallery.php`

## Role
Stores the gallery content and filter structure used by the gallery section.

## Expected concerns
Typical values may include:
- filters
- items
- initial counts
- gallery display settings

## Known stable status
Gallery is stable in its normal version.

## Editing rule
Do not rewrite gallery just because tracks or merch changed.
Keep gallery-specific logic isolated.

---

## `sections/gallery-section.php`

## Role
Renders the gallery section from gallery-specific data.

## Known stable status
This section is currently stable.

## Editing rule
Only touch it when the change is specifically about gallery behavior or rendering.

Do not use it as a pattern source for tracks.

---

## `data/merch.php`

## Role
Stores merch content for the merch section.

## Expected concerns
Typical values may include:
- releases
- items
- release keys
- merch metadata
- CTA links

## Known stable status
Merch is currently stable.

## Editing rule
Merch logic should stay merch-specific.
Do not assume tracks follows the same shape.

---

## `sections/merch-section.php`

## Role
Renders the merch area using merch-specific data structures.

## Known stable status
Merch works and is stable.

## Editing rule
Only patch it surgically if a merch-specific issue appears.

---

## `assets/js/main.js`

## Role
Main frontend behavior file.

May contain logic for:
- tabs
- navigation
- lightbox
- section interaction
- buttons/toggles

## Known status
Main JS is working overall.

## Editing rule
Avoid broad rewrites.
Patch the smallest possible area.

---

## `includes/link-helpers.php`

## Role
Centralized link behavior layer.

## Intended responsibilities
- escaping
- internal/external detection
- UTM appending
- target / rel generation
- reusable HTML attributes

## Editing rule
Use guarded helper declarations.
Keep behavior centralized.
Do not duplicate link logic across multiple section templates.

---

## Section Stability Summary

### Stable and should be preserved
- Hero
- About
- Videos
- Gallery
- Merch
- Lightbox
- Mobile nav

### Working again and sensitive
- Tracks
- Track data/helpers
- Track section rendering

---

## Future Content Architecture Already Approved

These are planned later and should follow the same data-driven philosophy:

### Contact
Expected future concerns:
- section anchor
- heading
- intro
- form
- newsletter checkbox
- direct contact block

### Promo module
Expected future concerns:
- fixed positioning
- image
- title
- CTA
- optional randomized batch/list mode
- data-driven config source

### Lyrics system
Expected future concerns:
- lyrics index
- release sub-list
- individual lyrics pages
- likely dedicated data structure or content source per release/song

---

## Final Rule

Before editing any data or section file:
- inspect the real file first
- identify the actual data shape
- do not normalize everything into one pattern
- preserve stable working behavior