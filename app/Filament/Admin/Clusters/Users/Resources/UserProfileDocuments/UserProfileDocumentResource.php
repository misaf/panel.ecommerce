<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Users\Resources\UserProfileDocuments;

use App\Filament\Admin\Clusters\Users\Resources\UserProfileDocuments\Pages\CreateUserProfileDocument;
use App\Filament\Admin\Clusters\Users\Resources\UserProfileDocuments\Pages\EditUserProfileDocument;
use App\Filament\Admin\Clusters\Users\Resources\UserProfileDocuments\Pages\ListUserProfileDocuments;
use App\Filament\Admin\Clusters\Users\Resources\UserProfileDocuments\Pages\ViewUserProfileDocument;
use App\Filament\Admin\Clusters\Users\UsersCluster;
use App\Tables\Columns\CreatedAtTextColumn;
use App\Tables\Columns\ModelLinkColumn;
use App\Tables\Columns\UpdatedAtTextColumn;
use App\Tables\Columns\VerifiedAtTextColumn;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Clusters\Cluster;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\ToggleButtons;
use Filament\Resources\Pages\PageRegistration;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rules\Unique;
use Misaf\Tenant\Models\Tenant;
use Misaf\User\Enums\UserProfileDocumentStatusEnum;
use Misaf\User\Models\UserProfileDocument;

final class UserProfileDocumentResource extends Resource
{
    protected static ?string $model = UserProfileDocument::class;

    protected static ?int $navigationSort = 5;

    protected static ?string $slug = 'users/profiles/documents';

    /**
     * @var class-string<Cluster>|null
     */
    protected static ?string $cluster = UsersCluster::class;

    public static function getBreadcrumb(): string
    {
        return __('navigation.user_profile_document');
    }

    public static function getModelLabel(): string
    {
        return __('navigation.user_profile_document');
    }

    public static function getNavigationGroup(): string
    {
        return __('navigation.user_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('navigation.user_profile_document');
    }

    public static function getPluralModelLabel(): string
    {
        return __('navigation.user_profile_document');
    }

    /**
     * @return array<string, PageRegistration>
     */
    public static function getPages(): array
    {
        return [
            'index'  => ListUserProfileDocuments::route('/'),
            'create' => CreateUserProfileDocument::route('/create'),
            'view'   => ViewUserProfileDocument::route('/{record}'),
            'edit'   => EditUserProfileDocument::route('/{record}/edit'),
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_profile_id')
                    ->columnSpanFull()
                    ->getOptionLabelFromRecordUsing(fn(Model $record) => $record->full_name)
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

                SpatieMediaLibraryFileUpload::make('image')
                    ->columnSpanFull()
                    ->image()
                    ->label(__('form.image'))
                    ->panelLayout('grid'),

                ToggleButtons::make('status')
                    ->columnSpanFull()
                    ->inline()
                    ->label(__('form.status'))
                    ->options(UserProfileDocumentStatusEnum::class)
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

                SelectColumn::make('status')
                    ->label(__('form.status'))
                    ->options(UserProfileDocumentStatusEnum::class)
                    ->beforeStateUpdated(fn($record, $state) => $record->verified_at = $state === UserProfileDocumentStatusEnum::Approved->value ? now() : null)
                    ->afterStateUpdated(fn($record, $state) => $record->setStatus($state)),

                VerifiedAtTextColumn::make('verified_at'),

                CreatedAtTextColumn::make('created_at'),

                UpdatedAtTextColumn::make('updated_at'),
            ])
            ->groups([
                Group::make('status')
                    ->getDescriptionFromRecordUsing(fn(UserProfileDocument $record): string => $record->status->getDescription())
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
