<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Users\Resources\UserProfileBalances;

use App\Filament\Admin\Clusters\Users\Resources\UserProfileBalances\Pages\CreateUserProfileBalance;
use App\Filament\Admin\Clusters\Users\Resources\UserProfileBalances\Pages\EditUserProfileBalance;
use App\Filament\Admin\Clusters\Users\Resources\UserProfileBalances\Pages\ListUserProfileBalances;
use App\Filament\Admin\Clusters\Users\Resources\UserProfileBalances\Pages\ViewUserProfileBalance;
use App\Filament\Admin\Clusters\Users\UsersCluster;
use App\Forms\Components\StatusToggle;
use App\Tables\Columns\CreatedAtTextColumn;
use App\Tables\Columns\ModelLinkColumn;
use App\Tables\Columns\StatusToggleColumn;
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
use Filament\Forms\Get;
use Filament\Resources\Pages\PageRegistration;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontFamily;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rules\Unique;
use Misaf\Currency\Models\CurrencyCategory;
use Misaf\Tenant\Models\Tenant;
use Misaf\User\Models\UserProfile;
use Misaf\User\Models\UserProfileBalance;

final class UserProfileBalanceResource extends Resource
{
    protected static ?string $model = UserProfileBalance::class;

    protected static ?int $navigationSort = 3;

    protected static ?string $slug = 'users/profiles/balances';

    /**
     * @var class-string<Cluster>|null
     */
    protected static ?string $cluster = UsersCluster::class;

    public static function getBreadcrumb(): string
    {
        return __('navigation.user_profile_balance');
    }

    public static function getModelLabel(): string
    {
        return __('navigation.user_profile_balance');
    }

    public static function getNavigationGroup(): string
    {
        return __('navigation.user_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('navigation.user_profile_balance');
    }

    public static function getPluralModelLabel(): string
    {
        return __('navigation.user_profile_balance');
    }

    /**
     * @return array<string, PageRegistration>
     */
    public static function getPages(): array
    {
        return [
            'index'  => ListUserProfileBalances::route('/'),
            'create' => CreateUserProfileBalance::route('/create'),
            'view'   => ViewUserProfileBalance::route('/{record}'),
            'edit'   => EditUserProfileBalance::route('/{record}/edit'),
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_profile_id')
                    ->columnSpanFull()
                    ->getOptionLabelFromRecordUsing(fn(UserProfile $record) => $record->full_name)
                    ->helperText(str(__('Only existing and valid user profiles will be displayed here.'))->inlineMarkdown()->toHtmlString())
                    ->label(__('model.user_profile'))
                    ->native(false)
                    ->preload()
                    ->relationship(name: 'userProfile', modifyQueryUsing: function (Builder $query): void {
                        $query->whereNotNull('first_name')
                            ->whereNotNull('last_name')
                            ->whereNotNull('birthdate');
                        // ->whereDoesntHave('userProfileBalances')
                    })
                    ->required()
                    ->searchable(),
                // ->unique(ignoreRecord: true, modifyRuleUsing: function (Unique $rule, Get $get): void {
                //     $rule->where('currency_id', $get('currency_id'))->withoutTrashed();
                // })

                Select::make('currency_id')
                    ->columnSpan(['lg' => 1])
                    ->label(__('model.currency'))
                    ->native(false)
                    ->options(CurrencyCategory::with('currencies')->get()->mapWithKeys(fn($item, $key) => [$item->name => $item->currencies->pluck('name', 'id')]))
                    ->preload()
                    ->required()
                    ->searchable(),
                // ->unique(
                //     ignoreRecord: true,
                //     modifyRuleUsing: function (Unique $rule): void {
                //     $rule->where('tenant_id', Tenant::current()->id)
                //         ->withoutTrashed();
                // },
                // ),

                TextInput::make('amount')
                    ->columnSpan(['lg' => 1])
                    ->extraInputAttributes(['dir' => 'ltr'])
                    ->formatStateUsing(fn($record) => $record?->amount->getAmount()->__toString())
                    ->label(__('form.balance'))
                    ->numeric()
                    ->stripCharacters(','),

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

                SpatieMediaLibraryImageColumn::make('latestUserProfile.image')
                    ->circular()
                    ->conversion('thumb-table')
                    ->extraImgAttributes(['class' => 'saturate-50', 'loading' => 'lazy'])
                    ->label(__('form.image'))
                    ->stacked()
                    ->defaultImageUrl(url('coin-payment/images/default.png')),

                ModelLinkColumn::make('user.username')
                    ->label(__('model.user'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('userProfile.first_name')
                    ->label(__('form.first_name'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('userProfile.last_name')
                    ->label(__('form.last_name'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('currency.name')
                    ->label(__('model.currency'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('amount')
                    ->alignCenter()
                    ->copyable()
                    ->copyableState(fn($record) => $record->amount->getAmount())
                    ->copyMessage(__('Phone copied to clipboard'))
                    ->copyMessageDuration(1500)
                    ->extraCellAttributes(['dir' => 'ltr'])
                    ->fontFamily(FontFamily::Mono)
                    ->formatStateUsing(fn($record) => $record->amount->formatTo('en_US'))
                    ->formatStateUsing(fn(int|float $state): int|float => abs($state))
                    ->label(__('form.balance'))
                    ->searchable()
                    ->sortable(),

                StatusToggleColumn::make('status'),
                CreatedAtTextColumn::make('created_at'),
                UpdatedAtTextColumn::make('updated_at'),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
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
