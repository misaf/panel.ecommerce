<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Languages\Resources\Languages;

use App\Filament\Admin\Clusters\Languages\LanguagesCluster;
use App\Filament\Admin\Clusters\Languages\Resources\Languages\Pages\CreateLanguage;
use App\Filament\Admin\Clusters\Languages\Resources\Languages\Pages\EditLanguage;
use App\Filament\Admin\Clusters\Languages\Resources\Languages\Pages\ListLanguages;
use App\Filament\Admin\Clusters\Languages\Resources\Languages\Pages\ViewLanguage;
use App\Forms\Components\DescriptionTextarea;
use App\Forms\Components\SlugTextInput;
use App\Forms\Components\StatusToggle;
use App\Tables\Columns\CreatedAtTextColumn;
use App\Tables\Columns\NameTextColumn;
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
use Filament\Forms\Components\Toggle;
use Filament\Resources\Pages\PageRegistration;
use Filament\Resources\Resource;
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
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;
use Misaf\Language\Models\Language;
use Misaf\Tenant\Models\Tenant;

final class LanguageResource extends Resource
{
    protected static ?string $model = Language::class;

    protected static ?int $navigationSort = 3;

    protected static ?string $slug = 'languages';

    /**
     * @var class-string<Cluster>|null
     */
    protected static ?string $cluster = LanguagesCluster::class;

    public static function getBreadcrumb(): string
    {
        return __('navigation.language');
    }

    public static function getModelLabel(): string
    {
        return __('navigation.language');
    }

    public static function getNavigationGroup(): string
    {
        return __('navigation.content_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('navigation.language');
    }

    public static function getPluralModelLabel(): string
    {
        return __('navigation.language');
    }

    /**
     * @return array<string, PageRegistration>
     */
    public static function getPages(): array
    {
        return [
            'index'  => ListLanguages::route('/'),
            'create' => CreateLanguage::route('/create'),
            'view'   => ViewLanguage::route('/{record}'),
            'edit'   => EditLanguage::route('/{record}/edit'),
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
                TextInput::make('iso_code')
                    ->columnSpanFull()
                    ->label(__('form.iso_code'))
                    ->required()
                    ->unique(
                        modifyRuleUsing: function (Unique $rule): void {
                            $rule->where('tenant_id', Tenant::current()->id)
                                ->withoutTrashed();
                        },
                    ),
                DescriptionTextarea::make('description'),
                SpatieMediaLibraryFileUpload::make('image')
                    ->collection('languages')
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
                ToggleColumn::make('is_default')
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
