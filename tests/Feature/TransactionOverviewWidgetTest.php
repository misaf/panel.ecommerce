<?php

declare(strict_types=1);

use Filament\Support\Enums\IconPosition;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Misaf\VendraTenant\Database\Factories\TenantFactory;
use Misaf\VendraTransaction\Filament\Widgets\TransactionBonusOverviewWidget;
use Misaf\VendraTransaction\Filament\Widgets\TransactionDepositOverviewWidget;
use Misaf\VendraTransaction\Filament\Widgets\TransactionWithdrawalOverviewWidget;

it('uses the transaction menu icon on dashboard stats', function (string $widget): void {
    TenantFactory::new()->enabled()->createOne()->makeCurrent();

    /** @var array<int, Stat> $stats */
    $stats = (new ReflectionMethod($widget, 'getStats'))->invoke(app($widget));

    expect($stats)->toHaveCount(1)
        ->and($stats[0]->getIcon())->toBe(Heroicon::OutlinedBanknotes)
        ->and($stats[0]->getDescriptionIconPosition())->toBe(IconPosition::Before);
})->with([
    TransactionDepositOverviewWidget::class,
    TransactionWithdrawalOverviewWidget::class,
    TransactionBonusOverviewWidget::class,
]);
