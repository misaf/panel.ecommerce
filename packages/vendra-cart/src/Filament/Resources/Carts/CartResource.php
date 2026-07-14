<?php

declare(strict_types=1);

namespace Misaf\VendraCart\Filament\Resources\Carts;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Misaf\VendraCart\Filament\Resources\Carts\Pages\ListCarts;
use Misaf\VendraCart\Filament\Resources\Carts\Pages\ViewCart;
use Misaf\VendraCart\Filament\Resources\Carts\RelationManagers\CartItemsRelationManager;
use Misaf\VendraCart\Filament\Resources\Carts\Schemas\CartForm;
use Misaf\VendraCart\Filament\Resources\Carts\Tables\CartTable;
use Misaf\VendraCart\Models\Cart;
use Misaf\VendraSupport\Filament\Navigation\NavigationGroup;

final class CartResource extends Resource
{
    protected static ?string $model = Cart::class;

    protected static ?int $navigationSort = 3;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingCart;

    protected static ?string $slug = 'carts';

    public static function getBreadcrumb(): string
    {
        return __('vendra-cart::navigation.cart');
    }

    public static function getModelLabel(): string
    {
        return __('vendra-cart::navigation.cart');
    }

    public static function getNavigationLabel(): string
    {
        return __('vendra-cart::navigation.cart');
    }

    public static function getNavigationGroup(): string
    {
        return NavigationGroup::Sales->getLabel();
    }

    public static function getPluralModelLabel(): string
    {
        return __('vendra-cart::navigation.carts');
    }

    public static function form(Schema $schema): Schema
    {
        return CartForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CartTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            CartItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCarts::route('/'),
            'view'  => ViewCart::route('/{record}'),
        ];
    }
}
