<?php

declare(strict_types=1);

namespace App\Filament\Account\Resources\Websites;

use App\Filament\Account\Resources\Websites\Pages\CreateWebsite;
use App\Filament\Account\Resources\Websites\Pages\ListWebsites;
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
use Illuminate\Validation\Rule;
use Misaf\VendraTenant\Actions\ReplaceTenantDomainAction;
use Misaf\VendraTenant\Models\Tenant;
use Misaf\VendraUser\Models\User;

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

    /**
     * The billing account of the currently authenticated owner.
     */
    public static function currentAccountId(): ?int
    {
        $user = Filament::auth()->user();

        return $user instanceof User ? $user->account_id : null;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('platform.name'))
                    ->maxLength(255)
                    ->required(),

                TextInput::make('domain')
                    ->label(__('platform.domain'))
                    ->maxLength(255)
                    ->required(),

                TextInput::make('owner_username')
                    ->label(__('platform.owner_username'))
                    ->maxLength(255)
                    ->required(),

                TextInput::make('owner_email')
                    ->label(__('platform.owner_email'))
                    ->email()
                    ->maxLength(255)
                    ->required(),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query): Builder => $query->where('account_id', self::currentAccountId()))
            ->columns([
                TextColumn::make('name')
                    ->label(__('platform.name'))
                    ->searchable()
                    ->sortable(),

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
            ->recordActions([
                self::replaceDomainAction(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListWebsites::route('/'),
            'create' => CreateWebsite::route('/create'),
        ];
    }

    /**
     * Replace the website's active domain. The previous domain is kept as
     * soft-deleted history; the account panel does not surface that history.
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
                    ->rule('regex:/^(?!-)[A-Za-z0-9-]{1,63}(?<!-)(\.(?!-)[A-Za-z0-9-]{1,63}(?<!-))+$/')
                    ->rule(Rule::unique('tenant_domains', 'name')->where('status', true)->withoutTrashed()),
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
