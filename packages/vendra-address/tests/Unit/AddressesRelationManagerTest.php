<?php

declare(strict_types=1);

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Misaf\VendraAddress\Filament\RelationManagers\AddressesRelationManager;
use Misaf\VendraSupport\Support\Countries;

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
