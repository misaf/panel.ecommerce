<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Misaf\VendraSupport\Support\TenantSchema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        $this->createLanguagesTable();
        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('languages');
        Schema::enableForeignKeyConstraints();
    }

    private function createLanguagesTable(): void
    {
        Schema::create('languages', function (Blueprint $table): void {
            $table->id();
            TenantSchema::addTenantColumn($table);
            $table->string('locale', 8);
            $table->boolean('is_default')
                ->default(false);
            $table->unsignedBigInteger('position');
            $table->timestampsTz();

            $table->unique(TenantSchema::tenantIndex(['locale']));
            $table->index(TenantSchema::tenantIndex(['is_default']));
            $table->index(TenantSchema::tenantIndex(['position']));
        });
    }
};
