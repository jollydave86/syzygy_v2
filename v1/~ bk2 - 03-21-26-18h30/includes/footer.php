<footer class="site-footer">
    <div class="container site-footer__inner">
        <div class="site-footer__brand">
            <p class="site-footer__name"><?= htmlspecialchars($site['name']); ?></p>
            <p class="site-footer__tagline"><?= htmlspecialchars($site['footer']['tagline']); ?></p>
        </div>

        <div class="site-footer__socials">
            <?php foreach ($site['footer']['socials'] as $social): ?>
                <a href="<?= htmlspecialchars($social['href']); ?>" class="site-footer__social-link">
                    <?= htmlspecialchars($social['label']); ?>
                </a>
            <?php endforeach; ?>
        </div>

        <p class="site-footer__copyright">
            <?= htmlspecialchars($site['footer']['copyright']); ?>
        </p>
    </div>
</footer>