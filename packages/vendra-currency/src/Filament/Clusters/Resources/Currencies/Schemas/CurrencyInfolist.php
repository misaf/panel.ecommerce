<?php

declare(strict_types=1);

namespace Misaf\VendraCurrency\Filament\Clusters\Resources\Currencies\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Misaf\VendraCurrency\Models\Currency;

final class CurrencyInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('currencyCategory.name')
                    ->label(__('vendra-currency::navigation.currency_category')),
                TextEntry::make('name')->label(__('vendra-currency::attributes.name')),
                TextEntry::make('slug')->label(__('vendra-currency::attributes.slug')),
                TextEntry::make('iso_code')
                    ->badge()
                    ->label(__('vendra-currency::attributes.iso_code')),
                TextEntry::make('conversion_rate')
                    ->label(__('vendra-currency::attributes.conversion_rate'))
                    ->numeric(locale: 'en'),
                TextEntry::make('decimal_place')
                    ->label(__('vendra-currency::attributes.decimal_place')),
                TextEntry::make('buy_price')
                    ->label(__('vendra-currency::attributes.buy_price'))
                    ->numeric(locale: 'en'),
                TextEntry::make('sell_price')
                    ->label(__('vendra-currency::attributes.sell_price'))
                    ->numeric(locale: 'en'),
                IconEntry::make('is_default')
                    ->boolean()
                    ->label(__('vendra-currency::attributes.is_default')),
                IconEntry::make('status')
                    ->boolean()
                    ->label(__('vendra-currency::attributes.status')),
                TextEntry::make('description')
                    ->columnSpanFull()
                    ->label(__('vendra-currency::attributes.description')),
                SpatieMediaLibraryImageEntry::make('image')
                    ->collection(Currency::MEDIA_COLLECTION)
                    ->columnSpanFull()
                    ->label(__('vendra-currency::attributes.image')),
                self::dateEntry('created_at'),
                self::dateEntry('updated_at'),
            ])
            ->columns(2);
    }

    private static function dateEntry(string $name): TextEntry
    {
        return TextEntry::make($name)
            ->label(__("vendra-currency::attributes.{$name}"))
            ->when(
                app()->isLocale('fa'),
                fn(TextEntry $entry): TextEntry => $entry->jalaliDateTime('Y-m-d H:i', latinNumbers: true),
                fn(TextEntry $entry): TextEntry => $entry->dateTime('Y-m-d H:i'),
            );
    }
}
