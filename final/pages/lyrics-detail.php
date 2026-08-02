<?php
declare(strict_types=1);
$isReady = ($lyric['status'] ?? '') === 'ready' && trim((string) ($lyric['body'] ?? '')) !== '';
$releaseSlug = (string) ($lyric['release_slug'] ?? '');
$releaseTitle = (string) ($lyric['release_title'] ?? 'Album');
$albumLyricsHref = $releaseSlug !== ''
    ? syzygy_url('/lyrics') . '#lyrics-' . $releaseSlug
    : syzygy_url('/lyrics');
?>
<section class="page-header">
    <div class="container">
        <p class="section__eyebrow">
            <a href="<?= syzygy_esc(syzygy_url('/lyrics')); ?>">Lyrics</a>
            /
            <a href="<?= syzygy_esc($albumLyricsHref); ?>"><?= syzygy_esc($releaseTitle); ?></a>
        </p>
        <h1 class="section__title"><?= syzygy_esc($lyric['title']); ?></h1>
    </div>
</section>

<section class="section">
    <div class="container lyrics-detail">
        <div class="lyrics-detail__meta">
            <a class="btn btn--primary" href="<?= syzygy_esc($albumLyricsHref); ?>">← Back to <?= syzygy_esc($releaseTitle); ?></a>
            <?php if (!empty($lyric['suno'])): ?>
                <a class="btn btn--secondary" href="<?= syzygy_esc($lyric['suno']); ?>" target="_blank" rel="noopener noreferrer">Open on Suno</a>
            <?php endif; ?>
            <?php if ($releaseSlug !== '' && $releaseSlug !== 'featured-singles'): ?>
                <a class="btn btn--secondary" href="<?= syzygy_esc(syzygy_url('/music/' . $releaseSlug)); ?>">View Release</a>
            <?php endif; ?>
        </div>

        <?php if ($isReady): ?>
            <pre class="lyrics-body"><?= syzygy_esc($lyric['body']); ?></pre>
        <?php else: ?>
            <div class="coming-soon-banner">
                <p class="section__eyebrow">Transmission Pending</p>
                <h2 class="section__title section__title--sm">Lyrics Incoming</h2>
                <p>This track’s lyric sheet is not public yet. Check back soon, or open the song on Suno while the archive updates.</p>
            </div>
        <?php endif; ?>
    </div>
</section>
