<?php
declare(strict_types=1);

$byRelease = [];
foreach ($lyrics as $entry) {
    $key = $entry['release_slug'] ?? 'other';
    $byRelease[$key]['title'] = $entry['release_title'] ?? $key;
    $byRelease[$key]['items'][] = $entry;
}

$releaseArt = [];
foreach ($releases as $release) {
    $releaseArt[$release['slug']] = $release['cover'];
}
$releaseArt['featured-singles'] = '/assets/img-optimized/gallery/Featured_Singles_Night/00 - Playlist Cover.webp';
?>
<section class="page-header">
    <div class="container">
        <p class="section__eyebrow">Transmissions</p>
        <h1 class="section__title">Lyrics</h1>
        <p class="section__lede">Browse the lyric archive by release. Each transmission opens to its dedicated song page.</p>
    </div>
</section>

<section class="section lyrics-archive">
    <div class="container lyrics-layout">
        <aside class="lyrics-sidebar">
            <div class="lyrics-sidebar__sticky">
                <p class="section__eyebrow">Release Index</p>
                <h2 class="section__title section__title--sm">Choose a World</h2>
                <div class="lyrics-release-tiles">
                    <?php foreach ($byRelease as $key => $group): ?>
                        <a class="lyrics-release-tile" href="#lyrics-<?= syzygy_esc($key); ?>">
                            <?php if (!empty($releaseArt[$key])): ?>
                                <img src="<?= syzygy_esc(syzygy_encode_public_path($releaseArt[$key])); ?>" alt="" loading="lazy">
                            <?php endif; ?>
                            <span>
                                <strong><?= syzygy_esc($group['title']); ?></strong>
                                <small><?= count($group['items']); ?> songs</small>
                            </span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </aside>

        <div class="lyrics-accordions">
            <?php $groupNumber = 0; ?>
            <?php foreach ($byRelease as $groupKey => $group): ?>
                <?php $groupNumber++; ?>
                <details class="lyrics-accordion" id="lyrics-<?= syzygy_esc((string) $groupKey); ?>" <?= $groupNumber === 1 ? 'open' : ''; ?>>
                    <summary class="lyrics-accordion__summary">
                        <span>
                            <small>Release <?= str_pad((string) $groupNumber, 2, '0', STR_PAD_LEFT); ?></small>
                            <strong><?= syzygy_esc($group['title']); ?></strong>
                        </span>
                        <span class="lyrics-accordion__count"><?= count($group['items']); ?> tracks</span>
                    </summary>
                    <div class="lyrics-accordion__body">
                        <ul class="lyrics-index">
                            <?php foreach ($group['items'] as $entry): ?>
                                <li>
                                    <a href="<?= syzygy_esc(syzygy_url('/lyrics/' . $entry['slug'])); ?>">
                                        <span><?= syzygy_esc($entry['title']); ?></span>
                                        <span class="lyrics-index__status"><?= ($entry['status'] ?? '') === 'ready' ? 'Lyrics' : 'Pending'; ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </details>
            <?php endforeach; ?>
        </div>
    </div>
</section>
