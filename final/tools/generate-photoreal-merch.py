#!/usr/bin/env python3
"""Composite Solo Signals covers onto photoreal Monument Zero merch templates."""

from __future__ import annotations

from pathlib import Path

import numpy as np
from PIL import Image, ImageChops, ImageDraw, ImageEnhance, ImageFilter

ROOT = Path(__file__).resolve().parents[1]
OPT = ROOT / "assets" / "img-optimized"
MERCH = OPT / "merch"
TPL = MERCH

RELEASES = [
    ("occupancy-zero", OPT / "gallery" / "occupancy-zero"),
    ("no-idle-speed", OPT / "gallery" / "no-idle-speed"),
    ("white-voltage", OPT / "gallery" / "white-voltage"),
    ("deluxe-queen", OPT / "gallery" / "deluxe-queen"),
    ("body-clock", OPT / "gallery" / "body-clock"),
    ("the-shape-i-left", OPT / "gallery" / "the-shape-i-left"),
    ("forever-city", OPT / "gallery" / "forever-city"),
]

VARIANTS = (400, 800, 1200)

# Pixel quads on 1000-wide Monument Zero product photos. TL, TR, BR, BL.
TEMPLATES = {
    "cd": {
        "file": "monument-zero-cd.webp",
        "cover": [(164, 142), (480, 140), (482, 496), (160, 498)],
        "spine": [(104, 140), (166, 138), (160, 504), (100, 500)],
        "disc": (678, 318, 158),
        "hub": (678, 318, 24),
    },
    "vinyl": {
        "file": "monument-zero-vinyl.webp",
        "cover": [(116, 86), (618, 88), (616, 592), (114, 590)],
        "label": (700, 342, 90),
        "hub": (700, 342, 14),
    },
    "cassette": {
        "file": "monument-zero-cassette.webp",
        "cover": [(146, 100), (414, 108), (406, 540), (138, 532)],
        "spine": [(108, 100), (150, 96), (140, 536), (100, 530)],
        "label": [(438, 214), (872, 218), (868, 402), (434, 398)],
        "reels": [(538, 318, 38), (762, 320, 38)],
    },
    "double-vinyl": {
        "file": "monument-zero-double-vinyl.webp",
        "left": [(80, 64), (500, 66), (498, 402), (78, 400)],
        "right": [(498, 66), (906, 74), (904, 404), (496, 402)],
        "label_l": (318, 538, 80),
        "label_r": (686, 550, 80),
        "hub_l": (318, 538, 12),
        "hub_r": (684, 552, 12),
    },
    "merch": {
        "file": "monument-zero-merch.webp",
        "print": [(238, 318), (762, 318), (762, 812), (238, 812)],
    },
}


def find_coeffs(src_pts, dst_pts):
    """PIL PERSPECTIVE coeffs mapping dest pixels back to source."""
    matrix = []
    for (xs, ys), (xd, yd) in zip(src_pts, dst_pts):
        matrix.append([xd, yd, 1, 0, 0, 0, -xs * xd, -xs * yd])
        matrix.append([0, 0, 0, xd, yd, 1, -ys * xd, -ys * yd])
    a = np.array(matrix, dtype=np.float64)
    b = np.array(src_pts, dtype=np.float64).reshape(8)
    return np.linalg.lstsq(a, b, rcond=None)[0]


