<?php if (!empty($videos)): ?>
<section id="videos" class="section videos-section">
    <div class="container">
        <div class="section-heading">
            <p class="section__eyebrow">VIDEOS</p>
            <h2 class="section__title">Visual transmissions</h2>
        </div>

        <div class="video-grid">
            <?php foreach ($videos as $video): ?>
                <article class="video-card">
                    <?php if (($video['type'] ?? 'embed') === 'external'): ?>
                        <a
                            class="video-card__trigger"
                            href="<?= htmlspecialchars($video['url']); ?>"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            <div class="video-card__media">
                                <?php if (!empty($video['thumbnail'])): ?>
                                    <img
                                        class="video-card__image"
                                        src="<?= htmlspecialchars(syzygy_encode_public_path($video['thumbnail']), ENT_QUOTES, 'UTF-8'); ?>"
                                        alt="<?= htmlspecialchars($video['title']); ?>"
                                        loading="lazy"
                                    >
                                <?php endif; ?>
                                <span class="video-card__overlay"></span>
                            </div>

                            <div class="video-card__body">
                                <h3 class="video-card__title"><?= htmlspecialchars($video['title']); ?></h3>

                                <?php if (!empty($video['description'])): ?>
                                    <p class="video-card__description"><?= htmlspecialchars($video['description']); ?></p>
                                <?php endif; ?>

                                <span class="video-card__link">
                                    <?= htmlspecialchars($video['button_label'] ?? 'Open'); ?>
                                </span>
                            </div>
                        </a>
                    <?php else: ?>
                        <button
                            type="button"
                            class="video-card__trigger"
                            data-video-modal-trigger
                            data-video-title="<?= htmlspecialchars($video['title']); ?>"
                            data-video-description="<?= htmlspecialchars($video['description'] ?? ''); ?>"
                            data-video-embed="<?= htmlspecialchars($video['embed_url']); ?>"
                        >
                            <div class="video-card__media">
                                <?php if (!empty($video['image'])): ?>
                                    <img
                                        class="video-card__image"
                                        src="<?= htmlspecialchars(syzygy_encode_public_path($video['image']), ENT_QUOTES, 'UTF-8'); ?>"
                                        alt="<?= htmlspecialchars($video['title']); ?>"
                                        loading="lazy"
                                    >
                                <?php endif; ?>

                                <span class="video-card__overlay"></span>
                                <span class="video-card__play" aria-hidden="true">
                                    <span class="video-card__play-icon"></span>
                                </span>
                            </div>

                            <div class="video-card__body">
                                <h3 class="video-card__title"><?= htmlspecialchars($video['title']); ?></h3>

                                <?php if (!empty($video['description'])): ?>
                                    <p class="video-card__description"><?= htmlspecialchars($video['description']); ?></p>
                                <?php endif; ?>
                            </div>
                        </button>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
