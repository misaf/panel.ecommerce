<?php

declare(strict_types=1);

arch()->preset()->php();
arch()->preset()->security();
arch()->preset()->laravel();

arch('the affiliate module derives tenancy from the support layer, never a concrete tenant provider')
    ->expect('Misaf\VendraAffiliate')
    ->not->toUse('Misaf\VendraTenant');

arch('the affiliate module integrates tags through support, never the tagger or Spatie tags modules')
    ->expect('Misaf\VendraAffiliate')
    ->not->toUse([
        'Misaf\VendraTagger',
        'Spatie\Tags',
    ]);
