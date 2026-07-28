<?php

declare(strict_types=1);

it('does not expose unsupported Pennant feature discovery', function (): void {
    expect(config('vendra-permission.features'))
        ->not->toHaveKey('discover');
});
