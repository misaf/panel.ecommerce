<?php

declare(strict_types=1);

namespace Misaf\VendraCart\Filament\Clusters\Resources\Carts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

final class CartForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('vendra-cart::navigation.cart'))
                    ->schema([
                        TextInput::make('token')
                            ->columnSpanFull()
                            ->label(__('vendra-cart::attributes.token')),

                        TextInput::make('owner_label')
                            ->columnSpanFull()
                            ->label(__('vendra-cart::attributes.owner')),

                        DateTimePicker::make('expires_at')
                            ->columnSpanFull()
                            ->label(__('vendra-cart::attributes.expires_at')),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
