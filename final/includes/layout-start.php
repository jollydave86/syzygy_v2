<?php
declare(strict_types=1);

$pageTitle = $pageTitle ?? ($site['title'] ?? 'SYZYGY.VOID');
$pageDescription = $pageDescription ?? ($site['description'] ?? '');
$pageKeywords = $pageKeywords ?? ($site['keywords'] ?? []);
if (is_array($pageKeywords)) {
    $pageKeywords = implode(', ', array_values(array_filter(array_map('strval', $pageKeywords))));
}
$schemaPage = $schemaPage ?? ($page ?? 'home');
$assetVersion = rawurlencode((string) ($site['asset_version'] ?? '20260802a'));
$siteName = $site['name'] ?? 'SYZYGY.VOID';
$baseUrl = rtrim($site['url'] ?? 'https://syzygyvoid.ca', '/');
$pathOnly = syzygy_current_path();
$canonicalUrl = $pathOnly === '/' ? $baseUrl . '/' : $baseUrl . $pathOnly;
$themeColor = $site['theme_color'] ?? '#001f3c';
$robots = $site['robots'] ?? 'index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1';
if (($schemaPage ?? '') === '404') {
    $robots = 'noindex,follow';
}
$ogType = $pageOgType ?? ($site['og_type'] ?? 'website');
$ogImage = $pageOgImage ?? ($site['og_image'] ?? '/assets/img-optimized/hero-bg-1200.webp');
$ogImageAbs = preg_match('#^https?://#i', (string) $ogImage)
    ? (string) $ogImage
    : syzygy_abs_url((string) $ogImage);
$ogImageAlt = $pageOgImageAlt ?? ($site['og_image_alt'] ?? $siteName);
$locale = $site['locale'] ?? 'en_CA';
$gtmId = trim((string) ($site['gtm_id'] ?? ''));

$schemaGraph = syzygy_build_schema_graph((string) $schemaPage, [
    'pageTitle' => $pageTitle,
    'pageDescription' => $pageDescription,
    'path' => $pathOnly,
    'ogImage' => (string) $ogImage,
    'release' => $release ?? null,
    'playlist' => $playlist ?? null,
    'member' => $member ?? null,
    'lyric' => $lyric ?? null,
    'post' => $post ?? null,
]);
?>
<!DOCTYPE html>
<html lang="<?= syzygy_esc(str_replace('_', '-', (string) $locale)); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <title><?= syzygy_esc($pageTitle); ?></title>
    <meta name="description" content="<?= syzygy_esc($pageDescription); ?>">
    <?php if ($pageKeywords !== ''): ?>
    <meta name="keywords" content="<?= syzygy_esc($pageKeywords); ?>">
    <?php endif; ?>
    <meta name="robots" content="<?= syzygy_esc($robots); ?>">
    <meta name="author" content="<?= syzygy_esc($siteName); ?>">
    <meta name="language" content="<?= syzygy_esc(str_replace('_', '-', (string) $locale)); ?>">
    <link rel="canonical" href="<?= syzygy_esc($canonicalUrl); ?>">
    <meta name="theme-color" content="<?= syzygy_esc($themeColor); ?>">
    <meta name="geo.region" content="CA-QC">
    <meta name="geo.placename" content="Montreal">
    <meta property="og:locale" content="<?= syzygy_esc($locale); ?>">
    <meta property="og:type" content="<?= syzygy_esc($ogType); ?>">
    <meta property="og:site_name" content="<?= syzygy_esc($siteName); ?>">
    <meta property="og:title" content="<?= syzygy_esc($pageTitle); ?>">
    <meta property="og:description" content="<?= syzygy_esc($pageDescription); ?>">
    <meta property="og:url" content="<?= syzygy_esc($canonicalUrl); ?>">
    <meta property="og:image" content="<?= syzygy_esc($ogImageAbs); ?>">
    <meta property="og:image:alt" content="<?= syzygy_esc($ogImageAlt); ?>">
    <meta name="twitter:card" content="<?= syzygy_esc($site['twitter_card'] ?? 'summary_large_image'); ?>">
    <meta name="twitter:title" content="<?= syzygy_esc($pageTitle); ?>">
    <meta name="twitter:description" content="<?= syzygy_esc($pageDescription); ?>">
    <meta name="twitter:image" content="<?= syzygy_esc($ogImageAbs); ?>">
    <?php if ($gtmId !== ''): ?>
    <link rel="preconnect" href="https://www.googletagmanager.com">
    <link rel="dns-prefetch" href="https://www.googletagmanager.com">
    <?php endif; ?>
    <?php
    $heroPreloadBase = '/assets/img-optimized/hero-bg.webp';
    $heroPreload = syzygy_prefer_public_variant($heroPreloadBase, [800, 1200, 400]);
    if (($schemaPage ?? '') === 'home'):
    ?>
    <link rel="preload" as="image" href="<?= syzygy_esc($heroPreload); ?>" fetchpriority="high" imagesizes="100vw" imagesrcset="<?= syzygy_esc(syzygy_prefer_public_variant($heroPreloadBase, [800])); ?> 800w, <?= syzygy_esc(syzygy_prefer_public_variant($heroPreloadBase, [1200])); ?> 1200w">
    <?php endif; ?>
    <link rel="preload" as="font" type="font/woff2" href="<?= syzygy_esc(syzygy_encode_public_path('/assets/fonts/JTUSjIg69CK48gW7PXoo9Wlhyw.woff2')); ?>" crossorigin>
    <link rel="preload" as="font" type="font/woff2" href="<?= syzygy_esc(syzygy_encode_public_path('/assets/fonts/UcCO3FwrK3iLTeHuS_nVMrMxCp50SjIw2boKoduKmMEVuLyfAZ9hiA.woff2')); ?>" crossorigin>
    <link rel="preload" as="font" type="font/woff2" href="<?= syzygy_esc(syzygy_encode_public_path('/assets/fonts/LDI2apCSOBg7S-QT7pa8FvOreec.woff2')); ?>" crossorigin>
    <link rel="stylesheet" href="<?= syzygy_esc(syzygy_encode_public_path('/assets/css/style.bundle.css')); ?>?v=<?= $assetVersion; ?>">
    <link rel="shortcut icon" href="<?= syzygy_esc(syzygy_encode_public_path('/favicon.ico')); ?>?v=<?= $assetVersion; ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= syzygy_esc(syzygy_encode_public_path('/apple-touch-icon.png')); ?>?v=<?= $assetVersion; ?>">
    <?php syzygy_print_json_ld($schemaGraph); ?>
    <?php if ($gtmId !== ''): ?>
    <script>
    window.dataLayer=window.dataLayer||[];
    window.addEventListener('load',function(){setTimeout(function(){
      (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});
      var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';
      j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
      })(window,document,'script','dataLayer','<?= syzygy_esc($gtmId); ?>');
    },1200);});
    </script>
    <?php endif; ?>
</head>
<body>
<?php if ($gtmId !== ''): ?>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?= syzygy_esc($gtmId); ?>"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<?php endif; ?>
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
