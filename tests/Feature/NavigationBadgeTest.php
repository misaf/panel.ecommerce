<?php

declare(strict_types=1);

use Misaf\VendraCart\Database\Factories\CartFactory;
use Misaf\VendraCart\Filament\Clusters\Resources\Carts\CartResource;
use Misaf\VendraTenant\Database\Factories\TenantFactory;
use Misaf\VendraTransaction\Database\Factories\TransactionFactory;
use Misaf\VendraTransaction\Filament\Clusters\Resources\Transactions\TransactionResource;
use Misaf\VendraUser\Database\Factories\UserFactory;
use Misaf\VendraUser\Filament\Clusters\Resources\Users\UserResource;

it('shows record counts on important navigation items', function (): void {
    $tenant = TenantFactory::new()->enabled()->createOne();
    $tenant->makeCurrent();

    $user = UserFactory::new()->createOne();
    UserFactory::new()->createOne();

    TransactionFactory::new()
        ->count(3)
        ->forUser($user)
        ->create();

    CartFactory::new()->count(4)->create();

    TenantFactory::new()->enabled()->createOne()->makeCurrent();

    UserFactory::new()->createOne();
    TransactionFactory::new()->createOne();
    CartFactory::new()->createOne();

    $tenant->makeCurrent();

    expect(UserResource::getNavigationBadge())->toBe('2')
        ->and(TransactionResource::getNavigationBadge())->toBe('3')
        ->and(CartResource::getNavigationBadge())->toBe('4')
        ->and(UserResource::getNavigationBadgeTooltip())->toBe(__('vendra-user::navigation.navigation_badge_tooltip'))
        ->and(TransactionResource::getNavigationBadgeTooltip())->toBe(__('vendra-transaction::navigation.navigation_badge_tooltip'))
        ->and(CartResource::getNavigationBadgeTooltip())->toBe(__('vendra-cart::navigation.navigation_badge_tooltip'));
});
