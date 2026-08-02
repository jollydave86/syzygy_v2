<?php
$esc = static fn ($value) => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');

if (!function_exists('syzygy_first_existing_public_path')) {
    function syzygy_first_existing_public_path(array $paths): string
    {
        foreach ($paths as $path) {
            $path = trim((string) $path);

            if ($path !== '' && syzygy_public_path_exists($path)) {
                return $path;
            }
        }

        return '';
    }
}

// Group shot replacement:
// - Recommended: 1600x900
// - Also acceptable: 1920x1080
// - Aspect ratio: 16:9
// - Drop one of these into assets/img/solo-signals/: group-shot.webp, group-shot.jpg, group-shot.png,
//   group-shot-placeholder.webp, group-shot-placeholder.jpg, group-shot-placeholder.png, or group-shot-placeholder.svg.
//
// Member portrait replacement:
// - Recommended source size: 500x500
// - Display size: 250x250
// - Aspect ratio: 1:1 square
// - Current paths use the local WEBP files in assets/img/solo-signals/.
//
/*
 * SUNO LINK WIRING:
 * Paste each member's Suno EP/album URL into the sunoUrl field.
 * Leave sunoUrl as an empty string to show Transmission Pending.
 */
$soloLabels = [
    'readMoreLabel' => 'Read More',
    'listenLabel' => 'Listen More',
    'pendingLabel' => 'Transmission Pending',
    'closeLabel' => 'Close Transmission',
];

