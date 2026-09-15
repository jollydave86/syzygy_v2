<?php
declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    header('Content-Type: text/plain; charset=UTF-8');
    echo "CLI only.\n";
    exit(1);
}

/**
 * Cache Suno playlist metadata for gallery / track ingest.
 *
 * Usage:
 *   php tools/fetch-playlist-meta.php <playlist-uuid> [output-basename]
 *
 * Examples:
 *   php tools/fetch-playlist-meta.php f94c300b-683a-41b0-bba0-7485f7d71018 forever-city
 */

$id = trim((string) ($argv[1] ?? ''));
$basename = trim((string) ($argv[2] ?? ''));

if ($id === '' || !preg_match('/^[a-f0-9-]{36}$/i', $id)) {
    fwrite(STDERR, "Usage: php tools/fetch-playlist-meta.php <playlist-uuid> [output-basename]\n");
    exit(1);
}

if ($basename === '') {
    $basename = $id;
}

$basename = preg_replace('/[^a-z0-9_-]+/i', '-', $basename) ?? $basename;
$basename = trim((string) $basename, '-');

$url = 'https://studio-api.prod.suno.com/api/playlist/' . rawurlencode($id);
$ctx = stream_context_create([
    'http' => [
        'timeout' => 45,
        'header' => "User-Agent: SYZYGY.VOID-Playlist-Meta/1.0\r\nAccept: application/json\r\n",
    ],
]);
$raw = @file_get_contents($url, false, $ctx);
$j = $raw ? json_decode($raw, true) : null;
if (!is_array($j)) {
    fwrite(STDERR, "Failed to fetch playlist\n");
    exit(1);
}

$out = [
    'playlist_id' => $id,
    'name' => (string) ($j['name'] ?? ''),
    'image_url' => (string) ($j['image_url'] ?? ''),
    'clips' => [],
];

foreach (($j['playlist_clips'] ?? []) as $i => $row) {
    $c = is_array($row) ? ($row['clip'] ?? $row) : [];
    if (!is_array($c)) {
        $c = [];
    }
    $out['clips'][] = [
        'n' => $i + 1,
        'title' => (string) ($c['title'] ?? ''),
        'id' => (string) ($c['id'] ?? ''),
        'image_url' => (string) ($c['image_large_url'] ?? $c['image_url'] ?? ''),
        'is_public' => !empty($c['is_public']),
    ];
}

$outPath = __DIR__ . '/' . $basename . '-playlist.json';
file_put_contents($outPath, json_encode($out, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n");
echo 'Wrote ' . count($out['clips']) . " clips to {$outPath}\n";
