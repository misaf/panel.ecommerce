<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Misaf\VendraSupport\Support\TenantSchema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('attributes', function (Blueprint $table): void {
            $table->id();
            TenantSchema::addTenantColumn($table);
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('unit', 64)->nullable();
            $table->unsignedBigInteger('position');
            $table->boolean('status')->default(true);
            $table->timestampsTz();
            $table->softDeletesTz();
            $table->string('active_name_guard')
                ->nullable()
                ->virtualAs('CASE WHEN deleted_at IS NULL THEN name ELSE NULL END');

            $table->unique(TenantSchema::tenantIndex(['active_name_guard']), 'attributes_active_name_unique');
            $table->index(TenantSchema::tenantIndex(['name']));
            $table->index(TenantSchema::tenantIndex(['unit']));
            $table->index(TenantSchema::tenantIndex(['position']));
            $table->index(TenantSchema::tenantIndex(['status']));
        });

        Schema::create('attribute_values', function (Blueprint $table): void {
            $table->id();
            TenantSchema::addTenantColumn($table);
            $table->foreignId('attribute_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->morphs('attributable');
            $table->text('value');
            $table->unsignedBigInteger('position');
            $table->timestampsTz();
            $table->softDeletesTz();

            $table->index(TenantSchema::tenantIndex(['attribute_id']));
            $table->index(TenantSchema::tenantIndex(['position']));
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attribute_values');
        Schema::dropIfExists('attributes');
    }
};
