<?php

declare(strict_types=1);

namespace Misaf\VendraLanguage\Filament\Clusters\Resources\Languages\Tables;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\BooleanConstraint;
use Filament\Tables\Table;
use Misaf\VendraLanguage\Actions\SetDefaultLanguage;
use Misaf\VendraLanguage\Models\Language;
use Misaf\VendraLanguage\Support\Locales;
use Misaf\VendraLanguage\Support\TranslationProgress;

final class LanguageTable
{
    public static function configure(Table $table): Table
    {
        /**
         * @var array<int, Column> $columns
         */
        $columns = [
            TextColumn::make('row')
                ->label('#')
                ->rowIndex(),

            TextColumn::make('locale')
                ->badge()
                ->label(__('vendra-language::attributes.locale'))
                ->searchable()
                ->sortable(),

            TextColumn::make('name')
                ->label(__('vendra-language::attributes.name'))
                ->state(fn(Language $record): string => Locales::name($record->locale)),

            IconColumn::make('is_default')
                ->boolean()
                ->label(__('vendra-language::attributes.is_default')),

            TextColumn::make('translation_coverage')
                ->badge()
                ->color(function (Language $record, TranslationProgress $progress): string {
                    $coverage = $progress->forLocale($record->locale);

                    return static::progressColor($coverage['percentage'], $coverage['total']);
                })
                ->description(function (Language $record, TranslationProgress $progress): string {
                    $coverage = $progress->forLocale($record->locale);

                    return __('vendra-language::messages.coverage_summary', [
                        'percentage' => $coverage['percentage'],
                        'remaining'  => $coverage['remaining'],
                    ]);
                })
                ->label(__('vendra-language::attributes.translation_coverage'))
                ->state(function (Language $record, TranslationProgress $progress): string {
                    $coverage = $progress->forLocale($record->locale);

                    return "{$coverage['translated']} / {$coverage['total']}";
                }),

            TextColumn::make('created_at')
                ->badge()
                ->dateTime('Y-m-d H:i')
                ->extraCellAttributes(['dir' => 'ltr'])
                ->label(__('vendra-language::attributes.created_at'))
                ->sinceTooltip()
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('updated_at')
                ->badge()
                ->dateTime('Y-m-d H:i')
                ->extraCellAttributes(['dir' => 'ltr'])
                ->label(__('vendra-language::attributes.updated_at'))
                ->sinceTooltip()
                ->toggleable(isToggledHiddenByDefault: true),
        ];

        return $table
            ->columns($columns)
            ->filters(
                [
                    QueryBuilder::make()
                        ->constraints([
                            BooleanConstraint::make('is_default')
                                ->label(__('vendra-language::attributes.is_default')),
                        ]),
                ],
                layout: FiltersLayout::AboveContentCollapsible,
            )
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),

                    EditAction::make(),

                    Action::make('setDefault')
                        ->action(function (Action $action, Language $record, SetDefaultLanguage $setDefaultLanguage): void {
                            $setDefaultLanguage->execute($record);
                            $action->success();
                        })
                        ->authorize(fn(Language $record): bool => auth()->user()?->can('update', $record) ?? false)
                        ->icon('heroicon-o-check-circle')
                        ->label(__('vendra-language::actions.set_default'))
                        ->requiresConfirmation()
                        ->successNotificationTitle(__('vendra-language::messages.default_language_updated'))
                        ->visible(fn(Language $record): bool => ! $record->is_default),

                    DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort(column: 'position')
            ->reorderable(column: 'position');
    }

    private static function progressColor(int $percentage, int $total): string
    {
        return match (true) {
            0 === $total          => 'gray',
            100 === $percentage   => 'success',
            $percentage > 0       => 'warning',
            default               => 'danger',
        };
    }
}
