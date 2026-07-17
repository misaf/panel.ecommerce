<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::withoutForeignKeyConstraints(function (): void {
            $this->createFaqCategoriesTable();
            $this->createFaqsTable();
        });
    }

    private function createFaqCategoriesTable(): void
    {
        Schema::create('faq_categories', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->json('name');
            $table->json('description')
                ->nullable();
            $table->json('slug');
            $table->unsignedBigInteger('position');
            $table->boolean('status')
                ->default(false);
            $table->timestampsTz();
            $table->softDeletesTz();

            $table->index(['tenant_id', 'position']);
            $table->index(['tenant_id', 'status']);
        });
    }

    private function createFaqsTable(): void
    {
        Schema::create('faqs', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->foreignId('faq_category_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->json('name');
            $table->json('description')
                ->nullable();
            $table->json('slug');
            $table->unsignedBigInteger('position');
            $table->boolean('status')
                ->default(false);
            $table->timestampsTz();
            $table->softDeletesTz();

            $table->index(['tenant_id', 'faq_category_id']);
            $table->index(['tenant_id', 'position']);
            $table->index(['tenant_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::withoutForeignKeyConstraints(function (): void {
            Schema::dropIfExists('faqs');
            Schema::dropIfExists('faq_categories');
        });
    }
};
