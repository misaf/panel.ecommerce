<?php

declare(strict_types=1);

arch()->preset()->php();
arch()->preset()->security();
arch()->preset()->laravel();

arch('the developer logins module derives tenancy from the support layer, never a concrete tenant provider')
    ->expect('Misaf\VendraDeveloperLogins')
    ->not->toUse('Misaf\VendraTenant');

arch('the developer logins module resolves the user model via auth config, never a concrete user package')
    ->expect('Misaf\VendraDeveloperLogins')
    ->not->toUse('Misaf\VendraUser');
