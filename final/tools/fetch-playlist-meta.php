<?php
declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    header('Content-Type: text/plain; charset=UTF-8');
    echo "CLI only.\n";
    exit(1);
}

$id = $argv[1] ?? '5bf0841f-9637-47ee-8d95-6d241c20455f';
$url = 'https://studio-api.prod.suno.com/api/playlist/' . $id;
$raw = @file_get_contents($url);
$j = $raw ? json_decode($raw, true) : null;
if (!is_array($j)) {
    fwrite(STDERR, "Failed to fetch playlist\n");
    exit(1);
}

$out = [
    'name' => $j['name'] ?? '',
    'image_url' => $j['image_url'] ?? '',
    'clips' => [],
];

foreach (($j['playlist_clips'] ?? []) as $i => $row) {
    $c = $row['clip'] ?? $row;
    $out['clips'][] = [
        'n' => $i + 1,
        'title' => (string) ($c['title'] ?? ''),
        'id' => (string) ($c['id'] ?? ''),
        'image_url' => (string) ($c['image_large_url'] ?? $c['image_url'] ?? ''),
    ];
}

file_put_contents(__DIR__ . '/no-idle-speed-playlist.json', json_encode($out, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
echo "Wrote " . count($out['clips']) . " clips\n";
