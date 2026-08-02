# SYZYGY.VOID — final/

Multi-page PHP rebuild of syzygyvoid.ca.

## Local (Laragon)

Point the site root at `final/` (or open `/syzygy_v2/final/`). Apache needs `mod_rewrite` and `.htaccess` allowed.

Clean URLs (no `.php` / `.html`):

- `/` `/about` `/music` `/music/{slug}`
- `/profiles` `/profiles/{slug}`
- `/lyrics` `/lyrics/{slug}`
- `/gallery` `/merch`
- `/blog` `/blog/{slug}`
- `/contact` `/booking`

## Design review

- Canvas: open `syzygy-design-review.canvas.tsx` beside chat
- HTML stubs: [`previews/`](previews/)

## Easy edits

| What | File |
|------|------|
| Nav, hero, highlights, artist links | `data/site.php` |
| Release meta + platform URLs | `data/releases.php` |
| Track Suno links | `data/tracks.php` |
| Solo Signals + member platform URLs | `data/members.php` |
| Blog journals | `data/blog.php` |
| Lyrics overrides | `data/lyrics.php` (`$lyricsOverrides`) |
| Merch copy | `data/merch.php` |
| Contact mail | `config/contact-mail.php` |

Empty platform URL fields show **Soon** in the UI.

## Canon rules

- Ready profiles: Nova, Ash, Lyra, Lucien (`NO IDLE SPEED`)
- Coming soon: Vanta, Kade (no invented tracklists)
- Forever Land: teaser only, not in public catalog
