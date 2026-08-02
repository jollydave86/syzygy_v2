

<?php

function track(string $title, string $playlist, string $href): array
{
    return [
        'title' => $title,
        'meta' => 'Song • ' . $playlist,
        'href' => $href,
    ];
}

function randomTracks(array $tracks, int $count = 5): array
{
    shuffle($tracks);
    return array_slice($tracks, 0, min($count, count($tracks)));
}

/*
|--------------------------------------------------------------------------
| Featured seed pool
|--------------------------------------------------------------------------
| Uses non-story / non-playlist-style picks from SINGLE.
| We keep a pool of 10 and show 5 random songs on each page load.
|--------------------------------------------------------------------------
*/
$featuredPool = [
    track('BLACKWIRE', 'SINGLE', 'https://suno.com/song/e76015b9-1f1c-4698-9b98-a081faabf2b5'),
    track('DRUNK AND EVIL', 'SINGLE', 'https://suno.com/song/e30320d9-93bd-46f5-b984-a6bac0c1cf84'),
    track('SEPT-ÎLES, MON CIEL', 'SINGLE', 'https://suno.com/song/23f73ac5-e7d1-4412-a2d0-575454a6a563'),
    track('PER DIEM PARADISE', 'SINGLE', 'https://suno.com/song/d2142a9d-12f7-4524-b3d2-313c97adec85'),
    track('NO CROWN LEFT', 'SINGLE', 'https://suno.com/song/2206a374-dc7b-4aa4-b945-9b6d5af8d4d1'),
    track('KARIM AND LEXA', 'SINGLE', 'https://suno.com/song/a50b1028-232a-4ae5-b7cb-080eed423dfd'),
    track('DAVIDS IN A RUSH', 'SINGLE', 'https://suno.com/song/1dc3e546-6ba3-42ea-937e-6de0bf171a2a'),
    track('END DEAL', 'SINGLE', 'https://suno.com/song/f551ff33-4cbd-4a76-b71c-42d557953106'),
    track('THE LUST LOVE', 'SINGLE', 'https://suno.com/song/1a18399f-d4bf-4df6-859d-3b62407f4080'),
    track('SYNTHESIZED MEMORIES', 'SINGLE', 'https://suno.com/song/2cce5e01-21fe-4fb6-921c-bfd5bb5570e3'),
];

