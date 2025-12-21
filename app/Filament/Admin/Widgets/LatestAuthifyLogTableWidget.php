<?php

declare(strict_types=1);

namespace App\Filament\Admin\Widgets;

use App\Tables\Columns\ModelLinkColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Misaf\AuthifyLog\Enums\AuthifyLogActionEnum;
use Misaf\AuthifyLog\Models\AuthifyLog;

final class LatestAuthifyLogTableWidget extends BaseWidget
{
    protected static ?int $sort = 9;

    /**
     * @var int|string|array<string, int|null>
     */
    protected int|string|array $columnSpan = [
        'sm' => 1,
        'lg' => 2,
    ];

    protected function getColumns(): int
    {
        return 1;
    }

    public static function isDiscovered(): bool
    {
        return true;
    }

    public static function canView(): bool
    {
        return true;
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading(__('authify-log::widgets.latest_authify_log_table'))
            ->query(AuthifyLog::where('action', '<>', AuthifyLogActionEnum::Authenticated)->take(5))
            ->columns([
                ModelLinkColumn::make('user.username')
                    ->alignCenter()
                    ->label(__('form.username'))
                    ->wrap(),

                TextColumn::make('action')
                    ->alignCenter()
                    ->extraCellAttributes(['dir' => 'ltr'])
                    ->label(__('authify-log::attributes.action'))
                    ->searchable(),

                TextColumn::make('ip_address')
                    ->alignCenter()
                    ->copyable()
                    ->copyMessage(__('form.saved'))
                    ->copyMessageDuration(1500)
                    ->extraCellAttributes(['dir' => 'ltr'])
                    ->label(__('authify-log::attributes.ip_address'))
                    ->formatStateUsing(function (string $state, AuthifyLog $record): HtmlString {
                        return new HtmlString(
                            '<span class="flex items-center space-x-2">'
                            . '<img src="' . asset('vendor/blade-country-flags/4x3-' . Str::lower($record->ip_country) . '.svg') . '" alt="' . $record->ip_country . '" title="' . $record->ip_country . '" class="w-4 inline-block" />'
                            . '<span>' . $state . '</span>'
                            . '</span>',
                        );
                    }),

                TextColumn::make('created_at')
                    ->alignCenter()
                    ->badge()
                    ->extraCellAttributes(['dir' => 'ltr'])
                    ->label(__('authify-log::attributes.created_at'))
                    ->sinceTooltip()
                    ->sortable(false)
                    ->unless(app()->isLocale('fa'), fn(TextColumn $column) => $column->jalaliDateTime('Y-m-d', toLatin: true)),
            ])
            ->searchable(false)
            ->paginated(false)
            ->poll('10s');
    }
}
