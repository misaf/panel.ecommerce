<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Misaf\VendraSupport\Support\TenantSchema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::withoutForeignKeyConstraints(function (): void {
            $this->createCurrencyCategoriesTable();
            $this->createCurrenciesTable();
        });
    }

    private function createCurrencyCategoriesTable(): void
    {
        Schema::create('currency_categories', function (Blueprint $table): void {
            $table->id();
            TenantSchema::addTenantColumn($table);
            $table->string('name');
            $table->string('description')
                ->nullable();
            $table->string('slug');
            $table->unsignedBigInteger('position');
            $table->boolean('status')
                ->default(false);
            $table->timestampsTz();
            $table->softDeletesTz();
            $table->string('active_name_guard')
                ->nullable()
                ->virtualAs('CASE WHEN deleted_at IS NULL THEN name ELSE NULL END');
            $table->string('active_slug_guard')
                ->nullable()
                ->virtualAs('CASE WHEN deleted_at IS NULL THEN slug ELSE NULL END');

            $table->unique(TenantSchema::tenantIndex(['active_name_guard']), 'currency_categories_active_name_unique');
            $table->unique(TenantSchema::tenantIndex(['active_slug_guard']), 'currency_categories_active_slug_unique');
            $table->index(TenantSchema::tenantIndex(['name']));
            $table->index(TenantSchema::tenantIndex(['slug']));
            $table->index(TenantSchema::tenantIndex(['position']));
            $table->index(TenantSchema::tenantIndex(['status']));
        });
    }

    private function createCurrenciesTable(): void
    {
        Schema::create('currencies', function (Blueprint $table): void {
            $table->id();
            TenantSchema::addTenantColumn($table);
            $table->foreignId('currency_category_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('name');
            $table->string('description')
                ->nullable();
            $table->string('slug');
            $table->char('iso_code', 3);
            $table->decimal('conversion_rate', 20, 8);
            $table->unsignedTinyInteger('decimal_place');
            $table->unsignedBigInteger('buy_price');
            $table->unsignedBigInteger('sell_price');
            $table->boolean('is_default')
                ->default(false);
            $table->unsignedBigInteger('position');
            $table->boolean('status')
                ->default(false);
            $table->timestampsTz();
            $table->softDeletesTz();
            $table->string('active_slug_guard')
                ->nullable()
                ->virtualAs('CASE WHEN deleted_at IS NULL THEN slug ELSE NULL END');

            $table->index(TenantSchema::tenantIndex(['currency_category_id']));
            $table->unique(TenantSchema::tenantIndex(['active_slug_guard']), 'currencies_active_slug_unique');
            $table->index(TenantSchema::tenantIndex(['name']));
            $table->index(TenantSchema::tenantIndex(['slug']));
            $table->index(TenantSchema::tenantIndex(['iso_code']));
            $table->index(TenantSchema::tenantIndex(['is_default']));
            $table->index(TenantSchema::tenantIndex(['position']));
            $table->index(TenantSchema::tenantIndex(['status']));
        });
    }

    public function down(): void
    {
        Schema::withoutForeignKeyConstraints(function (): void {
            Schema::dropIfExists('currencies');
            Schema::dropIfExists('currency_categories');
        });
    }
};
