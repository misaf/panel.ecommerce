<?php

declare(strict_types=1);

namespace Misaf\VendraLanguage\Filament\Clusters\Resources\Languages\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;
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
                    ->options(fn(): array => Locales::names(static::catalog()))
                    ->required()
                    ->rule(Rule::in(static::catalog()))
                    ->searchable()
                    ->unique(
                        ignoreRecord: true,
                        modifyRuleUsing: fn(Unique $rule): Unique => TenantAwareness::constrainUniqueRule($rule),
                    ),

                Toggle::make('is_default')
                    ->columnSpanFull()
                    ->default(false)
                    ->label(__('vendra-language::attributes.is_default'))
                    ->required(),
            ]);
    }

    /**
     * The platform-supported locales a tenant may enable.
     *
     * @return array<int, string>
     */
    private static function catalog(): array
    {
        return Locales::configured();
    }
}
