<?php declare(strict_types=1); ?>
<section class="page-header">
    <div class="container">
        <p class="section__eyebrow"><a href="<?= syzygy_esc(syzygy_url('/blog')); ?>">Blog</a> / <?= syzygy_esc($post['eyebrow'] ?? 'Journal'); ?></p>
        <h1 class="section__title"><?= syzygy_esc($post['title']); ?></h1>
        <p class="section__lede"><?= syzygy_esc($post['date'] ?? ''); ?></p>
    </div>
</section>

<section class="section">
    <div class="container prose-wrap">
        <?php if (!empty($post['image'])): ?>
            <figure class="blog-featured-image">
                <img src="<?= syzygy_esc(syzygy_encode_public_path($post['image'])); ?>" alt="<?= syzygy_esc($post['title']); ?>" loading="eager">
            </figure>
        <?php endif; ?>
        <div class="prose-html">
            <?= $post['body'] ?? ''; ?>
        </div>
        <p><a class="btn btn--secondary" href="<?= syzygy_esc(syzygy_url('/blog')); ?>">← All Journals</a></p>
    </div>
</section>
