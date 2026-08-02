<?php

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    return;
}

if (($_POST['form_id'] ?? '') !== 'contact') {
    return;
}

$redirectPath = strtok($_SERVER['REQUEST_URI'], '?');
$redirectPath = $redirectPath ?: '/';
$redirectUrl = $redirectPath . '#contact';

$flash = [
    'status' => 'error',
    'message' => 'Something went wrong. Please try again.',
    'errors' => [],
    'old' => [
        'name' => trim((string) ($_POST['name'] ?? '')),
        'email' => trim((string) ($_POST['email'] ?? '')),
        'subject' => trim((string) ($_POST['subject'] ?? '')),
        'message' => trim((string) ($_POST['message'] ?? '')),
        'newsletter' => !empty($_POST['newsletter']) ? '1' : '0',
    ],
];

$finish = static function (array $payload) use ($redirectUrl): void {
    $_SESSION['contact_form_flash'] = $payload;
    header('Location: ' . $redirectUrl);
    exit;
};

if (!hash_equals($_SESSION['contact_csrf'] ?? '', (string) ($_POST['contact_csrf'] ?? ''))) {
    $flash['message'] = 'Your session expired. Please try again.';
    $finish($flash);
}

if (!empty($_POST['website'])) {
    $flash['status'] = 'success';
    $flash['message'] = 'Message sent successfully.';
    $flash['errors'] = [];
    $flash['old'] = [
        'name' => '',
        'email' => '',
        'subject' => '',
        'message' => '',
        'newsletter' => '0',
    ];
    $finish($flash);
}

$name = trim((string) ($_POST['name'] ?? ''));
$email = trim((string) ($_POST['email'] ?? ''));
$subject = trim((string) ($_POST['subject'] ?? ''));
$message = trim((string) ($_POST['message'] ?? ''));
$newsletter = !empty($_POST['newsletter']);

if ($name === '') {
    $flash['errors']['name'] = 'Please enter your name.';
}

if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $flash['errors']['email'] = 'Please enter a valid email address.';
}

if ($message === '') {
    $flash['errors']['message'] = 'Please enter a message.';
}

if (mb_strlen($name) > 120) {
    $flash['errors']['name'] = 'Name is too long.';
}

if (mb_strlen($email) > 190) {
    $flash['errors']['email'] = 'Email is too long.';
}

if (mb_strlen($subject) > 180) {
    $flash['errors']['subject'] = 'Subject is too long.';
}

if (mb_strlen($message) > 5000) {
    $flash['errors']['message'] = 'Message is too long.';
}

if ($flash['errors'] !== []) {
    $flash['message'] = 'Please fix the highlighted fields and try again.';
    $finish($flash);
}

$autoloadPath = __DIR__ . '/../vendor/autoload.php';
$configPath = __DIR__ . '/../config/contact-mail.php';

if (!is_file($autoloadPath)) {
    $flash['message'] = 'Mailer is not installed yet. Run composer install.';
    $finish($flash);
}

if (!is_file($configPath)) {
    $flash['message'] = 'Mail config file is missing.';
    $finish($flash);
}

require_once $autoloadPath;
$mailConfig = require $configPath;

try {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = (string) ($mailConfig['smtp']['host'] ?? '');
    $mail->Port = (int) ($mailConfig['smtp']['port'] ?? 587);
    $mail->SMTPAuth = true;
    $mail->Username = (string) ($mailConfig['smtp']['username'] ?? '');
    $mail->Password = (string) ($mailConfig['smtp']['password'] ?? '');
    $mail->SMTPSecure = (string) ($mailConfig['smtp']['secure'] ?? 'tls');

    $mail->setFrom(
        (string) ($mailConfig['from_email'] ?? ''),
        (string) ($mailConfig['from_name'] ?? 'Website')
    );

    $mail->addAddress(
        (string) ($mailConfig['to_email'] ?? ''),
        (string) ($mailConfig['to_name'] ?? '')
    );

    $mail->addReplyTo($email, $name);
    $mail->Subject = '[SYZYGY.VOID Contact] ' . ($subject !== '' ? $subject : 'New message');

    $safeName = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
    $safeEmail = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
    $safeSubject = htmlspecialchars($subject !== '' ? $subject : '(No subject)', ENT_QUOTES, 'UTF-8');
    $safeMessage = nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8'));
    $safeNewsletter = $newsletter ? 'Yes' : 'No';

    $mail->isHTML(true);
    $mail->Body = <<<HTML
<h2>New contact form submission</h2>
<p><strong>Name:</strong> {$safeName}</p>
<p><strong>Email:</strong> {$safeEmail}</p>
<p><strong>Subject:</strong> {$safeSubject}</p>
<p><strong>Newsletter:</strong> {$safeNewsletter}</p>
<hr>
<p><strong>Message:</strong></p>
<p>{$safeMessage}</p>
HTML;

    $mail->AltBody =
        "New contact form submission\n\n" .
        "Name: {$name}\n" .
        "Email: {$email}\n" .
        "Subject: " . ($subject !== '' ? $subject : '(No subject)') . "\n" .
        "Newsletter: " . ($newsletter ? 'Yes' : 'No') . "\n\n" .
        "Message:\n{$message}\n";

    $mail->send();

    if (!empty($mailConfig['send_auto_reply'])) {
        $reply = new PHPMailer(true);
        $reply->isSMTP();
        $reply->Host = (string) ($mailConfig['smtp']['host'] ?? '');
        $reply->Port = (int) ($mailConfig['smtp']['port'] ?? 587);
        $reply->SMTPAuth = true;
        $reply->Username = (string) ($mailConfig['smtp']['username'] ?? '');
        $reply->Password = (string) ($mailConfig['smtp']['password'] ?? '');
        $reply->SMTPSecure = (string) ($mailConfig['smtp']['secure'] ?? 'tls');

        $reply->setFrom(
            (string) ($mailConfig['from_email'] ?? ''),
            (string) ($mailConfig['from_name'] ?? 'SYZYGY.VOID')
        );

        $reply->addAddress($email, $name);
        $reply->Subject = 'We received your message';

        $reply->isHTML(true);
        $reply->Body = <<<HTML
<p>Hi {$safeName},</p>
<p>Thanks for reaching out to SYZYGY.VOID. Your message has been received.</p>
<p>We will get back to you as soon as possible.</p>
<p>— SYZYGY.VOID</p>
HTML;

        $reply->AltBody =
            "Hi {$name},\n\n" .
            "Thanks for reaching out to SYZYGY.VOID. Your message has been received.\n\n" .
            "We will get back to you as soon as possible.\n\n" .
            "— SYZYGY.VOID";

        $reply->send();
    }

    $_SESSION['contact_csrf'] = bin2hex(random_bytes(32));

    $finish([
        'status' => 'success',
        'message' => 'Message sent successfully.',
        'errors' => [],
        'old' => [
            'name' => '',
            'email' => '',
            'subject' => '',
            'message' => '',
            'newsletter' => '0',
        ],
    ]);
} catch (Exception $e) {
    $flash['message'] = 'Email could not be sent. Please try again.';
    $finish($flash);
}