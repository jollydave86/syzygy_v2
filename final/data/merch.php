<?php

$merchReleases = [
    ['key' => 'occupancy-zero', 'name' => 'OCCUPANCY ZERO', 'music_href' => '/music/occupancy-zero', 'profile_href' => '/profiles/kade-null'],
    ['key' => 'no-idle-speed', 'name' => 'NO IDLE SPEED', 'music_href' => '/music/no-idle-speed', 'profile_href' => '/profiles/lucien-cross'],
    ['key' => 'white-voltage', 'name' => 'WHITE VOLTAGE', 'music_href' => '/music/white-voltage', 'profile_href' => '/profiles/nova-vale'],
    ['key' => 'deluxe-queen', 'name' => 'DELUXE QUEEN', 'music_href' => '/music/deluxe-queen', 'profile_href' => '/profiles/nova-vale'],
    ['key' => 'body-clock', 'name' => 'BODY CLOCK', 'music_href' => '/music/body-clock', 'profile_href' => '/profiles/lyra-static'],
    ['key' => 'the-shape-i-left', 'name' => 'THE SHAPE I LEFT', 'music_href' => '/music/the-shape-i-left', 'profile_href' => '/profiles/ash-vex'],
    ['key' => 'forever-city', 'name' => 'Forever City', 'music_href' => '/music/forever-city', 'profile_href' => ''],
    ['key' => 'the-chapel-protocol-aftermath', 'name' => 'The Chapel Protocol // Aftermath Sequel', 'music_href' => '/music/chapel-protocol-aftermath', 'profile_href' => ''],
    ['key' => 'monument-zero', 'name' => 'Monument Zero', 'music_href' => '/music/monument-zero', 'profile_href' => ''],
    ['key' => 'neo-monument', 'name' => 'NEO//MONUMENT : The Crossover', 'music_href' => '/music/neo-monument', 'profile_href' => ''],
    ['key' => 'neo-noir-city', 'name' => 'Neo-Noir City', 'music_href' => '/music/neo-noir-city', 'profile_href' => ''],
    ['key' => 'no-exit-clown-arcade-game-show', 'name' => 'NO EXIT ARCADE: THE CLOWN GAME SHOW', 'music_href' => '/music/no-exit-arcade', 'profile_href' => ''],
    ['key' => 'operation-tomorrowline', 'name' => 'OPERATION // TOMORROWLINE', 'music_href' => '/music/operation-tomorrowline', 'profile_href' => ''],
    ['key' => 'the-chapel-protocol', 'name' => 'The Chapel Protocol', 'music_href' => '/music/chapel-protocol', 'profile_href' => ''],
    ['key' => 'the-wondering-traveler', 'name' => 'The Wondering Traveler', 'music_href' => '/music/wondering-traveler', 'profile_href' => ''],
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
$releasesWithItems = [];

foreach ($merchReleases as $release) {
    $releaseItems = 0;
    foreach ($merchFormats as $formatKey => $format) {
        $filename = $release['key'] . '-' . $formatKey . '.webp';
        $filePath = $baseDir . $filename;

        if (!is_file($filePath)) {
            continue;
        }

        $releaseItems++;
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
            'music_href' => $release['music_href'] ?? '',
            'profile_href' => $release['profile_href'] ?? '',
        ];
    }
    if ($releaseItems > 0) {
        $releasesWithItems[] = $release;
    }
}

return [
    'eyebrow' => 'PRE ORDER SOON',
    'title' => 'PHYSICAL FORMATS, COLLECTOR EDITIONS, AND APPAREL DROPS',
    'text' => 'Compressed WebP CD, vinyl, cassette, and apparel concepts from serialized worlds plus Era 3 Solo Signals. Occupancy Zero, No Idle Speed, White Voltage, Deluxe Queen, Body Clock, The Shape I Left, and Forever City sit on the same preorder wall. Not live inventory.',
    'releases' => $releasesWithItems,
    'items' => $items,
];
