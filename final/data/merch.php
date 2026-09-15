<?php

$merchReleases = [
    ['key' => 'occupancy-zero', 'name' => 'OCCUPANCY ZERO'],
    ['key' => 'no-idle-speed', 'name' => 'NO IDLE SPEED'],
    ['key' => 'white-voltage', 'name' => 'WHITE VOLTAGE'],
    ['key' => 'deluxe-queen', 'name' => 'DELUXE QUEEN'],
    ['key' => 'body-clock', 'name' => 'BODY CLOCK'],
    ['key' => 'the-shape-i-left', 'name' => 'THE SHAPE I LEFT'],
    ['key' => 'forever-city', 'name' => 'Forever City'],
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
    'cd' => ['label' => 'CD'],
    'vinyl' => ['label' => 'Single Vinyl'],
    'double-vinyl' => ['label' => 'Double Vinyl Special Edition'],
    'cassette' => ['label' => 'Cassette'],
    'merch' => ['label' => 'T-Shirt / Apparel'],
];

$formatBlurb = static function (string $releaseName, string $formatKey): string {
    return match ($formatKey) {
        'cd' => $releaseName . ' CD edition with full cover art, matching disc design, and collector display styling.',
        'vinyl' => $releaseName . ' single vinyl edition with cinematic sleeve artwork and premium LP presentation.',
        'double-vinyl' => $releaseName . ' deluxe double LP with gatefold artwork and archive collector treatment.',
        'cassette' => $releaseName . ' special edition cassette with cover art, track listing, and analog archive styling.',
        'merch' => $releaseName . ' black apparel edition with SYZYGY.VOID branding and release visual identity.',
        default => $releaseName . ' merch concept. Pre order soon.',
    };
};

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
            'description' => $formatBlurb($release['name'], $formatKey),
            'image' => $baseUrl . $filename,
            'alt' => $release['name'] . ' ' . $format['label'],
            'link' => $preorderLink,
            'link_label' => $preorderLabel,
        ];
    }
}

return [
    'eyebrow' => 'PRE ORDER SOON',
    'title' => 'PHYSICAL FORMATS, COLLECTOR EDITIONS, AND APPAREL DROPS',
    'text' => 'Browse physical release and apparel concepts from the SYZYGY.VOID catalog — serialized worlds plus Solo Signals EPs. Items are preorder placeholders — not active inventory.',
    'releases' => $merchReleases,
    'items' => $items,
];
