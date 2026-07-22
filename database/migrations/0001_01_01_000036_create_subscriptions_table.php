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
            $table->morphs('subscriber');
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
            $table->unsignedBigInteger('active_subscriber_guard')
                ->nullable()
                ->virtualAs("CASE WHEN status = 'active' AND deleted_at IS NULL THEN subscriber_id ELSE NULL END");

            $table->unique(['subscriber_type', 'active_subscriber_guard'], 'subscriptions_active_subscriber_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
