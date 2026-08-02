<?php
declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    header('Content-Type: text/plain; charset=UTF-8');
    echo "CLI only.\n";
    exit(1);
}

$root = dirname(__DIR__);
$srcUrl = 'https://cdn2.suno.ai/c77d0996.jpeg';
$tmp = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'tomorrowline-cover-src.jpg';
$dest = $root . '/assets/img-optimized/gallery/operation-tomorrowline/00 - Playlist Cover.webp';
$backup = $root . '/assets/img-optimized/gallery/operation-tomorrowline/00 - Playlist Cover.old.webp';

$ctx = stream_context_create([
    'http' => [
        'timeout' => 30,
        'header' => "User-Agent: SYZYGY.VOID-Asset-Updater/1.0\r\nAccept: image/*\r\n",
    ],
]);

$bytes = file_get_contents($srcUrl, false, $ctx);
if ($bytes === false || $bytes === '') {
    fwrite(STDERR, "Download failed\n");
    exit(1);
}

file_put_contents($tmp, $bytes);

$src = @imagecreatefromjpeg($tmp);
if (!$src) {
    fwrite(STDERR, "Could not decode JPEG\n");
    exit(1);
}

$sw = imagesx($src);
$sh = imagesy($src);
$size = 360;
$side = min($sw, $sh);
$sx = (int) floor(($sw - $side) / 2);
$sy = (int) floor(($sh - $side) / 2);

$out = imagecreatetruecolor($size, $size);
imagealphablending($out, true);
imagesavealpha($out, true);
imagecopyresampled($out, $src, 0, 0, $sx, $sy, $size, $size, $side, $side);

if (is_file($dest)) {
    copy($dest, $backup);
}

if (!imagewebp($out, $dest, 82)) {
    fwrite(STDERR, "Could not write webp\n");
    exit(1);
}

imagedestroy($src);
imagedestroy($out);
@unlink($tmp);
@unlink($backup);

$info = getimagesize($dest);
echo 'Wrote ' . $dest . PHP_EOL;
echo 'Size: ' . $info[0] . 'x' . $info[1] . ' bytes=' . filesize($dest) . PHP_EOL;
