<?php

declare(strict_types=1);

use App\Filament\Account\Resources\Websites\Pages\CreateWebsite;
use App\Filament\Account\Resources\Websites\Pages\ListWebsites;
use App\Filament\Account\Resources\Websites\WebsiteResource;
use App\Filament\Account\Widgets\AccountOverview;
use App\Filament\Account\Widgets\LatestWebsites;
use App\Filament\Account\Widgets\SubscriptionDetail;
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

function accountOwner(Account $account): User
{
    return User::factory()
        ->forTenant(createTestTenant())
        ->forAccount($account->getKey())
        ->create();
}

function actAsAccountOwner(Account $account): User
{
    $owner = accountOwner($account);
    actingAs($owner);
    Filament::setCurrentPanel(Filament::getPanel('account'));

    return $owner;
}

it('uses the Vendra logo in light and dark modes', function (): void {
    $panel = Filament::getPanel('account');

    expect($panel->getBrandName())->toBe('Vendra Account')
        ->and($panel->getBrandLogo())->toBe(asset('images/vendra-logo.svg'))
        ->and($panel->getDarkModeBrandLogo())->toBe(asset('images/vendra-logo-dark.svg'))
        ->and($panel->getBrandLogoHeight())->toBe('2rem');
});

it('renders the account dashboard with its widgets for an owner', function (): void {
    $account = Account::factory()->create();
    Subscription::factory()->for($account)->for(Plan::factory()->maxWebsites(3))->create();
    $website = Tenant::factory()->create(['account_id' => $account->getKey(), 'status' => true]);
    TenantDomain::factory()->for($website)->create(['name' => 'shop.test', 'status' => true]);

    actAsAccountOwner($account);

    livewire(AccountOverview::class)
        ->assertOk()
        ->assertSee('1 / 3');

    livewire(SubscriptionDetail::class)
        ->assertOk()
        ->assertSee(__('platform.status_active'));

    livewire(LatestWebsites::class)
        ->call('loadTable')
        ->assertOk()
        ->assertCanSeeTableRecords([$website]);
});

it('hides subscription and website widgets until the account has a website', function (): void {
    $account = Account::factory()->create();

    actAsAccountOwner($account);

    expect(SubscriptionDetail::canView())->toBeFalse()
        ->and(LatestWebsites::canView())->toBeFalse();
});

it('grants account panel access only to account owners', function (): void {
    $account = Account::factory()->create();
    $owner = accountOwner($account);
    $regular = User::factory()->forTenant(createTestTenant())->create();

    $panel = Filament::getPanel('account');

    expect($owner->canAccessPanel($panel))->toBeTrue()
        ->and($regular->canAccessPanel($panel))->toBeFalse();
});

it('keeps disabled owners in the panel but blocks website operations', function (): void {
    $account = Account::factory()->create(['status' => false]);
    Subscription::factory()->for($account)->for(Plan::factory()->maxWebsites(2))->create();
    $website = Tenant::factory()->create(['account_id' => $account->getKey(), 'status' => true]);

    $owner = actAsAccountOwner($account);

    expect($owner->canAccessPanel(Filament::getPanel('account')))->toBeTrue()
        ->and(WebsiteResource::canCreate())->toBeFalse();

    livewire(ListWebsites::class)
        ->assertActionHidden(TestAction::make('replaceDomain')->table($website))
        ->assertActionHidden(TestAction::make('delete')->table($website));
});

it('shows an owner only their own account websites', function (): void {
    $accountA = Account::factory()->create();
    $accountB = Account::factory()->create();
    $websiteA = Tenant::factory()->create(['account_id' => $accountA->getKey(), 'status' => true]);
    $websiteB = Tenant::factory()->create(['account_id' => $accountB->getKey(), 'status' => true]);

    actAsAccountOwner($accountA);

    livewire(ListWebsites::class)
        ->call('loadTable')
        ->assertCanSeeTableRecords([$websiteA])
        ->assertCanNotSeeTableRecords([$websiteB]);
});

it('lets an owner create a website within the plan limit', function (): void {
    $account = Account::factory()->create();
    Subscription::factory()->for($account)->for(Plan::factory()->maxWebsites(2))->create();

    actAsAccountOwner($account);

    livewire(CreateWebsite::class)
        ->fillForm([
            'name'           => 'Acme',
            'domain'         => 'acme.test',
            'owner_username' => 'admin_acme',
            'owner_email'    => 'admin@acme.test',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    assertDatabaseHas('tenants', [
        'name'       => 'Acme',
        'account_id' => $account->getKey(),
    ]);
});

it('lets an owner soft-delete their own website', function (): void {
    $account = Account::factory()->create();
    $website = Tenant::factory()->create(['account_id' => $account->getKey(), 'status' => true]);

    actAsAccountOwner($account);

    livewire(ListWebsites::class)
        ->callAction(TestAction::make('delete')->table($website))
        ->assertHasNoErrors();

    expect($website->fresh()?->trashed())->toBeTrue();
});

it('lets an owner replace their website domain, keeping the old one as trashed history', function (): void {
    $account = Account::factory()->create();
    $website = Tenant::factory()->create(['account_id' => $account->getKey(), 'status' => true]);
    TenantDomain::factory()->for($website)->create(['name' => 'old.test', 'status' => true]);

    actAsAccountOwner($account);

    livewire(ListWebsites::class)
        ->callAction(TestAction::make('replaceDomain')->table($website), ['domain' => 'new.test'])
        ->assertHasNoErrors();

    expect($website->execute(fn() => $website->tenantDomains()->where('status', true)->value('name')))->toBe('new.test')
        ->and($website->execute(fn() => $website->tenantDomains()->onlyTrashed()->where('name', 'old.test')->exists()))->toBeTrue();
});

it('validates the replacement domain format and active-domain uniqueness', function (): void {
    $account = Account::factory()->create();
    $website = Tenant::factory()->create(['account_id' => $account->getKey(), 'status' => true]);
    TenantDomain::factory()->for($website)->create(['name' => 'current.test', 'status' => true]);

    $other = Tenant::factory()->create();
    TenantDomain::factory()->for($other)->create(['name' => 'taken.test', 'status' => true]);

    actAsAccountOwner($account);

    livewire(ListWebsites::class)
        ->callAction(TestAction::make('replaceDomain')->table($website), ['domain' => 'not a domain'])
        ->assertHasActionErrors(['domain' => 'regex']);

    livewire(ListWebsites::class)
        ->callAction(TestAction::make('replaceDomain')->table($website), ['domain' => 'taken.test'])
        ->assertHasActionErrors(['domain' => 'unique']);
});

it('blocks an owner from exceeding the plan limit', function (): void {
    $account = Account::factory()->create();
    Subscription::factory()->for($account)->for(Plan::factory()->maxWebsites(1))->create();
    Tenant::factory()->create(['account_id' => $account->getKey()]);

    actAsAccountOwner($account);

    livewire(CreateWebsite::class)
        ->fillForm([
            'name'           => 'Second Store',
            'domain'         => 'second.test',
            'owner_username' => 'admin_second',
            'owner_email'    => 'admin@second.test',
        ])
        ->call('create');

    assertDatabaseMissing('tenants', ['name' => 'Second Store']);
});
