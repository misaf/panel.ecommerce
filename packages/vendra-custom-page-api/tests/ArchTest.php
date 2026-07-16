<?php

declare(strict_types=1);

arch()->preset()->php();
arch()->preset()->security();
arch()->preset()->laravel();

arch('the custom page API module inherits tenancy from domain models')
    ->expect('Misaf\VendraCustomPageApi')
    ->not->toUse('Misaf\VendraTenant');
