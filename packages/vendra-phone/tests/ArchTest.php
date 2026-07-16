<?php

declare(strict_types=1);

arch()->preset()->php();
arch()->preset()->security();
arch()->preset()->laravel();

arch('the phone module derives tenancy from the support layer')
    ->expect('Misaf\VendraPhone')
    ->not->toUse('Misaf\VendraTenant');
