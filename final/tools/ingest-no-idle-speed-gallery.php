<?php
declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    header('Content-Type: text/plain; charset=UTF-8');
    echo "CLI only.\n";
    exit(1);
}

/**
 * Download and optimize Solo Signals / No Idle Speed gallery art from Suno.
 *
 * Usage: php tools/ingest-no-idle-speed-gallery.php
 */

$root = dirname(__DIR__);
$metaPath = __DIR__ . '/no-idle-speed-playlist.json';
if (!is_file($metaPath)) {
    fwrite(STDERR, "Missing {$metaPath}. Run fetch-playlist-meta.php first.\n");
    exit(1);
}

$meta = json_decode((string) file_get_contents($metaPath), true);
if (!is_array($meta)) {
    fwrite(STDERR, "Invalid playlist meta JSON\n");
    exit(1);
}

$srcDir = $root . '/assets/img/solo-signals/no-idle-speed';
$optDir = $root . '/assets/img-optimized/gallery/no-idle-speed';
foreach ([$srcDir, $optDir] as $dir) {
    if (!is_dir($dir) && !mkdir($dir, 0775, true) && !is_dir($dir)) {
        fwrite(STDERR, "Could not create {$dir}\n");
        exit(1);
    }
}

$ctx = stream_context_create([
    'http' => [
        'timeout' => 45,
        'header' => "User-Agent: SYZYGY.VOID-Gallery-Ingest/1.0\r\nAccept: image/*\r\n",
    ],
]);

function syzygy_slug_file(string $title): string
{
    $title = html_entity_decode($title, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $title = preg_replace('/[^\pL\pN]+/u', '-', $title) ?? '';
    $title = trim($title, '-');
    $title = strtolower($title);
    return $title !== '' ? $title : 'track';
}

function syzygy_load_image_blob(string $bytes)
{
    $img = @imagecreatefromstring($bytes);
    return $img ?: null;
}

function syzygy_write_webp_resized($src, int $targetW, string $dest, int $quality = 82): bool
{
    $sw = imagesx($src);
    $sh = imagesy($src);
    if ($sw < 1 || $sh < 1) {
        return false;
    }

    $ratio = min(1, $targetW / $sw);
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

function syzygy_ingest_image(string $url, string $srcPath, string $optBaseNoExt, $ctx): array
{
    $bytes = @file_get_contents($url, false, $ctx);
    if ($bytes === false || $bytes === '') {
        return ['ok' => false, 'error' => 'download failed'];
    }

    file_put_contents($srcPath, $bytes);
    $img = syzygy_load_image_blob($bytes);
    if (!$img) {
        return ['ok' => false, 'error' => 'decode failed'];
    }

    $mainW = min(1200, imagesx($img));
    $targets = [
        [$optBaseNoExt . '.webp', $mainW, 82],
    ];
    foreach ([400, 800, 1200] as $w) {
        if ($w > imagesx($img)) {
            continue;
        }
        $targets[] = [$optBaseNoExt . '-' . $w . '.webp', $w, 80];
    }

    foreach ($targets as [$dest, $w, $q]) {
        if (!syzygy_write_webp_resized($img, (int) $w, $dest, (int) $q)) {
            imagedestroy($img);
            return ['ok' => false, 'error' => 'webp write failed for ' . $dest];
        }
    }

    imagedestroy($img);
    return ['ok' => true];
}

$manifestItems = [];

// Playlist cover
$coverUrl = (string) ($meta['image_url'] ?? '');
if ($coverUrl !== '') {
    $srcCover = $srcDir . '/00-playlist-cover.jpg';
    $optCoverBase = $optDir . '/00 - Playlist Cover';
    $result = syzygy_ingest_image($coverUrl, $srcCover, $optCoverBase, $ctx);
    if (!$result['ok']) {
        fwrite(STDERR, "Cover failed: {$result['error']}\n");
        exit(1);
    }
    echo "Cover optimized\n";
}

foreach (($meta['clips'] ?? []) as $clip) {
    $n = (int) ($clip['n'] ?? 0);
    $title = (string) ($clip['title'] ?? 'Untitled');
    $imageUrl = (string) ($clip['image_url'] ?? '');
    if ($n < 1 || $imageUrl === '') {
        continue;
    }

    $slug = syzygy_slug_file($title);
    $pad = str_pad((string) $n, 2, '0', STR_PAD_LEFT);
    $fileBase = $pad . ' - ' . $title;
    // Keep filesystem-safe but readable names similar to other galleries
    $safeBase = $pad . '-' . $slug;

    $srcPath = $srcDir . '/' . $safeBase . '.jpg';
    $optBase = $optDir . '/' . $safeBase;
    $result = syzygy_ingest_image($imageUrl, $srcPath, $optBase, $ctx);
    if (!$result['ok']) {
        fwrite(STDERR, "Track {$n} failed: {$result['error']}\n");
        exit(1);
    }

    $manifestItems[] = [
        'title' => $title,
        'file' => $safeBase . '.webp',
    ];
    echo "Track {$n}: {$title}\n";
}

$manifestPath = $optDir . '/_manifest-items.php';
$php = "<?php\nreturn " . var_export($manifestItems, true) . ";\n";
file_put_contents($manifestPath, $php);
echo "Wrote " . count($manifestItems) . " gallery items helper\n";
