<?php

declare(strict_types=1);

use App\Filament\Console\Resources\Plans\Pages\CreatePlan;
use App\Filament\Console\Resources\Plans\Pages\EditPlan;
use App\Filament\Console\Resources\Plans\Pages\ListPlans;
use App\Filament\Console\Resources\Properties\Pages\CreateProperty;
use App\Filament\Console\Resources\Properties\Pages\EditProperty;
use App\Filament\Console\Resources\Properties\Pages\ListProperties;
use App\Filament\Console\Resources\Properties\RelationManagers\DomainsRelationManager;
use App\Filament\Console\Resources\Resellers\Pages\CreateReseller;
use App\Models\ConsoleUser;
use App\Models\Reseller;
use App\Models\ResellerUser;
use Database\Seeders\ConsoleUserSeeder;
use Filament\Actions\DeleteAction;
use Filament\Actions\Testing\TestAction;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
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

function consoleAdmin(): ConsoleUser
{
    return ConsoleUser::factory()->create();
}

function actAsConsoleAdmin(): ConsoleUser
{
    $admin = consoleAdmin();
    actingAs($admin, 'console');
    Filament::setCurrentPanel(Filament::getPanel('console'));

    return $admin;
}

it('uses the Vendra logo in light and dark modes', function (): void {
    $panel = Filament::getPanel('console');

    expect($panel->getBrandName())->toBe('Vendra Console')
        ->and($panel->getBrandLogo())->toBe(asset('images/vendra-logo.svg'))
        ->and($panel->getDarkModeBrandLogo())->toBe(asset('images/vendra-logo-dark.svg'))
        ->and($panel->getBrandLogoHeight())->toBe('2rem');
});

it('isolates console operators from application users', function (): void {
    $tenant = createTestTenant();
    $admin = ConsoleUser::factory()->create();
    $regular = User::factory()->forTenant($tenant)->create();

    $panel = Filament::getPanel('console');

    expect($admin->canAccessPanel($panel))->toBeTrue()
        ->and($regular->canAccessPanel($panel))->toBeFalse()
        ->and($panel->getAuthGuard())->toBe('console')
        ->and($panel->getAuthPasswordBroker())->toBe('console_users')
        ->and(config('auth.guards.console.provider'))->toBe('console_users')
        ->and(config('auth.providers.console_users.model'))->toBe(ConsoleUser::class)
        ->and(Filament::getPanel('reseller')->getAuthGuard())->toBe('reseller')
        ->and(Filament::getPanel('admin')->getAuthGuard())->toBe('web');

    actingAs($admin, 'console');

    expect(auth('console')->id())->toBe($admin->getKey())
        ->and(auth('web')->check())->toBeFalse();
});

it('redirects an application user away from the console panel', function (): void {
    actingAs(User::factory()->forTenant(createTestTenant())->create());

    $this->get('https://console.vendra.test')
        ->assertRedirect('https://console.vendra.test/login');
});

it('allows a verified console operator into the console panel', function (): void {
    actingAs(ConsoleUser::factory()->create(), 'console');

    $this->get('https://console.vendra.test')->assertOk();
});

it('seeds the initial console operator only from explicit credentials', function (): void {
    Config::set('console.operator', [
        'username' => 'console_owner',
        'email'    => 'OWNER@EXAMPLE.TEST',
        'password' => 'a-secure-console-password',
    ]);

    app(ConsoleUserSeeder::class)->run();

    $operator = ConsoleUser::query()->sole();

    expect($operator->username)->toBe('console_owner')
        ->and($operator->email)->toBe('owner@example.test')
        ->and($operator->hasVerifiedEmail())->toBeTrue()
        ->and(Hash::check('a-secure-console-password', $operator->password))->toBeTrue();
});

