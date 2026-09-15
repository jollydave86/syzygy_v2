<?php
declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    header('Content-Type: text/plain; charset=UTF-8');
    echo "CLI only.\n";
    exit(1);
}

/**
 * Dynamic XML sitemap for production SEO.
 *
 * Usage:
 *   php tools/generate-sitemap.php
 */

require_once dirname(__DIR__) . '/includes/bootstrap.php';

$urls = [
    ['loc' => '/', 'priority' => '1.0', 'changefreq' => 'weekly'],
    ['loc' => '/about', 'priority' => '0.8', 'changefreq' => 'monthly'],
    ['loc' => '/music', 'priority' => '0.9', 'changefreq' => 'weekly'],
    ['loc' => '/profiles', 'priority' => '0.9', 'changefreq' => 'weekly'],
    ['loc' => '/lyrics', 'priority' => '0.8', 'changefreq' => 'weekly'],
    ['loc' => '/gallery', 'priority' => '0.7', 'changefreq' => 'weekly'],
    ['loc' => '/merch', 'priority' => '0.7', 'changefreq' => 'weekly'],
    ['loc' => '/blog', 'priority' => '0.7', 'changefreq' => 'weekly'],
    ['loc' => '/contact', 'priority' => '0.6', 'changefreq' => 'yearly'],
    ['loc' => '/booking', 'priority' => '0.3', 'changefreq' => 'yearly'],
];

foreach ($releases as $release) {
    $slug = (string) ($release['slug'] ?? '');
    if ($slug === '') {
        continue;
    }
    $urls[] = ['loc' => '/music/' . $slug, 'priority' => '0.8', 'changefreq' => 'monthly'];
}

foreach ($members as $member) {
    $slug = (string) ($member['slug'] ?? '');
    if ($slug === '') {
        continue;
    }
    $urls[] = ['loc' => '/profiles/' . $slug, 'priority' => '0.7', 'changefreq' => 'monthly'];
}

foreach ($blogPosts as $post) {
    $slug = (string) ($post['slug'] ?? '');
    if ($slug === '') {
        continue;
    }
    $urls[] = [
        'loc' => '/blog/' . $slug,
        'priority' => '0.6',
        'changefreq' => 'monthly',
        'lastmod' => $post['date'] ?? null,
    ];
}

// Ready lyrics only (cap for sitemap size sanity).
$lyricCount = 0;
foreach ($lyrics as $lyric) {
    if (($lyric['status'] ?? '') !== 'ready') {
        continue;
    }
    $slug = (string) ($lyric['slug'] ?? '');
    if ($slug === '') {
        continue;
    }
    $urls[] = ['loc' => '/lyrics/' . $slug, 'priority' => '0.5', 'changefreq' => 'yearly'];
    $lyricCount++;
    if ($lyricCount >= 250) {
        break;
    }
}

$xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
$xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
foreach ($urls as $row) {
    $loc = syzygy_abs_url((string) $row['loc']);
    $xml .= "  <url>\n";
    $xml .= '    <loc>' . htmlspecialchars($loc, ENT_XML1) . "</loc>\n";
    if (!empty($row['lastmod'])) {
        $xml .= '    <lastmod>' . htmlspecialchars((string) $row['lastmod'], ENT_XML1) . "</lastmod>\n";
    }
    if (!empty($row['changefreq'])) {
        $xml .= '    <changefreq>' . htmlspecialchars((string) $row['changefreq'], ENT_XML1) . "</changefreq>\n";
    }
    if (!empty($row['priority'])) {
        $xml .= '    <priority>' . htmlspecialchars((string) $row['priority'], ENT_XML1) . "</priority>\n";
    }
    $xml .= "  </url>\n";
}
$xml .= "</urlset>\n";

$outPath = dirname(__DIR__) . '/sitemap.xml';
file_put_contents($outPath, $xml);
echo 'Wrote ' . $outPath . ' with ' . count($urls) . " URLs\n";
