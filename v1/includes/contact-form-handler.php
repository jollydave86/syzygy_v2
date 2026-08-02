<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$contactMailConfigPath = __DIR__ . '/contact-mail-config.php';
$contactMailConfig = file_exists($contactMailConfigPath)
    ? require $contactMailConfigPath
    : [
        'to_email' => '',
        'from_email' => '',
        'from_name' => 'Website',
        'subject_prefix' => '',
        'auto_reply_enabled' => false,
    ];

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

if (
    ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST'
    && isset($_POST['contact_form_submit'])
) {
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
        $safeSiteName = (string) ($contactMailConfig['from_name'] ?? 'Website');
        $toEmail = (string) $contactMailConfig['to_email'];
        $fromEmail = (string) $contactMailConfig['from_email'];
        $fromName = (string) ($contactMailConfig['from_name'] ?? 'Website');
        $subjectPrefix = (string) ($contactMailConfig['subject_prefix'] ?? '');

        $adminSubject = $subjectPrefix . $old['subject'];

        $adminBody = implode("\n", [
            'New contact form submission',
            '===========================',
            '',
            'Name: ' . $old['name'],
            'Email: ' . $old['email'],
            'Subject: ' . $old['subject'],
            'Newsletter: ' . ($old['newsletter'] ? 'Yes' : 'No'),
            '',
            'Message:',
            $old['message'],
            '',
            'IP: ' . (string) ($_SERVER['REMOTE_ADDR'] ?? 'unknown'),
            'User Agent: ' . (string) ($_SERVER['HTTP_USER_AGENT'] ?? 'unknown'),
        ]);

        $adminHeaders = [
            'MIME-Version: 1.0',
            'Content-Type: text/plain; charset=UTF-8',
            'From: ' . $fromName . ' <' . $fromEmail . '>',
            'Reply-To: ' . $old['name'] . ' <' . $old['email'] . '>',
            'X-Mailer: PHP/' . PHP_VERSION,
        ];

        $adminSent = mail(
            $toEmail,
            $adminSubject,
            $adminBody,
            implode("\r\n", $adminHeaders)
        );

        $autoReplySent = true;

        if ($adminSent && !empty($contactMailConfig['auto_reply_enabled'])) {
            $replySubject = 'We received your message';
            $replyBody = implode("\n", [
                'Hi ' . $old['name'] . ',',
                '',
                'Thanks for reaching out to ' . $safeSiteName . '.',
                'We received your message and will get back to you as soon as possible.',
                '',
                'Your subject: ' . $old['subject'],
                '',
                '— ' . $safeSiteName,
            ]);

            $replyHeaders = [
                'MIME-Version: 1.0',
                'Content-Type: text/plain; charset=UTF-8',
                'From: ' . $fromName . ' <' . $fromEmail . '>',
                'Reply-To: ' . $fromName . ' <' . $fromEmail . '>',
                'X-Mailer: PHP/' . PHP_VERSION,
            ];

            $autoReplySent = mail(
                $old['email'],
                $replySubject,
                $replyBody,
                implode("\r\n", $replyHeaders)
            );
        }

        if ($adminSent) {
            $_SESSION['contact_form_state'] = [
                'status' => 'success',
                'message' => $autoReplySent
                    ? 'Message sent successfully. Check your inbox for a confirmation email.'
                    : 'Message sent successfully.',
                'errors' => [],
                'old' => [
                    'name' => '',
                    'email' => '',
                    'subject' => '',
                    'message' => '',
                    'newsletter' => false,
                ],
            ];

            $_SESSION['contact_csrf'] = bin2hex(random_bytes(32));
        } else {
            $_SESSION['contact_form_state'] = [
                'status' => 'error',
                'message' => 'Your message could not be sent right now. Please try again.',
                'errors' => [],
                'old' => $old,
            ];
        }
    } else {
        $_SESSION['contact_form_state'] = [
            'status' => 'error',
            'message' => 'Please fix the highlighted issues and try again.',
            'errors' => $errors,
            'old' => $old,
        ];
    }

    $requestUri = (string) ($_SERVER['REQUEST_URI'] ?? '/');
    $redirectTarget = preg_replace('/#.*$/', '', $requestUri);
    $redirectTarget .= '#contact';

    header('Location: ' . $redirectTarget);
    exit;
}