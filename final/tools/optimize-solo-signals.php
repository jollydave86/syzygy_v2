<?php
declare(strict_types=1);

/**
 * Optimize Solo Signals portraits into webp variants.
 *
 * Source JPGs/PNGs live in assets/img/solo-signals/.
 * Outputs:
 *   - assets/img/solo-signals/{name}.webp and -250/-400/-800/-1200
 *   - assets/img-optimized/solo-signals/{same}
 *
 * Usage: php tools/optimize-solo-signals.php
 */

$root = dirname(__DIR__);
$srcDir = $root . '/assets/img/solo-signals';
$optDir = $root . '/assets/img-optimized/solo-signals';

if (!is_dir($optDir) && !mkdir($optDir, 0775, true) && !is_dir($optDir)) {
    fwrite(STDERR, "Could not create {$optDir}\n");
    exit(1);
}

$jobs = [
    // source file => canonical basename used by members.php
    'nova-vale.jpg' => 'nova-vale',
    'lyra-static.jpg' => 'lyra-static',
    'lucien-cross.jpg' => 'lucien-cross',
    'kade-null.jpg' => 'kade-null',
    'vanta-ray.jpg' => 'vanta-rey', // member slug spelling
];

$widths = [250, 400, 800, 1200];

function load_image(string $path)
{
    $info = @getimagesize($path);
    if ($info === false) {
        return null;
    }

    return match ($info['mime'] ?? '') {
        'image/jpeg' => @imagecreatefromjpeg($path),
        'image/png' => @imagecreatefrompng($path),
        'image/webp' => @imagecreatefromwebp($path),
        default => null,
    };
}

function write_resized($src, int $targetW, string $dest, int $quality = 82): bool
{
    $sw = imagesx($src);
    $sh = imagesy($src);
    if ($sw < 1 || $sh < 1) {
        return false;
    }

    $ratio = $targetW / $sw;
    if ($ratio > 1) {
        $ratio = 1;
    }
    $tw = (int) max(1, round($sw * $ratio));
    $th = (int) max(1, round($sh * $ratio));

    $out = imagecreatetruecolor($tw, $th);
    imagealphablending($out, true);
    imagesavealpha($out, true);
    $transparent = imagecolorallocatealpha($out, 0, 0, 0, 127);
    imagefill($out, 0, 0, $transparent);
    imagecopyresampled($out, $src, 0, 0, 0, 0, $tw, $th, $sw, $sh);

    $ok = imagewebp($out, $dest, $quality);
    imagedestroy($out);

    return $ok;
}

foreach ($jobs as $sourceName => $baseName) {
    $sourcePath = $srcDir . DIRECTORY_SEPARATOR . $sourceName;
    if (!is_file($sourcePath)) {
        fwrite(STDERR, "Missing source: {$sourceName}\n");
        continue;
    }

    $src = load_image($sourcePath);
    if (!$src) {
        fwrite(STDERR, "Could not decode: {$sourceName}\n");
        continue;
    }

    $mainW = min(1200, imagesx($src));
    $targets = [
        [$srcDir . DIRECTORY_SEPARATOR . $baseName . '.webp', $mainW, 82],
        [$optDir . DIRECTORY_SEPARATOR . $baseName . '.webp', $mainW, 82],
    ];

    foreach ($widths as $width) {
        if ($width > imagesx($src)) {
            continue;
        }
        $targets[] = [$srcDir . DIRECTORY_SEPARATOR . $baseName . '-' . $width . '.webp', $width, 80];
        $targets[] = [$optDir . DIRECTORY_SEPARATOR . $baseName . '-' . $width . '.webp', $width, 80];
    }

    foreach ($targets as [$dest, $width, $quality]) {
        if (!write_resized($src, (int) $width, $dest, (int) $quality)) {
            fwrite(STDERR, "Failed writing {$dest}\n");
            exit(1);
        }
    }

    imagedestroy($src);
    echo "Optimized {$sourceName} -> {$baseName}.webp (+size variants)\n";
}

echo "Done.\n";
