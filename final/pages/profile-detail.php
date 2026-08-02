<?php
declare(strict_types=1);
$isSoon = ($member['status'] ?? '') === 'coming_soon';
$platformLinksMap = $member['links'] ?? [];
?>
<section class="page-header">
    <div class="container">
        <p class="section__eyebrow"><a href="<?= syzygy_esc(syzygy_url('/profiles')); ?>">Solo Signals</a> / <?= syzygy_esc($member['badge'] ?? ''); ?></p>
        <h1 class="section__title"><?= syzygy_esc($member['name']); ?></h1>
        <p class="section__lede"><?= syzygy_esc($member['role']); ?></p>
    </div>
</section>

<section class="section detail-section">
    <div class="container detail-layout">
        <aside class="detail-sidebar">
            <div class="detail-sidebar__sticky">
                <div class="detail-cover detail-cover--portrait">
                    <?php if (!empty($member['image']) && syzygy_public_path_exists($member['image'])): ?>
                        <img src="<?= syzygy_esc(syzygy_prefer_public_variant($member['image'], [800, 1200])); ?>" alt="<?= syzygy_esc($member['name']); ?>" width="800" height="1000" loading="eager" decoding="async" fetchpriority="high">
                    <?php endif; ?>
                </div>
                <p class="detail-meta__label">Platforms</p>
                <?php require dirname(__DIR__) . '/includes/platform-links.php'; ?>
                <?php if (!empty($member['sound_tags'])): ?>
                    <p class="detail-meta__label">Sound</p>
                    <p class="tag-row"><?= syzygy_esc(implode(' · ', $member['sound_tags'])); ?></p>
                <?php endif; ?>
            </div>
        </aside>

        <div class="detail-main">
            <?php if ($isSoon): ?>
                <div class="coming-soon-banner">
                    <p class="section__eyebrow">Coming Soon</p>
                    <h2 class="section__title section__title--sm">Transmission Pending</h2>
                    <p><?= syzygy_esc($member['chapter_bio'] ?? $member['bio'] ?? ''); ?></p>
                    <p><?= syzygy_esc($member['bio'] ?? ''); ?></p>
                </div>
            <?php else: ?>
                <div class="section-heading">
                    <p class="section__eyebrow"><?= syzygy_esc($member['release'] ?? 'Solo'); ?></p>
                    <h2 class="section__title section__title--sm">Solo Signal</h2>
                </div>
                <p class="prose"><?= syzygy_esc($member['bio'] ?? ''); ?></p>
                <p class="prose"><?= syzygy_esc($member['chapter_bio'] ?? ''); ?></p>

                <?php if (!empty($member['release_slug']) || !empty($member['secondary_release_slug'])): ?>
                    <p>
                        <?php if (!empty($member['release_slug'])): ?>
                            <a class="btn btn--primary" href="<?= syzygy_esc(syzygy_url('/music/' . $member['release_slug'])); ?>">Open <?= syzygy_esc($member['release'] ?? 'release'); ?></a>
                        <?php endif; ?>
                        <?php if (!empty($member['secondary_release_slug'])): ?>
                            <a class="btn btn--secondary" href="<?= syzygy_esc(syzygy_url('/music/' . $member['secondary_release_slug'])); ?>">Open <?= syzygy_esc($member['secondary_release'] ?? 'release'); ?></a>
                        <?php endif; ?>
                    </p>
                <?php endif; ?>

                <?php if (!empty($member['tracks'])): ?>
                    <ol class="track-list">
                        <?php foreach ($member['tracks'] as $i => $trackTitle): ?>
                            <li class="track-list__item">
                                <span class="track-list__num"><?= (int) $i + 1; ?></span>
                                <div class="track-list__body">
                                    <p class="track-list__title"><?= syzygy_esc($trackTitle); ?></p>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ol>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</section>
