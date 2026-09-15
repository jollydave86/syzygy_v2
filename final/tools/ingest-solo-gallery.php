<?php
declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    header('Content-Type: text/plain; charset=UTF-8');
    echo "CLI only.\n";
    exit(1);
}

/**
 * Download and optimize Solo Signals gallery art from a cached playlist JSON.
 *
 * Usage:
 *   php tools/ingest-solo-gallery.php <meta.json> <folder-slug> [startN] [endN] [--cover=playlist|clip:N]
 *
 * Examples:
 *   php tools/ingest-solo-gallery.php tools/white-voltage-playlist.json white-voltage 1 8
 *   php tools/ingest-solo-gallery.php tools/white-voltage-playlist.json deluxe-queen 9 18 --cover=clip:9
 */

$root = dirname(__DIR__);

$metaPath = $argv[1] ?? '';
$folder = trim((string) ($argv[2] ?? ''));
$startN = isset($argv[3]) && ctype_digit((string) $argv[3]) ? (int) $argv[3] : 1;
$endN = isset($argv[4]) && ctype_digit((string) $argv[4]) ? (int) $argv[4] : PHP_INT_MAX;
$coverMode = 'playlist';
$coverClipN = 0;

foreach ($argv as $arg) {
    if (!is_string($arg)) {
        continue;
    }
    if ($arg === '--cover=playlist') {
        $coverMode = 'playlist';
    } elseif (preg_match('/^--cover=clip:(\d+)$/', $arg, $m)) {
        $coverMode = 'clip';
        $coverClipN = (int) $m[1];
    }
}

if ($metaPath === '' || $folder === '') {
    fwrite(STDERR, "Usage: php tools/ingest-solo-gallery.php <meta.json> <folder-slug> [startN] [endN] [--cover=playlist|clip:N]\n");
    exit(1);
}

if (!is_file($metaPath)) {
    $alt = $root . '/' . ltrim(str_replace('\\', '/', $metaPath), '/');
    if (is_file($alt)) {
        $metaPath = $alt;
    }
}

if (!is_file($metaPath)) {
    fwrite(STDERR, "Missing meta JSON: {$metaPath}\n");
    exit(1);
}

$meta = json_decode((string) file_get_contents($metaPath), true);
if (!is_array($meta)) {
    fwrite(STDERR, "Invalid playlist meta JSON\n");
    exit(1);
}

$srcDir = $root . '/assets/img/solo-signals/' . $folder;
$optDir = $root . '/assets/img-optimized/gallery/' . $folder;
foreach ([$srcDir, $optDir] as $dir) {
    if (!is_dir($dir) && !mkdir($dir, 0775, true) && !is_dir($dir)) {
        fwrite(STDERR, "Could not create {$dir}\n");
        exit(1);
    }
}

$ctx = stream_context_create([
    'http' => [
        'timeout' => 45,
        'header' => "User-Agent: Mozilla/5.0 (compatible; SYZYGY.VOID-Gallery-Ingest/1.0)\r\nAccept: image/*\r\nReferer: https://suno.com/\r\n",
    ],
]);

function syzygy_slug_file(string $title): string
{
    $title = html_entity_decode($title, ENT_QUOTES | ENT_HTML5, 'UTF-8');

    if (class_exists('Transliterator')) {
        $converted = transliterator_transliterate('Any-Latin; Latin-ASCII;', $title);
        if (is_string($converted) && $converted !== '') {
            $title = $converted;
        }
    } else {
        $converted = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $title);
        if ($converted !== false && $converted !== '') {
            $title = $converted;
        }
    }

    $title = strtolower($title);
    $title = preg_replace('/[^a-z0-9]+/', '-', $title) ?? '';
    $title = trim($title, '-');
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

$clips = $meta['clips'] ?? [];
$selected = [];
foreach ($clips as $clip) {
    $n = (int) ($clip['n'] ?? 0);
    if ($n < $startN || $n > $endN) {
        continue;
    }
    $selected[] = $clip;
}

if ($selected === []) {
    fwrite(STDERR, "No clips in range {$startN}-{$endN}\n");
    exit(1);
}

$manifestItems = [];

$coverUrl = '';
if ($coverMode === 'clip' && $coverClipN > 0) {
    foreach ($clips as $clip) {
        if ((int) ($clip['n'] ?? 0) === $coverClipN) {
            $coverUrl = (string) ($clip['image_url'] ?? '');
            break;
        }
    }
}
if ($coverUrl === '') {
    $coverUrl = (string) ($meta['image_url'] ?? '');
}

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

$seq = 0;
foreach ($selected as $clip) {
    $seq++;
    $title = (string) ($clip['title'] ?? 'Untitled');
    $imageUrl = (string) ($clip['image_url'] ?? '');
    if ($imageUrl === '') {
        continue;
    }

    $slug = syzygy_slug_file($title);
    $pad = str_pad((string) $seq, 2, '0', STR_PAD_LEFT);
    $safeBase = $pad . '-' . $slug;

    $srcPath = $srcDir . '/' . $safeBase . '.jpg';
    $optBase = $optDir . '/' . $safeBase;
    $result = syzygy_ingest_image($imageUrl, $srcPath, $optBase, $ctx);
    if (!$result['ok']) {
        fwrite(STDERR, "Track {$seq} failed: {$result['error']}\n");
        exit(1);
    }

    $manifestItems[] = [
        'title' => $title,
        'file' => $safeBase . '.webp',
    ];
    echo "Track {$seq}: {$title}\n";
}

$manifestPath = $optDir . '/_manifest-items.php';
$php = "<?php\nreturn " . var_export($manifestItems, true) . ";\n";
file_put_contents($manifestPath, $php);
echo "Wrote " . count($manifestItems) . " gallery items helper into {$folder}\n";
