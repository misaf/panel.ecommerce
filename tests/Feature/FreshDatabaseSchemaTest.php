<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Misaf\VendraSupport\Support\TenantTableRegistry;

it('contains every package table in the fresh database baseline', function (): void {
    expect(Schema::hasTable('authify_logs'))->toBeTrue()
        ->and(Schema::hasColumns('language_lines', ['tenant_id', 'namespace', 'namespace_guard']))->toBeTrue()
        ->and(Schema::hasColumns('activity_log', ['tenant_id', 'event', 'batch_uuid']))->toBeTrue()
        ->and(Schema::hasColumns('roles', ['tenant_id', 'description']))->toBeTrue()
        ->and(Schema::hasColumns('permissions', ['tenant_id', 'description']))->toBeTrue()
        ->and(Schema::hasColumn('tags', 'position'))->toBeTrue()
        ->and(Schema::hasColumn('tags', 'order_column'))->toBeFalse();
});

it('enforces required relational integrity', function (string $table, string $column): void {
    expect(Schema::hasForeignKey($table, [$column]))->toBeTrue();
})->with([
    ['tenant_domains', 'tenant_id'],
    ['tenant_user', 'tenant_id'],
    ['tenant_user', 'user_id'],
    ['blog_posts', 'blog_post_category_id'],
    ['custom_pages', 'custom_page_category_id'],
    ['products', 'product_category_id'],
    ['product_prices', 'product_id'],
    ['faqs', 'faq_category_id'],
    ['transactions', 'transaction_gateway_id'],
    ['transactions', 'wallet_id'],
    ['transactions', 'counterparty_wallet_id'],
    ['wallets', 'user_id'],
    ['wallets', 'currency_id'],
    ['ledger_entries', 'wallet_id'],
    ['transaction_fees', 'transaction_id'],
    ['transaction_metadata', 'transaction_id'],
    ['transaction_limits', 'wallet_id'],
    ['attribute_values', 'attribute_id'],
    ['affiliates', 'user_id'],
    ['affiliate_clicks', 'affiliate_id'],
    ['affiliate_referrals', 'affiliate_id'],
    ['affiliate_referrals', 'user_id'],
    ['affiliate_referrals', 'affiliate_click_id'],
    ['affiliate_commissions', 'affiliate_id'],
    ['affiliate_commissions', 'affiliate_referral_id'],
    ['affiliate_commissions', 'affiliate_payout_id'],
    ['affiliate_payouts', 'affiliate_id'],
    ['affiliate_payouts', 'transaction_id'],
    ['socialite_users', 'user_id'],
    ['user_profiles', 'user_id'],
    ['addresses', 'user_profile_id'],
    ['documents', 'user_profile_id'],
    ['phone_numbers', 'user_profile_id'],
    ['verifications', 'user_profile_id'],
    ['authify_logs', 'user_id'],
    ['taggables', 'tag_id'],
    ['model_has_permissions', 'permission_id'],
    ['model_has_roles', 'role_id'],
    ['role_has_permissions', 'permission_id'],
    ['role_has_permissions', 'role_id'],
]);

it('prevents duplicate tenant memberships', function (): void {
    expect(Schema::hasIndex('tenant_user', ['tenant_id', 'user_id'], 'unique'))->toBeTrue();
});

it('uses final create migrations instead of fresh-install follow-ups', function (): void {
    $rootMigrations = glob(database_path('migrations/*.php')) ?: [];
    $packageMigrations = glob(base_path('packages/*/database/migrations/*.stub')) ?: [];
    $followUpMigrations = array_filter(
        [...$rootMigrations, ...$packageMigrations],
        static fn(string $path): bool => 1 === preg_match('/(?:^|_)(?:add|rename|backfill|enforce)_/', basename($path)),
    );

    expect(array_values($followUpMigrations))->toBe([]);
});

it('keeps package migration stubs identical to application baselines', function (): void {
    $rootMigrations = collect(glob(database_path('migrations/*.php')) ?: [])
        ->keyBy(fn(string $migration): string => preg_replace('/^\d{4}_\d{2}_\d{2}_\d{6}_/', '', basename($migration)) ?? basename($migration));

    $packageMigrations = glob(base_path('packages/*/database/migrations/*.stub')) ?: [];

    expect($packageMigrations)->not->toBeEmpty();

    foreach ($packageMigrations as $packageMigration) {
        $migrationName = str_replace('.stub', '', basename($packageMigration));
        $rootMigration = $rootMigrations->get($migrationName);

        expect($rootMigration)
            ->not->toBeNull("Missing application baseline for [{$packageMigration}].")
            ->and(file_get_contents($packageMigration))
            ->toBe(file_get_contents($rootMigration), "Package migration [{$packageMigration}] has drifted from its application baseline.");
    }
});

it('registers every tenant-aware application table for legacy schema retrofits', function (): void {
    $registeredTables = collect(app(TenantTableRegistry::class)->all())
        ->where('connection', null)
        ->pluck('table')
        ->values();

    $tenantAwareTables = collect(Schema::getTableListing(schemaQualified: false))
        ->filter(fn(string $table): bool => Schema::hasColumn($table, 'tenant_id'))
        ->reject(fn(string $table): bool => in_array($table, ['tenant_domains', 'tenant_user'], true))
        ->values();

    expect($registeredTables->all())->toEqualCanonicalizing($tenantAwareTables->all());
});

it('keeps corrected SQL types in package and application baselines', function (string $migration, array $declarations): void {
    $contents = file_get_contents(base_path($migration));

    expect($contents)->toBeString();

    foreach ($declarations as $declaration) {
        expect($contents)->toContain($declaration);
    }
})->with([
    'package currency' => [
        'packages/vendra-currency/database/migrations/create_currencies_table.php.stub',
        [
            "string('code', 16)",
            "unsignedTinyInteger('decimal_places')",
        ],
    ],
    'application currency' => [
        'database/migrations/0001_01_01_000017_create_currencies_table.php',
        [
            "string('code', 16)",
            "unsignedTinyInteger('decimal_places')",
        ],
    ],
    'package transaction gateway' => [
        'packages/vendra-transaction/database/migrations/create_transactions_table.php.stub',
        ["json('name')", "json('description')", "string('slug')"],
    ],
    'application transaction gateway' => [
        'database/migrations/0001_01_01_000018_create_transactions_table.php',
        ["json('name')", "json('description')", "string('slug')"],
    ],
    'package product price' => [
        'packages/vendra-product/database/migrations/create_products_table.php.stub',
        ["char('currency_code', 3)"],
    ],
    'application product price' => [
        'database/migrations/0001_01_01_000012_create_products_table.php',
        ["char('currency_code', 3)"],
    ],
]);
