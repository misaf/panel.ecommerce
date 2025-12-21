<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Permissions\Resources\Roles;

use App\Filament\Admin\Clusters\Permissions\PermissionsCluster;
use App\Filament\Admin\Clusters\Permissions\Resources\Permissions\RelationManagers\PermissionRelationManager;
use App\Filament\Admin\Clusters\Permissions\Resources\Roles\Pages\CreateRole;
use App\Filament\Admin\Clusters\Permissions\Resources\Roles\Pages\EditRole;
use App\Filament\Admin\Clusters\Permissions\Resources\Roles\Pages\ListRoles;
use App\Filament\Admin\Clusters\Permissions\Resources\Roles\Pages\ViewRole;
use App\Filament\Admin\Clusters\Users\Resources\Users\RelationManagers\UserRelationManager;
use App\Tables\Columns\CreatedAtTextColumn;
use App\Tables\Columns\NameTextColumn;
use App\Tables\Columns\UpdatedAtTextColumn;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Clusters\Cluster;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\PageRegistration;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\DateConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;
use Misaf\Permission\Models\Role;
use Misaf\Tenant\Models\Tenant;

final class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'roles';

    /**
     * @var class-string<Cluster>|null
     */
    protected static ?string $cluster = PermissionsCluster::class;

    public static function getBreadcrumb(): string
    {
        return __('navigation.role');
    }

    public static function getModelLabel(): string
    {
        return __('navigation.role');
    }

    public static function getNavigationGroup(): string
    {
        return __('navigation.user_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('navigation.role');
    }

    public static function getPluralModelLabel(): string
    {
        return __('navigation.role');
    }

    /**
     * @return array<string, PageRegistration>
     */
    public static function getPages(): array
    {
        return [
            'index'  => ListRoles::route('/'),
            'create' => CreateRole::route('/create'),
            'view'   => ViewRole::route('/{record}'),
            'edit'   => EditRole::route('/{record}/edit'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            UserRelationManager::class,
            PermissionRelationManager::class,
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state): void {
                        if (($get('guard_name') ?? '') !== Str::slug($old)) {
                            return;
                        }

                        $set('guard_name', Str::slug($state));
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

                TextInput::make('guard_name')
                    ->columnSpan(['lg' => 1])
                    ->label(__('form.guard_name'))
                    ->required(),

                Select::make('permission')
                    ->columnSpanFull()
                    ->label(__('model.permission'))
                    ->multiple()
                    ->native(false)
                    ->relationship('permissions', 'name')
                    ->preload()
                    ->searchable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('row')
                    ->label('#')
                    ->rowIndex(),

                NameTextColumn::make('name'),
                TextColumn::make('guard_name')
                    ->label(__('form.guard_name')),
                CreatedAtTextColumn::make('created_at'),
                UpdatedAtTextColumn::make('updated_at'),
            ])
            ->filters(
                [
                    QueryBuilder::make()
                        ->constraints([
                            TextConstraint::make('name'),
                            TextConstraint::make('guard_name'),
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

                    DeleteAction::make()
                        ->hidden(fn(Role $role) => in_array($role->name, ['super-admin', 'reseller'])),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
