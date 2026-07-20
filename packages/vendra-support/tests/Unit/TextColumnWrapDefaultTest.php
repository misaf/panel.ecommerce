<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Tests\Unit;

use Filament\Tables\Columns\TextColumn;

it('wraps text columns by default so descriptions stay readable', function (): void {
    expect(TextColumn::make('name')->canWrap())->toBeTrue();
});

it('lets a column opt out of wrapping explicitly', function (): void {
    expect(TextColumn::make('name')->wrap(false)->canWrap())->toBeFalse();
});
