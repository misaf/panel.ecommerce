<?php

declare(strict_types=1);

namespace App\Filament\Console\Resources\Properties;

use App\Filament\Console\Resources\Properties\Pages\CreateProperty;
use App\Filament\Console\Resources\Properties\Pages\EditProperty;
use App\Filament\Console\Resources\Properties\Pages\ListProperties;
use App\Filament\Console\Resources\Properties\RelationManagers\DomainsRelationManager;
use App\Models\Reseller;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Misaf\VendraTenant\Actions\ReplaceTenantDomainAction;
use Misaf\VendraTenant\Models\Tenant;
use Misaf\VendraTenant\Models\TenantDomain;

final class PropertyResource extends Resource
{
    protected static ?string $model = Tenant::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGlobeAlt;

    protected static ?string $slug = 'properties';

    public static function getModelLabel(): string
    {
        return __('console.property');
    }

    public static function getPluralModelLabel(): string
    {
        return __('console.properties');
    }

    public static function getNavigationLabel(): string
    {
        return __('console.properties');
    }

    public static function getNavigationGroup(): string
    {
        return __('console.navigation_group');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('reseller_id')
                    ->label(__('console.reseller'))
                    ->options(fn(): array => Reseller::query()->enabled()->pluck('name', 'id')->all())
                    ->required()
                    ->native(false)
                    ->visibleOn('create'),

                TextInput::make('name')
                    ->label(__('console.name'))
                    ->maxLength(255)
                    ->required(),

                TextInput::make('domain')
                    ->label(__('console.domain'))
                    ->maxLength(255)
                    ->required()
                    ->rules(TenantDomain::activeDomainRules())
                    ->dehydrateStateUsing(fn(?string $state): ?string => null === $state
                        ? null
                        : TenantDomain::normalizeDomain($state))
                    ->visibleOn('create'),

                TextInput::make('owner_username')
                    ->label(__('console.owner_username'))
                    ->maxLength(255)
                    ->required()
                    ->visibleOn('create'),

                TextInput::make('owner_email')
                    ->label(__('console.owner_email'))
                    ->email()
                    ->maxLength(255)
                    ->required()
                    ->visibleOn('create'),

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

                TextColumn::make('reseller')
                    ->label(__('console.reseller'))
                    ->state(fn(Tenant $record): ?string => Reseller::query()->find($record->reseller_id)?->name)
                    ->placeholder('—'),

                TextColumn::make('domain')
                    ->label(__('console.domain'))
                    ->state(fn(Tenant $record): ?string => $record->activeDomainName())
                    ->placeholder('—'),

                IconColumn::make('status')
                    ->label(__('console.status'))
                    ->boolean(),

                TextColumn::make('created_at')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                self::replaceDomainAction(),
                EditAction::make(),
                DeleteAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            DomainsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListProperties::route('/'),
            'create' => CreateProperty::route('/create'),
            'edit'   => EditProperty::route('/{record}/edit'),
        ];
    }

    /**
     * Replace a property's active domain, keeping the previous one as
     * soft-deleted history visible behind the trashed filter.
     */
    private static function replaceDomainAction(): Action
    {
        return Action::make('replaceDomain')
            ->label(__('console.replace_domain'))
            ->icon(Heroicon::OutlinedArrowPath)
            ->schema([
                TextInput::make('domain')
                    ->label(__('console.new_domain'))
                    ->required()
                    ->maxLength(255)
                    ->rules(TenantDomain::activeDomainRules())
                    ->dehydrateStateUsing(fn(?string $state): ?string => null === $state
                        ? null
                        : TenantDomain::normalizeDomain($state)),
            ])
            ->action(function (Tenant $record, array $data): void {
                $domain = $data['domain'];

                if ( ! is_string($domain)) {
                    return;
                }

                app(ReplaceTenantDomainAction::class)->execute($record, $domain);

                Notification::make()
                    ->success()
                    ->title(__('console.domain_replaced'))
                    ->send();
            });
    }
}
