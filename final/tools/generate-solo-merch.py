#!/usr/bin/env python3
"""Generate compressed merch WebP mockups from official playlist covers."""

from __future__ import annotations

import math
from pathlib import Path

from PIL import Image, ImageDraw, ImageFilter, ImageEnhance

ROOT = Path(__file__).resolve().parents[1]
OPT = ROOT / "assets" / "img-optimized"
MERCH = OPT / "merch"

RELEASES = [
    ("no-idle-speed", "NO IDLE SPEED", OPT / "gallery" / "no-idle-speed" / "00 - Playlist Cover.webp"),
    ("white-voltage", "WHITE VOLTAGE", OPT / "gallery" / "white-voltage" / "00 - Playlist Cover.webp"),
    ("deluxe-queen", "DELUXE QUEEN", OPT / "gallery" / "deluxe-queen" / "00 - Playlist Cover.webp"),
    ("body-clock", "BODY CLOCK", OPT / "gallery" / "body-clock" / "00 - Playlist Cover.webp"),
    ("the-shape-i-left", "THE SHAPE I LEFT", OPT / "gallery" / "the-shape-i-left" / "00 - Playlist Cover.webp"),
    ("occupancy-zero", "OCCUPANCY ZERO", OPT / "gallery" / "occupancy-zero" / "00 - Playlist Cover.webp"),
]

VARIANTS = (400, 800, 1200)


