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
            $this->createUserProfilesTable();
        });
    }

    private function createUserProfilesTable(): void
    {
        Schema::create('user_profiles', function (Blueprint $table): void {
            $table->id();
            TenantSchema::addTenantColumn($table);
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('name');
            $table->string('description')
                ->nullable();
            $table->string('slug');
            $table->boolean('is_default')
                ->default(false);
            $table->boolean('status')
                ->default(false);
            $table->timestampsTz();
            $table->softDeletesTz();

            $table->index(TenantSchema::tenantIndex(['user_id']));
            $table->index(TenantSchema::tenantIndex(['name']));
            $table->index(TenantSchema::tenantIndex(['slug']));
            $table->index(TenantSchema::tenantIndex(['is_default']));
            $table->index(TenantSchema::tenantIndex(['status']));
        });
    }

    public function down(): void
    {
        Schema::withoutForeignKeyConstraints(function (): void {
            Schema::dropIfExists('user_profiles');
        });
    }
};
