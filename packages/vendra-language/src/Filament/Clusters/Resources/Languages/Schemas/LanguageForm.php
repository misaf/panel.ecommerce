<?php

declare(strict_types=1);

namespace Misaf\VendraLanguage\Filament\Clusters\Resources\Languages\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;
use Misaf\VendraLanguage\Models\Language;
use Misaf\VendraLanguage\Support\Locales;
use Misaf\VendraSupport\Support\TenantAwareness;

final class LanguageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('locale')
                    ->columnSpanFull()
                    ->label(__('vendra-language::attributes.locale'))
                    ->native(false)
                    ->options(fn(?Language $record): array => static::installableLocaleOptions($record))
                    ->required()
                    ->rule(Rule::in(Locales::all()))
                    ->searchable()
                    ->unique(
                        modifyRuleUsing: fn(Unique $rule): Unique => TenantAwareness::constrainUniqueRule($rule),
                    ),

                Toggle::make('status')
                    ->columnSpanFull()
                    ->default(true)
                    ->label(__('vendra-language::attributes.status'))
                    ->required(),

                Toggle::make('is_default')
                    ->columnSpanFull()
                    ->default(false)
                    ->disabled(fn(?Language $record): bool => null !== $record && $record->is_default)
                    ->label(__('vendra-language::attributes.is_default'))
                    ->required(),
            ]);
    }

    /** @return array<string, string> */
    private static function installableLocaleOptions(?Language $record): array
    {
        $installedLanguagesQuery = Language::query();

        if (null !== $record) {
            $installedLanguagesQuery->whereKeyNot($record->getKey());
        }

        $installedLocales = $installedLanguagesQuery
            ->get(['locale'])
            ->map(fn(Language $language): string => $language->locale);

        return collect(Locales::options())
            ->except($installedLocales)
            ->all();
    }
}
