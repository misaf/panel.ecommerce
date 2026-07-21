<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('account_id')
                ->index();
            $table->unsignedBigInteger('plan_id')
                ->index();
            $table->string('status')
                ->index();
            $table->unsignedBigInteger('price')
                ->default(0);
            $table->char('currency_code', 3)
                ->nullable();
            $table->timestampTz('trial_ends_at')
                ->nullable();
            $table->timestampTz('starts_at');
            $table->timestampTz('ends_at')
                ->nullable();
            $table->timestampTz('expiry_reminder_sent_at')
                ->nullable();
            $table->timestampsTz();
            $table->softDeletesTz();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
