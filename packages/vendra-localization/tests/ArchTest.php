<?php

declare(strict_types=1);

arch()->preset()->php();
arch()->preset()->security();
arch()->preset()->laravel();

arch('localization never depends on a concrete tenant provider')
    ->expect('Misaf\VendraLocalization')
    ->not->toUse('Misaf\VendraTenant');
