<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Currencies\Resources\Currencies\Schemas;

use App\Forms\Components\DescriptionTextarea;
use App\Forms\Components\SlugTextInput;
use App\Forms\Components\StatusToggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;
use Misaf\Tenant\Models\Tenant;

final class CurrencyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('currency_category_id')
                    ->columnSpanFull()
                    ->label(__('model.currency_category'))
                    ->native(false)
                    ->preload()
                    ->relationship('currencyCategory', 'name')
                    ->required()
                    ->searchable(),

                TextInput::make('name')
                    ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state): void {
                        if (($get('slug') ?? '') === Str::slug($old ?? '')) {
                            $set('slug', Str::slug($state));
                        }
                    })
                    ->autofocus()
                    ->columnSpan(['lg' => 1])
                    ->label(__('form.name'))
                    ->live(onBlur: true)
                    ->required()
                    ->unique(
                        modifyRuleUsing: function (Unique $rule): void {
                            $rule->where('tenant_id', Tenant::current()->id)
                                ->withoutTrashed();
                        },
                    ),

                SlugTextInput::make('slug'),

                Fieldset::make('currency_setting')
                    ->columns(3)
                    ->label(__('form.currency_setting'))
                    ->schema([
                        TextInput::make('iso_code')
                            ->columnSpan([
                                'lg' => 1,
                            ])
                            ->extraInputAttributes(['dir' => 'ltr'])
                            ->label(__('form.iso_code'))
                            ->required(),

                        TextInput::make('conversion_rate')
                            ->columnSpan([
                                'lg' => 1,
                            ])
                            ->extraInputAttributes(['dir' => 'ltr'])
                            // ->inputMode('decimal')
                            ->label(__('form.conversion_rate'))
                            ->required(),

                        TextInput::make('decimal_place')
                            ->columnSpan([
                                'lg' => 1,
                            ])
                            ->extraInputAttributes(['dir' => 'ltr'])
                            ->inputMode('decimal')
                            ->label(__('form.decimal_place'))
                            ->required(),
                    ]),

                Fieldset::make('exchange_setting')
                    ->columns(2)
                    ->label(__('form.exchange_setting'))
                    ->schema([
                        TextInput::make('buy_price')
                            ->columnSpan([
                                'lg' => 1,
                            ])
                            ->extraInputAttributes(['dir' => 'ltr'])
                            ->label(__('form.buy_price'))
                            ->required()
                            ->numeric(),

                        TextInput::make('sell_price')
                            ->columnSpan([
                                'lg' => 1,
                            ])
                            ->extraInputAttributes(['dir' => 'ltr'])
                            ->label(__('form.sell_price'))
                            ->required()
                            ->numeric(),
                    ]),

                DescriptionTextarea::make('description'),

                SpatieMediaLibraryFileUpload::make('image')
                    ->columnSpanFull()
                    ->image()
                    ->label(__('form.image'))
                    ->panelLayout('grid'),

                Toggle::make('is_default')
                    ->columnSpanFull()
                    ->label(__('form.is_default'))
                    ->rules('required'),

                StatusToggle::make('status'),
            ]);
    }
}
