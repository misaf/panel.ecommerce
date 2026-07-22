<?php

declare(strict_types=1);

use App\Actions\ChargeSubscriptionAction;
use App\Actions\SubscribeResellerAction;
use App\Models\Reseller;
use Illuminate\Database\Eloquent\Model;
use Misaf\VendraSubscription\Exceptions\SubscriptionPaymentException;
use Misaf\VendraSubscription\Models\Plan;
use Misaf\VendraSupport\Contracts\SubscriptionCharger;
use Misaf\VendraUser\Models\User;

/**
 * Recording fake charger bound in place of the real payment provider.
 */
function fakeSubscriptionCharger(bool $succeeds = true): object
{
    $charger = new class ($succeeds) implements SubscriptionCharger {
        /** @var array<int, array<string, mixed>> */
        public array $charges = [];

        public function __construct(private readonly bool $succeeds) {}

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

            return $this->succeeds;
        }
    };

    app()->instance(SubscriptionCharger::class, $charger);

    return $charger;
}

function resellerWithOwner(): Reseller
{
    $reseller = Reseller::factory()->create();
    User::factory()->forTenant(createTestTenant())->forReseller($reseller->getKey())->create();

    return $reseller;
}

it('charges the reseller owner for a paid plan', function (): void {
    $charger = fakeSubscriptionCharger();
    $reseller = resellerWithOwner();
    $plan = Plan::factory()->priced(1500, 'USD')->create();

    $subscription = app(SubscribeResellerAction::class)->execute($reseller, $plan);

    expect($charger->charges)->toHaveCount(1)
        ->and($charger->charges[0]['amount'])->toBe(1500)
        ->and($charger->charges[0]['currencyCode'])->toBe('USD')
        ->and($charger->charges[0]['payer'])->toBe($reseller->ownerUser()->sole()->getKey())
        ->and($charger->charges[0]['reference'])->toBe('subscription:' . $subscription->getKey());
});

it('does not charge for a free plan', function (): void {
    $charger = fakeSubscriptionCharger();
    $reseller = resellerWithOwner();

    app(SubscribeResellerAction::class)->execute($reseller, Plan::factory()->create());

    expect($charger->charges)->toHaveCount(0);
});

it('does not charge when the reseller has no owner', function (): void {
    $charger = fakeSubscriptionCharger();
    $reseller = Reseller::factory()->create();

    app(SubscribeResellerAction::class)->execute($reseller, Plan::factory()->priced(1000, 'USD')->create());

    expect($charger->charges)->toHaveCount(0);
});

it('does not charge during the trial period', function (): void {
    $charger = fakeSubscriptionCharger();
    $reseller = resellerWithOwner();

    app(SubscribeResellerAction::class)->execute($reseller, Plan::factory()->priced(1500, 'USD')->trialDays(14)->create());

    expect($charger->charges)->toHaveCount(0);
});

it('subscribes without charging when no payment provider is available', function (): void {
    $reseller = resellerWithOwner();

    $subscription = app(SubscribeResellerAction::class)
        ->execute($reseller, Plan::factory()->priced(1000, 'USD')->create());

    expect($subscription->isActive())->toBeTrue();
});

it('rolls back activation when the payment provider declines the charge', function (): void {
    $charger = fakeSubscriptionCharger(succeeds: false);
    $reseller = resellerWithOwner();
    $action = new SubscribeResellerAction(new ChargeSubscriptionAction($charger));
    $current = $action->execute($reseller, Plan::factory()->create());

    $paidPlan = Plan::factory()->priced(1500, 'USD')->create();

    expect(fn() => $action->execute($reseller, $paidPlan))
        ->toThrow(SubscriptionPaymentException::class);

    expect($reseller->subscriptions()->count())->toBe(1)
        ->and($reseller->activeSubscription()?->is($current))->toBeTrue();
});
