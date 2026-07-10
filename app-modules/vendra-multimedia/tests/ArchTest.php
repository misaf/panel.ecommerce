<?php

declare(strict_types=1);

arch('multimedia package uses strict types')
    ->expect('Misaf\VendraMultimedia')
    ->toUseStrictTypes();

arch('multimedia package does not use debugging functions')
    ->expect('Misaf\VendraMultimedia')
    ->not->toUse(['dd', 'dump', 'ray', 'var_dump']);
