<?php
$siteName        = $site['name'] ?? 'SYZYGY.VOID';
$pageTitle       = $site['title'] ?? 'SYZYGY.VOID Official Site | Cinematic Darkwave & Electronic Music';
$pageDescription = $site['description'] ?? 'Official site of SYZYGY.VOID — cinematic darkwave, electronic and dark-futurist music with serialized releases, videos, gallery archives and immersive visual identity.';
$assetVersion    = rawurlencode((string) ($site['asset_version'] ?? '20260321f'));

$baseUrl = rtrim($site['url'] ?? 'https://syzygyvoid.ca', '/');
if ($baseUrl === '' || $baseUrl === '/') {
    $baseUrl = 'https://syzygyvoid.ca';
}

$requestUri   = $_SERVER['REQUEST_URI'] ?? '/';
$pathOnly     = strtok($requestUri, '?') ?: '/';
$canonicalUrl = $pathOnly === '/' ? $baseUrl . '/' : $baseUrl . $pathOnly;

$ogTitle       = $site['og_title'] ?? $pageTitle;
$ogDescription = $site['og_description'] ?? $pageDescription;
$ogType        = $site['og_type'] ?? 'website';
$locale        = $site['locale'] ?? 'en_CA';
$themeColor    = $site['theme_color'] ?? '#001f3c';
$robots        = $site['robots'] ?? 'index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1';
$twitterCard   = $site['twitter_card'] ?? 'summary_large_image';

$ogImage = $site['og_image'] ?? '/assets/img/hero-bg.jpg';
if (!preg_match('#^https?://#i', $ogImage)) {
    $ogImage = $baseUrl . '/' . ltrim($ogImage, '/');
}

$ogImageAlt = $site['og_image_alt'] ?? 'SYZYGY.VOID official site — cinematic darkwave and electronic music';
?>
<head>
    <meta charset="UTF-8">

    <!-- Google Tag Manager -->
    <script>
        (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-KNZ2B3HL');
    </script>
    <!-- End Google Tag Manager -->

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">

    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></title>
    <meta name="description" content="<?= htmlspecialchars($pageDescription, ENT_QUOTES, 'UTF-8'); ?>">

    <link rel="canonical" href="<?= htmlspecialchars($canonicalUrl, ENT_QUOTES, 'UTF-8'); ?>">

    <meta name="robots" content="<?= htmlspecialchars($robots, ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="googlebot" content="<?= htmlspecialchars($robots, ENT_QUOTES, 'UTF-8'); ?>">

    <meta name="author" content="<?= htmlspecialchars($siteName, ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="application-name" content="<?= htmlspecialchars($siteName, ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="apple-mobile-web-app-title" content="<?= htmlspecialchars($siteName, ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="theme-color" content="<?= htmlspecialchars($themeColor, ENT_QUOTES, 'UTF-8'); ?>">

    <!-- Open Graph -->
    <meta property="og:locale" content="<?= htmlspecialchars($locale, ENT_QUOTES, 'UTF-8'); ?>">
    <meta property="og:type" content="<?= htmlspecialchars($ogType, ENT_QUOTES, 'UTF-8'); ?>">
    <meta property="og:site_name" content="<?= htmlspecialchars($siteName, ENT_QUOTES, 'UTF-8'); ?>">
    <meta property="og:url" content="<?= htmlspecialchars($canonicalUrl, ENT_QUOTES, 'UTF-8'); ?>">
    <meta property="og:title" content="<?= htmlspecialchars($ogTitle, ENT_QUOTES, 'UTF-8'); ?>">
    <meta property="og:description" content="<?= htmlspecialchars($ogDescription, ENT_QUOTES, 'UTF-8'); ?>">
    <meta property="og:image" content="<?= htmlspecialchars($ogImage, ENT_QUOTES, 'UTF-8'); ?>">
    <meta property="og:image:secure_url" content="<?= htmlspecialchars($ogImage, ENT_QUOTES, 'UTF-8'); ?>">
    <meta property="og:image:alt" content="<?= htmlspecialchars($ogImageAlt, ENT_QUOTES, 'UTF-8'); ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">

    <!-- Twitter -->
    <meta name="twitter:card" content="<?= htmlspecialchars($twitterCard, ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="twitter:title" content="<?= htmlspecialchars($ogTitle, ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($ogDescription, ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="twitter:image" content="<?= htmlspecialchars($ogImage, ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="twitter:image:alt" content="<?= htmlspecialchars($ogImageAlt, ENT_QUOTES, 'UTF-8'); ?>">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;500;600;700&family=Rajdhani:wght@500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="<?= htmlspecialchars(syzygy_encode_public_path('/assets/css/style.css'), ENT_QUOTES, 'UTF-8'); ?>?v=<?= $assetVersion; ?>">

    <!-- Favicon -->
    <link rel="shortcut icon" href="<?= htmlspecialchars(syzygy_encode_public_path('/favicon.ico'), ENT_QUOTES, 'UTF-8'); ?>?v=<?= $assetVersion; ?>">
    <link rel="icon" href="<?= htmlspecialchars(syzygy_encode_public_path('/favicon.ico'), ENT_QUOTES, 'UTF-8'); ?>?v=<?= $assetVersion; ?>" sizes="any">
    <link rel="icon" type="image/svg+xml" href="<?= htmlspecialchars(syzygy_encode_public_path('/assets/img/favicon/favicon.svg'), ENT_QUOTES, 'UTF-8'); ?>?v=<?= $assetVersion; ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= htmlspecialchars(syzygy_encode_public_path('/assets/img/favicon/favicon-32x32.png'), ENT_QUOTES, 'UTF-8'); ?>?v=<?= $assetVersion; ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= htmlspecialchars(syzygy_encode_public_path('/assets/img/favicon/favicon-16x16.png'), ENT_QUOTES, 'UTF-8'); ?>?v=<?= $assetVersion; ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= htmlspecialchars(syzygy_encode_public_path('/apple-touch-icon.png'), ENT_QUOTES, 'UTF-8'); ?>?v=<?= $assetVersion; ?>">
    <link rel="manifest" href="<?= htmlspecialchars(syzygy_encode_public_path('/assets/img/favicon/site.webmanifest'), ENT_QUOTES, 'UTF-8'); ?>?v=<?= $assetVersion; ?>">
    <meta name="msapplication-config" content="<?= htmlspecialchars(syzygy_encode_public_path('/browserconfig.xml'), ENT_QUOTES, 'UTF-8'); ?>?v=<?= $assetVersion; ?>">
    <meta name="msapplication-TileColor" content="#06101d">
</head>
