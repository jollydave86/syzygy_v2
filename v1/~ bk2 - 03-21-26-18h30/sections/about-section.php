<section class="section about-section" id="about">
    <div class="container">
        <div class="about-grid">
            <div class="about-media">
            <div class="about-media__frame">
                <img
                    src="/assets/img-optimized/about-image-800.webp"
                    alt="SYZYGY.VOID promotional image"
                    class="about-media__image"
                    loading="lazy"
                >
            </div>
            </div>

            <div class="about-copy">
                <p class="section__eyebrow"><?= htmlspecialchars($site['about']['eyebrow']); ?></p>
                <h2 class="section__title about-copy__title"><?= htmlspecialchars($site['about']['title']); ?></h2>

                <p class="about-copy__text">
                    <?= htmlspecialchars($site['about']['text']); ?>
                </p>

                <p class="about-copy__text about-copy__text--muted">
                    <?= htmlspecialchars($site['about']['text_2']); ?>
                </p>

                <div class="stats-grid">
                    <?php foreach ($site['about']['stats'] as $stat): ?>
                        <article class="stat-card">
                            <p class="stat-card__value"><?= htmlspecialchars($stat['value']); ?></p>
                            <p class="stat-card__label"><?= htmlspecialchars($stat['label']); ?></p>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>