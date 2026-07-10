<?php

declare(strict_types=1);

namespace Misaf\VendraAttribute\Filament\Clusters\Resources\Attributes;

use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Misaf\VendraAttribute\Filament\Clusters\AttributesCluster;
use Misaf\VendraAttribute\Filament\Clusters\Resources\Attributes\Pages\CreateAttribute;
use Misaf\VendraAttribute\Filament\Clusters\Resources\Attributes\Pages\EditAttribute;
use Misaf\VendraAttribute\Filament\Clusters\Resources\Attributes\Pages\ListAttributes;
use Misaf\VendraAttribute\Filament\Clusters\Resources\Attributes\RelationManagers\AttributeValueRelationManager;
use Misaf\VendraAttribute\Filament\Clusters\Resources\Attributes\Schemas\AttributeForm;
use Misaf\VendraAttribute\Filament\Clusters\Resources\Attributes\Tables\AttributeTable;
use Misaf\VendraAttribute\Models\Attribute;

final class AttributeResource extends Resource
{
    protected static ?string $model = Attribute::class;

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'attributes';

    protected static ?string $cluster = AttributesCluster::class;

    public static function getBreadcrumb(): string
    {
        return trans_choice('vendra-attribute::navigation.attribute', 1);
    }

    public static function getModelLabel(): string
    {
        return trans_choice('vendra-attribute::navigation.attribute', 1);
    }

    public static function getNavigationGroup(): string
    {
        return trans_choice('vendra-attribute::navigation.attribute_management', 1);
    }

    public static function getNavigationLabel(): string
    {
        return trans_choice('vendra-attribute::navigation.attribute', 1);
    }

    public static function getPluralModelLabel(): string
    {
        return trans_choice('vendra-attribute::navigation.attributes', 1);
    }

    public static function getRelations(): array
    {
        return [
            AttributeValueRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListAttributes::route('/'),
            'create' => CreateAttribute::route('/create'),
            'edit'   => EditAttribute::route('/{record}/edit'),
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return AttributeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AttributeTable::configure($table);
    }
}
