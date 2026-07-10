<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        $this->createCountriesTable();
        $this->createStatesTable();
        $this->createCitiesTable();
        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('cities');
        Schema::dropIfExists('states');
        Schema::dropIfExists('countries');
        Schema::enableForeignKeyConstraints();
    }

    private function createCountriesTable(): void
    {
        Schema::create('countries', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->json('name');
            $table->json('slug');
            $table->string('iso2', 2);
            $table->string('iso3', 3)
                ->nullable();
            $table->string('numeric_code', 3)
                ->nullable();
            $table->string('phone_code', 16)
                ->nullable();
            $table->string('currency_code', 3)
                ->nullable();
            $table->decimal('latitude', 10, 7)
                ->nullable();
            $table->decimal('longitude', 10, 7)
                ->nullable();
            $table->unsignedBigInteger('position');
            $table->boolean('status')
                ->default(false);
            $table->timestampsTz();
            $table->softDeletesTz();

            $table->unique(['tenant_id', 'iso2']);
            $table->index(['tenant_id', 'position']);
            $table->index(['tenant_id', 'status']);
        });
    }

    private function createStatesTable(): void
    {
        Schema::create('states', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('country_id');
            $table->json('name');
            $table->json('slug');
            $table->string('code', 32)
                ->nullable();
            $table->string('type', 32)
                ->default('state');
            $table->decimal('latitude', 10, 7)
                ->nullable();
            $table->decimal('longitude', 10, 7)
                ->nullable();
            $table->unsignedBigInteger('position');
            $table->boolean('status')
                ->default(false);
            $table->timestampsTz();
            $table->softDeletesTz();

            $table->index(['tenant_id', 'country_id']);
            $table->index(['tenant_id', 'position']);
            $table->index(['tenant_id', 'status']);
        });
    }

    private function createCitiesTable(): void
    {
        Schema::create('cities', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('country_id');
            $table->unsignedBigInteger('state_id');
            $table->json('name');
            $table->json('slug');
            $table->decimal('latitude', 10, 7)
                ->nullable();
            $table->decimal('longitude', 10, 7)
                ->nullable();
            $table->unsignedBigInteger('position');
            $table->boolean('status')
                ->default(false);
            $table->timestampsTz();
            $table->softDeletesTz();

            $table->index(['tenant_id', 'country_id']);
            $table->index(['tenant_id', 'state_id']);
            $table->index(['tenant_id', 'position']);
            $table->index(['tenant_id', 'status']);
        });
    }
};
