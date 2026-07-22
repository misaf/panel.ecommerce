<?php

declare(strict_types=1);

namespace App\Filament\Reseller\Resources\Properties;

use App\Filament\Reseller\Resources\Properties\Pages\CreateProperty;
use App\Filament\Reseller\Resources\Properties\Pages\ListProperties;
use App\Models\Reseller;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Misaf\VendraTenant\Actions\ReplaceTenantDomainAction;
use Misaf\VendraTenant\Models\Tenant;
use Misaf\VendraTenant\Models\TenantDomain;
use Misaf\VendraUser\Models\User;

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

    /**
     * The billing reseller of the currently authenticated owner.
     */
    public static function currentResellerId(): ?int
    {
        return self::currentReseller()?->id;
    }

    public static function currentReseller(): ?Reseller
    {
        $user = Filament::auth()->user();

        if ( ! $user instanceof User || null === $user->reseller_id) {
            return null;
        }

        return Reseller::query()->find($user->reseller_id);
    }

    public static function canCreate(): bool
    {
        $reseller = self::currentReseller();

        return null !== $reseller && $reseller->status;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
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
                        : TenantDomain::normalizeDomain($state)),

                TextInput::make('owner_username')
                    ->label(__('console.owner_username'))
                    ->maxLength(255)
                    ->required(),

                TextInput::make('owner_email')
                    ->label(__('console.owner_email'))
                    ->email()
                    ->maxLength(255)
                    ->required(),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query): Builder => $query->where('reseller_id', self::currentResellerId()))
            ->columns([
                TextColumn::make('name')
                    ->label(__('console.name'))
                    ->searchable()
                    ->sortable(),

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
            ->recordActions([
                self::replaceDomainAction(),
                DeleteAction::make()
                    ->authorize(fn(): bool => self::canCreate()),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->authorize(fn(): bool => self::canCreate()),
                ]),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListProperties::route('/'),
            'create' => CreateProperty::route('/create'),
        ];
    }

    /**
     * Replace the property's active domain. The previous domain is kept as
     * soft-deleted history; the reseller panel does not surface that history.
     */
    private static function replaceDomainAction(): Action
    {
        return Action::make('replaceDomain')
            ->label(__('console.replace_domain'))
            ->icon(Heroicon::OutlinedArrowPath)
            ->authorize(fn(): bool => self::canCreate())
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
