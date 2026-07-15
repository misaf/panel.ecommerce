<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Misaf\VendraSupport\Support\TenantSchema;

return new class () extends Migration {
    public function up(): void
    {
        $defaultGuardExpression = TenantSchema::enabled()
            ? 'CASE WHEN is_default THEN tenant_id ELSE NULL END'
            : 'CASE WHEN is_default THEN 1 ELSE NULL END';

        Schema::table('languages', function (Blueprint $table) use ($defaultGuardExpression): void {
            $table->unsignedBigInteger('default_guard')
                ->nullable()
                ->virtualAs($defaultGuardExpression);

            $table->unique('default_guard', 'languages_one_default_unique');
        });

        Schema::table('language_lines', function (Blueprint $table): void {
            $table->unique(
                TenantSchema::tenantIndex(['group', 'key']),
                'language_lines_tenant_group_key_unique',
            );
        });
    }

    public function down(): void
    {
        Schema::table('language_lines', function (Blueprint $table): void {
            $table->dropUnique('language_lines_tenant_group_key_unique');
        });

        Schema::table('languages', function (Blueprint $table): void {
            $table->dropUnique('languages_one_default_unique');
            $table->dropColumn('default_guard');
        });
    }
};
