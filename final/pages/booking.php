<?php declare(strict_types=1); ?>
<section class="page-header">
    <div class="container">
        <p class="section__eyebrow">For Fun</p>
        <h1 class="section__title">Booking</h1>
        <p class="section__lede">SYZYGY.VOID does not offer services. This page exists as a playful transmission form only.</p>
    </div>
</section>

<section class="section">
    <div class="container contact-layout">
        <div class="contact-info">
            <div class="coming-soon-banner">
                <p class="section__eyebrow">Notice</p>
                <h2 class="section__title section__title--sm">Not a Real Booking Desk</h2>
                <p>There is no live show calendar, no paid production service, and no guarantee of reply for fictional booking requests. Use Contact for real messages.</p>
            </div>
            <a class="btn btn--secondary" href="<?= syzygy_esc(syzygy_url('/contact')); ?>">Go to Contact</a>
        </div>

        <form class="contact-form" method="post" action="<?= syzygy_esc(syzygy_url('/contact')); ?>">
            <input type="hidden" name="csrf_token" value="<?= syzygy_esc($_SESSION['contact_csrf'] ?? ''); ?>">
            <input type="hidden" name="subject" value="Booking (fun inquiry)">
            <input type="text" name="website" value="" tabindex="-1" autocomplete="off" class="hp-field" aria-hidden="true">

            <label>
                <span>Name / Alias</span>
                <input type="text" name="name" required>
            </label>
            <label>
                <span>Email</span>
                <input type="email" name="email" required>
            </label>
            <label>
                <span>Imaginary Event</span>
                <textarea name="message" rows="6" required placeholder="City, date fantasy, venue hallucination..."></textarea>
            </label>
            <button class="btn btn--primary" type="submit" name="contact_form_submit" value="1">Send Fun Request</button>
        </form>
    </div>
</section>
