<?php

declare(strict_types=1);

arch()->preset()->php();
arch()->preset()->security();
arch()->preset()->laravel();

arch('the verification module derives tenancy from the support layer, never a concrete tenant provider')
    ->expect('Misaf\VendraVerification')
    ->not->toUse('Misaf\VendraTenant');
