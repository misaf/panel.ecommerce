<?php

declare(strict_types=1);

use App\Filament\Reseller\Resources\Properties\Pages\CreateProperty;
use App\Filament\Reseller\Resources\Properties\Pages\ListProperties;
use App\Filament\Reseller\Resources\Properties\PropertyResource;
use App\Filament\Reseller\Widgets\LatestProperties;
use App\Filament\Reseller\Widgets\ResellerOverview;
use App\Filament\Reseller\Widgets\SubscriptionDetail;
use App\Models\Reseller;
use Filament\Actions\Testing\TestAction;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Event;
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

function resellerOwner(Reseller $reseller): User
{
    return User::factory()
        ->forTenant(createTestTenant())
        ->forReseller($reseller->getKey())
        ->create();
}

function actAsResellerOwner(Reseller $reseller): User
{
    $owner = resellerOwner($reseller);
    actingAs($owner);
    Filament::setCurrentPanel(Filament::getPanel('reseller'));

    return $owner;
}

it('uses the Vendra logo in light and dark modes', function (): void {
    $panel = Filament::getPanel('reseller');

    expect($panel->getBrandName())->toBe('Vendra Reseller')
        ->and($panel->getBrandLogo())->toBe(asset('images/vendra-logo.svg'))
        ->and($panel->getDarkModeBrandLogo())->toBe(asset('images/vendra-logo-dark.svg'))
        ->and($panel->getBrandLogoHeight())->toBe('2rem');
});

it('renders the reseller login without a current tenant', function (): void {
    $this->get('/reseller/login')->assertSuccessful();
});

it('allows a reseller owner to open the reseller dashboard without a current tenant', function (): void {
    $reseller = Reseller::factory()->create();

    actingAs(resellerOwner($reseller));

    $this->get('/reseller')->assertSuccessful();
});

it('renders the reseller dashboard with its widgets for an owner', function (): void {
    $reseller = Reseller::factory()->create();
    Subscription::factory()->forSubscriber($reseller)->for(Plan::factory()->maxUnits(3))->create();
    $property = Tenant::factory()->create(['reseller_id' => $reseller->getKey(), 'status' => true]);
    TenantDomain::factory()->for($property)->create(['name' => 'shop.test', 'status' => true]);

    actAsResellerOwner($reseller);

    livewire(ResellerOverview::class)
        ->assertOk()
        ->assertSee('1 / 3');

    livewire(SubscriptionDetail::class)
        ->assertOk()
        ->assertSee(__('console.status_active'));

    livewire(LatestProperties::class)
        ->call('loadTable')
        ->assertOk()
        ->assertCanSeeTableRecords([$property]);
});

it('hides subscription and property widgets until the reseller has a property', function (): void {
    $reseller = Reseller::factory()->create();

    actAsResellerOwner($reseller);

    expect(SubscriptionDetail::canView())->toBeFalse()
        ->and(LatestProperties::canView())->toBeFalse();
});

it('grants reseller panel access only to reseller owners', function (): void {
    $reseller = Reseller::factory()->create();
    $owner = resellerOwner($reseller);
    $regular = User::factory()->forTenant(createTestTenant())->create();

    $panel = Filament::getPanel('reseller');

    expect($owner->canAccessPanel($panel))->toBeTrue()
        ->and($regular->canAccessPanel($panel))->toBeFalse();
});

it('keeps disabled owners in the panel but blocks property operations', function (): void {
    $reseller = Reseller::factory()->create(['status' => false]);
    Subscription::factory()->forSubscriber($reseller)->for(Plan::factory()->maxUnits(2))->create();
    $property = Tenant::factory()->create(['reseller_id' => $reseller->getKey(), 'status' => true]);

    $owner = actAsResellerOwner($reseller);

    expect($owner->canAccessPanel(Filament::getPanel('reseller')))->toBeTrue()
        ->and(PropertyResource::canCreate())->toBeFalse();

    livewire(ListProperties::class)
        ->assertActionHidden(TestAction::make('replaceDomain')->table($property))
        ->assertActionHidden(TestAction::make('delete')->table($property));
});

