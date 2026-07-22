<?php

declare(strict_types=1);

namespace App\Filament\Console\Resources\Plans;

use App\Filament\Console\Resources\Plans\Pages\CreatePlan;
use App\Filament\Console\Resources\Plans\Pages\EditPlan;
use App\Filament\Console\Resources\Plans\Pages\ListPlans;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Misaf\VendraSubscription\Enums\PeriodUnit;
use Misaf\VendraSubscription\Models\Plan;

final class PlanResource extends Resource
{
    protected static ?string $model = Plan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $slug = 'plans';

    public static function getModelLabel(): string
    {
        return __('console.plan');
    }

    public static function getPluralModelLabel(): string
    {
        return __('console.plans');
    }

    public static function getNavigationLabel(): string
    {
        return __('console.plans');
    }

    public static function getNavigationGroup(): string
    {
        return __('console.navigation_group');
    }

    public static function form(Schema $schema): Schema
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('console.name'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('max_units')
                    ->label(__('console.max_units'))
                    ->alignCenter()
                    ->sortable(),

                TextColumn::make('period')
                    ->label(__('console.period'))
                    ->state(fn(Plan $record): string => "{$record->period_count} {$record->period_unit->value}"),

                TextColumn::make('price')
                    ->label(__('console.price'))
                    ->state(fn(Plan $record): string => $record->isFree()
                        ? __('console.free')
                        : $record->price . ' ' . ($record->currency_code ?? '')),

                IconColumn::make('status')
                    ->label(__('console.status'))
                    ->boolean(),

                TextColumn::make('created_at')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->hidden(fn(Plan $record): bool => $record->isInUse()),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListPlans::route('/'),
            'create' => CreatePlan::route('/create'),
            'edit'   => EditPlan::route('/{record}/edit'),
        ];
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
