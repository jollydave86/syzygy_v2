<?php
declare(strict_types=1);

/**
 * Lyrics stubs — auto-built from public tracklists.
 *
 * To publish lyrics for a song:
 * 1. Find the slug (or set 'slug' override below)
 * 2. Set status to 'ready'
 * 3. Paste lyrics into 'body'
 *
 * Example override:
 * $lyricsOverrides['static-on-line-3-chapter-2'] = [
 *   'status' => 'ready',
 *   'body' => "Line one...\nLine two...",
 * ];
 */

$lyricsOverrides = [
    'neo-monument-the-crossover' => [
        'status' => 'ready',
        'body' => "[Instrumental]\n\nThe album opens not with melody but with architecture that hums.\nA low drone rises like air vibrating in a subway tunnel.\nMetallic echoes drip in uneven rhythms, as if water is leaking through cracked concrete.\nThe sound builds slowly, not with urgency but with inevitability,\nlayering detuned synths, bowed-steel textures, and a faint heartbeat hidden beneath the noise.\n\nHalfway through, the drone splits in two tonalities —\nNew Avalon’s industrial coldness and the neon shimmer of Neo Noir City.",
    ],
];

$importedLyricsPath = __DIR__ . '/lyrics-imported.php';
$lyricsImported = is_file($importedLyricsPath) ? require $importedLyricsPath : [];
$lyricsImported = is_array($lyricsImported) ? $lyricsImported : [];

$lyrics = [];

$sources = [];
if (!empty($playlists) && is_array($playlists)) {
    foreach ($playlists as $key => $playlist) {
        $sources[] = [
            'release_slug' => $key,
            'release_title' => $playlist['label'] ?? $key,
            'tracks' => $playlist['tracks'] ?? [],
        ];
    }
}
if (!empty($featuredPool) && is_array($featuredPool)) {
    $sources[] = [
        'release_slug' => 'featured-singles',
        'release_title' => 'Featured Singles',
        'tracks' => $featuredPool,
    ];
}

foreach ($sources as $source) {
    foreach ($source['tracks'] as $index => $track) {
        $title = (string) ($track['title'] ?? 'Untitled');
        $slug = syzygy_slugify($title);
        if ($slug === '') {
            $slug = syzygy_slugify($source['release_slug'] . '-' . ($index + 1));
        }

        // Avoid collisions by appending release key when needed
        if (isset($lyrics[$slug]) && ($lyrics[$slug]['release_slug'] ?? '') !== $source['release_slug']) {
            $slug .= '-' . syzygy_slugify($source['release_slug']);
        }

        $entry = [
            'slug' => $slug,
            'title' => $title,
            'release_slug' => $source['release_slug'],
            'release_title' => $source['release_title'],
            'track_number' => $index + 1,
            'suno' => (string) ($track['href'] ?? ''),
            'status' => 'pending',
            'body' => '',
        ];

        if (isset($lyricsImported[$slug]) && is_array($lyricsImported[$slug])) {
            $entry = array_merge($entry, $lyricsImported[$slug]);
            $entry['slug'] = $slug;
        }

        if (isset($lyricsOverrides[$slug]) && is_array($lyricsOverrides[$slug])) {
            $entry = array_merge($entry, $lyricsOverrides[$slug]);
            $entry['slug'] = $slug;
        }

        $lyrics[$slug] = $entry;
    }
}
