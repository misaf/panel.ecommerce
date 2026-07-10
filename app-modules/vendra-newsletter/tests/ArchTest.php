<?php

declare(strict_types=1);

arch()->preset()->php();
arch()->preset()->security();
arch()->preset()->laravel();

arch('newsletter module does not depend on vendra language')
    ->expect('Misaf\VendraNewsletter')
    ->not->toUse('Misaf\VendraLanguage');
