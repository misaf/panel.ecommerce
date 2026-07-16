<?php

declare(strict_types=1);

use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Misaf\VendraPhone\Filament\RelationManagers\PhoneNumbersRelationManager;

it('provides a notes field', function (): void {
    $relationManager = new PhoneNumbersRelationManager();
    $schema = $relationManager->form(Schema::make($relationManager));
    $field = $schema->getFlatFields()['notes'];

    expect($field)
        ->toBeInstanceOf(Textarea::class)
        ->and($field->getColumnSpan())->toBe(['default' => 'full']);
});
