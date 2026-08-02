<?php
declare(strict_types=1);

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
        'pageDescription' => 'The requested transmission could not be found.',
    ]);
    exit;
}

switch ($route) {
    case '':
        syzygy_render('home', [
            'pageTitle' => $site['title'],
            'pageDescription' => $site['description'],
        ]);
        break;

    case 'about':
        syzygy_render('about', [
            'pageTitle' => 'About | ' . $site['name'],
            'pageDescription' => $site['about']['text'],
        ]);
        break;

    case 'music':
        if ($param === null) {
            syzygy_render('music', [
                'pageTitle' => 'Music | ' . $site['name'],
                'pageDescription' => 'Explore SYZYGY.VOID release worlds and featured transmissions.',
            ]);
            break;
        }
        $release = syzygy_find_release($param);
        if (!$release) {
            http_response_code(404);
            syzygy_render('404', ['pageTitle' => $site['name'] . ' | Page Not Found']);
            break;
        }
        $playlist = ($release['playlist_key'] ?? '') === 'featured'
            ? ['label' => 'FEATURED SIGNALS', 'tracks' => $featuredPool]
            : ($playlists[$release['playlist_key']] ?? ['tracks' => []]);
        syzygy_render('music-detail', [
            'pageTitle' => $release['title'] . ' | ' . $site['name'],
            'pageDescription' => $release['summary'],
            'release' => $release,
            'playlist' => $playlist,
        ]);
        break;

    case 'profiles':
        if ($param === null) {
            syzygy_render('profiles', [
                'pageTitle' => 'Solo Signals | ' . $site['name'],
                'pageDescription' => 'Member and legacy transmissions from the SYZYGY.VOID Solo Signals system.',
            ]);
            break;
        }
        $member = syzygy_find_member($param);
        if (!$member) {
            http_response_code(404);
            syzygy_render('404', ['pageTitle' => $site['name'] . ' | Page Not Found']);
            break;
        }
        syzygy_render('profile-detail', [
            'pageTitle' => $member['name'] . ' | ' . $site['name'],
            'pageDescription' => $member['teaser'],
            'member' => $member,
        ]);
        break;

    case 'lyrics':
        if ($param === null) {
            syzygy_render('lyrics', [
                'pageTitle' => 'Lyrics | ' . $site['name'],
                'pageDescription' => 'Lyrics transmissions from the SYZYGY.VOID catalog.',
            ]);
            break;
        }
        $lyric = syzygy_find_lyric($param);
        if (!$lyric) {
            http_response_code(404);
            syzygy_render('404', ['pageTitle' => $site['name'] . ' | Page Not Found']);
            break;
        }
        syzygy_render('lyrics-detail', [
            'pageTitle' => $lyric['title'] . ' — Lyrics | ' . $site['name'],
            'pageDescription' => 'Lyrics for ' . $lyric['title'],
            'lyric' => $lyric,
        ]);
        break;

    case 'gallery':
        syzygy_render('gallery', [
            'pageTitle' => 'Gallery | ' . $site['name'],
            'pageDescription' => 'Visual archive organized by SYZYGY.VOID release.',
        ]);
        break;

    case 'merch':
        syzygy_render('merch', [
            'pageTitle' => 'Merch | ' . $site['name'],
            'pageDescription' => 'SYZYGY.VOID merch mockups — pre order soon.',
        ]);
        break;

    case 'blog':
        if ($param === null) {
            syzygy_render('blog', [
                'pageTitle' => 'Journals | ' . $site['name'],
                'pageDescription' => 'Working journals from the SYZYGY.VOID signal.',
            ]);
            break;
        }
        $post = syzygy_find_blog_post($param);
        if (!$post) {
            http_response_code(404);
            syzygy_render('404', ['pageTitle' => $site['name'] . ' | Page Not Found']);
            break;
        }
        syzygy_render('blog-detail', [
            'pageTitle' => $post['title'] . ' | ' . $site['name'],
            'pageDescription' => $post['excerpt'],
            'post' => $post,
        ]);
        break;

    case 'contact':
        syzygy_render('contact', [
            'pageTitle' => 'Contact | ' . $site['name'],
            'pageDescription' => 'Send a transmission to SYZYGY.VOID.',
        ]);
        break;

    case 'booking':
        syzygy_render('booking', [
            'pageTitle' => 'Booking | ' . $site['name'],
            'pageDescription' => 'Fun booking page — SYZYGY.VOID does not offer services.',
        ]);
        break;

    case '404':
        http_response_code(404);
        syzygy_render('404', [
            'pageTitle' => $site['name'] . ' | Page Not Found',
        ]);
        break;

    default:
        http_response_code(404);
        syzygy_render('404', [
            'pageTitle' => $site['name'] . ' | Page Not Found',
        ]);
        break;
}
