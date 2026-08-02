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

$contactMailConfig = [
    'to_email' => '',
    'from_email' => '',
    'from_name' => 'SYZYGY.VOID Website',
    'subject_prefix' => '[SYZYGY.VOID] ',
    'auto_reply_enabled' => false,
];

$configPath = dirname(__DIR__) . '/config/contact-mail.php';
if (is_file($configPath)) {
    $loaded = require $configPath;
    if (is_array($loaded)) {
        $contactMailConfig['to_email'] = (string) ($loaded['to_email'] ?? '');
        $contactMailConfig['from_email'] = (string) ($loaded['from_email'] ?? '');
        $contactMailConfig['from_name'] = (string) ($loaded['from_name'] ?? $contactMailConfig['from_name']);
        $contactMailConfig['auto_reply_enabled'] = !empty($loaded['send_auto_reply']);
    }
}

$contactFormState = [
    'status' => null,
    'message' => null,
    'errors' => [],
    'old' => [
        'name' => '',
        'email' => '',
        'subject' => '',
        'message' => '',
        'newsletter' => false,
    ],
];

if (empty($_SESSION['contact_csrf'])) {
    $_SESSION['contact_csrf'] = bin2hex(random_bytes(32));
}

if (!empty($_SESSION['contact_form_state']) && is_array($_SESSION['contact_form_state'])) {
    $contactFormState = array_replace_recursive($contactFormState, $_SESSION['contact_form_state']);
    unset($_SESSION['contact_form_state']);
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && isset($_POST['contact_form_submit'])) {
    $old = [
        'name' => trim((string) ($_POST['name'] ?? '')),
        'email' => trim((string) ($_POST['email'] ?? '')),
        'subject' => trim((string) ($_POST['subject'] ?? '')),
        'message' => trim((string) ($_POST['message'] ?? '')),
        'newsletter' => !empty($_POST['newsletter']),
    ];

    $errors = [];
    $csrfToken = (string) ($_POST['csrf_token'] ?? '');
    $honeypot = trim((string) ($_POST['website'] ?? ''));

    if ($csrfToken === '' || !hash_equals((string) $_SESSION['contact_csrf'], $csrfToken)) {
        $errors[] = 'Security check failed. Please refresh the page and try again.';
    }
    if ($honeypot !== '') {
        $errors[] = 'Spam protection triggered.';
    }
    if ($old['name'] === '') {
        $errors[] = 'Please enter your name.';
    }
    if ($old['email'] === '' || !filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }
    if ($old['subject'] === '') {
        $errors[] = 'Please enter a subject.';
    }
    if ($old['message'] === '') {
        $errors[] = 'Please enter a message.';
    }
    if ($contactMailConfig['to_email'] === '' || $contactMailConfig['from_email'] === '') {
        $errors[] = 'Mail configuration is incomplete.';
    }

    if ($errors === []) {
        $adminSubject = $contactMailConfig['subject_prefix'] . $old['subject'];
        $adminBody = implode("\n", [
            'New contact form submission',
            'Name: ' . $old['name'],
            'Email: ' . $old['email'],
            'Subject: ' . $old['subject'],
            'Newsletter: ' . ($old['newsletter'] ? 'Yes' : 'No'),
            '',
            $old['message'],
        ]);
        $adminHeaders = implode("\r\n", [
            'MIME-Version: 1.0',
            'Content-Type: text/plain; charset=UTF-8',
            'From: ' . $contactMailConfig['from_name'] . ' <' . $contactMailConfig['from_email'] . '>',
            'Reply-To: ' . $old['name'] . ' <' . $old['email'] . '>',
        ]);

        $adminSent = @mail($contactMailConfig['to_email'], $adminSubject, $adminBody, $adminHeaders);

        if ($adminSent && $contactMailConfig['auto_reply_enabled']) {
            @mail(
                $old['email'],
                'We received your message',
                "Hi {$old['name']},\n\nThanks for reaching out to SYZYGY.VOID.\n\n— SYZYGY.VOID",
                implode("\r\n", [
                    'MIME-Version: 1.0',
                    'Content-Type: text/plain; charset=UTF-8',
                    'From: ' . $contactMailConfig['from_name'] . ' <' . $contactMailConfig['from_email'] . '>',
                ])
            );
        }

        $_SESSION['contact_form_state'] = [
            'status' => $adminSent ? 'success' : 'error',
            'message' => $adminSent
                ? 'Message sent successfully.'
                : 'Your message could not be sent right now. Please try again.',
            'errors' => [],
            'old' => $adminSent ? $contactFormState['old'] : $old,
        ];

        if ($adminSent) {
            $_SESSION['contact_csrf'] = bin2hex(random_bytes(32));
        }
    } else {
        $_SESSION['contact_form_state'] = [
            'status' => 'error',
            'message' => 'Please fix the highlighted issues and try again.',
            'errors' => $errors,
            'old' => $old,
        ];
    }

    header('Location: ' . syzygy_url('/contact'));
    exit;
}
