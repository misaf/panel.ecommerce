<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Misaf\UserMessenger\Enums\UserMessengerPlatformEnum;

return new class () extends Migration {
    /**
     * @return void
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        $this->createUserMessengersTable();
        Schema::enableForeignKeyConstraints();
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('user_messengers');
        Schema::enableForeignKeyConstraints();
    }

    /**
     * @return void
     */
    private function createUserMessengersTable(): void
    {
        Schema::create('user_messengers', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('platform', [
                UserMessengerPlatformEnum::Telegram->value,
            ]);
            $table->string('key_name');
            $table->string('key_value');
            $table->timestampsTz();
            $table->softDeletesTz();

            // Unique constraints
            $table->unique(['tenant_id', 'user_id', 'platform'], 'user_messengers_tenant_user_platform_unique');

            // Indexes for performance optimization (all queries include tenant_id)
            $table->index(['tenant_id']);
            $table->index(['tenant_id', 'platform']);
            $table->index(['tenant_id', 'user_id', 'key_name']);
            $table->index(['tenant_id', 'platform', 'key_name']);
        });
    }
};
