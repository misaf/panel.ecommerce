<?php

declare(strict_types=1);

arch()->preset()->php();
arch()->preset()->security();
arch()->preset()->laravel();

arch('the cart module derives tenancy from support, never a concrete tenant provider')
    ->expect('Misaf\VendraCart')
    ->not->toUse('Misaf\VendraTenant');

arch('the cart module accepts sellables from any catalog implementation')
    ->expect('Misaf\VendraCart')
    ->not->toUse('Misaf\VendraProduct');