$soloSignals = [
    [
        'slug' => 'nova-vale',
        'status' => 'Active Era',
        'teaser' => 'Commanding, bright, dangerous, and impossible to bury - the main voltage of SYZYGY.VOID.',
        'initials' => 'NV',
        'name' => 'Nova Vale',
        'role' => 'Lead Frontwoman',
        'release' => 'WHITE VOLTAGE',
        'chapterBio' => 'Nova Vale turns the band\'s industrial heart into a full frontal pop-noir detonation. WHITE VOLTAGE is sharp vocals, neon pressure, emotional control, and beautiful damage pushed to maximum brightness.',
        'loreFile' => 'Nova Vale is the front-facing voltage of SYZYGY.VOID - the voice that turns pressure into command. Her solo signal is built like a spotlight cutting through smoke: bright, controlled, dangerous, and impossible to ignore.',
        'soundTags' => ['industrial pop', 'darkwave', 'neon rock', 'commanding female vocal', 'cinematic pressure'],
        'visualTags' => ['white voltage', 'black chrome', 'solar gold', 'stage halo', 'frontwoman energy'],
        'image' => '/assets/img/solo-signals/nova-vale.webp',
        'image_alt' => 'Nova Vale solo signal portrait',
        'sunoUrl' => '',
    ],
    [
        'slug' => 'vanta-rey',
        'status' => 'Transmission Pending',
        'teaser' => 'The shadow voice inside the machine - colder, slower, and more dangerous in silence.',
        'initials' => 'VR',
        'name' => 'Vanta Rey',
        'role' => 'Co-Frontwoman',
        'release' => 'THE LOW LIGHT',
        'chapterBio' => 'Vanta Rey moves through darkwave, trip-hop, industrial ballads, and haunted club electronics. Her solo signal is velvet pressure: restrained, elegant, and quietly destructive.',
        'loreFile' => 'Vanta Rey is the low light in the system: the voice that does not chase impact because it already owns the room. Her transmission is colder, slower, and more intimate - a shadow moving through velvet electronics and haunted bass.',
        'soundTags' => ['darkwave', 'trip-hop', 'industrial ballad', 'haunted club', 'low female vocal'],
        'visualTags' => ['velvet shadow', 'cold neon', 'black glass', 'low light', 'ghost glamour'],
        'image' => '/assets/img/solo-signals/vanta-rey.webp',
        'image_alt' => 'Vanta Rey solo signal portrait',
        'sunoUrl' => '',
    ],
    [
        'slug' => 'lucien-cross',
        'status' => 'Transmission Pending',
        'teaser' => 'Guitars, machines, signal chains, and controlled collapse under the floor.',
        'initials' => 'LC',
        'name' => 'Lucien Cross',
        'role' => 'Multi-Instrumentalist',
        'release' => 'ENGINE ROOM',
        'chapterBio' => 'Lucien Cross pulls SYZYGY.VOID into a colder mechanical space. ENGINE ROOM is distorted rhythm guitar, bass pressure, synthetic architecture, and songs that feel built as much as written.',
        'loreFile' => 'Lucien Cross is the engine under the floorboards. His transmission is mechanical, physical, and exact - guitar pressure, bass movement, signal chains, and songs that feel engineered from damaged metal.',
        'soundTags' => ['industrial rock', 'distorted guitar', 'mechanical synth', 'bass pressure', 'controlled collapse'],
        'visualTags' => ['engine room', 'cable light', 'machine architecture', 'steel frame', 'black circuitry'],
        'image' => '/assets/img/solo-signals/lucien-cross.webp',
        'image_alt' => 'Lucien Cross solo signal portrait',
        'sunoUrl' => '',
    ],
    [
        'slug' => 'lyra-static',
        'status' => 'Transmission Pending',
        'teaser' => 'The human pulse fighting the sequencer from inside the system.',
        'initials' => 'LS',
        'name' => 'Lyra Static',
        'role' => 'Live Drummer',
        'release' => 'BODY CLOCK',
        'chapterBio' => 'Lyra Static brings body, timing, and physical tension into the SYZYGY.VOID machine. BODY CLOCK is built on dry snares, floor toms, abrupt stops, human error, and live pressure.',
        'loreFile' => 'Lyra Static is the proof that the machine still has a body. Her signal lives in timing, impact, and human error - the moment a live drummer fights the sequencer and turns the mistake into the pulse.',
        'soundTags' => ['live drums', 'dry snare', 'floor toms', 'post-punk rhythm', 'human timing'],
        'visualTags' => ['drum light', 'motion blur', 'concrete room', 'red pulse', 'live error'],
        'image' => '/assets/img/solo-signals/lyra-static.webp',
        'image_alt' => 'Lyra Static solo signal portrait',
        'sunoUrl' => '',
    ],
    [
        'slug' => 'kade-null',
        'status' => 'Transmission Pending',
        'teaser' => 'The blueprint ghost behind the signal.',
        'initials' => 'KN',
        'name' => 'Kade Null',
        'role' => 'Producer / Architect',
        'release' => 'NULL ARCHITECTURE',
        'chapterBio' => 'Kade Null strips the project down to systems, rooms, damaged synth design, hidden rhythms, and instrumental pressure. NULL ARCHITECTURE sounds like the control room thinking for itself.',
        'loreFile' => 'Kade Null is the blueprint ghost behind the signal. His transmission removes the spotlight and leaves the system exposed: rooms, patterns, damaged synth design, hidden rhythms, and control panels thinking in the dark.',
        'soundTags' => ['instrumental industrial', 'glitch electronics', 'modular synth', 'broken beats', 'cinematic ambient'],
        'visualTags' => ['control room', 'blueprint ghost', 'dark interface', 'signal map', 'cold architecture'],
        'image' => '/assets/img/solo-signals/kade-null.webp',
        'image_alt' => 'Kade Null solo signal portrait',
        'sunoUrl' => '',
    ],
    [
        'slug' => 'ash-vex',
        'status' => 'Legacy Signal',
        'teaser' => 'The abrasive emotional undercurrent before the project became a universe.',
        'initials' => 'AV',
        'name' => 'Ash Vex',
        'role' => 'Legacy Member',
        'release' => 'THE SHAPE I LEFT',
        'chapterBio' => 'Ash Vex is smaller, meaner, and more personal - distorted rhythm guitars, ugly bass, damaged machines, low spoken vocals, and the sound of one person refusing to decorate the wound.',
        'loreFile' => 'Ash Vex is the wound left in the wall before SYZYGY.VOID became a universe. His signal is stripped, ugly, personal, and close to the floor - distorted rhythm guitars, damaged machines, and no decoration.',
        'soundTags' => ['industrial desert rock', 'damaged trip-hop', 'nocturnal post-punk', 'ugly bass', 'low spoken vocal'],
        'visualTags' => ['concrete wall', 'black jacket', 'old wound', 'dead channel', 'legacy signal'],
        'image' => '/assets/img/solo-signals/ash-vex.webp',
        'image_alt' => 'Ash Vex solo signal portrait',
        'sunoUrl' => '',
    ],
];

