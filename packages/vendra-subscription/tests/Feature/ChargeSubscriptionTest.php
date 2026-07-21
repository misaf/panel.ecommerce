<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Model;
use Misaf\VendraSubscription\Actions\SubscribeAccountAction;
use Misaf\VendraSubscription\Models\Account;
use Misaf\VendraSubscription\Models\Plan;
use Misaf\VendraSupport\Contracts\SubscriptionCharger;
use Misaf\VendraUser\Models\User;

/**
 * Recording fake charger bound in place of the real payment provider.
 */
function fakeSubscriptionCharger(): object
{
    $charger = new class () implements SubscriptionCharger {
        /** @var array<int, array<string, mixed>> */
        public array $charges = [];

        public function available(): bool
        {
            return true;
        }

        public function charge(Model $payer, int $amount, string $currencyCode, string $reference): bool
        {
            $this->charges[] = [
                'payer'        => $payer->getKey(),
                'amount'       => $amount,
                'currencyCode' => $currencyCode,
                'reference'    => $reference,
            ];

            return true;
        }
    };

    app()->instance(SubscriptionCharger::class, $charger);

    return $charger;
}

function accountWithOwner(): Account
{
    $account = Account::factory()->create();
    User::factory()->forTenant(createTestTenant())->forAccount($account->getKey())->create();

    return $account;
}

it('charges the account owner for a paid plan', function (): void {
    $charger = fakeSubscriptionCharger();
    $account = accountWithOwner();
    $plan = Plan::factory()->priced(1500, 'USD')->create();

    $subscription = app(SubscribeAccountAction::class)->execute($account, $plan);

    expect($charger->charges)->toHaveCount(1)
        ->and($charger->charges[0]['amount'])->toBe(1500)
        ->and($charger->charges[0]['currencyCode'])->toBe('USD')
        ->and($charger->charges[0]['payer'])->toBe($account->ownerUser()->sole()->getKey())
        ->and($charger->charges[0]['reference'])->toBe('subscription:' . $subscription->getKey());
});

it('does not charge for a free plan', function (): void {
    $charger = fakeSubscriptionCharger();
    $account = accountWithOwner();

    app(SubscribeAccountAction::class)->execute($account, Plan::factory()->create());

    expect($charger->charges)->toHaveCount(0);
});

it('does not charge when the account has no owner', function (): void {
    $charger = fakeSubscriptionCharger();
    $account = Account::factory()->create();

    app(SubscribeAccountAction::class)->execute($account, Plan::factory()->priced(1000, 'USD')->create());

    expect($charger->charges)->toHaveCount(0);
});

it('does not charge during the trial period', function (): void {
    $charger = fakeSubscriptionCharger();
    $account = accountWithOwner();

    app(SubscribeAccountAction::class)->execute($account, Plan::factory()->priced(1500, 'USD')->trialDays(14)->create());

    expect($charger->charges)->toHaveCount(0);
});

it('subscribes without charging when no payment provider is available', function (): void {
    $account = accountWithOwner();

    $subscription = app(SubscribeAccountAction::class)
        ->execute($account, Plan::factory()->priced(1000, 'USD')->create());

    expect($subscription->isActive())->toBeTrue();
});
