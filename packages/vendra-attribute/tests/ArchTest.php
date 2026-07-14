<?php

declare(strict_types=1);

arch()->preset()->php();
arch()->preset()->security();
arch()->preset()->laravel();

arch('the attribute module is independent of product and concrete tenant providers')
    ->expect('Misaf\VendraAttribute')
    ->not->toUse([
        'Misaf\VendraProduct',
        'Misaf\VendraTenant',
    ]);

arch('the attribute module integrates tags through support, never the tagger or Spatie tags modules')
    ->expect('Misaf\VendraAttribute')
    ->not->toUse([
        'Misaf\VendraTagger',
        'Spatie\Tags',
    ]);
