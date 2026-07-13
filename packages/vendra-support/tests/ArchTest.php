<?php

declare(strict_types=1);

arch()->preset()->php();
arch()->preset()->security();
arch()->preset()->laravel();

arch('support never depends on a concrete tenant provider')
    ->expect('Misaf\VendraSupport')
    ->not->toUse('Misaf\VendraTenant');
