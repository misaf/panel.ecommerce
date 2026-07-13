<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Queue;
use Misaf\VendraSubscription\Actions\ProvisionTenantAction;
use Misaf\VendraSupport\Events\TenantProvisioned;

beforeEach(function (): void {
    Event::fake([TenantProvisioned::class]);
    Queue::fake();
    Artisan::shouldReceive('call')->andReturn(0);
});

it('hashes a provided owner password', function (): void {
    $result = app(ProvisionTenantAction::class)->execute([
        'name'     => 'Acme',
        'domain'   => 'acme.test',
        'username' => 'admin_acme',
        'email'    => 'admin@acme.test',
    ], password: 'secret-password');

    expect($result['password'])->toBe('secret-password')
        ->and(Hash::check('secret-password', $result['user']->password))->toBeTrue();
});

it('generates a random owner password when none is provided', function (): void {
    $result = app(ProvisionTenantAction::class)->execute([
        'name'     => 'Acme',
        'domain'   => 'acme.test',
        'username' => 'admin_acme',
        'email'    => 'admin@acme.test',
    ]);

    expect($result['password'])->toHaveLength(8)
        ->and(Hash::check($result['password'], $result['user']->password))->toBeTrue();
});
