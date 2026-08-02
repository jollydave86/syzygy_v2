<?php
declare(strict_types=1);

$pageTitle = $pageTitle ?? ($site['title'] ?? 'SYZYGY.VOID');
$pageDescription = $pageDescription ?? ($site['description'] ?? '');
$assetVersion = rawurlencode((string) ($site['asset_version'] ?? '20260802a'));
$siteName = $site['name'] ?? 'SYZYGY.VOID';
$baseUrl = rtrim($site['url'] ?? 'https://syzygyvoid.ca', '/');
$pathOnly = syzygy_current_path();
$canonicalUrl = $pathOnly === '/' ? $baseUrl . '/' : $baseUrl . $pathOnly;
$themeColor = $site['theme_color'] ?? '#001f3c';
$ogImage = $site['og_image'] ?? '/assets/img-optimized/hero-bg-1200.webp';
if (!preg_match('#^https?://#i', (string) $ogImage)) {
    $ogImage = $baseUrl . '/' . ltrim((string) $ogImage, '/');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <title><?= syzygy_esc($pageTitle); ?></title>
    <meta name="description" content="<?= syzygy_esc($pageDescription); ?>">
    <link rel="canonical" href="<?= syzygy_esc($canonicalUrl); ?>">
    <meta name="theme-color" content="<?= syzygy_esc($themeColor); ?>">
    <meta property="og:site_name" content="<?= syzygy_esc($siteName); ?>">
    <meta property="og:title" content="<?= syzygy_esc($pageTitle); ?>">
    <meta property="og:description" content="<?= syzygy_esc($pageDescription); ?>">
    <meta property="og:url" content="<?= syzygy_esc($canonicalUrl); ?>">
    <meta property="og:image" content="<?= syzygy_esc($ogImage); ?>">
    <meta name="twitter:card" content="summary_large_image">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;500;600;700&family=Rajdhani:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= syzygy_esc(syzygy_encode_public_path('/assets/css/style.css')); ?>?v=<?= $assetVersion; ?>">
    <link rel="shortcut icon" href="<?= syzygy_esc(syzygy_encode_public_path('/favicon.ico')); ?>?v=<?= $assetVersion; ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= syzygy_esc(syzygy_encode_public_path('/apple-touch-icon.png')); ?>?v=<?= $assetVersion; ?>">
</head>
<body>
<header class="site-header" data-site-header>
    <div class="container site-header__inner">
        <a href="<?= syzygy_esc(syzygy_url('/')); ?>" class="site-logo" aria-label="<?= syzygy_esc($siteName); ?>">
            <?= syzygy_esc($site['short_name'] ?? $siteName); ?>
        </a>

        <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="primary-menu" aria-label="Toggle navigation">
            <span class="menu-toggle__line"></span>
            <span class="menu-toggle__line"></span>
            <span class="menu-toggle__line"></span>
        </button>

        <nav class="site-nav" id="primary-menu" aria-label="Primary navigation">
            <ul class="site-nav__list">
                <?php foreach (($site['nav'] ?? []) as $item): ?>
                    <?php
                    $href = syzygy_url($item['href'] ?? '/');
                    $isCurrent = syzygy_is_current($item['href'] ?? '/');
                    ?>
                    <li class="site-nav__item">
                        <a class="site-nav__link<?= $isCurrent ? ' is-current' : ''; ?>" href="<?= syzygy_esc($href); ?>">
                            <?= syzygy_esc($item['label'] ?? ''); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>
    </div>
</header>
<main class="site-main">
