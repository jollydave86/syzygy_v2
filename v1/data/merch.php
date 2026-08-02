<?php

$merchReleases = [
    ['key' => 'the-chapel-protocol-aftermath', 'name' => 'The Chapel Protocol // Aftermath Sequel'],
    ['key' => 'monument-zero', 'name' => 'Monument Zero'],
    ['key' => 'neo-monument', 'name' => 'NEO//MONUMENT : The Crossover'],
    ['key' => 'neo-noir-city', 'name' => 'Neo-Noir City'],
    ['key' => 'no-exit-clown-arcade-game-show', 'name' => 'NO EXIT ARCADE: THE CLOWN GAME SHOW'],
    ['key' => 'operation-tomorrowline', 'name' => 'OPERATION // TOMORROWLINE'],
    ['key' => 'the-chapel-protocol', 'name' => 'The Chapel Protocol'],
    ['key' => 'the-wondering-traveler', 'name' => 'The Wondering Traveler'],
];

$merchFormats = [
    'cd' => [
        'label' => 'CD',
        'description' => 'Premium compact disc edition with the full Aftermath cover treatment, disc art, and collector display energy.',
    ],
    'vinyl' => [
        'label' => 'Single Vinyl',
        'description' => 'Display-ready single vinyl edition with dark cinematic sleeve artwork and a matching black record design.',
    ],
    'double-vinyl' => [
        'label' => 'Double Vinyl Special Edition',
        'description' => 'Deluxe double LP collector edition with gatefold artwork, custom vinyl designs, and premium archive presentation.',
    ],
    'cassette' => [
        'label' => 'Cassette',
        'description' => 'Special edition cassette with front cover, back track listing, and dark analog archive styling.',
    ],
    'merch' => [
        'label' => 'T-Shirt / Apparel',
        'description' => 'Black apparel edition with the Aftermath ensemble artwork, SYZYGY.VOID branding, and The Black Spring visual identity.',
    ],
];

$releaseOverrides = [
    'the-chapel-protocol-aftermath' => [
        'cd' => [
            'description' => 'The Chapel Protocol // Aftermath Sequel CD edition with full cover art, matching disc design, and dark collector display styling.',
        ],
        'vinyl' => [
            'description' => 'Single vinyl edition for The Black Spring, built around the Aftermath cover art and a clean premium LP presentation.',
        ],
        'double-vinyl' => [
            'description' => 'Special edition double vinyl with gatefold artwork, two custom vinyl designs, and the full Aftermath collector treatment.',
        ],
        'cassette' => [
            'description' => 'Special edition cassette with both sides shown, full track listing, and the official Aftermath tape design.',
        ],
        'merch' => [
            'description' => 'Black T-shirt featuring the Aftermath ensemble artwork, The Black Spring title treatment, and SYZYGY.VOID insignia details.',
        ],
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

        $description = $releaseOverrides[$release['key']][$formatKey]['description']
            ?? $format['description'];

        $items[] = [
            'release_key' => $release['key'],
            'release_name' => $release['name'],
            'format_key' => $formatKey,
            'format_title' => $format['label'],
            'description' => $description,
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
    'text' => 'Browse physical releases and visual merch concepts from the SYZYGY.VOID catalog. Current items are preorder placeholders while final store links are prepared.',
    'releases' => $merchReleases,
    'items' => $items,
];