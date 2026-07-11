<?php

declare(strict_types=1);

arch()->preset()->php();
arch()->preset()->security();
arch()->preset()->laravel();

arch('testing utilities never depend on a concrete tenant provider')
    ->expect('Misaf\VendraTesting')
    ->not->toUse('Misaf\VendraTenant');
