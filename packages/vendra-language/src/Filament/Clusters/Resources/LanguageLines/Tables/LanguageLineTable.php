<?php

declare(strict_types=1);

namespace Misaf\VendraLanguage\Filament\Clusters\Resources\LanguageLines\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;
use Filament\Tables\Table;
use Misaf\VendraLanguage\Models\LanguageLine;
use Misaf\VendraLanguage\Support\TranslationProgress;

final class LanguageLineTable
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

            TextColumn::make('namespace')
                ->alignStart()
                ->badge()
                ->label(__('vendra-language::attributes.namespace'))
                ->placeholder('—')
                ->searchable()
                ->sortable(),

            TextColumn::make('group')
                ->alignStart()
                ->badge()
                ->label(__('vendra-language::attributes.group'))
                ->searchable()
                ->sortable(),

            TextColumn::make('key')
                ->alignStart()
                ->label(__('vendra-language::attributes.key'))
                ->searchable()
                ->sortable(),

            TextColumn::make('text')
                ->alignStart()
                ->label(__('vendra-language::attributes.text'))
                ->state(fn(LanguageLine $record): ?string => $record->getTranslation(app()->getLocale()))
                ->wrap(),

            TextColumn::make('translation_progress')
                ->badge()
                ->color(function (LanguageLine $record, TranslationProgress $progress): string {
                    $coverage = $progress->forLanguageLine($record);

                    return static::progressColor($coverage['percentage'], $coverage['total']);
                })
                ->description(function (LanguageLine $record, TranslationProgress $progress): string {
                    $coverage = $progress->forLanguageLine($record);

                    return __('vendra-language::messages.coverage_summary', [
                        'percentage' => $coverage['percentage'],
                        'remaining'  => $coverage['remaining'],
                    ]);
                })
                ->label(__('vendra-language::attributes.translation_progress'))
                ->state(function (LanguageLine $record, TranslationProgress $progress): string {
                    $coverage = $progress->forLanguageLine($record);

                    return "{$coverage['translated']} / {$coverage['total']}";
                })
                ->tooltip(function (LanguageLine $record, TranslationProgress $progress): ?string {
                    $missingLocales = $progress->forLanguageLine($record)['missing_locales'];

                    if ([] === $missingLocales) {
                        return null;
                    }

                    return __('vendra-language::messages.missing_locales', [
                        'locales' => implode(', ', $missingLocales),
                    ]);
                }),

            TextColumn::make('created_at')
                ->alignCenter()
                ->badge()
                ->dateTime('Y-m-d H:i')
                ->extraCellAttributes(['dir' => 'ltr'])
                ->label(__('vendra-language::attributes.created_at'))
                ->sinceTooltip()
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('updated_at')
                ->alignCenter()
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
                            TextConstraint::make('namespace')
                                ->label(__('vendra-language::attributes.namespace')),

                            TextConstraint::make('group')
                                ->label(__('vendra-language::attributes.group')),
                        ]),
                ],
                layout: FiltersLayout::AboveContentCollapsible,
            )
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
            ])
            ->defaultSort(column: 'created_at', direction: 'desc');
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
