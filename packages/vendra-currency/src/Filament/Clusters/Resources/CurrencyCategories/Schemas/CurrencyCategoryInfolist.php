<?php

declare(strict_types=1);

namespace Misaf\VendraCurrency\Filament\Clusters\Resources\CurrencyCategories\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Misaf\VendraCurrency\Models\CurrencyCategory;

final class CurrencyCategoryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')->label(__('vendra-currency::attributes.name')),
                TextEntry::make('slug')->label(__('vendra-currency::attributes.slug')),
                TextEntry::make('description')
                    ->columnSpanFull()
                    ->label(__('vendra-currency::attributes.description')),
                IconEntry::make('status')
                    ->boolean()
                    ->label(__('vendra-currency::attributes.status')),
                SpatieMediaLibraryImageEntry::make('image')
                    ->collection(CurrencyCategory::MEDIA_COLLECTION)
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
