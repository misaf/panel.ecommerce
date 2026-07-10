<?php

declare(strict_types=1);

arch('profile package uses strict types')
    ->expect('Misaf\VendraUserProfile')
    ->toUseStrictTypes();

arch('profile package does not use debugging functions')
    ->expect('Misaf\VendraUserProfile')
    ->not->toUse(['dd', 'dump', 'ray', 'var_dump']);

arch('the user profile module derives tenancy from the support layer, never a concrete tenant provider')
    ->expect('Misaf\VendraUserProfile')
    ->not->toUse('Misaf\VendraTenant');
