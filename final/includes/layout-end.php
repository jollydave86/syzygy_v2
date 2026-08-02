<?php
declare(strict_types=1);
$assetVersion = rawurlencode((string) ($site['asset_version'] ?? '20260802a'));
$currentPath = syzygy_current_path();
$loadLyricsJs = str_starts_with($currentPath, '/lyrics');
$loadGalleryJs = str_starts_with($currentPath, '/gallery');
?>
</main>

<footer class="site-footer">
    <div class="container site-footer__inner site-footer__grid">
        <div class="site-footer__brand">
            <p class="site-footer__name"><?= syzygy_esc($site['name'] ?? 'SYZYGY.VOID'); ?></p>
            <p class="site-footer__tagline"><?= syzygy_esc($site['footer']['tagline'] ?? ''); ?></p>
        </div>

        <div class="site-footer__col">
            <p class="site-footer__heading">Navigate</p>
            <ul class="site-footer__links">
                <?php foreach (array_slice($site['nav'] ?? [], 0, 6) as $item): ?>
                    <li><a href="<?= syzygy_esc(syzygy_url($item['href'] ?? '/')); ?>"><?= syzygy_esc($item['label'] ?? ''); ?></a></li>
                <?php endforeach; ?>
                <li><a href="<?= syzygy_esc(syzygy_url('/booking')); ?>">Booking</a></li>
            </ul>
        </div>

        <div class="site-footer__col">
            <p class="site-footer__heading">Listen</p>
            <?php
            $platformLinksMap = null; // force site artist defaults
            require __DIR__ . '/platform-links.php';
            ?>
        </div>

        <p class="site-footer__copyright"><?= syzygy_esc($site['footer']['copyright'] ?? ''); ?></p>
    </div>
</footer>

<button class="back-to-top" type="button" data-back-to-top aria-label="Back to top" hidden>
    <span aria-hidden="true">↑</span>
</button>

<script src="<?= syzygy_esc(syzygy_encode_public_path('/assets/js/utils.js')); ?>?v=<?= $assetVersion; ?>" defer></script>
<script src="<?= syzygy_esc(syzygy_encode_public_path('/assets/js/menu.js')); ?>?v=<?= $assetVersion; ?>" defer></script>
<?php if ($loadLyricsJs): ?>
<script src="<?= syzygy_esc(syzygy_encode_public_path('/assets/js/lyrics.js')); ?>?v=<?= $assetVersion; ?>" defer></script>
<?php endif; ?>
<?php if ($loadGalleryJs): ?>
<script src="<?= syzygy_esc(syzygy_encode_public_path('/assets/js/gallery.js')); ?>?v=<?= $assetVersion; ?>" defer></script>
<script src="<?= syzygy_esc(syzygy_encode_public_path('/assets/js/lightbox.js')); ?>?v=<?= $assetVersion; ?>" defer></script>
<?php endif; ?>
<script src="<?= syzygy_esc(syzygy_encode_public_path('/assets/js/main.js')); ?>?v=<?= $assetVersion; ?>" defer></script>
</body>
</html>
