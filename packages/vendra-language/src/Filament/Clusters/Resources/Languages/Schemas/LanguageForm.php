<?php

declare(strict_types=1);

namespace Misaf\VendraLanguage\Filament\Clusters\Resources\Languages\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;
use Livewire\Component as Livewire;
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
                    ->afterStateUpdated(fn(Livewire $livewire) => $livewire->validateOnly('data.locale'))
                    ->columnSpanFull()
                    ->label(__('vendra-language::attributes.locale'))
                    ->live()
                    ->native(false)
                    ->options(fn(?Language $record): array => static::installableLocaleOptions($record))
                    ->required()
                    ->rule(Rule::in(Locales::all()))
                    ->searchable()
                    ->unique(
                        modifyRuleUsing: fn(Unique $rule): Unique => TenantAwareness::constrainUniqueRule($rule),
                    ),

                Toggle::make('status')
                    ->afterStateUpdated(fn(Livewire $livewire) => $livewire->validateOnly('data.status'))
                    ->columnSpanFull()
                    ->default(false)
                    ->label(__('vendra-language::attributes.status'))
                    ->live()
                    ->onIcon(Heroicon::Bolt)
                    ->required()
                    ->rules(['boolean']),

                Toggle::make('is_default')
                    ->afterStateUpdated(fn(Livewire $livewire) => $livewire->validateOnly('data.is_default'))
                    ->columnSpanFull()
                    ->default(false)
                    ->helperText(__('vendra-language::attributes.is_default_helper_text'))
                    ->label(__('vendra-language::attributes.is_default'))
                    ->live()
                    ->onIcon(Heroicon::Bolt)
                    ->required()
                    ->rules(['boolean']),
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
