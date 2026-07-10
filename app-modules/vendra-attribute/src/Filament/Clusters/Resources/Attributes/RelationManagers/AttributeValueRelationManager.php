<?php

declare(strict_types=1);

namespace Misaf\VendraAttribute\Filament\Clusters\Resources\Attributes\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Number;
use Misaf\VendraAttribute\Models\AttributeValue;

final class AttributeValueRelationManager extends RelationManager
{
    protected static string $relationship = 'values';

    protected static bool $isLazy = false;

    public static function getModelLabel(): string
    {
        return trans_choice('vendra-attribute::navigation.attribute_value', 1);
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return trans_choice('vendra-attribute::navigation.attribute_value', 1);
    }

    public function isReadOnly(): bool
    {
        return false;
    }

    public static function getBadge(Model $ownerRecord, string $pageClass): string
    {
        /** @var Collection<int, AttributeValue> $values */
        $values = $ownerRecord->getRelation('values') ?? collect();

        return (string) Number::format($values->count());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('value')
                    ->label(trans_choice('vendra-attribute::attributes.value', 1))
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('value')
                    ->label(trans_choice('vendra-attribute::attributes.value', 1))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('position')
                    ->label(trans_choice('vendra-attribute::attributes.position', 1))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->defaultSort('position', 'desc')
            ->reorderable('position', direction: 'desc');
    }
}
