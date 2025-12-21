<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Users\Resources\UserLevels;

use App\Filament\Admin\Clusters\Users\Resources\UserLevelHistories\RelationManagers\UserLevelHistoryRelationManager;
use App\Filament\Admin\Clusters\Users\Resources\UserLevels\Pages\CreateUserLevel;
use App\Filament\Admin\Clusters\Users\Resources\UserLevels\Pages\EditUserLevel;
use App\Filament\Admin\Clusters\Users\Resources\UserLevels\Pages\ListUserLevels;
use App\Filament\Admin\Clusters\Users\Resources\UserLevels\Pages\ViewUserLevel;
use App\Filament\Admin\Clusters\Users\UsersCluster;
use App\Forms\Components\SlugTextInput;
use App\Forms\Components\StatusToggle;
use App\Forms\Components\WysiwygEditor;
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
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\PageRegistration;
use Filament\Resources\RelationManagers\RelationGroup;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\RelationManagers\RelationManagerConfiguration;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\BooleanConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\DateConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\NumberConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;
use Misaf\Tenant\Models\Tenant;
use Misaf\UserLevel\Models\UserLevel;

final class UserLevelResource extends Resource
{
    protected static ?string $model = UserLevel::class;

    protected static ?int $navigationSort = 6;

    protected static ?string $slug = 'users/levels';

    /**
     * @var class-string<Cluster>|null
     */
    protected static ?string $cluster = UsersCluster::class;

    public static function getBreadcrumb(): string
    {
        return __('user-level::navigation.user_level');
    }

    public static function getModelLabel(): string
    {
        return __('user-level::navigation.user_level');
    }

    public static function getNavigationGroup(): string
    {
        return __('navigation.user_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('user-level::navigation.user_level');
    }

    public static function getPluralModelLabel(): string
    {
        return __('user-level::navigation.user_level');
    }

    /**
     * @return array<string, PageRegistration>
     */
    public static function getPages(): array
    {
        return [
            'index'  => ListUserLevels::route('/'),
            'create' => CreateUserLevel::route('/create'),
            'view'   => ViewUserLevel::route('/{record}'),
            'edit'   => EditUserLevel::route('/{record}/edit'),
        ];
    }

    /**
     * @return array<class-string<RelationManager>|RelationGroup|RelationManagerConfiguration>
     */
    public static function getRelations(): array
    {
        return [
            UserLevelHistoryRelationManager::class,
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
                    ->label(__('user-level::attributes.name'))
                    ->live(onBlur: true)
                    ->required()
                    ->unique(
                        modifyRuleUsing: function (Unique $rule): void {
                            $rule->where('tenant_id', Tenant::current()->id)
                                ->withoutTrashed();
                        },
                    ),
                SlugTextInput::make('slug')
                    ->label(__('user-level::attributes.slug')),
                WysiwygEditor::make('description')
                    ->label(__('user-level::attributes.description')),
                TextInput::make('min_points')
                    ->columnSpanFull()
                    ->extraInputAttributes(['dir' => 'ltr'])
                    ->label(__('user-level::attributes.min_points'))
                    ->numeric()
                    ->required()
                    ->minValue(fn() => UserLevel::where('tenant_id', Tenant::current()->id)->max('min_points') + 1),
                StatusToggle::make('status')
                    ->label(__('user-level::attributes.status'))
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

                NameTextColumn::make('name')
                    ->label(__('user-level::attributes.name')),
                TextColumn::make('min_points')
                    ->label(__('user-level::attributes.min_points'))
                    ->numeric()
                    ->action(
                        Action::make('updateMinPoints')
                            ->schema([
                                TextInput::make('min_points')
                                    ->autofocus()
                                    ->default(fn(UserLevel $record) => $record->min_points)
                                    ->extraInputAttributes(['dir' => 'ltr'])
                                    ->label(__('user-level::attributes.min_points'))
                                    ->numeric()
                                    ->required(),
                            ])
                            ->action(function (array $data, UserLevel $record): void {
                                $record->min_points = $data['min_points'];
                                $record->save();
                            })
                            ->label(__('بروزرسانی')),
                    ),
                StatusToggleColumn::make('status')
                    ->label(__('user-level::attributes.status')),
                CreatedAtTextColumn::make('created_at')
                    ->label(__('user-level::attributes.created_at')),
                UpdatedAtTextColumn::make('updated_at')
                    ->label(__('user-level::attributes.updated_at')),
            ])
            ->filters(
                [
                    QueryBuilder::make()
                        ->constraints([
                            TextConstraint::make('name')
                                ->label(__('user-level::attributes.name')),
                            NumberConstraint::make('min_points')
                                ->label(__('user-level::attributes.min_points')),
                            BooleanConstraint::make('status')
                                ->label(__('user-level::attributes.status')),
                            DateConstraint::make('created_at')
                                ->label(__('user-level::attributes.created_at')),
                            DateConstraint::make('updated_at')
                                ->label(__('user-level::attributes.updated_at')),
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
