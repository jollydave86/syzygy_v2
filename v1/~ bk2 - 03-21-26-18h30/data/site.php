<?php

$site = [
    'name' => 'SYZYGY.VOID',
    'short_name' => 'SYZYGY.VOID',
    'asset_version' => '20260321f',

    /*
    |--------------------------------------------------------------------------
    | Core SEO / Meta
    |--------------------------------------------------------------------------
    */
    'title' => 'SYZYGY.VOID Official Site | Cinematic Darkwave & Electronic Music',
    'description' => 'Enter SYZYGY.VOID — the official home of cinematic darkwave, electronic and dark-futurist music. Explore serialized releases, visual archives, videos and the expanding SYZYGY.VOID universe.',
    'url' => 'https://syzygyvoid.ca',

    'og_title' => 'SYZYGY.VOID Official Site | Cinematic Darkwave & Electronic Music',
    'og_description' => 'Official site of SYZYGY.VOID — cinematic darkwave, electronic and dark-futurist music with serialized releases, videos, gallery archives and immersive visual identity.',
    'og_type' => 'website',
    'og_image' => '/assets/img/og/syzygyvoid-home-og.jpg',
    'og_image_alt' => 'SYZYGY.VOID official site — cinematic darkwave and electronic music',

    'twitter_card' => 'summary_large_image',
    'locale' => 'en_CA',
    'theme_color' => '#001f3c',
    'robots' => 'index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1',

    /*
    |--------------------------------------------------------------------------
    | Brand / Entity
    |--------------------------------------------------------------------------
    */
    'artist_profiles' => [
        'suno' => 'https://suno.com/@syzygy86',
        'youtube' => 'https://www.youtube.com/channel/UCRgXBc15C7yQVqoRVFVl_Cw',
        'spotify' => 'https://open.spotify.com/artist/0qFeGyiohL3cCuRTVRhI5u',
        'apple_music' => 'https://music.apple.com/ca/artist/syzygy-void/1845693940',
    ],

    /*
    |--------------------------------------------------------------------------
    | Hero
    |--------------------------------------------------------------------------
    */
    'hero' => [
        'eyebrow' => 'CINEMATIC • DARKWAVE • ELECTRONIC',
        'heading_line_1' => 'SYZYGY.VOID',
        'heading_line_2' => 'OFFICIAL',
        'text' => 'A premium dark-futurist music brand built on cosmic tension, industrial pulse, and visual atmosphere.',
        'primary_cta' => [
            'label' => 'Listen Now',
            'href' => '#music',
        ],
        'secondary_cta' => [
            'label' => 'Gallery',
            'href' => '#gallery',
        ],
        'image' => '/assets/img/hero-bg.jpg',
    ],

    /*
    |--------------------------------------------------------------------------
    | About
    |--------------------------------------------------------------------------
    */
    'about' => [
        'eyebrow' => 'ABOUT THE PROJECT',
        'title' => 'Dark signal. Human emotion. Cinematic pressure.',
        'text' => 'SYZYGY.VOID is built around large-scale atmosphere, futuristic tension, and emotionally charged electronic music. The visual identity leans into deep shadow, solar gold, industrial texture, and a premium sci-fi tone that feels part album campaign, part digital myth.',
        'text_2' => 'Explore releases, visual worlds, and evolving project eras through a site structure designed to scale with new music, videos, roadmap drops, and future expansions.',
        'image' => '/assets/img/about-image.jpg',
        'stats' => [
            [
                'value' => '4K+',
                'label' => 'Tracks',
            ],
            [
                'value' => '700+',
                'label' => 'Active Fans',
            ],
            [
                'value' => '7',
                'label' => 'Eras',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Footer
    |--------------------------------------------------------------------------
    */
    'footer' => [
        'tagline' => 'Cinematic dark-futurist music and visual identity.',
        'copyright' => '© ' . date('Y') . ' SYZYGY.VOID. All rights reserved.',
        'socials' => [
            [
                'label' => 'Suno',
                'href' => 'https://suno.com/@syzygy86',
            ],
            [
                'label' => 'YouTube',
                'href' => 'https://www.youtube.com/channel/UCRgXBc15C7yQVqoRVFVl_Cw',
            ],
            [
                'label' => 'Spotify',
                'href' => 'https://open.spotify.com/artist/0qFeGyiohL3cCuRTVRhI5u',
            ],
            [
                'label' => 'Apple Music',
                'href' => 'https://music.apple.com/ca/artist/syzygy-void/1845693940',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Navigation
    |--------------------------------------------------------------------------
    */
    'nav' => [
        ['label' => 'About', 'href' => '#about'],
        ['label' => 'Music', 'href' => '#music'],
        ['label' => 'Videos', 'href' => '#videos'],
        ['label' => 'Gallery', 'href' => '#gallery'],
        ['label' => 'Merch', 'href' => '#merch'],
        ['label' => 'Contact', 'href' => '#contact'],
    ],
];