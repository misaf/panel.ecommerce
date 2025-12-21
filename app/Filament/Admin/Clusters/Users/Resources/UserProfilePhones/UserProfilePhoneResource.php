<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Users\Resources\UserProfilePhones;

use App\Filament\Admin\Clusters\Users\Resources\UserProfilePhones\Pages\CreateUserProfilePhone;
use App\Filament\Admin\Clusters\Users\Resources\UserProfilePhones\Pages\EditUserProfilePhone;
use App\Filament\Admin\Clusters\Users\Resources\UserProfilePhones\Pages\ListUserProfilePhones;
use App\Filament\Admin\Clusters\Users\Resources\UserProfilePhones\Pages\ViewUserProfilePhone;
use App\Filament\Admin\Clusters\Users\UsersCluster;
use App\Tables\Columns\CreatedAtTextColumn;
use App\Tables\Columns\ModelLinkColumn;
use App\Tables\Columns\UpdatedAtTextColumn;
use App\Tables\Columns\VerifiedAtTextColumn;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Clusters\Cluster;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ToggleButtons;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rules\Unique;
use Misaf\Tenant\Models\Tenant;
use Misaf\User\Enums\UserProfilePhoneStatusEnum;
use Misaf\User\Models\UserProfile;
use Misaf\User\Models\UserProfilePhone;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Ysfkaya\FilamentPhoneInput\PhoneInputNumberType;
use Ysfkaya\FilamentPhoneInput\Tables\PhoneColumn;

final class UserProfilePhoneResource extends Resource
{
    protected static ?string $model = UserProfilePhone::class;

    protected static ?int $navigationSort = 4;

    protected static ?string $slug = 'users/profiles/phones';

    /**
     * @var class-string<Cluster>|null
     */
    protected static ?string $cluster = UsersCluster::class;

    public static function getBreadcrumb(): string
    {
        return __('navigation.user_profile_phone');
    }

    public static function getModelLabel(): string
    {
        return __('navigation.user_profile_phone');
    }

    public static function getNavigationGroup(): string
    {
        return __('navigation.user_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('navigation.user_profile_phone');
    }

    public static function getPluralModelLabel(): string
    {
        return __('navigation.user_profile_phone');
    }

    /**
     * @return array<string>
     */
    public static function getGloballySearchableAttributes(): array
    {
        return ['phone', 'phone_normalized', 'phone_national', 'phone_e164'];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['userProfile']);
    }

    /**
     * @return array<string, string>
     */
    public static function getGlobalSearchResultActions(Model $record): array
    {
        return [
            Action::make('view')
                ->url(self::getUrl('view', ['record' => $record])),
        ];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            __('Name') => $record->userProfile->full_name,
        ];
    }

    public static function getGlobalSearchResultTitle(Model $record): string|Htmlable
    {
        return str('<span dir="ltr">' . $record?->phone . '</span>')->toHtmlString();
    }

    /**
     * @return array<string, PageRegistration>
     */
    public static function getPages(): array
    {
        return [
            'index'  => ListUserProfilePhones::route('/'),
            'create' => CreateUserProfilePhone::route('/create'),
            'view'   => ViewUserProfilePhone::route('/{record}'),
            'edit'   => EditUserProfilePhone::route('/{record}/edit'),
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
                    })
                    ->required()
                    ->searchable()
                    ->unique(
                        modifyRuleUsing: function (Unique $rule): void {
                            $rule->where('tenant_id', Tenant::current()->id)
                                ->withoutTrashed();
                        },
                    ),

                PhoneInput::make('phone')
                    ->columnSpanFull()
                    ->countryStatePath('country')
                    ->disallowDropdown()
                    ->displayNumberFormat(PhoneInputNumberType::E164)
                    ->label(__('form.phone'))
                    ->required()
                    ->rule('phone'),

                ToggleButtons::make('status')
                    ->columnSpanFull()
                    ->inline()
                    ->label(__('form.status'))
                    ->options(UserProfilePhoneStatusEnum::class)
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

                TextColumn::make('userProfile.birthdate')
                    ->badge()
                    ->jalaliDate('Y-m-d', toLatin: true)
                    ->label(__('form.birthdate'))
                    ->searchable()
                    ->sortable(),

                PhoneColumn::make('phone')
                    ->copyable()
                    ->copyMessage(__('Phone copied to clipboard'))
                    ->copyMessageDuration(1500)
                    ->displayFormat(PhoneInputNumberType::INTERNATIONAL)
                    ->extraCellAttributes(['dir' => 'ltr'])
                    ->label(__('form.phone'))
                    ->searchable(isGlobal: true)
                    ->sortable(),

                SelectColumn::make('status')
                    ->afterStateUpdated(fn($record, $state) => $record->setStatus($state))
                    ->beforeStateUpdated(fn($record, $state) => $record->verified_at = $state === UserProfilePhoneStatusEnum::Approved->value ? now() : null)
                    ->label(__('form.status'))
                    ->options(UserProfilePhoneStatusEnum::class),

                VerifiedAtTextColumn::make('verified_at'),
                CreatedAtTextColumn::make('created_at'),
                UpdatedAtTextColumn::make('updated_at'),
            ])
            ->groups([
                Group::make('status')
                    ->getDescriptionFromRecordUsing(function (UserProfilePhone $record): string {
                        return $record->status->getDescription();
                    })
                    ->label(__('form.status')),

                Group::make('verified_at')
                    ->date()
                    ->label(__('form.verified_at')),
            ])
            ->groupingSettingsInDropdownOnDesktop()
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
