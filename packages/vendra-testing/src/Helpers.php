<?php

declare(strict_types=1);

use Filament\Facades\Filament;
use Filament\Panel;
use Filament\PanelRegistry;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Livewire\Livewire;
use Misaf\VendraSupport\Contracts\TenantResolver;
use Misaf\VendraSupport\Tenancy\TenantAwareness;
use PHPUnit\Framework\Assert;

/**
 * Resolve the tenant model class bound by the installed tenant provider.
 *
 * @return class-string<Model>
 */
function testTenantModel(): string
{
    return app(TenantResolver::class)->modelClass();
}

/**
 * Create a persisted, active tenant through the bound tenant provider.
 *
 * Returns null when tenancy is disabled so suites stay provider-agnostic:
 * the same test runs with or without a tenant provider installed.
 *
 * @param  array<string, mixed>  $attributes
 */
function createTestTenant(array $attributes = []): ?Model
{
    if ( ! TenantAwareness::enabled()) {
        return null;
    }

    $factory = vendraTestingModelFactory(testTenantModel());

    if (method_exists($factory, 'active')) {
        $factory = $factory->active();
    }

    $tenant = $factory->create($attributes);

    if ( ! $tenant instanceof Model) {
        Assert::fail('The tenant factory did not create a single tenant model.');
    }

    return $tenant;
}

/**
 * Create an active tenant and make it the current tenant context.
 *
 * No-op returning null when tenancy is disabled.
 *
 * @param  array<string, mixed>  $attributes
 */
function makeCurrentTestTenant(array $attributes = []): ?Model
{
    $tenant = createTestTenant($attributes);

    if ($tenant instanceof Model) {
        app(TenantResolver::class)->makeCurrent($tenant);
    }

    return $tenant;
}

/**
 * Switch the current tenant context to an already created tenant.
 *
 * Accepts null so call sites composed with createTestTenant() stay a no-op
 * when tenancy is disabled.
 */
function switchToTestTenant(Model|int|string|null $tenant): void
{
    if (null === $tenant) {
        return;
    }

    app(TenantResolver::class)->makeCurrent($tenant);
}

/**
 * Resolve the current tenant through the bound tenant provider.
 */
function currentTestTenant(): ?Model
{
    return app(TenantResolver::class)->current();
}

/**
 * Clear the current tenant context. No-op when tenancy is disabled.
 */
function forgetCurrentTestTenant(): void
{
    if ( ! TenantAwareness::enabled()) {
        return;
    }

    $modelClass = testTenantModel();

    if (method_exists($modelClass, 'forgetCurrent')) {
        $modelClass::forgetCurrent();
    }
}

/**
 * Resolve the authenticatable user model from the host application's auth
 * configuration instead of importing a concrete user package.
 *
 * @return class-string<Model>
 */
function testUserModel(): string
{
    $modelClass = config('auth.providers.users.model');

    if ( ! is_string($modelClass) || ! is_a($modelClass, Model::class, true)) {
        Assert::fail('The auth user provider does not define an Eloquent user model.');
    }

    return $modelClass;
}

/**
 * Create a persisted user through the configured auth user model.
 *
 * @param  array<string, mixed>  $attributes
 */
function createTestUser(array $attributes = []): Model
{
    $user = vendraTestingModelFactory(testUserModel())->create($attributes);

    if ( ! $user instanceof Model) {
        Assert::fail('The user factory did not create a single user model.');
    }

    return $user;
}

/**
 * Create the current tenant with a deterministic Pennant feature state.
 *
 * Every feature key configured by an installed permission provider is
 * deactivated unless explicitly requested, so tenant state never depends on
 * published feature defaults. Fails when no tenant provider is installed.
 *
 * @param  list<string>  $features
 */
function makeCurrentTestTenantWithFeatures(array $features = []): Model
{
    $tenant = makeCurrentTestTenant();

    if ( ! $tenant instanceof Model) {
        Assert::fail('This helper requires an installed tenant provider.');
    }

    if (class_exists(Laravel\Pennant\Feature::class)) {
        $configuredFeatures = array_keys((array) config('vendra-permission.features.defaults', []));

        Laravel\Pennant\Feature::for($tenant)->deactivate(array_values(array_diff($configuredFeatures, $features)));
        Laravel\Pennant\Feature::for($tenant)->activate($features);
    }

    return $tenant;
}

/**
 * Boot a default Filament admin panel acting as a super-admin user.
 *
 * The super-admin role is resolved through the Spatie permission and
 * vendra-permission configuration instead of importing a concrete permission
 * package, so any module test suite can use this helper without declaring a
 * permission dependency. Returns the current tenant.
 *
 * @param  list<class-string>  $resources
 * @param  list<string>  $features
 */
function setUpFilamentSuperAdminTestContext(array $resources = [], ?array $features = null): Model
{
    $tenant = makeCurrentTestTenantWithFeatures(
        $features ?? array_keys((array) config('vendra-permission.features.defaults', [])),
    );

    $user = vendraTestingModelFactory(testUserModel());

    if (method_exists($user, 'forTenant')) {
        $user = $user->forTenant($tenant);
    }

    $user = $user->create([
        'username' => 'super-admin',
        'email'    => 'super-admin@example.test',
    ]);

    if ( ! $user instanceof Model) {
        Assert::fail('The user factory did not create a single user model.');
    }

    $roleModel = config('permission.models.role');

    if (is_string($roleModel) && is_a($roleModel, Model::class, true) && method_exists($user, 'assignRole')) {
        $role = vendraTestingModelFactory($roleModel);

        if (method_exists($role, 'forTenant')) {
            $role = $role->forTenant($tenant);
        }

        if (method_exists($role, 'forGuard')) {
            $role = $role->forGuard('web');
        }

        $user->assignRole($role->create([
            'name' => config('vendra-permission.super_admin_role', 'super-admin'),
        ]));
    }

    app(PanelRegistry::class)->register(
        Panel::make()
            ->default()
            ->id('admin')
            ->path('admin')
            ->resources($resources)
            ->tenant(testTenantModel()),
    );

    Table::configureUsing(function (Table $table) {
        return $table
            ->paginationPageOptions([10, 25, 50])
            ->deferLoading();
    });

    Filament::setCurrentPanel('admin');
    Livewire::actingAs($user);
    Filament::setTenant($tenant);
    Filament::bootCurrentPanel();

    app('url')->resolveMissingNamedRoutesUsing(static fn(): string => '/');

    return $tenant;
}

/**
 * @param  class-string<Model>  $modelClass
 * @return Factory<Model>
 */
function vendraTestingModelFactory(string $modelClass): Factory
{
    if ( ! method_exists($modelClass, 'factory')) {
        Assert::fail("The model [{$modelClass}] does not expose a factory.");
    }

    $factory = $modelClass::factory();

    if ( ! $factory instanceof Factory) {
        Assert::fail("The model [{$modelClass}] did not resolve an Eloquent factory.");
    }

    return $factory;
}
