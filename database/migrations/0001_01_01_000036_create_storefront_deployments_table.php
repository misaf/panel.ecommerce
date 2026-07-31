<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('storefront_deployments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('slug')->unique();
            $table->string('domain')->unique();
            $table->string('theme');
            $table->json('configuration');
            $table->string('status')->index();
            $table->string('provider_reference')->nullable();
            $table->string('image_digest')->nullable();
            $table->timestampTz('requested_at')->nullable();
            $table->timestampTz('deployed_at')->nullable();
            $table->timestampTz('failed_at')->nullable();
            $table->text('error')->nullable();
            $table->timestampsTz();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('storefront_deployments');
    }
};
