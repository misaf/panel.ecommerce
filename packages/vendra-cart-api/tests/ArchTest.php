<?php

declare(strict_types=1);

arch()->preset()->php();
arch()->preset()->security();
arch()->preset()->laravel();

arch('the cart API derives tenancy from the cart domain, never a concrete tenant provider')
    ->expect('Misaf\VendraCartApi')
    ->not->toUse('Misaf\VendraTenant');
