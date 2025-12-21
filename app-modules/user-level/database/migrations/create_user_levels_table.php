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
        $this->createUserLevelsTable();
        $this->createUserLevelHistoriesTable();
        Schema::enableForeignKeyConstraints();
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('user_levels');
        Schema::dropIfExists('user_level_histories');
        Schema::enableForeignKeyConstraints();
    }

    private function createUserLevelsTable(): void
    {
        Schema::create('user_levels', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->string('name')
                ->index();
            $table->text('description')
                ->nullable();
            $table->string('slug')
                ->index();
            $table->decimal('min_points', 11, 2);
            $table->boolean('status')
                ->index();
            $table->timestampsTz();
            $table->softDeletesTz();
        });
    }

    private function createUserLevelHistoriesTable(): void
    {
        Schema::create('user_level_histories', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('user_level_id');
            $table->timestampsTz();
        });
    }
};
