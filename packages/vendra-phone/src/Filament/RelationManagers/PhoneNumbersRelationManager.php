<?php

declare(strict_types=1);

namespace Misaf\VendraPhone\Filament\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\ColumnGroup;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\Layout\Component as LayoutComponent;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Ysfkaya\FilamentPhoneInput\PhoneInputNumberType;
use Ysfkaya\FilamentPhoneInput\Tables\PhoneColumn;

final class PhoneNumbersRelationManager extends RelationManager
{
    protected static string $relationship = 'phoneNumbers';

    public static function getModelLabel(): string
    {
        return __('vendra-phone::phone.phone_number');
    }

    public static function getPluralModelLabel(): string
    {
        return __('vendra-phone::phone.phone_numbers');
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('vendra-phone::phone.phone_numbers');
    }

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('type')->label(__('vendra-phone::phone.fields.type'))->required(),
            TextInput::make('label')->label(__('vendra-phone::phone.fields.label')),
            PhoneInput::make('number')
                ->label(__('vendra-phone::phone.fields.number'))
                ->countryStatePath('country_code')
                ->displayNumberFormat(PhoneInputNumberType::INTERNATIONAL)
                ->inputNumberFormat(PhoneInputNumberType::E164)
                ->strictMode()
                ->required(),
            TextInput::make('extension')->label(__('vendra-phone::phone.fields.extension')),
            KeyValue::make('metadata')
                ->label(__('vendra-phone::phone.fields.metadata'))
                ->columnSpanFull(),
            Textarea::make('notes')
                ->label(__('vendra-phone::phone.fields.notes'))
                ->columnSpanFull(),
            Toggle::make('is_primary')->label(__('vendra-phone::phone.fields.is_primary')),
        ]);
    }

    public function table(Table $table): Table
    {
        /** @var array<int, Column|ColumnGroup|LayoutComponent> $columns */
        $columns = [
            TextColumn::make('label')->label(__('vendra-phone::phone.fields.label'))->default('—'),
            TextColumn::make('type')->label(__('vendra-phone::phone.fields.type'))->badge(),
            PhoneColumn::make('number')
                ->label(__('vendra-phone::phone.fields.number'))
                ->countryColumn('country_code')
                ->displayFormat(PhoneInputNumberType::INTERNATIONAL),
            TextColumn::make('extension')->label(__('vendra-phone::phone.fields.extension')),
            IconColumn::make('is_primary')->label(__('vendra-phone::phone.fields.is_primary'))->boolean(),
            IconColumn::make('verified_at')->label(__('vendra-phone::phone.fields.verified_at'))->boolean(),
        ];

        return $table
            ->columns($columns)
            ->headerActions([CreateAction::make()])
            ->recordActions([EditAction::make(), DeleteAction::make()]);
    }
}
