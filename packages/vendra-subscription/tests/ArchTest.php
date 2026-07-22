<?php

declare(strict_types=1);

arch()->preset()->php();
arch()->preset()->security();
arch()->preset()->laravel();

arch('the subscription module does not depend on domain modules other than its sanctioned dependencies')
    ->expect('Misaf\VendraSubscription')->not->toUse([
        'Misaf\VendraTenant',
        'Misaf\VendraUser',
        'Misaf\VendraPermission',
        'Misaf\VendraProduct',
        'Misaf\VendraBlog',
        'Misaf\VendraCart',
        'Misaf\VendraAttribute',
        'Misaf\VendraCurrency',
        'Misaf\VendraTransaction',
        'Misaf\VendraNewsletter',
        'Misaf\VendraFaq',
        'Misaf\VendraCustomPage',
        'Misaf\VendraAffiliate',
        'Misaf\VendraMultimedia',
        'Misaf\VendraTagger',
        'Misaf\VendraLanguage',
        'Misaf\VendraSocialite',
        'Misaf\VendraAuthifyLog',
        'Misaf\VendraActivityLog',
        'Misaf\VendraDeveloperLogins',
        'Misaf\VendraVerification',
        'Misaf\VendraAddress',
        'Misaf\VendraDocument',
        'Misaf\VendraPhone',
        'Misaf\VendraUserProfile',
    ]);
