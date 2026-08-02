<?php

$merchReleases = [
    ['key' => 'monument-zero', 'name' => 'Monument Zero'],
    ['key' => 'neo-monument', 'name' => 'NEO//MONUMENT : The Crossover'],
    ['key' => 'neo-noir-city', 'name' => 'Neo-Noir City'],
    ['key' => 'no-exit-clown-arcade-game-show', 'name' => 'NO EXIT ARCADE: THE CLOWN GAME SHOW'],
    ['key' => 'operation-tomorrowline', 'name' => 'OPERATION // TOMORROWLINE'],
    ['key' => 'the-chapel-protocol', 'name' => 'The Chapel Protocol'],
    ['key' => 'the-wondering-traveler', 'name' => 'The Wondering Traveler'],
];

$merchFormats = [
    'cassette' => [
        'label' => 'Cassette',
        'description' => 'Compact collector edition built for the archive shelf and late-night playback energy.',
    ],
    'cd' => [
        'label' => 'CD',
        'description' => 'Premium archive piece designed for the physical stack, display wall, or studio desk.',
    ],
    'double-vinyl' => [
        'label' => 'Double Vinyl',
        'description' => 'Deluxe visual-impact edition made to feel cinematic, oversized, and era-defining in hand.',
    ],
    'merch' => [
        'label' => 'Apparel',
        'description' => 'Wearable era drop made to carry the release identity into the real world with clean visual punch.',
    ],
    'vinyl' => [
        'label' => 'Vinyl',
        'description' => 'Classic display-ready release with strong shelf presence and a timeless collector feel.',
    ],
];

$baseDir = dirname(__DIR__) . '/assets/img-optimized/merch/';
$baseUrl = '/assets/img-optimized/merch/';

$preorderLink = '#';
$preorderLabel = 'Pre Order soon!';

$items = [];

foreach ($merchReleases as $release) {
    foreach ($merchFormats as $formatKey => $format) {
        $filename = $release['key'] . '-' . $formatKey . '.webp';
        $filePath = $baseDir . $filename;

        if (!is_file($filePath)) {
            continue;
        }

        $items[] = [
            'release_key' => $release['key'],
            'release_name' => $release['name'],
            'format_key' => $formatKey,
            'format_title' => $format['label'],
            'description' => $format['description'],
            'image' => $baseUrl . $filename,
            'alt' => $release['name'] . ' ' . $format['label'],
            'link' => $preorderLink,
            'link_label' => $preorderLabel,
        ];
    }
}

return [
    'eyebrow' => 'ORDER NOW!',
    'title' => 'PHYSICAL FORMATS, COLLECTOR EDITIONS, AND APPAREL DROPS',
    'text' => 'Browse our collection of physical releases now on PRE-ORDER only. Thank you for your support.',
    'releases' => $merchReleases,
    'items' => $items,
];