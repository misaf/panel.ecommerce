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
        $this->createAffiliatesTable();
        $this->createAffiliateUsersTable();
        Schema::enableForeignKeyConstraints();
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('affiliates');
        Schema::dropIfExists('affiliate_users');
        Schema::enableForeignKeyConstraints();
    }

    private function createAffiliatesTable(): void
    {
        Schema::create('affiliates', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->string('description')
                ->nullable();
            $table->string('slug');
            $table->tinyInteger('commission_percent')
                ->default(0);
            $table->boolean('is_processing')
                ->default(false);
            $table->boolean('status')
                ->default(false);
            $table->timestampsTz();
            $table->softDeletesTz();

            $table->index(['tenant_id', 'name']);
            $table->index(['tenant_id', 'slug']);
            $table->index(['tenant_id', 'status']);
        });
    }

    private function createAffiliateUsersTable(): void
    {
        Schema::create('affiliate_users', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('affiliate_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedInteger('commission_earned')
                ->default(0);
            $table->timestampsTz();
            $table->softDeletesTz();

            $table->index(['tenant_id', 'commission_earned']);
        });
    }
};
