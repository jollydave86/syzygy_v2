<?php
declare(strict_types=1);

$manifest = require __DIR__ . '/gallery-manifest.php';

if (!function_exists('syzygy_gallery_variant_label')) {
    function syzygy_gallery_variant_label(string $variant): string
    {
        $variant = trim($variant);

        if ($variant === '') {
            return '';
        }

        return ucwords(str_replace(['_', '-'], ' ', $variant));
    }
}

if (!function_exists('syzygy_gallery_normalize_key')) {
    function syzygy_gallery_normalize_key(string $value): string
    {
        $value = html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
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
        $value = preg_replace('/^\s*\d+\s*-\s*/', '', $value) ?? $value;
        $value = preg_replace('/\.(jpg|jpeg|png|webp)$/i', '', $value) ?? $value;
        $value = preg_replace('/-(400|800|1200)$/i', '', $value) ?? $value;

        $value = str_replace(
            ['’', "'", '&', '//', '/', ':', ' - ', '—', '–'],
            [' ', ' ', ' and ', ' ', ' ', ' ', ' ', ' ', ' '],
            $value
        );

        $value = preg_replace('/[^a-z0-9]+/', ' ', $value) ?? $value;
        $value = trim($value);

        return preg_replace('/\s+/', ' ', $value) ?? $value;
    }
}

if (!function_exists('syzygy_gallery_normalize_filter_id')) {
    function syzygy_gallery_normalize_filter_id(string $value): string
    {
        $value = html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
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
        $value = str_replace(['&', '//', '/', ':', '—', '–'], [' and ', ' ', ' ', ' ', ' ', ' '], $value);
        $value = preg_replace('/[^a-z0-9]+/', '-', $value) ?? $value;
        $value = trim($value, '-');

        return $value;
    }
}

if (!function_exists('syzygy_gallery_scan_folder')) {
    function syzygy_gallery_scan_folder(string $folder): array
    {
        $folder = trim($folder);

        if ($folder === '') {
            return [];
        }

        $projectRoot = dirname(__DIR__);
        $absoluteFolder = $projectRoot
            . DIRECTORY_SEPARATOR . 'assets'
            . DIRECTORY_SEPARATOR . 'img-optimized'
            . DIRECTORY_SEPARATOR . 'gallery'
            . DIRECTORY_SEPARATOR . $folder;

        if (!is_dir($absoluteFolder)) {
            return [];
        }

        $files = scandir($absoluteFolder);
        if ($files === false) {
            return [];
        }

        natsort($files);

        $indexed = [];

        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            if (!preg_match('/\.webp$/i', $file)) {
                continue;
            }

            if (preg_match('/^00\s*-\s*playlist\s+cover/i', $file)) {
                continue;
            }

            if (preg_match('/-(400|800|1200)\.webp$/i', $file)) {
                continue;
            }

            $rawRelative = '/assets/img-optimized/gallery/' . $folder . '/' . $file;
            $baseName = (string) pathinfo($file, PATHINFO_FILENAME);

            $indexed[] = [
                'file' => $file,
                'base_name' => $baseName,
                'raw_src' => $rawRelative,
                'src' => syzygy_encode_public_path($rawRelative),
                'thumb_src' => syzygy_prefer_public_variant($rawRelative, [400, 800]),
                'full_src' => syzygy_prefer_public_variant($rawRelative, [1200, 800]),
                'key' => syzygy_gallery_normalize_key($file),
                'base_key' => syzygy_gallery_normalize_key($baseName),
            ];
        }

        return array_values($indexed);
    }
}

