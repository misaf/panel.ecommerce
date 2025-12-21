<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Users\Resources\UserProfiles;

use App\Filament\Admin\Clusters\Users\Resources\UserProfileDocuments\RelationManagers\UserProfileDocumentRelationManager;
use App\Filament\Admin\Clusters\Users\Resources\UserProfilePhones\RelationManagers\UserProfilePhoneRelationManager;
use App\Filament\Admin\Clusters\Users\Resources\UserProfiles\Pages\CreateUserProfile;
use App\Filament\Admin\Clusters\Users\Resources\UserProfiles\Pages\EditUserProfile;
use App\Filament\Admin\Clusters\Users\Resources\UserProfiles\Pages\ListUserProfiles;
use App\Filament\Admin\Clusters\Users\Resources\UserProfiles\Pages\ViewUserProfile;
use App\Filament\Admin\Clusters\Users\UsersCluster;
use App\Forms\Components\DescriptionTextarea;
use App\Forms\Components\StatusToggle;
use App\Tables\Columns\CreatedAtTextColumn;
use App\Tables\Columns\FirstNameTextColumn;
use App\Tables\Columns\LastNameTextColumn;
use App\Tables\Columns\ModelLinkColumn;
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
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\PageRegistration;
use Filament\Resources\RelationManagers\RelationGroup;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\RelationManagers\RelationManagerConfiguration;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Illuminate\Validation\Rules\Unique;
use Misaf\Tenant\Models\Tenant;
use Misaf\User\Models\UserProfile;

final class UserProfileResource extends Resource
{
    protected static ?string $model = UserProfile::class;

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'users/profiles/profiles';

    /**
     * @var class-string<Cluster>|null
     */
    protected static ?string $cluster = UsersCluster::class;

    public static function getBreadcrumb(): string
    {
        return __('navigation.user_profile');
    }

    public static function getModelLabel(): string
    {
        return __('navigation.user_profile');
    }

    public static function getNavigationGroup(): string
    {
        return __('navigation.user_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('navigation.user_profile');
    }

    public static function getPluralModelLabel(): string
    {
        return __('navigation.user_profile');
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['first_name', 'last_name'];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['user', 'latestUserProfilePhone']);
    }

    /**
     * @return array<Action>
     */
    public static function getGlobalSearchResultActions(Model $record): array
    {
        return [
            Action::make('view')
                ->url(self::getUrl('view', ['record' => $record])),
        ];
    }

    /**
     * @return array<string, HtmlString>
     */
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            __('form.email') => str('<span dir="ltr">' . $record->user->email . '</span>')->toHtmlString(),
            __('form.phone') => str('<span dir="ltr">' . $record?->latestUserProfilePhone?->phone . '</span>')->toHtmlString(),
        ];
    }

    public static function getGlobalSearchResultTitle(Model $record): string|Htmlable
    {
        return str('<span dir="ltr">' . $record->full_name . '</span>')->toHtmlString();
    }

    /**
     * @return array<string, PageRegistration>
     */
    public static function getPages(): array
    {
        return [
            'index'  => ListUserProfiles::route('/'),
            'create' => CreateUserProfile::route('/create'),
            'view'   => ViewUserProfile::route('/{record}'),
            'edit'   => EditUserProfile::route('/{record}/edit'),
        ];
    }

    /**
     * @return array<class-string<RelationManager>|RelationGroup|RelationManagerConfiguration>
     */
    public static function getRelations(): array
    {
        return [
            UserProfileDocumentRelationManager::class,
            UserProfilePhoneRelationManager::class,
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->columnSpanFull()
                    ->helperText(str(__('Only existing users will be displayed here.'))->inlineMarkdown()->toHtmlString())
                    ->label(__('model.user'))
                    ->native(false)
                    ->preload()
                    ->relationship('user', 'username')
                    ->required()
                    ->searchable()
                    ->unique(
                        modifyRuleUsing: function (Unique $rule): void {
                            $rule->where('tenant_id', Tenant::current()->id)
                                ->withoutTrashed();
                        },
                    ),

                TextInput::make('first_name')
                    ->autofocus()
                    ->columnSpan(['lg' => 1])
                    ->label(__('form.first_name'))
                    ->required(),

                TextInput::make('last_name')
                    ->columnSpan(['lg' => 1])
                    ->label(__('form.last_name'))
                    ->required(),

                DatePicker::make('birthdate')
                    ->closeOnDateSelection()
                    ->columnSpanFull()
                    ->displayFormat('Y-m-d')
                    ->firstDayOfWeek(6)
                    ->helperText(str(__('Only individuals aged 7 years and older are eligible to create a profile.'))->inlineMarkdown()->toHtmlString())
                    ->label(__('form.birthdate'))
                    ->maxDate(today()->subYears(7))
                    ->native(false)
                    ->required(),

                DescriptionTextarea::make('description'),

                SpatieMediaLibraryFileUpload::make('image')
                    ->avatar()
                    ->circleCropper()
                    ->image()
                    ->imageEditor()
                    ->label(__('form.image')),

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

                SpatieMediaLibraryImageColumn::make('image')
                    ->circular()
                    ->conversion('thumb-table')
                    ->extraImgAttributes(['class' => 'saturate-50', 'loading' => 'lazy'])
                    ->label(__('form.image'))
                    ->stacked()
                    ->defaultImageUrl(url('coin-payment/images/default.png')),

                ModelLinkColumn::make('user.username')
                    ->label(__('model.user'))
                    ->searchable(),

                FirstNameTextColumn::make('first_name')
                    ->searchable(),

                LastNameTextColumn::make('last_name')
                    ->searchable(),

                TextColumn::make('birthdate')
                    ->alignCenter()
                    ->badge()
                    ->extraCellAttributes(['dir' => 'ltr'])
                    ->unless(app()->isLocale('fa'), fn(TextColumn $column) => $column->jalaliDateTime('Y-m-d H:i', toLatin: true), fn(TextColumn $column) => $column->dateTime('Y-m-d H:i'))
                    ->label(__('form.birthdate'))
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
