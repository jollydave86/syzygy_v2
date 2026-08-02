<?php
declare(strict_types=1);

/**
 * Platform link row.
 * Pass $platformLinksMap (assoc) or defaults to site artist_profiles.
 * Empty values inherit site artist profile URLs when available.
 * Unready platforms are omitted (no “Soon” placeholders).
 */
$siteProfiles = $site['artist_profiles'] ?? [];
$platformLinksMap = $platformLinksMap ?? [
    'suno' => $siteProfiles['suno'] ?? '',
    'spotify' => $siteProfiles['spotify'] ?? '',
    'apple' => $siteProfiles['apple'] ?? ($siteProfiles['apple_music'] ?? ''),
    'amazon' => $siteProfiles['amazon'] ?? '',
    'youtube' => $siteProfiles['youtube'] ?? '',
];

foreach ($platformLinksMap as $key => $href) {
    $href = trim((string) $href);
    if ($href === '' && !empty($siteProfiles[$key])) {
        $platformLinksMap[$key] = (string) $siteProfiles[$key];
    }
}

$platformItems = syzygy_platform_links($platformLinksMap);
$platformClass = $platformClass ?? 'platform-links';
$readyItems = array_values(array_filter(
    $platformItems,
    static fn (array $item): bool => !empty($item['ready'])
));
?>
<?php if ($readyItems !== []): ?>
<div class="<?= syzygy_esc($platformClass); ?>">
    <?php foreach ($readyItems as $item): ?>
        <a class="platform-links__link" href="<?= syzygy_esc($item['href']); ?>" target="_blank" rel="noopener noreferrer">
            <?= syzygy_esc($item['label']); ?>
        </a>
    <?php endforeach; ?>
</div>
<?php endif; ?>
