<?php
declare(strict_types=1);

if (PHP_SAPI === 'cli-server') {
    $cliPath = parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH) ?: '/';
    $cliFile = __DIR__ . $cliPath;
    if ($cliPath !== '/' && is_file($cliFile)) {
        return false;
    }
}

require __DIR__ . '/includes/bootstrap.php';

$path = syzygy_current_path();
$segments = array_values(array_filter(explode('/', trim($path, '/')), static fn ($s) => $s !== ''));

$route = $segments[0] ?? '';
$param = $segments[1] ?? null;

// Extra segments beyond detail slug => 404
if (count($segments) > 2) {
    http_response_code(404);
    syzygy_render('404', [
        'pageTitle' => $site['name'] . ' | Page Not Found',
        'pageDescription' => 'The requested transmission could not be found inside the SYZYGY.VOID archive.',
        'schemaPage' => '404',
    ]);
    exit;
}

switch ($route) {
    case '':
        syzygy_render('home', [
            'pageTitle' => $site['title'],
            'pageDescription' => $site['description'],
            'pageKeywords' => $site['keywords'] ?? [],
            'schemaPage' => 'home',
        ]);
        break;

    case 'about':
        syzygy_render('about', [
            'pageTitle' => 'About SYZYGY.VOID | Cinematic Dark-Futurist Music Project',
            'pageDescription' => ($site['about']['text'] ?? '') . ' ' . ($site['about']['text_2'] ?? ''),
            'pageKeywords' => array_merge($site['keywords'] ?? [], ['about SYZYGY.VOID', 'Montreal electronic music', 'darkwave project']),
            'schemaPage' => 'about',
        ]);
        break;

    case 'music':
        if ($param === null) {
            syzygy_render('music', [
                'pageTitle' => 'Music Catalog | SYZYGY.VOID Releases & Solo Signals',
                'pageDescription' => 'Browse SYZYGY.VOID serialized release worlds and Solo Signals albums — cinematic darkwave, industrial electronics, and chapter-based story systems.',
                'pageKeywords' => array_merge($site['keywords'] ?? [], ['music catalog', 'album releases', 'Solo Signals albums']),
                'schemaPage' => 'music',
            ]);
            break;
        }
        $release = syzygy_find_release($param);
        if (!$release) {
            http_response_code(404);
            syzygy_render('404', [
                'pageTitle' => $site['name'] . ' | Page Not Found',
                'pageDescription' => 'Release not found.',
                'schemaPage' => '404',
            ]);
            break;
        }
        $playlist = ($release['playlist_key'] ?? '') === 'featured'
            ? ['label' => 'FEATURED SIGNALS', 'tracks' => $featuredPool]
            : ($playlists[$release['playlist_key']] ?? ['tracks' => []]);
        $trackCount = count($playlist['tracks'] ?? []);
        syzygy_render('music-detail', [
            'pageTitle' => ($release['title'] ?? 'Release') . ' | Music | ' . $site['name'],
            'pageDescription' => trim(($release['summary'] ?? '') . ' ' . ($release['eyebrow'] ?? '') . ($trackCount ? " {$trackCount} transmissions." : '')),
            'pageKeywords' => array_values(array_filter([
                $release['title'] ?? '',
                $release['eyebrow'] ?? '',
                'SYZYGY.VOID',
                'cinematic darkwave',
                'electronic album',
            ])),
            'pageOgImage' => $release['cover'] ?? null,
            'pageOgImageAlt' => ($release['title'] ?? 'SYZYGY.VOID') . ' cover art',
            'pageOgType' => 'music.album',
            'schemaPage' => 'music-detail',
            'release' => $release,
            'playlist' => $playlist,
        ]);
        break;

    case 'profiles':
        if ($param === null) {
            syzygy_render('profiles', [
                'pageTitle' => 'Solo Signals | SYZYGY.VOID Members & Legacy Transmissions',
                'pageDescription' => 'Meet Nova Vale, Ash Vex, Lyra Static, Lucien Cross, Vanta Rey, and Kade Null — Solo Signals members of the SYZYGY.VOID cinematic music universe.',
                'pageKeywords' => array_merge($site['keywords'] ?? [], [
                    'Solo Signals',
                    'Nova Vale',
                    'Ash Vex',
                    'Lyra Static',
                    'Lucien Cross',
                    'Vanta Rey',
                    'Kade Null',
                ]),
                'schemaPage' => 'profiles',
            ]);
            break;
        }
        $member = syzygy_find_member($param);
        if (!$member) {
            http_response_code(404);
            syzygy_render('404', [
                'pageTitle' => $site['name'] . ' | Page Not Found',
                'pageDescription' => 'Member not found.',
                'schemaPage' => '404',
            ]);
            break;
        }
        syzygy_render('profile-detail', [
            'pageTitle' => ($member['name'] ?? 'Member') . ' | Solo Signals | ' . $site['name'],
            'pageDescription' => trim(($member['teaser'] ?? '') . ' ' . ($member['bio'] ?? '')),
            'pageKeywords' => array_merge(
                [(string) ($member['name'] ?? ''), (string) ($member['release'] ?? ''), 'Solo Signals', 'SYZYGY.VOID'],
                $member['sound_tags'] ?? []
            ),
            'pageOgImage' => $member['image'] ?? null,
            'pageOgImageAlt' => ($member['name'] ?? 'SYZYGY.VOID') . ' portrait',
            'pageOgType' => 'profile',
            'schemaPage' => 'profile-detail',
            'member' => $member,
        ]);
        break;

    case 'lyrics':
        if ($param === null) {
            syzygy_render('lyrics', [
                'pageTitle' => 'Lyrics Archive | SYZYGY.VOID Song Transmissions',
                'pageDescription' => 'Read lyrics from SYZYGY.VOID release worlds and Solo Signals — chapter transmissions, industrial narratives, and darkwave song texts.',
                'pageKeywords' => array_merge($site['keywords'] ?? [], ['lyrics', 'song lyrics', 'album lyrics']),
                'schemaPage' => 'lyrics',
            ]);
            break;
        }
        $lyric = syzygy_find_lyric($param);
        if (!$lyric) {
            http_response_code(404);
            syzygy_render('404', [
                'pageTitle' => $site['name'] . ' | Page Not Found',
                'pageDescription' => 'Lyric not found.',
                'schemaPage' => '404',
            ]);
            break;
        }
        $readySnippet = (($lyric['status'] ?? '') === 'ready')
            ? substr(trim(preg_replace('/\s+/', ' ', (string) ($lyric['body'] ?? '')) ?? ''), 0, 155)
            : '';
        syzygy_render('lyrics-detail', [
            'pageTitle' => ($lyric['title'] ?? 'Track') . ' Lyrics | ' . ($lyric['release_title'] ?? 'SYZYGY.VOID'),
            'pageDescription' => $readySnippet !== ''
                ? $readySnippet
                : ('Official lyrics page for ' . ($lyric['title'] ?? 'this track') . ' from ' . ($lyric['release_title'] ?? 'SYZYGY.VOID') . '.'),
            'pageKeywords' => array_values(array_filter([
                $lyric['title'] ?? '',
                $lyric['release_title'] ?? '',
                'lyrics',
                'SYZYGY.VOID',
            ])),
            'schemaPage' => 'lyrics-detail',
            'lyric' => $lyric,
        ]);
        break;

    case 'gallery':
        syzygy_render('gallery', [
            'pageTitle' => 'Gallery | SYZYGY.VOID Visual Archive',
            'pageDescription' => 'Explore SYZYGY.VOID cover art and track visuals organized by release world — cinematic darkwave imagery from the expanding visual archive.',
            'pageKeywords' => array_merge($site['keywords'] ?? [], ['gallery', 'album artwork', 'cover art archive']),
            'schemaPage' => 'gallery',
        ]);
        break;

    case 'merch':
        syzygy_render('merch', [
            'pageTitle' => 'Merch | SYZYGY.VOID Preorder Placeholders',
            'pageDescription' => 'SYZYGY.VOID merch concepts — Solo Signals and world-album CD, vinyl, cassette, and apparel placeholders. Compressed WebP. Preorder only.',
            'pageKeywords' => array_merge($site['keywords'] ?? [], ['merch', 'band merch', 'preorder', 'vinyl', 'cassette', 'solo signals merch']),
            'schemaPage' => 'merch',
        ]);
        break;

    case 'blog':
        if ($param === null) {
            syzygy_render('blog', [
                'pageTitle' => 'Journals | SYZYGY.VOID Project Notes',
                'pageDescription' => 'Working journals from the SYZYGY.VOID signal — origin notes, era fractures, Chapel Protocol history, and Solo Signals context.',
                'pageKeywords' => array_merge($site['keywords'] ?? [], ['blog', 'journals', 'music project notes']),
                'schemaPage' => 'blog',
            ]);
            break;
        }
        $post = syzygy_find_blog_post($param);
        if (!$post) {
            http_response_code(404);
            syzygy_render('404', [
                'pageTitle' => $site['name'] . ' | Page Not Found',
                'pageDescription' => 'Journal post not found.',
                'schemaPage' => '404',
            ]);
            break;
        }
        syzygy_render('blog-detail', [
            'pageTitle' => ($post['title'] ?? 'Journal') . ' | Journals | ' . $site['name'],
            'pageDescription' => $post['excerpt'] ?? '',
            'pageKeywords' => array_values(array_filter([
                $post['title'] ?? '',
                $post['eyebrow'] ?? '',
                'SYZYGY.VOID journal',
            ])),
            'pageOgImage' => $post['image'] ?? null,
            'pageOgType' => 'article',
            'schemaPage' => 'blog-detail',
            'post' => $post,
        ]);
        break;

    case 'contact':
        syzygy_render('contact', [
            'pageTitle' => 'Contact SYZYGY.VOID | Collaborations & Media',
            'pageDescription' => 'Contact SYZYGY.VOID for collaborations, media, features, and general transmissions from the cinematic darkwave project.',
            'pageKeywords' => array_merge($site['keywords'] ?? [], ['contact', 'booking contact', 'music collaboration']),
            'schemaPage' => 'contact',
        ]);
        break;

    case 'booking':
        syzygy_render('booking', [
            'pageTitle' => 'Booking | SYZYGY.VOID',
            'pageDescription' => 'Fun booking page for SYZYGY.VOID — the project does not currently offer traditional services; send a playful transmission instead.',
            'schemaPage' => 'booking',
        ]);
        break;

    case '404':
        http_response_code(404);
        syzygy_render('404', [
            'pageTitle' => $site['name'] . ' | Page Not Found',
            'pageDescription' => 'The requested transmission could not be found.',
            'schemaPage' => '404',
        ]);
        break;

    default:
        http_response_code(404);
        syzygy_render('404', [
            'pageTitle' => $site['name'] . ' | Page Not Found',
            'pageDescription' => 'The requested transmission could not be found.',
            'schemaPage' => '404',
        ]);
        break;
}
