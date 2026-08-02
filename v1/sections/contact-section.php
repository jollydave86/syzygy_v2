<?php
$esc = static fn ($value) => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');

$contact = $contact ?? require __DIR__ . '/../data/contact.php';

$contactFormState = $contactFormState ?? [
    'status' => null,
    'message' => '',
    'errors' => [],
    'old' => [
        'name' => '',
        'email' => '',
        'subject' => '',
        'message' => '',
        'newsletter' => false,
    ],
];

/**
 * Supports both:
 * - keyed field errors: ['name' => 'Please enter your name']
 * - global error lists: ['Please enter your name', 'Please enter a valid email']
 */
$getFieldError = static function (array $errors, string $field): ?string {
    return isset($errors[$field]) && is_string($errors[$field]) ? $errors[$field] : null;
};

$getGlobalErrors = static function (array $errors): array {
    $global = [];

    foreach ($errors as $key => $value) {
        if (is_int($key) && is_string($value) && $value !== '') {
            $global[] = $value;
        }
    }

    return $global;
};

$fieldErrorName = $getFieldError($contactFormState['errors'] ?? [], 'name');
$fieldErrorEmail = $getFieldError($contactFormState['errors'] ?? [], 'email');
$fieldErrorSubject = $getFieldError($contactFormState['errors'] ?? [], 'subject');
$fieldErrorMessage = $getFieldError($contactFormState['errors'] ?? [], 'message');
$globalErrors = $getGlobalErrors($contactFormState['errors'] ?? []);

$csrfToken = $contactCsrf
    ?? ($_SESSION['contact_csrf'] ?? '');

$old = $contactFormState['old'] ?? [];
?>

<section class="section contact-section" id="contact" aria-labelledby="contact-title">
    <div class="container">
        <div class="section-heading">
            <?php if (!empty($contact['eyebrow'])): ?>
                <p class="section__eyebrow"><?= $esc($contact['eyebrow']); ?></p>
            <?php endif; ?>

            <?php if (!empty($contact['title'])): ?>
                <h2 class="section__title" id="contact-title"><?= $esc($contact['title']); ?></h2>
            <?php endif; ?>

            <?php if (!empty($contact['text'])): ?>
                <p class="section-heading__text"><?= $esc($contact['text']); ?></p>
            <?php endif; ?>
        </div>

        <div class="contact-form-wrap">
            <div class="contact-card contact-card--form">
                <?php if (!empty($contactFormState['message']) || !empty($globalErrors)): ?>
                    <div class="contact-form__alert contact-form__alert--<?= $esc($contactFormState['status'] ?? 'error'); ?>">
                        <?php if (!empty($contactFormState['message'])): ?>
                            <p><?= $esc($contactFormState['message']); ?></p>
                        <?php endif; ?>

                        <?php if (!empty($globalErrors)): ?>
                            <ul>
                                <?php foreach ($globalErrors as $error): ?>
                                    <li><?= $esc($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <form class="contact-form" method="post" action="" novalidate>
                    <input type="hidden" name="contact_form_submit" value="1">
                    <input type="hidden" name="csrf_token" value="<?= $esc($csrfToken); ?>">

                    <div class="contact-form__honeypot" hidden aria-hidden="true">
                        <label for="website-field">
                            Website
                        </label>
                        <input
                            type="text"
                            name="website"
                            id="website-field"
                            tabindex="-1"
                            autocomplete="off"
                        >
                    </div>

                    <div class="contact-form__grid">
                        <label class="contact-form__field">
                            <span class="contact-form__label"><?= $esc($contact['form']['name_label'] ?? 'Name'); ?></span>
                            <input
                                class="contact-form__input<?= $fieldErrorName ? ' is-invalid' : ''; ?>"
                                type="text"
                                name="name"
                                autocomplete="name"
                                required
                                value="<?= $esc($old['name'] ?? ''); ?>"
                            >
                            <?php if ($fieldErrorName): ?>
                                <span class="contact-form__error"><?= $esc($fieldErrorName); ?></span>
                            <?php endif; ?>
                        </label>

                        <label class="contact-form__field">
                            <span class="contact-form__label"><?= $esc($contact['form']['email_label'] ?? 'Email'); ?></span>
                            <input
                                class="contact-form__input<?= $fieldErrorEmail ? ' is-invalid' : ''; ?>"
                                type="email"
                                name="email"
                                autocomplete="email"
                                required
                                value="<?= $esc($old['email'] ?? ''); ?>"
                            >
                            <?php if ($fieldErrorEmail): ?>
                                <span class="contact-form__error"><?= $esc($fieldErrorEmail); ?></span>
                            <?php endif; ?>
                        </label>

                        <label class="contact-form__field contact-form__field--full">
                            <span class="contact-form__label"><?= $esc($contact['form']['subject_label'] ?? 'Subject'); ?></span>
                            <input
                                class="contact-form__input<?= $fieldErrorSubject ? ' is-invalid' : ''; ?>"
                                type="text"
                                name="subject"
                                required
                                value="<?= $esc($old['subject'] ?? ''); ?>"
                            >
                            <?php if ($fieldErrorSubject): ?>
                                <span class="contact-form__error"><?= $esc($fieldErrorSubject); ?></span>
                            <?php endif; ?>
                        </label>

                        <label class="contact-form__field contact-form__field--full">
                            <span class="contact-form__label"><?= $esc($contact['form']['message_label'] ?? 'Message'); ?></span>
                            <textarea
                                class="contact-form__textarea<?= $fieldErrorMessage ? ' is-invalid' : ''; ?>"
                                name="message"
                                rows="6"
                                required
                            ><?= $esc($old['message'] ?? ''); ?></textarea>
                            <?php if ($fieldErrorMessage): ?>
                                <span class="contact-form__error"><?= $esc($fieldErrorMessage); ?></span>
                            <?php endif; ?>
                        </label>
                    </div>

                    <label class="contact-form__checkbox">
                        <input
                            class="contact-form__checkbox-input"
                            type="checkbox"
                            name="newsletter"
                            value="1"
                            <?= !empty($old['newsletter']) ? 'checked' : ''; ?>
                        >
                        <span class="contact-form__checkbox-text">
                            <?= $esc($contact['form']['newsletter_label'] ?? 'Subscribe to updates'); ?>
                        </span>
                    </label>

                    <?php if (!empty($contact['form']['privacy_note'])): ?>
                        <p class="contact-form__note">
                            <?= $esc($contact['form']['privacy_note']); ?>
                        </p>
                    <?php endif; ?>

                    <div class="contact-form__actions">
                        <button class="btn btn--primary" type="submit">
                            <?= $esc($contact['form']['submit_label'] ?? 'Send Message'); ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>