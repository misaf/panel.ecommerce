<?php

declare(strict_types=1);

arch()->preset()->php();
arch()->preset()->security();
arch()->preset()->laravel();

arch('the container layer knows nothing about the business domain')
    ->expect('Misaf\VendraContainer')
    ->not->toUse([
        'Misaf\VendraConsole',
        'Misaf\VendraProperty',
        'Misaf\VendraReseller',
        'Misaf\VendraSupport',
        'Misaf\VendraTenant',
    ]);

arch('the contract does not depend on any implementation')
    ->expect('Misaf\VendraContainer\Contracts')
    ->not->toUse([
        'Misaf\VendraContainer\Http',
        'Misaf\VendraContainer\Runtimes',
        'Misaf\VendraContainer\Support',
        'Misaf\VendraContainer\Testing',
    ]);

arch('only the runtimes speak HTTP')
    ->expect('Misaf\VendraContainer\Http')
    ->toOnlyBeUsedIn([
        'Misaf\VendraContainer\Providers',
        'Misaf\VendraContainer\Runtimes',
    ]);

arch('value objects depend on nothing but each other')
    ->expect('Misaf\VendraContainer\ValueObjects')
    ->not->toUse([
        'Misaf\VendraContainer\Contracts',
        'Misaf\VendraContainer\Http',
        'Misaf\VendraContainer\Runtimes',
        'Misaf\VendraContainer\Support',
        'Misaf\VendraContainer\Testing',
    ]);
