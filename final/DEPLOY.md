# Production deploy

## Quick path

```bash
php tools/build-production-zip.php
```

Upload the zip from `../dist/syzygyvoid-production-*.zip` to the host and extract so the **document root** is this site root (`index.php` + `.htaccess` visible).

## Host requirements

- PHP 8.0+ (8.1/8.3 preferred)
- `mod_rewrite` + `AllowOverride` for `.htaccess`
- `display_errors = Off` in production
- PHP `mail()` working for the contact form (or wire SMTP later)

## After upload

1. Confirm `config/contact-mail.php` exists with your real `to_email` / `from_email`.
2. **Do not** put a live SMTP password in the zip if you share it. Rotate any password that was ever committed or shared.
3. Spot-check: `/`, `/music`, `/profiles`, `/lyrics`, `/gallery`, `/contact`, one Solo Signal release.

## Intentionally excluded from the production zip

- `tools/` (CLI ingest scripts)
- `previews/`
- Raw Solo Signals source dumps under `assets/img/solo-signals/*/`
- Source JPG masters, `hero-bg.jpg`, `video-modal.js`, README

Local rebuild assets stay in the git working tree; they are not needed on the public host.
