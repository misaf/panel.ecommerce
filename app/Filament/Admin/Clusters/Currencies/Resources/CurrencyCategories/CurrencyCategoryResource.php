<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Currencies\Resources\CurrencyCategories;

use App\Filament\Admin\Clusters\Currencies\CurrenciesCluster;
use App\Filament\Admin\Clusters\Currencies\Resources\Currencies\RelationManagers\CurrencyRelationManager;
use App\Filament\Admin\Clusters\Currencies\Resources\CurrencyCategories\Pages\CreateCurrencyCategory;
use App\Filament\Admin\Clusters\Currencies\Resources\CurrencyCategories\Pages\EditCurrencyCategory;
use App\Filament\Admin\Clusters\Currencies\Resources\CurrencyCategories\Pages\ListCurrencyCategories;
use App\Filament\Admin\Clusters\Currencies\Resources\CurrencyCategories\Pages\ViewCurrencyCategory;
use App\Forms\Components\DescriptionTextarea;
use App\Forms\Components\SlugTextInput;
use App\Forms\Components\StatusToggle;
use App\Tables\Columns\CreatedAtTextColumn;
use App\Tables\Columns\StatusToggleColumn;
use App\Tables\Columns\UpdatedAtTextColumn;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Clusters\Cluster;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\DateConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;
use Misaf\Currency\Models\CurrencyCategory;
use Misaf\Tenant\Models\Tenant;

final class CurrencyCategoryResource extends Resource
{
    protected static ?string $model = CurrencyCategory::class;

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'categories';

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
        return __('navigation.currency_category');
    }

    public static function getNavigationGroup(): string
    {
        return __('navigation.billing_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('navigation.currency_category');
    }

    public static function getPluralModelLabel(): string
    {
        return __('navigation.currency_category');
    }

    /**
     * @return array<string, PageRegistration>
     */
    public static function getPages(): array
    {
        return [
            'index'  => ListCurrencyCategories::route('/'),
            'create' => CreateCurrencyCategory::route('/create'),
            'view'   => ViewCurrencyCategory::route('/{record}'),
            'edit'   => EditCurrencyCategory::route('/{record}/edit'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            CurrencyRelationManager::class,
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
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
                DescriptionTextarea::make('description'),
                SpatieMediaLibraryFileUpload::make('image')
                    ->collection('currencies/categories')
                    ->columnSpanFull()
                    ->image()
                    ->label(__('form.image'))
                    ->panelLayout('grid'),

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
                    ->defaultImageUrl(url('coin-payment/images/default.png'))
                    ->extraImgAttributes(['class' => 'saturate-50', 'loading' => 'lazy'])
                    ->label(__('form.image'))
                    ->stacked(),

                TextColumn::make('name')
                    ->label(__('form.name'))
                    ->description(fn(CurrencyCategory $record): string => $record->description)
                    ->searchable(),

                StatusToggleColumn::make('status'),
                CreatedAtTextColumn::make('created_at'),
                UpdatedAtTextColumn::make('updated_at'),
            ])
            ->filters(
                [
                    QueryBuilder::make()
                        ->constraints([
                            TextConstraint::make('name'),
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
            ]);
    }
}
