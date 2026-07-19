<?php

declare(strict_types=1);

namespace Misaf\VendraCurrency\Filament\Clusters\Resources\Currencies\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;
use Livewire\Component as Livewire;
use Misaf\VendraCurrency\Models\Currency;
use Misaf\VendraSupport\Support\TenantAwareness;

final class CurrencyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('currency_category_id')
                    ->columnSpanFull()
                    ->label(__('vendra-currency::navigation.currency_category'))
                    ->native(false)
                    ->preload()
                    ->relationship('currencyCategory', 'name')
                    ->required()
                    ->searchable(),

                TextInput::make('name')
                    ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state): void {
                        if (($get->string('slug', isNullable: true) ?? '') === Str::slug($old ?? '')) {
                            $set('slug', Str::slug($state ?? ''));
                        }
                    })
                    ->autofocus()
                    ->columnSpan(['lg' => 1])
                    ->label(__('vendra-currency::attributes.name'))
                    ->live(onBlur: true)
                    ->maxLength(255)
                    ->required(),

                TextInput::make('slug')
                    ->afterStateUpdated(fn(Livewire $livewire) => $livewire->validateOnly('data.slug'))
                    ->columnSpan(['lg' => 1])
                    ->helperText(__('vendra-currency::attributes.slug_helper_text'))
                    ->label(__('vendra-currency::attributes.slug'))
                    ->maxLength(255)
                    ->required()
                    ->unique(
                        modifyRuleUsing: fn(Unique $rule): Unique => TenantAwareness::constrainUniqueRule($rule)
                            ->withoutTrashed(),
                    ),

                Fieldset::make('currency_setting')
                    ->columns(3)
                    ->label(__('vendra-currency::attributes.currency_setting'))
                    ->schema([
                        TextInput::make('iso_code')
                            ->columnSpan([
                                'lg' => 1,
                            ])
                            ->extraInputAttributes(['dir' => 'ltr'])
                            ->label(__('vendra-currency::attributes.iso_code'))
                            ->length(3)
                            ->required()
                            ->rules(['alpha:ascii']),

                        TextInput::make('conversion_rate')
                            ->columnSpan([
                                'lg' => 1,
                            ])
                            ->extraInputAttributes(['dir' => 'ltr'])
                            ->inputMode('decimal')
                            ->label(__('vendra-currency::attributes.conversion_rate'))
                            ->minValue(0)
                            ->numeric()
                            ->required()
                            ->rules(['decimal:0,8']),

                        TextInput::make('decimal_place')
                            ->columnSpan([
                                'lg' => 1,
                            ])
                            ->extraInputAttributes(['dir' => 'ltr'])
                            ->inputMode('numeric')
                            ->integer()
                            ->label(__('vendra-currency::attributes.decimal_place'))
                            ->maxValue(255)
                            ->minValue(0)
                            ->required(),
                    ]),

                Fieldset::make('exchange_setting')
                    ->columns(2)
                    ->label(__('vendra-currency::attributes.exchange_setting'))
                    ->schema([
                        TextInput::make('buy_price')
                            ->columnSpan([
                                'lg' => 1,
                            ])
                            ->extraInputAttributes(['dir' => 'ltr'])
                            ->inputMode('numeric')
                            ->label(__('vendra-currency::attributes.buy_price'))
                            ->required()
                            ->numeric(),

                        TextInput::make('sell_price')
                            ->columnSpan([
                                'lg' => 1,
                            ])
                            ->extraInputAttributes(['dir' => 'ltr'])
                            ->inputMode('numeric')
                            ->label(__('vendra-currency::attributes.sell_price'))
                            ->required()
                            ->numeric(),
                    ]),

                Textarea::make('description')
                    ->columnSpanFull()
                    ->label(__('vendra-currency::attributes.description'))
                    ->maxLength(255)
                    ->required()
                    ->rows(5),

                SpatieMediaLibraryFileUpload::make('image')
                    ->collection(Currency::MEDIA_COLLECTION)
                    ->columnSpanFull()
                    ->image()
                    ->label(__('vendra-currency::attributes.image'))
                    ->panelLayout('grid')
                    ->responsiveImages(),

                Toggle::make('is_default')
                    ->afterStateUpdated(fn(Livewire $livewire) => $livewire->validateOnly('data.is_default'))
                    ->columnSpanFull()
                    ->default(false)
                    ->label(__('vendra-currency::attributes.is_default'))
                    ->onIcon(Heroicon::Bolt)
                    ->required()
                    ->rules([
                        'boolean',
                    ]),

                Toggle::make('status')
                    ->afterStateUpdated(fn(Livewire $livewire) => $livewire->validateOnly('data.status'))
                    ->columnSpanFull()
                    ->default(false)
                    ->label(__('vendra-currency::attributes.status'))
                    ->onIcon(Heroicon::Bolt)
                    ->required()
                    ->rules([
                        'boolean',
                    ]),
            ]);
    }
}
