<?php

declare(strict_types=1);

use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Misaf\VendraDocument\Filament\RelationManagers\DocumentsRelationManager;
use Misaf\VendraSupport\Support\Countries;

it('uses a searchable localized country select for the issuing country', function (): void {
    app()->setLocale('fa');

    $relationManager = new DocumentsRelationManager();
    $schema = $relationManager->form(Schema::make($relationManager));
    $field = $schema->getFlatFields()['issuing_country_code'];

    expect($field)
        ->toBeInstanceOf(Select::class)
        ->and($field->isSearchable())->toBeTrue()
        ->and($field->getOptions())->toBe(Countries::options());
});