def noise(size: tuple[int, int], base: tuple[int, int, int], amp: int = 18) -> Image.Image:
    w, h = size
    grain = Image.effect_noise((max(80, w // 8), max(50, h // 8)), 32).convert("L").resize(size, Image.Resampling.BILINEAR)
    canvas = Image.new("RGB", size, base)
    overlay = Image.merge("RGB", (grain, grain, grain))
    return Image.blend(canvas, overlay, 0.12).filter(ImageFilter.GaussianBlur(0.4))


def fit_cover(cover: Image.Image, box: tuple[int, int]) -> Image.Image:
    img = cover.convert("RGB")
    img.thumbnail(box, Image.Resampling.LANCZOS)
    canvas = Image.new("RGB", box, (8, 10, 14))
    x = (box[0] - img.width) // 2
    y = (box[1] - img.height) // 2
    canvas.paste(img, (x, y))
    return canvas


def disc(cover: Image.Image, diameter: int) -> Image.Image:
    art = fit_cover(cover, (diameter, diameter)).convert("RGBA")
    mask = Image.new("L", (diameter, diameter), 0)
    d = ImageDraw.Draw(mask)
    d.ellipse((1, 1, diameter - 2, diameter - 2), fill=255)
    c = diameter // 2
    r = max(10, diameter // 14)
    d.ellipse((c - r, c - r, c + r, c + r), fill=0)
    art.putalpha(mask)
    ring = ImageDraw.Draw(art)
    ring.ellipse((2, 2, diameter - 3, diameter - 3), outline=(237, 190, 114, 90), width=2)
    ring.ellipse((c - r - 4, c - r - 4, c + r + 4, c + r + 4), outline=(30, 30, 32, 255), width=3)
    return art


def save_set(img: Image.Image, dest_base: Path) -> None:
    MERCH.mkdir(parents=True, exist_ok=True)
    rgb = img.convert("RGB")
    rgb.save(dest_base.with_suffix(".webp"), "WEBP", quality=84, method=6)
    for w in VARIANTS:
        if w >= rgb.width:
            continue
        ratio = w / rgb.width
        h = max(1, round(rgb.height * ratio))
        small = rgb.resize((w, h), Image.Resampling.LANCZOS)
        small.save(dest_base.parent / f"{dest_base.name}-{w}.webp", "WEBP", quality=82, method=6)


def make_cd(cover: Image.Image, title: str) -> Image.Image:
    W, H = 1400, 900
    bg = noise((W, H), (18, 16, 22), 22)
    bg = ImageEnhance.Contrast(bg).enhance(1.15)
    canvas = bg.convert("RGBA")
    sleeve = fit_cover(cover, (520, 520)).convert("RGBA")
    # jewel left
    left = Image.new("RGBA", (560, 560), (20, 22, 28, 255))
    left.paste(sleeve, (20, 20))
    d = ImageDraw.Draw(left)
    d.rectangle((16, 16, 543, 543), outline=(237, 190, 114, 70), width=2)
    # tray + disc
    right = Image.new("RGBA", (560, 560), (28, 30, 36, 255))
    disk = disc(cover, 430)
    right.paste(disk, (65, 65), disk)
    canvas.paste(left, (90, 170), left)
    canvas.paste(right, (740, 170), right)
    return canvas


def make_vinyl(cover: Image.Image, title: str) -> Image.Image:
    W, H = 1200, 1200
    bg = noise((W, H), (16, 14, 18), 20).convert("RGBA")
    sleeve = fit_cover(cover, (620, 620)).convert("RGBA")
    vinyl = disc(cover, 640)
    bg.paste(vinyl, (430, 280), vinyl)
    bg.paste(sleeve, (110, 260), sleeve)
    d = ImageDraw.Draw(bg)
    d.rectangle((108, 258, 732, 882), outline=(237, 190, 114, 80), width=2)
    return bg


def make_double(cover: Image.Image, title: str) -> Image.Image:
    W, H = 1500, 1000
    bg = noise((W, H), (14, 16, 20), 18).convert("RGBA")
    a = fit_cover(cover, (520, 520)).convert("RGBA")
    b = ImageEnhance.Brightness(fit_cover(cover, (520, 520))).enhance(0.72).convert("RGBA")
    bg.paste(b, (820, 240), b)
    bg.paste(a, (160, 240), a)
    d = ImageDraw.Draw(bg)
    d.rectangle((158, 238, 682, 762), outline=(237, 190, 114, 70), width=2)
    d.rectangle((818, 238, 1342, 762), outline=(237, 190, 114, 40), width=2)
    return bg


def make_cassette(cover: Image.Image, title: str) -> Image.Image:
    W, H = 1400, 900
    bg = noise((W, H), (20, 18, 16), 16).convert("RGBA")
    body = Image.new("RGBA", (920, 560), (12, 12, 14, 255))
    sticker = fit_cover(cover, (860, 360)).convert("RGBA")
    body.paste(sticker, (30, 30), sticker)
    d = ImageDraw.Draw(body)
    d.rounded_rectangle((40, 410, 880, 530), radius=40, outline=(80, 80, 86, 255), width=4)
    d.ellipse((220, 430, 320, 510), outline=(237, 190, 114, 120), width=3)
    d.ellipse((600, 430, 700, 510), outline=(237, 190, 114, 120), width=3)
    bg.paste(body, (240, 170), body)
    return bg


def make_apparel(cover: Image.Image, title: str) -> Image.Image:
    W, H = 1000, 1200
    bg = noise((W, H), (22, 20, 24), 14).convert("RGBA")
    shirt = Image.new("RGBA", (640, 820), (10, 10, 12, 255))
    print_ = fit_cover(cover, (320, 320)).convert("RGBA")
    shirt.paste(print_, (160, 180), print_)
    d = ImageDraw.Draw(shirt)
    d.rectangle((156, 176, 484, 504), outline=(237, 190, 114, 90), width=2)
    # sleeves
    d.polygon([(0, 40), (90, 40), (40, 260), (0, 240)], fill=(10, 10, 12, 255))
    d.polygon([(640, 40), (550, 40), (600, 260), (640, 240)], fill=(10, 10, 12, 255))
    bg.paste(shirt, (180, 200), shirt)
    return bg


def variants_for_existing() -> None:
    for path in sorted(MERCH.glob("*.webp")):
        name = path.name
        if any(name.endswith(f"-{w}.webp") for w in VARIANTS):
            continue
        stem = path.stem
        try:
            img = Image.open(path).convert("RGB")
        except Exception as exc:
            print("skip", path.name, exc)
            continue
        for w in VARIANTS:
            dest = MERCH / f"{stem}-{w}.webp"
            if dest.exists():
                continue
            if w >= img.width:
                continue
            ratio = w / img.width
            h = max(1, round(img.height * ratio))
            small = img.resize((w, h), Image.Resampling.LANCZOS)
            small.save(dest, "WEBP", quality=80, method=6)
            print("variant", dest.name)


def main() -> None:
    """Solo EP merch is photoreal — keep this entry point from overwriting with flat mockups."""
    import subprocess
    import sys

    photoreal = Path(__file__).with_name("generate-photoreal-merch.py")
    subprocess.check_call([sys.executable, str(photoreal)])
    variants_for_existing()


if __name__ == "__main__":
    main()
