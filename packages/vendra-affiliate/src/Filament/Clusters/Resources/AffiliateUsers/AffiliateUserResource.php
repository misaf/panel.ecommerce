<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliate\Filament\Clusters\Resources\AffiliateUsers;

use App\Filament\Admin\Clusters\Affiliates\AffiliatesCluster;
use App\Tables\Columns\CreatedAtTextColumn;
use App\Tables\Columns\DeletedAtTextColumn;
use App\Tables\Columns\ModelLinkColumn;
use App\Tables\Columns\UpdatedAtTextColumn;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Clusters\Cluster;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\PageRegistration;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\DateConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\NumberConstraint;
use Filament\Tables\Table;
use Illuminate\Validation\Rules\Unique;
use Misaf\VendraAffiliate\Filament\Clusters\Resources\AffiliateUsers\Pages\CreateAffiliateUser;
use Misaf\VendraAffiliate\Filament\Clusters\Resources\AffiliateUsers\Pages\ListAffiliateUsers;
use Misaf\VendraAffiliate\Filament\Clusters\Resources\AffiliateUsers\Pages\ViewAffiliateUser;
use Misaf\VendraAffiliate\Models\Affiliate;
use Misaf\VendraAffiliate\Models\AffiliateUser;

final class AffiliateUserResource extends Resource
{
    protected static ?string $model = AffiliateUser::class;

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'affiliate-users';

    /**
     * @var class-string<Cluster>|null
     */
    protected static ?string $cluster = AffiliatesCluster::class;

    public static function getBreadcrumb(): string
    {
        return __('vendra-affiliate::navigation.affiliate_user');
    }

    public static function getModelLabel(): string
    {
        return __('vendra-affiliate::navigation.affiliate_user');
    }

    public static function getNavigationGroup(): string
    {
        return __('vendra-affiliate::navigation.affiliate_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('vendra-affiliate::navigation.affiliate_user');
    }

    public static function getPluralModelLabel(): string
    {
        return __('vendra-affiliate::navigation.affiliate_user');
    }

    /**
     * @return array<string, PageRegistration>
     */
    public static function getPages(): array
    {
        return [
            'index'  => ListAffiliateUsers::route('/'),
            'create' => CreateAffiliateUser::route('/create'),
            'view'   => ViewAffiliateUser::route('/{record}'),
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('affiliate_id')
                    ->columnSpanFull()
                    ->label(__('vendra-affiliate::navigation.affiliate'))
                    ->native(false)
                    ->preload()
                    ->relationship('affiliate', 'name')
                    ->getOptionLabelFromRecordUsing(function (Affiliate $affiliate) {
                        $affiliate->loadMissing('user');

                        return sprintf('%s - %s', $affiliate->user->username, $affiliate->slug);
                    })
                    ->required()
                    ->searchable(),

                Select::make('user_id')
                    ->columnSpanFull()
                    ->label(__('vendra-user::navigation.user'))
                    ->native(false)
                    ->preload()
                    ->relationship('user', 'username')
                    ->required()
                    ->searchable()
                    ->unique(modifyRuleUsing: fn(Unique $rule) => $rule->withoutTrashed()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('row')
                    ->label('#')
                    ->rowIndex(),

                ModelLinkColumn::make('affiliate.user.username')
                    ->label(__('vendra-affiliate::navigation.affiliate'))
                    ->searchable(),
                ModelLinkColumn::make('user.username')
                    ->label(__('vendra-user::navigation.user'))
                    ->searchable(),
                TextColumn::make('commission_earned')
                    ->label(__('vendra-affiliate::attributes.commission_earned'))
                    ->numeric(locale: 'en', maxDecimalPlaces: 0)
                    ->sortable(),
                CreatedAtTextColumn::make('created_at'),
                UpdatedAtTextColumn::make('updated_at')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                DeletedAtTextColumn::make('deleted_at'),
            ])
            ->filters(
                [
                    QueryBuilder::make()
                        ->constraints([
                            NumberConstraint::make('commission_earned')
                                ->label(__('vendra-affiliate::attributes.commission_earned')),
                            DateConstraint::make('created_at')
                                ->label(__('vendra-affiliate::attributes.created_at')),
                            DateConstraint::make('updated_at')
                                ->label(__('vendra-affiliate::attributes.updated_at')),
                        ]),
                ],
                layout: FiltersLayout::AboveContentCollapsible,
            )
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),

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
