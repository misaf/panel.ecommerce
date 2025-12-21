<?php

declare(strict_types=1);

namespace Misaf\Currency\Database\Seeders;

use Illuminate\Database\Seeder;
use Misaf\Currency\Models\Currency;
use Misaf\Currency\Models\CurrencyCategory;
use Misaf\Tenant\Models\Tenant;

final class CurrencySeeder extends Seeder
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

        // Create sample currencies for the tenant
        $this->createSampleCurrencies($tenant, $tenant->slug);
    }

    /**
     * Create sample currencies for a specific tenant.
     *
     * @param Tenant $tenant
     * @param string $tenantSlug
     * @return void
     */
    private function createSampleCurrencies(Tenant $tenant, string $tenantSlug): void
    {
        // Create currency categories
        $fiatCategory = CurrencyCategory::create([
            'tenant_id'   => $tenant->id,
            'name'        => 'Fiat Currencies',
            'description' => 'Traditional government-issued currencies',
            'slug'        => 'fiat-currencies',
            'status'      => true,
        ]);

        $cryptoCategory = CurrencyCategory::create([
            'tenant_id'   => $tenant->id,
            'name'        => 'Cryptocurrencies',
            'description' => 'Digital or virtual currencies',
            'slug'        => 'cryptocurrencies',
            'status'      => true,
        ]);

        // Create US Dollar (Fiat)
        Currency::create([
            'tenant_id'            => $tenant->id,
            'currency_category_id' => $fiatCategory->id,
            'name'                 => 'US Dollar',
            'description'          => 'United States Dollar',
            'slug'                 => 'us-dollar',
            'iso_code'             => 'USD',
            'conversion_rate'      => 1.0,
            'decimal_place'        => 2,
            'buy_price'            => 100000, // 1,000.00 in cents
            'sell_price'           => 100000, // 1,000.00 in cents
            'is_default'           => true,
            'position'             => 1,
            'status'               => true,
        ]);

        // Create Euro (Fiat)
        Currency::create([
            'tenant_id'            => $tenant->id,
            'currency_category_id' => $fiatCategory->id,
            'name'                 => 'Euro',
            'description'          => 'European Union Euro',
            'slug'                 => 'euro',
            'iso_code'             => 'EUR',
            'conversion_rate'      => 0.85,
            'decimal_place'        => 2,
            'buy_price'            => 85000, // 850.00 in cents
            'sell_price'           => 85000, // 850.00 in cents
            'is_default'           => false,
            'position'             => 2,
            'status'               => true,
        ]);

        // Create Bitcoin (Crypto)
        Currency::create([
            'tenant_id'            => $tenant->id,
            'currency_category_id' => $cryptoCategory->id,
            'name'                 => 'Bitcoin',
            'description'          => 'Bitcoin cryptocurrency',
            'slug'                 => 'bitcoin',
            'iso_code'             => 'BTC',
            'conversion_rate'      => 0.000025,
            'decimal_place'        => 8,
            'buy_price'            => 40000000, // 400,000.00 in cents
            'sell_price'           => 40000000, // 400,000.00 in cents
            'is_default'           => false,
            'position'             => 3,
            'status'               => true,
        ]);

        // Create Ethereum (Crypto)
        Currency::create([
            'tenant_id'            => $tenant->id,
            'currency_category_id' => $cryptoCategory->id,
            'name'                 => 'Ethereum',
            'description'          => 'Ethereum cryptocurrency',
            'slug'                 => 'ethereum',
            'iso_code'             => 'ETH',
            'conversion_rate'      => 0.0004,
            'decimal_place'        => 8,
            'buy_price'            => 2500000, // 25,000.00 in cents
            'sell_price'           => 2500000, // 25,000.00 in cents
            'is_default'           => false,
            'position'             => 4,
            'status'               => true,
        ]);

        $this->command->info("Created sample currencies for {$tenantSlug} tenant:");
        $this->command->info("- 2 currency categories (Fiat, Crypto)");
        $this->command->info("- 4 currencies (USD, EUR, BTC, ETH)");
    }
}