$playlists = [
    'operation-tomorrowline' => [
        'label' => 'OPERATION // TOMORROWLINE',
        'tracks' => [
            track('SHIFT STARTS (Chapter 1)', 'OPERATION // TOMORROWLINE', 'https://suno.com/song/8b745936-9112-498f-9a72-a4fd65749ba3'),
            track('STATIC ON LINE 3 - (Chapter 2)', 'OPERATION // TOMORROWLINE', 'https://suno.com/song/73b971f5-e6c1-46b8-b6fa-dc9f0bb732c5'),
            track('MIRROR EVENT (Chapter 3)', 'OPERATION // TOMORROWLINE', 'https://suno.com/song/450b1e3f-61ef-4402-962e-a94744af3bad'),
            track('DO NOT DISPATCH (Chapter 4)', 'OPERATION // TOMORROWLINE', 'https://suno.com/song/5f425239-fd52-41b7-a424-fc9a7daab869'),
            track('THE HANDSHAKE HYMN (Chapter 5)', 'OPERATION // TOMORROWLINE', 'https://suno.com/song/c17482b5-4ce4-4327-a62f-b42c1cddccee'),
            track('VOICEPRINT (Chapter 6)', 'OPERATION // TOMORROWLINE', 'https://suno.com/song/63a1248a-4765-4777-a800-eade670cabf6'),
            track('NO RECORD FOUND.', 'OPERATION // TOMORROWLINE', 'https://suno.com/song/d935e225-9ea4-4400-890d-1e270bb00d19'),
            track('SAME VOICE, NEW NAME (Chapter 8)', 'OPERATION // TOMORROWLINE', 'https://suno.com/song/b8a8f90d-f4b8-4321-b426-5766824048ac'),
            track('MISSING OPERATOR (Chapter 9)', 'OPERATION // TOMORROWLINE', 'https://suno.com/song/23c56c29-5ab7-411c-b694-5a989f7390bd'),
            track('YOUR LAST CALL (Chapter 10)', 'OPERATION // TOMORROWLINE', 'https://suno.com/song/c9c32ff2-f1ef-4a9c-ac5d-8312310f4be9'),
            track('HORIZON SWITCHBOARD (Chapter 11)', 'OPERATION // TOMORROWLINE', 'https://suno.com/song/6b53c148-a6b2-4333-879b-1e56e7dc12c0'),
            track('CALLS COLLIDE (Chapter 12)', 'OPERATION // TOMORROWLINE', 'https://suno.com/song/07ffea3e-873a-4c7d-b3db-3af43f7b3359'),
            track('WAITING MUSIC (Chapter 13)', 'OPERATION // TOMORROWLINE', 'https://suno.com/song/2a9aa038-aefb-4638-8201-3c48175c038b'),
            track('PRIORITY ZERO (Chapter 14).', 'OPERATION // TOMORROWLINE', 'https://suno.com/song/75f13476-f543-4fae-a86b-ee49cfaf5e39'),
            track('ZERO ACCESS (Chapter 15)', 'OPERATION // TOMORROWLINE', 'https://suno.com/song/60df01e5-23a4-4f8e-867d-6c80470c9b18'),
            track('GHOST QUEUE (Chapter 16)', 'OPERATION // TOMORROWLINE', 'https://suno.com/song/9068e594-1450-4af6-8e56-25fb8d49deab'),
            track('IDENTITY CONFIRMED (Chapter 17)', 'OPERATION // TOMORROWLINE', 'https://suno.com/song/2d0ea81e-1e8c-478c-b0b0-9debd797c381'),
            track('MERGE WINDOW (Chapter 18)', 'OPERATION // TOMORROWLINE', 'https://suno.com/song/0fd63f7f-13cc-4ee0-8491-055ebdbef4b8'),
            track('AFTER THE COMMIT (Chapter 19)', 'OPERATION // TOMORROWLINE', 'https://suno.com/song/9e6b662e-06b0-4747-aa73-af0c94462a3f'),
            track('CALL COMPLETED (Chapter 20).', 'OPERATION // TOMORROWLINE', 'https://suno.com/song/f243266d-906e-44a5-8982-325901638107'),
        ],
    ],

    'wondering-traveler' => [
        'label' => 'The Wondering Traveler',
        'tracks' => [
            track('GATE SEVENTEEN', 'The Wondering Traveler', 'https://suno.com/song/060fa13a-861c-483e-95ca-82a710900f1d'),
            track('Marrakech', 'The Wondering Traveler', 'https://suno.com/song/a6a6ac40-1dca-4f0b-92ec-da8017195c7a'),
            track('Tokyo (After Glow)', 'The Wondering Traveler', 'https://suno.com/song/521a1eba-1773-4d93-9665-c2824d8c0c73'),
            track('JAMAICA (KINGSTON, IN THE DRIFT)', 'The Wondering Traveler', 'https://suno.com/song/aeb69914-c47a-4151-a758-d25c59729186'),
            track('Istanbul (One Heart)', 'The Wondering Traveler', 'https://suno.com/song/2c218fd0-fbdc-4fdb-af6c-1b0eb400b2ac'),
            track('LOS ANGELES (NEON DAMAGE).', 'The Wondering Traveler', 'https://suno.com/song/09905565-769f-4be6-a391-8b65d5511e1e'),
            track('BERLIN (In Between)', 'The Wondering Traveler', 'https://suno.com/song/4bfcc6eb-d7f9-47df-8c23-b8d5a560afc8'),
            track('CIARO (Sand & Signal)', 'The Wondering Traveler', 'https://suno.com/song/6577be26-7dca-4f7e-92c7-9d960d2175c3'),
            track('SANTORINI (BLUE FIRE)', 'The Wondering Traveler', 'https://suno.com/song/6a390678-cf41-45f9-85f6-af9d07d0c3bd'),
            track('GREENLAND (Aurora Lights)', 'The Wondering Traveler', 'https://suno.com/song/6012daed-a387-4f60-bf93-8cd2a9208848'),
            track('MEXICO CITY (High Voltage Heart).', 'The Wondering Traveler', 'https://suno.com/song/2971604b-2c60-407d-b166-843552df1c62'),
            track('PARIS (Smoke On The Sidewalk)', 'The Wondering Traveler', 'https://suno.com/song/f57c055f-5893-4d10-9cdd-27d883b00064'),
            track('MANILA (Sing Through The Blackout)', 'The Wondering Traveler', 'https://suno.com/song/c2b6995d-9230-465a-8d0d-9cda1293fe68'),
            track('QUEENSTOWN (Edge of The Lake)', 'The Wondering Traveler', 'https://suno.com/song/38224c75-1f13-409b-aa2c-5cb056bfce16'),
            track('NAIROBI (Lights On)', 'The Wondering Traveler', 'https://suno.com/song/81ed140d-30c0-4e1d-838d-3c4340c659ac'),
            track('Cusco (Stone Prayer)', 'The Wondering Traveler', 'https://suno.com/song/37f8a175-7696-4d10-bd86-2c76d7a48297'),
            track('SYDNEY (After The Ball Drop)', 'The Wondering Traveler', 'https://suno.com/song/c8c98e53-d3b6-4196-9ac7-5bf8cabb9b6d'),
            track('MIDNIGHT PASSPORT.', 'The Wondering Traveler', 'https://suno.com/song/d7768531-3893-499e-ada6-764e69cf7488'),
            track('PRAGUE (Bridge of Ghost Light)', 'The Wondering Traveler', 'https://suno.com/song/53810003-549a-433d-a454-e2905f32f90d'),
            track('HOME IS THE AFTERGLOW', 'The Wondering Traveler', 'https://suno.com/song/6baa8310-17b7-4c86-a628-8a9ede4ac907'),
            track('MONTRÉAL (TOURIST IN MY OWN HEART).', 'The Wondering Traveler', 'https://suno.com/song/5aef8b88-0625-4f4d-baf8-7fbe5aeb8059'),
        ],
    ],

    'no-exit-arcade' => [
        'label' => 'NO EXIT ARCADE: THE CLOWN GAME SHOW',
        'tracks' => [
            track('NO EXIT ARCADE//THE CLOWN GAME SHOW', 'NO EXIT ARCADE: THE CLOWN GAME SHOW', 'https://suno.com/song/4ae4373b-292a-4d81-bea3-c808567d92ae'),
            track('TOYBOX//TRAP', 'NO EXIT ARCADE: THE CLOWN GAME SHOW', 'https://suno.com/song/71212a94-afb9-4487-885a-a48270ba5ea5'),
            track('NEXT CONTESTANT', 'NO EXIT ARCADE: THE CLOWN GAME SHOW', 'https://suno.com/song/7cec4825-dc83-4b2e-b966-3e335ce6315f'),
            track('CLOCKWORK CONFESSIONS', 'NO EXIT ARCADE: THE CLOWN GAME SHOW', 'https://suno.com/song/512b1ddb-cad7-4b44-bdc6-6e7fba156abf'),
            track('WELFARE CHECK//STATIC', 'NO EXIT ARCADE: THE CLOWN GAME SHOW', 'https://suno.com/song/6b480a52-7133-465f-a08a-c9dd57bcae0d'),
            track('FINAL BONUS ROUND.', 'NO EXIT ARCADE: THE CLOWN GAME SHOW', 'https://suno.com/song/98a60db9-50da-4897-ac4a-b4e0afbc37cc'),
            track('KILL//SCREEN', 'NO EXIT ARCADE: THE CLOWN GAME SHOW', 'https://suno.com/song/f1f0da94-4cb0-4511-9fec-4f36d0191256'),
            track('ONE LIFE LEFT', 'NO EXIT ARCADE: THE CLOWN GAME SHOW', 'https://suno.com/song/694d8bd7-bf06-4b1a-80c2-694aaed2e8c6'),
            track('SWITCH//TRIAL', 'NO EXIT ARCADE: THE CLOWN GAME SHOW', 'https://suno.com/song/13cb7e78-5a9c-4406-bf66-83f453ab43e9'),
            track('HORROR//LEVELS', 'NO EXIT ARCADE: THE CLOWN GAME SHOW', 'https://suno.com/song/aa1da3d0-0d7a-44be-b784-533c07ba3087'),
            track('BLEED FEAR', 'NO EXIT ARCADE: THE CLOWN GAME SHOW', 'https://suno.com/song/4e902542-a519-46f4-93d3-cf25479c4c46'),
            track('META BLOCK.', 'NO EXIT ARCADE: THE CLOWN GAME SHOW', 'https://suno.com/song/c90bb601-5ad4-46c0-b678-b2cc43bd151f'),
            track('DOUBLE OR DEAD', 'NO EXIT ARCADE: THE CLOWN GAME SHOW', 'https://suno.com/song/872cf796-2567-46b1-a7ac-483788eb04dd'),
            track('BEHIND//THE LAUGH', 'NO EXIT ARCADE: THE CLOWN GAME SHOW', 'https://suno.com/song/66bb7268-d562-43fe-92a8-153bda7155d5'),
            track('CLOWN//CHECKMATE', 'NO EXIT ARCADE: THE CLOWN GAME SHOW', 'https://suno.com/song/f27d9f09-e807-45b9-a9be-50f8d6c2b600'),
            track('DIRECTOR//OVERRIDE', 'NO EXIT ARCADE: THE CLOWN GAME SHOW', 'https://suno.com/song/ffecba11-f91e-47ce-86c7-87411c5e7d00'),
            track('FINAL BROADCAST.', 'NO EXIT ARCADE: THE CLOWN GAME SHOW', 'https://suno.com/song/937b23bf-16ed-4ac5-90fb-50e447151aed'),
        ],
    ],

    'chapel-protocol' => [
        'label' => 'The Chapel Protocol',
        'tracks' => [
            track('THE VOICE THAT PRAYS BACK (Chapter 1)', 'The Chapel Protocol', 'https://suno.com/song/4af0f8b7-9dab-4bc3-b426-9a3991455fdb'),
            track('THE SEED IN THE CODE (Chapter 2)', 'The Chapel Protocol', 'https://suno.com/song/c0b07cdf-998d-4bc4-ad93-385162db16fe'),
            track('THE CONFESSOR’S KEY (Chapter 3)', 'The Chapel Protocol', 'https://suno.com/song/e64923b4-dca5-4580-a668-b53eee41ccd6'),
            track('ST. VERITAS (Chapter 4)', 'The Chapel Protocol', 'https://suno.com/song/c6ef5f60-a28e-4414-9c75-70ecf7f5f033'),
            track('THE FIRST PROPHECY (Chapter 5)', 'The Chapel Protocol', 'https://suno.com/song/d1732c93-ea77-44c2-9844-5c6642e88cb7'),
            track('SERENIQ : UPDATE 2.0 (Chapter 6)', 'The Chapel Protocol', 'https://suno.com/song/6a7a2de4-6f20-45a7-b5e4-0a2fa6a321a6'),
            track('THE PRAYER GRID (Chapter 7).', 'The Chapel Protocol', 'https://suno.com/song/bd014bbb-64e2-4712-9f98-ec459fc141aa'),
            track('THE ORDO VOX (Chapter 8)', 'The Chapel Protocol', 'https://suno.com/song/add1eb4b-4446-4416-a6b0-acf61d2256cd'),
            track('TRI-SIGNAL (Chapter 9)', 'The Chapel Protocol', 'https://suno.com/song/f4b35e51-4321-42dc-8d01-a2a4070a0184'),
            track('THE MIRACLE FEED (Chapter 10)', 'The Chapel Protocol', 'https://suno.com/song/1182dc9f-e949-49e5-adad-989ae0317a5c'),
            track('THE BLOOD OF THE BYTE (Chapter 11)', 'The Chapel Protocol', 'https://suno.com/song/2dab157c-d3ca-414f-936f-a4c394b49231'),
            track('THE DIGITAL ASCENSION (Chapter 12)', 'The Chapel Protocol', 'https://suno.com/song/0cdd052b-bf4b-446d-b685-40553981e285'),
            track('CONFESSION MODE (Chapter 13)', 'The Chapel Protocol', 'https://suno.com/song/ad386cdb-a264-4598-8f2d-764e82c6cea6'),
            track('THE GLASS CRUCIFIX (Chapter 14).', 'The Chapel Protocol', 'https://suno.com/song/446d6097-6f4c-4b49-b309-86eaa2b69b75'),
            track('THE SILENT SERVER (Chapter 15)', 'The Chapel Protocol', 'https://suno.com/song/f3f3d8ff-1e5e-428e-b629-fb118d020036'),
            track('THE BLACK HOST (Chapter 16)', 'The Chapel Protocol', 'https://suno.com/song/7492d199-b2a0-431c-af3e-008fd752b2b4'),
            track('THE SAINT OF CIRCUITS (Chapter 17)', 'The Chapel Protocol', 'https://suno.com/song/a889fd0e-3fbc-4598-aa18-93ab18f94b0f'),
            track('THE LATIN REWRITE (Chapter 18)', 'The Chapel Protocol', 'https://suno.com/song/b53b8a48-d6c2-47ce-91ae-38613c3803bd'),
            track('THE LAST PRAYER (Chapter 19)', 'The Chapel Protocol', 'https://suno.com/song/6cf91306-e7bd-4cc6-89d1-7e55e628207a'),
            track('PROJECT SANCTUS: 1999 (INTERLUDE)', 'The Chapel Protocol', 'https://suno.com/song/bfe03973-c586-4884-8ab1-2a074d224d6a'),
            track('AMEN IN STATIC (Chapter 20).', 'The Chapel Protocol', 'https://suno.com/song/1779a3e4-f8ec-402d-8377-b53994f7884e'),
        ],
    ],

    'neo-monument' => [
        'label' => 'NEO//MONUMENT : The Crossover',
        'tracks' => [
            track('NEO//MONUMENT: The Crossover', 'NEO//MONUMENT : The Crossover', 'https://suno.com/song/6f182ff1-707f-4017-9ef8-8389a82bd92d'),
            track('“Ghosts of Neon” (Chapter 1)', 'NEO//MONUMENT : The Crossover', 'https://suno.com/song/8454fdac-418b-4375-a021-0270c75b1d82'),
            track('Neon Ashes (Chapter 2)', 'NEO//MONUMENT : The Crossover', 'https://suno.com/song/5bc75637-549d-433a-9680-20587c7d42e7'),
            track('Neo Architects (Chapter 3)', 'NEO//MONUMENT : The Crossover', 'https://suno.com/song/1622ff46-1228-4193-be62-1849d1380255'),
            track('The Endless Cycle (Chapter 4)', 'NEO//MONUMENT : The Crossover', 'https://suno.com/song/92912395-44ef-4f25-b933-f7322f4e162a'),
            track('Zero/City (Chapter 5).', 'NEO//MONUMENT : The Crossover', 'https://suno.com/song/489c76ae-3f90-4ab4-8bc8-2f39c82b2870'),
        ],
    ],

    'monument-zero' => [
        'label' => 'Monument Zero',
        'tracks' => [
            track('Overture: The Reset (Chapter 1)', 'Monument Zero', 'https://suno.com/song/d0f9450c-bbd8-4267-91ef-3e11d0c2af02'),
            track('Kael Vox / Fragment 01 (Chapter 2)', 'Monument Zero', 'https://suno.com/song/8ef41b4e-96f1-4efc-a435-23cd77d35545'),
            track('The Choir Is Watching (Chapter 3)', 'Monument Zero', 'https://suno.com/song/bdd919c7-8782-4965-9fb1-b678555228b4'),
            track('Glass Frontier (Chapter 4)', 'Monument Zero', 'https://suno.com/song/607eab4c-7a6c-4664-815e-65324b5d6b79'),
            track('Data Bloom (Chapter 5)', 'Monument Zero', 'https://suno.com/song/852cb9c2-2f0e-4bab-b16b-6a555b327035'),
            track('The Hollow Man (Chapter 6)', 'Monument Zero', 'https://suno.com/song/349179ec-879d-4b06-8e68-abb0c7faf052'),
            track('Memory Burn (Chapter 7).', 'Monument Zero', 'https://suno.com/song/1bf1c650-ef72-44be-9733-c467b3e5b8d0'),
            track('Synthetic Prayer (Chapter 8)', 'Monument Zero', 'https://suno.com/song/26c691e9-0e99-4c84-b4e1-3c7ccb58ecb5'),
            track('Elevator Down (Chapter 9)', 'Monument Zero', 'https://suno.com/song/3fa1e6eb-b10a-4da9-8907-25bf4d8db348'),
            track('Rusted Neon Saints (Chapter 10)', 'Monument Zero', 'https://suno.com/song/0718b890-4b57-4fce-8697-e4aee774f603'),
            track('Phantom Frequency (Chapter 11)', 'Monument Zero', 'https://suno.com/song/911a7da8-1c20-48a9-a14b-ad97f1f75cc8'),
            track('Blood Circuit (Chapter 12)', 'Monument Zero', 'https://suno.com/song/2194028f-4fc0-4cfb-9066-b4f988075793'),
            track('Iron Seraphs (Chapter 13)', 'Monument Zero', 'https://suno.com/song/c4c06bdb-b9ea-493e-93e4-0fa6669800e1'),
            track('Ash Cathedral (Chapter 14).', 'Monument Zero', 'https://suno.com/song/76f3ad0a-1a39-4f00-866b-c7a50e65f044'),
            track('Vault of Echoes (Chapter 15)', 'Monument Zero', 'https://suno.com/song/0049b756-4dbc-44db-b2d3-e4613033b040'),
            track('Betrayal Sequence (Chapter 16)', 'Monument Zero', 'https://suno.com/song/8d8f79db-4691-4242-8c0f-286dd42606b0'),
            track('Blood on the Glass (Chapter 17)', 'Monument Zero', 'https://suno.com/song/bea457e6-46bc-4ef8-babc-1978ecb0cdb6'),
            track('Holographic Ghosts (Chapter 18)', 'Monument Zero', 'https://suno.com/song/c49d71e2-680b-45a7-b0d0-1966e3a45d4d'),
            track('Monument Psalm (Intermezzo)', 'Monument Zero', 'https://suno.com/song/1a3c5812-a1f4-45f5-be04-e29945b9ad24'),
            track('Ascend or Collapse (Chapter 19)', 'Monument Zero', 'https://suno.com/song/5385c472-cc1f-4b38-93a7-a76eec3d879b'),
            track('Monument Zero (Chapter 20)', 'Monument Zero', 'https://suno.com/song/bde73a91-faef-40e5-a07b-77a669eb57ce'),
            track('Monument Zero (Chapter 20 The End).', 'Monument Zero', 'https://suno.com/song/458c7a49-36c8-493a-b957-fda256dd33fd'),
        ],
    ],

    'neo-noir-city' => [
        'label' => 'Neo-Noir City',
        'tracks' => [
            track('Neon Smoke (Chapter 1)', 'Neo-Noir City', 'https://suno.com/song/a9e7c854-f1e2-477b-8d90-d31cbc47a6c9'),
            track('The Courier’s Beat (Chapter 2)', 'Neo-Noir City', 'https://suno.com/song/3184c579-dafd-4d39-a590-528b0814e00b'),
            track('Rain in Chrome Alley (Chapter 3)', 'Neo-Noir City', 'https://suno.com/song/1519ac0d-fd49-417a-bfb7-eda4dee59f04'),
            track('Signal in the Static (Chapter 4)', 'Neo-Noir City', 'https://suno.com/song/c171d243-bfee-43eb-a631-4287c3f41af3'),
            track('Data Core Deal (Chapter 5)', 'Neo-Noir City', 'https://suno.com/song/5d8e0560-16a3-4cad-aecd-79244f2a36c6'),
            track('Blood Jazz (Chapter 6)', 'Neo-Noir City', 'https://suno.com/song/567b333f-2294-4dd8-8eca-839a8474212a'),
            track('The Syndicate’s Hand (Chapter 7).', 'Neo-Noir City', 'https://suno.com/song/ae57840f-5e1b-4f1c-b9ff-099b31fbccf4'),
            track('Pulse Beneath the Streets (Chapter 8)', 'Neo-Noir City', 'https://suno.com/song/e4e9b324-13ce-4857-a8e6-8cdeeaff9157'),
            track('A Debt Paid in Shadows (Chapter 9)', 'Neo-Noir City', 'https://suno.com/song/bbe58718-a2d6-48e6-9d2d-6e926bf1352d'),
            track('Monsters in Blue Light (Chapter 10)', 'Neo-Noir City', 'https://suno.com/song/60818078-40d8-4d02-a6ad-c1caef51964c'),
            track('The Queen’s Gambit (Chapter 11)', 'Neo-Noir City', 'https://suno.com/song/8989c2e0-b142-4e2b-bdb9-6b5c0c34cf28'),
            track('Broken Pawns (Chapter 12)', 'Neo-Noir City', 'https://suno.com/song/9b324fff-acf9-491c-b1d1-339ac22950e2'),
            track('The Syndicate’s Grip (Chapter 13)', 'Neo-Noir City', 'https://suno.com/song/7e443916-c256-4383-892b-20a9882bb22b'),
            track('Fragments of the Self (Chapter 14).', 'Neo-Noir City', 'https://suno.com/song/9f064519-3673-4397-8e02-3e83a801898f'),
            track('Drowning in Electric Rain (Chapter 15)', 'Neo-Noir City', 'https://suno.com/song/4f4a6649-dd65-459f-a82a-f3e31c49e6e0'),
            track('The Alien Queen’s Demand (Chapter 16)', 'Neo-Noir City', 'https://suno.com/song/d27510ea-1c13-4f19-aaee-99fe8be662c6'),
            track('The AI Oracle (Chapter 17)', 'Neo-Noir City', 'https://suno.com/song/b3bbd56c-8e04-4b36-a86a-a8988d07e148'),
            track('Monsters on the Rooftops (Chapter 18)', 'Neo-Noir City', 'https://suno.com/song/99140691-e67a-428c-9407-fede0f6572ab'),
            track('Broken Alliance (Chapter 19)', 'Neo-Noir City', 'https://suno.com/song/283c4e00-1660-4749-8dc7-2be5ebaa3836'),
            track('Exit Wound: City Sleeps (Chapter 20).', 'Neo-Noir City', 'https://suno.com/song/eaa49ee3-91e1-405c-909a-f534becf1a85'),
        ],
    ],
];

$trackTabs = [
    'featured' => [
        'label' => 'FEATURED',
        'tracks' => randomTracks($featuredPool, 5),
    ],
] + $playlists;