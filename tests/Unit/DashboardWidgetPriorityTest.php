<?php

declare(strict_types=1);

use App\Filament\Admin\Pages\Dashboard as AdminDashboard;
use App\Filament\User\Widgets\LatestTransactionTableWidget as UserLatestTransactionTableWidget;
use Filament\Facades\Filament;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\View;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Misaf\VendraActivityLog\Filament\Widgets\LatestActivityLogTableWidget;
use Misaf\VendraAffiliate\Filament\Widgets\AffiliateOverviewWidget;
use Misaf\VendraAffiliate\Filament\Widgets\UserAffiliateOverviewWidget;
use Misaf\VendraMultimedia\Filament\Widgets\LatestMultimediaTableWidget;
use Misaf\VendraProduct\Filament\Widgets\ProductOverviewWidget;
use Misaf\VendraTransaction\Filament\Widgets\LatestTransactionTableWidget;
use Misaf\VendraTransaction\Filament\Widgets\TransactionBonusOverviewWidget;
use Misaf\VendraTransaction\Filament\Widgets\TransactionDepositOverviewWidget;
use Misaf\VendraTransaction\Filament\Widgets\TransactionWithdrawalOverviewWidget;
use Misaf\VendraUser\Filament\Widgets\LatestUsersWidget;

it('orders admin dashboard widgets by unique priority', function (): void {
    $expectedWidgets = [
        ProductOverviewWidget::class,
        TransactionDepositOverviewWidget::class,
        TransactionWithdrawalOverviewWidget::class,
        TransactionBonusOverviewWidget::class,
        AffiliateOverviewWidget::class,
        LatestTransactionTableWidget::class,
        LatestUsersWidget::class,
        LatestActivityLogTableWidget::class,
        LatestMultimediaTableWidget::class,
    ];

    $registeredWidgets = array_values(array_filter(
        Filament::getPanel('admin')->getWidgets(),
        fn(mixed $widget): bool => is_string($widget) && in_array($widget, $expectedWidgets, true),
    ));

    expect($registeredWidgets)->toBe($expectedWidgets)
        ->and(array_map(
            fn(string $widget): int => $widget::getSort(),
            $expectedWidgets,
        ))->toBe(range(1, 9));
});

it('orders user dashboard widgets by unique priority', function (): void {
    $expectedWidgets = [
        UserAffiliateOverviewWidget::class,
        UserLatestTransactionTableWidget::class,
    ];

    $registeredWidgets = array_values(array_filter(
        Filament::getPanel('user')->getWidgets(),
        fn(mixed $widget): bool => is_string($widget) && in_array($widget, $expectedWidgets, true),
    ));

    expect($registeredWidgets)->toBe($expectedWidgets)
        ->and(array_map(
            fn(string $widget): int => $widget::getSort(),
            $expectedWidgets,
        ))->toBe(range(1, 2));
});

it('does not poll dashboard widgets', function (): void {
    $statsWidgets = [
        ProductOverviewWidget::class,
        TransactionDepositOverviewWidget::class,
        TransactionWithdrawalOverviewWidget::class,
        TransactionBonusOverviewWidget::class,
        AffiliateOverviewWidget::class,
        UserAffiliateOverviewWidget::class,
    ];

    foreach ($statsWidgets as $widgetClass) {
        $pollingInterval = (new ReflectionMethod($widgetClass, 'getPollingInterval'))
            ->invoke(app($widgetClass));

        expect($pollingInterval)->toBeNull();
    }

    $tableWidgets = [
        LatestTransactionTableWidget::class,
        LatestUsersWidget::class,
        LatestActivityLogTableWidget::class,
        LatestMultimediaTableWidget::class,
        UserLatestTransactionTableWidget::class,
    ];

    $locale = app()->getLocale();

    try {
        app()->setLocale('fa');

        foreach ($tableWidgets as $widgetClass) {
            /** @var TableWidget $widget */
            $widget = app($widgetClass);

            expect($widget->table(Table::make($widget))->getPollingInterval())->toBeNull();
        }
    } finally {
        app()->setLocale($locale);
    }
});

it('uses consistent dashboard widget widths', function (): void {
    $fullWidthWidgets = [
        ProductOverviewWidget::class,
        AffiliateOverviewWidget::class,
        LatestTransactionTableWidget::class,
        LatestUsersWidget::class,
        LatestActivityLogTableWidget::class,
        LatestMultimediaTableWidget::class,
    ];

    foreach ($fullWidthWidgets as $widgetClass) {
        expect(app($widgetClass)->getColumnSpan())->toBe('full');
    }

    $metricWidgets = [
        TransactionDepositOverviewWidget::class,
        TransactionWithdrawalOverviewWidget::class,
        TransactionBonusOverviewWidget::class,
    ];

    foreach ($metricWidgets as $widgetClass) {
        expect(app($widgetClass)->getColumnSpan())->toBe(['sm' => 1]);
    }
});

it('groups all dashboard widgets beside a markdown changelog', function (): void {
    $currentPanel = Filament::getCurrentPanel();
    $locale = app()->getLocale();

    try {
        Filament::setCurrentPanel('admin');
        app()->setLocale('en');

        $dashboard = app(AdminDashboard::class);
        $layout = $dashboard->getWidgetsContentComponent();
        $components = $layout->getDefaultChildComponents();

        expect($layout)->toBeInstanceOf(Grid::class)
            ->and($layout->getColumns('lg'))->toBe(3)
            ->and($components)->toHaveCount(2)
            ->and($components[0])->toBeInstanceOf(Group::class)
            ->and($components[0]->getColumns('md'))->toBe(3)
            ->and($components[0]->getColumnSpan('lg'))->toBe(2)
            ->and($components[0]->getDefaultChildComponents())->not->toBeEmpty()
            ->and($components[1])->toBeInstanceOf(Section::class)
            ->and($components[1]->getColumnSpan('lg'))->toBe(1);

        $markdown = $components[1]->getDefaultChildComponents()[0];

        expect($markdown)->toBeInstanceOf(View::class)
            ->and((string) $markdown->getViewData()['content'])
            ->toContain('<h3>Dashboard widgets</h3>')
            ->toContain('<li>Shorter labels</li>')
            ->toContain('<li>Consistent grid sizing</li>');
    } finally {
        app()->setLocale($locale);
        Filament::setCurrentPanel($currentPanel);
    }
});
