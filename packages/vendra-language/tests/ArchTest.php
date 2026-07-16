<?php

declare(strict_types=1);

arch()->preset()->php();
arch()->preset()->security();
arch()->preset()->laravel();

arch('the language module derives tenancy from the support layer, never a concrete tenant provider')
    ->expect('Misaf\VendraLanguage')
    ->not->toUse('Misaf\VendraTenant');

arch('the language module does not depend on the removed translatable integration')
    ->expect('Misaf\VendraLanguage')
    ->not->toUse('LaraZeus\SpatieTranslatable');
