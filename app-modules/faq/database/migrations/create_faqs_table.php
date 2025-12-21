<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * @return void
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        $this->dropFaqTables();
        Schema::enableForeignKeyConstraints();
    }

    /**
     * @return void
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        $this->createFaqTables();
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Create faq categories table.
     */
    private function createFaqCategoriesTable(): void
    {
        Schema::create('faq_categories', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->longText('name');
            $table->longText('description')
                ->nullable();
            $table->longText('slug');
            $table->boolean('status')
                ->index();
            $table->timestampsTz();
            $table->softDeletesTz();
        });
    }

    /**
     * Create faqs table.
     */
    private function createFaqsTable(): void
    {
        Schema::create('faqs', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('faq_category_id');
            $table->longText('name');
            $table->longText('description')
                ->nullable();
            $table->longText('slug');
            $table->unsignedInteger('position')
                ->index();
            $table->boolean('status')
                ->index();
            $table->timestampsTz();
            $table->softDeletesTz();
        });
    }

    /**
     * Create faq tables.
     */
    private function createFaqTables(): void
    {
        $this->createFaqCategoriesTable();
        $this->createFaqsTable();
    }

    /**
     * Drop faq tables.
     */
    private function dropFaqTables(): void
    {
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('faq_categories');
    }
};
