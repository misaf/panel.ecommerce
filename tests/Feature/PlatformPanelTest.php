<?php

declare(strict_types=1);

use App\Filament\Platform\Resources\Plans\Pages\CreatePlan;
use App\Filament\Platform\Resources\Websites\Pages\CreateWebsite;
use App\Filament\Platform\Resources\Websites\Pages\EditWebsite;
use App\Filament\Platform\Resources\Websites\Pages\ListWebsites;
use App\Filament\Platform\Resources\Websites\RelationManagers\DomainsRelationManager;
use Filament\Actions\Testing\TestAction;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Event;
use Misaf\VendraSubscription\Models\Account;
use Misaf\VendraSubscription\Models\Plan;
use Misaf\VendraSubscription\Models\Subscription;
use Misaf\VendraSupport\Events\TenantProvisioned;
use Misaf\VendraTenant\Models\Tenant;
use Misaf\VendraTenant\Models\TenantDomain;
use Misaf\VendraUser\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
    Event::fake([TenantProvisioned::class]);
    Artisan::shouldReceive('call')->andReturn(0);
});

function platformAdmin(): User
{
    $tenant = createTestTenant();

    return User::factory()->forTenant($tenant)->platformAdmin()->create();
}

function actAsPlatformAdmin(): User
{
    $admin = platformAdmin();
    actingAs($admin);
    Filament::setCurrentPanel(Filament::getPanel('platform'));

    return $admin;
}

it('grants platform panel access only to platform admins', function (): void {
    $tenant = createTestTenant();
    $admin = User::factory()->forTenant($tenant)->platformAdmin()->create();
    $regular = User::factory()->forTenant($tenant)->create();

    $panel = Filament::getPanel('platform');

    expect($admin->canAccessPanel($panel))->toBeTrue()
        ->and($regular->canAccessPanel($panel))->toBeFalse();
});

it('lets a platform admin create a plan', function (): void {
    actAsPlatformAdmin();

    livewire(CreatePlan::class)
        ->fillForm([
            'name'         => 'Pro',
            'max_websites' => 3,
            'period_unit'  => 'month',
            'period_count' => 1,
            'status'       => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    assertDatabaseHas('plans', [
        'name'         => 'Pro',
        'max_websites' => 3,
        'period_unit'  => 'month',
    ]);
});

it('creates a website for an account within its plan limit', function (): void {
    actAsPlatformAdmin();

    $account = Account::factory()->create();
    Subscription::factory()->for($account)->for(Plan::factory()->maxWebsites(2))->create();

    livewire(CreateWebsite::class)
        ->fillForm([
            'account_id'     => $account->getKey(),
            'name'           => 'Acme',
            'domain'         => 'acme.test',
            'owner_username' => 'admin_acme',
            'owner_email'    => 'admin@acme.test',
            'status'         => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    assertDatabaseHas('tenants', [
        'name'       => 'Acme',
        'account_id' => $account->getKey(),
    ]);
});

it('blocks website creation once the account reaches its plan limit', function (): void {
    actAsPlatformAdmin();

    $account = Account::factory()->create();
    Subscription::factory()->for($account)->for(Plan::factory()->maxWebsites(1))->create();
    Tenant::factory()->create(['account_id' => $account->getKey()]);

    livewire(CreateWebsite::class)
        ->fillForm([
            'account_id'     => $account->getKey(),
            'name'           => 'Second Store',
            'domain'         => 'second.test',
            'owner_username' => 'admin_second',
            'owner_email'    => 'admin@second.test',
            'status'         => true,
        ])
        ->call('create');

    assertDatabaseMissing('tenants', ['name' => 'Second Store']);
    expect($account->tenants()->count())->toBe(1);
});

it('lets a platform admin replace a domain and shows the old one in trashed history', function (): void {
    actAsPlatformAdmin();

    $website = Tenant::factory()->create(['status' => true]);
    $original = TenantDomain::factory()->for($website)->create(['name' => 'old.test', 'status' => true]);

    livewire(ListWebsites::class)
        ->callAction(TestAction::make('replaceDomain')->table($website), ['domain' => 'new.test'])
        ->assertHasNoErrors();

    $current = $website->execute(fn() => $website->tenantDomains()->where('status', true)->value('name'));
    expect($current)->toBe('new.test');

    livewire(DomainsRelationManager::class, [
        'ownerRecord' => $website,
        'pageClass'   => EditWebsite::class,
    ])
        ->call('loadTable')
        ->filterTable('trashed', ['value' => '0'])
        ->assertCanSeeTableRecords([$original]);
});

it('lets a platform admin soft-delete then restore a website', function (): void {
    actAsPlatformAdmin();

    $website = Tenant::factory()->create(['status' => true]);

    livewire(ListWebsites::class)
        ->callAction(TestAction::make('delete')->table($website))
        ->assertHasNoErrors();

    expect($website->fresh()?->trashed())->toBeTrue();

    livewire(ListWebsites::class)
        ->filterTable('trashed', ['value' => 'trashed'])
        ->callAction(TestAction::make('restore')->table($website))
        ->assertHasNoErrors();

    expect($website->fresh()?->trashed())->toBeFalse();
});

it('lets a platform admin permanently delete a trashed website', function (): void {
    actAsPlatformAdmin();

    $website = Tenant::factory()->trashed()->create();

    livewire(ListWebsites::class)
        ->filterTable('trashed', ['value' => 'trashed'])
        ->callAction(TestAction::make('forceDelete')->table($website))
        ->assertHasNoErrors();

    assertDatabaseMissing('tenants', ['id' => $website->getKey()]);
});