it('lets a console admin create a plan', function (): void {
    actAsConsoleAdmin();

    livewire(CreatePlan::class)
        ->fillForm([
            'name'          => 'Pro',
            'max_units'     => 3,
            'period_unit'   => 'month',
            'period_count'  => 1,
            'status'        => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    assertDatabaseHas('plans', [
        'name'          => 'Pro',
        'max_units'     => 3,
        'period_unit'   => 'month',
    ]);
});

it('requires a currency for a paid plan', function (): void {
    actAsConsoleAdmin();

    livewire(CreatePlan::class)
        ->fillForm([
            'name'           => 'Paid',
            'max_units'      => 3,
            'period_unit'    => 'month',
            'period_count'   => 1,
            'price'          => 1500,
            'currency_code'  => null,
            'status'         => true,
        ])
        ->call('create')
        ->assertHasFormErrors(['currency_code' => 'required']);
});

it('honors a disabled status when creating a reseller', function (): void {
    actAsConsoleAdmin();

    livewire(CreateReseller::class)
        ->fillForm([
            'plan_id'               => Plan::factory()->create()->getKey(),
            'username'              => 'paused_owner',
            'email'                 => 'owner@gmail.com',
            'password'              => 'Secure123',
            'password_confirmation' => 'Secure123',
            'status'                => false,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $reseller = Reseller::query()->where('name', 'paused_owner')->sole();

    expect($reseller->status)->toBeFalse()
        ->and($reseller->ownerUser()->sole())->toBeInstanceOf(ResellerUser::class)
        ->and(Hash::check('Secure123', $reseller->ownerUser()->sole()->password))->toBeTrue();
});

it('prevents deleting a plan used by subscriptions from list and edit pages', function (): void {
    actAsConsoleAdmin();

    $plan = Plan::factory()->create();
    Subscription::factory()->for($plan)->create();

    livewire(ListPlans::class)
        ->assertActionHidden(TestAction::make('delete')->table($plan));

    livewire(EditPlan::class, ['record' => $plan->getKey()])
        ->assertActionHidden(DeleteAction::class);
});

it('allows deleting an unused plan from list and edit pages', function (): void {
    actAsConsoleAdmin();

    $plan = Plan::factory()->create();

    livewire(ListPlans::class)
        ->assertActionVisible(TestAction::make('delete')->table($plan));

    livewire(EditPlan::class, ['record' => $plan->getKey()])
        ->assertActionVisible(DeleteAction::class);
});

it('creates a property for a reseller within its plan limit', function (): void {
    actAsConsoleAdmin();

    $reseller = Reseller::factory()->create();
    Subscription::factory()->forSubscriber($reseller)->for(Plan::factory()->maxUnits(2))->create();

    livewire(CreateProperty::class)
        ->fillForm([
            'reseller_id'    => $reseller->getKey(),
            'domain'         => 'acme.test',
            'email'          => 'admin@gmail.com',
            'status'         => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    assertDatabaseHas('tenants', [
        'name'        => 'Acme',
        'reseller_id' => $reseller->getKey(),
    ]);
    assertDatabaseHas('users', [
        'username' => 'admin',
        'email'    => 'admin@gmail.com',
    ]);
});

it('blocks property creation once the reseller reaches its plan limit', function (): void {
    actAsConsoleAdmin();

    $reseller = Reseller::factory()->create();
    Subscription::factory()->forSubscriber($reseller)->for(Plan::factory()->maxUnits(1))->create();
    Tenant::factory()->create(['reseller_id' => $reseller->getKey()]);

    livewire(CreateProperty::class)
        ->fillForm([
            'reseller_id'    => $reseller->getKey(),
            'domain'         => 'second.test',
            'email'          => 'admin@second.test',
            'status'         => true,
        ])
        ->call('create');

    assertDatabaseMissing('tenant_domains', ['name' => 'second.test']);
    expect($reseller->tenants()->count())->toBe(1);
});

it('validates property domains during creation', function (): void {
    actAsConsoleAdmin();

    $reseller = Reseller::factory()->create();
    Subscription::factory()->forSubscriber($reseller)->for(Plan::factory()->maxUnits(3))->create();
    $existingProperty = Tenant::factory()->create();
    TenantDomain::factory()->for($existingProperty)->create(['name' => 'taken.test', 'status' => true]);

    livewire(CreateProperty::class)
        ->fillForm([
            'reseller_id'    => $reseller->getKey(),
            'domain'         => 'not a domain',
            'email'          => 'invalid@example.test',
            'status'         => true,
        ])
        ->call('create')
        ->assertHasFormErrors(['domain' => 'regex']);

    livewire(CreateProperty::class)
        ->fillForm([
            'reseller_id'    => $reseller->getKey(),
            'domain'         => 'taken.test',
            'email'          => 'duplicate@example.test',
            'status'         => true,
        ])
        ->call('create')
        ->assertHasFormErrors(['domain' => 'unique']);
});

it('lets a console admin replace a domain and shows the old one in trashed history', function (): void {
    actAsConsoleAdmin();

    $property = Tenant::factory()->create(['status' => true]);
    $original = TenantDomain::factory()->for($property)->create(['name' => 'old.test', 'status' => true]);

    livewire(ListProperties::class)
        ->callAction(TestAction::make('replaceDomain')->table($property), ['domain' => 'new.test'])
        ->assertHasNoErrors();

    $current = $property->execute(fn() => $property->tenantDomains()->where('status', true)->value('name'));
    expect($current)->toBe('new.test');

    livewire(DomainsRelationManager::class, [
        'ownerRecord' => $property,
        'pageClass'   => EditProperty::class,
    ])
        ->call('loadTable')
        ->filterTable('trashed', ['value' => '0'])
        ->assertCanSeeTableRecords([$original]);
});

it('lets a console admin soft-delete then restore a property', function (): void {
    actAsConsoleAdmin();

    $property = Tenant::factory()->create(['status' => true]);

    livewire(ListProperties::class)
        ->callAction(TestAction::make('delete')->table($property))
        ->assertHasNoErrors();

    expect($property->fresh()?->trashed())->toBeTrue();

    livewire(ListProperties::class)
        ->filterTable('trashed', ['value' => 'trashed'])
        ->callAction(TestAction::make('restore')->table($property))
        ->assertHasNoErrors();

    expect($property->fresh()?->trashed())->toBeFalse();
});

it('lets a console admin permanently delete a trashed property', function (): void {
    actAsConsoleAdmin();

    $property = Tenant::factory()->trashed()->create();

    livewire(ListProperties::class)
        ->filterTable('trashed', ['value' => 'trashed'])
        ->callAction(TestAction::make('forceDelete')->table($property))
        ->assertHasNoErrors();

    assertDatabaseMissing('tenants', ['id' => $property->getKey()]);
});
