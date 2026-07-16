<?php

declare(strict_types=1);

namespace Misaf\VendraUserProfile\Providers;

use Composer\InstalledVersions;

use Filament\Panel;
use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Gate;
use Laravel\Pennant\Feature;
use Misaf\VendraSupport\Filament\Concerns\ResolvesConfiguredPanels;
use Misaf\VendraUser\Models\User;
use Misaf\VendraUserProfile\Models\UserProfile;
use Misaf\VendraUserProfile\UserProfilePlugin;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class UserProfileServiceProvider extends PackageServiceProvider
{
    use ResolvesConfiguredPanels;

    public function configurePackage(Package $package): void
    {
        $package
            ->name('vendra-user-profile')
            ->hasConfigFile()
            ->hasTranslations()
            ->hasMigrations([
                'create_user_profiles_table',
            ])
            ->hasInstallCommand(function (InstallCommand $command): void {
                $command->askToStarRepoOnGitHub('misaf/vendra-user-profile');
            });
    }

    public function packageRegistered(): void
    {
        Panel::configureUsing(function (Panel $panel): void {
            if ( ! $this->shouldRegisterOnPanel($panel->getId(), 'vendra-user-profile')) {
                return;
            }

            $panel->plugin(UserProfilePlugin::make());
        });
    }

    public function packageBooted(): void
    {
        AboutCommand::add('Vendra User Profile', fn() => ['Version' => InstalledVersions::getPrettyVersion('misaf/vendra-user-profile')]);

        Gate::after(function (User $user): ?true {
            return $user->hasRole(Config::string('vendra-permission.super_admin_role', 'superadmin')) ? true : null;
        });

        $this->registerUserProfileRelationship();
        $this->discoverPackageFeatures();
    }

    private function registerUserProfileRelationship(): void
    {
        User::resolveRelationUsing('profiles', fn(User $user) => $user->hasMany(UserProfile::class));
        User::resolveRelationUsing('userProfiles', fn(User $user) => $user->hasMany(UserProfile::class));
    }

    private function discoverPackageFeatures(): void
    {
        $featureNamespace = 'Misaf\\VendraUserProfile\\Features';
        $featurePath = dirname(__DIR__) . '/Features';

        if (Config::boolean('vendra-user-profile.features_discover', false) && is_dir($featurePath)) {
            Feature::discover($featureNamespace, $featurePath);
        }
    }

}
