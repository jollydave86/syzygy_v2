<?php
declare(strict_types=1);

/**
 * Platform link row.
 * Pass $platformLinksMap (assoc) or defaults to site artist_profiles.
 */
$platformLinksMap = $platformLinksMap ?? [
    'suno' => $site['artist_profiles']['suno'] ?? '',
    'spotify' => $site['artist_profiles']['spotify'] ?? '',
    'apple' => $site['artist_profiles']['apple'] ?? ($site['artist_profiles']['apple_music'] ?? ''),
    'amazon' => $site['artist_profiles']['amazon'] ?? '',
    'youtube' => $site['artist_profiles']['youtube'] ?? '',
];

$platformItems = syzygy_platform_links($platformLinksMap);
$platformClass = $platformClass ?? 'platform-links';
?>
<div class="<?= syzygy_esc($platformClass); ?>">
    <?php foreach ($platformItems as $item): ?>
        <?php if ($item['ready']): ?>
            <a class="platform-links__link" href="<?= syzygy_esc($item['href']); ?>" target="_blank" rel="noopener noreferrer">
                <?= syzygy_esc($item['label']); ?>
            </a>
        <?php else: ?>
            <span class="platform-links__link platform-links__link--soon" title="Paste URL in data files">
                <?= syzygy_esc($item['label']); ?> · Soon
            </span>
        <?php endif; ?>
    <?php endforeach; ?>
</div>
