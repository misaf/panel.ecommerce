<?php

declare(strict_types=1);

namespace Misaf\VendraCurrency\Filament\Clusters\Resources\Currencies\Actions;

use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Misaf\VendraCurrency\Actions\SetSellPriceAction;
use Misaf\VendraCurrency\Models\Currency;

final class UpdateSellPriceAction
{
    public static function make(): Action
    {
        return Action::make('updateSellPrice')
            ->schema([
                TextInput::make('sell_price')
                    ->default(fn(Currency $record) => $record->sell_price)
                    ->extraInputAttributes(['dir' => 'ltr'])
                    ->inputMode('number')
                    ->label(__('vendra-currency::attributes.sell_price'))
                    ->numeric()
                    ->required(),
            ])
            ->action(function (array $data, Currency $record): void {
                $price = $data['sell_price'] ?? null;

                if ( ! is_numeric($price)) {
                    return;
                }

                $sellPrice = (new SetSellPriceAction())->execute($record, (int) $price);

                if ($sellPrice) {
                    Notification::make()
                        ->title(__('vendra-currency::messages.sell_price_changed_successfully', ['iso_code' => $record->iso_code]))
                        ->success()
                        ->send();
                }
            })
            ->label(fn(Currency $record) => __('vendra-currency::messages.update_name', ['name' => $record->name]))
            ->requiresConfirmation();
    }
}
