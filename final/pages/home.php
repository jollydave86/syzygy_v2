<?php
declare(strict_types=1);
$hero = $site['hero'] ?? [];
$highlights = $site['highlights'] ?? [];
?>
<section class="page-hero page-hero--home">
    <?php
    $heroBase = '/assets/img-optimized/hero-bg.webp';
    $hero800 = syzygy_prefer_public_variant($heroBase, [800, 400]);
    $hero1200 = syzygy_prefer_public_variant($heroBase, [1200, 800]);
    ?>
    <div class="page-hero__bg">
        <img
            class="page-hero__bg-image"
            src="<?= syzygy_esc($hero800); ?>"
            srcset="<?= syzygy_esc($hero800); ?> 800w, <?= syzygy_esc($hero1200); ?> 1200w"
            sizes="100vw"
            alt=""
            width="1200"
            height="800"
            decoding="async"
            fetchpriority="high"
        >
    </div>
    <div class="page-hero__overlay"></div>
    <div class="container page-hero__content">
        <p class="section__eyebrow"><?= syzygy_esc($hero['eyebrow'] ?? ''); ?></p>
        <h1 class="page-hero__title">
            <span class="page-hero__brand"><?= syzygy_esc($hero['heading_line_1'] ?? 'SYZYGY.VOID'); ?></span>
            <span class="page-hero__sub"><?= syzygy_esc($hero['heading_line_2'] ?? ''); ?></span>
        </h1>
        <p class="page-hero__text"><?= syzygy_esc($hero['text'] ?? ''); ?></p>
        <div class="page-hero__actions">
            <a class="btn btn--primary" href="<?= syzygy_esc(syzygy_url($hero['primary_cta']['href'] ?? '/music')); ?>">
                <?= syzygy_esc($hero['primary_cta']['label'] ?? 'Enter Music'); ?>
            </a>
            <a class="btn btn--secondary" href="<?= syzygy_esc(syzygy_url($hero['secondary_cta']['href'] ?? '/profiles')); ?>">
                <?= syzygy_esc($hero['secondary_cta']['label'] ?? 'Solo Signals'); ?>
            </a>
        </div>
    </div>
</section>

<section class="section highlight-section">
    <div class="container">
        <div class="section-heading section-heading--center">
            <p class="section__eyebrow">Enter the System</p>
            <h2 class="section__title">Choose a Transmission</h2>
        </div>
        <div class="highlight-grid">
            <?php foreach ($highlights as $item): ?>
                <a class="highlight-card" href="<?= syzygy_esc(syzygy_url($item['href'] ?? '/')); ?>">
                    <p class="highlight-card__eyebrow"><?= syzygy_esc($item['eyebrow'] ?? ''); ?></p>
                    <h3 class="highlight-card__title"><?= syzygy_esc($item['title'] ?? ''); ?></h3>
                    <p class="highlight-card__text"><?= syzygy_esc($item['text'] ?? ''); ?></p>
                    <span class="highlight-card__cta"><?= syzygy_esc($item['cta'] ?? 'Open'); ?> →</span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section section--dark home-teaser">
    <div class="container home-teaser__grid">
        <div class="home-teaser__copy">
            <p class="section__eyebrow">Current Signal</p>
            <h2 class="section__title">Serialized Music Worlds</h2>
            <p class="section__lede">Move through communications systems, cyber-noir streets, synthetic religion, arcade horror, and the emotional distance between cities.</p>
            <a class="btn btn--primary" href="<?= syzygy_esc(syzygy_url('/music')); ?>">Explore the Catalog</a>
        </div>
        <div class="home-teaser__releases">
            <?php foreach (array_slice($releases, 0, 3) as $release): ?>
                <a class="home-mini-release" href="<?= syzygy_esc(syzygy_url('/music/' . $release['slug'])); ?>">
                    <img src="<?= syzygy_esc(syzygy_encode_public_path($release['cover'])); ?>" alt="" width="400" height="400" loading="lazy" decoding="async">
                    <span><?= syzygy_esc($release['title']); ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section home-teaser">
    <div class="container home-teaser__grid home-teaser__grid--reverse">
        <div class="home-teaser__copy">
            <p class="section__eyebrow">Solo Signals</p>
            <h2 class="section__title">Six Voices Outside the Machine</h2>
            <p class="section__lede">Nova’s voltage, Ash’s damaged minimalism, Lyra’s body-clock percussion, and Lucien’s forward-motion electro rap reveal what changes when the shared system goes quiet.</p>
            <a class="btn btn--secondary" href="<?= syzygy_esc(syzygy_url('/profiles')); ?>">Meet the Signals</a>
        </div>
        <div class="home-profile-strip">
            <?php foreach (array_slice($members, 0, 4) as $member): ?>
                <a href="<?= syzygy_esc(syzygy_url('/profiles/' . $member['slug'])); ?>">
                    <img src="<?= syzygy_esc(syzygy_prefer_public_variant($member['image'], [250, 400])); ?>" alt="<?= syzygy_esc($member['name']); ?>" width="250" height="250" loading="lazy" decoding="async">
                    <span><?= syzygy_esc($member['name']); ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section section--dark home-journal-teaser">
    <div class="container">
        <div class="section-heading">
            <p class="section__eyebrow">From the Journal</p>
            <h2 class="section__title">History Behind the Signal</h2>
        </div>
        <div class="blog-grid">
            <?php foreach (array_slice($blogPosts, 0, 3) as $post): ?>
                <article class="blog-card">
                    <p class="blog-card__eyebrow"><?= syzygy_esc($post['eyebrow']); ?> · <?= syzygy_esc($post['date']); ?></p>
                    <h3 class="blog-card__title"><a href="<?= syzygy_esc(syzygy_url('/blog/' . $post['slug'])); ?>"><?= syzygy_esc($post['title']); ?></a></h3>
                    <p class="blog-card__excerpt"><?= syzygy_esc($post['excerpt']); ?></p>
                    <a class="blog-card__cta" href="<?= syzygy_esc(syzygy_url('/blog/' . $post['slug'])); ?>">Read Journal →</a>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