// Replace later with a 1600x900 or 1920x1080 group image.
$soloGroupShotPath = syzygy_first_existing_public_path([
    '/assets/img/solo-signals/group-shot.webp',
    '/assets/img/solo-signals/group-shot.jpg',
    '/assets/img/solo-signals/group-shot.png',
    '/assets/img/solo-signals/group-shot-placeholder.webp',
    '/assets/img/solo-signals/group-shot-placeholder.jpg',
    '/assets/img/solo-signals/group-shot-placeholder.png',
    '/assets/img/solo-signals/group-shot-placeholder.svg',
]);
$hasSoloGroupShot = syzygy_public_path_exists($soloGroupShotPath);
?>

<!--
OLD ABOUT SECTION PRESERVED - DO NOT DELETE

<section class="section about-section" id="about">
    <div class="container">
        <div class="about-grid">
            <div class="about-media">
                <div class="about-media__frame">
                    <img
                        src="/assets/img/about-image.jpg"
                        alt="SYZYGY.VOID promotional image"
                        class="about-media__image"
                        loading="lazy"
                    >
                </div>
            </div>

            <div class="about-copy">
                <p class="section__eyebrow">ABOUT THE PROJECT</p>
                <h2 class="section__title about-copy__title">Dark signal. Human emotion. Cinematic pressure.</h2>

                <p class="about-copy__text">
                    SYZYGY.VOID is built around large-scale atmosphere, futuristic tension, and emotionally charged electronic music. The visual identity leans into deep shadow, solar gold, industrial texture, and a premium sci-fi tone that feels part album campaign, part digital myth.
                </p>

                <p class="about-copy__text about-copy__text--muted">
                    Explore releases, visual worlds, and evolving project eras through a site structure designed to scale with new music, videos, roadmap drops, and future expansions.
                </p>

                <div class="stats-grid">
                    <article class="stat-card">
                        <p class="stat-card__value">4K+</p>
                        <p class="stat-card__label">Tracks</p>
                    </article>
                    <article class="stat-card">
                        <p class="stat-card__value">700+</p>
                        <p class="stat-card__label">Active Fans</p>
                    </article>
                    <article class="stat-card">
                        <p class="stat-card__value">3</p>
                        <p class="stat-card__label">Eras</p>
                    </article>
                </div>
            </div>
        </div>
    </div>
</section>
-->

