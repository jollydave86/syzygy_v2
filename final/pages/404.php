<?php declare(strict_types=1); ?>
<section class="page-header">
    <div class="container">
        <p class="section__eyebrow">Error</p>
        <h1 class="section__title">404</h1>
        <p class="section__lede">This transmission does not exist in the public archive.</p>
        <div class="page-hero__actions">
            <a class="btn btn--primary" href="<?= syzygy_esc(syzygy_url('/')); ?>">Return Home</a>
            <a class="btn btn--secondary" href="<?= syzygy_esc(syzygy_url('/music')); ?>">Browse Music</a>
        </div>
    </div>
</section>
