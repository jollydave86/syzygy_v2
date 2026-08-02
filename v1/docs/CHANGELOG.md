# SYZYGY.VOID Changelog

## Purpose

Tracks major site stabilization work and important milestones.

This is not meant to be ultra-granular.
It should record meaningful progress.

---

## Unreleased

### Stabilization and recovery
- restored `sections/tracks-section.php` after corruption/overwrite issues
- re-aligned tracks rendering to the real `data/tracks.php` structure
- confirmed tracks depend on `$trackTabs` rather than merch/gallery-style structures
- preserved helper function guard strategy in `data/tracks.php` to prevent fatal redeclare errors

### Section stability
- stabilized Gallery in its current normal version
- stabilized Merch in its current normal version
- preserved Videos as a protected stable area
- kept Lightbox working
- kept Mobile nav working

### Architecture / helper direction
- established centralized link helper direction
- confirmed UTM helper retrofit working for tracks and videos
- documented safer helper-based approach for future link handling

### Documentation
- added root README documentation
- added project digest documentation
- added content structure documentation
- added deploy notes
- added roadmap documentation
- added practical dev notes

### Roadmap lock-in
- confirmed Contact as next actual feature
- confirmed future back-to-top button
- confirmed future floating newly published promo module
- confirmed future lyrics architecture/page system
- documented longer-term CMS and framework direction
- documented future hero video idea for CMS phase