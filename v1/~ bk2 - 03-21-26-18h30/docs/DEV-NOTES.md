# SYZYGY.VOID Dev Notes

## Purpose

Practical development notes for local work, debugging, and safe iteration.

This is the place for real-world reminders that do not need to clutter the main README.

---

## Local Environment

Current local workflow is based on:
- Windows
- Laragon
- VS Code
- browser refresh testing
- localhost-first development

Typical local project path example:
`D:\laragon\www\...`

---

## Current Development Habit To Preserve

Build locally first.
Do not push unstable code live.
Keep changes small and test after each change.

Preferred pattern:
1. inspect current file
2. patch the smallest necessary area
3. reload locally
4. verify stable sections still work
5. commit only when stable

---

## Stable Sections To Leave Alone Unless Needed

These are currently working and should not be touched casually:
- videos
- gallery
- merch
- lightbox
- mobile nav

The existence of a working section is a reason to avoid “cleanup rewrites.”

---

## Tracks Reminder

Tracks is not generic.

It uses:
- `$trackTabs`
- helper functions such as `track()` and `randomTracks()`
- guarded function declarations

Never treat it like merch/gallery.

---

## Common Mistakes To Avoid

- assuming all section data files use the same structure
- rewriting a stable section during unrelated work
- removing `function_exists` guards
- changing multiple layers at once
- touching assets/media files without explicit need
- letting a “refactor” become a hidden rewrite

---

## Good Safe Workflow For Feature Work

Example for a new feature:
1. confirm target section
2. confirm section data shape
3. confirm helper dependencies
4. patch only that feature
5. verify:
   - tracks still render
   - videos still render
   - gallery still renders
   - merch still renders
6. commit stable state

---

## Current Feature Priority

Next actual feature:
- Contact section

Then:
- back-to-top button
- floating bottom-left newly published promo module
- lyrics architecture/page system

Later:
- hero video in CMS phase

---

## Notes On Documentation

This project should be documented before deployment work expands.

Documentation set should include:
- README
- project digest
- content structure
- deploy notes
- roadmap
- changelog

That way future work is safer and easier to transfer into a fresh GPT or a Git workflow.

---

## Notes On Assets

Do not touch assets/media files unless explicitly requested.

When working on structural or PHP changes, assume assets are already curated and should not be rewritten by default.

---

## Notes On UTM Links

UTM helper retrofit is already done for tracks and videos.

Treat that as working unless a specific issue appears.

Link helper work should support future:
- merch links
- contact/social links
- promo module links
- lyrics page links

---

## Reminder For Future Me

Keep it boring.
Boring is good.
Boring means stable.
Stable means progress.