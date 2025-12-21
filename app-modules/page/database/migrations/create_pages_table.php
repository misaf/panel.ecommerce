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
        $this->dropPageTables();
        Schema::enableForeignKeyConstraints();
    }

    /**
     * @return void
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        $this->createPageTables();
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Create page categories table.
     */
    private function createPageCategoriesTable(): void
    {
        Schema::create('page_categories', function (Blueprint $table): void {
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

    /**
     * Create pages table.
     */
    private function createPagesTable(): void
    {
        Schema::create('pages', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('page_category_id');
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

    /**
     * Create page tables.
     */
    private function createPageTables(): void
    {
        $this->createPageCategoriesTable();
        $this->createPagesTable();
    }

    /**
     * Drop page tables.
     */
    private function dropPageTables(): void
    {
        Schema::dropIfExists('pages');
        Schema::dropIfExists('page_categories');
    }
};
