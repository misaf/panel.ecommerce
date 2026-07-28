<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Context;
use Misaf\VendraSupport\Context\RequestJobContext;

it('stores and reads structured request and job identifiers', function (): void {
    $context = new RequestJobContext(
        tenantId: 10,
        resellerId: 20,
        subscriptionId: 30,
        paymentId: 40,
        idempotencyKey: 'payment-attempt-50',
    );

    $context->add();

    expect(RequestJobContext::current())->toEqual($context)
        ->and(Context::all())->toMatchArray([
            'tenant_id'       => 10,
            'reseller_id'     => 20,
            'subscription_id' => 30,
            'payment_id'      => 40,
            'idempotency_key' => 'payment-attempt-50',
        ]);
});

it('omits absent identifiers and restores scoped values', function (): void {
    Context::add(['tenant_id' => 10, 'payment_id' => 40]);

    $captured = (new RequestJobContext(
        resellerId: 20,
        paymentId: 41,
    ))->scope(fn(): RequestJobContext => RequestJobContext::current());

    expect($captured->tenantId)->toBe(10)
        ->and($captured->resellerId)->toBe(20)
        ->and($captured->paymentId)->toBe(41)
        ->and(Context::all())->toBe([
            'tenant_id'  => 10,
            'payment_id' => 40,
        ]);
});
