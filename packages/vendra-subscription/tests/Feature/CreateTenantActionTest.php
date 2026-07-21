<?php

declare(strict_types=1);

use Misaf\VendraSubscription\Actions\CreateTenantAction;
use Misaf\VendraSubscription\Exceptions\SubscriptionLimitException;
use Misaf\VendraSubscription\Models\Account;
use Misaf\VendraSubscription\Models\Plan;
use Misaf\VendraSubscription\Models\Subscription;
use Misaf\VendraTenant\Models\Tenant;

function subscribedAccount(int $maxWebsites): Account
{
    $account = Account::factory()->create();

    Subscription::factory()
        ->for($account)
        ->for(Plan::factory()->maxWebsites($maxWebsites))
        ->create();

    return $account;
}

it('stamps the owning account on a website created under it', function (): void {
    $account = subscribedAccount(maxWebsites: 2);

    $result = app(CreateTenantAction::class)->execute(
        name: 'Acme Store',
        domain: 'acme.test',
        username: 'admin_acme',
        email: 'admin@acme.test',
        password: 'secret-password',
        account: $account,
    );

    expect($result['tenant']->account_id)->toBe($account->getKey())
        ->and($account->tenants()->count())->toBe(1);
});

it('rejects creating a website once the account reaches its plan limit', function (): void {
    $account = subscribedAccount(maxWebsites: 1);

    app(CreateTenantAction::class)->execute(
        name: 'First Store',
        domain: 'first.test',
        username: 'admin_first',
        email: 'admin@first.test',
        password: 'secret-password',
        account: $account,
    );

    app(CreateTenantAction::class)->execute(
        name: 'Second Store',
        domain: 'second.test',
        username: 'admin_second',
        email: 'admin@second.test',
        password: 'secret-password',
        account: $account,
    );
})->throws(SubscriptionLimitException::class);

it('links the first website owner to the account but not later ones', function (): void {
    $account = subscribedAccount(maxWebsites: 3);

    $first = app(CreateTenantAction::class)->execute(
        name: 'First Store',
        domain: 'first.test',
        username: 'admin_first',
        email: 'admin@first.test',
        password: 'secret-password',
        account: $account,
    );

    $second = app(CreateTenantAction::class)->execute(
        name: 'Second Store',
        domain: 'second.test',
        username: 'admin_second',
        email: 'admin@second.test',
        password: 'secret-password',
        account: $account,
    );

    expect($first['user']->account_id)->toBe($account->getKey())
        ->and($second['user']->account_id)->toBeNull();
});

it('still creates a tenant with no account for the legacy path', function (): void {
    $result = app(CreateTenantAction::class)->execute(
        name: 'Legacy Store',
        domain: 'legacy.test',
        username: 'admin_legacy',
        email: 'admin@legacy.test',
        password: 'secret-password',
    );

    expect($result['tenant'])->toBeInstanceOf(Tenant::class)
        ->and($result['tenant']->account_id)->toBeNull();
});
