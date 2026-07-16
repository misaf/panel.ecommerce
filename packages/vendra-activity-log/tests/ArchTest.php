<?php

declare(strict_types=1);

arch()->preset()->php();
arch()->preset()->security();
arch()->preset()->laravel();

arch('the activity log module derives tenancy from the support layer, never a concrete tenant provider')
    ->expect('Misaf\VendraActivityLog')
    ->not->toUse('Misaf\VendraTenant');
