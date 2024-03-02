<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function down(): void
    {
        Schema::dropIfExists('statuses');
    }

    public function up(): void
    {
        Schema::create('statuses', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->text('reason')->nullable();
            $table->morphs('model');
            $table->timestampsTz();
        });
    }
};
