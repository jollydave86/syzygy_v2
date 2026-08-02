<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (empty($_SESSION['contact_csrf'])) {
    $_SESSION['contact_csrf'] = bin2hex(random_bytes(32));
}

require __DIR__ . '/data/site.php';
require __DIR__ . '/includes/image-helpers.php';
require __DIR__ . '/data/tracks.php';
require __DIR__ . '/data/videos.php';
require __DIR__ . '/data/gallery.php';
require __DIR__ . '/includes/link-helpers.php';
$merch = require __DIR__ . '/data/merch.php';
$contact = require __DIR__ . '/data/contact.php';

require __DIR__ . '/includes/contact-form-handler.php';

$contactFormState = $_SESSION['contact_form_flash'] ?? [
    'status' => null,
    'message' => '',
    'errors' => [],
    'old' => [
        'name' => '',
        'email' => '',
        'subject' => '',
        'message' => '',
        'newsletter' => '0',
    ],
];

unset($_SESSION['contact_form_flash']);

$contactCsrf = $_SESSION['contact_csrf'];
$assetVersion = rawurlencode((string) ($site['asset_version'] ?? '20260321f'));
?>
<!DOCTYPE html>
<html lang="en-CA">
<?php include __DIR__ . '/includes/head.php'; ?>
<body>
    <noscript>
        <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KNZ2B3HL"
        height="0" width="0" style="display:none;visibility:hidden"></iframe>
    </noscript>
    <?php include __DIR__ . '/includes/header.php'; ?>

<main class="site-main">
    <?php include __DIR__ . '/sections/hero.php'; ?>
    <?php include __DIR__ . '/sections/about-section.php'; ?>
    <?php include __DIR__ . '/sections/tracks-section.php'; ?>
    <?php include __DIR__ . '/sections/videos-section.php'; ?>
    <?php include __DIR__ . '/sections/gallery-section.php'; ?>
    <?php include __DIR__ . '/sections/merch-section.php'; ?>
    <?php include __DIR__ . '/sections/contact-section.php'; ?>
    <?php include __DIR__ . '/sections/lightbox.php'; ?>
</main>

    <button
        class="back-to-top"
        type="button"
        aria-label="Back to top"
        data-back-to-top
        hidden
    >
        <span aria-hidden="true">↑</span>
    </button>

    <?php include __DIR__ . '/includes/footer.php'; ?>

    <script src="/assets/js/utils.js?v=<?= $assetVersion; ?>" defer></script>
    <script src="/assets/js/tabs.js?v=<?= $assetVersion; ?>" defer></script>
    <script src="/assets/js/menu.js?v=<?= $assetVersion; ?>" defer></script>
    <script src="/assets/js/gallery.js?v=<?= $assetVersion; ?>" defer></script>
    <script src="/assets/js/lightbox.js?v=<?= $assetVersion; ?>" defer></script>
    <script src="/assets/js/video-modal.js?v=<?= $assetVersion; ?>" defer></script>
    <script src="/assets/js/main.js?v=<?= $assetVersion; ?>" defer></script>
</body>
</html>