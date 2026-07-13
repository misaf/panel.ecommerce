<?php

declare(strict_types=1);

arch()->preset()->php();
arch()->preset()->security();
arch()->preset()->laravel();

arch('shared api infrastructure never depends on a concrete tenant provider')
    ->expect('Misaf\VendraApi')
    ->not->toUse('Misaf\VendraTenant');
