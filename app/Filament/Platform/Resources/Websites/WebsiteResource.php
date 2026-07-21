<?php

declare(strict_types=1);

namespace App\Filament\Platform\Resources\Websites;

use App\Filament\Platform\Resources\Websites\Pages\CreateWebsite;
use App\Filament\Platform\Resources\Websites\Pages\EditWebsite;
use App\Filament\Platform\Resources\Websites\Pages\ListWebsites;
use App\Filament\Platform\Resources\Websites\RelationManagers\DomainsRelationManager;
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
use Misaf\VendraSubscription\Models\Account;
use Misaf\VendraTenant\Actions\ReplaceTenantDomainAction;
use Misaf\VendraTenant\Models\Tenant;
use Misaf\VendraTenant\Models\TenantDomain;

final class WebsiteResource extends Resource
{
    protected static ?string $model = Tenant::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGlobeAlt;

    protected static ?string $slug = 'websites';

    public static function getModelLabel(): string
    {
        return __('platform.website');
    }

    public static function getPluralModelLabel(): string
    {
        return __('platform.websites');
    }

    public static function getNavigationLabel(): string
    {
        return __('platform.websites');
    }

    public static function getNavigationGroup(): string
    {
        return __('platform.navigation_group');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('account_id')
                    ->label(__('platform.account'))
                    ->options(fn(): array => Account::query()->enabled()->pluck('name', 'id')->all())
                    ->required()
                    ->native(false)
                    ->visibleOn('create'),

                TextInput::make('name')
                    ->label(__('platform.name'))
                    ->maxLength(255)
                    ->required(),

                TextInput::make('domain')
                    ->label(__('platform.domain'))
                    ->maxLength(255)
                    ->required()
                    ->rules(TenantDomain::activeDomainRules())
                    ->dehydrateStateUsing(fn(?string $state): ?string => null === $state
                        ? null
                        : TenantDomain::normalizeDomain($state))
                    ->visibleOn('create'),

                TextInput::make('owner_username')
                    ->label(__('platform.owner_username'))
                    ->maxLength(255)
                    ->required()
                    ->visibleOn('create'),

                TextInput::make('owner_email')
                    ->label(__('platform.owner_email'))
                    ->email()
                    ->maxLength(255)
                    ->required()
                    ->visibleOn('create'),

                Toggle::make('status')
                    ->label(__('platform.status'))
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
                    ->label(__('platform.name'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('account')
                    ->label(__('platform.account'))
                    ->state(fn(Tenant $record): ?string => Account::query()->find($record->account_id)?->name)
                    ->placeholder('—'),

                TextColumn::make('domain')
                    ->label(__('platform.domain'))
                    ->state(fn(Tenant $record): ?string => $record->activeDomainName())
                    ->placeholder('—'),

                IconColumn::make('status')
                    ->label(__('platform.status'))
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
            'index'  => ListWebsites::route('/'),
            'create' => CreateWebsite::route('/create'),
            'edit'   => EditWebsite::route('/{record}/edit'),
        ];
    }

    /**
     * Replace a website's active domain, keeping the previous one as
     * soft-deleted history visible behind the trashed filter.
     */
    private static function replaceDomainAction(): Action
    {
        return Action::make('replaceDomain')
            ->label(__('platform.replace_domain'))
            ->icon(Heroicon::OutlinedArrowPath)
            ->schema([
                TextInput::make('domain')
                    ->label(__('platform.new_domain'))
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
                    ->title(__('platform.domain_replaced'))
                    ->send();
            });
    }
}
