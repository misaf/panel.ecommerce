<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('geographical_zones');
        Schema::dropIfExists('geographical_countries');
        Schema::dropIfExists('geographical_states');
        Schema::dropIfExists('geographical_cities');
        Schema::dropIfExists('geographical_neighborhoods');

        Schema::enableForeignKeyConstraints();
    }

    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('geographical_zones', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->index();
            $table->text('description')->nullable();
            $table->string('slug')->index();
            $table->boolean('status')->index();
            $table->timestampsTz();
            $table->softDeletesTz();
        });

        Schema::create('geographical_countries', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('geographical_zone_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('name')->index();
            $table->text('description')->nullable();
            $table->string('slug')->index();
            $table->boolean('status')->index();
            $table->timestampsTz();
            $table->softDeletesTz();
        });

        Schema::create('geographical_states', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('geographical_country_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('name')->index();
            $table->text('description')->nullable();
            $table->string('slug')->index();
            $table->boolean('status')->index();
            $table->timestampsTz();
            $table->softDeletesTz();
        });

        Schema::create('geographical_cities', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('geographical_state_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('name')->index();
            $table->text('description')->nullable();
            $table->string('slug')->index();
            $table->boolean('status')->index();
            $table->timestampsTz();
            $table->softDeletesTz();
        });

        Schema::create('geographical_neighborhoods', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('geographical_city_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('name')->index();
            $table->text('description')->nullable();
            $table->string('slug')->index();
            $table->boolean('status')->index();
            $table->timestampsTz();
            $table->softDeletesTz();
        });

        Schema::enableForeignKeyConstraints();
    }
};
