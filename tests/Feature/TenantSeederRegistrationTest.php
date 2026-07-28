<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Misaf\VendraSupport\Tenancy\TenantSeeders;
use Misaf\VendraUserProfile\Tests\Support\UserProfileModuleTestContext;

it('registers the user profile and provider seed commands for tenant provisioning', function (): void {
    $ordered = app(TenantSeeders::class)->ordered();

    expect($ordered)->toContain(
        'vendra-user-profile:seed',
        'vendra-address:seed',
        'vendra-phone:seed',
        'vendra-document:seed',
        'vendra-verification:seed',
    )->and(array_search('vendra-user:seed', $ordered, true))
        ->toBeLessThan(array_search('vendra-user-profile:seed', $ordered, true));
});

it('seeds module permissions through the registered seed commands', function (string $command, string $permission): void {
    UserProfileModuleTestContext::createCurrentTenant();

    $exitCode = Artisan::call($command, [
        'tenant'  => 1,
        'seeders' => ['all'],
    ]);

    /** @var class-string<Model> $permissionModel */
    $permissionModel = Config::string('permission.models.permission');

    expect($exitCode)->toBe(0)
        ->and($permissionModel::query()->where('name', $permission)->exists())->toBeTrue();
})->with([
    ['vendra-user-profile:seed', 'view-any-user-profile'],
    ['vendra-address:seed', 'view-any-address'],
    ['vendra-phone:seed', 'view-any-phone-number'],
    ['vendra-document:seed', 'view-any-document'],
    ['vendra-verification:seed', 'view-any-verification'],
]);
