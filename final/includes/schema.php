<?php
declare(strict_types=1);

/**
 * Schema.org / GEO JSON-LD builders for every public page.
 */

if (!function_exists('syzygy_abs_url')) {
    function syzygy_abs_url(string $path = '/'): string
    {
        global $site;
        $base = rtrim((string) ($site['url'] ?? 'https://syzygyvoid.ca'), '/');

        if (preg_match('#^https?://#i', $path)) {
            return $path;
        }

        $path = '/' . ltrim($path, '/');
        if ($path === '/') {
            return $base . '/';
        }

        $segments = explode('/', trim($path, '/'));
        $encoded = array_map(static fn ($s) => rawurlencode($s), $segments);

        return $base . '/' . implode('/', $encoded);
    }
}

if (!function_exists('syzygy_same_as_links')) {
    /** @return list<string> */
    function syzygy_same_as_links(): array
    {
        global $site;
        $profiles = $site['artist_profiles'] ?? [];
        $out = [];
        foreach (['suno', 'youtube', 'spotify', 'apple', 'amazon'] as $key) {
            $href = trim((string) ($profiles[$key] ?? ''));
            if ($href !== '') {
                $out[] = $href;
            }
        }
        return array_values(array_unique($out));
    }
}

if (!function_exists('syzygy_schema_id')) {
    function syzygy_schema_id(string $fragment): string
    {
        return syzygy_abs_url('/') . '#' . ltrim($fragment, '#');
    }
}

if (!function_exists('syzygy_schema_organization')) {
    function syzygy_schema_organization(): array
    {
        global $site;

        return [
            '@type' => 'Organization',
            '@id' => syzygy_schema_id('organization'),
            'name' => $site['name'] ?? 'SYZYGY.VOID',
            'alternateName' => $site['alternate_names'] ?? ['SYZYGYVOID', 'SYZYGY VOID'],
            'url' => syzygy_abs_url('/'),
            'logo' => [
                '@type' => 'ImageObject',
                'url' => syzygy_abs_url($site['logo'] ?? '/apple-touch-icon.png'),
            ],
            'image' => ['@id' => syzygy_schema_id('primaryimage')],
            'description' => $site['og_description'] ?? ($site['description'] ?? ''),
            'sameAs' => syzygy_same_as_links(),
            'areaServed' => [
                ['@type' => 'Country', 'name' => 'Canada'],
                ['@type' => 'Place', 'name' => 'Worldwide'],
            ],
            'foundingLocation' => [
                '@type' => 'Place',
                'name' => $site['founding_location'] ?? 'Montreal, Quebec, Canada',
            ],
        ];
    }
}

if (!function_exists('syzygy_schema_website')) {
    function syzygy_schema_website(): array
    {
        global $site;

        return [
            '@type' => 'WebSite',
            '@id' => syzygy_schema_id('website'),
            'url' => syzygy_abs_url('/'),
            'name' => $site['name'] ?? 'SYZYGY.VOID',
            'alternateName' => $site['alternate_names'] ?? ['SYZYGYVOID', 'SYZYGY VOID'],
            'description' => $site['description'] ?? '',
            'inLanguage' => $site['locale'] ?? 'en-CA',
            'publisher' => ['@id' => syzygy_schema_id('organization')],
        ];
    }
}

if (!function_exists('syzygy_schema_primary_image')) {
    function syzygy_schema_primary_image(?string $imagePath = null, ?string $caption = null): array
    {
        global $site;
        $path = $imagePath ?: ($site['og_image'] ?? '/assets/img-optimized/hero-bg-1200.webp');
        $url = syzygy_abs_url($path);

        return [
            '@type' => 'ImageObject',
            '@id' => syzygy_schema_id('primaryimage'),
            'url' => $url,
            'contentUrl' => $url,
            'caption' => $caption ?: ($site['og_image_alt'] ?? 'SYZYGY.VOID official artwork'),
            'representativeOfPage' => true,
        ];
    }
}

if (!function_exists('syzygy_schema_breadcrumb')) {
    /** @param list<array{name:string,path:string}> $crumbs */
    function syzygy_schema_breadcrumb(array $crumbs): array
    {
        $elements = [];
        foreach (array_values($crumbs) as $i => $crumb) {
            $elements[] = [
                '@type' => 'ListItem',
                'position' => $i + 1,
                'name' => $crumb['name'],
                'item' => syzygy_abs_url($crumb['path']),
            ];
        }

        return [
            '@type' => 'BreadcrumbList',
            '@id' => syzygy_schema_id('breadcrumb'),
            'itemListElement' => $elements,
        ];
    }
}

