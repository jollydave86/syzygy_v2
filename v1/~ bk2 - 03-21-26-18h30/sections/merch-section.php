<?php
$esc = static fn ($value) => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');

$merch = $merch ?? [];
$merchReleases = array_values($merch['releases'] ?? []);
$merchItems = $merch['items'] ?? [];

if (count($merchReleases) > 1) {
    shuffle($merchReleases);
}

$itemsByRelease = [];

foreach ($merchItems as $item) {
    $releaseKey = $item['release_key'] ?? null;

    if (!$releaseKey) {
        continue;
    }

    $itemsByRelease[$releaseKey][] = $item;
}
?>

<section class="section merch-section" id="merch" aria-labelledby="merch-title">
    <div class="container">
        <div class="section-heading">
            <?php if (!empty($merch['eyebrow'])): ?>
                <p class="section__eyebrow"><?= $esc($merch['eyebrow']); ?></p>
            <?php endif; ?>

            <?php if (!empty($merch['title'])): ?>
                <h2 class="section__title" id="merch-title"><?= $esc($merch['title']); ?></h2>
            <?php endif; ?>

            <?php if (!empty($merch['text'])): ?>
                <p class="section__lede"><?= $esc($merch['text']); ?></p>
            <?php endif; ?>
        </div>

        <?php if (!empty($merchReleases)): ?>
            <div class="track-tabs merch-tabs" data-track-tabs data-merch-tabs>
                <div class="track-tabs__nav merch-tabs__nav" role="tablist" aria-label="Merch releases">
                    <?php foreach ($merchReleases as $index => $release): ?>
                        <?php
                        $releaseKey = $release['key'] ?? '';
                        $tabId = 'merch-tab-' . $releaseKey;
                        $panelId = 'merch-panel-' . $releaseKey;
                        $isActive = $index === 0;
                        ?>
                        <button
                            type="button"
                            id="<?= $esc($tabId); ?>"
                            class="track-tabs__button merch-tabs__button<?= $isActive ? ' is-active' : ''; ?>"
                            role="tab"
                            aria-selected="<?= $isActive ? 'true' : 'false'; ?>"
                            aria-controls="<?= $esc($panelId); ?>"
                        >
                            <?= $esc($release['name'] ?? 'Release'); ?>
                        </button>
                    <?php endforeach; ?>
                </div>

                <div class="track-tabs__panels merch-tabs__panels">
                    <?php foreach ($merchReleases as $index => $release): ?>
                        <?php
                        $releaseKey = $release['key'] ?? '';
                        $tabId = 'merch-tab-' . $releaseKey;
                        $panelId = 'merch-panel-' . $releaseKey;
                        $isActive = $index === 0;
                        $releaseItems = $itemsByRelease[$releaseKey] ?? [];
                        ?>
                        <div
                            id="<?= $esc($panelId); ?>"
                            class="track-tabs__panel merch-tabs__panel<?= $isActive ? ' is-active' : ''; ?>"
                            role="tabpanel"
                            aria-labelledby="<?= $esc($tabId); ?>"
                            <?= $isActive ? '' : 'hidden'; ?>
                        >
                            <?php if (!empty($releaseItems)): ?>
                                <div class="merch-grid">
                                    <?php foreach ($releaseItems as $item): ?>
                                        <?php
                                        $image = $item['image'] ?? '';
                                        $releaseName = $item['release_name'] ?? 'SYZYGY.VOID';
                                        $formatTitle = $item['format_title'] ?? 'Format';
                                        $description = $item['description'] ?? 'Preorder placeholder.';
                                        $link = $item['link'] ?? '#';
                                        $linkLabel = $item['link_label'] ?? 'Preorder';
                                        $alt = $item['alt'] ?? ($releaseName . ' ' . $formatTitle);
                                        ?>
                                        <article class="merch-card">
                                            <div class="merch-card__media">
                                                <button
                                                    type="button"
                                                    class="merch-card__media-trigger"
                                                    data-merch-lightbox-trigger
                                                    data-merch-image="<?= $esc($image); ?>"
                                                    data-merch-alt="<?= $esc($alt); ?>"
                                                    data-merch-eyebrow="<?= $esc($releaseName); ?>"
                                                    data-merch-title="<?= $esc($formatTitle); ?>"
                                                    data-merch-description="<?= $esc($description); ?>"
                                                    data-merch-link="<?= $esc($link); ?>"
                                                    data-merch-link-label="<?= $esc($linkLabel); ?>"
                                                    aria-label="<?= $esc('Open ' . $releaseName . ' ' . $formatTitle . ' preview'); ?>"
                                                >
                                                    <img
                                                        class="merch-card__image"
                                                        src="<?= $esc($image); ?>"
                                                        alt="<?= $esc($alt); ?>"
                                                        loading="lazy"
                                                    >
                                                </button>
                                            </div>

                                            <div class="merch-card__body">
                                                <p class="merch-card__eyebrow"><?= $esc($releaseName); ?></p>
                                                <h3 class="merch-card__title"><?= $esc($formatTitle); ?></h3>
                                                <p class="merch-card__description"><?= $esc($description); ?></p>

                                                <a
                                                    class="merch-card__cta"
                                                    href="<?= $esc($link); ?>"
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    <?= ($link === '#' || $link === '') ? 'onclick="return false;"' : ''; ?>
                                                    aria-label="<?= $esc($linkLabel . ' ' . $releaseName . ' ' . $formatTitle); ?>"
                                                >
                                                    <?= $esc($linkLabel); ?>
                                                </a>
                                            </div>
                                        </article>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="merch-empty">No merch artwork found for this release yet.</p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="merch-lightbox" id="merch-lightbox" hidden aria-hidden="true">
        <div class="merch-lightbox__backdrop" data-merch-lightbox-close></div>

        <div
            class="merch-lightbox__dialog"
            role="dialog"
            aria-modal="true"
            aria-labelledby="merch-lightbox-title"
        >
            <button
                type="button"
                class="merch-lightbox__close"
                data-merch-lightbox-close
                aria-label="Close merch preview"
            >
                ×
            </button>

            <div class="merch-lightbox__media">
                <img
                    class="merch-lightbox__image"
                    id="merch-lightbox-image"
                    src=""
                    alt=""
                >
            </div>

            <div class="merch-lightbox__content">
                <p class="merch-lightbox__eyebrow" id="merch-lightbox-eyebrow"></p>
                <h3 class="merch-lightbox__title" id="merch-lightbox-title"></h3>
                <p class="merch-lightbox__description" id="merch-lightbox-description"></p>

                <a
                    class="merch-lightbox__cta"
                    id="merch-lightbox-link"
                    href="#"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    Preorder
                </a>
            </div>
        </div>
    </div>
</section>