def fit_cover(cover: Image.Image, box: tuple[int, int]) -> Image.Image:
    img = cover.convert("RGB")
    tw, th = box
    src_ratio = img.width / img.height
    dest_ratio = tw / th
    if src_ratio > dest_ratio:
        new_h = th
        new_w = max(1, round(th * src_ratio))
    else:
        new_w = tw
        new_h = max(1, round(tw / src_ratio))
    img = img.resize((new_w, new_h), Image.Resampling.LANCZOS)
    left = max(0, (new_w - tw) // 2)
    top = max(0, (new_h - th) // 2)
    return img.crop((left, top, left + tw, top + th))


def quad_mask(size: tuple[int, int], quad, feather: int = 2) -> Image.Image:
    mask = Image.new("L", size, 0)
    ImageDraw.Draw(mask).polygon(quad, fill=255)
    if feather > 0:
        mask = mask.filter(ImageFilter.GaussianBlur(feather))
    return mask


def studio_luma(template: Image.Image) -> np.ndarray:
    """Blur away printed artwork so only photographed room/product light remains."""
    blurred = template.filter(ImageFilter.GaussianBlur(36))
    return np.array(blurred.convert("L"), dtype=np.float32)


def relight(warped: Image.Image, template: Image.Image, mask: Image.Image, luma: np.ndarray | None = None) -> Image.Image:
    art = np.array(warped.convert("RGB"), dtype=np.float32)
    photo = np.array(template.convert("RGB"), dtype=np.float32)
    alpha = np.array(mask, dtype=np.float32) / 255.0
    if luma is None:
        luma = studio_luma(template)
    region = luma[alpha > 0.25]
    mid = float(np.median(region)) if region.size else 140.0
    factor = np.clip(luma / max(mid, 40.0), 0.72, 1.22)[:, :, None]
    lit = np.clip(art * factor, 0, 255)
    mixed = photo * (1.0 - alpha[:, :, None]) + lit * alpha[:, :, None]
    return Image.fromarray(mixed.astype(np.uint8), "RGB")


def blank_quad(base: Image.Image, quad, color: tuple[int, int, int] = (10, 10, 12)) -> Image.Image:
    img = base.copy()
    ImageDraw.Draw(img).polygon([(int(x), int(y)) for x, y in quad], fill=color)
    return img


def blank_circle(base: Image.Image, cx: int, cy: int, radius: int, color: tuple[int, int, int] = (10, 10, 12)) -> Image.Image:
    img = base.copy()
    ImageDraw.Draw(img).ellipse((cx - radius, cy - radius, cx + radius, cy + radius), fill=color)
    return img


def paste_quad(base: Image.Image, art: Image.Image, quad, feather: int = 2, light: Image.Image | None = None) -> Image.Image:
    dst = [(float(x), float(y)) for x, y in quad]
    src = [(0.0, 0.0), (float(art.width), 0.0), (float(art.width), float(art.height)), (0.0, float(art.height))]
    coeffs = find_coeffs(src, dst)
    warped = art.convert("RGB").transform(
        base.size, Image.Transform.PERSPECTIVE, coeffs, Image.Resampling.BICUBIC
    )
    mask = quad_mask(base.size, quad, feather=feather)
    luma = studio_luma(light if light is not None else base)
    return relight(warped, base, mask, luma=luma)


def paste_circle(base: Image.Image, art: Image.Image, cx: int, cy: int, radius: int, hole: int = 0, light: Image.Image | None = None) -> Image.Image:
    d = radius * 2
    fitted = fit_cover(art, (d, d))
    canvas = Image.new("RGB", base.size, (0, 0, 0))
    canvas.paste(fitted, (cx - radius, cy - radius))
    mask = Image.new("L", base.size, 0)
    draw = ImageDraw.Draw(mask)
    draw.ellipse((cx - radius, cy - radius, cx + radius, cy + radius), fill=255)
    if hole > 0:
        draw.ellipse((cx - hole, cy - hole, cx + hole, cy + hole), fill=0)
    mask = mask.filter(ImageFilter.GaussianBlur(1.2))
    luma = studio_luma(light if light is not None else base)
    return relight(canvas, base, mask, luma=luma)


def restore_circle(base: Image.Image, original: Image.Image, cx: int, cy: int, radius: int) -> Image.Image:
    mask = Image.new("L", base.size, 0)
    ImageDraw.Draw(mask).ellipse((cx - radius, cy - radius, cx + radius, cy + radius), fill=255)
    mask = mask.filter(ImageFilter.GaussianBlur(0.8))
    return Image.composite(original, base, mask)


def spine_art(cover: Image.Image) -> Image.Image:
    strip_w = max(48, cover.width // 14)
    strip = cover.crop((0, 0, strip_w, cover.height)).convert("RGB")
    return strip.transpose(Image.Transpose.ROTATE_90)


def inner_art(cover: Image.Image, alt: Image.Image | None) -> Image.Image:
    src = alt if alt is not None else cover
    return ImageEnhance.Brightness(src.convert("RGB")).enhance(0.78)


def cover_path(folder: Path) -> Path:
    for name in ("00 - Playlist Cover.webp", "00-playlist-cover.webp"):
        candidate = folder / name
        if candidate.is_file():
            return candidate
    matches = sorted(folder.glob("00*"))
    if not matches:
        raise FileNotFoundError(folder)
    return matches[0]


def alt_path(folder: Path) -> Path | None:
    matches = sorted(p for p in folder.glob("02-*") if "-400" not in p.name and "-800" not in p.name and "-1200" not in p.name)
    return matches[0] if matches else None


def save_set(img: Image.Image, dest_base: Path) -> None:
    MERCH.mkdir(parents=True, exist_ok=True)
    rgb = img.convert("RGB")
    rgb.save(dest_base.with_suffix(".webp"), "WEBP", quality=84, method=6)
    for width in VARIANTS:
        if width >= rgb.width:
            continue
        ratio = width / rgb.width
        height = max(1, round(rgb.height * ratio))
        small = rgb.resize((width, height), Image.Resampling.LANCZOS)
        small.save(dest_base.parent / f"{dest_base.name}-{width}.webp", "WEBP", quality=82, method=6)


def make_cd(cover: Image.Image, tpl: Image.Image) -> Image.Image:
    spec = TEMPLATES["cd"]
    img = blank_quad(tpl.copy(), spec["cover"])
    img = blank_quad(img, spec["spine"])
    cx, cy, r = spec["disc"]
    img = blank_circle(img, cx, cy, r)
    img = paste_quad(img, cover, spec["cover"], feather=0, light=tpl)
    img = paste_quad(img, spine_art(cover), spec["spine"], feather=0, light=tpl)
    img = paste_circle(img, cover, cx, cy, r, hole=spec["hub"][2], light=tpl)
    hx, hy, hr = spec["hub"]
    return restore_circle(img, tpl, hx, hy, hr)


def make_vinyl(cover: Image.Image, tpl: Image.Image) -> Image.Image:
    spec = TEMPLATES["vinyl"]
    img = blank_quad(tpl.copy(), spec["cover"])
    cx, cy, r = spec["label"]
    img = blank_circle(img, cx, cy, r + 8)
    img = paste_quad(img, cover, spec["cover"], feather=0, light=tpl)
    img = paste_circle(img, cover, cx, cy, r, hole=spec["hub"][2], light=tpl)
    return img


def make_cassette(cover: Image.Image, tpl: Image.Image) -> Image.Image:
    spec = TEMPLATES["cassette"]
    img = blank_quad(tpl.copy(), spec["cover"])
    img = blank_quad(img, spec["spine"])
    img = blank_quad(img, spec["label"])
    img = paste_quad(img, cover, spec["cover"], feather=0, light=tpl)
    img = paste_quad(img, spine_art(cover), spec["spine"], feather=0, light=tpl)
    landscape = fit_cover(cover, (1200, 520))
    img = paste_quad(img, landscape, spec["label"], feather=0, light=tpl)
    return img


def make_double(cover: Image.Image, alt: Image.Image | None, tpl: Image.Image) -> Image.Image:
    spec = TEMPLATES["double-vinyl"]
    img = blank_quad(tpl.copy(), spec["left"])
    img = blank_quad(img, spec["right"])
    lx, ly, lr = spec["label_l"]
    rx, ry, rr = spec["label_r"]
    img = blank_circle(img, lx, ly, lr + 6)
    img = blank_circle(img, rx, ry, rr + 6)
    img = paste_quad(img, cover, spec["left"], feather=0, light=tpl)
    img = paste_quad(img, inner_art(cover, alt), spec["right"], feather=0, light=tpl)
    img = paste_circle(img, cover, lx, ly, lr, hole=spec["hub_l"][2], light=tpl)
    img = paste_circle(img, cover, rx, ry, rr, hole=spec["hub_r"][2], light=tpl)
    return img


def make_apparel(cover: Image.Image, tpl: Image.Image) -> Image.Image:
    spec = TEMPLATES["merch"]
    print_art = fit_cover(cover, (1000, 1000))
    img = blank_quad(tpl.copy(), spec["print"])
    return paste_quad(img, print_art, spec["print"], feather=1, light=tpl)


def main() -> None:
    loaded = {key: Image.open(TPL / spec["file"]).convert("RGB") for key, spec in TEMPLATES.items()}
    for key, folder in RELEASES:
        if not folder.is_dir():
            print("MISSING FOLDER", folder)
            continue
        cover = Image.open(cover_path(folder)).convert("RGB")
        alt_file = alt_path(folder)
        alt = Image.open(alt_file).convert("RGB") if alt_file else None
        makers = {
            "cd": lambda: make_cd(cover, loaded["cd"]),
            "vinyl": lambda: make_vinyl(cover, loaded["vinyl"]),
            "cassette": lambda: make_cassette(cover, loaded["cassette"]),
            "double-vinyl": lambda: make_double(cover, alt, loaded["double-vinyl"]),
            "merch": lambda: make_apparel(cover, loaded["merch"]),
        }
        for fmt, fn in makers.items():
            img = fn()
            dest = MERCH / f"{key}-{fmt}"
            save_set(img, dest)
            print("wrote", dest.name)


if __name__ == "__main__":
    main()
