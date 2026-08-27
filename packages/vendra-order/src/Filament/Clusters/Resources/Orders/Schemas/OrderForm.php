<?php

declare(strict_types=1);

namespace Misaf\VendraOrder\Filament\Clusters\Resources\Orders\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

final class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('vendra-order::navigation.order'))
                    ->schema([
                        TextInput::make('number')
                            ->label(__('vendra-order::attributes.number')),

                        TextInput::make('customer_label')
                            ->label(__('vendra-order::attributes.customer')),

                        TextInput::make('payment_reference')
                            ->label(__('vendra-order::attributes.payment_reference')),

                        TextInput::make('total_amount')
                            ->label(__('vendra-order::attributes.total_amount')),

                        Textarea::make('card_message')
                            ->columnSpanFull()
                            ->label(__('vendra-order::attributes.card_message'))
                            ->rows(3),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
