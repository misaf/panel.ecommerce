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
        $this->createCurrencyCategoriesTable();
        $this->createCurrenciesTable();
        Schema::enableForeignKeyConstraints();
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('currencies');
        Schema::dropIfExists('currency_categories');
        Schema::enableForeignKeyConstraints();
    }

    private function createCurrenciesTable(): void
    {
        Schema::create('currencies', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('currency_category_id');
            $table->string('name');
            $table->string('description')
                ->nullable();
            $table->string('slug')
                ->index();
            $table->char('iso_code')
                ->index();
            $table->string('conversion_rate');
            $table->string('decimal_place');
            $table->integer('buy_price');
            $table->integer('sell_price');
            $table->boolean('is_default')
                ->index();
            $table->unsignedInteger('position')
                ->index();
            $table->boolean('status')
                ->index();
            $table->timestampsTz();
            $table->softDeletesTz();
        });
    }

    private function createCurrencyCategoriesTable(): void
    {
        Schema::create('currency_categories', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->string('name');
            $table->string('description')
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
