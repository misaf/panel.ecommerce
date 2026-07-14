<?php

declare(strict_types=1);

arch()->preset()->php();
arch()->preset()->security();
arch()->preset()->laravel();

arch('the faq module derives tenancy from the support layer, never a concrete tenant provider')
    ->expect('Misaf\VendraFaq')
    ->not->toUse('Misaf\VendraTenant');

arch('the faq module integrates tags through support, never the tagger or Spatie tags modules')
    ->expect('Misaf\VendraFaq')
    ->not->toUse([
        'Misaf\VendraTagger',
        'Spatie\Tags',
    ]);
