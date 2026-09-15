#!/usr/bin/env python3
"""Ingest labeled MERCH folders from Era 3 Singles EPS dumps.

Looks for a MERCH (or Merch) subfolder in each solo EP directory, maps
filenames by label (cd / vinyl / cassette / shirt), writes compressed
WebP + 400/800/1200 variants into assets/img-optimized/merch/.

Usage:
  python3 tools/ingest-official-merch.py [source-root]
"""

from __future__ import annotations

import sys
from pathlib import Path

from PIL import Image

ROOT = Path(__file__).resolve().parents[1]
DEST = ROOT / "assets" / "img-optimized" / "merch"
VARIANTS = (400, 800, 1200)
QUALITY_MAIN = 82
QUALITY_VARIANT = 80

FOLDER_KEYS = [
    ("occupancy-zero", ("occupancy", "kade")),
    ("no-idle-speed", ("no idle", "noidle", "lucien")),
    ("white-voltage", ("white voltage", "white-voltage", "nova vale")),
    ("deluxe-queen", ("deluxe queen", "deluxe-queen")),
    ("body-clock", ("body clock", "body-clock", "lyra")),
    ("the-shape-i-left", ("shape i left", "the-shape", "ash vex", "ash")),
    ("forever-city", ("forever city", "forever-city")),
]

FORMAT_KEYS = [
    ("double-vinyl", ("double vinyl", "double-vinyl", "2lp", "gatefold", "double_lp")),
    ("vinyl", ("vinyl_single", "single vinyl", "vinyl", "lp")),
    ("cassette", ("cassette", "tape")),
    ("cd", (" merch_cd", "merch_cd", "_cd", " cd", "jewel")),
    ("merch", ("t_shirt", "t-shirt", "tshirt", "apparel", "shirt", "merch_t", "hoodie")),
]


def guess_release_key(folder_name: str) -> str | None:
    hay = folder_name.lower()
    for key, needles in FOLDER_KEYS:
        if any(n in hay for n in needles):
            return key
    return None


def guess_format(filename: str) -> str | None:
    hay = filename.lower().replace("\\", "/")
    # Prefer more specific labels first (double-vinyl before vinyl).
    for key, needles in FORMAT_KEYS:
        if any(n in hay for n in needles):
            return key
    return None


def find_merch_dirs(root: Path) -> list[Path]:
    found: list[Path] = []
    if not root.is_dir():
        return found
    for path in root.rglob("*"):
        if path.is_dir() and path.name.lower() == "merch":
            found.append(path)
    return found


def save_webp_set(img: Image.Image, dest_base: Path) -> None:
    DEST.mkdir(parents=True, exist_ok=True)
    rgb = img.convert("RGB")
    max_w = min(1600, rgb.width)
    if rgb.width > max_w:
        ratio = max_w / rgb.width
        rgb = rgb.resize((max_w, max(1, round(rgb.height * ratio))), Image.Resampling.LANCZOS)
    rgb.save(dest_base.with_suffix(".webp"), "WEBP", quality=QUALITY_MAIN, method=6)
    for w in VARIANTS:
        if w >= rgb.width:
            continue
        ratio = w / rgb.width
        small = rgb.resize((w, max(1, round(rgb.height * ratio))), Image.Resampling.LANCZOS)
        small.save(dest_base.parent / f"{dest_base.name}-{w}.webp", "WEBP", quality=QUALITY_VARIANT, method=6)


def ingest_dir(merch_dir: Path) -> int:
    parent_key = guess_release_key(merch_dir.parent.name) or guess_release_key(str(merch_dir.parent))
    if parent_key is None:
        print(f"skip unlabeled merch dir: {merch_dir}")
        return 0
    count = 0
    for file in sorted(merch_dir.iterdir()):
        if not file.is_file():
            continue
        if file.suffix.lower() not in {".jpg", ".jpeg", ".png", ".webp", ".tif", ".tiff"}:
            continue
        fmt = guess_format(file.name)
        if fmt is None:
            print(f"  unlabeled file: {file.name}")
            continue
        img = Image.open(file)
        dest = DEST / f"{parent_key}-{fmt}"
        save_webp_set(img, dest)
        print(f"  {parent_key}-{fmt} <- {file.name}")
        count += 1
    return count


def candidate_roots() -> list[Path]:
    extra = []
    if len(sys.argv) > 1:
        extra.append(Path(sys.argv[1]))
    extra.extend(
        [
            Path(r"H:/~ Backup C drive - Jan 2026/Music Experiment/avatars/Avatars - Offical/~ SYZYGY.VOID - ERA 3/Singles EPS"),
            Path("/mnt/h/~ Backup C drive - Jan 2026/Music Experiment/avatars/Avatars - Offical/~ SYZYGY.VOID - ERA 3/Singles EPS"),
            Path.home() / "Singles EPS",
            ROOT.parent / "Singles EPS",
            Path("/workspace/Singles EPS"),
        ]
    )
    return extra


def main() -> int:
    ingested = 0
    roots = [p for p in candidate_roots() if p.is_dir()]
    if not roots:
        print("No Singles EPS source root found on this machine.")
        return 2
    for root in roots:
        print(f"Scanning {root}")
        dirs = find_merch_dirs(root)
        print(f"  merch folders: {len(dirs)}")
        for merch_dir in dirs:
            ingested += ingest_dir(merch_dir)
    print(f"Ingested {ingested} labeled merch images")
    return 0 if ingested else 2


if __name__ == "__main__":
    raise SystemExit(main())
