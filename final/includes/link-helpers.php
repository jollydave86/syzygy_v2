<?php
declare(strict_types=1);

/**
 * SYZYGY.VOID Link Helpers
 * -------------------------------------------------------------
 * Purpose:
 * Centralize all link-related behavior for the site so section
 * templates do not each reinvent link logic.
 *
 * This helper is meant to keep the PHP templates clean and safe.
 *
 * Responsibilities:
 * - Escape link values safely for HTML output
 * - Detect internal vs external URLs
 * - Detect special schemes (mailto:, tel:, etc.)
 * - Build tracked URLs with UTM parameters when needed
 * - Build consistent HTML attributes for links
 * - Standardize target / rel behavior
 *
 * Why this exists:
 * The site now has multiple sections with different link types:
 * - Music / track links
 * - Video links
 * - Merch links
 * - Future contact/social links
 * - Future promo module CTA links
 * - Future lyrics/release navigation links
 *
 * Instead of spreading that logic across section files, this file
 * provides one reusable and safer source of truth.
 *
 * Important project note:
 * The site has already had issues in the past from overwriting
 * working files or mismatching data shapes between sections.
 * This helper is meant to reduce that kind of risk by keeping
 * link logic centralized and predictable.
 */

if (!function_exists('syzygy_link_esc')) {
    /**
     * Escape any value for safe HTML attribute output.
     *
     * Example:
     * href="<?= syzygy_link_esc($url); ?>"
     */
    function syzygy_link_esc($value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('syzygy_current_site_url')) {
    /**
     * Best-effort current site URL resolver.
     *
     * Tries to build the current site base URL from server variables.
     * Falls back to a provided URL if available.
     *
     * This matters when determining whether a full URL is internal
     * or external relative to the current site.
     */
    function syzygy_current_site_url(?string $fallback = null): ?string
    {
        if (!empty($_SERVER['HTTP_HOST'])) {
            $https = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
            $scheme = $https ? 'https' : 'http';

            return $scheme . '://' . $_SERVER['HTTP_HOST'];
        }

        if (is_string($fallback) && preg_match('~^https?://~i', $fallback)) {
            return $fallback;
        }

        return null;
    }
}

if (!function_exists('syzygy_normalize_host')) {
    /**
     * Normalize a host name for comparison.
     *
     * Example:
     * www.example.com -> example.com
     */
    function syzygy_normalize_host(?string $host): string
    {
        $host = strtolower(trim((string) $host));
        $host = preg_replace('~^www\.~', '', $host);

        return $host ?? '';
    }
}

if (!function_exists('syzygy_is_http_url')) {
    /**
     * True only for http:// or https:// URLs.
     *
     * Used to distinguish full web URLs from relative paths or
     * special schemes like mailto: or tel:.
     */
    function syzygy_is_http_url(string $url): bool
    {
        return (bool) preg_match('~^https?://~i', trim($url));
    }
}

if (!function_exists('syzygy_is_special_scheme')) {
    /**
     * Detect special schemes that should not be treated like
     * normal website URLs.
     *
     * Examples:
     * - mailto:
     * - tel:
     * - sms:
     * - javascript:
     * - data:
     */
    function syzygy_is_special_scheme(string $url): bool
    {
        return (bool) preg_match('~^(mailto:|tel:|sms:|javascript:|data:)~i', trim($url));
    }
}

if (!function_exists('syzygy_is_internalish_url')) {
    /**
     * Detect URLs that behave like internal links even if they are
     * not full absolute URLs.
     *
     * Examples:
     * - #contact
     * - /lyrics
     * - ./page.php
     * - ../something
     * - ?filter=featured
     * - plain relative file/path strings
     *
     * This prevents relative site paths from being misclassified
     * as external links.
     */
    function syzygy_is_internalish_url(string $url): bool
    {
        $url = trim($url);

        if ($url === '') {
            return true;
        }

        if (
            str_starts_with($url, '#') ||
            str_starts_with($url, '/') ||
            str_starts_with($url, './') ||
            str_starts_with($url, '../') ||
            str_starts_with($url, '?')
        ) {
            return true;
        }

        if (!syzygy_is_http_url($url) && !syzygy_is_special_scheme($url)) {
            return true;
        }

        return false;
    }
}

if (!function_exists('syzygy_is_external_url')) {
    /**
     * Determine whether a URL is truly external relative to the site.
     *
     * Rules:
     * - Empty values are not external
     * - mailto:, tel:, etc. are not treated as external web URLs
     * - relative/internalish paths are not external
     * - full absolute http(s) URLs are compared against the site host
     */
    function syzygy_is_external_url(string $url, ?string $siteUrl = null): bool
    {
        $url = trim($url);

        if ($url === '' || syzygy_is_special_scheme($url) || syzygy_is_internalish_url($url)) {
            return false;
        }

        if (!syzygy_is_http_url($url)) {
            return false;
        }

        $siteUrl = syzygy_current_site_url($siteUrl);

        $linkHost = syzygy_normalize_host((string) parse_url($url, PHP_URL_HOST));
        $siteHost = syzygy_normalize_host((string) parse_url((string) $siteUrl, PHP_URL_HOST));

        if ($linkHost === '') {
            return false;
        }

        if ($siteHost === '') {
            return true;
        }

        return $linkHost !== $siteHost;
    }
}

if (!function_exists('syzygy_build_url_from_parts')) {
    /**
     * Rebuild a URL from parse_url() parts.
     *
     * This is used after query params are merged back into an
     * existing URL.
     */
    function syzygy_build_url_from_parts(array $parts): string
    {
        $scheme = isset($parts['scheme']) ? $parts['scheme'] . '://' : '';
        $user = $parts['user'] ?? '';
        $pass = $parts['pass'] ?? '';
        $auth = $user !== '' ? $user . ($pass !== '' ? ':' . $pass : '') . '@' : '';
        $host = $parts['host'] ?? '';
        $port = isset($parts['port']) ? ':' . $parts['port'] : '';
        $path = $parts['path'] ?? '';
        $query = isset($parts['query']) && $parts['query'] !== '' ? '?' . ltrim((string) $parts['query'], '?') : '';
        $fragment = isset($parts['fragment']) && $parts['fragment'] !== '' ? '#' . ltrim((string) $parts['fragment'], '#') : '';

        return $scheme . $auth . $host . $port . $path . $query . $fragment;
    }
}

if (!function_exists('syzygy_append_query_params')) {
    /**
     * Merge query parameters into an existing URL safely.
     *
     * Existing query params are preserved unless overwritten.
     * Empty values are ignored.
     */
    function syzygy_append_query_params(string $url, array $params): string
    {
        $url = trim($url);

        if ($url === '' || $params === []) {
            return $url;
        }

        $parts = parse_url($url);

        if ($parts === false) {
            return $url;
        }

        $existing = [];

        if (!empty($parts['query'])) {
            parse_str((string) $parts['query'], $existing);
        }

        foreach ($params as $key => $value) {
            if ($key === '' || $value === null || $value === '') {
                continue;
            }

            $existing[(string) $key] = (string) $value;
        }

        $parts['query'] = http_build_query($existing, '', '&', PHP_QUERY_RFC3986);

        return syzygy_build_url_from_parts($parts);
    }
}

if (!function_exists('syzygy_default_utm_params')) {
    /**
     * Default UTM values for SYZYGY.VOID.
     *
     * Can be overridden per section or per link as needed.
     */
    function syzygy_default_utm_params(array $overrides = []): array
    {
        $defaults = [
            'utm_source' => 'syzygyvoid',
            'utm_medium' => 'website',
            'utm_campaign' => 'official-site',
        ];

        return array_merge($defaults, $overrides);
    }
}

if (!function_exists('syzygy_build_tracked_url')) {
    /**
     * Build a tracked URL by appending UTM parameters.
     *
     * Options:
     * - site_url
     * - source
     * - medium
     * - campaign
     * - content
     * - term
     * - extra
     * - track_external_only
     *
     * Important:
     * By default, this only tracks external URLs to avoid polluting
     * internal navigation unnecessarily.
     */
    function syzygy_build_tracked_url(string $url, array $options = []): string
    {
        $options = array_merge([
            'site_url' => null,
            'source' => 'syzygyvoid',
            'medium' => 'website',
            'campaign' => 'official-site',
            'content' => null,
            'term' => null,
            'extra' => [],
            'track_external_only' => true,
        ], $options);

        $url = trim($url);

        if ($url === '' || !syzygy_is_http_url($url)) {
            return $url;
        }

        $isExternal = syzygy_is_external_url($url, is_string($options['site_url']) ? $options['site_url'] : null);

        if ($options['track_external_only'] && !$isExternal) {
            return $url;
        }

        $utm = syzygy_default_utm_params([
            'utm_source' => (string) $options['source'],
            'utm_medium' => (string) $options['medium'],
            'utm_campaign' => (string) $options['campaign'],
            'utm_content' => $options['content'],
            'utm_term' => $options['term'],
        ]);

        $extra = is_array($options['extra']) ? $options['extra'] : [];

        return syzygy_append_query_params($url, array_merge($utm, $extra));
    }
}

if (!function_exists('syzygy_html_attrs')) {
    /**
     * Convert an associative array of HTML attributes into a safe
     * attribute string for output inside a tag.
     *
     * Examples:
     * ['href' => '/test', 'target' => '_blank']
     * becomes:
     * href="/test" target="_blank"
     */
    function syzygy_html_attrs(array $attributes): string
    {
        $compiled = [];

        foreach ($attributes as $name => $value) {
            if ($value === null || $value === false || $name === '') {
                continue;
            }

            if ($value === true) {
                $compiled[] = syzygy_link_esc((string) $name);
                continue;
            }

            $compiled[] = sprintf(
                '%s="%s"',
                syzygy_link_esc((string) $name),
                syzygy_link_esc((string) $value)
            );
        }

        return implode(' ', $compiled);
    }
}

if (!function_exists('syzygy_prepare_link')) {
    /**
     * Prepare a link once and return all useful metadata.
     *
     * Returns:
     * - href
     * - href_escaped
     * - is_external
     * - new_tab
     * - rel
     * - attrs
     * - attributes
     *
     * This is the main function templates will usually call.
     */
    function syzygy_prepare_link(string $url, array $options = []): array
    {
        $options = array_merge([
            'site_url' => null,
            'track' => false,
            'track_external_only' => true,
            'source' => 'syzygyvoid',
            'medium' => 'website',
            'campaign' => 'official-site',
            'content' => null,
            'term' => null,
            'extra' => [],
            'new_tab' => null,
            'noopener' => true,
            'noreferrer' => true,
            'nofollow' => false,
            'aria_label' => null,
            'class' => null,
        ], $options);

        $siteUrl = is_string($options['site_url']) ? $options['site_url'] : null;
        $isExternal = syzygy_is_external_url($url, $siteUrl);

        $href = $options['track']
            ? syzygy_build_tracked_url($url, [
                'site_url' => $siteUrl,
                'source' => $options['source'],
                'medium' => $options['medium'],
                'campaign' => $options['campaign'],
                'content' => $options['content'],
                'term' => $options['term'],
                'extra' => $options['extra'],
                'track_external_only' => (bool) $options['track_external_only'],
            ])
            : $url;

        $newTab = $options['new_tab'] === null
            ? $isExternal
            : (bool) $options['new_tab'];

        $relTokens = [];

        if ($newTab && !empty($options['noopener'])) {
            $relTokens[] = 'noopener';
        }

        if ($newTab && !empty($options['noreferrer'])) {
            $relTokens[] = 'noreferrer';
        }

        if (!empty($options['nofollow'])) {
            $relTokens[] = 'nofollow';
        }

        $rel = implode(' ', array_values(array_unique($relTokens)));

        $attributes = [
            'href' => $href,
        ];

        if ($newTab) {
            $attributes['target'] = '_blank';
        }

        if ($rel !== '') {
            $attributes['rel'] = $rel;
        }

        if (!empty($options['aria_label'])) {
            $attributes['aria-label'] = (string) $options['aria_label'];
        }

        if (!empty($options['class'])) {
            $attributes['class'] = (string) $options['class'];
        }

        return [
            'href' => $href,
            'href_escaped' => syzygy_link_esc($href),
            'is_external' => $isExternal,
            'new_tab' => $newTab,
            'rel' => $rel,
            'attrs' => syzygy_html_attrs($attributes),
            'attributes' => $attributes,
        ];
    }
}

if (!function_exists('syzygy_link_attrs')) {
    /**
     * Shortcut helper when a template only needs the compiled
     * attribute string and not the full prepared link array.
     *
     * Example:
     * <a <?= syzygy_link_attrs($url, ['track' => true]); ?>>...</a>
     */
    function syzygy_link_attrs(string $url, array $options = []): string
    {
        $link = syzygy_prepare_link($url, $options);

        return $link['attrs'];
    }
}