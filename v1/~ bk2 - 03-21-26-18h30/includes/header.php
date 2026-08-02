<header class="site-header" data-site-header>
    <div class="container site-header__inner">
        <a href="/" class="site-logo" aria-label="<?= htmlspecialchars($site['name']); ?>">
            <?= htmlspecialchars($site['short_name']); ?>
        </a>

        <button
            class="menu-toggle"
            type="button"
            aria-expanded="false"
            aria-controls="primary-menu"
            aria-label="Toggle navigation"
        >
            <span class="menu-toggle__line"></span>
            <span class="menu-toggle__line"></span>
            <span class="menu-toggle__line"></span>
        </button>

        <nav class="site-nav" id="primary-menu" aria-label="Primary navigation">
            <ul class="site-nav__list">
                <?php foreach ($site['nav'] as $item): ?>
                    <li class="site-nav__item">
                        <a class="site-nav__link" href="<?= htmlspecialchars($item['href']); ?>">
                            <?= htmlspecialchars($item['label']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>
    </div>
</header>