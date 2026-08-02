<?php
declare(strict_types=1);

/**
 * Build a production zip of final/ with secrets, tools, previews, and source dumps excluded.
 *
 * Usage: php tools/build-production-zip.php
 * Output: ../dist/syzygyvoid-production-YYYYMMDD-HHMMSS.zip
 */

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    header('Content-Type: text/plain; charset=UTF-8');
    echo "CLI only.\n";
    exit(1);
}

$root = dirname(__DIR__);
$repoRoot = dirname($root);
$distDir = $repoRoot . DIRECTORY_SEPARATOR . 'dist';
$stamp = gmdate('Ymd-His');
$stageDir = $distDir . DIRECTORY_SEPARATOR . 'stage-' . $stamp;
$zipPath = $distDir . DIRECTORY_SEPARATOR . 'syzygyvoid-production-' . $stamp . '.zip';

if (!is_dir($distDir) && !mkdir($distDir, 0775, true) && !is_dir($distDir)) {
    fwrite(STDERR, "Could not create {$distDir}\n");
    exit(1);
}

$excludeDirNames = [
    'tools' => true,
    'previews' => true,
];

$excludePathFragments = [
    '/assets/img/solo-signals/body-clock/',
    '/assets/img/solo-signals/deluxe-queen/',
    '/assets/img/solo-signals/no-idle-speed/',
    '/assets/img/solo-signals/the-shape-i-left/',
    '/assets/img/solo-signals/white-voltage/',
];

$excludeFileNames = [
    'includes.zip' => true,
    'hero-bg.jpg' => true,
    'video-modal.js' => true,
    '_manifest-items.php' => true,
    'README.md' => true,
    'favicon-head-snippet.txt' => true,
];

function syzygy_prod_rel(string $root, string $path): string
{
    $root = str_replace('\\', '/', rtrim($root, '/\\'));
    $path = str_replace('\\', '/', $path);
    return ltrim(substr($path, strlen($root)), '/');
}

function syzygy_should_exclude(string $root, string $path, array $excludeDirNames, array $excludePathFragments, array $excludeFileNames): bool
{
    $rel = '/' . syzygy_prod_rel($root, $path);
    $base = basename($path);

    if (isset($excludeFileNames[$base])) {
        return true;
    }

    if (preg_match('#/assets/img/solo-signals/[^/]+\.(jpe?g|png)$#i', $rel)) {
        return true;
    }

    foreach ($excludePathFragments as $fragment) {
        if (str_contains($rel, $fragment)) {
            return true;
        }
    }

    $parts = explode('/', trim($rel, '/'));
    foreach ($parts as $part) {
        if (isset($excludeDirNames[$part])) {
            return true;
        }
    }

    return false;
}

function syzygy_rrmdir(string $dir): void
{
    if (!is_dir($dir)) {
        return;
    }
    $items = scandir($dir);
    if ($items === false) {
        return;
    }
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') {
            continue;
        }
        $path = $dir . DIRECTORY_SEPARATOR . $item;
        if (is_dir($path)) {
            syzygy_rrmdir($path);
        } else {
            @unlink($path);
        }
    }
    @rmdir($dir);
}

syzygy_rrmdir($stageDir);
if (!mkdir($stageDir, 0775, true) && !is_dir($stageDir)) {
    fwrite(STDERR, "Could not create stage {$stageDir}\n");
    exit(1);
}

$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS)
);

$count = 0;
foreach ($iterator as $fileInfo) {
    /** @var SplFileInfo $fileInfo */
    $path = $fileInfo->getPathname();
    if ($fileInfo->isDir()) {
        continue;
    }

    if (syzygy_should_exclude($root, $path, $excludeDirNames, $excludePathFragments, $excludeFileNames)) {
        continue;
    }

    $rel = syzygy_prod_rel($root, $path);
    $dest = $stageDir . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $rel);
    $destDir = dirname($dest);
    if (!is_dir($destDir) && !mkdir($destDir, 0775, true) && !is_dir($destDir)) {
        fwrite(STDERR, "Could not create {$destDir}\n");
        exit(1);
    }
    if (!copy($path, $dest)) {
        fwrite(STDERR, "Copy failed: {$rel}\n");
        exit(1);
    }
    $count++;
}

if (is_file($zipPath)) {
    @unlink($zipPath);
}

$psStage = str_replace("'", "''", $stageDir);
$psZip = str_replace("'", "''", $zipPath);
$cmd = 'powershell -NoProfile -Command "Compress-Archive -Path \'' . $psStage . '\\*\' -DestinationPath \'' . $psZip . '\' -Force"';
exec($cmd, $out, $code);
if ($code !== 0 || !is_file($zipPath)) {
    fwrite(STDERR, "Compress-Archive failed.\n" . implode("\n", $out) . "\n");
    fwrite(STDERR, "Stage left at: {$stageDir}\n");
    exit(1);
}

syzygy_rrmdir($stageDir);

echo "Wrote {$zipPath}\n";
echo "Files: {$count}\n";
echo "Excluded: tools/, previews/, source solo dumps, hero-bg.jpg, video-modal.js, README, _manifest helpers\n";
echo "Remember: rotate any previously exposed SMTP password; keep config/contact-mail.php scrubbed in the zip.\n";
