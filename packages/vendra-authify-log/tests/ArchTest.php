<?php

declare(strict_types=1);

arch()->preset()->php();
arch()->preset()->security();
arch()->preset()->laravel();

arch('the authify-log module derives tenancy from the support layer, never a concrete tenant provider')
    ->expect('Misaf\VendraAuthifyLog')
    ->not->toUse('Misaf\VendraTenant');

arch('the authify-log module resolves the user model via auth config, never a concrete user package')
    ->expect('Misaf\VendraAuthifyLog')
    ->not->toUse('Misaf\VendraUser');
