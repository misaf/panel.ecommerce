<?php

declare(strict_types=1);

namespace App\Filament\Reseller\Widgets;

use App\Filament\Reseller\Concerns\InteractsWithCurrentReseller;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Misaf\VendraTenant\Models\Tenant;

final class LatestProperties extends BaseWidget
{
    use InteractsWithCurrentReseller;

    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        $reseller = (new self())->currentReseller();

        return null !== $reseller && $reseller->tenants()->exists();
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading(__('console.properties'))
            ->query(fn(): Builder => Tenant::query()->where('reseller_id', $this->currentReseller()?->getKey() ?? 0))
            ->columns([
                TextColumn::make('name')
                    ->label(__('console.name'))
                    ->icon(Heroicon::Tag)
                    ->searchable(),

                TextColumn::make('domain')
                    ->label(__('console.domain'))
                    ->icon(Heroicon::GlobeAlt)
                    ->state(fn(Tenant $record): ?string => $record->activeDomainName())
                    ->placeholder('—'),

                ToggleColumn::make('status')
                    ->label(__('console.status'))
                    ->onIcon(Heroicon::Bolt),

                TextColumn::make('created_at')
                    ->label(__('console.created_at'))
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ])
            ->defaultSort('id', 'desc')
            ->paginated([5, 10, 25]);
    }
}
