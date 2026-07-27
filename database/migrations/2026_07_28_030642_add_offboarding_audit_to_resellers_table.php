<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('resellers', function (Blueprint $table): void {
            $table->text('offboarding_reason')
                ->nullable()
                ->after('email');
            $table->timestampTz('offboarded_at')
                ->nullable()
                ->index()
                ->after('offboarding_reason');
        });
    }

    public function down(): void
    {
        Schema::table('resellers', function (Blueprint $table): void {
            $table->dropIndex(['offboarded_at']);
            $table->dropColumn(['offboarding_reason', 'offboarded_at']);
        });
    }
};
