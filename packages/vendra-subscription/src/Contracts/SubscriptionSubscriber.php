<?php

declare(strict_types=1);

namespace Misaf\VendraSubscription\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notification;
use Misaf\VendraSubscription\Models\Subscription;
use Misaf\VendraSubscription\Models\SubscriptionPayment;

/**
 * A billing entity that can hold plan-limited subscriptions. Subscriptions are
 * polymorphic; this contract lets the subscription engine (subscribe, activate,
 * enforce) operate on any subscriber without knowing the concrete model. The
 * host app currently binds it to App\Models\Reseller, but a future subscriber
 * only needs to implement this interface and register in the morph map.
 *
 * The contract exposes subscription operations rather than the raw Eloquent
 * relation so the engine stays free of ORM details. Implementations are Eloquent
 * models; type against Model&SubscriptionSubscriber where model behaviour (e.g.
 * getKey) is also required.
 */
interface SubscriptionSubscriber
{
    /**
     * The subscriber's currently active subscription, if any.
     */
    public function activeSubscription(): ?Subscription;

    /**
     * The subscriber's most recently started subscription, if any.
     */
    public function latestSubscription(): ?Subscription;

    /**
     * Whether the subscriber has ever held a subscription.
     */
    public function hasSubscriptions(): bool;

    /**
     * Create a subscription period owned by this subscriber.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function createSubscription(array $attributes): Subscription;

    /**
     * Cancel the subscriber's active subscriptions, optionally keeping one.
     *
     * @return int the number of subscriptions cancelled
     */
    public function cancelActiveSubscriptions(?int $exceptKey = null): int;

    /**
     * Cancel the subscriber's pending-payment subscriptions with the given keys.
     *
     * @param  array<int, int>  $keys
     * @return int the number of subscriptions cancelled
     */
    public function cancelPendingPaymentSubscriptions(array $keys): int;

    /**
     * Lock and return the subscriber's open (non-terminal) subscription payments.
     *
     * @return Collection<int, SubscriptionPayment>
     */
    public function lockOpenSubscriptionPayments(): Collection;

    /**
     * Whether the subscriber is enabled to create and hold properties.
     */
    public function isSubscriptionEnabled(): bool;

    /**
     * Whether there is an owner contact to receive billing notifications.
     */
    public function hasOwnerContact(): bool;

    /**
     * Deliver a billing notification to the subscriber's owner contact.
     */
    public function notifyOwner(Notification $notification): void;

    /**
     * The user billed for the subscriber's paid subscriptions, if any.
     */
    public function subscriptionPayer(): ?Model;

    /**
     * Total number of billable properties the subscriber currently holds.
     */
    public function subscribedPropertyCount(): int;

    /**
     * Number of the subscriber's properties that are currently active.
     */
    public function activeSubscribedPropertyCount(): int;

    /**
     * Suspend every active property after a lapse.
     *
     * @return int the number of properties suspended
     */
    public function suspendActiveProperties(): int;

    /**
     * Reactivate every suspended property after payment succeeds.
     *
     * @return int the number of properties reactivated
     */
    public function reactivateSuspendedProperties(): int;

    /**
     * Re-fetch this subscriber under a row lock for a subscription transaction.
     */
    public function lockForSubscription(): static;
}
