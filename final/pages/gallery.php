<?php
declare(strict_types=1);

$galleryFilters = array_values($gallery['filters'] ?? []);
$galleryItems = $gallery['items'] ?? [];
$galleryInitialCount = (int) ($gallery['initial_count'] ?? 12);
?>
<section class="page-header">
    <div class="container">
        <p class="section__eyebrow">Visual Archive</p>
        <h1 class="section__title">Gallery</h1>
        <p class="section__lede">Artwork organized by release. Preserve release-to-art relationships.</p>
    </div>
</section>

<section class="section gallery-section" id="gallery">
    <div class="container">
        <?php if (!empty($galleryFilters) && !empty($galleryItems)): ?>
            <div class="gallery-tabs" data-gallery-tabs data-initial-count="<?= $galleryInitialCount; ?>" data-random-count="10">
                <div class="gallery-tabs__nav" role="tablist" aria-label="Gallery releases">
                    <?php foreach ($galleryFilters as $index => $filter): ?>
                        <?php
                        $key = (string) ($filter['id'] ?? '');
                        $tabId = 'gallery-tab-' . syzygy_slugify($key);
                        $panelId = 'gallery-panel-' . syzygy_slugify($key);
                        ?>
                        <button
                            id="<?= syzygy_esc($tabId); ?>"
                            type="button"
                            role="tab"
                            class="gallery-tabs__button<?= $index === 0 ? ' is-active' : ''; ?>"
                            aria-selected="<?= $index === 0 ? 'true' : 'false'; ?>"
                            aria-controls="<?= syzygy_esc($panelId); ?>"
                            data-gallery-tab="<?= syzygy_esc(strtolower($key)); ?>"
                        >
                            <?= syzygy_esc($filter['label'] ?? 'Release'); ?>
                        </button>
                    <?php endforeach; ?>
                </div>

                <?php foreach ($galleryFilters as $index => $filter): ?>
                    <?php
                    $key = strtolower((string) ($filter['id'] ?? ''));
                    $tabId = 'gallery-tab-' . syzygy_slugify($key);
                    $panelId = 'gallery-panel-' . syzygy_slugify($key);
                    $panelItems = array_values(array_filter(
                        $galleryItems,
                        static fn (array $item): bool => strtolower((string) ($item['filter_id'] ?? '')) === $key
                    ));
                    ?>
                    <div
                        id="<?= syzygy_esc($panelId); ?>"
                        class="gallery-tabs__panel"
                        role="tabpanel"
                        aria-labelledby="<?= syzygy_esc($tabId); ?>"
                        data-gallery-panel="<?= syzygy_esc($key); ?>"
                        <?= $index === 0 ? '' : 'hidden'; ?>
                    >
                        <?php if (count($panelItems) > $galleryInitialCount): ?>
                            <div class="gallery-panel__controls">
                                <button type="button" class="gallery-panel__toggle" data-gallery-toggle data-expanded="false" aria-expanded="false">Show More</button>
                            </div>
                        <?php endif; ?>

                        <div class="gallery-grid">
                            <?php foreach ($panelItems as $itemIndex => $item): ?>
                                <?php
                                $title = (string) ($item['display_title'] ?? $item['title'] ?? 'Artwork');
                                $thumb = (string) ($item['thumb_src'] ?? $item['src'] ?? '');
                                $full = (string) ($item['full_src'] ?? $thumb);
                                $alt = (string) ($item['alt'] ?? $title);
                                ?>
                                <article class="gallery-card<?= $itemIndex >= $galleryInitialCount ? ' is-hidden' : ''; ?>" data-gallery-item>
                                    <button
                                        type="button"
                                        class="gallery-card__button"
                                        data-lightbox-image="<?= syzygy_esc($full); ?>"
                                        data-lightbox-alt="<?= syzygy_esc($alt); ?>"
                                        data-lightbox-title="<?= syzygy_esc($title); ?>"
                                        data-lightbox-caption="<?= syzygy_esc($filter['label'] ?? ''); ?>"
                                    >
                                        <img class="gallery-card__image" src="<?= syzygy_esc($thumb); ?>" alt="<?= syzygy_esc($alt); ?>" loading="lazy" decoding="async">
                                        <span class="gallery-card__overlay"></span>
                                        <span class="gallery-card__caption"><?= syzygy_esc($title); ?></span>
                                    </button>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="gallery-empty">No gallery artwork found yet.</p>
        <?php endif; ?>
    </div>
</section>

<div class="lightbox" data-lightbox hidden>
    <div class="lightbox__backdrop" data-lightbox-close></div>
    <div class="lightbox__dialog" role="dialog" aria-modal="true" aria-label="Image preview">
        <button type="button" class="lightbox__close" data-lightbox-close aria-label="Close image">×</button>
        <button type="button" class="lightbox__nav lightbox__nav--prev" data-lightbox-prev aria-label="Previous image">‹</button>
        <button type="button" class="lightbox__nav lightbox__nav--next" data-lightbox-next aria-label="Next image">›</button>
        <div class="lightbox__media-wrap">
            <img class="lightbox__image" data-lightbox-output src="" alt="">
        </div>
        <p class="lightbox__caption" data-lightbox-caption-output></p>
    </div>
</div>
