<?php

declare(strict_types=1);

use Misaf\VendraApi\JsonApi\Sorting\RandomPositionSort;

it('creates random position sort fields', function (): void {
    $sort = RandomPositionSort::make('random-position');

    expect($sort->sortField())->toBe('random-position');
});
