<?php

declare(strict_types=1);

arch()->preset()->php();
arch()->preset()->security();
arch()->preset()->laravel();

arch('the blog API module inherits tenancy from the domain models, never a concrete tenant provider')
    ->expect('Misaf\VendraBlogApi')
    ->not->toUse('Misaf\VendraTenant');
