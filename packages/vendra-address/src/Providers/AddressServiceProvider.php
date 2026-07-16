<?php

declare(strict_types=1);

namespace Misaf\VendraAddress\Providers;

use Misaf\VendraAddress\Console\Commands\SeedCommand;
use Misaf\VendraAddress\Filament\RelationManagers\AddressesRelationManager;
use Misaf\VendraAddress\Models\Address;
use Misaf\VendraUserProfile\Models\UserProfile;
use Misaf\VendraUserProfile\Support\UserProfileRelationManagers;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class AddressServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('vendra-address')
            ->hasTranslations()
            ->hasCommands(SeedCommand::class)
            ->hasMigration('create_addresses_table');
    }

    public function packageBooted(): void
    {
        UserProfile::resolveRelationUsing(
            'addresses',
            fn(UserProfile $profile) => $profile->hasMany(Address::class),
        );

        $this->app->make(UserProfileRelationManagers::class)
            ->register(AddressesRelationManager::class, priority: 10);
    }
}
