<?php
declare(strict_types=1);
$about = $site['about'] ?? [];
?>
<section class="page-header">
    <div class="container">
        <p class="section__eyebrow"><?= syzygy_esc($about['eyebrow'] ?? 'About'); ?></p>
        <h1 class="section__title"><?= syzygy_esc($about['title'] ?? 'SYZYGY.VOID'); ?></h1>
    </div>
</section>

<section class="section about-page">
    <div class="container about-page__grid">
        <div class="about-page__copy">
            <p><?= syzygy_esc($about['text'] ?? ''); ?></p>
            <p><?= syzygy_esc($about['text_2'] ?? ''); ?></p>
            <div class="stats-row">
                <?php foreach (($about['stats'] ?? []) as $stat): ?>
                    <div class="stat-block">
                        <p class="stat-block__value"><?= syzygy_esc($stat['value'] ?? ''); ?></p>
                        <p class="stat-block__label"><?= syzygy_esc($stat['label'] ?? ''); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
            <a class="btn btn--primary" href="<?= syzygy_esc(syzygy_url('/profiles')); ?>">Solo Signals</a>
        </div>
        <div class="about-page__media">
            <?php if (!empty($about['image'])): ?>
                <img src="<?= syzygy_esc(syzygy_encode_public_path($about['image'])); ?>" alt="SYZYGY.VOID" width="400" height="500" loading="lazy" decoding="async">
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="section section--dark">
    <div class="container">
        <div class="section-heading">
            <p class="section__eyebrow">The Project</p>
            <h2 class="section__title">More Than a Band Name</h2>
            <p class="section__lede">SYZYGY.VOID is a connected music universe, narrative archive, visual system, fictional group history, and evolving digital mythology.</p>
        </div>
        <div class="about-canon-grid">
            <article>
                <p class="section__eyebrow">The Foundation</p>
                <h3>SYZYGY Before the VOID</h3>
                <p>The original foundation was Nova, Ash, and Kade: melodic command, damaged truth, and the architecture that made different voices sound like one system.</p>
            </article>
            <article>
                <p class="section__eyebrow">The Transformation</p>
                <h3>A Narrative System</h3>
                <p>The project expanded into futuristic cities, artificial intelligence, monumental systems, digital religion, rotating narrators, and chapter-based records.</p>
            </article>
            <article>
                <p class="section__eyebrow">The Human Center</p>
                <h3>Emotion Inside Machinery</h3>
                <p>Every world returns to identity, memory, faith, control, fracture, and the people trying to remain human while the surrounding system grows larger.</p>
            </article>
        </div>
    </div>
</section>

<section class="section">
    <div class="container about-worlds">
        <div>
            <p class="section__eyebrow">Connected Worlds</p>
            <h2 class="section__title">Cities, Chapels & Systems</h2>
        </div>
        <div class="about-worlds__copy">
            <p><strong>Singles Night V5.5</strong> sits beside Featured Signals — remasters, remixes, and one-off transmissions from Neon Thick Skin to THE TOMORROWLINE, a full singles wall in the catalogue.</p>
            <p><strong>Forever City</strong> is the City of Echoes — Little Neo City grows too advanced and starts singing: romance tables, brake lights, borrowed sky, and the last render of a world that learned to echo itself.</p>
            <p><strong>Monument Zero</strong> is cold architecture, synthetic prayer, glass structures, betrayal, ascension, and collapse.</p>
            <p><strong>Neo-Noir City</strong> brings the system down to street level: couriers, electric rain, debts, syndicates, and monsters under blue light.</p>
            <p><strong>NEO//MONUMENT</strong> collides those two worlds — monumental systems and street-level noir forced into one transmission.</p>
            <p><strong>The Chapel Protocol</strong> turns code into ritual, servers into sanctuaries, and confession into an algorithm.</p>
            <p><strong>The Chapel Protocol // Aftermath</strong> follows what remains after the digital religion spreads or collapses: lost tapes, thaw lines, and the remaining choir.</p>
            <p><strong>NO EXIT ARCADE</strong> is arcade horror under a clown game-show machine — competition, CRT pressure, and survival with one life left.</p>
            <p><strong>The Wondering Traveler</strong> moves through international cities as emotional transit: displacement, memory, and being a tourist inside one’s own life.</p>
            <p><strong>OPERATION // TOMORROWLINE</strong> is communications crisis — switchboards, voiceprints, missing operators, and identity turned into records.</p>
            <p><strong>NO IDLE SPEED</strong> is Lucien Cross in motion — electro-rap, 808 pressure, chapel backs, prize machines, and the Tomorrowline as a personal highway.</p>
            <p><strong>WHITE VOLTAGE</strong> and <strong>DELUXE QUEEN</strong> are Nova Vale’s dual signal: industrial command, then the colder coronation in the same white fire.</p>
            <p><strong>BODY CLOCK</strong> is Lyra Static in French — percussion, injury, muscle memory, the body that kept time for everyone else.</p>
            <p><strong>THE SHAPE I LEFT</strong> is Ash Vex’s negative space: one room, one amplifier, the outline in the wall after Arcade.</p>
            <p><strong>OCCUPANCY ZERO</strong> is Kade Null’s locked solo architecture — lost takes, common time, quiet engines, and house lights in a room that was always his.</p>
            <a class="btn btn--primary" href="<?= syzygy_esc(syzygy_url('/music')); ?>">Enter the Music Worlds</a>
        </div>
    </div>
</section>

<section class="section section--dark">
    <div class="container">
        <div class="section-heading">
            <p class="section__eyebrow">Era 3 / Solo Signals</p>
            <h2 class="section__title">Five Rooms Off the Shared Machine</h2>
            <p class="section__lede">The universe does not only serialize cities. It splits. Each ready voice occupies a private frequency — Suno-first, merch-concepted, journaled through Forever Cities on October 10.</p>
        </div>
        <div class="about-canon-grid">
            <article>
                <p class="section__eyebrow">Lucien Cross</p>
                <h3>NO IDLE SPEED</h3>
                <p>Ten cuts of electro-rap motion. The Tomorrowline stops being an album world and becomes his tempo. Get on or get left.</p>
            </article>
            <article>
                <p class="section__eyebrow">Nova Vale</p>
                <h3>WHITE VOLTAGE / DELUXE QUEEN</h3>
                <p>Eighteen clips, two crowns. Detonation then coronation — glass sirens, throat of light, the last vote.</p>
            </article>
            <article>
                <p class="section__eyebrow">Lyra Static</p>
                <h3>BODY CLOCK</h3>
                <p>Six French transmissions. Dry snares, bandaged fingers, the salle that still rings after the sequencer goes quiet.</p>
            </article>
            <article>
                <p class="section__eyebrow">Ash Vex</p>
                <h3>THE SHAPE I LEFT</h3>
                <p>Six dirty rooms. He left during Arcade. The EP is the wound catalogued, not a reunion.</p>
            </article>
            <article>
                <p class="section__eyebrow">Kade Null</p>
                <h3>OCCUPANCY ZERO</h3>
                <p>Eight modular rooms. The architect finally signs the blueprint in public. House lights up. Occupancy: one.</p>
            </article>
            <article>
                <p class="section__eyebrow">Vanta Rey</p>
                <h3>Still Coming Soon</h3>
                <p>Intentionally incomplete. The fragment stays locked until the story says otherwise. Incompleteness is canon.</p>
            </article>
        </div>
        <p class="about-era-cta"><a class="btn btn--secondary" href="<?= syzygy_esc(syzygy_url('/profiles')); ?>">Open Solo Signals</a></p>
    </div>
</section>
