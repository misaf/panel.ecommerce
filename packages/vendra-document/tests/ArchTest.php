<?php

declare(strict_types=1);

arch()->preset()->php();
arch()->preset()->security();
arch()->preset()->laravel();

arch('the document module derives tenancy from the support layer')
    ->expect('Misaf\VendraDocument')
    ->not->toUse('Misaf\VendraTenant');