if (!function_exists('syzygy_schema_musicgroup')) {
    function syzygy_schema_musicgroup(): array
    {
        global $site, $releases, $members;

        $albumRefs = [];
        foreach ($releases as $release) {
            if (($release['slug'] ?? '') === 'featured') {
                continue;
            }
            if (!empty($release['member_slug'])) {
                continue; // solo albums hang off members
            }
            $albumRefs[] = ['@id' => syzygy_schema_id('album-' . ($release['slug'] ?? ''))];
        }

        $memberRefs = [];
        foreach ($members as $member) {
            $memberRefs[] = ['@id' => syzygy_schema_id('person-' . ($member['slug'] ?? ''))];
        }

        return [
            '@type' => 'MusicGroup',
            '@id' => syzygy_schema_id('musicgroup'),
            'name' => $site['name'] ?? 'SYZYGY.VOID',
            'alternateName' => $site['alternate_names'] ?? ['SYZYGYVOID', 'SYZYGY VOID'],
            'url' => syzygy_abs_url('/'),
            'description' => $site['og_description'] ?? ($site['description'] ?? ''),
            'image' => ['@id' => syzygy_schema_id('primaryimage')],
            'genre' => $site['genres'] ?? [
                'Darkwave',
                'Electronic',
                'Industrial',
                'Cinematic',
                'Dark-futurist',
                'Alternative Electronic',
            ],
            'keywords' => $site['keywords'] ?? [],
            'sameAs' => syzygy_same_as_links(),
            'member' => $memberRefs,
            'album' => $albumRefs,
            'subjectOf' => [['@id' => syzygy_schema_id('solo-signals')]],
            'areaServed' => [
                ['@type' => 'Country', 'name' => 'Canada'],
                ['@type' => 'Place', 'name' => 'Worldwide'],
            ],
            'foundingLocation' => [
                '@type' => 'Place',
                'name' => $site['founding_location'] ?? 'Montreal, Quebec, Canada',
            ],
        ];
    }
}

if (!function_exists('syzygy_schema_person')) {
    function syzygy_schema_person(array $member): array
    {
        $slug = (string) ($member['slug'] ?? '');
        $node = [
            '@type' => 'Person',
            '@id' => syzygy_schema_id('person-' . $slug),
            'name' => $member['name'] ?? '',
            'url' => syzygy_abs_url('/profiles/' . $slug),
            'description' => trim(($member['bio'] ?? '') . ' ' . ($member['teaser'] ?? '')),
            'jobTitle' => $member['role'] ?? '',
            'memberOf' => ['@id' => syzygy_schema_id('musicgroup')],
            'knowsAbout' => $member['sound_tags'] ?? [],
        ];

        if (!empty($member['image'])) {
            $node['image'] = syzygy_abs_url((string) $member['image']);
        }

        if (!empty($member['release_slug'])) {
            $node['subjectOf'] = ['@id' => syzygy_schema_id('album-' . $member['release_slug'])];
        }

        if (($member['status'] ?? '') === 'coming_soon') {
            $node['additionalProperty'] = [
                '@type' => 'PropertyValue',
                'name' => 'publicationStatus',
                'value' => 'coming_soon',
            ];
        }

        return $node;
    }
}

