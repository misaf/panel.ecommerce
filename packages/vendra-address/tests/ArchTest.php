<?php

declare(strict_types=1);

arch()->preset()->php();
arch()->preset()->security();
arch()->preset()->laravel();

arch('the address module derives tenancy from the support layer, never a concrete tenant provider')
    ->expect('Misaf\VendraAddress')
    ->not->toUse('Misaf\VendraTenant');
