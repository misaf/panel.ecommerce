<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Currencies\Resources\Currencies;

use App\Filament\Admin\Clusters\Currencies\CurrenciesCluster;
use App\Filament\Admin\Clusters\Currencies\Resources\Currencies\Pages\CreateCurrency;
use App\Filament\Admin\Clusters\Currencies\Resources\Currencies\Pages\EditCurrency;
use App\Filament\Admin\Clusters\Currencies\Resources\Currencies\Pages\ListCurrencies;
use App\Filament\Admin\Clusters\Currencies\Resources\Currencies\Pages\ViewCurrency;
use App\Forms\Components\DescriptionTextarea;
use App\Forms\Components\SlugTextInput;
use App\Forms\Components\StatusToggle;
use App\Tables\Columns\CreatedAtTextColumn;
use App\Tables\Columns\NameTextColumn;
use App\Tables\Columns\StatusToggleColumn;
use App\Tables\Columns\UpdatedAtTextColumn;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Clusters\Cluster;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\BooleanConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\DateConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;
use Misaf\Currency\Models\Currency;
use Misaf\Tenant\Models\Tenant;

final class CurrencyResource extends Resource
{
    protected static ?string $model = Currency::class;

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'currencies';

    /**
     * @var class-string<Cluster>|null
     */
    protected static ?string $cluster = CurrenciesCluster::class;

    public static function getBreadcrumb(): string
    {
        return __('navigation.currency');
    }

    public static function getModelLabel(): string
    {
        return __('navigation.currency');
    }

    public static function getNavigationGroup(): string
    {
        return __('navigation.billing_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('navigation.currency');
    }

    public static function getPluralModelLabel(): string
    {
        return __('navigation.currency');
    }

    /**
     * @return array<string, PageRegistration>
     */
    public static function getPages(): array
    {
        return [
            'index'  => ListCurrencies::route('/'),
            'create' => CreateCurrency::route('/create'),
            'view'   => ViewCurrency::route('/{record}'),
            'edit'   => EditCurrency::route('/{record}/edit'),
        ];
    }

    public static function form(Schema $schema): Schema
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('row')
                    ->label('#')
                    ->rowIndex(),

                SpatieMediaLibraryImageColumn::make('image')
                    ->circular()
                    ->conversion('thumb-table')
                    ->extraImgAttributes(['class' => 'saturate-50', 'loading' => 'lazy'])
                    ->label(__('form.image'))
                    ->stacked()
                    ->defaultImageUrl(url('coin-payment/images/default.png')),

                NameTextColumn::make('name'),

                TextColumn::make('iso_code')
                    ->badge()
                    ->label(__('form.iso_code'))
                    ->searchable(),

                TextColumn::make('buy_price')
                    ->label(__('form.buy_price'))
                    ->numeric()
                    ->action(
                        Action::make('updateBuyPrice')
                            ->schema([
                                TextInput::make('buy_price')
                                    ->extraInputAttributes(['dir' => 'ltr'])
                                    ->label(__('form.buy_price'))
                                    ->required()
                                    ->numeric(),
                            ])
                            ->action(function (array $data, Currency $record): void {
                                $record->buy_price = $data['buy_price'];
                                $record->save();
                            })
                            ->label(__('بروزرسانی %s', [__('form.buy_price')])),
                    ),

                TextColumn::make('sell_price')
                    ->label(__('form.sell_price'))
                    ->numeric()
                    ->action(
                        Action::make('updateSellPrice')
                            ->schema([
                                TextInput::make('sell_price')
                                    ->extraInputAttributes(['dir' => 'ltr'])
                                    ->label(__('form.sell_price'))
                                    ->required()
                                    ->numeric(),
                            ])
                            ->action(function (array $data, Currency $record): void {
                                $record->sell_price = $data['sell_price'];
                                $record->save();
                            })
                            ->label(__('بروزرسانی %s', [__('form.sell_price')])),
                    ),

                ToggleColumn::make('is_default')
                    ->afterStateUpdated(function (Currency $record, ?string $state): void {
                        Currency::when($state, fn(Builder $query) => $query->whereKeyNot($record->id)->where('is_default', 1))->update(['is_default' => 0]);
                    })
                    ->label(__('form.is_default'))
                    ->onIcon('heroicon-m-bolt'),

                StatusToggleColumn::make('status'),

                CreatedAtTextColumn::make('created_at'),

                UpdatedAtTextColumn::make('updated_at'),
            ])
            ->filters(
                [
                    QueryBuilder::make()
                        ->constraints([
                            TextConstraint::make('name'),
                            TextConstraint::make('iso_code'),
                            TextConstraint::make('conversion_rate'),
                            TextConstraint::make('decimal_place'),
                            BooleanConstraint::make('is_default'),
                            BooleanConstraint::make('status'),
                            DateConstraint::make('created_at')
                                ->label(__('form.created_at')),
                            DateConstraint::make('updated_at')
                                ->label(__('form.updated_at')),
                        ]),
                ],
                layout: FiltersLayout::AboveContentCollapsible,
            )
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),

                    EditAction::make(),

                    DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->paginatedWhileReordering()
            ->reorderable('position');
    }
}