if (!function_exists('syzygy_schema_album')) {
    function syzygy_schema_album(array $release, ?array $playlist = null, ?array $member = null): array
    {
        $slug = (string) ($release['slug'] ?? '');
        $cover = !empty($release['cover']) ? syzygy_abs_url((string) $release['cover']) : null;

        $node = [
            '@type' => 'MusicAlbum',
            '@id' => syzygy_schema_id('album-' . $slug),
            'name' => $release['title'] ?? '',
            'url' => syzygy_abs_url('/music/' . $slug),
            'description' => $release['summary'] ?? '',
            'inLanguage' => 'en-CA',
            'genre' => [
                'Darkwave',
                'Electronic',
                'Industrial',
                'Cinematic',
            ],
        ];

        if ($cover) {
            $node['image'] = $cover;
        }

        if ($member) {
            $node['byArtist'] = ['@id' => syzygy_schema_id('person-' . ($member['slug'] ?? ''))];
        } else {
            $node['byArtist'] = ['@id' => syzygy_schema_id('musicgroup')];
        }

        $suno = trim((string) (($release['links']['suno'] ?? '')));
        if ($suno !== '') {
            $node['sameAs'] = [$suno];
        }

        $tracks = $playlist['tracks'] ?? [];
        if (is_array($tracks) && $tracks !== []) {
            $trackNodes = [];
            foreach (array_values($tracks) as $i => $track) {
                $title = (string) ($track['title'] ?? 'Untitled');
                $trackSlug = syzygy_slugify($title);
                $trackNode = [
                    '@type' => 'MusicRecording',
                    '@id' => syzygy_schema_id('track-' . $trackSlug),
                    'name' => $title,
                    'position' => $i + 1,
                    'url' => syzygy_abs_url('/lyrics/' . $trackSlug),
                    'byArtist' => $node['byArtist'],
                    'inAlbum' => ['@id' => syzygy_schema_id('album-' . $slug)],
                ];
                $href = trim((string) ($track['href'] ?? ''));
                if ($href !== '') {
                    $trackNode['sameAs'] = [$href];
                }
                $trackNodes[] = $trackNode;
            }
            $node['track'] = $trackNodes;
            $node['numTracks'] = count($trackNodes);
        }

        return $node;
    }
}

if (!function_exists('syzygy_schema_solo_signals_list')) {
    function syzygy_schema_solo_signals_list(): array
    {
        global $members;

        $items = [];
        $pos = 0;
        foreach ($members as $member) {
            $pos++;
            $label = (string) ($member['name'] ?? '');
            if (!empty($member['release'])) {
                $label .= ' — ' . $member['release'];
            }
            $entry = [
                '@type' => 'ListItem',
                'position' => $pos,
                'name' => $label,
                'url' => syzygy_abs_url('/profiles/' . ($member['slug'] ?? '')),
            ];
            if (!empty($member['release_slug'])) {
                $entry['item'] = ['@id' => syzygy_schema_id('album-' . $member['release_slug'])];
            } else {
                $entry['item'] = ['@id' => syzygy_schema_id('person-' . ($member['slug'] ?? ''))];
            }
            $items[] = $entry;
        }

        return [
            '@type' => 'ItemList',
            '@id' => syzygy_schema_id('solo-signals'),
            'name' => 'SOLO SIGNALS',
            'description' => 'Member and legacy solo transmissions from the SYZYGY.VOID music and visual universe.',
            'url' => syzygy_abs_url('/profiles'),
            'numberOfItems' => count($items),
            'itemListElement' => $items,
        ];
    }
}

if (!function_exists('syzygy_schema_webpage')) {
    function syzygy_schema_webpage(
        string $pageId,
        string $name,
        string $description,
        string $path,
        array $extra = []
    ): array {
        global $site;

        $node = array_merge([
            '@type' => 'WebPage',
            '@id' => syzygy_schema_id($pageId),
            'url' => syzygy_abs_url($path),
            'name' => $name,
            'description' => $description,
            'isPartOf' => ['@id' => syzygy_schema_id('website')],
            'about' => ['@id' => syzygy_schema_id('musicgroup')],
            'inLanguage' => $site['locale'] ?? 'en-CA',
            'breadcrumb' => ['@id' => syzygy_schema_id('breadcrumb')],
            'primaryImageOfPage' => ['@id' => syzygy_schema_id('primaryimage')],
            'publisher' => ['@id' => syzygy_schema_id('organization')],
        ], $extra);

        return $node;
    }
}

