<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Users\Resources\UserLevelHistories;

use App\Filament\Admin\Clusters\Users\Resources\UserLevelHistories\Pages\CreateUserLevelHistory;
use App\Filament\Admin\Clusters\Users\Resources\UserLevelHistories\Pages\EditUserLevelHistory;
use App\Filament\Admin\Clusters\Users\Resources\UserLevelHistories\Pages\ListUserLevelHistories;
use App\Filament\Admin\Clusters\Users\Resources\UserLevelHistories\Pages\ViewUserLevelHistory;
use App\Filament\Admin\Clusters\Users\Resources\UserLevelHistories\RelationManagers\UserLevelHistoryRelationManager;
use App\Filament\Admin\Clusters\Users\UsersCluster;
use App\Tables\Columns\CreatedAtTextColumn;
use App\Tables\Columns\ModelLinkColumn;
use App\Tables\Columns\UpdatedAtTextColumn;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Clusters\Cluster;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\PageRegistration;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\NumberConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint\Operators\IsRelatedToOperator;
use Filament\Tables\Table;
use Illuminate\Validation\Rules\Unique;
use Misaf\Tenant\Models\Tenant;
use Misaf\User\Models\User;
use Misaf\UserLevel\Models\UserLevel;
use Misaf\UserLevel\Models\UserLevelHistory;

final class UserLevelHistoryResource extends Resource
{
    protected static ?string $model = UserLevelHistory::class;

    protected static ?int $navigationSort = 7;

    protected static ?string $slug = 'users/level-histories';

    /**
     * @var class-string<Cluster>|null
     */
    protected static ?string $cluster = UsersCluster::class;

    public static function getBreadcrumb(): string
    {
        return __('user-level::navigation.user_level_history');
    }

    public static function getModelLabel(): string
    {
        return __('user-level::navigation.user_level_history');
    }

    public static function getNavigationGroup(): string
    {
        return __('navigation.user_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('user-level::navigation.user_level_history');
    }

    public static function getPluralModelLabel(): string
    {
        return __('user-level::navigation.user_level_history');
    }

    /**
     * @return array<string, PageRegistration>
     */
    public static function getPages(): array
    {
        return [
            'index'  => ListUserLevelHistories::route('/'),
            'create' => CreateUserLevelHistory::route('/create'),
            'view'   => ViewUserLevelHistory::route('/{record}'),
            'edit'   => EditUserLevelHistory::route('/{record}/edit'),
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->columnSpanFull()
                    ->label(__('model.user'))
                    ->native(false)
                    ->preload()
                    ->relationship('user', 'username')
                    ->required()
                    ->searchable()
                    ->unique(
                        modifyRuleUsing: function (Unique $rule): void {
                            $rule->where('tenant_id', Tenant::current()->id);
                        },
                    )
                    ->hiddenOn([
                        UserLevelHistoryRelationManager::class,
                    ]),
                Select::make('user_level_id')
                    ->columnSpanFull()
                    ->label(__('user-level::attributes.name'))
                    ->native(false)
                    ->preload()
                    ->relationship('userLevel', 'name')
                    ->required()
                    ->searchable()
                    ->unique(
                        modifyRuleUsing: function (Unique $rule): void {
                            $rule->where('tenant_id', Tenant::current()->id);
                        },
                    ),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('row')
                    ->label('#')
                    ->rowIndex(),

                ModelLinkColumn::make('user.username')
                    ->label(__('model.user'))
                    ->searchable()
                    ->sortable(),
                ModelLinkColumn::make('userLevel.name')
                    ->label(__('user-level::attributes.name'))
                    ->searchable()
                    ->sortable(),
                ModelLinkColumn::make('userLevel.min_points')
                    ->label(__('user-level::attributes.min_points'))
                    ->numeric()
                    ->sortable(),
                CreatedAtTextColumn::make('created_at'),
                UpdatedAtTextColumn::make('updated_at'),
            ])
            ->filters(
                [
                    QueryBuilder::make()
                        ->constraints([
                            RelationshipConstraint::make('user')
                                ->label(__('user::attributes.username'))
                                ->selectable(
                                    IsRelatedToOperator::make()
                                        ->getOptionLabelFromRecordUsing(function (User $record) {
                                            return $record->getAttributeValue('username');
                                        })
                                        ->preload()
                                        ->searchable()
                                        ->titleAttribute('username'),
                                ),
                            RelationshipConstraint::make('userLevel')
                                ->label(__('user-level::attributes.name'))
                                ->selectable(
                                    IsRelatedToOperator::make()
                                        ->getOptionLabelFromRecordUsing(function (UserLevel $record) {
                                            return $record->getAttributeValue('name');
                                        })
                                        ->preload()
                                        ->searchable()
                                        ->titleAttribute('name'),
                                ),
                            NumberConstraint::make('min_points')
                                ->label(__('user-level::attributes.min_points')),
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
            ]);
    }
}
