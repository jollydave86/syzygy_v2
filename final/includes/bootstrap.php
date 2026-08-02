<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$root = dirname(__DIR__);

require_once $root . '/includes/image-helpers.php';
require_once $root . '/includes/link-helpers.php';
require_once $root . '/includes/helpers.php';

require_once $root . '/data/site.php';
require_once $root . '/data/releases.php';
require_once $root . '/data/tracks.php';
require_once $root . '/data/members.php';

$gallery = require $root . '/data/gallery.php';
$merch = require $root . '/data/merch.php';
$contact = require $root . '/data/contact.php';

require_once $root . '/data/blog.php';
require_once $root . '/data/lyrics.php';

$contactMailConfigPath = $root . '/config/contact-mail.php';
if (is_file($contactMailConfigPath)) {
    require_once $contactMailConfigPath;
}

require_once $root . '/includes/contact-form-handler.php';
