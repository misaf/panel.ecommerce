<?php

declare(strict_types=1);

use App\Models\Reseller;
use Illuminate\Database\Eloquent\Model;
use Misaf\VendraSubscription\Contracts\SubscriptionSubscriber;
use Misaf\VendraSubscription\Support\SubscriptionRegistry;

it('binds the reseller as a subscription subscriber', function (): void {
    $reseller = Reseller::factory()->create();

    expect($reseller)
        ->toBeInstanceOf(SubscriptionSubscriber::class)
        ->toBeInstanceOf(Model::class);
});

it('locks the subscriber to its own row for a subscription transaction', function (): void {
    $reseller = Reseller::factory()->create();

    $locked = app(SubscriptionRegistry::class)->lockSubscriber($reseller);

    expect($locked)
        ->toBeInstanceOf(Reseller::class)
        ->and($locked->getKey())->toBe($reseller->getKey());
});
