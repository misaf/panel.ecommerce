<?php

declare(strict_types=1);

namespace App\Filament\Account\Widgets;

use App\Filament\Account\Concerns\InteractsWithCurrentAccount;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Misaf\VendraTenant\Models\Tenant;

final class LatestWebsites extends BaseWidget
{
    use InteractsWithCurrentAccount;

    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        $account = (new self())->currentAccount();

        return null !== $account && $account->tenants()->exists();
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading(__('platform.websites'))
            ->query(fn(): Builder => Tenant::query()->where('account_id', $this->currentAccount()?->getKey() ?? 0))
            ->columns([
                TextColumn::make('name')
                    ->label(__('platform.name'))
                    ->searchable(),

                TextColumn::make('domain')
                    ->label(__('platform.domain'))
                    ->state(fn(Tenant $record): ?string => $record->activeDomainName())
                    ->placeholder('—'),

                IconColumn::make('status')
                    ->label(__('platform.status'))
                    ->boolean(),

                TextColumn::make('created_at')
                    ->label(__('platform.created_at'))
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ])
            ->defaultSort('id', 'desc')
            ->paginated([5, 10, 25]);
    }
}
