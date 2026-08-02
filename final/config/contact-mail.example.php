<?php

/**
 * Example mail settings. Copy to contact-mail.php on the server and fill values.
 *
 * The site currently sends via PHP mail(). SMTP keys are unused until an SMTP
 * transport is wired in includes/contact-form-handler.php.
 */

return [
    'to_email' => 'info@syzygyvoid.ca',
    'to_name' => 'SYZYGY.VOID',

    'from_email' => 'info@syzygyvoid.ca',
    'from_name' => 'SYZYGY.VOID Website',

    'smtp' => [
        'host' => 'smtp.your-provider.com',
        'port' => 587,
        'username' => 'info@syzygyvoid.ca',
        'password' => 'YOUR_SMTP_PASSWORD',
        'secure' => 'tls', // tls or ssl
    ],

    'send_auto_reply' => true,
];
