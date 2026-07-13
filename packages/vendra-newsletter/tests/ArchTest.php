<?php

declare(strict_types=1);

arch()->preset()->php();
arch()->preset()->security();
arch()->preset()->laravel();

arch('newsletter module does not depend on vendra language')
    ->expect('Misaf\VendraNewsletter')
    ->not->toUse('Misaf\VendraLanguage');

arch('the newsletter module derives tenancy from the support layer, never a concrete tenant provider')
    ->expect('Misaf\VendraNewsletter')
    ->not->toUse('Misaf\VendraTenant');
