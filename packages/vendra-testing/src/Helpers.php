<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Misaf\VendraSupport\Contracts\TenantResolver;
use Misaf\VendraSupport\Support\TenantAwareness;
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
 * Create a persisted, enabled tenant through the bound tenant provider.
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

    if (method_exists($factory, 'enabled')) {
        $factory = $factory->enabled();
    }

    $tenant = $factory->create($attributes);

    if ( ! $tenant instanceof Model) {
        Assert::fail('The tenant factory did not create a single tenant model.');
    }

    return $tenant;
}

/**
 * Create an enabled tenant and make it the current tenant context.
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
