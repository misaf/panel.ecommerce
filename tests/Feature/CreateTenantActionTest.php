<?php

declare(strict_types=1);

use App\Actions\CreateTenantAction;
use App\Exceptions\SubscriptionLimitException;
use App\Models\Reseller;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Misaf\VendraSubscription\Models\Plan;
use Misaf\VendraSubscription\Models\Subscription;
use Misaf\VendraTenant\Models\Tenant;
use Misaf\VendraTenant\Models\TenantDomain;
use Misaf\VendraUser\Models\User;

function subscribedReseller(int $maxUnits): Reseller
{
    $reseller = Reseller::factory()->create();

    Subscription::factory()
        ->forSubscriber($reseller)
        ->for(Plan::factory()->maxUnits($maxUnits))
        ->create();

    return $reseller;
}

it('stamps the owning reseller on a property created under it', function (): void {
    $reseller = subscribedReseller(maxUnits: 2);

    $result = app(CreateTenantAction::class)->execute(
        name: 'Acme Store',
        domain: 'acme.test',
        username: 'admin_acme',
        email: 'admin@acme.test',
        password: 'secret-password',
        reseller: $reseller,
    );

    expect($result['tenant']->reseller_id)->toBe($reseller->getKey())
        ->and($reseller->tenants()->count())->toBe(1);
});

it('rejects creating a property once the reseller reaches its plan limit', function (): void {
    $reseller = subscribedReseller(maxUnits: 1);

    app(CreateTenantAction::class)->execute(
        name: 'First Store',
        domain: 'first.test',
        username: 'admin_first',
        email: 'admin@first.test',
        password: 'secret-password',
        reseller: $reseller,
    );

    app(CreateTenantAction::class)->execute(
        name: 'Second Store',
        domain: 'second.test',
        username: 'admin_second',
        email: 'admin@second.test',
        password: 'secret-password',
        reseller: $reseller,
    );
})->throws(SubscriptionLimitException::class);

it('links the first property owner to the reseller but not later ones', function (): void {
    $reseller = subscribedReseller(maxUnits: 3);

    $first = app(CreateTenantAction::class)->execute(
        name: 'First Store',
        domain: 'first.test',
        username: 'admin_first',
        email: 'admin@first.test',
        password: 'secret-password',
        reseller: $reseller,
    );

    $second = app(CreateTenantAction::class)->execute(
        name: 'Second Store',
        domain: 'second.test',
        username: 'admin_second',
        email: 'admin@second.test',
        password: 'secret-password',
        reseller: $reseller,
    );

    expect($first['user']->reseller_id)->toBe($reseller->getKey())
        ->and($second['user']->reseller_id)->toBeNull();
});

it('rejects assigning a second active owner to a reseller', function (): void {
    $reseller = subscribedReseller(maxUnits: 2);

    $first = app(CreateTenantAction::class)->execute(
        name: 'First Store',
        domain: 'first.test',
        username: 'admin_first',
        email: 'admin@first.test',
        password: 'secret-password',
        reseller: $reseller,
    );

    expect(fn(): User => $first['tenant']->execute(
        fn(): User => User::factory()->forReseller($reseller->getKey())->create(),
    ))->toThrow(QueryException::class);
});

it('allows replacing a soft-deleted reseller owner', function (): void {
    $reseller = subscribedReseller(maxUnits: 2);

    $first = app(CreateTenantAction::class)->execute(
        name: 'First Store',
        domain: 'first.test',
        username: 'admin_first',
        email: 'admin@first.test',
        password: 'secret-password',
        reseller: $reseller,
    );

    $first['user']->delete();

    $replacement = $first['tenant']->execute(
        fn(): User => User::factory()->forReseller($reseller->getKey())->create(),
    );

    expect($replacement->reseller_id)->toBe($reseller->getKey());
});

it('still creates a tenant with no reseller for the legacy path', function (): void {
    $result = app(CreateTenantAction::class)->execute(
        name: 'Legacy Store',
        domain: 'legacy.test',
        username: 'admin_legacy',
        email: 'admin@legacy.test',
        password: 'secret-password',
    );

    expect($result['tenant'])->toBeInstanceOf(Tenant::class)
        ->and($result['tenant']->reseller_id)->toBeNull();
});

it('rejects invalid and duplicate active domains outside Filament', function (string $domain): void {
    $existingProperty = Tenant::factory()->create();
    TenantDomain::factory()->for($existingProperty)->create(['name' => 'taken.test', 'status' => true]);

    app(CreateTenantAction::class)->execute(
        name: 'Rejected Store',
        domain: $domain,
        username: 'admin_rejected',
        email: 'admin@rejected.test',
        password: 'secret-password',
    );
})->with([
    'invalid format'   => 'not a domain',
    'duplicate domain' => 'taken.test',
])->throws(ValidationException::class);
