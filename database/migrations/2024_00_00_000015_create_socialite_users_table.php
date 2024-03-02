<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function down(): void
    {
        Schema::dropIfExists('socialite_users');
    }

    public function up(): void
    {
        Schema::create('socialite_users', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('user_id');
            $table->string('provider');
            $table->string('provider_id');

            $table->timestampsTz();

            $table->unique([
                'provider',
                'provider_id',
            ]);
        });
    }
};