<section class="section about-section solo-signals-section" id="about" aria-labelledby="solo-signals-title">
    <div class="container">
        <div class="solo-signals__header">
            <p class="section__eyebrow">ABOUT THE PROJECT</p>
            <h2 class="section__title solo-signals__title" id="solo-signals-title">SOLO SIGNALS</h2>
            <p class="solo-signals__intro">
                Dark signal. Human emotion. Cinematic pressure. SYZYGY.VOID is not one voice - it is a music and visual universe built from separate human signals moving through the same black circuit. These solo transmissions open the project from the inside: voltage, shadow, machinery, rhythm, architecture, and legacy.
            </p>
        </div>

        <div class="solo-signals__stats" aria-label="SYZYGY.VOID project stats">
            <article class="solo-stat">
                <p class="solo-stat__value">4K+</p>
                <p class="solo-stat__label">Tracks</p>
            </article>
            <article class="solo-stat">
                <p class="solo-stat__value">700+</p>
                <p class="solo-stat__label">Active Fans</p>
            </article>
            <article class="solo-stat">
                <p class="solo-stat__value">3</p>
                <p class="solo-stat__label">Eras</p>
            </article>
        </div>

        <?php if (false): ?>
        SOLO SIGNALS GROUP SHOT PRESERVED - DO NOT RENDER
        <figure class="solo-signals__group-shot" aria-labelledby="solo-group-shot-title">
            <?php if ($hasSoloGroupShot): ?>
                <img
                    src="<?= $esc(syzygy_encode_public_path($soloGroupShotPath)); ?>"
                    alt="SYZYGY.VOID solo signals group lineup"
                    class="solo-signals__group-image"
                    width="1600"
                    height="900"
                    loading="lazy"
                >
            <?php else: ?>
                <?php /*
                SOLO SIGNALS STAGE PLACEHOLDER PRESERVED - DO NOT RENDER
                <div class="solo-signals__stage" aria-hidden="true">
                    <span class="solo-signals__beam solo-signals__beam--one"></span>
                    <span class="solo-signals__beam solo-signals__beam--two"></span>
                    <span class="solo-signals__beam solo-signals__beam--three"></span>
                    <span class="solo-signals__beam solo-signals__beam--four"></span>
                    <span class="solo-signals__beam solo-signals__beam--five"></span>
                    <span class="solo-signals__beam solo-signals__beam--six"></span>
                    <span class="solo-signals__silhouette solo-signals__silhouette--one">NV</span>
                    <span class="solo-signals__silhouette solo-signals__silhouette--two">VR</span>
                    <span class="solo-signals__silhouette solo-signals__silhouette--three">LC</span>
                    <span class="solo-signals__silhouette solo-signals__silhouette--four">LS</span>
                    <span class="solo-signals__silhouette solo-signals__silhouette--five">KN</span>
                    <span class="solo-signals__silhouette solo-signals__silhouette--six">AV</span>
                </div>
                */ ?>
            <?php endif; ?>
            <figcaption class="solo-signals__group-copy">
                <span id="solo-group-shot-title">THE COLLECTIVE SIGNAL</span>
                <!--
                GROUP SHOT REPLACEMENT NOTE PRESERVED - DO NOT RENDER
                Replace this cinematic placeholder with a 1600x900 or 1920x1080 group image when the official SYZYGY.VOID lineup photo is ready.
                -->
            </figcaption>
        </figure>
        <?php endif; ?>

        <div class="solo-signals__grid">
            <?php foreach ($soloSignals as $index => $member): ?>
                <?php
                $sunoUrl = trim((string) ($member['sunoUrl'] ?? ''));
                $hasSunoUrl = $sunoUrl !== ''
                    && preg_match('~^https?://~i', $sunoUrl)
                    && filter_var($sunoUrl, FILTER_VALIDATE_URL) !== false;
                $portraitPath = (string) ($member['image'] ?? '');
                $hasPortrait = $portraitPath !== '' && syzygy_public_path_exists($portraitPath);
                $portraitAlt = (string) ($member['image_alt'] ?? ($member['name'] ?? 'SYZYGY.VOID solo signal portrait'));
                $chapterBio = (string) ($member['chapterBio'] ?? '');
                $loreFile = (string) ($member['loreFile'] ?? '');
                $soundTags = implode('|', array_map('strval', $member['soundTags'] ?? []));
                $visualTags = implode('|', array_map('strval', $member['visualTags'] ?? []));
                ?>
                <article class="solo-card" data-solo-card>
                    <p class="solo-card__status"><?= $esc($member['status']); ?></p>
                    <p class="solo-card__teaser"><?= $esc($member['teaser']); ?></p>

                    <div class="solo-card__portrait"<?= $hasPortrait ? '' : ' aria-label="' . $esc($member['name'] . ' portrait placeholder') . '"'; ?>>
                        <?php if ($hasPortrait): ?>
                            <img
                                src="<?= $esc(syzygy_encode_public_path($portraitPath)); ?>"
                                alt="<?= $esc($portraitAlt); ?>"
                                width="500"
                                height="500"
                                loading="lazy"
                            >
                        <?php else: ?>
                            <span class="solo-card__initials" aria-hidden="true"><?= $esc($member['initials']); ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="solo-card__body">
                        <h3 class="solo-card__name"><?= $esc($member['name']); ?></h3>
                        <p class="solo-card__role"><?= $esc($member['role']); ?></p>
                        <p class="solo-card__release"><?= $esc($member['release']); ?></p>

                        <button
                            class="solo-card__read-more"
                            type="button"
                            aria-haspopup="dialog"
                            aria-controls="solo-signal-modal"
                            data-solo-name="<?= $esc($member['name']); ?>"
                            data-solo-role="<?= $esc($member['role']); ?>"
                            data-solo-release="<?= $esc($member['release']); ?>"
                            data-solo-status="<?= $esc($member['status']); ?>"
                            data-solo-bio-text="<?= $esc($chapterBio); ?>"
                            data-solo-lore="<?= $esc($loreFile); ?>"
                            data-solo-sound="<?= $esc($soundTags); ?>"
                            data-solo-visual="<?= $esc($visualTags); ?>"
                            data-solo-image="<?= $hasPortrait ? $esc(syzygy_encode_public_path($portraitPath)) : ''; ?>"
                            data-solo-image-alt="<?= $esc($portraitAlt); ?>"
                            data-solo-playlist-url="<?= $hasSunoUrl ? $esc($sunoUrl) : ''; ?>"
                            data-solo-read-more
                        >
                            <?= $esc($soloLabels['readMoreLabel']); ?>
                        </button>
                    </div>

                    <?php if ($hasSunoUrl): ?>
                        <a
                            class="solo-card__button"
                            href="<?= $esc($sunoUrl); ?>"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            <?= $esc($soloLabels['listenLabel']); ?>
                        </a>
                    <?php else: ?>
                        <span class="solo-card__button solo-card__button--disabled" aria-disabled="true">
                            <?= $esc($soloLabels['pendingLabel']); ?>
                        </span>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </div>

        <div
            class="solo-modal"
            id="solo-signal-modal"
            role="dialog"
            aria-modal="true"
            aria-labelledby="solo-modal-title"
            aria-describedby="solo-modal-bio"
            aria-hidden="true"
            hidden
        >
            <div class="solo-modal__backdrop" data-solo-modal-close></div>
            <div class="solo-modal__dialog" role="document">
                <button
                    class="solo-modal__close"
                    type="button"
                    aria-label="Close solo signal"
                    data-solo-modal-close
                >
                    <span aria-hidden="true">×</span>
                </button>

                <div class="solo-modal__content">
                    <p class="solo-modal__eyebrow">MEMBER TRANSMISSION</p>
                    <div class="solo-modal__heading">
                        <h3 class="solo-modal__title" id="solo-modal-title" data-solo-modal-title></h3>
                        <p class="solo-modal__release" data-solo-modal-release></p>
                        <p class="solo-modal__meta">
                            <span data-solo-modal-role></span>
                            <span aria-hidden="true">/</span>
                            <span data-solo-modal-status></span>
                        </p>
                    </div>

                    <div class="solo-modal__media">
                        <img
                            class="solo-modal__image"
                            src=""
                            alt=""
                            width="500"
                            height="500"
                            data-solo-modal-image
                            hidden
                        >
                        <div class="solo-modal__image-fallback" data-solo-modal-fallback aria-hidden="true">SV</div>
                    </div>

                    <section class="solo-modal__detail solo-modal__detail--primary">
                        <h4>Chapter Bio</h4>
                        <p id="solo-modal-bio" data-solo-modal-bio></p>
                    </section>

                    <div class="solo-modal__detail-grid">
                        <section class="solo-modal__detail">
                            <h4>Lore File</h4>
                            <p data-solo-modal-lore></p>
                        </section>
                        <section class="solo-modal__detail">
                            <h4>Sound Signature</h4>
                            <div class="solo-modal__chips" data-solo-modal-sound></div>
                        </section>
                        <section class="solo-modal__detail">
                            <h4>Visual Code</h4>
                            <div class="solo-modal__chips" data-solo-modal-visual></div>
                        </section>
                    </div>

                    <div class="solo-modal__actions">
                        <a
                            class="solo-modal__playlist"
                            target="_blank"
                            rel="noopener noreferrer"
                            data-solo-modal-playlist
                            hidden
                        >
                            <?= $esc($soloLabels['listenLabel']); ?>
                        </a>
                        <span
                            class="solo-modal__playlist solo-modal__playlist--disabled"
                            aria-disabled="true"
                            data-solo-modal-pending
                        >
                            <?= $esc($soloLabels['pendingLabel']); ?>
                        </span>
                        <button
                            class="solo-modal__secondary-close"
                            type="button"
                            data-solo-modal-close
                        >
                            <?= $esc($soloLabels['closeLabel']); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
