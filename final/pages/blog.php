<?php declare(strict_types=1); ?>
<section class="page-header">
    <div class="container">
        <p class="section__eyebrow">Journals</p>
        <h1 class="section__title">Blog</h1>
        <p class="section__lede">Working notes from the signal.</p>
    </div>
</section>

<section class="section">
    <div class="container blog-grid">
        <?php foreach ($blogPosts as $post): ?>
            <article class="blog-card">
                <?php if (!empty($post['image'])): ?>
                    <?php $postImg = syzygy_responsive_image((string) $post['image'], [400, 800]); ?>
                    <a class="blog-card__media" href="<?= syzygy_esc(syzygy_url('/blog/' . $post['slug'])); ?>">
                        <img src="<?= syzygy_esc($postImg['src']); ?>" srcset="<?= syzygy_esc($postImg['srcset']); ?>" sizes="(max-width: 700px) 92vw, 360px" alt="" width="800" height="450" loading="lazy" decoding="async">
                    </a>
                <?php endif; ?>
                <p class="blog-card__eyebrow"><?= syzygy_esc($post['eyebrow'] ?? ''); ?> · <?= syzygy_esc($post['date'] ?? ''); ?></p>
                <h2 class="blog-card__title">
                    <a href="<?= syzygy_esc(syzygy_url('/blog/' . $post['slug'])); ?>"><?= syzygy_esc($post['title']); ?></a>
                </h2>
                <p class="blog-card__excerpt"><?= syzygy_esc($post['excerpt'] ?? ''); ?></p>
                <a class="blog-card__cta" href="<?= syzygy_esc(syzygy_url('/blog/' . $post['slug'])); ?>">Read Journal →</a>
            </article>
        <?php endforeach; ?>
    </div>
</section>
