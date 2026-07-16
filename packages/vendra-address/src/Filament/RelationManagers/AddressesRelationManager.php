<?php

declare(strict_types=1);

namespace Misaf\VendraAddress\Filament\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Misaf\VendraSupport\Support\Countries;

final class AddressesRelationManager extends RelationManager
{
    protected static string $relationship = 'addresses';

    public static function getModelLabel(): string
    {
        return __('vendra-address::address.address');
    }

    public static function getPluralModelLabel(): string
    {
        return __('vendra-address::address.addresses');
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('vendra-address::address.addresses');
    }

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('type')->label(__('vendra-address::address.fields.type'))->required(),
            TextInput::make('label')->label(__('vendra-address::address.fields.label')),
            TextInput::make('recipient_name')->label(__('vendra-address::address.fields.recipient_name')),
            TextInput::make('organization')->label(__('vendra-address::address.fields.organization')),
            TextInput::make('line_one')->label(__('vendra-address::address.fields.line_one'))->required(),
            TextInput::make('line_two')->label(__('vendra-address::address.fields.line_two')),
            TextInput::make('line_three')->label(__('vendra-address::address.fields.line_three')),
            TextInput::make('locality')->label(__('vendra-address::address.fields.locality')),
            TextInput::make('administrative_area')->label(__('vendra-address::address.fields.administrative_area')),
            TextInput::make('postal_code')->label(__('vendra-address::address.fields.postal_code')),
            TextInput::make('sorting_code')->label(__('vendra-address::address.fields.sorting_code')),
            Select::make('country_code')
                ->label(__('vendra-address::address.fields.country_code'))
                ->native(false)
                ->options(fn(): array => Countries::options())
                ->searchable()
                ->required(),
            TextInput::make('locale')->label(__('vendra-address::address.fields.locale')),
            KeyValue::make('metadata')
                ->label(__('vendra-address::address.fields.metadata'))
                ->columnSpanFull(),
            Textarea::make('notes')
                ->label(__('vendra-address::address.fields.notes'))
                ->columnSpanFull(),
            Toggle::make('is_primary')->label(__('vendra-address::address.fields.is_primary')),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('label')->label(__('vendra-address::address.fields.label'))->default('—'),
                TextColumn::make('line_one')->label(__('vendra-address::address.fields.line_one'))->searchable(),
                TextColumn::make('locality')->label(__('vendra-address::address.fields.locality'))->searchable(),
                TextColumn::make('country_code')->label(__('vendra-address::address.fields.country_code'))->badge(),
                IconColumn::make('is_primary')->label(__('vendra-address::address.fields.is_primary'))->boolean(),
                IconColumn::make('verified_at')->label(__('vendra-address::address.fields.verified_at'))->boolean(),
            ])
            ->headerActions([CreateAction::make()])
            ->recordActions([EditAction::make(), DeleteAction::make()]);
    }
}
