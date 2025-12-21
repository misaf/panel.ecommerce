<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\AuthifyLogs\AuthifyLogs;

use App\Filament\Admin\Resources\AuthifyLogs\Pages\ListAuthifyLogs;
use App\Tables\Columns\CreatedAtTextColumn;
use App\Tables\Columns\ModelLinkColumn;
use App\Tables\Columns\UpdatedAtTextColumn;
use Filament\Resources\Pages\PageRegistration;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\DateConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\SelectConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Misaf\AuthifyLog\Enums\AuthifyLogActionEnum;
use Misaf\AuthifyLog\Models\AuthifyLog;

final class AuthifyLogResource extends Resource
{
    protected static ?string $model = AuthifyLog::class;

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'authify-logs';

    public static function getModelLabel(): string
    {
        return __('navigation.authify_log');
    }

    public static function getNavigationGroup(): string
    {
        return __('navigation.report_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('navigation.authify_log');
    }

    public static function getPluralModelLabel(): string
    {
        return __('navigation.authify_log');
    }

    /**
     * @return array<string, PageRegistration>
     */
    public static function getPages(): array
    {
        return [
            'index' => ListAuthifyLogs::route('/'),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('row')
                    ->label('#')
                    ->rowIndex(),

                ModelLinkColumn::make('user.username')
                    ->alignCenter()
                    ->label(__('form.username'))
                    ->wrap(),

                TextColumn::make('user_agent')
                    ->alignCenter()
                    ->extraCellAttributes(['dir' => 'ltr'])
                    ->label(__('form.user_agent'))
                    ->wrap(),

                TextColumn::make('action')
                    ->alignCenter()
                    ->extraCellAttributes(['dir' => 'ltr'])
                    ->label(__('form.action')),

                TextColumn::make('ip_address')
                    ->alignCenter()
                    ->copyable()
                    ->copyMessage(__('form.saved'))
                    ->copyMessageDuration(1500)
                    ->extraCellAttributes(['dir' => 'ltr'])
                    ->formatStateUsing(fn(string $state, AuthifyLog $record): HtmlString => new HtmlString(
                        '<span class="flex items-center space-x-2">'
                        . '<img src="' . asset('vendor/blade-country-flags/4x3-' . Str::lower($record->ip_country) . '.svg') . '" alt="' . $record->ip_country . '" title="' . $record->ip_country . '" class="w-4 inline-block" />'
                        . '<span>' . $state . '</span>'
                        . '</span>',
                    ))
                    ->label(__('form.ip_address'))
                    ->searchable(),

                CreatedAtTextColumn::make('created_at'),
                UpdatedAtTextColumn::make('updated_at'),
            ])
            ->filters(
                [
                    QueryBuilder::make()
                        ->constraints([
                            TextConstraint::make('user_agent'),
                            SelectConstraint::make('action')
                                ->multiple()
                                ->options(AuthifyLogActionEnum::class),
                            TextConstraint::make('ip_address'),
                            DateConstraint::make('created_at')
                                ->label(__('form.created_at')),
                            DateConstraint::make('updated_at')
                                ->label(__('form.updated_at')),
                        ]),
                ],
                layout: FiltersLayout::AboveContentCollapsible,
            );
    }
}
