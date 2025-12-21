<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Languages\Resources\LanguageTranslates;

use App\Filament\Admin\Clusters\Languages\LanguagesCluster;
use App\Filament\Admin\Clusters\Languages\Resources\LanguageTranslates\Pages\CreateLanguageTranslate;
use App\Filament\Admin\Clusters\Languages\Resources\LanguageTranslates\Pages\EditLanguageTranslate;
use App\Filament\Admin\Clusters\Languages\Resources\LanguageTranslates\Pages\ListLanguageTranslates;
use App\Filament\Admin\Clusters\Languages\Resources\LanguageTranslates\Pages\ViewLanguageTranslate;
use App\Tables\Columns\CreatedAtTextColumn;
use App\Tables\Columns\UpdatedAtTextColumn;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Clusters\Cluster;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\PageRegistration;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\DateConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;
use Filament\Tables\Table;
use Illuminate\Validation\Rules\Unique;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;
use Misaf\Language\Models\LanguageTranslate;
use Misaf\Tenant\Models\Tenant;

final class LanguageTranslateResource extends Resource
{
    use Translatable;

    protected static ?string $model = LanguageTranslate::class;

    protected static ?int $navigationSort = 4;

    protected static ?string $slug = 'translates';

    /**
     * @var class-string<Cluster>|null
     */
    protected static ?string $cluster = LanguagesCluster::class;

    public static function getBreadcrumb(): string
    {
        return __('navigation.language_translate');
    }

    public static function getModelLabel(): string
    {
        return __('navigation.language_translate');
    }

    public static function getNavigationGroup(): string
    {
        return __('navigation.content_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('navigation.language_translate');
    }

    public static function getPluralModelLabel(): string
    {
        return __('navigation.language_translate');
    }

    /**
     * @return array<string, PageRegistration>
     */
    public static function getPages(): array
    {
        return [
            'index'  => ListLanguageTranslates::route('/'),
            'create' => CreateLanguageTranslate::route('/create'),
            'view'   => ViewLanguageTranslate::route('/{record}'),
            'edit'   => EditLanguageTranslate::route('/{record}/edit'),
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('group')
                    ->autofocus()
                    ->columnSpan(['lg' => 1])
                    ->label(__('form.group'))
                    ->required(),

                TextInput::make('key')
                    ->columnSpan(['lg' => 1])
                    ->label(__('form.key'))
                    ->required()
                    ->unique(
                        modifyRuleUsing: function (Unique $rule, Get $get): void {
                            $rule->where('tenant_id', Tenant::current()->id)
                                ->where('group', $get('group'))
                                ->withoutTrashed();
                        },
                    ),

                TextInput::make('text')
                    ->columnSpanFull()
                    ->label(__('form.text'))
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('row')
                    ->label('#')
                    ->rowIndex(),

                TextColumn::make('group')
                    ->label(__('form.group'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('key')
                    ->label(__('form.key'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('text')
                    ->label(__('form.text'))
                    ->searchable()
                    ->sortable(),

                CreatedAtTextColumn::make('created_at'),

                UpdatedAtTextColumn::make('updated_at'),
            ])
            ->filters(
                [
                    QueryBuilder::make()
                        ->constraints([
                            TextConstraint::make('group'),
                            TextConstraint::make('key'),
                            TextConstraint::make('text'),
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
