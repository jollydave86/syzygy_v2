<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || ((string) ($_SERVER['SERVER_PORT'] ?? '') === '443');

    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => $secure,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

$root = dirname(__DIR__);

require_once $root . '/includes/image-helpers.php';
require_once $root . '/includes/link-helpers.php';
require_once $root . '/includes/helpers.php';
require_once $root . '/includes/schema.php';

require_once $root . '/data/site.php';
require_once $root . '/data/releases.php';
require_once $root . '/data/tracks.php';
require_once $root . '/data/members.php';

$gallery = require $root . '/data/gallery.php';
$merch = require $root . '/data/merch.php';
$contact = require $root . '/data/contact.php';

require_once $root . '/data/blog.php';
usort($blogPosts, static function (array $a, array $b): int {
    return strcmp((string) ($b['date'] ?? ''), (string) ($a['date'] ?? ''));
});
require_once $root . '/data/lyrics.php';

$contactMailConfigPath = $root . '/config/contact-mail.php';
if (is_file($contactMailConfigPath)) {
    require_once $contactMailConfigPath;
}

require_once $root . '/includes/contact-form-handler.php';
