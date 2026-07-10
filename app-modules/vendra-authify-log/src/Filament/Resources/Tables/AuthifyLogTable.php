<?php

declare(strict_types=1);

namespace Misaf\VendraAuthifyLog\Filament\Resources\Tables;

use Awcodes\BadgeableColumn\Components\Badge;
use Awcodes\BadgeableColumn\Components\BadgeableColumn;
use Filament\Actions\ActionGroup;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\Size;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\ColumnGroup;
use Filament\Tables\Columns\Layout\Component as LayoutComponent;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\BooleanConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\DateConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Number;
use Illuminate\Support\Str;
use Misaf\VendraAuthifyLog\Models\AuthifyLog;

class AuthifyLogTable
{
    public static function configure(Table $table): Table
    {
        /**
         * @var array<int, Column|ColumnGroup|LayoutComponent> $columns
         */
        $columns = [
            TextColumn::make('row')
                ->label('#')
                ->rowIndex(),

            // SpatieMediaLibraryImageColumn::make('image')
            //     ->circular()
            //     ->conversion('thumb-table')
            //     ->defaultImageUrl(url('coin-payment/images/default.png'))
            //     ->extraImgAttributes(['class' => 'saturate-50', 'loading' => 'lazy'])
            //     ->label(__('vendra-authify-log::attributes.image'))
            //     ->stacked(),

            // BadgeableColumn::make('name')
            //     ->alignStart()
            //     // ->description(fn(FaqCategory $record): string => $record->description)
            //     ->icon('heroicon-m-folder-plus')
            //     ->label(__('vendra-authify-log::attributes.name'))
            //     ->searchable()
            //     ->suffixBadges([
            //         Badge::make('count')
            //             ->label(fn(FaqCategory $record): string => Number::format($record->faqs()->count()))
            //             ->size(Size::Small),
            //     ]),

            TextColumn::make('ip_address')
                ->alignCenter()
                ->copyable()
                ->copyMessage(__('vendra-authify-log::messages.ip_address_copied'))
                ->copyMessageDuration(1500)
                ->extraCellAttributes(['dir' => 'ltr'])
                ->formatStateUsing(fn(string $state, AuthifyLog $record): HtmlString => new HtmlString(
                    '<span class="flex items-center space-x-2">'
                    . '<img src="' . asset('vendor/blade-country-flags/4x3-' . Str::lower($record->ip_country) . '.svg') . '" alt="' . $record->ip_country . '" title="' . $record->ip_country . '" class="w-4 inline-block" />'
                    . '<span>' . $state . '</span>'
                    . '</span>',
                ))
                ->label(__('vendra-authify-log::attributes.ip_address'))
                ->searchable(),

            ToggleColumn::make('status')
                ->label(__('vendra-authify-log::attributes.status'))
                ->onIcon('heroicon-m-bolt'),

            TextColumn::make('created_at')
                ->alignCenter()
                ->badge()
                ->extraCellAttributes(['dir' => 'ltr'])
                ->label(__('vendra-authify-log::attributes.created_at'))
                ->sinceTooltip()
                ->toggleable(isToggledHiddenByDefault: true)
                ->unless(
                    app()->isLocale('fa'),
                    fn(TextColumn $column) => $column->jalaliDateTime('Y-m-d H:i', latinNumbers: true),
                    fn(TextColumn $column) => $column->dateTime('Y-m-d H:i')
                ),

            TextColumn::make('updated_at')
                ->alignCenter()
                ->badge()
                ->extraCellAttributes(['dir' => 'ltr'])
                ->label(__('vendra-authify-log::attributes.updated_at'))
                ->sinceTooltip()
                ->toggleable(isToggledHiddenByDefault: true)
                ->unless(
                    app()->isLocale('fa'),
                    fn(TextColumn $column) => $column->jalaliDateTime('Y-m-d H:i', latinNumbers: true),
                    fn(TextColumn $column) => $column->dateTime('Y-m-d H:i')
                ),
        ];

        return $table
            ->columns($columns)
            ->filters(
                [
                    QueryBuilder::make()
                        ->constraints([
                            TextConstraint::make('name')
                                ->label(__('vendra-authify-log::attributes.name')),

                            BooleanConstraint::make('status')
                                ->label(__('vendra-authify-log::attributes.status')),

                            DateConstraint::make('created_at')
                                ->label(__('vendra-authify-log::attributes.created_at')),

                            DateConstraint::make('updated_at')
                                ->label(__('vendra-authify-log::attributes.updated_at')),
                        ]),
                ],
                layout: FiltersLayout::AboveContentCollapsible,
            )
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                ]),
            ]);
    }
}
