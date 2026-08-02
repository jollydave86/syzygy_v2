<?php declare(strict_types=1); ?>
<section class="page-header">
    <div class="container">
        <p class="section__eyebrow">Catalog</p>
        <h1 class="section__title">Music</h1>
        <p class="section__lede">Serialized release worlds and Solo Signals — each with its own sound, visual language, and story system.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="release-grid">
            <?php foreach ($releases as $release): ?>
                <a class="release-card" href="<?= syzygy_esc(syzygy_url('/music/' . $release['slug'])); ?>">
                    <div class="release-card__media">
                        <img src="<?= syzygy_esc(syzygy_encode_public_path($release['cover'])); ?>" alt="<?= syzygy_esc($release['title']); ?>" width="400" height="400" loading="lazy" decoding="async">
                    </div>
                    <p class="release-card__eyebrow"><?= syzygy_esc($release['eyebrow'] ?? ''); ?></p>
                    <h2 class="release-card__title"><?= syzygy_esc($release['title']); ?></h2>
                    <p class="release-card__text"><?= syzygy_esc($release['summary'] ?? ''); ?></p>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
