<?php

declare(strict_types=1);

use App\Filament\Platform\Resources\Accounts\Pages\CreateAccount;
use App\Filament\Platform\Resources\Plans\Pages\CreatePlan;
use App\Filament\Platform\Resources\Plans\Pages\ListPlans;
use App\Filament\Platform\Resources\Websites\Pages\CreateWebsite;
use App\Filament\Platform\Resources\Websites\Pages\EditWebsite;
use App\Filament\Platform\Resources\Websites\Pages\ListWebsites;
use App\Filament\Platform\Resources\Websites\RelationManagers\DomainsRelationManager;
use App\Models\PlatformUser;
use Database\Seeders\PlatformUserSeeder;
use Filament\Actions\Testing\TestAction;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
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

function platformAdmin(): PlatformUser
{
    return PlatformUser::factory()->create();
}

function actAsPlatformAdmin(): PlatformUser
{
    $admin = platformAdmin();
    actingAs($admin, 'platform');
    Filament::setCurrentPanel(Filament::getPanel('platform'));

    return $admin;
}

it('uses the Vendra logo in light and dark modes', function (): void {
    $panel = Filament::getPanel('platform');

    expect($panel->getBrandName())->toBe('Vendra Platform')
        ->and($panel->getBrandLogo())->toBe(asset('images/vendra-logo.svg'))
        ->and($panel->getDarkModeBrandLogo())->toBe(asset('images/vendra-logo-dark.svg'))
        ->and($panel->getBrandLogoHeight())->toBe('2rem');
});

it('isolates platform operators from application users', function (): void {
    $tenant = createTestTenant();
    $admin = PlatformUser::factory()->create();
    $regular = User::factory()->forTenant($tenant)->create();

    $panel = Filament::getPanel('platform');

    expect($admin->canAccessPanel($panel))->toBeTrue()
        ->and($regular->canAccessPanel($panel))->toBeFalse()
        ->and($panel->getAuthGuard())->toBe('platform')
        ->and($panel->getAuthPasswordBroker())->toBe('platform_users')
        ->and(config('auth.guards.platform.provider'))->toBe('platform_users')
        ->and(config('auth.providers.platform_users.model'))->toBe(PlatformUser::class)
        ->and(Filament::getPanel('account')->getAuthGuard())->toBe('web')
        ->and(Filament::getPanel('admin')->getAuthGuard())->toBe('web')
        ->and(Filament::getPanel('user')->getAuthGuard())->toBe('web');

    actingAs($admin, 'platform');

    expect(auth('platform')->id())->toBe($admin->getKey())
        ->and(auth('web')->check())->toBeFalse();
});

it('redirects an application user away from the platform panel', function (): void {
    actingAs(User::factory()->forTenant(createTestTenant())->create());

    $this->get('/platform')
        ->assertRedirect('/platform/login');
});

it('allows a verified platform operator into the platform panel', function (): void {
    actingAs(PlatformUser::factory()->create(), 'platform');

    $this->get('/platform')->assertOk();
});

it('seeds the initial platform operator only from explicit credentials', function (): void {
    Config::set('platform.operator', [
        'username' => 'platform_owner',
        'email'    => 'OWNER@EXAMPLE.TEST',
        'password' => 'a-secure-platform-password',
    ]);

    app(PlatformUserSeeder::class)->run();

    $operator = PlatformUser::query()->sole();

    expect($operator->username)->toBe('platform_owner')
        ->and($operator->email)->toBe('owner@example.test')
        ->and($operator->hasVerifiedEmail())->toBeTrue()
        ->and(Hash::check('a-secure-platform-password', $operator->password))->toBeTrue();
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

it('requires a currency for a paid plan', function (): void {
    actAsPlatformAdmin();

    livewire(CreatePlan::class)
        ->fillForm([
            'name'          => 'Paid',
            'max_websites'  => 3,
            'period_unit'   => 'month',
            'period_count'  => 1,
            'price'         => 1500,
            'currency_code' => null,
            'status'        => true,
        ])
        ->call('create')
        ->assertHasFormErrors(['currency_code' => 'required']);
});

it('honors a disabled status when creating an account', function (): void {
    actAsPlatformAdmin();

    livewire(CreateAccount::class)
        ->fillForm([
            'name'    => 'Paused Account',
            'plan_id' => Plan::factory()->create()->getKey(),
            'status'  => false,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Account::query()->where('name', 'Paused Account')->sole()->status)->toBeFalse();
});

it('prevents deleting a plan used by subscriptions', function (): void {
    actAsPlatformAdmin();

    $plan = Plan::factory()->create();
    Subscription::factory()->for($plan)->create();

    livewire(ListPlans::class)
        ->assertActionHidden(TestAction::make('delete')->table($plan));
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

it('validates website domains during creation', function (): void {
    actAsPlatformAdmin();

    $account = Account::factory()->create();
    Subscription::factory()->for($account)->for(Plan::factory()->maxWebsites(3))->create();
    $existingWebsite = Tenant::factory()->create();
    TenantDomain::factory()->for($existingWebsite)->create(['name' => 'taken.test', 'status' => true]);

    livewire(CreateWebsite::class)
        ->fillForm([
            'account_id'     => $account->getKey(),
            'name'           => 'Invalid Domain',
            'domain'         => 'not a domain',
            'owner_username' => 'invalid_domain',
            'owner_email'    => 'invalid@example.test',
            'status'         => true,
        ])
        ->call('create')
        ->assertHasFormErrors(['domain' => 'regex']);

    livewire(CreateWebsite::class)
        ->fillForm([
            'account_id'     => $account->getKey(),
            'name'           => 'Duplicate Domain',
            'domain'         => 'taken.test',
            'owner_username' => 'duplicate_domain',
            'owner_email'    => 'duplicate@example.test',
            'status'         => true,
        ])
        ->call('create')
        ->assertHasFormErrors(['domain' => 'unique']);
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
