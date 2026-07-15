<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Misaf\VendraSupport\Support\TenantSchema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('language_lines', function (Blueprint $table): void {
            $table->dropUnique('language_lines_tenant_group_key_unique');

            $table->string('namespace')
                ->nullable()
                ->after('tenant_id');

            $table->string('namespace_guard')
                ->virtualAs("COALESCE(namespace, '')")
                ->after('namespace');

            $table->unique(
                TenantSchema::tenantIndex(['namespace_guard', 'group', 'key']),
                'language_lines_tenant_namespace_group_key_unique',
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('language_lines', function (Blueprint $table): void {
            $table->dropUnique('language_lines_tenant_namespace_group_key_unique');
            $table->dropColumn(['namespace', 'namespace_guard']);

            $table->unique(
                TenantSchema::tenantIndex(['group', 'key']),
                'language_lines_tenant_group_key_unique',
            );
        });
    }
};
