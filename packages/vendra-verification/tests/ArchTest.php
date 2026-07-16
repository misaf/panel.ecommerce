<?php

declare(strict_types=1);

arch()->preset()->php();
arch()->preset()->security();
arch()->preset()->laravel();

arch('the verification module derives tenancy from the support layer')
    ->expect('Misaf\VendraVerification')
    ->not->toUse('Misaf\VendraTenant');
