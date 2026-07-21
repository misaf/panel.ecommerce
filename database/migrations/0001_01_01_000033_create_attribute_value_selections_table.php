<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('attribute_value_selections', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('attribute_value_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->morphs('selectable');
            $table->timestampsTz();

            $table->unique(
                ['attribute_value_id', 'selectable_type', 'selectable_id'],
                'attribute_value_selections_unique',
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attribute_value_selections');
    }
};
