<?php

declare(strict_types=1);

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Misaf\VendraAddress\Database\Factories\AddressFactory;
use Misaf\VendraAddress\Filament\RelationManagers\AddressesRelationManager;
use Misaf\VendraSupport\Support\Countries;
use Misaf\VendraUserProfile\Tests\Support\UserProfileModuleTestContext;

it('uses a localized country select and free-text administrative fields', function (): void {
    app()->setLocale('fa');

    $relationManager = new AddressesRelationManager();
    $schema = $relationManager->form(Schema::make($relationManager));
    $fields = $schema->getFlatFields();

    expect($fields['country_code'])
        ->toBeInstanceOf(Select::class)
        ->and($fields['country_code']->isSearchable())->toBeTrue()
        ->and($fields['country_code']->getOptions())->toBe(Countries::options())
        ->and($fields['administrative_area'])->toBeInstanceOf(TextInput::class)
        ->and($fields['locality'])->toBeInstanceOf(TextInput::class)
        ->and($fields['notes'])->toBeInstanceOf(Textarea::class)
        ->and($fields['notes']->getColumnSpan())->toBe(['default' => 'full']);
});

it('updates primary and verification states from table toggles', function (): void {
    UserProfileModuleTestContext::createCurrentTenant();

    $relationManager = new AddressesRelationManager();
    $table = $relationManager->table(Table::make($relationManager));
    $address = AddressFactory::new()->createOne();
    $primaryColumn = $table->getColumn('is_primary');
    $verifiedColumn = $table->getColumn('verified_at');

    expect($primaryColumn)->toBeInstanceOf(ToggleColumn::class)
        ->and($verifiedColumn)->toBeInstanceOf(ToggleColumn::class);

    $primaryColumn->record($address)->updateState(true);
    $verifiedColumn->record($address)->updateState(true);

    expect($address->refresh()->is_primary)->toBeTrue()
        ->and($address->verified_at)->not->toBeNull();

    $verifiedColumn->record($address)->updateState(false);

    expect($address->refresh()->verified_at)->toBeNull();
});
