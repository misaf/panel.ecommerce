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
        $this->createLanguagesTable();
        $this->createLanguageTranslatesTable();
        Schema::enableForeignKeyConstraints();
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('languages');
        Schema::dropIfExists('language_translates');
        Schema::enableForeignKeyConstraints();
    }

    private function createLanguagesTable(): void
    {
        Schema::create('languages', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->longText('name');
            $table->longText('description')
                ->nullable();
            $table->longText('slug');
            $table->char('iso_code')
                ->index();
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

    private function createLanguageTranslatesTable(): void
    {
        Schema::create('language_translates', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->string('group')
                ->index();
            $table->string('key');
            $table->json('text');
            $table->timestampsTz();
            $table->softDeletesTz();
        });
    }
};
