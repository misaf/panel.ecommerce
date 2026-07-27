<?php

declare(strict_types=1);

use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;

arch()->preset()->php();
arch()->preset()->security();
arch()->preset()->laravel();

arch('the affiliate module derives tenancy from the support layer, never a concrete tenant provider')
    ->expect('Misaf\VendraAffiliate')
    ->not->toUse('Misaf\VendraTenant');

arch('the affiliate module reaches currency only through the transaction wallet layer, never the currency module')
    ->expect('Misaf\VendraAffiliate')
    ->not->toUse('Misaf\VendraCurrency');

arch('the affiliate module integrates tags through support, never the tagger or Spatie tags modules')
    ->expect('Misaf\VendraAffiliate')
    ->not->toUse([
        'Misaf\VendraTagger',
        'Spatie\Tags',
    ]);

arch('affiliate state events dispatch after database commits')
    ->expect('Misaf\VendraAffiliate\Events')
    ->toImplement(ShouldDispatchAfterCommit::class);
