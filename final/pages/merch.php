<?php declare(strict_types=1); ?>
<section class="page-header">
    <div class="container">
        <p class="section__eyebrow">Archive Store</p>
        <h1 class="section__title">Physical Editions &amp; Apparel</h1>
        <p class="section__lede"><?= syzygy_esc($merch['text'] ?? 'Pre order soon.'); ?></p>
    </div>
</section>

<section class="section merch-section" id="merch">
    <div class="container">
        <?php
        $merchReleases = array_values($merch['releases'] ?? []);
        $merchItems = $merch['items'] ?? [];
        $itemsByRelease = [];
        foreach ($merchItems as $item) {
            $rk = $item['release_key'] ?? '';
            if ($rk !== '') {
                $itemsByRelease[$rk][] = $item;
            }
        }
        ?>

        <?php if (!empty($merchReleases)): ?>
            <div class="track-tabs merch-tabs" data-track-tabs>
                <div class="track-tabs__nav merch-tabs__nav" role="tablist" aria-label="Merch releases">
                    <?php foreach ($merchReleases as $index => $release): ?>
                        <?php $key = $release['key'] ?? ''; ?>
                        <button
                            type="button"
                            id="merch-tab-<?= syzygy_esc($key); ?>"
                            class="track-tabs__button merch-tabs__button<?= $index === 0 ? ' is-active' : ''; ?>"
                            role="tab"
                            aria-selected="<?= $index === 0 ? 'true' : 'false'; ?>"
                            aria-controls="merch-panel-<?= syzygy_esc($key); ?>"
                        >
                            <?= syzygy_esc($release['name'] ?? 'Release'); ?>
                        </button>
                    <?php endforeach; ?>
                </div>

                <div class="track-tabs__panels">
                    <?php foreach ($merchReleases as $index => $release): ?>
                        <?php
                        $key = $release['key'] ?? '';
                        $releaseItems = $itemsByRelease[$key] ?? [];
                        ?>
                        <div
                            id="merch-panel-<?= syzygy_esc($key); ?>"
                            class="track-tabs__panel merch-tabs__panel<?= $index === 0 ? ' is-active' : ''; ?>"
                            role="tabpanel"
                            aria-labelledby="merch-tab-<?= syzygy_esc($key); ?>"
                            <?= $index === 0 ? '' : 'hidden'; ?>
                        >
                            <div class="merch-panel__heading">
                                <p class="section__eyebrow">Release Collection</p>
                                <h2><?= syzygy_esc($release['name'] ?? 'SYZYGY.VOID'); ?></h2>
                            </div>
                            <div class="merch-grid">
                                <?php foreach ($releaseItems as $item): ?>
                                    <article class="merch-card">
                                        <div class="merch-card__media">
                                            <?php $merchImg = syzygy_responsive_image((string) ($item['image'] ?? ''), [400, 800, 1200]); ?>
                                            <img class="merch-card__image" src="<?= syzygy_esc($merchImg['src']); ?>" srcset="<?= syzygy_esc($merchImg['srcset']); ?>" sizes="(max-width: 700px) 50vw, 280px" alt="<?= syzygy_esc($item['alt'] ?? ''); ?>" width="400" height="400" loading="lazy" decoding="async">
                                        </div>
                                        <div class="merch-card__body">
                                            <p class="merch-card__eyebrow"><?= syzygy_esc($item['release_name'] ?? 'SYZYGY.VOID'); ?></p>
                                            <h3 class="merch-card__title"><?= syzygy_esc($item['format_title'] ?? ''); ?></h3>
                                            <p class="merch-card__description"><?= syzygy_esc($item['description'] ?? ''); ?></p>
                                            <span class="btn btn--secondary is-disabled"><?= syzygy_esc($item['link_label'] ?? 'Pre Order soon!'); ?></span>
                                        </div>
                                    </article>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>
