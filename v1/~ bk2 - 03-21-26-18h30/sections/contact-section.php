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
        'newsletter' => '0',
    ],
];
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
                <?php if (!empty($contactFormState['message'])): ?>
                    <div class="contact-form__alert contact-form__alert--<?= $esc($contactFormState['status'] ?? 'error'); ?>">
                        <?= $esc($contactFormState['message']); ?>
                    </div>
                <?php endif; ?>

                <form class="contact-form" method="post" action="#contact" novalidate>
                    <input type="hidden" name="form_id" value="contact">
                    <input type="hidden" name="contact_csrf" value="<?= $esc($contactCsrf ?? ''); ?>">

                    <div class="contact-form__honeypot" aria-hidden="true">
                        <label>
                            Website
                            <input type="text" name="website" tabindex="-1" autocomplete="off">
                        </label>
                    </div>

                    <div class="contact-form__grid">
                        <label class="contact-form__field">
                            <span class="contact-form__label"><?= $esc($contact['form']['name_label']); ?></span>
                            <input
                                class="contact-form__input<?= isset($contactFormState['errors']['name']) ? ' is-invalid' : ''; ?>"
                                type="text"
                                name="name"
                                autocomplete="name"
                                required
                                value="<?= $esc($contactFormState['old']['name'] ?? ''); ?>"
                            >
                            <?php if (!empty($contactFormState['errors']['name'])): ?>
                                <span class="contact-form__error"><?= $esc($contactFormState['errors']['name']); ?></span>
                            <?php endif; ?>
                        </label>

                        <label class="contact-form__field">
                            <span class="contact-form__label"><?= $esc($contact['form']['email_label']); ?></span>
                            <input
                                class="contact-form__input<?= isset($contactFormState['errors']['email']) ? ' is-invalid' : ''; ?>"
                                type="email"
                                name="email"
                                autocomplete="email"
                                required
                                value="<?= $esc($contactFormState['old']['email'] ?? ''); ?>"
                            >
                            <?php if (!empty($contactFormState['errors']['email'])): ?>
                                <span class="contact-form__error"><?= $esc($contactFormState['errors']['email']); ?></span>
                            <?php endif; ?>
                        </label>

                        <label class="contact-form__field contact-form__field--full">
                            <span class="contact-form__label"><?= $esc($contact['form']['subject_label']); ?></span>
                            <input
                                class="contact-form__input<?= isset($contactFormState['errors']['subject']) ? ' is-invalid' : ''; ?>"
                                type="text"
                                name="subject"
                                value="<?= $esc($contactFormState['old']['subject'] ?? ''); ?>"
                            >
                            <?php if (!empty($contactFormState['errors']['subject'])): ?>
                                <span class="contact-form__error"><?= $esc($contactFormState['errors']['subject']); ?></span>
                            <?php endif; ?>
                        </label>

                        <label class="contact-form__field contact-form__field--full">
                            <span class="contact-form__label"><?= $esc($contact['form']['message_label']); ?></span>
                            <textarea
                                class="contact-form__textarea<?= isset($contactFormState['errors']['message']) ? ' is-invalid' : ''; ?>"
                                name="message"
                                rows="6"
                                required
                            ><?= $esc($contactFormState['old']['message'] ?? ''); ?></textarea>
                            <?php if (!empty($contactFormState['errors']['message'])): ?>
                                <span class="contact-form__error"><?= $esc($contactFormState['errors']['message']); ?></span>
                            <?php endif; ?>
                        </label>
                    </div>

                    <label class="contact-form__checkbox">
                        <input
                            class="contact-form__checkbox-input"
                            type="checkbox"
                            name="newsletter"
                            value="1"
                            <?= !empty($contactFormState['old']['newsletter']) ? 'checked' : ''; ?>
                        >
                        <span class="contact-form__checkbox-text">
                            <?= $esc($contact['form']['newsletter_label']); ?>
                        </span>
                    </label>

                    <p class="contact-form__note">
                        <?= $esc($contact['form']['privacy_note']); ?>
                    </p>

                    <div class="contact-form__actions">
                        <button class="btn btn--primary" type="submit">
                            <?= $esc($contact['form']['submit_label']); ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>