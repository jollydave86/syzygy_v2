<?php
$esc = static fn ($value) => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');

$trackTabs = (isset($trackTabs) && is_array($trackTabs) && !empty($trackTabs))
    ? $trackTabs
    : (require __DIR__ . '/../data/tracks.php');

$trackInitialCount = 5;

$makeDomSlug = static function ($value): string {
    $value = strtolower(trim((string) $value));
    $value = preg_replace('/[^a-z0-9\-]+/', '-', $value);
    $value = trim($value, '-');

    return $value !== '' ? $value : 'tracks';
};

$normalizedTabs = [];

foreach ((array) $trackTabs as $tabKey => $tab) {
    $label = trim((string) ($tab['label'] ?? ''));
    $tabTracks = array_values(array_filter((array) ($tab['tracks'] ?? []), static function ($track): bool {
        return is_array($track) && !empty($track['title']) && !empty($track['href']);
    }));

    if ($label === '' || empty($tabTracks)) {
        continue;
    }

    $normalizedTabs[] = [
        'key' => (string) $tabKey,
        'label' => $label,
        'tracks' => $tabTracks,
    ];
}

if (count($normalizedTabs) > 1) {
    shuffle($normalizedTabs);
}
?>

<?php if (!empty($normalizedTabs)): ?>
<section class="section tracks-section" id="music" aria-labelledby="music-title">
    <div class="container">
        <div class="section-heading">
            <p class="section__eyebrow">MUSIC</p>
            <h2 class="section__title" id="music-title">Featured Tracks</h2>
            <p class="section__lede">Explore featured songs and full release track lists across the SYZYGY.VOID catalog.</p>
        </div>

        <div class="track-tabs" data-track-tabs>
            <div class="track-tabs__nav" role="tablist" aria-label="Music releases">
                <?php foreach ($normalizedTabs as $index => $tab): ?>
                    <?php
                    $domSlug = $makeDomSlug($tab['key']);
                    $tabId = 'track-tab-' . $domSlug;
                    $panelId = 'track-panel-' . $domSlug;
                    $isActive = $index === 0;
                    ?>
                    <button
                        type="button"
                        id="<?= $esc($tabId); ?>"
                        class="track-tabs__button<?= $isActive ? ' is-active' : ''; ?>"
                        role="tab"
                        aria-selected="<?= $isActive ? 'true' : 'false'; ?>"
                        aria-controls="<?= $esc($panelId); ?>"
                    >
                        <?= $esc($tab['label']); ?>
                    </button>
                <?php endforeach; ?>
            </div>

            <div class="track-tabs__panels">
                <?php foreach ($normalizedTabs as $index => $tab): ?>
                    <?php
                    $domSlug = $makeDomSlug($tab['key']);
                    $tabId = 'track-tab-' . $domSlug;
                    $panelId = 'track-panel-' . $domSlug;
                    $isActive = $index === 0;
                    $releaseTracks = $tab['tracks'];
                    $needsToggle = count($releaseTracks) > $trackInitialCount;
                    ?>
                    <div
                        id="<?= $esc($panelId); ?>"
                        class="track-tabs__panel<?= $isActive ? ' is-active' : ''; ?>"
                        role="tabpanel"
                        aria-labelledby="<?= $esc($tabId); ?>"
                        <?= $isActive ? '' : 'hidden'; ?>
                    >
                        <?php if ($needsToggle): ?>
                            <div class="track-panel__controls">
                                <button
                                    type="button"
                                    class="track-panel__toggle"
                                    data-track-toggle
                                    aria-expanded="false"
                                >
                                    Expand All
                                </button>
                            </div>
                        <?php endif; ?>

                        <div class="track-list track-list--scrollable<?= $needsToggle ? ' is-collapsed' : ''; ?>" data-track-list>
                            <?php foreach ($releaseTracks as $trackIndex => $track): ?>
                                <?php
                                $title = (string) ($track['title'] ?? 'Untitled Track');
                                $href = (string) ($track['href'] ?? '#');
                                $meta = (string) ($track['meta'] ?? ('Song • ' . $tab['label']));
                                $trackUrl = function_exists('syzygy_utm_link')
                                    ? syzygy_utm_link($href, [
                                        'section' => 'music',
                                        'release' => $tab['key'],
                                        'type' => 'track',
                                        'slug' => $title,
                                        'label' => 'listen_now',
                                    ])
                                    : $href;
                                $indexLabel = str_pad((string) ($trackIndex + 1), 2, '0', STR_PAD_LEFT);
                                ?>
                                <article class="track-row">
                                    <div class="track-row__left">
                                        <span class="track-row__index"><?= $esc($indexLabel); ?></span>

                                        <a
                                            class="track-row__play"
                                            href="<?= $esc($trackUrl); ?>"
                                            aria-label="<?= $esc('Play ' . $title); ?>"
                                        >
                                            <span class="track-row__play-icon" aria-hidden="true"></span>
                                        </a>

                                        <div class="track-row__copy">
                                            <h3 class="track-row__title"><?= $esc($title); ?></h3>
                                            <p class="track-row__meta"><?= $esc($meta); ?></p>
                                        </div>
                                    </div>

                                    <div class="track-row__right">
                                        <a
                                            class="track-row__action"
                                            href="<?= $esc($trackUrl); ?>"
                                        >
                                            Listen Now
                                        </a>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
<?php else: ?>
<section class="section tracks-section" id="music" aria-labelledby="music-title">
    <div class="container">
        <div class="section-heading">
            <p class="section__eyebrow">MUSIC</p>
            <h2 class="section__title" id="music-title">Featured Tracks</h2>
        </div>

        <p class="tracks-empty">No tracks found yet.</p>
    </div>
</section>
<?php endif; ?>