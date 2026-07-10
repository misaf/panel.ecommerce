<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Creates the fixture table backing the test-only Eloquent models
 * {@see Misaf\VendraActivityLog\Tests\Fixtures\LoggableWidget} and
 * {@see Misaf\VendraActivityLog\Tests\Fixtures\PlainWidget}. This table exists
 * solely for the test suite and is never shipped by the module.
 */
return new class () extends Migration {
    public function up(): void
    {
        Schema::create('activity_log_widgets', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_log_widgets');
    }
};
