<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('blog_post_categories');
        Schema::dropIfExists('blog_posts');

        Schema::enableForeignKeyConstraints();
    }

    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('blog_post_categories', function (Blueprint $table): void {
            $table->id();
            $table->longText('name');
            $table->longText('description')->nullable();
            $table->longText('slug');
            $table->boolean('status')->index();
            $table->timestampsTz();
            $table->softDeletesTz();
        });

        Schema::create('blog_posts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('blog_post_category_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->longText('name');
            $table->longText('description')->nullable();
            $table->longText('slug');
            $table->boolean('status')->index();
            $table->timestampsTz();
            $table->softDeletesTz();
        });

        Schema::enableForeignKeyConstraints();
    }
};
