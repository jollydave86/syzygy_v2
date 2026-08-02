<?php
declare(strict_types=1);

if (!function_exists('syzygy_encode_public_path')) {
    function syzygy_encode_public_path(string $path): string
    {
        $path = trim($path);

        if ($path === '' || strpos($path, '/') !== 0) {
            return $path;
        }

        $segments = explode('/', $path);
        $segments = array_map(
            static function (string $segment): string {
                return $segment === '' ? '' : rawurlencode($segment);
            },
            $segments
        );

        return implode('/', $segments);
    }
}

if (!function_exists('syzygy_public_path_exists')) {
    function syzygy_public_path_exists(string $publicPath): bool
    {
        $publicPath = trim($publicPath);

        if ($publicPath === '' || strpos($publicPath, '/') !== 0) {
            return false;
        }

        $absolutePath = dirname(__DIR__) . str_replace('/', DIRECTORY_SEPARATOR, $publicPath);

        return is_file($absolutePath);
    }
}

if (!function_exists('syzygy_prefer_public_variant')) {
    function syzygy_prefer_public_variant(string $basePublicPath, array $preferredWidths = []): string
    {
        $basePublicPath = trim($basePublicPath);

        if ($basePublicPath === '') {
            return '';
        }

        $info = pathinfo($basePublicPath);
        $dirname = (string) ($info['dirname'] ?? '');
        $filename = (string) ($info['filename'] ?? '');
        $extension = (string) ($info['extension'] ?? '');

        if ($dirname === '' || $filename === '' || $extension === '') {
            return syzygy_encode_public_path($basePublicPath);
        }

        foreach ($preferredWidths as $width) {
            $width = (int) $width;

            if ($width <= 0) {
                continue;
            }

            $candidate = $dirname . '/' . $filename . '-' . $width . '.' . $extension;

            if (syzygy_public_path_exists($candidate)) {
                return syzygy_encode_public_path($candidate);
            }
        }

        return syzygy_encode_public_path($basePublicPath);
    }
}

if (!function_exists('syzygy_image_public_path')) {
    function syzygy_image_public_path(string $originalPath, ?int $preferredWidth = null): string
    {
        $originalPath = trim($originalPath);

        if ($originalPath === '') {
            return '';
        }

        if (strpos($originalPath, '/assets/img/') !== 0) {
            return syzygy_encode_public_path($originalPath);
        }

        $subPath = substr($originalPath, strlen('/assets/img/'));
        $info = pathinfo($subPath);
        $dirname = (string) ($info['dirname'] ?? '');
        $filename = (string) ($info['filename'] ?? '');

        if ($filename === '') {
            return syzygy_encode_public_path($originalPath);
        }

        $optimizedDir = '/assets/img-optimized';

        if ($dirname !== '' && $dirname !== '.') {
            $optimizedDir .= '/' . trim($dirname, '/');
        }

        $candidates = [];

        if ($preferredWidth !== null && $preferredWidth > 0) {
            $candidates[] = $optimizedDir . '/' . $filename . '-' . $preferredWidth . '.webp';
        }

        $candidates[] = $optimizedDir . '/' . $filename . '.webp';

        foreach ($candidates as $candidate) {
            if (syzygy_public_path_exists($candidate)) {
                return syzygy_encode_public_path($candidate);
            }
        }

        return syzygy_encode_public_path($originalPath);
    }
}