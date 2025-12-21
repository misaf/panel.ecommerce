<?php

declare(strict_types=1);

namespace Misaf\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Misaf\Currency\Models\Currency;
use Misaf\Tenant\Models\Tenant;
use Misaf\User\Enums\UserProfileDocumentStatusEnum;
use Misaf\User\Enums\UserProfilePhoneStatusEnum;
use Misaf\User\Models\User;
use Misaf\User\Models\UserProfile;
use Misaf\User\Models\UserProfileBalance;
use Misaf\User\Models\UserProfileDocument;
use Misaf\User\Models\UserProfilePhone;
use Spatie\Permission\Models\Role;

final class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $tenant = Tenant::query()->first();

        if ( ! $tenant) {
            $this->command->error('Tenants not found. Please run TenantSeeder first.');
            return;
        }

        // Get a default currency for balances
        $defaultCurrency = Currency::first();

        if ( ! $defaultCurrency) {
            $this->command->error('No currency found. Please ensure currencies are seeded first.');
            return;
        }

        // Create sample users for Misaf tenant
        $this->createSampleUsers($tenant, $defaultCurrency, 'misaf-shops');
    }

    /**
     * Create sample users for a specific tenant.
     *
     * @param Tenant $tenant
     * @param Currency $currency
     * @param string $tenantSlug
     * @return void
     */
    private function createSampleUsers(Tenant $tenant, Currency $currency, string $tenantSlug): void
    {
        // Create admin user
        $adminUser = User::factory()->forTenant($tenant)->create([
            'username'          => "admin_{$tenantSlug}",
            'email'             => "admin@{$tenantSlug}.test",
            'email_verified_at' => now(),
        ]);

        $adminProfile = UserProfile::create([
            'tenant_id'   => $tenant->id,
            'user_id'     => $adminUser->id,
            'first_name'  => 'Admin',
            'last_name'   => ucfirst($tenantSlug),
            'description' => "Administrator for {$tenantSlug} tenant",
            'birthdate'   => null,
            'status'      => true,
        ]);

        // Create admin phone (temporarily skipped due to phone casting issues)
        // $adminPhone = new UserProfilePhone([
        //     'tenant_id' => $tenant->id,
        //     'user_profile_id' => $adminProfile->id,
        //     'country' => 'US',
        //     'phone' => '+1234567890',
        //     'status' => UserProfilePhoneStatusEnum::Approved,
        //     'verified_at' => now(),
        // ]);
        // $adminPhone->save();

        // Create admin document
        UserProfileDocument::create([
            'tenant_id'       => $tenant->id,
            'user_profile_id' => $adminProfile->id,
            'status'          => UserProfileDocumentStatusEnum::Approved,
            'verified_at'     => now(),
        ]);

        // Create admin balance
        UserProfileBalance::create([
            'tenant_id'       => $tenant->id,
            'user_profile_id' => $adminProfile->id,
            'currency_id'     => $currency->id,
            'amount'          => 1000000, // 10,000.00 in cents
            'status'          => true,
        ]);

        // Assign super-admin role to admin user
        $superAdminRole = Role::where('name', 'super-admin')->where('guard_name', 'web')->first();
        if ($superAdminRole) {
            $adminUser->assignRole($superAdminRole);
            $this->command->info("Assigned super-admin role to admin user (ID: {$adminUser->id})");
        } else {
            $this->command->warn("Super-admin role not found. Please run PermissionSeeder first.");
        }

        // Create regular user
        $regularUser = User::factory()->forTenant($tenant)->create([
            'username'          => "user_{$tenantSlug}",
            'email'             => "user@{$tenantSlug}.test",
            'email_verified_at' => now(),
        ]);

        $regularProfile = UserProfile::create([
            'tenant_id'   => $tenant->id,
            'user_id'     => $regularUser->id,
            'first_name'  => 'Regular',
            'last_name'   => 'User',
            'description' => "Regular user for {$tenantSlug} tenant",
            'birthdate'   => null,
            'status'      => true,
        ]);

        // Create regular user phone (temporarily skipped due to phone casting issues)
        // $regularPhone = new UserProfilePhone([
        //     'tenant_id' => $tenant->id,
        //     'user_profile_id' => $regularProfile->id,
        //     'country' => 'US',
        //     'phone' => '+1234567891',
        //     'status' => UserProfilePhoneStatusEnum::Approved,
        //     'verified_at' => now(),
        // ]);
        // $regularPhone->save();

        // Create regular user document
        UserProfileDocument::create([
            'tenant_id'       => $tenant->id,
            'user_profile_id' => $regularProfile->id,
            'status'          => UserProfileDocumentStatusEnum::Pending,
        ]);

        // Create regular user balance
        UserProfileBalance::create([
            'tenant_id'       => $tenant->id,
            'user_profile_id' => $regularProfile->id,
            'currency_id'     => $currency->id,
            'amount'          => 500000, // 5,000.00 in cents
            'status'          => true,
        ]);

        // Create unverified user
        $unverifiedUser = User::factory()->forTenant($tenant)->unverified()->create([
            'username' => "unverified_{$tenantSlug}",
            'email'    => "unverified@{$tenantSlug}.test",
        ]);

        $unverifiedProfile = UserProfile::create([
            'tenant_id'   => $tenant->id,
            'user_id'     => $unverifiedUser->id,
            'first_name'  => 'Unverified',
            'last_name'   => 'User',
            'description' => "Unverified user for {$tenantSlug} tenant",
            'birthdate'   => null,
            'status'      => false,
        ]);

        // Create unverified user phone (temporarily skipped due to phone casting issues)
        // $unverifiedPhone = new UserProfilePhone([
        //     'tenant_id' => $tenant->id,
        //     'user_profile_id' => $unverifiedProfile->id,
        //     'country' => 'US',
        //     'phone' => '+1234567892',
        //     'status' => UserProfilePhoneStatusEnum::Pending,
        // ]);
        // $unverifiedPhone->save();

        // Create unverified user document
        UserProfileDocument::create([
            'tenant_id'       => $tenant->id,
            'user_profile_id' => $unverifiedProfile->id,
            'status'          => UserProfileDocumentStatusEnum::Rejected,
        ]);

        // Create unverified user balance
        UserProfileBalance::create([
            'tenant_id'       => $tenant->id,
            'user_profile_id' => $unverifiedProfile->id,
            'currency_id'     => $currency->id,
            'amount'          => 0,
            'status'          => false,
        ]);

        $this->command->info("Created sample users for {$tenantSlug} tenant:");
        $this->command->info("- Admin user: admin@{$tenantSlug}.test");
        $this->command->info("- Regular user: user@{$tenantSlug}.test");
        $this->command->info("- Unverified user: unverified@{$tenantSlug}.test");
    }
}
