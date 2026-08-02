<?php declare(strict_types=1); ?>
<section class="page-header">
    <div class="container">
        <p class="section__eyebrow">Solo Signals</p>
        <h1 class="section__title">Profiles</h1>
        <p class="section__lede">What each person sounds like when separated from the shared SYZYGY.VOID machine.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="profile-grid">
            <?php foreach ($members as $member): ?>
                <a class="profile-card<?= ($member['status'] ?? '') === 'coming_soon' ? ' profile-card--soon' : ''; ?>" href="<?= syzygy_esc(syzygy_url('/profiles/' . $member['slug'])); ?>">
                    <div class="profile-card__media">
                        <?php if (!empty($member['image']) && syzygy_public_path_exists($member['image'])): ?>
                            <img src="<?= syzygy_esc(syzygy_encode_public_path($member['image'])); ?>" alt="<?= syzygy_esc($member['name']); ?>" loading="lazy">
                        <?php endif; ?>
                    </div>
                    <p class="profile-card__badge"><?= syzygy_esc($member['badge'] ?? ''); ?></p>
                    <h2 class="profile-card__name"><?= syzygy_esc($member['name']); ?></h2>
                    <p class="profile-card__role"><?= syzygy_esc($member['role']); ?></p>
                    <p class="profile-card__teaser"><?= syzygy_esc($member['teaser']); ?></p>
                    <?php if (!empty($member['release'])): ?>
                        <p class="profile-card__release"><?= syzygy_esc($member['release']); ?></p>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
