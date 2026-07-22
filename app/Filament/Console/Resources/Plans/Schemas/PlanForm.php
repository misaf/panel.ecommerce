<?php

declare(strict_types=1);

namespace App\Filament\Console\Resources\Plans\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Misaf\VendraSubscription\Enums\PeriodUnit;

final class PlanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('console.name'))
                    ->maxLength(255)
                    ->required(),

                TextInput::make('max_units')
                    ->label(__('console.max_units'))
                    ->integer()
                    ->minValue(1)
                    ->required(),

                Select::make('period_unit')
                    ->label(__('console.period_unit'))
                    ->native(false)
                    ->options(self::periodUnitOptions())
                    ->required(),

                TextInput::make('period_count')
                    ->label(__('console.period_count'))
                    ->integer()
                    ->minValue(1)
                    ->required(),

                TextInput::make('grace_days')
                    ->label(__('console.grace_days'))
                    ->integer()
                    ->minValue(0)
                    ->default(0)
                    ->required(),

                TextInput::make('price')
                    ->label(__('console.price'))
                    ->helperText(__('console.price_hint'))
                    ->integer()
                    ->minValue(0)
                    ->default(0)
                    ->live()
                    ->required(),

                TextInput::make('currency_code')
                    ->label(__('console.currency'))
                    ->length(3)
                    ->alpha()
                    ->required(function (Get $get): bool {
                        $price = $get('price');

                        if (is_int($price) || is_float($price)) {
                            return $price > 0;
                        }

                        return is_string($price) && (int) $price > 0;
                    })
                    ->dehydrateStateUsing(fn(?string $state): ?string => filled($state) ? Str::upper($state) : null)
                    ->placeholder('USD'),

                TextInput::make('trial_days')
                    ->label(__('console.trial_days'))
                    ->integer()
                    ->minValue(0)
                    ->default(0)
                    ->required(),

                Textarea::make('description')
                    ->label(__('console.description'))
                    ->columnSpanFull()
                    ->maxLength(1000),

                Toggle::make('status')
                    ->label(__('console.status'))
                    ->columnSpanFull()
                    ->default(true)
                    ->required(),
            ])
            ->columns(2);
    }

    /**
     * @return array<string, string>
     */
    private static function periodUnitOptions(): array
    {
        return collect(PeriodUnit::cases())
            ->mapWithKeys(fn(PeriodUnit $unit): array => [$unit->value => ucfirst($unit->value)])
            ->all();
    }
}
