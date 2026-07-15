<?php

declare(strict_types=1);

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Misaf\VendraSupport\Events\TenantProvisioned;
use Misaf\VendraTenant\Models\Tenant;
use Misaf\VendraTenant\Models\TenantDomain;
use Misaf\VendraUser\Models\User;

it('skips provisioning an existing tenant domain when requested', function (): void {
    $tenant = Tenant::factory()->create();
    TenantDomain::factory()->for($tenant)->create(['name' => 'existing.test']);

    $this->artisan('vendra-subscription:provision', [
        'name'         => 'Existing Tenant',
        'domain'       => 'existing.test',
        'username'     => 'admin',
        'email'        => 'admin@example.com',
        '--if-missing' => true,
    ])
        ->expectsOutput('Tenant domain [existing.test] already exists; provisioning skipped.')
        ->assertSuccessful();

    expect(Tenant::query()->count())->toBe(1)
        ->and(TenantDomain::query()->count())->toBe(1)
        ->and(User::query()->count())->toBe(0);
});

it('provisions a tenant with a provided password without printing it', function (): void {
    Event::fake([TenantProvisioned::class]);
    Queue::fake();

    $kernel = Mockery::mock(get_class(app(Kernel::class)), [app(), app(Dispatcher::class)])
        ->makePartial();
    $kernel
        ->shouldReceive('call')
        ->with('tenants:artisan', Mockery::type('array'))
        ->once()
        ->andReturn(0);
    app()->instance(Kernel::class, $kernel);
    Artisan::swap($kernel);

    $this->artisan('vendra-subscription:provision', [
        'name'       => 'Acme',
        'domain'     => 'acme.test',
        'username'   => 'admin_acme',
        'email'      => 'admin@acme.test',
        '--password' => 'secret-password',
    ])
        ->expectsConfirmation('Run default tenant seeders?', 'no')
        ->expectsOutputToContain('[provided]')
        ->doesntExpectOutputToContain('secret-password')
        ->assertSuccessful();
});

it('rejects a provided password shorter than eight characters', function (): void {
    $this->artisan('vendra-subscription:provision', [
        'name'       => 'Acme',
        'domain'     => 'acme.test',
        'username'   => 'admin_acme',
        'email'      => 'admin@acme.test',
        '--password' => 'short',
    ])
        ->expectsConfirmation('Run default tenant seeders?', 'no')
        ->assertFailed();

    expect(Tenant::query()->count())->toBe(0)
        ->and(User::query()->count())->toBe(0);
});

it('rejects an existing tenant domain without the option', function (): void {
    $tenant = Tenant::factory()->create();
    TenantDomain::factory()->for($tenant)->create(['name' => 'existing.test']);

    $this->artisan('vendra-subscription:provision', [
        'name'     => 'Duplicate Tenant',
        'domain'   => 'existing.test',
        'username' => 'admin',
        'email'    => 'admin@example.com',
    ])
        ->assertFailed();

    expect(Tenant::query()->count())->toBe(1)
        ->and(TenantDomain::query()->count())->toBe(1)
        ->and(User::query()->count())->toBe(0);
});
