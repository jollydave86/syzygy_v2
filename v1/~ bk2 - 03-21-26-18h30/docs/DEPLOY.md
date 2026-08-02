# SYZYGY.VOID Deploy Notes

## Purpose

This file documents the planned Git-to-cPanel deployment workflow for the SYZYGY.VOID site.

The deployment goal is:
develop locally, commit safely, then push live in a controlled way.

---

## Deployment Strategy

Planned workflow:

1. continue working locally
2. initialize and maintain Git locally
3. push to a cPanel-managed Git repository
4. deploy live through cPanel deployment flow

This keeps localhost as the safe build environment and production as the controlled destination.

---

## Local-First Rule

Never use the live site as the primary development environment.

All meaningful edits should be tested locally first.

---

## Recommended Repository Layout

Recommended idea:
- keep the managed Git repository outside the public web root if possible
- deploy from that repository into the live directory

Example concept:
- repo path managed by cPanel
- deployment target is `public_html`

This keeps Git internals separate from served files.

---

## Planned Git Workflow

Typical local rhythm:

1. make changes locally
2. test locally
3. commit locally
4. push to cPanel remote
5. deploy live when ready

High-level Git command pattern:

```bash
git init
git add .
git commit -m "Initial commit"
git remote add live YOUR_CPANEL_REMOTE_URL
git push -u live HEAD