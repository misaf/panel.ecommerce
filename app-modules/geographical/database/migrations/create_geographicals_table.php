<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * @return void
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        $this->createGeographicalZonesTable();
        $this->createGeographicalCountriesTable();
        $this->createGeographicalStatesTable();
        $this->createGeographicalCitiesTable();
        $this->createGeographicalNeighborhoodsTable();
        Schema::enableForeignKeyConstraints();
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('geographical_neighborhoods');
        Schema::dropIfExists('geographical_cities');
        Schema::dropIfExists('geographical_states');
        Schema::dropIfExists('geographical_countries');
        Schema::dropIfExists('geographical_zones');
        Schema::enableForeignKeyConstraints();
    }

    private function createGeographicalCitiesTable(): void
    {
        Schema::create('geographical_cities', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('geographical_state_id');
            $table->string('name')
                ->index();
            $table->text('description')
                ->nullable();
            $table->string('slug')
                ->index();
            $table->boolean('status')
                ->index();
            $table->timestampsTz();
            $table->softDeletesTz();
        });
    }

    private function createGeographicalCountriesTable(): void
    {
        Schema::create('geographical_countries', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('geographical_zone_id');
            $table->string('name')
                ->index();
            $table->text('description')
                ->nullable();
            $table->string('slug')
                ->index();
            $table->boolean('status')
                ->index();
            $table->timestampsTz();
            $table->softDeletesTz();
        });
    }

    private function createGeographicalNeighborhoodsTable(): void
    {
        Schema::create('geographical_neighborhoods', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('geographical_city_id');
            $table->string('name')
                ->index();
            $table->text('description')
                ->nullable();
            $table->string('slug')
                ->index();
            $table->boolean('status')
                ->index();
            $table->timestampsTz();
            $table->softDeletesTz();
        });
    }

    private function createGeographicalStatesTable(): void
    {
        Schema::create('geographical_states', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('geographical_country_id');
            $table->string('name')
                ->index();
            $table->text('description')
                ->nullable();
            $table->string('slug')
                ->index();
            $table->boolean('status')
                ->index();
            $table->timestampsTz();
            $table->softDeletesTz();
        });
    }

    private function createGeographicalZonesTable(): void
    {
        Schema::create('geographical_zones', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->string('name')
                ->index();
            $table->text('description')
                ->nullable();
            $table->string('slug')
                ->index();
            $table->boolean('status')
                ->index();
            $table->timestampsTz();
            $table->softDeletesTz();
        });
    }
};
