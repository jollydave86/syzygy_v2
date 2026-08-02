<?php
$gallery = $gallery ?? require __DIR__ . '/../data/gallery.php';

$galleryFilters = $gallery['filters'] ?? [];
$galleryItems = $gallery['items'] ?? [];
$galleryInitialCount = (int) ($gallery['initial_count'] ?? 12);
$galleryRandomCount = 10;

$normalizeGalleryKey = static function ($value): string {
    return strtolower(trim((string) $value));
};

$makeDomSlug = static function ($value): string {
    $value = strtolower(trim((string) $value));
    $value = preg_replace('/[^a-z0-9\-]+/', '-', $value);
    $value = trim($value, '-');

    return $value !== '' ? $value : 'gallery';
};

$galleryFiltersShuffled = array_values(array_filter(
    $galleryFilters,
    static function (array $filter): bool {
        return !empty($filter['id']) && !empty($filter['label']);
    }
));

if (count($galleryFiltersShuffled) > 1) {
    shuffle($galleryFiltersShuffled);
}
?>

<?php if (!empty($galleryItems) && !empty($galleryFiltersShuffled)): ?>
<section id="gallery" class="section gallery-section">
    <div class="container">
        <div class="section-heading">
            <p class="section__eyebrow">VISUAL ARCHIVE</p>
            <h2 class="section__title">Gallery</h2>
            <p class="section__lede">Artwork across the SYZYGY.VOID catalog, organized by release.</p>
        </div>

        <div
            class="gallery-tabs"
            data-gallery-tabs
            data-initial-count="<?= htmlspecialchars((string) $galleryInitialCount, ENT_QUOTES, 'UTF-8'); ?>"
            data-random-count="<?= htmlspecialchars((string) $galleryRandomCount, ENT_QUOTES, 'UTF-8'); ?>"
        >
            <div class="gallery-tabs__nav" role="tablist" aria-label="Gallery playlists">
                <?php foreach ($galleryFiltersShuffled as $index => $filter): ?>
                    <?php
                    $rawFilterId = (string) ($filter['id'] ?? '');
                    $filterKey = $normalizeGalleryKey($rawFilterId);
                    $filterLabel = (string) ($filter['label'] ?? '');
                    $domSlug = $makeDomSlug($filterKey);
                    $tabId = 'gallery-tab-' . $domSlug;
                    $panelId = 'gallery-panel-' . $domSlug;
                    $isActive = $index === 0;
                    ?>
                    <button
                        id="<?= htmlspecialchars($tabId, ENT_QUOTES, 'UTF-8'); ?>"
                        type="button"
                        role="tab"
                        class="gallery-tabs__button<?= $isActive ? ' is-active' : ''; ?>"
                        aria-selected="<?= $isActive ? 'true' : 'false'; ?>"
                        aria-controls="<?= htmlspecialchars($panelId, ENT_QUOTES, 'UTF-8'); ?>"
                        data-gallery-tab="<?= htmlspecialchars($filterKey, ENT_QUOTES, 'UTF-8'); ?>"
                    >
                        <?= htmlspecialchars($filterLabel, ENT_QUOTES, 'UTF-8'); ?>
                    </button>
                <?php endforeach; ?>
            </div>

            <?php foreach ($galleryFiltersShuffled as $index => $filter): ?>
                <?php
                $rawFilterId = (string) ($filter['id'] ?? '');
                $filterKey = $normalizeGalleryKey($rawFilterId);
                $domSlug = $makeDomSlug($filterKey);
                $tabId = 'gallery-tab-' . $domSlug;
                $panelId = 'gallery-panel-' . $domSlug;
                $isActive = $index === 0;

                $panelItems = array_values(array_filter(
                    $galleryItems,
                    static function (array $item) use ($filterKey, $normalizeGalleryKey): bool {
                        return $normalizeGalleryKey($item['filter_id'] ?? '') === $filterKey;
                    }
                ));

                $needsToggle = count($panelItems) > $galleryInitialCount;
                ?>

                <div
                    id="<?= htmlspecialchars($panelId, ENT_QUOTES, 'UTF-8'); ?>"
                    class="gallery-tabs__panel"
                    role="tabpanel"
                    aria-labelledby="<?= htmlspecialchars($tabId, ENT_QUOTES, 'UTF-8'); ?>"
                    data-gallery-panel="<?= htmlspecialchars($filterKey, ENT_QUOTES, 'UTF-8'); ?>"
                    <?= $isActive ? '' : 'hidden'; ?>
                >
                    <?php if (!empty($panelItems)): ?>
                        <div class="gallery-panel__controls">
                            <?php if ($needsToggle): ?>
                                <button
                                    type="button"
                                    class="gallery-panel__toggle"
                                    data-gallery-toggle
                                    data-expanded="false"
                                    aria-expanded="false"
                                >
                                    Show More
                                </button>
                            <?php endif; ?>
                        </div>

                        <div class="gallery-grid">
                            <?php foreach ($panelItems as $itemIndex => $item): ?>
                                <?php
                                $title = (string) ($item['display_title'] ?? $item['title'] ?? 'Artwork');
                                $thumbSrc = (string) ($item['thumb_src'] ?? $item['src'] ?? '');
                                $fullSrc = (string) ($item['full_src'] ?? $thumbSrc);
                                $alt = (string) ($item['alt'] ?? $title);
                                $playlist = (string) ($item['playlist'] ?? '');
                                $randomEligible = !array_key_exists('random_eligible', $item) || !empty($item['random_eligible']);
                                $hiddenClass = $itemIndex >= $galleryInitialCount ? ' is-hidden' : '';

                                if ($thumbSrc === '') {
                                    continue;
                                }
                                ?>
                                <article
                                    class="gallery-card<?= $hiddenClass; ?>"
                                    data-gallery-item
                                    data-random-eligible="<?= $randomEligible ? 'true' : 'false'; ?>"
                                >
                                    <button
                                        type="button"
                                        class="gallery-card__button"
                                        data-lightbox-image="<?= htmlspecialchars($fullSrc, ENT_QUOTES, 'UTF-8'); ?>"
                                        data-lightbox-alt="<?= htmlspecialchars($alt, ENT_QUOTES, 'UTF-8'); ?>"
                                        data-lightbox-title="<?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?>"
                                        data-lightbox-caption="<?= htmlspecialchars($playlist, ENT_QUOTES, 'UTF-8'); ?>"
                                    >
                                        <img
                                            class="gallery-card__image"
                                            src="<?= htmlspecialchars($thumbSrc, ENT_QUOTES, 'UTF-8'); ?>"
                                            alt="<?= htmlspecialchars($alt, ENT_QUOTES, 'UTF-8'); ?>"
                                            loading="lazy"
                                            decoding="async"
                                        >

                                        <span class="gallery-card__overlay"></span>
                                        <span class="gallery-card__caption">
                                            <?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?>
                                        </span>
                                    </button>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="gallery-empty">No artwork found in this playlist yet.</p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php else: ?>
<section id="gallery" class="section gallery-section">
    <div class="container">
        <div class="section-heading">
            <p class="section__eyebrow">VISUAL ARCHIVE</p>
            <h2 class="section__title">Gallery</h2>
            <p class="section__lede">Artwork across the SYZYGY.VOID catalog, organized by release.</p>
        </div>

        <p class="gallery-empty">No gallery artwork found yet.</p>
    </div>
</section>
<?php endif; ?>