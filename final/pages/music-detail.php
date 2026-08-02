<?php
declare(strict_types=1);
$tracks = $playlist['tracks'] ?? [];
$platformLinksMap = $release['links'] ?? [];
?>
<section class="page-header">
    <div class="container">
        <p class="section__eyebrow"><a href="<?= syzygy_esc(syzygy_url('/music')); ?>">Music</a> / <?= syzygy_esc($release['eyebrow'] ?? 'Release'); ?></p>
        <h1 class="section__title"><?= syzygy_esc($release['title']); ?></h1>
    </div>
</section>

<section class="section detail-section">
    <div class="container detail-layout">
        <aside class="detail-sidebar">
            <div class="detail-sidebar__sticky">
                <div class="detail-cover">
                    <img src="<?= syzygy_esc(syzygy_encode_public_path($release['cover'])); ?>" alt="<?= syzygy_esc($release['title']); ?>">
                </div>
                <p class="detail-meta__label">Platforms</p>
                <?php require dirname(__DIR__) . '/includes/platform-links.php'; ?>
                <p class="detail-meta__text"><?= syzygy_esc($release['summary'] ?? ''); ?></p>
                <?php if (!empty($release['member_slug'])): ?>
                    <p>
                        <a class="btn btn--secondary" href="<?= syzygy_esc(syzygy_url('/profiles/' . $release['member_slug'])); ?>">Artist profile</a>
                    </p>
                <?php endif; ?>
            </div>
        </aside>

        <div class="detail-main">
            <div class="section-heading">
                <p class="section__eyebrow">Tracklist</p>
                <h2 class="section__title section__title--sm"><?= count($tracks); ?> Transmissions</h2>
            </div>
            <ol class="track-list">
                <?php foreach ($tracks as $i => $track): ?>
                    <?php
                    $title = $track['title'] ?? 'Untitled';
                    $lyricSlug = syzygy_slugify($title);
                    $suno = $track['href'] ?? '';
                    ?>
                    <li class="track-list__item">
                        <span class="track-list__num"><?= (int) $i + 1; ?></span>
                        <div class="track-list__body">
                            <p class="track-list__title"><?= syzygy_esc($title); ?></p>
                            <div class="track-list__actions">
                                <?php if ($suno): ?>
                                    <a href="<?= syzygy_esc($suno); ?>" target="_blank" rel="noopener noreferrer">Suno</a>
                                <?php endif; ?>
                                <a href="<?= syzygy_esc(syzygy_url('/lyrics/' . $lyricSlug)); ?>">Lyrics</a>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ol>
        </div>
    </div>
</section>
