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
        $this->createTagsTable();
        $this->createTaggablesTable();
        Schema::enableForeignKeyConstraints();
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('taggables');
        Schema::dropIfExists('tags');
        Schema::enableForeignKeyConstraints();
    }

    private function createTagsTable(): void
    {
        Schema::create('tags', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->json('name');
            $table->json('slug');
            $table->string('type')->nullable();
            $table->unsignedBigInteger('position');
            $table->timestamps();
        });
    }

    private function createTaggablesTable(): void
    {
        Schema::create('taggables', function (Blueprint $table): void {
            $table->unsignedBigInteger('tag_id');
            // $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
            $table->morphs('taggable');
            $table->unique(['tag_id', 'taggable_id', 'taggable_type']);
        });
    }
};
