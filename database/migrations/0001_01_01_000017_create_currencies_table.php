<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->unsignedBigInteger('tenant_id');
            $table->string('name');
            $table->string('description')
                ->nullable();
            $table->string('slug');
            $table->unsignedBigInteger('position');
            $table->boolean('status')
                ->default(false);
            $table->timestampsTz();
            $table->softDeletesTz();

            $table->index(['tenant_id', 'name']);
            $table->index(['tenant_id', 'slug']);
            $table->index(['tenant_id', 'position']);
            $table->index(['tenant_id', 'status']);
        });
    }

    private function createCurrenciesTable(): void
    {
        Schema::create('currencies', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
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

            $table->index(['tenant_id', 'currency_category_id']);
            $table->index(['tenant_id', 'name']);
            $table->index(['tenant_id', 'slug']);
            $table->index(['tenant_id', 'iso_code']);
            $table->index(['tenant_id', 'is_default']);
            $table->index(['tenant_id', 'position']);
            $table->index(['tenant_id', 'status']);
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
