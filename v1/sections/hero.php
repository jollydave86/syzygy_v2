<section class="hero" id="top">
    <div class="hero__bg">
        <img
            src="<?= htmlspecialchars(syzygy_image_public_path($site['hero']['image'], 1200), ENT_QUOTES, 'UTF-8'); ?>"
            alt="<?= htmlspecialchars($site['name']); ?> cosmic background"
            class="hero__bg-image"
            width="1200"
            height="800"
            decoding="async"
            fetchpriority="high"
        >
        <div class="hero__overlay"></div>
        <div class="hero__glow hero__glow--left"></div>
        <div class="hero__glow hero__glow--right"></div>
    </div>

    <div class="container hero__container">
        <div class="hero__content">
            <p class="hero__eyebrow">
                <?= htmlspecialchars($site['hero']['eyebrow']); ?>
            </p>

            <h1 class="hero__title">
                <span class="hero__title-line hero__title-line--primary">
                    <?= htmlspecialchars($site['hero']['heading_line_1']); ?>
                </span>
                <span class="hero__title-line hero__title-line--accent">
                    <?= htmlspecialchars($site['hero']['heading_line_2']); ?>
                </span>
            </h1>

            <p class="hero__text">
                <?= htmlspecialchars($site['hero']['text']); ?>
            </p>

            <div class="hero__actions">
                <a class="btn btn--primary" href="<?= htmlspecialchars($site['hero']['primary_cta']['href']); ?>">
                    <?= htmlspecialchars($site['hero']['primary_cta']['label']); ?>
                </a>

                <a class="btn btn--secondary" href="<?= htmlspecialchars($site['hero']['secondary_cta']['href']); ?>">
                    <?= htmlspecialchars($site['hero']['secondary_cta']['label']); ?>
                </a>
            </div>
        </div>
    </div>
</section>