it('shows an owner only their own reseller properties', function (): void {
    $resellerA = Reseller::factory()->create();
    $resellerB = Reseller::factory()->create();
    $propertyA = Tenant::factory()->create(['reseller_id' => $resellerA->getKey(), 'status' => true]);
    $propertyB = Tenant::factory()->create(['reseller_id' => $resellerB->getKey(), 'status' => true]);

    actAsResellerOwner($resellerA);

    livewire(ListProperties::class)
        ->call('loadTable')
        ->assertCanSeeTableRecords([$propertyA])
        ->assertCanNotSeeTableRecords([$propertyB]);
});

it('lets an owner create a property within the plan limit', function (): void {
    $reseller = Reseller::factory()->create();
    Subscription::factory()->forSubscriber($reseller)->for(Plan::factory()->maxUnits(2))->create();

    actAsResellerOwner($reseller);

    livewire(CreateProperty::class)
        ->fillForm([
            'name'           => 'Acme',
            'domain'         => 'acme.test',
            'owner_username' => 'admin_acme',
            'owner_email'    => 'admin@acme.test',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    assertDatabaseHas('tenants', [
        'name'        => 'Acme',
        'reseller_id' => $reseller->getKey(),
    ]);
});

it('lets an owner soft-delete their own property', function (): void {
    $reseller = Reseller::factory()->create();
    $property = Tenant::factory()->create(['reseller_id' => $reseller->getKey(), 'status' => true]);

    actAsResellerOwner($reseller);

    livewire(ListProperties::class)
        ->callAction(TestAction::make('delete')->table($property))
        ->assertHasNoErrors();

    expect($property->fresh()?->trashed())->toBeTrue();
});

it('lets an owner replace their property domain, keeping the old one as trashed history', function (): void {
    $reseller = Reseller::factory()->create();
    $property = Tenant::factory()->create(['reseller_id' => $reseller->getKey(), 'status' => true]);
    TenantDomain::factory()->for($property)->create(['name' => 'old.test', 'status' => true]);

    actAsResellerOwner($reseller);

    livewire(ListProperties::class)
        ->callAction(TestAction::make('replaceDomain')->table($property), ['domain' => 'new.test'])
        ->assertHasNoErrors();

    expect($property->execute(fn() => $property->tenantDomains()->where('status', true)->value('name')))->toBe('new.test')
        ->and($property->execute(fn() => $property->tenantDomains()->onlyTrashed()->where('name', 'old.test')->exists()))->toBeTrue();
});

it('validates the replacement domain format and active-domain uniqueness', function (): void {
    $reseller = Reseller::factory()->create();
    $property = Tenant::factory()->create(['reseller_id' => $reseller->getKey(), 'status' => true]);
    TenantDomain::factory()->for($property)->create(['name' => 'current.test', 'status' => true]);

    $other = Tenant::factory()->create();
    TenantDomain::factory()->for($other)->create(['name' => 'taken.test', 'status' => true]);

    actAsResellerOwner($reseller);

    livewire(ListProperties::class)
        ->callAction(TestAction::make('replaceDomain')->table($property), ['domain' => 'not a domain'])
        ->assertHasActionErrors(['domain' => 'regex']);

    livewire(ListProperties::class)
        ->callAction(TestAction::make('replaceDomain')->table($property), ['domain' => 'taken.test'])
        ->assertHasActionErrors(['domain' => 'unique']);
});

it('blocks an owner from exceeding the plan limit', function (): void {
    $reseller = Reseller::factory()->create();
    Subscription::factory()->forSubscriber($reseller)->for(Plan::factory()->maxUnits(1))->create();
    Tenant::factory()->create(['reseller_id' => $reseller->getKey()]);

    actAsResellerOwner($reseller);

    livewire(CreateProperty::class)
        ->fillForm([
            'name'           => 'Second Store',
            'domain'         => 'second.test',
            'owner_username' => 'admin_second',
            'owner_email'    => 'admin@second.test',
        ])
        ->call('create');

    assertDatabaseMissing('tenants', ['name' => 'Second Store']);
});
