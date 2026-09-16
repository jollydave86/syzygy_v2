#!/usr/bin/env python3
"""Write missing 400/800/1200 WebP variants for merch product shots.

Does not re-encode the canonical `{key}-{format}.webp` file. Speed-first
variants match the gallery ingest formula (q80, LANCZOS).
"""

from __future__ import annotations

from pathlib import Path

from PIL import Image

ROOT = Path(__file__).resolve().parents[1]
DEST = ROOT / "assets" / "img-optimized" / "merch"
VARIANTS = (400, 800, 1200)
QUALITY_VARIANT = 80


def is_canonical(path: Path) -> bool:
    name = path.name
    if not name.endswith(".webp"):
        return False
    stem = name[: -len(".webp")]
    return not stem.endswith(("-400", "-800", "-1200"))


def write_variants(src: Path) -> int:
    written = 0
    img = Image.open(src).convert("RGB")
    for width in VARIANTS:
        dest = src.with_name(f"{src.stem}-{width}.webp")
        if dest.exists():
            continue
        if width >= img.width:
            continue
        ratio = width / img.width
        small = img.resize((width, max(1, round(img.height * ratio))), Image.Resampling.LANCZOS)
        small.save(dest, "WEBP", quality=QUALITY_VARIANT, method=6)
        written += 1
    return written


def main() -> int:
    if not DEST.is_dir():
        print(f"Missing merch dir: {DEST}")
        return 2
    total = 0
    files = sorted(p for p in DEST.glob("*.webp") if is_canonical(p))
    for src in files:
        n = write_variants(src)
        if n:
            print(f"  {src.name}: +{n} variants")
        total += n
    print(f"Wrote {total} merch size variants")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
