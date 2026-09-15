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
            <p class="section__lede">The full catalogue wall — Singles Night V5.5 beside Featured Signals, Forever City, switchboard crises, cyber-noir streets, chapel protocols, arcade horror, Solo Signals including Occupancy Zero, and live transmissions. Every playlist world, not a recent slice.</p>
            <a class="btn btn--primary" href="<?= syzygy_esc(syzygy_url('/music')); ?>">Explore the Catalog</a>
        </div>
        <div class="home-teaser__releases">
            <?php foreach ($releases as $release): ?>
                <?php $coverImg = syzygy_responsive_image((string) ($release['cover'] ?? ''), [400, 800]); ?>
                <a class="home-mini-release" href="<?= syzygy_esc(syzygy_url('/music/' . $release['slug'])); ?>">
                    <img src="<?= syzygy_esc($coverImg['src']); ?>" srcset="<?= syzygy_esc($coverImg['srcset']); ?>" sizes="(max-width: 700px) 45vw, 180px" alt="" width="400" height="400" loading="lazy" decoding="async">
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
            <p class="section__lede">Nova’s voltage, Ash’s damaged minimalism, Lyra’s body-clock percussion, Lucien’s forward-motion electro rap, and Kade’s occupancy architecture reveal what changes when the shared system goes quiet.</p>
            <a class="btn btn--secondary" href="<?= syzygy_esc(syzygy_url('/profiles')); ?>">Meet the Signals</a>
        </div>
        <div class="home-profile-strip">
            <?php
            $readyMembers = array_values(array_filter($members, static fn($m) => ($m['status'] ?? '') === 'ready'));
            foreach ($readyMembers as $member): ?>
                <a href="<?= syzygy_esc(syzygy_url('/profiles/' . $member['slug'])); ?>">
                    <img src="<?= syzygy_esc(syzygy_prefer_public_variant($member['image'], [250, 400])); ?>" alt="<?= syzygy_esc($member['name']); ?>" width="250" height="250" loading="lazy" decoding="async">
                    <span><?= syzygy_esc($member['name']); ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section section--dark home-teaser">
    <div class="container home-teaser__grid">
        <div class="home-teaser__copy">
            <p class="section__eyebrow">Archive Store</p>
            <h2 class="section__title">Physical Concepts, Compressed</h2>
            <p class="section__lede">CD, vinyl, cassette, and apparel mockups for Occupancy Zero and the Era 3 solo EPs — photographed product language, WebP variants, preorder only. Official labeled merch folders can replace these the moment they sync.</p>
            <a class="btn btn--primary" href="<?= syzygy_esc(syzygy_url('/merch')); ?>">Browse Merch</a>
        </div>
        <div class="home-merch-strip">
            <?php
            $homeMerchKeys = ['occupancy-zero', 'no-idle-speed', 'white-voltage', 'body-clock', 'the-shape-i-left'];
            $homeMerch = [];
            foreach ($merch['items'] ?? [] as $item) {
                $rk = (string) ($item['release_key'] ?? '');
                if (($item['format_key'] ?? '') !== 'vinyl' || !in_array($rk, $homeMerchKeys, true) || isset($homeMerch[$rk])) {
                    continue;
                }
                $homeMerch[$rk] = $item;
            }
            foreach (array_values($homeMerch) as $item):
                $merchImg = syzygy_responsive_image((string) ($item['image'] ?? ''), [400, 800]);
            ?>
                <a href="<?= syzygy_esc(syzygy_url('/merch')); ?>">
                    <img src="<?= syzygy_esc($merchImg['src']); ?>" srcset="<?= syzygy_esc($merchImg['srcset']); ?>" sizes="(max-width: 700px) 40vw, 160px" alt="<?= syzygy_esc($item['alt'] ?? ''); ?>" width="400" height="267" loading="lazy" decoding="async">
                    <span><?= syzygy_esc(($item['release_name'] ?? '') . ' · ' . ($item['format_title'] ?? '')); ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section home-journal-teaser">
    <div class="container">
        <div class="section-heading">
            <p class="section__eyebrow">From the Journal</p>
            <h2 class="section__title">Autumn Press Through October 20</h2>
        </div>
        <div class="blog-grid">
            <?php foreach (array_slice($blogPosts, 0, 3) as $post): ?>
                <article class="blog-card">
                    <?php if (!empty($post['image'])): ?>
                        <?php $postImg = syzygy_responsive_image((string) $post['image'], [400, 800]); ?>
                        <a class="blog-card__media" href="<?= syzygy_esc(syzygy_url('/blog/' . $post['slug'])); ?>">
                            <img src="<?= syzygy_esc($postImg['src']); ?>" srcset="<?= syzygy_esc($postImg['srcset']); ?>" sizes="(max-width: 700px) 92vw, 360px" alt="" width="800" height="450" loading="lazy" decoding="async">
                        </a>
                    <?php endif; ?>
                    <p class="blog-card__eyebrow"><?= syzygy_esc($post['eyebrow']); ?> · <?= syzygy_esc($post['date']); ?></p>
                    <h3 class="blog-card__title"><a href="<?= syzygy_esc(syzygy_url('/blog/' . $post['slug'])); ?>"><?= syzygy_esc($post['title']); ?></a></h3>
                    <p class="blog-card__excerpt"><?= syzygy_esc($post['excerpt']); ?></p>
                    <a class="blog-card__cta" href="<?= syzygy_esc(syzygy_url('/blog/' . $post['slug'])); ?>">Read Journal →</a>
                </article>
            <?php endforeach; ?>
        </div>
        <p class="home-journal-teaser__more"><a class="btn btn--secondary" href="<?= syzygy_esc(syzygy_url('/blog')); ?>">All Journals</a></p>
    </div>
</section>
