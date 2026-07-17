<?php

declare(strict_types=1);

namespace Misaf\VendraCurrency\Filament\Clusters\Resources\Currencies\Actions;

use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Misaf\VendraCurrency\Actions\SetBuyPriceAction;
use Misaf\VendraCurrency\Models\Currency;

final class UpdateBuyPriceAction
{
    public static function make(): Action
    {
        return Action::make('updateBuyPrice')
            ->schema([
                TextInput::make('buy_price')
                    ->default(fn(Currency $record) => $record->buy_price)
                    ->extraInputAttributes(['dir' => 'ltr'])
                    ->label(__('vendra-currency::attributes.buy_price'))
                    ->inputMode('number')
                    ->numeric()
                    ->required(),
            ])
            ->action(function (Currency $record, array $data): void {
                $price = $data['buy_price'] ?? null;

                if ( ! is_numeric($price)) {
                    return;
                }

                $buyPrice = (new SetBuyPriceAction())->execute($record, (int) $price);

                if ($buyPrice) {
                    Notification::make()
                        ->title(__('vendra-currency::messages.buy_price_changed_successfully', ['iso_code' => $record->iso_code]))
                        ->success()
                        ->send();
                }
            })
            ->label(fn(Currency $record) => __('vendra-currency::messages.update_name', ['name' => $record->name]))
            ->requiresConfirmation();
    }
}
