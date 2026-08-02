<?php
declare(strict_types=1);

if (!function_exists('syzygy_esc')) {
    function syzygy_esc($value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('syzygy_slugify')) {
    function syzygy_slugify(string $value): string
    {
        $value = trim($value);

        if ($value === '') {
            return '';
        }

        if (class_exists('Transliterator')) {
            $converted = transliterator_transliterate('Any-Latin; Latin-ASCII;', $value);
            if (is_string($converted) && $converted !== '') {
                $value = $converted;
            }
        } else {
            $converted = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value);
            if ($converted !== false && $converted !== '') {
                $value = $converted;
            }
        }

        $value = strtolower($value);
        $value = preg_replace('/[^a-z0-9]+/', '-', $value) ?? '';
        return trim($value, '-');
    }
}

if (!function_exists('syzygy_url')) {
    function syzygy_url(string $path = '/'): string
    {
        $path = '/' . ltrim($path, '/');
        if ($path === '/') {
            return syzygy_encode_public_path('/');
        }
        return syzygy_encode_public_path($path);
    }
}

if (!function_exists('syzygy_current_path')) {
    function syzygy_current_path(): string
    {
        $uri = (string) ($_SERVER['REQUEST_URI'] ?? '/');
        $path = (string) (parse_url($uri, PHP_URL_PATH) ?? '/');
        $base = syzygy_base_path();

        if ($base !== '' && str_starts_with($path, $base)) {
            $path = substr($path, strlen($base)) ?: '/';
        }

        $path = '/' . ltrim($path, '/');
        return $path === '/' ? '/' : rtrim($path, '/');
    }
}

if (!function_exists('syzygy_is_current')) {
    function syzygy_is_current(string $href): bool
    {
        $current = syzygy_current_path();
        $hrefPath = parse_url($href, PHP_URL_PATH) ?? $href;
        $base = syzygy_base_path();

        if ($base !== '' && is_string($hrefPath) && str_starts_with($hrefPath, $base)) {
            $hrefPath = substr($hrefPath, strlen($base)) ?: '/';
        }

        $hrefPath = '/' . ltrim((string) $hrefPath, '/');
        $hrefPath = $hrefPath === '/' ? '/' : rtrim($hrefPath, '/');

        if ($hrefPath === '/') {
            return $current === '/';
        }

        return $current === $hrefPath || str_starts_with($current, $hrefPath . '/');
    }
}

if (!function_exists('syzygy_platform_links')) {
    /**
     * Normalize platform link map. Empty strings render as "Link soon".
     *
     * @param array<string,string|null> $links
     * @return list<array{key:string,label:string,href:string,ready:bool}>
     */
    function syzygy_platform_links(array $links): array
    {
        $order = [
            'suno' => 'Suno',
            'spotify' => 'Spotify',
            'apple' => 'Apple Music',
            'amazon' => 'Amazon Music',
            'youtube' => 'YouTube',
        ];

        $out = [];
        foreach ($order as $key => $label) {
            if (!array_key_exists($key, $links)) {
                continue;
            }
            $href = trim((string) ($links[$key] ?? ''));
            $out[] = [
                'key' => $key,
                'label' => $label,
                'href' => $href,
                'ready' => $href !== '' && $href !== '#',
            ];
        }

        return $out;
    }
}

if (!function_exists('syzygy_find_release')) {
    function syzygy_find_release(string $slug): ?array
    {
        global $releases;
        foreach ($releases as $release) {
            if (($release['slug'] ?? '') === $slug) {
                return $release;
            }
        }
        return null;
    }
}

if (!function_exists('syzygy_find_member')) {
    function syzygy_find_member(string $slug): ?array
    {
        global $members;
        foreach ($members as $member) {
            if (($member['slug'] ?? '') === $slug) {
                return $member;
            }
        }
        return null;
    }
}

if (!function_exists('syzygy_find_blog_post')) {
    function syzygy_find_blog_post(string $slug): ?array
    {
        global $blogPosts;
        foreach ($blogPosts as $post) {
            if (($post['slug'] ?? '') === $slug) {
                return $post;
            }
        }
        return null;
    }
}

if (!function_exists('syzygy_find_lyric')) {
    function syzygy_find_lyric(string $slug): ?array
    {
        global $lyrics;
        return $lyrics[$slug] ?? null;
    }
}

if (!function_exists('syzygy_render')) {
    function syzygy_render(string $page, array $vars = []): void
    {
        global $site, $releases, $playlists, $trackTabs, $featuredPool, $members, $gallery, $merch, $blogPosts, $lyrics, $contact, $foreverLandTeaser, $contactFormState;

        extract($vars, EXTR_SKIP);
        $pageFile = dirname(__DIR__) . '/pages/' . $page . '.php';

        if (!is_file($pageFile)) {
            http_response_code(404);
            $pageFile = dirname(__DIR__) . '/pages/404.php';
            $pageTitle = ($site['name'] ?? 'SYZYGY.VOID') . ' | 404';
        }

        require dirname(__DIR__) . '/includes/layout-start.php';
        require $pageFile;
        require dirname(__DIR__) . '/includes/layout-end.php';
    }
}