if (!function_exists('syzygy_gallery_match_local_file')) {
    function syzygy_gallery_match_local_file(array $item, array &$folderFiles): ?array
    {
        $declaredFile = trim((string) ($item['file'] ?? ''));

        if ($declaredFile !== '') {
            $declaredBaseKey = syzygy_gallery_normalize_key((string) pathinfo($declaredFile, PATHINFO_FILENAME));

            if ($declaredBaseKey !== '') {
                foreach ($folderFiles as $index => $file) {
                    if (($file['base_key'] ?? '') === $declaredBaseKey) {
                        $match = $file;
                        unset($folderFiles[$index]);
                        $folderFiles = array_values($folderFiles);
                        return $match;
                    }
                }
            }
        }

        $title = trim((string) ($item['title'] ?? ''));
        $variant = trim((string) ($item['variant'] ?? ''));
        $variantLabel = syzygy_gallery_variant_label($variant);

        $candidates = [
            syzygy_gallery_normalize_key($title),
        ];

        if ($variantLabel !== '') {
            $candidates[] = syzygy_gallery_normalize_key($title . ' ' . $variantLabel);
            $candidates[] = syzygy_gallery_normalize_key($title . ' ' . $variant);
        }

        $candidates = array_values(array_unique(array_filter($candidates)));

        foreach ($candidates as $candidate) {
            foreach ($folderFiles as $index => $file) {
                $fileKey = (string) ($file['key'] ?? '');
                $fileBaseKey = (string) ($file['base_key'] ?? '');

                if ($fileKey === $candidate || $fileBaseKey === $candidate) {
                    $match = $file;
                    unset($folderFiles[$index]);
                    $folderFiles = array_values($folderFiles);
                    return $match;
                }
            }
        }

        if (!empty($candidates)) {
            $primary = $candidates[0];

            foreach ($folderFiles as $index => $file) {
                $fileKey = (string) ($file['key'] ?? '');
                $fileBaseKey = (string) ($file['base_key'] ?? '');

                if (
                    ($fileKey !== '' && (strpos($fileKey, $primary) !== false || strpos($primary, $fileKey) !== false))
                    || ($fileBaseKey !== '' && (strpos($fileBaseKey, $primary) !== false || strpos($primary, $fileBaseKey) !== false))
                ) {
                    $match = $file;
                    unset($folderFiles[$index]);
                    $folderFiles = array_values($folderFiles);
                    return $match;
                }
            }
        }

        return null;
    }
}

$filters = [];
$items = [];
$missing = [];
$initialCount = 12;

foreach ($manifest as $playlistIndex => $playlist) {
    $playlistLabel = trim((string) ($playlist['label'] ?? ''));
    $playlistFolder = trim((string) ($playlist['folder'] ?? ''));
    $playlistItemsSource = $playlist['items'] ?? [];

    $rawPlaylistId = trim((string) ($playlist['id'] ?? ''));
    $playlistIdSource = $rawPlaylistId !== '' ? $rawPlaylistId : ($playlistLabel !== '' ? $playlistLabel : 'playlist-' . ($playlistIndex + 1));
    $playlistId = syzygy_gallery_normalize_filter_id($playlistIdSource);

    if ($playlistId === '' || $playlistLabel === '' || $playlistFolder === '' || !is_array($playlistItemsSource)) {
        continue;
    }

    $folderFiles = syzygy_gallery_scan_folder($playlistFolder);
    $playlistItems = [];

    foreach ($playlistItemsSource as $index => $item) {
        if (!is_array($item)) {
            continue;
        }

        $title = trim((string) ($item['title'] ?? ''));
        if ($title === '') {
            continue;
        }

        $match = syzygy_gallery_match_local_file($item, $folderFiles);

        if ($match === null) {
            if (!empty($folderFiles)) {
                $match = array_shift($folderFiles);
            } else {
                $missing[] = [
                    'playlist_id' => $playlistId,
                    'playlist' => $playlistLabel,
                    'title' => $title,
                    'variant' => trim((string) ($item['variant'] ?? '')),
                    'folder' => $playlistFolder,
                ];
                continue;
            }
        }

        $variant = trim((string) ($item['variant'] ?? ''));
        $variantLabel = syzygy_gallery_variant_label($variant);
        $displayTitle = $variantLabel !== '' ? $title . ' — ' . $variantLabel : $title;

        $thumbSrc = (string) ($match['thumb_src'] ?? $match['src'] ?? '');
        $fullSrc = (string) ($match['full_src'] ?? $match['src'] ?? '');

        $playlistItems[] = [
            'id' => $playlistId . '-' . ($index + 1),
            'filter_id' => $playlistId,
            'playlist' => $playlistLabel,
            'title' => $title,
            'display_title' => $displayTitle,
            'variant' => $variant,
            'variant_label' => $variantLabel,
            'src' => $thumbSrc,
            'thumb_src' => $thumbSrc,
            'full_src' => $fullSrc,
            'raw_src' => (string) ($match['raw_src'] ?? ''),
            'random_eligible' => !array_key_exists('random_eligible', $item) || !empty($item['random_eligible']),
            'alt' => $displayTitle . ' artwork from ' . $playlistLabel,
        ];
    }

    if (!empty($playlistItems)) {
        $filters[] = [
            'id' => $playlistId,
            'label' => $playlistLabel,
            'count' => count($playlistItems),
        ];

        $items = array_merge($items, $playlistItems);
    }
}

return [
    'initial_count' => $initialCount,
    'filters' => $filters,
    'items' => $items,
    'missing' => $missing,
];