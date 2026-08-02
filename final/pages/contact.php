<?php
declare(strict_types=1);
$state = $contactFormState ?? ['status' => null, 'message' => null, 'errors' => [], 'old' => []];
$old = $state['old'] ?? [];
$csrf = $_SESSION['contact_csrf'] ?? '';
$copy = is_array($contact) ? $contact : [];
?>
<section class="page-header">
    <div class="container">
        <p class="section__eyebrow">Signal</p>
        <h1 class="section__title"><?= syzygy_esc($copy['title'] ?? 'Contact'); ?></h1>
        <p class="section__lede"><?= syzygy_esc($copy['text'] ?? 'Send a transmission.'); ?></p>
    </div>
</section>

<section class="section contact-section" id="contact">
    <div class="container contact-layout">
        <div class="contact-info">
            <p><?= syzygy_esc($copy['text'] ?? 'Dark signal. Human emotion. Cinematic pressure.'); ?></p>
            <p class="section__eyebrow">Also</p>
            <a class="btn btn--secondary" href="<?= syzygy_esc(syzygy_url('/booking')); ?>">Booking</a>
            <br>
            <?php
            $platformLinksMap = null;
            $platformClass = 'platform-links';
            require dirname(__DIR__) . '/includes/platform-links.php';
            ?>
        </div>

        <div class="contact-form-wrap">
        <form class="contact-form contact-card" method="post" action="<?= syzygy_esc(syzygy_url('/contact')); ?>">
            <?php if (!empty($state['message'])): ?>
                <div class="contact-form__alert contact-form__alert--<?= syzygy_esc($state['status'] ?? 'info'); ?>">
                    <p><?= syzygy_esc($state['message']); ?></p>
                    <?php if (!empty($state['errors'])): ?>
                        <ul>
                            <?php foreach ($state['errors'] as $error): ?>
                                <li><?= syzygy_esc($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <input type="hidden" name="csrf_token" value="<?= syzygy_esc($csrf); ?>">
            <div class="contact-form__honeypot" aria-hidden="true">
                <label>Website <input type="text" name="website" value="" tabindex="-1" autocomplete="off"></label>
            </div>

            <div class="contact-form__grid">
                <label class="contact-form__field">
                    <span class="contact-form__label"><?= syzygy_esc($copy['form']['name_label'] ?? 'Name'); ?></span>
                    <input class="contact-form__input" type="text" name="name" required autocomplete="name" value="<?= syzygy_esc($old['name'] ?? ''); ?>">
                </label>
                <label class="contact-form__field">
                    <span class="contact-form__label"><?= syzygy_esc($copy['form']['email_label'] ?? 'Email'); ?></span>
                    <input class="contact-form__input" type="email" name="email" required autocomplete="email" value="<?= syzygy_esc($old['email'] ?? ''); ?>">
                </label>
                <label class="contact-form__field contact-form__field--full">
                    <span class="contact-form__label"><?= syzygy_esc($copy['form']['subject_label'] ?? 'Subject'); ?></span>
                    <input class="contact-form__input" type="text" name="subject" required value="<?= syzygy_esc($old['subject'] ?? ''); ?>">
                </label>
                <label class="contact-form__field contact-form__field--full">
                    <span class="contact-form__label"><?= syzygy_esc($copy['form']['message_label'] ?? 'Message'); ?></span>
                    <textarea class="contact-form__textarea" name="message" rows="6" required><?= syzygy_esc($old['message'] ?? ''); ?></textarea>
                </label>
            </div>

            <label class="contact-form__checkbox">
                <input class="contact-form__checkbox-input" type="checkbox" name="newsletter" value="1" <?= !empty($old['newsletter']) ? 'checked' : ''; ?>>
                <span class="contact-form__checkbox-text"><?= syzygy_esc($copy['form']['newsletter_label'] ?? 'Subscribe to signal updates.'); ?></span>
            </label>
            <p class="contact-form__note"><?= syzygy_esc($copy['form']['privacy_note'] ?? 'Your information is used only to respond to this message.'); ?></p>
            <div class="contact-form__actions">
                <button class="btn btn--primary" type="submit" name="contact_form_submit" value="1"><?= syzygy_esc($copy['form']['submit_label'] ?? 'Send Transmission'); ?></button>
            </div>
        </form>
        </div>
    </div>
</section>
