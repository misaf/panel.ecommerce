<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table): void {
            $table->id();
            $table->string('name')
                ->index();
            $table->string('slug')
                ->index();
            $table->text('description')
                ->nullable();
            $table->unsignedInteger('max_units');
            $table->string('period_unit');
            $table->unsignedInteger('period_count');
            $table->unsignedInteger('grace_days')
                ->default(0);
            $table->unsignedBigInteger('price')
                ->default(0);
            $table->char('currency_code', 3)
                ->nullable();
            $table->unsignedInteger('trial_days')
                ->default(0);
            $table->json('features')
                ->nullable();
            $table->boolean('status')
                ->index();
            $table->timestampsTz();
            $table->softDeletesTz();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
