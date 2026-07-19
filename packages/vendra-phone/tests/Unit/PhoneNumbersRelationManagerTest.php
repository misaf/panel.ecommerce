<?php

declare(strict_types=1);

use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Misaf\VendraPhone\Database\Factories\PhoneNumberFactory;
use Misaf\VendraPhone\Filament\RelationManagers\PhoneNumbersRelationManager;
use Misaf\VendraUserProfile\Tests\Support\UserProfileModuleTestContext;

it('provides a notes field', function (): void {
    $relationManager = new PhoneNumbersRelationManager();
    $schema = $relationManager->form(Schema::make($relationManager));
    $field = $schema->getFlatFields()['notes'];

    expect($field)
        ->toBeInstanceOf(Textarea::class)
        ->and($field->getColumnSpan())->toBe(['default' => 'full']);
});

it('updates primary and verification states from table toggles', function (): void {
    UserProfileModuleTestContext::createCurrentTenant();

    $relationManager = new PhoneNumbersRelationManager();
    $table = $relationManager->table(Table::make($relationManager));
    $phoneNumber = PhoneNumberFactory::new()->createOne();
    $primaryColumn = $table->getColumn('is_primary');
    $verifiedColumn = $table->getColumn('verified_at');

    expect($primaryColumn)->toBeInstanceOf(ToggleColumn::class)
        ->and($verifiedColumn)->toBeInstanceOf(ToggleColumn::class);

    $primaryColumn->record($phoneNumber)->updateState(true);
    $verifiedColumn->record($phoneNumber)->updateState(true);

    expect($phoneNumber->refresh()->is_primary)->toBeTrue()
        ->and($phoneNumber->verified_at)->not->toBeNull();

    $verifiedColumn->record($phoneNumber)->updateState(false);

    expect($phoneNumber->refresh()->verified_at)->toBeNull();
});
