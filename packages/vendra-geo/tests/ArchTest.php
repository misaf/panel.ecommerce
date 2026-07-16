<?php

declare(strict_types=1);

arch()->preset()->php();
arch()->preset()->security();
arch()->preset()->laravel();

arch('the geo module derives tenancy from the support layer, never a concrete tenant provider')
    ->expect('Misaf\VendraGeo')
    ->not->toUse('Misaf\VendraTenant');

arch('geo resources use plain string attributes')
    ->expect('Misaf\VendraGeo\Filament\Clusters\Resources')
    ->not->toUse('Misaf\VendraSupport\Filament\Concerns\InteractsWithTranslatedTableRecords');

arch('geo does not use translatable model or filament concerns')
    ->expect('Misaf\VendraGeo')
    ->not->toUse([
        'LaraZeus\SpatieTranslatable',
        'Spatie\Translatable',
    ]);