if (!function_exists('syzygy_build_schema_graph')) {
    /**
     * @param array<string,mixed> $ctx
     * @return list<array<string,mixed>>
     */
    function syzygy_build_schema_graph(string $page, array $ctx = []): array
    {
        global $site, $releases, $members, $playlists, $featuredPool, $blogPosts, $lyrics, $gallery, $merch;

        $pageTitle = (string) ($ctx['pageTitle'] ?? ($site['title'] ?? 'SYZYGY.VOID'));
        $pageDescription = (string) ($ctx['pageDescription'] ?? ($site['description'] ?? ''));
        $path = (string) ($ctx['path'] ?? syzygy_current_path());
        $ogImage = (string) ($ctx['ogImage'] ?? ($site['og_image'] ?? '/assets/img-optimized/hero-bg-1200.webp'));

        $graph = [
            syzygy_schema_organization(),
            syzygy_schema_website(),
            syzygy_schema_primary_image($ogImage),
            syzygy_schema_musicgroup(),
        ];

        foreach ($members as $member) {
            $graph[] = syzygy_schema_person($member);
        }

        // Band + solo albums (data-driven, no invented titles).
        foreach ($releases as $release) {
            $slug = (string) ($release['slug'] ?? '');
            if ($slug === '') {
                continue;
            }
            $member = null;
            if (!empty($release['member_slug'])) {
                $member = syzygy_find_member((string) $release['member_slug']);
            }
            if ($slug === 'featured') {
                $tracks = $featuredPool ?? [];
                $playlist = ['tracks' => $tracks];
                $album = syzygy_schema_album($release, $playlist, null);
                $album['@type'] = 'MusicPlaylist';
                unset($album['numTracks']);
                $graph[] = $album;
                continue;
            }
            $playlist = $playlists[$release['playlist_key'] ?? ''] ?? ['tracks' => []];
            $graph[] = syzygy_schema_album($release, $playlist, $member ?: null);
        }

        $graph[] = syzygy_schema_solo_signals_list();

        switch ($page) {
            case 'home':
                $graph[] = syzygy_schema_breadcrumb([
                    ['name' => 'Home', 'path' => '/'],
                ]);
                $hasPart = [];
                foreach ($releases as $release) {
                    $slug = (string) ($release['slug'] ?? '');
                    if ($slug === '' || $slug === 'featured' || !empty($release['member_slug'])) {
                        continue;
                    }
                    $hasPart[] = ['@id' => syzygy_schema_id('album-' . $slug)];
                }
                $graph[] = syzygy_schema_webpage('webpage', $pageTitle, $pageDescription, '/', [
                    '@type' => ['WebPage', 'CollectionPage'],
                    'mainEntity' => ['@id' => syzygy_schema_id('musicgroup')],
                    'hasPart' => $hasPart,
                    'keywords' => $site['keywords'] ?? [],
                    'mentions' => array_merge(
                        [['@id' => syzygy_schema_id('solo-signals')]],
                        array_map(
                            static fn ($m) => ['@id' => syzygy_schema_id('person-' . ($m['slug'] ?? ''))],
                            $members
                        )
                    ),
                    'speakable' => [
                        '@type' => 'SpeakableSpecification',
                        'cssSelector' => ['.hero__title', '.hero__text', '.section__title', '.section__lede'],
                    ],
                ]);
                break;

            case 'about':
                $graph[] = syzygy_schema_breadcrumb([
                    ['name' => 'Home', 'path' => '/'],
                    ['name' => 'About', 'path' => '/about'],
                ]);
                $graph[] = syzygy_schema_webpage('webpage-about', $pageTitle, $pageDescription, '/about', [
                    '@type' => ['WebPage', 'AboutPage'],
                    'mainEntity' => ['@id' => syzygy_schema_id('musicgroup')],
                    'speakable' => [
                        '@type' => 'SpeakableSpecification',
                        'cssSelector' => ['.section__title', '.prose', '.section__lede'],
                    ],
                ]);
                break;

            case 'music':
                $graph[] = syzygy_schema_breadcrumb([
                    ['name' => 'Home', 'path' => '/'],
                    ['name' => 'Music', 'path' => '/music'],
                ]);
                $list = [];
                $pos = 0;
                foreach ($releases as $release) {
                    $pos++;
                    $list[] = [
                        '@type' => 'ListItem',
                        'position' => $pos,
                        'name' => $release['title'] ?? '',
                        'url' => syzygy_abs_url('/music/' . ($release['slug'] ?? '')),
                        'item' => ['@id' => syzygy_schema_id('album-' . ($release['slug'] ?? ''))],
                    ];
                }
                $graph[] = [
                    '@type' => 'ItemList',
                    '@id' => syzygy_schema_id('music-catalog'),
                    'name' => 'SYZYGY.VOID Music Catalog',
                    'numberOfItems' => count($list),
                    'itemListElement' => $list,
                ];
                $graph[] = syzygy_schema_webpage('webpage-music', $pageTitle, $pageDescription, '/music', [
                    '@type' => ['WebPage', 'CollectionPage'],
                    'mainEntity' => ['@id' => syzygy_schema_id('music-catalog')],
                ]);
                break;

            case 'music-detail':
                $release = $ctx['release'] ?? null;
                $playlist = $ctx['playlist'] ?? ['tracks' => []];
                if (!is_array($release)) {
                    break;
                }
                $slug = (string) ($release['slug'] ?? '');
                $graph[] = syzygy_schema_breadcrumb([
                    ['name' => 'Home', 'path' => '/'],
                    ['name' => 'Music', 'path' => '/music'],
                    ['name' => (string) ($release['title'] ?? 'Release'), 'path' => '/music/' . $slug],
                ]);
                $graph[] = syzygy_schema_webpage('webpage-music-' . $slug, $pageTitle, $pageDescription, '/music/' . $slug, [
                    'mainEntity' => ['@id' => syzygy_schema_id('album-' . $slug)],
                    'keywords' => array_values(array_filter([
                        $release['title'] ?? '',
                        $release['eyebrow'] ?? '',
                        'SYZYGY.VOID',
                        'cinematic darkwave',
                    ])),
                ]);
                break;

            case 'profiles':
                $graph[] = syzygy_schema_breadcrumb([
                    ['name' => 'Home', 'path' => '/'],
                    ['name' => 'Solo Signals', 'path' => '/profiles'],
                ]);
                $graph[] = syzygy_schema_webpage('webpage-profiles', $pageTitle, $pageDescription, '/profiles', [
                    '@type' => ['WebPage', 'CollectionPage'],
                    'mainEntity' => ['@id' => syzygy_schema_id('solo-signals')],
                ]);
                break;

            case 'profile-detail':
                $member = $ctx['member'] ?? null;
                if (!is_array($member)) {
                    break;
                }
                $slug = (string) ($member['slug'] ?? '');
                $graph[] = syzygy_schema_breadcrumb([
                    ['name' => 'Home', 'path' => '/'],
                    ['name' => 'Solo Signals', 'path' => '/profiles'],
                    ['name' => (string) ($member['name'] ?? 'Member'), 'path' => '/profiles/' . $slug],
                ]);
                $graph[] = syzygy_schema_webpage('webpage-profile-' . $slug, $pageTitle, $pageDescription, '/profiles/' . $slug, [
                    '@type' => ['WebPage', 'ProfilePage'],
                    'mainEntity' => ['@id' => syzygy_schema_id('person-' . $slug)],
                    'keywords' => array_merge(
                        [(string) ($member['name'] ?? ''), 'Solo Signals', 'SYZYGY.VOID'],
                        $member['sound_tags'] ?? []
                    ),
                ]);
                break;

            case 'lyrics':
                $graph[] = syzygy_schema_breadcrumb([
                    ['name' => 'Home', 'path' => '/'],
                    ['name' => 'Lyrics', 'path' => '/lyrics'],
                ]);
                $graph[] = syzygy_schema_webpage('webpage-lyrics', $pageTitle, $pageDescription, '/lyrics', [
                    '@type' => ['WebPage', 'CollectionPage'],
                    'mainEntity' => ['@id' => syzygy_schema_id('musicgroup')],
                ]);
                break;

            case 'lyrics-detail':
                $lyric = $ctx['lyric'] ?? null;
                if (!is_array($lyric)) {
                    break;
                }
                $slug = (string) ($lyric['slug'] ?? '');
                $releaseSlug = (string) ($lyric['release_slug'] ?? '');
                $graph[] = syzygy_schema_breadcrumb([
                    ['name' => 'Home', 'path' => '/'],
                    ['name' => 'Lyrics', 'path' => '/lyrics'],
                    ['name' => (string) ($lyric['title'] ?? 'Lyric'), 'path' => '/lyrics/' . $slug],
                ]);
                $creative = [
                    '@type' => 'CreativeWork',
                    '@id' => syzygy_schema_id('lyric-' . $slug),
                    'name' => ($lyric['title'] ?? '') . ' — Lyrics',
                    'headline' => $lyric['title'] ?? '',
                    'url' => syzygy_abs_url('/lyrics/' . $slug),
                    'inLanguage' => 'en-CA',
                    'isPartOf' => $releaseSlug !== ''
                        ? ['@id' => syzygy_schema_id('album-' . $releaseSlug)]
                        : ['@id' => syzygy_schema_id('musicgroup')],
                    'about' => ['@id' => syzygy_schema_id('track-' . $slug)],
                    'creator' => ['@id' => syzygy_schema_id('musicgroup')],
                ];
                if (($lyric['status'] ?? '') === 'ready' && trim((string) ($lyric['body'] ?? '')) !== '') {
                    $creative['text'] = (string) $lyric['body'];
                    $creative['encodingFormat'] = 'text/plain';
                }
                if (!empty($lyric['suno'])) {
                    $creative['sameAs'] = [(string) $lyric['suno']];
                }
                $graph[] = $creative;
                $graph[] = syzygy_schema_webpage('webpage-lyric-' . $slug, $pageTitle, $pageDescription, '/lyrics/' . $slug, [
                    'mainEntity' => ['@id' => syzygy_schema_id('lyric-' . $slug)],
                ]);
                break;

            case 'gallery':
                $graph[] = syzygy_schema_breadcrumb([
                    ['name' => 'Home', 'path' => '/'],
                    ['name' => 'Gallery', 'path' => '/gallery'],
                ]);
                $imageList = [];
                $pos = 0;
                foreach (($gallery['items'] ?? []) as $item) {
                    if ($pos >= 24) {
                        break;
                    }
                    $pos++;
                    $src = (string) ($item['raw_src'] ?? $item['src'] ?? '');
                    if ($src === '') {
                        continue;
                    }
                    // Prefer unencoded raw paths when available.
                    $abs = str_starts_with($src, 'http') ? $src : syzygy_abs_url(urldecode($src));
                    $imageList[] = [
                        '@type' => 'ImageObject',
                        'name' => $item['title'] ?? ('Gallery image ' . $pos),
                        'contentUrl' => $abs,
                        'url' => $abs,
                    ];
                }
                $graph[] = [
                    '@type' => 'ImageGallery',
                    '@id' => syzygy_schema_id('gallery'),
                    'name' => 'SYZYGY.VOID Visual Archive',
                    'url' => syzygy_abs_url('/gallery'),
                    'description' => $pageDescription,
                    'image' => $imageList,
                ];
                $graph[] = syzygy_schema_webpage('webpage-gallery', $pageTitle, $pageDescription, '/gallery', [
                    '@type' => ['WebPage', 'CollectionPage'],
                    'mainEntity' => ['@id' => syzygy_schema_id('gallery')],
                ]);
                break;

            case 'merch':
                $graph[] = syzygy_schema_breadcrumb([
                    ['name' => 'Home', 'path' => '/'],
                    ['name' => 'Merch', 'path' => '/merch'],
                ]);
                $merchList = [];
                $pos = 0;
                foreach (($merch['releases'] ?? []) as $release) {
                    $pos++;
                    $merchList[] = [
                        '@type' => 'ListItem',
                        'position' => $pos,
                        'name' => $release['name'] ?? '',
                        'url' => syzygy_abs_url('/merch'),
                    ];
                }
                $graph[] = [
                    '@type' => 'CollectionPage',
                    '@id' => syzygy_schema_id('merch'),
                    'name' => 'SYZYGY.VOID Archive Store',
                    'url' => syzygy_abs_url('/merch'),
                    'description' => $pageDescription,
                    'mainEntity' => [
                        '@type' => 'ItemList',
                        'numberOfItems' => count($merchList),
                        'itemListElement' => $merchList,
                    ],
                ];
                $graph[] = syzygy_schema_webpage('webpage-merch', $pageTitle, $pageDescription, '/merch', [
                    '@type' => ['WebPage', 'CollectionPage'],
                    'mainEntity' => ['@id' => syzygy_schema_id('merch')],
                ]);
                break;

            case 'blog':
                $graph[] = syzygy_schema_breadcrumb([
                    ['name' => 'Home', 'path' => '/'],
                    ['name' => 'Journals', 'path' => '/blog'],
                ]);
                $posts = [];
                $pos = 0;
                foreach (syzygy_blog_posts_newest_first() as $post) {
                    $pos++;
                    $posts[] = [
                        '@type' => 'ListItem',
                        'position' => $pos,
                        'name' => $post['title'] ?? '',
                        'url' => syzygy_abs_url('/blog/' . ($post['slug'] ?? '')),
                    ];
                }
                $graph[] = [
                    '@type' => 'Blog',
                    '@id' => syzygy_schema_id('blog'),
                    'name' => 'SYZYGY.VOID Journals',
                    'url' => syzygy_abs_url('/blog'),
                    'description' => $pageDescription,
                    'publisher' => ['@id' => syzygy_schema_id('organization')],
                    'blogPost' => array_map(
                        static fn ($p) => [
                            '@type' => 'BlogPosting',
                            '@id' => syzygy_schema_id('blog-' . ($p['slug'] ?? '')),
                            'headline' => $p['title'] ?? '',
                            'url' => syzygy_abs_url('/blog/' . ($p['slug'] ?? '')),
                            'datePublished' => $p['date'] ?? null,
                            'description' => $p['excerpt'] ?? '',
                        ],
                        syzygy_blog_posts_newest_first()
                    ),
                ];
                $graph[] = syzygy_schema_webpage('webpage-blog', $pageTitle, $pageDescription, '/blog', [
                    '@type' => ['WebPage', 'CollectionPage'],
                    'mainEntity' => ['@id' => syzygy_schema_id('blog')],
                ]);
                break;

            case 'blog-detail':
                $post = $ctx['post'] ?? null;
                if (!is_array($post)) {
                    break;
                }
                $slug = (string) ($post['slug'] ?? '');
                $graph[] = syzygy_schema_breadcrumb([
                    ['name' => 'Home', 'path' => '/'],
                    ['name' => 'Journals', 'path' => '/blog'],
                    ['name' => (string) ($post['title'] ?? 'Journal'), 'path' => '/blog/' . $slug],
                ]);
                $article = [
                    '@type' => 'BlogPosting',
                    '@id' => syzygy_schema_id('blog-' . $slug),
                    'headline' => $post['title'] ?? '',
                    'description' => $post['excerpt'] ?? '',
                    'datePublished' => $post['date'] ?? null,
                    'dateModified' => $post['date'] ?? null,
                    'author' => ['@id' => syzygy_schema_id('organization')],
                    'publisher' => ['@id' => syzygy_schema_id('organization')],
                    'mainEntityOfPage' => syzygy_abs_url('/blog/' . $slug),
                    'url' => syzygy_abs_url('/blog/' . $slug),
                    'articleSection' => $post['eyebrow'] ?? 'Journals',
                    'inLanguage' => 'en-CA',
                ];
                if (!empty($post['image'])) {
                    $article['image'] = syzygy_abs_url((string) $post['image']);
                }
                if (!empty($post['body'])) {
                    $article['articleBody'] = trim(html_entity_decode(strip_tags((string) $post['body'])));
                }
                $graph[] = $article;
                $graph[] = syzygy_schema_webpage('webpage-blog-' . $slug, $pageTitle, $pageDescription, '/blog/' . $slug, [
                    'mainEntity' => ['@id' => syzygy_schema_id('blog-' . $slug)],
                ]);
                break;

            case 'contact':
                $graph[] = syzygy_schema_breadcrumb([
                    ['name' => 'Home', 'path' => '/'],
                    ['name' => 'Contact', 'path' => '/contact'],
                ]);
                $graph[] = syzygy_schema_webpage('webpage-contact', $pageTitle, $pageDescription, '/contact', [
                    '@type' => ['WebPage', 'ContactPage'],
                    'mainEntity' => ['@id' => syzygy_schema_id('organization')],
                ]);
                break;

            case 'booking':
                $graph[] = syzygy_schema_breadcrumb([
                    ['name' => 'Home', 'path' => '/'],
                    ['name' => 'Booking', 'path' => '/booking'],
                ]);
                $graph[] = syzygy_schema_webpage('webpage-booking', $pageTitle, $pageDescription, '/booking', [
                    '@type' => 'WebPage',
                ]);
                break;

            case '404':
            default:
                $graph[] = syzygy_schema_breadcrumb([
                    ['name' => 'Home', 'path' => '/'],
                    ['name' => 'Not Found', 'path' => $path],
                ]);
                $graph[] = syzygy_schema_webpage('webpage-404', $pageTitle, $pageDescription ?: 'Page not found.', $path, [
                    '@type' => 'WebPage',
                ]);
                break;
        }

        return $graph;
    }
}

if (!function_exists('syzygy_print_json_ld')) {
    function syzygy_print_json_ld(array $graph): void
    {
        $payload = [
            '@context' => 'https://schema.org',
            '@graph' => array_values($graph),
        ];

        $json = json_encode(
            $payload,
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS
        );

        if (!is_string($json) || $json === '') {
            return;
        }

        echo '<script type="application/ld+json">' . $json . '</script>' . "\n";
    }
}
